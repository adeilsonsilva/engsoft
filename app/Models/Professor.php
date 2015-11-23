<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;


class Professor extends Model
{

    /* Constants to calculate the score */
    public static $DOI_PREFIX = "http://dx.doi.org/";
    public static $RESEARCH_POINTS = 5;
    public static $JOB_POINTS = 5;
    public static $ABSTRACT_POINTS = 1;
    public static $PAPER_POINTS = 10;
    public static $BOOK_POINTS = 20;
    public static $TEXT_POINTS = 5;
    public static $MAX_YEAR = 2;
    public static $ELABORADOR_POINTS = 2;
    public static $COORDENADOR_POINTS = 2;
    public static $ORIENTADOR_POINTS = 2;

    /* Class attributes */
    private $name;
	private $CPF;
	private $password;
	private $lattesFile;
	private $rank;
	private $year;
    private $xmlPoints;
    private $xmlPointsOrigin;
    private $siatexPoints;
    private $siatexPointsOrigin;
    private $sapiPoints;
    private $sapiPointsOrigin;
    private $sisbicPoints;
    private $sisbicPointsOrigin;

	function __construct($attributes = array())
	{
		// $this->name = $attributes['name'];
		$this->year = $attributes['year'];
        $file = utf8_encode(file_get_contents($attributes['lattes']->getRealPath()));
        $this->lattesFile = new Crawler($file);
        $this->xmlPoints = array('research' => 0,
                              'jobs' => 0,
                              'abstracts' => 0,
                              'papers' => 0,
                              'books' => 0,
                              'texts' => 0);

        $this->xmlPointsOrigin = array('research' => array(),
                                    'jobs' => array(),
                                    'abstracts' => array(),
                                    'papers' => array(),
                                    'books' => array(),
                                    'texts' => array());

        $this->siatexPoints = array('elaborador' => 0,
                              'coordenador' => 0,
                              'submissao' => 0);

        $this->siatexPointsOrigin = array('elaborador' => array(),
                                    'coordenador' => array(),
                                    'submissao' => array());

        $this->sapiPoints = array('coordenador' => 0);

		$this->sapiPointsOrigin = array('coordenador' => array());
	}

	/* Main User method to make report
	 */
	public function makeReport()
	{
        $this->parseXML();
        $this->parseSIATEX();
        $this->parseSAPI();
        $this->parseSISBIC();
	}

    public function parseSIATEX()
    {
        /* Cálculo de pontuação por elaboração ode projetos */
        $count = DB::connection('SIATEX')->table('propostas')
                            ->select('titulo')
                            ->whereBetween('ano', array($this->year, $this->year + self::$MAX_YEAR))
                            ->where('elaborador', '=', $this->name)
                            ->limit(5)
                            ->count();

        $this->siatexPointsOrigin['elaborador'] = DB::connection('SIATEX')->table('propostas')
                                                    ->select('titulo')
                                                    ->whereBetween('ano', array($this->year, $this->year + self::$MAX_YEAR))
                                                    ->where('elaborador', '=', $this->name)
                                                    ->limit(5)
                                                    ->get();

        $this->siatexPoints['elaborador'] = $count * self::$ELABORADOR_POINTS;

        /* Cálculo de pontuação por coordenação de projetos */
        $count = DB::connection('SIATEX')->table('propostas')
                            ->select('titulo')
                            ->whereBetween('ano', array($this->year, $this->year + self::$MAX_YEAR))
                            ->where('coordenador', '=', $this->name)
                            ->limit(1)
                            ->count();

        $this->siatexPointsOrigin['coordenador'] = DB::connection('SIATEX')->table('propostas')
                                                    ->select('titulo')
                                                    ->whereBetween('ano', array($this->year, $this->year + self::$MAX_YEAR))
                                                    ->where('coordenador', '=', $this->name)
                                                    ->limit(1)
                                                    ->get();

        $this->siatexPoints['coordenador'] = $count * self::$COORDENADOR_POINTS;
    }

