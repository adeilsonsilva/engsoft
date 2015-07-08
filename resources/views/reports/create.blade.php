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
		<h1>Geração de Relatório</h1>
	</div>
	<form action="{{ action('ReportsController@store') }}" method="post" enctype="multipart/form-data" class="col-sm-4">
		<div class="form-group">
			<label for="name">Nome</label>
			<input type="text" name="name" class="form-control">
			</input>
		</div>
		<div class="form-group" hidden>
			<label for="cpf">C.P.F</label>
			<input type="text" name="cpf" class="form-control">
			</input>
		</div>
		<div class="form-group" hidden>
			<label for="password">Senha</label>
			<input type="password" name="pass" class="form-control">
			</input>
		</div>
		<div class="form-group">
			<label for="lattes">Lattes</label>
			<input type="file" accept=".xml" name="lattes" class="form-control" required>
			</input>
		</div>
		<div class="form-group" hidden>
			<select title="Selecione sua categoria atual" name="rank" class="form-control">
				<option value="">Selecione sua categoria atual...</option>
				<option value="1">Adjunto</option>
				<option value="2">Associado</option>
				<option value="3">Titular</option>
			</select>
		</div>
		<div class="form-group">
			<label for="year">Ano de Início</label>
			<input type="text" name="year" class="form-control" required>
			</input>
		</div>
		<div class="form-group">
			<input type="submit" value="Gerar!" class="btn btn-primary">
		</div>
		<!-- Laravel CSRF protection  -->
		<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
	</form>
</body>
</html>