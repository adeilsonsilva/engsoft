<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8">
		<title>EngSoft</title>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
	   	<link href="{{ asset('css/main.css') }}" rel="stylesheet">
	</head>
	<body>
		<div class="page-header">
			<h1>Relatório</h1>
			<h2>
				<?php echo $professor->getData('name'); ?>
			</h2>
			<h4>
				Ano de início da Progressão: <?php echo $professor->getData('year'); ?>
			</h4>
		</div>
		<div class="form-group">
			<h3 class="dropdown" onclick="drop(this, '.Lattes')">Pontuação calculada utilizando informações do Lattes. <i class="fa fa-chevron-down"></i></h3>
		</div>
		<div class='Lattes'>
			<div class="container">
				<div class="form-group">
					<h4>
						Pontos por publicação de trabalhos completos em anais de congressos: <?php echo $professor->getXMLPoints('jobs'); ?>
						<i class="fa fa-plus-square">
							<div class="doi-links">
								<?php $professor->showXMLOrigin('jobs'); ?>
							</div>
						</i>
					</h4>
				</div>
				<div class="form-group">
					<h4>
						Pontos por resumos: <?php echo $professor->getXMLPoints('abstracts'); ?>
						<i class="fa fa-plus-square">
							<div class="doi-links">
								<?php $professor->showXMLOrigin('abstracts'); ?>
							</div>
						</i>
					</h4>
				</div>
				<div class="form-group">
					<h4>
						Pontos por artigos publicados em periódicos especializados: <?php echo $professor->getXMLPoints('papers'); ?>
						<i class="fa fa-plus-square">
							<div class="doi-links">
								<?php $professor->showXMLOrigin('papers'); ?>
							</div>
						</i>
					</h4>
				</div>
				<div class="form-group">
					<h4>
						Pontos por autoria ou co-autoria de livros: <?php echo $professor->getXMLPoints('books'); ?>
						<i class="fa fa-plus-square">
							<div class="doi-links">
								<?php $professor->showXMLOrigin('books'); ?>
							</div>
						</i>
					</h4>
				</div>
				<div class="form-group">
					<h4>
						Pontos por textos em jornais ou revistas: <?php echo $professor->getXMLPoints('texts'); ?>
						<i class="fa fa-plus-square">
							<div class="doi-links">
								<?php $professor->showXMLOrigin('texts'); ?>
							</div>
						</i>
					</h4>
				</div>
			</div>
		</div>
		<div class="form-group">
			<h3 class="dropdown" onclick="drop(this, '.Siatex')">Pontuação calculada utilizando informações do SIATEX. <i class="fa fa-chevron-down"></i></h3>
		</div>
		<div class='Siatex'>
			<div class="container">
				<div class="form-group">
					<h4>
						Pontos por elaboração de projetos de Extensão (cadastrados na pró-reitoria da extensão): <?php echo $professor->getSIATEXPoints('elaborador'); ?>
						<i class="fa fa-plus-square">
							<div class="doi-links">
								<?php $professor->showSIATEXOrigin('elaborador'); ?>
							</div>
						</i>
					</h4>
				</div>
				<div class="form-group">
					<h4>
						Pontos por coordenação de projetos de Extensão (cadastrados na pró-reitoria da extensão): <?php echo $professor->getSIATEXPoints('coordenador'); ?>
						<i class="fa fa-plus-square">
							<div class="doi-links">
								<?php $professor->showSIATEXOrigin('coordenador'); ?>
							</div>
						</i>
					</h4>
				</div>
			</div>
		</div>
		<div class="form-group">
			<h3 class="dropdown" onclick="drop(this, '.Sapi')">Pontuação calculada utilizando informações do SAPI. <i class="fa fa-chevron-down"></i></h3>
		</div>
		<div class='Sapi'>
			<div class="container">
				<div class="form-group">
					<h4>
						Pontos por coordenação de projetos de Pesquisa (cadastrados na pró-reitoria de graduação e pesquisa): <?php echo $professor->getSAPIPoints('coordenador'); ?>
						<i class="fa fa-plus-square">
							<div class="doi-links">
								<?php $professor->showSAPIOrigin('coordenador'); ?>
							</div>
						</i>
					</h4>
				</div>
			</div>
		</div>
		<div class="form-group">
			<h3 class="dropdown" onclick="drop(this, '.Sisbic')">Pontuação calculada utilizando informações do SISBIC. <i class="fa fa-chevron-down"></i></h3>
		</div>
		<div class='Sisbic'>
			<div class="container">
				<div class="form-group">
					<h4>
						Pontos por orientação de bolsas de Pesquisa: <?php echo $professor->getSISBICPoints('orientador'); ?>
						<i class="fa fa-plus-square">
							<div class="doi-links">
								<?php $professor->showSISBICOrigin('orientador'); ?>
							</div>
						</i>
					</h4>
				</div>
			</div>
		</div>
		<div>
			<?php //print_r($professor->getData('siatexPointsOrigin')); ?>
		</div>
	</body>
	<script src="{{ asset('js/main.js') }}" type="text/javascript"></script>
</html>
