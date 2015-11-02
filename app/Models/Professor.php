<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;


class Professor extends Model
{

    /* Constants to calculate the score */
    public static $RESEARCH_POINTS = 5;
    public static $JOB_POINTS = 5;
    public static $ABSTRACT_POINTS = 1;
    public static $PAPER_POINTS = 10;
    public static $BOOK_POINTS = 20;
    public static $TEXT_POINTS = 5;
    public static $MAX_YEAR = 2;
    public static $DOI_PREFIX = "http://dx.doi.org/";

    /* Class attributes */
    private $name;
	private $CPF;
	private $password;
	private $lattesFile;
	private $rank;
	private $year;
    private $points;
    private $pointsOrigin;

	function __construct($attributes = array())
	{
		$this->name = $attributes['name'];
		$this->year = $attributes['year'];
        $file = utf8_encode(file_get_contents($attributes['lattes']->getRealPath()));
        $this->lattesFile = new Crawler($file);
		$this->points = array('research' => 0,
							  'jobs' => 0,
							  'abstracts' => 0,
							  'papers' => 0,
							  'books' => 0,
							  'texts' => 0);

		$this->pointsOrigin = array('research' => array(),
									'jobs' => array(),
									'abstracts' => array(),
									'papers' => array(),
									'books' => array(),
									'texts' => array());
	}

	/* Main User method to make report
	 */
	public function makeReport()
	{
		$this->parseXML();
	}

    /**
     * XML parsing.
     *
     * @return Points.
     */
    private function parseXML()
    {
    	$researchPATH = '//CURRICULO-VITAE/DADOS-GERAIS/ATUACOES-PROFISSIONAIS/ATIVIDADES-DE-PARTICIPACAO-EM-PROJETO/PARTICIPACAO-EM-PROJETO';
    	$eventsPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/TRABALHOS-EM-EVENTOS';
    	$abstractsPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/ARTIGOS-PUBLICADOS';
    	$booksPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/LIVROS-E-CAPITULOS/LIVROS-PUBLICADOS-OU-ORGANIZADOS';
    	$textsPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/TEXTOS-EM-JORNAIS-OU-REVISTAS';

    	try{
    		$this->lattesFile->filterXPath($researchPATH)
					   ->children()
					   ->nextAll()
					   ->each(function ($node, $i)
						{
						  echo $node->children()->attr('ANO-INICIO')."\n";
						});
		}catch(\Exception $e){
			var_dump($e->getMessage());
		}

    	try{
    		$this->lattesFile->filterXPath($eventsPATH)
					   ->children()
					   ->nextAll()
					   ->each(function ($node, $i)
						{
							$DOI = utf8_decode($node->children()->attr('DOI'));
							$jobYear = $node->children()->attr('ANO-DO-TRABALHO');
							$title = utf8_decode($node->children()->attr('TITULO-DO-TRABALHO'));
							if(($DOI) && ($this->validateYear($jobYear) && $this->saveFile($DOI, $title))){
								if($node->children()->attr('NATUREZA') === 'RESUMO'){
									$this->points['jobs'] += self::$ABSTRACT_POINTS;
								}else {
									$this->points['jobs'] += self::$JOB_POINTS;
								}
								$origin = array('title' => $title, 'link' => self::$DOI_PREFIX.$DOI);
								array_push($this->pointsOrigin['jobs'], $origin);							}
						});
		}catch(\Exception $e){
			var_dump($e->getMessage());
		}

    	try{
    		$this->lattesFile->filterXPath($abstractsPATH)
					   ->children()
					   ->nextAll()
					   ->each(function ($node, $i)
						{
							$DOI = utf8_decode($node->children()->attr('DOI'));
							$paperYear = $node->children()->attr('ANO-DO-ARTIGO');
							$title = utf8_decode($node->children()->attr('TITULO-DO-ARTIGO'));
						  	if(($DOI) && ($this->validateYear($paperYear) && $this->saveFile($DOI, $title))){
						  		$this->points['papers'] += self::$PAPER_POINTS;
						  		$origin = array('title' => $title, 'link' => self::$DOI_PREFIX.$DOI);
					  			array_push($this->pointsOrigin['papers'], $origin);
						  	}
						});
		}catch(\Exception $e){
			var_dump($e->getMessage());
		}

    	try{
    		$this->lattesFile->filterXPath($booksPATH)
					   ->children()
					   ->nextAll()
					   ->each(function ($node, $i)
						{
						  	if($node->children()->attr('DOI')){
								echo $node->children()->attr('ANO')."\n";
							}
						});
		}catch(\Exception $e){
			var_dump($e->getMessage());
		}

    	try{
    		$this->lattesFile->filterXPath($textsPATH)
					   ->children()
					   ->nextAll()
					   ->each(function ($node, $i)
						{
						  	if($node->children()->attr('DOI')){
						  		echo $node->children()->attr('ANO-DO-TEXTO')."\n";
						  	}
						});
		}catch(\Exception $e){
			var_dump($e->getMessage());
		}

        return $this->points;
    }

    /**
     * Download lattesFiles
     *
     * @return boolean
     */
	private function saveFile ($doi, $title){
		$link = self::$DOI_PREFIX . $doi;
		$client = new Client();
		$crawler = $client->request('GET', $link);
		if($crawler){
			$html = '';
			foreach($crawler as $domElement){
			    $html .= $domElement->ownerDocument->saveHTML($domElement);
			}
			$file = fopen(__DIR__.'/../../storage/app/professor/'.$title.'.html', "w");
			fwrite($file, utf8_encode($html));
			fclose($file);
			return true;
		}
		return false;
	}

    /**
     * The publication year must be in the interval [year, year+2]
     *
     * @return boolean
     */
    private function validateYear($publicationYear)
    {
    	$maxYear = $this->year + self::$MAX_YEAR;
		return (($publicationYear >= $this->year) && ($publicationYear <= $maxYear));
    }

    /**
     * Papers and others files will be returned to professor
     *
     * @return boolean
     */
    private function download($professorName, $filePath)
    {
            $headers = array(
                  'Content-Type: application/zip'
                );
            return Response::download($filePath, $professorName + ".zip", $headers);
    }

    /**
	 * Getters and Setters
	 *
	 * @return attribute
     */

    public function getName()
    {
    	return $this->name;
    }

    public function getYear()
    {
    	return $this->year;
    }

    public function getPoints($category)
    {
    	return $this->points[$category];
    }

    public function showOrigin($category)
    {
    	for($i=0; $i < count($this->pointsOrigin[$category]); $i++){
    		echo "<a href=\"".$this->pointsOrigin[$category][$i]['link']."\">"
    				.$this->pointsOrigin[$category][$i]['title'].
				  "</a><br />";
    	}
    }
}
