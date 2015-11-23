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
			<div class="form-group" hidden>
				<label for="professor_cpf">C.P.F</label>
				<input type="text" name="professor[cpf]" class="form-control">
				</input>
			</div>
			<div class="form-group" hidden>
				<label for="professor_password">Senha</label>
				<input type="password" name="professor[password]" class="form-control">
				</input>
			</div>
			<div class="form-group">
				<label for="professor_lattes">Lattes</label>
				<input type="file" accept=".xml" name="professor[lattes]" class="form-control" required>
				</input>
			</div>
			<div class="form-group" hidden>
				<select title="Selecione sua categoria atual" name="professor[rank]" class="form-control">
					<option value="">Selecione sua categoria atual...</option>
					<option value="1">Adjunto</option>
					<option value="2">Associado</option>
					<option value="3">Titular</option>
				</select>
			</div>
			<div class="form-group">
				<label for="professor[year]">Ano de Início</label>
				<input type="text" name="professor[year]" class="form-control" required>
				</input>
			</div>
			<div class="form-group">
				<!-- Laravel CSRF protection  -->
				<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
			</div>
			<div class="form-group">
				<input type="submit" value="Gerar!" class="btn btn-primary">
			</div>
		</form>
	</body>
</html>
