<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

/**
 * Constants to calculate the score
 */
define("pontosPorBOLSA", 5);
define("pontosPorTRABALHO", 5);
define("pontosPorRESUMO", 1);
define("pontosPorARTIGO", 10);
define("pontosPorLIVRO", 20);
define("pontosPorTEXTO", 5);
define("anoMaximo", 2);

class User extends Model
{

	/* Class attributes */
	private $name;
	private $CPF;
	private $password;
	private $lattesFile;
	private $rank;
	private $year;
    private $points;
    private $doiPrefix;
    private $pointsOrigin;

	function __construct($name, $file, $year)
	{
		$this->name = $name;
		$this->lattesFile = new Crawler($file);
		$this->year = $year;
		$this->doiPrefix = "http://dx.doi.org/";
		$this->points = array('bolsas' => 0,
							  'trabalhos' => 0,
							  'resumos' => 0,
							  'artigos' => 0,
							  'livros' => 0,
							  'textos' => 0);

		$this->pointsOrigin = array('bolsas' => array(),
									'trabalhos' => array(),
									'resumos' => array(),
									'artigos' => array(),
									'livros' => array(),
									'textos' => array());
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
    	$projetosPesquisaPATH = '//CURRICULO-VITAE/DADOS-GERAIS/ATUACOES-PROFISSIONAIS/ATIVIDADES-DE-PARTICIPACAO-EM-PROJETO/PARTICIPACAO-EM-PROJETO';
    	$trabalhosEventosPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/TRABALHOS-EM-EVENTOS';
    	$artigosCompletosPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/ARTIGOS-PUBLICADOS';
    	$livrosPublicadosPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/LIVROS-E-CAPITULOS/LIVROS-PUBLICADOS-OU-ORGANIZADOS';
    	$textosJornaisPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/TEXTOS-EM-JORNAIS-OU-REVISTAS';

    	/* TO DO: Solve InvalidArgumentException
		 * Save doi links to show on results page
    	 */

    	try{
    		$this->lattesFile->filterXPath($projetosPesquisaPATH)
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
    		$this->lattesFile->filterXPath($trabalhosEventosPATH)
					   ->children()
					   ->nextAll()
					   ->each(function ($node, $i)
						{
							$DOI = utf8_decode($node->children()->attr('DOI'));
							$anoTrabalho = $node->children()->attr('ANO-DO-TRABALHO');
							$titulo = utf8_decode($node->children()->attr('TITULO-DO-TRABALHO'));
							// echo $node->children()->attr('TITULO-DO-TRABALHO');
							if(($DOI) && ($this->validateYear($anoTrabalho) && $this->saveFile($DOI, $titulo))){
								if($node->children()->attr('NATUREZA') === 'RESUMO'){
									$this->points['trabalhos'] += pontosPorRESUMO;
								}else {
									$this->points['trabalhos'] += pontosPorTRABALHO;
								}
								$origin = array('title' => $titulo, 'link' => $this->doiPrefix.$DOI);
								array_push($this->pointsOrigin['trabalhos'], $origin);							}
						});
		}catch(\Exception $e){
			var_dump($e->getMessage());
		}

    	try{
    		$this->lattesFile->filterXPath($artigosCompletosPATH)
					   ->children()
					   ->nextAll()
					   ->each(function ($node, $i)
						{
							$DOI = utf8_decode($node->children()->attr('DOI'));
							$anoDoArtigo = $node->children()->attr('ANO-DO-ARTIGO');
							$titulo = utf8_decode($node->children()->attr('TITULO-DO-ARTIGO'));
						  	if(($DOI) && ($this->validateYear($anoDoArtigo) && $this->saveFile($DOI, $titulo))){
						  		$this->points['artigos'] += pontosPorARTIGO;
						  		$origin = array('title' => $titulo, 'link' => $this->doiPrefix.$DOI);
					  			array_push($this->pointsOrigin['artigos'], $origin);
						  	}
						});
		}catch(\Exception $e){
			var_dump($e->getMessage());
		}

    	try{
    		$this->lattesFile->filterXPath($livrosPublicadosPATH)
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
    		$this->lattesFile->filterXPath($textosJornaisPATH)
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
		$this->doiPrefix = "http://dx.doi.org/";
		$link = $this->doiPrefix . $doi;
		$client = new Client();
		$crawler = $client->request('GET', $link);
		if($crawler){
			$html = '';
			foreach($crawler as $domElement){
			    $html .= $domElement->ownerDocument->saveHTML($domElement);
			}
			// $filePath = __DIR__.'/../storage/app/user/'.$title.'.html';
			$file = fopen(__DIR__.'/../../storage/app/user/'.$title.'.html', "w");
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
    	$maxYear = $this->year + anoMaximo;
		return (($publicationYear >= $this->year) && ($publicationYear <= $maxYear));
    }

    /**
     * Papers and others files will be returned to user
     *
     * @return boolean
     */
    private function download($userName, $filePath)
    {
            $headers = array(
                  'Content-Type: application/zip',
                );
            return Response::download($filePath, "$userName.zip", $headers);
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