    public function parseSAPI()
    {

        /* Cálculo de pontuação por coordenação de projetos */
        $count = DB::connection('SAPI')->table('projetos')
                            ->select('titulo')
                            ->whereBetween('ano', array($this->year, $this->year + self::$MAX_YEAR))
                            ->where('coordenador', '=', $this->name)
                            ->limit(5)
                            ->count();

        $this->sapiPointsOrigin['coordenador'] = DB::connection('SAPI')->table('projetos')
                                                    ->select('titulo')
                                                    ->whereBetween('ano', array($this->year, $this->year + self::$MAX_YEAR))
                                                    ->where('coordenador', '=', $this->name)
                                                    ->limit(5)
                                                    ->get();

        $this->sapiPoints['coordenador'] = $count * self::$COORDENADOR_POINTS;
    }

    public function parseSISBIC()
    {
        /* Cálculo de pontuação por orientação de bolsas */
        $count = DB::connection('SISBIC')->table('planos')
                            ->whereBetween('ano', array($this->year, $this->year + self::$MAX_YEAR))
                            ->where('orientador', '=', $this->name)
                            ->limit(5)
                            ->count();

        $this->sisbicPointsOrigin['orientador'] = DB::connection('SISBIC')->table('planos')
                                                    ->whereBetween('ano', array($this->year, $this->year + self::$MAX_YEAR))
                                                    ->where('orientador', '=', $this->name)
                                                    ->limit(5)
                                                    ->get();

        $this->sisbicPoints['orientador'] = $count * self::$ORIENTADOR_POINTS;
    }

    /**
     * XML file parsing.
     *
     */
    private function parseXML()
    {
        $namePATH = '//CURRICULO-VITAE/DADOS-GERAIS';
    	$researchPATH = '//CURRICULO-VITAE/DADOS-GERAIS/ATUACOES-PROFISSIONAIS/ATIVIDADES-DE-PARTICIPACAO-EM-PROJETO/PARTICIPACAO-EM-PROJETO';
    	$eventsPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/TRABALHOS-EM-EVENTOS';
    	$abstractsPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/ARTIGOS-PUBLICADOS';
    	$booksPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/LIVROS-E-CAPITULOS/LIVROS-PUBLICADOS-OU-ORGANIZADOS';
    	$textsPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/TEXTOS-EM-JORNAIS-OU-REVISTAS';

        try{
            $this->lattesFile->filterXPath($namePATH)
                       ->each(function ($node, $i)
                        {
                            $this->name = utf8_decode($node->attr('NOME-COMPLETO'));
                        });
        }catch(\Exception $e){
            var_dump($e->getMessage());
        }

    	try{
    		$this->lattesFile->filterXPath($researchPATH)
					   ->children()
					   ->nextAll()
					   ->each(function ($node, $i)
						{
                            $DOI = utf8_decode($node->children()->attr('DOI'));
                            $researchYear = $node->children()->attr('ANO-INICIO');
                            $title = utf8_decode($node->children()->attr('NOME-DO-PROJETO'));
                            if(($DOI) && ($this->validateYear($researchYear) && $this->saveFile($DOI, $title))){
                                $researchYearEnd = $node->children()->attr('ANO-FIM');
                                if (($researchYearEnd) && $researchYearEnd - $researchYear >= 2){
                                    $this->xmlPoints['research'] += self::$PAPER_POINTS;
                                    $origin = array('title' => $title, 'link' => self::$DOI_PREFIX.$DOI);
                                    array_push($this->xmlPointsOrigin['research'], $origin);
                                }
                            }
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
									$this->xmlPoints['jobs'] += self::$ABSTRACT_POINTS;
								}else {
									$this->xmlPoints['jobs'] += self::$JOB_POINTS;
								}
								$origin = array('title' => $title, 'link' => self::$DOI_PREFIX.$DOI);
								array_push($this->xmlPointsOrigin['jobs'], $origin);							}
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
							$abstractYear = $node->children()->attr('ANO-DO-ARTIGO');
							$title = utf8_decode($node->children()->attr('TITULO-DO-ARTIGO'));
						  	if(($DOI) && ($this->validateYear($abstractYear) && $this->saveFile($DOI, $title))){
						  		$this->xmlPoints['papers'] += self::$PAPER_POINTS;
						  		$origin = array('title' => $title, 'link' => self::$DOI_PREFIX.$DOI);
					  			array_push($this->xmlPointsOrigin['papers'], $origin);
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
                            $DOI = utf8_decode($node->children()->attr('DOI'));
                            $bookYear = $node->children()->attr('ANO');
                            $title = utf8_decode($node->children()->attr('TITULO-DO-LIVRO'));
						  	if(($DOI) && ($this->validateYear($bookYear) && $this->saveFile($DOI, $title))){
					            $this->xmlPoints['books'] += self::$BOOK_POINTS;
                                $origin = array('title' => $title, 'link' => self::$DOI_PREFIX.$DOI);
                                array_push($this->xmlPointsOrigin['books'], $origin);
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
                            $DOI = utf8_decode($node->children()->attr('DOI'));
                            $textYear = $node->children()->attr('ANO-DO-TEXTO');
                            $title = utf8_decode($node->children()->attr('TITULO-DO-TEXTO'));
                            if(($DOI) && ($this->validateYear($textYear) && $this->saveFile($DOI, $title))){
                                $this->xmlPoints['texts'] += self::$TEXT_POINTS;
                                $origin = array('title' => $title, 'link' => self::$DOI_PREFIX.$DOI);
                                array_push($this->xmlPointsOrigin['texts'], $origin);
                            }
						});
		}catch(\Exception $e){
			var_dump($e->getMessage());
		}
    }

