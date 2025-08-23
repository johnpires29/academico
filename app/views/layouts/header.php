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
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
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
                <ul class="nav navbar-nav">
                    <?php if (is_authenticated()): ?>
                        <!-- Menu dinâmico conforme o perfil -->
                        <?php if (has_permission('aluno')): ?>
                            <li><a href="<?= base_url('aulas') ?>">Aulas</a></li>
                            <li><a href="<?= base_url('provas') ?>">Provas</a></li>
                            <li><a href="<?= base_url('tarefas') ?>">Tarefas</a></li>
                            <li><a href="<?= base_url('boletim') ?>">Boletim</a></li>
                        <?php endif; ?>
                        
                        <?php if (has_permission('professor')): ?>
                            <li><a href="<?= base_url('aulas') ?>">Aulas</a></li>
                            <li><a href="<?= base_url('planos') ?>">Planos</a></li>
                            <li><a href="<?= base_url('provas') ?>">Provas</a></li>
                            <li><a href="<?= base_url('turmas') ?>">Turmas</a></li>
                            <li><a href="<?= base_url('materias') ?>">Matérias</a></li>
                        <?php endif; ?>
                        
                        <?php if (has_permission('coordenador')): ?>
                            <li><a href="<?= base_url('professores') ?>">Professores</a></li>
                            <li><a href="<?= base_url('planos-semestrais') ?>">Planos Semestrais</a></li>
                        <?php endif; ?>
                        
                        <?php if (has_permission('secretario')): ?>
                            <li><a href="<?= base_url('alunos') ?>">Alunos</a></li>
                            <li><a href="<?= base_url('professores') ?>">Professores</a></li>
                            <li><a href="<?= base_url('coordenadores') ?>">Coordenadores</a></li>
                        <?php endif; ?>
                        
                        <?php if (has_permission('admin')): ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cadastros <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= base_url('usuarios') ?>">Usuários</a></li>
                                    <li><a href="<?= base_url('series') ?>">Séries</a></li>
                                    <li><a href="<?= base_url('turmas') ?>">Turmas</a></li>
                                    <li><a href="<?= base_url('materias') ?>">Matérias</a></li>
                                </ul>
                            </li>
                            <li><a href="<?= base_url('configuracoes') ?>">Configurações</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="nav navbar-nav navbar-right">
                    <?php if (is_authenticated()): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user"></i> <?= $_SESSION['user_name'] ?> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= base_url('perfil') ?>">Meu Perfil</a></li>
                                <li><a href="<?= base_url('auth/change-password') ?>">Alterar Senha</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="<?= base_url('auth/logout') ?>">Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="<?= base_url('auth/login') ?>">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Mensagens de alerta -->
    <div class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']) ?>
            </div>
        <?php endif; ?>
    </div>