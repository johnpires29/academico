<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro Interno - Sistema Acadêmico</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        body { padding-top: 50px; }
        .error-template { padding: 40px 15px; text-align: center; }
        .error-actions { margin-top: 15px; margin-bottom: 15px; }
        .error-actions .btn { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>Oops!</h1>
                    <h2>500 - Erro Interno do Servidor</h2>
                    <div class="error-details">
                        Desculpe, ocorreu um erro interno no servidor. Estamos trabalhando para resolver o problema!
                    </div>
                    <div class="error-actions">
                        <a href="<?= base_url() ?>" class="btn btn-primary btn-lg">
                            <i class="glyphicon glyphicon-home"></i> Voltar para o Início
                        </a>
                        <a href="<?= base_url('home/contact') ?>" class="btn btn-default btn-lg">
                            <i class="glyphicon glyphicon-envelope"></i> Contato
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>