<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<title>EngSoft</title>
	<link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css')
	   }}" rel="stylesheet">
</head>
<body>
	<div class="page-header">
		<h1>Relatório</h1>
		<h2>
			<?php echo $name ?>
		</h2>
	</div>
	<div class="container">
		<div class="form-group">
			<h4>
				Ano de início da Progressão: <?php echo $year ?>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por bolsa de produtividade do CNPq:  <?php echo $points['bolsas'] ?>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por publicação de trabalhos completos em anais de congressos: <?php echo $points['trabalhos'] ?>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por resumos: <?php echo $points['resumos'] ?>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por artigos publicados em periódicos especializados: <?php echo $points['artigos'] ?>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por autoria ou co-autoria de livros: <?php echo $points['livros'] ?>
			</h4>
		</div>
		<div class="form-group">
			<h4>
				Pontos por textos em jornais ou revistas: <?php echo $points['textos'] ?>
			</h4>
		</div>
		<!-- Laravel CSRF protection  -->
		<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
	</div>
</body>
</html>