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
        <h1>Bem-Vindo ao SisProg!</h1>
    </div>
    <div class="container">
        <div class="content">
            <div class="title">
                <p class="lead">Sistema web para geração de relatório de progressão
                dos professores do DCC-UFBA. </p>
            </div>
            <a class="btn btn-primary" href="{{ action('ReportsController@create') }}">Gerar
            Relatório!</a>
        </div>
    </div>
</body>
</html>
