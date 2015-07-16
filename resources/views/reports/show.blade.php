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
			<?php echo $user->getName(); ?>
		</h2>
	</div>
	<div class="container">
		<div class="form-group">
			<h4>
				Ano de início da Progressão: <?php echo $user->getYear(); ?>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por bolsa de produtividade do CNPq:  <?php echo $user->getPoints('bolsas'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $user->showOrigin('bolsas'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por publicação de trabalhos completos em anais de congressos: <?php echo $user->getPoints('trabalhos'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $user->showOrigin('trabalhos'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por resumos: <?php echo $user->getPoints('resumos'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $user->showOrigin('resumos'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por artigos publicados em periódicos especializados: <?php echo $user->getPoints('artigos'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $user->showOrigin('artigos'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por autoria ou co-autoria de livros: <?php echo $user->getPoints('livros'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $user->showOrigin('livros'); ?>
					</div>
				</i>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por textos em jornais ou revistas: <?php echo $user->getPoints('textos'); ?>
				<i class="fa fa-plus-square">
					<div class="doi-links">
						<?php $user->showOrigin('textos'); ?>
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