<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Sistema Acadêmico</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="login-page">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Recuperar Senha</h3>
                    </div>
                    <div class="panel-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?= $_SESSION['error'] ?>
                                <?php unset($_SESSION['error']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <p class="text-center">Informe seu e-mail para receber instruções de recuperação de senha.</p>
                        
                        <form role="form" method="post" action="<?= base_url('auth/reset-password') ?>">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus required>
                                </div>
                                
                                <button type="submit" class="btn btn-lg btn-primary btn-block">Enviar</button>
                                
                                <div class="text-center" style="margin-top: 15px;">
                                    <a href="<?= base_url('auth/login') ?>">Voltar para o login</a>
                                </div>
                            </fieldset>
                        </form>
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