    /**
     * Download lattesFiles
     *
     * @return boolean
     */
	private function saveFile ($doi, $title){
		// $link = self::$DOI_PREFIX . $doi;
		// $client = new Client();
		// $crawler = $client->request('GET', $link);
		// if($crawler){
		// 	$html = '';
		// 	foreach($crawler as $domElement){
		// 	    $html .= $domElement->ownerDocument->saveHTML($domElement);
		// 	}
		// 	$file = fopen(__DIR__.'/../../storage/app/professor/'.$title.'.html', "w");
		// 	fwrite($file, utf8_encode($html));
		// 	fclose($file);
		// 	return true;
		// }
		// return false;
        return true;
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

    public function getData($data)
    {
        return $this->$data;
    }

    public function getXMLPoints($category)
    {
        return $this->xmlPoints[$category];
    }

    public function showXMLOrigin($category)
    {
        for($i=0; $i < count($this->xmlPointsOrigin[$category]); $i++){
            echo "<a target=\"__blank\" href=\"".$this->xmlPointsOrigin[$category][$i]['link']."\">"
                    .$this->xmlPointsOrigin[$category][$i]['title'].
                  "</a><br />";
        }
    }

    public function getSIATEXPoints($category)
    {
        return $this->siatexPoints[$category];
    }

    public function showSIATEXOrigin($category)
    {
        for($i = 0; $i < count($this->siatexPointsOrigin[$category]); $i++){
            echo "<p>"
                    . "Nome da Proposta: "
                    .$this->siatexPointsOrigin[$category][$i]->titulo.
                  "</p>";
        }
    }

    public function getSAPIPoints($category)
    {
        return $this->sapiPoints[$category];
    }

    public function showSAPIOrigin($category)
    {
        for($i = 0; $i < count($this->sapiPointsOrigin[$category]); $i++){
            echo "<p>"
                    . "Nome do Projeto: "
                    .$this->sapiPointsOrigin[$category][$i]->titulo.
                  "</p>";
        }
    }

    public function getSISBICPoints($category)
    {
        return $this->sisbicPoints[$category];
    }

    public function showSISBICOrigin($category)
    {
        for($i = 0; $i < count($this->sisbicPointsOrigin[$category]); $i++){
            echo "<p>"
                    . "Nome do Plano: "
                    .$this->sisbicPointsOrigin[$category][$i]->titulo.
                  "</p>";
        }
    }

}
