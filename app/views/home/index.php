<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Acadêmico</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        .jumbotron {
            background-color: #f8f8f8;
            margin-top: 20px;
        }
        .feature-box {
            text-align: center;
            padding: 20px;
            margin-bottom: 30px;
        }
        .feature-box i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #337ab7;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <!-- Brand and toggle -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= base_url() ?>">Sistema Acadêmico</a>
            </div>
            
            <!-- Navbar links -->
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?= base_url('home/about') ?>">Sobre</a></li>
                    <li><a href="<?= base_url('home/contact') ?>">Contato</a></li>
                    <li><a href="<?= base_url('auth/login') ?>">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Jumbotron -->
    <div class="jumbotron">
        <div class="container">
            <h1>Bem-vindo ao Sistema Acadêmico</h1>
            <p>Uma plataforma completa para gestão acadêmica, com recursos para alunos, professores, coordenadores e administradores.</p>
            <p><a class="btn btn-primary btn-lg" href="<?= base_url('auth/login') ?>" role="button">Acessar &raquo;</a></p>
        </div>
    </div>
    
    <!-- Features -->
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fa fa-graduation-cap"></i>
                    <h3>Para Alunos</h3>
                    <p>Acesse aulas, provas, tarefas e acompanhe seu desempenho através do boletim online.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fa fa-book"></i>
                    <h3>Para Professores</h3>
                    <p>Gerencie aulas, planos de aula, provas, turmas e matérias de forma simples e eficiente.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fa fa-users"></i>
                    <h3>Para Gestores</h3>
                    <p>Coordenadores, secretários e administradores podem gerenciar todos os aspectos da instituição.</p>
                </div>
            </div>
        </div>
        
        <hr>
        
        <footer>
            <p class="text-center">&copy; <?= date('Y') ?> Sistema Acadêmico</p>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>