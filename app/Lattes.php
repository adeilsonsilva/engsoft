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

class Lattes extends Model
{

	/* Class attributes */
    private $points;
    private $file;
    private $year;

	function __construct($file, $year)
	{
		$this->file = new Crawler($file);
		$this->year = $year;
		$this->points = array('bolsas' => 0, 'trabalhos' => 0, 'resumos' => 0, 'artigos' => 0, 'livros' => 0, 'textos' => 0);
	}

    /**
     * XML parsing.
     *
     * @return Points.
     */
    public function parseXML()
    {
    	$projetosPesquisaPATH = '//CURRICULO-VITAE/DADOS-GERAIS/ATUACOES-PROFISSIONAIS/ATIVIDADES-DE-PARTICIPACAO-EM-PROJETO/PARTICIPACAO-EM-PROJETO';
    	$trabalhosEventosPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/TRABALHOS-EM-EVENTOS';
    	$artigosCompletosPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/ARTIGOS-PUBLICADOS';
    	$livrosPublicadosPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/LIVROS-E-CAPITULOS/LIVROS-PUBLICADOS-OU-ORGANIZADOS';
    	$textosJornaisPATH = '//CURRICULO-VITAE/PRODUCAO-BIBLIOGRAFICA/TEXTOS-EM-JORNAIS-OU-REVISTAS';

    	/* TO DO: Solve InvalidArgumentException 
		 * Save doi links to show on results page
    	 */

    	/*
    	$this->file->filterXPath($projetosPesquisaPATH)
				   ->children()
				   ->nextAll()
				   ->each(function ($node, $i)
					{
					  echo $node->children()->attr('ANO-INICIO')."\n";
					});
		*/
    	$this->file->filterXPath($trabalhosEventosPATH)
				   ->children()
				   ->nextAll()
				   ->each(function ($node, $i)
					{
						$anoTrabalho = $node->children()->attr('ANO-DO-TRABALHO');
						if($this->validateYear($anoTrabalho)){
							if($node->children()->attr('NATUREZA') === 'RESUMO'){
								$this->points['trabalhos'] += pontosPorRESUMO;
							}else {
								$this->points['trabalhos'] += pontosPorTRABALHO;
							}
						}
					});

    	$this->file->filterXPath($artigosCompletosPATH)
				   ->children()
				   ->nextAll()
				   ->each(function ($node, $i)
					{
						$DOI = $node->children()->attr('DOI');
						$anoDoArtigo = $node->children()->attr('ANO-DO-ARTIGO');
						$titulo = $node->children()->attr('TITULO-DO-ARTIGO');
					  	if(($DOI) && ($this->validateYear($anoDoArtigo) && $this->saveFile($DOI, $titulo))){
					  		$this->points['artigos'] += pontosPorARTIGO;
					  	}
					});
		/*
    	$this->file->filterXPath($livrosPublicadosPATH)
				   ->children()
				   ->nextAll()
				   ->each(function ($node, $i)
					{
					  	if($node->children()->attr('DOI')){
					  		echo $node->children()->attr('ANO')."\n";
					  	}
					});

    	$this->file->filterXPath($textosJornaisPATH)
				   ->children()
				   ->nextAll()
				   ->each(function ($node, $i)
					{
					  	if($node->children()->attr('DOI')){
					  		echo $node->children()->attr('ANO-DO-TEXTO')."\n";
					  	}
					});
		*/
        return $this->points;
    }

    /**
     * Download files
     *
     * @return boolean
     */
	private function saveFile ($doi, $title){
		$doiPrefix = "http://dx.doi.org/";
		$link = $doiPrefix . $doi;
		$client = new Client();
		$crawler = $client->request('GET', $link);
		if ($crawler) {
			$html = '';
			foreach ($crawler as $domElement) {
			    $html .= $domElement->ownerDocument->saveHTML($domElement);
			}
			$file = fopen(__DIR__.'/../storage/app/user/'.$title.'.html', "w");
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
}
