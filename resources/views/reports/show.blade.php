<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<title>EngSoft</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
   	<link href="{{ asset('css/main.css') }}" rel="stylesheet">
   	<script src="{{ asset('js/main.js') }}" type="text/javascript"></script>
</head>
<body>
	<div class="page-header">
		<h1>Relatório</h1>
		<h2>
			<?php echo $professor->getName(); ?>
		</h2>
	</div>
	<div class="container">
		<div class="form-group">
			<h4>
				Ano de início da Progressão: <?php echo $professor->getYear(); ?>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por bolsa de produtividade do CNPq:  <?php echo $professor->getPoints('bolsas'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $professor->showOrigin('bolsas'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por publicação de trabalhos completos em anais de congressos: <?php echo $professor->getPoints('trabalhos'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $professor->showOrigin('trabalhos'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por resumos: <?php echo $professor->getPoints('resumos'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $professor->showOrigin('resumos'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por artigos publicados em periódicos especializados: <?php echo $professor->getPoints('artigos'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $professor->showOrigin('artigos'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por autoria ou co-autoria de livros: <?php echo $professor->getPoints('livros'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $professor->showOrigin('livros'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por textos em jornais ou revistas: <?php echo $professor->getPoints('textos'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $professor->showOrigin('textos'); ?>
					</div>
				</i>
			</h4>
		</div>
	</div>
	<script>
		show_divs();
	</script>
</body>
</html>
