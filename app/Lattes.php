<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lattes extends Model
{
	/**
	 * Constantes definidas para o cálculo da pontuação
	 */
	const pontosPorBOLSA = 5;
	const pontosPorTRABALHO = 5;
	const pontosPorRESUMO = 1;
	const pontosPorARTIGO = 10;
	const pontosPorLIVRO = 20;
	const pontosPorTEXTO = 5;
	const anoMaximo = 2;

	/**
	 * Constantes definidas para as expressões regulares para busca no arquivo xml
	 */
	const doiREGEX = "\"http:\/\/dx\.doi\.org\/.+?\"";
	const projetosPesquisaREGEX = "<PROJETO-DE-PESQUISA[\S\s]+?<\/PROJETO-DE-PESQUISA>";
	const trabalhosEventosREGEX = "<TRABALHO-EM-EVENTOS[\S\s]+?<\/TRABALHO-EM-EVENTOS>";
	const artigosCompletosREGEX = "<ARTIGO-PUBLICADO[\S\s]+?<\/ARTIGO-PUBLICADO>";
	const livrosPublicadosREGEX = "<LIVRO-PUBLICADO-OU-ORGANIZADO[\S\s]+?<\/LIVRO-PUBLICADO-OU-ORGANIZADO>";
	const textosJornaisREGEX = "<TEXTO-EM-JORNAL-OU-REVISTA[\S\s]+?<\/TEXTO-EM-JORNAL-OU-REVISTA>";

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
    	$this->points['bolsas'] = 1000;
    	$this->points['resumos'] = 342423;
    	$this->points['textos'] = 55;
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
