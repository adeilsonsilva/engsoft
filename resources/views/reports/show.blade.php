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
					Pontos por bolsa de produtividade do CNPq:  <?php echo $professor->getPoints('research'); ?>
					<i class="fa fa-plus-square">
						<div class="doi-links">
							<?php $professor->showOrigin('research'); ?>
						</div>
					</i>
				</h4>
			</div>
			<div class="form-group">
				<h4>
					Pontos por publicação de trabalhos completos em anais de congressos: <?php echo $professor->getPoints('jobs'); ?>
					<i class="fa fa-plus-square">
						<div class="doi-links">
							<?php $professor->showOrigin('jobs'); ?>
						</div>
					</i>
				</h4>
			</div>
			<div class="form-group">
				<h4>
					Pontos por resumos: <?php echo $professor->getPoints('abstracts'); ?>
					<i class="fa fa-plus-square">
						<div class="doi-links">
							<?php $professor->showOrigin('abstracts'); ?>
						</div>
					</i>
				</h4>
			</div>
			<div class="form-group">
				<h4>
					Pontos por artigos publicados em periódicos especializados: <?php echo $professor->getPoints('papers'); ?>
					<i class="fa fa-plus-square">
						<div class="doi-links">
							<?php $professor->showOrigin('papers'); ?>
						</div>
					</i>
				</h4>
			</div>
			<div class="form-group">
				<h4>
					Pontos por autoria ou co-autoria de livros: <?php echo $professor->getPoints('books'); ?>
					<i class="fa fa-plus-square">
						<div class="doi-links">
							<?php $professor->showOrigin('books'); ?>
						</div>
					</i>
				</h4>
			</div>
			<div class="form-group">
				<h4>
					Pontos por textos em jornais ou revistas: <?php echo $professor->getPoints('texts'); ?>
					<i class="fa fa-plus-square">
						<div class="doi-links">
							<?php $professor->showOrigin('texts'); ?>
						</div>
					</i>
				</h4>
			</div>
		</div>
		<div>
			<?php print_r($professor->getSapi()); ?>
		</div>
	</body>
	<script src="{{ asset('js/main.js') }}" type="text/javascript"></script>
</html>
