<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

/**
 * Constants for calculating the score
 */
define("pontosPorBOLSA", 5);
define("pontosPorTRABALHO", 5);
define("pontosPorRESUMO", 1);
define("pontosPorARTIGO", 10);
define("pontosPorLIVRO", 20);
define("pontosPorTEXTO", 5);
define("anoMaximo", 2);
define("TRABALHO", 0);
define("RESUMO", 1);
define("ARTIGO", 2);
define("LIVRO", 3);
define("TEXTO", 4);

class Lattes extends Model
{

	/* Class attributes */
    private $points;
    private $file;
    private $year;

	function __construct($file, $year)
	{
		$this->file = $file;
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
    	/**
    	 * Constants for REGEX used on the XML parsing
    	 * PHP is so crazy you have to use "&lt;" instead of "<" or it won't create the string
    	 */
    	$doiREGEX = "http:\/\/dx\.doi\.org\/.+?";
    	$projetosPesquisaREGEX = htmlentities("/<PROJETO-DE-PESQUISA[\S\s]+?<\/PROJETO-DE-PESQUISA>/");
    	$trabalhosEventosREGEX = htmlentities("/<TRABALHO-EM-EVENTOS[\S\s]+?<\/TRABALHO-EM-EVENTOS>/");
    	$artigosCompletosREGEX = htmlentities("/<ARTIGO-PUBLICADO[\S\s]+?<\/ARTIGO-PUBLICADO>/");
    	$livrosPublicadosREGEX = htmlentities("/<LIVRO-PUBLICADO-OU-ORGANIZADO[\S\s]+?<\/LIVRO-PUBLICADO-OU-ORGANIZADO>/");
    	$textosJornaisREGEX = htmlentities("/<TEXTO-EM-JORNAL-OU-REVISTA[\S\s]+?<\/TEXTO-EM-JORNAL-OU-REVISTA>/");

    	preg_match_all($projetosPesquisaREGEX, $this->file, $projetosPesquisa);
    	preg_match_all($trabalhosEventosREGEX, $this->file, $trabalhosEventos);
    	preg_match_all($artigosCompletosREGEX, $this->file, $artigosCompletos);
    	preg_match_all($livrosPublicadosREGEX, $this->file, $livrosPublicados);
    	preg_match_all($textosJornaisREGEX, $this->file, $textosJornais);

    	// echo count($projetosPesquisa[0]);
    	// echo $aaaa;
    	for ($i=0; $i < count($projetosPesquisa[0]); $i++) { 
    		preg_match_all("/ANO-INICIO=\"\d{4}\"/", $projetosPesquisa[0][$i], $date);
    		$anoInicioProjeto = substr($date[0][0], 12, 4);
			if(validateYear($year, $anoInicioProjeto)){
				$this->points['bolsas'] += pontosPorBOLSA;
			}
    	}

    	for ($i=0; $i < count($trabalhosEventos[0]); $i++) { 
    		preg_match_all("/ANO-DO-TRABALHO=\"\d{4}\"/", $trabalhosEventos[0][$i], $date);
    		$anoTrabalho = substr($date[0][0], 12, 4);
			if(validateYear($year, $anoTrabalho)){
				if(preg_match("/NATUREZA=\"RESUMO\"/", $trabalhosEventos[0][$i])) {
					$this->points['trabalhos'] += pontosPorRESUMO;
				} else {
					$this->points['trabalhos'] += pontosPorTRABALHO;
				}
			}
    	}

    	for ($i=0; $i < count($artigosCompletos[0]); $i++) { 
    		preg_match_all("/ANO-DO-ARTIGO=\"\d{4}\"/", $artigosCompletos[0][$i], $date);
    		$anoDoArtigo = substr($date[0][0], 12, 4);
			if(validateYear($year, $anoDoArtigo) && getDoi($artigosCompletos[0][$i], ARTIGO)){
				$this->points['artigos'] += pontosPorARTIGO;
			}
    	}

    	for ($i=0; $i < count($livrosPublicados[0]); $i++) { 
    		preg_match_all("/ANO=\"\d{4}\"/", $livrosPublicados[0][$i], $date);
    		$anoDoLivro = substr($date[0][0], 12, 4);
			if(validateYear($year, $anoDoLivro) && getDoi($livrosPublicados[0][$i], LIVRO)){
				$this->points['livros'] += pontosPorLIVRO;
			}
    	}

    	for ($i=0; $i < count($textosJornais[0]); $i++) { 
    		preg_match_all("/ANO-DO-TEXTO=\"\d{4}\"/", $textosJornais[0][$i], $date);
    		$anoDoTexto = substr($date[0][0], 12, 4);
			if(validateYear($year, $anoDoTexto) && getDoi($textosJornais[0][$i], LIVRO)){
				$this->points['textos'] += pontosPorTEXTO;
			}
    	}

    	// $this->points['bolsas'] = count($projetosPesquisa[0]) * pontosPorBOLSA;
    	// $this->points['trabalhos'] = count($trabalhosEventos[0]) * pontosPorTRABALHO;
    	// $this->points['artigos'] = count($artigosCompletos[0]) * pontosPorARTIGO;
    	// $this->points['livros'] = count($livrosPublicados[0]) * pontosPorLIVRO;
    	// $this->points['textos'] = count(($textosJornais[0])) * pontosPorTEXTO;
        return $this->points;
    }

    /**
     * Download DOI files.
     *
     * @return boolean
     */
    private function getDoi($publicationDiv, $publicationtype)
    {
        return true;
    }
}
