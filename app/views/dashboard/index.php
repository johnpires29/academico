<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Dashboard <small>Bem-vindo, <?= $nome ?></small></h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Perfil</h3>
                </div>
                <div class="panel-body">
                    <p><strong>Nome:</strong> <?= $nome ?></p>
                    <p><strong>Perfil:</strong> <?= ucfirst($perfil) ?></p>
                    <a href="<?= base_url('perfil') ?>" class="btn btn-default btn-block">Editar Perfil</a>
                </div>
            </div>
            
            <!-- Menu lateral específico para cada perfil -->
            <div class="list-group">
                <?php if ($perfil === 1): // aluno ?>
                    <a href="<?= base_url('aulas') ?>" class="list-group-item"><i class="fa fa-book"></i> Aulas</a>
                    <a href="<?= base_url('provas') ?>" class="list-group-item"><i class="fa fa-file-text"></i> Provas</a>
                    <a href="<?= base_url('tarefas') ?>" class="list-group-item"><i class="fa fa-tasks"></i> Tarefas</a>
                    <a href="<?= base_url('boletim') ?>" class="list-group-item"><i class="fa fa-bar-chart"></i> Boletim</a>
                <?php elseif ($perfil === 2): // professor ?>
                    <a href="<?= base_url('aulas') ?>" class="list-group-item"><i class="fa fa-book"></i> Aulas</a>
                    <a href="<?= base_url('planos') ?>" class="list-group-item"><i class="fa fa-calendar"></i> Planos</a>
                    <a href="<?= base_url('provas') ?>" class="list-group-item"><i class="fa fa-file-text"></i> Provas</a>
                    <a href="<?= base_url('turmas') ?>" class="list-group-item"><i class="fa fa-users"></i> Turmas</a>
                    <a href="<?= base_url('materias') ?>" class="list-group-item"><i class="fa fa-graduation-cap"></i> Matérias</a>
                <?php elseif ($perfil === 3): // coordenador ?>
                    <a href="<?= base_url('professores') ?>" class="list-group-item"><i class="fa fa-user"></i> Professores</a>
                    <a href="<?= base_url('planos-semestrais') ?>" class="list-group-item"><i class="fa fa-calendar"></i> Planos Semestrais</a>
                <?php elseif ($perfil === 4): // secretario ?>
                    <a href="<?= base_url('alunos') ?>" class="list-group-item"><i class="fa fa-user"></i> Alunos</a>
                    <a href="<?= base_url('professores') ?>" class="list-group-item"><i class="fa fa-user"></i> Professores</a>
                    <a href="<?= base_url('coordenadores') ?>" class="list-group-item"><i class="fa fa-user"></i> Coordenadores</a>
                <?php elseif ($perfil === 5): // admin ?>
                    <a href="<?= base_url('usuarios') ?>" class="list-group-item"><i class="fa fa-users"></i> Usuários</a>
                    <a href="<?= base_url('series') ?>" class="list-group-item"><i class="fa fa-list"></i> Séries</a>
                    <a href="<?= base_url('turmas') ?>" class="list-group-item"><i class="fa fa-users"></i> Turmas</a>
                    <a href="<?= base_url('materias') ?>" class="list-group-item"><i class="fa fa-book"></i> Matérias</a>
                    <a href="<?= base_url('configuracoes') ?>" class="list-group-item"><i class="fa fa-cog"></i> Configurações</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Conteúdo específico para cada perfil -->
            <?php if ($perfil === 1): // aluno ?>
                <!-- Dashboard do Aluno -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Próximas Aulas</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($aulas_recentes)): ?>
                                    <p class="text-center">Nenhuma aula agendada.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($aulas_recentes as $aula): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $aula['titulo'] ?></h4>
                                                <p class="list-group-item-text"><?= format_date($aula['data']) ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Próximas Provas</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($proximas_provas)): ?>
                                    <p class="text-center">Nenhuma prova agendada.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($proximas_provas as $prova): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $prova['titulo'] ?></h4>
                                                <p class="list-group-item-text"><?= format_date($prova['data']) ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tarefas Pendentes</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($tarefas_pendentes)): ?>
                                    <p class="text-center">Nenhuma tarefa pendente.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($tarefas_pendentes as $tarefa): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $tarefa['titulo'] ?></h4>
                                                <p class="list-group-item-text">Data de entrega: <?= format_date($tarefa['data_entrega']) ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($perfil === 2): // professor ?>
                <!-- Dashboard do Professor -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Minhas Turmas</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($turmas)): ?>
                                    <p class="text-center">Nenhuma turma encontrada.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($turmas as $turma): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $turma['nome'] ?></h4>
                                                <p class="list-group-item-text"><?= $turma['serie'] ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Próximas Aulas</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($proximas_aulas)): ?>
                                    <p class="text-center">Nenhuma aula agendada.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($proximas_aulas as $aula): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $aula['titulo'] ?></h4>
                                                <p class="list-group-item-text"><?= format_date($aula['data']) ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Provas Pendentes</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($provas_pendentes)): ?>
                                    <p class="text-center">Nenhuma prova pendente.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($provas_pendentes as $prova): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $prova['titulo'] ?></h4>
                                                <p class="list-group-item-text">Data: <?= format_date($prova['data']) ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($perfil === 3): // coordenador ?>
                <!-- Dashboard do Coordenador -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Professores</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($professores)): ?>
                                    <p class="text-center">Nenhum professor encontrado.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($professores as $professor): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $professor['nome'] ?></h4>
                                                <p class="list-group-item-text"><?= $professor['email'] ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Planos Semestrais</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($planos_semestrais)): ?>
                                    <p class="text-center">Nenhum plano semestral encontrado.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($planos_semestrais as $plano): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $plano['titulo'] ?></h4>
                                                <p class="list-group-item-text">Semestre: <?= $plano['semestre'] ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($perfil === 4): // secretario ?>
                <!-- Dashboard do Secretário -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Alunos</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($alunos)): ?>
                                    <p class="text-center">Nenhum aluno encontrado.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($alunos as $aluno): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $aluno['nome'] ?></h4>
                                                <p class="list-group-item-text"><?= $aluno['email'] ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Professores</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($professores)): ?>
                                    <p class="text-center">Nenhum professor encontrado.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($professores as $professor): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $professor['nome'] ?></h4>
                                                <p class="list-group-item-text"><?= $professor['email'] ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Coordenadores</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($coordenadores)): ?>
                                    <p class="text-center">Nenhum coordenador encontrado.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($coordenadores as $coordenador): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $coordenador['nome'] ?></h4>
                                                <p class="list-group-item-text"><?= $coordenador['email'] ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($perfil === 5): // admin ?>
                <!-- Dashboard do Administrador -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Estatísticas</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($estatisticas)): ?>
                                    <p class="text-center">Nenhuma estatística disponível.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="badge"><?= $estatisticas['total_alunos'] ?></span>
                                            Total de Alunos
                                        </li>
                                        <li class="list-group-item">
                                            <span class="badge"><?= $estatisticas['total_professores'] ?></span>
                                            Total de Professores
                                        </li>
                                        <li class="list-group-item">
                                            <span class="badge"><?= $estatisticas['total_turmas'] ?></span>
                                            Total de Turmas
                                        </li>
                                        <li class="list-group-item">
                                            <span class="badge"><?= $estatisticas['total_materias'] ?></span>
                                            Total de Matérias
                                        </li>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Usuários Recentes</h3>
                            </div>
                            <div class="panel-body">
                                <?php if (empty($usuarios)): ?>
                                    <p class="text-center">Nenhum usuário encontrado.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <li class="list-group-item">
                                                <h4 class="list-group-item-heading"><?= $usuario['nome'] ?></h4>
                                                <p class="list-group-item-text"><?= $usuario['email'] ?> (<?= ucfirst($usuario['perfil']) ?>)</p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Ações Rápidas</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <a href="<?= base_url('usuarios/create') ?>" class="btn btn-primary btn-block">
                                            <i class="fa fa-user-plus"></i> Novo Usuário
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= base_url('turmas/create') ?>" class="btn btn-info btn-block">
                                            <i class="fa fa-users"></i> Nova Turma
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= base_url('materias/create') ?>" class="btn btn-warning btn-block">
                                            <i class="fa fa-book"></i> Nova Matéria
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= base_url('configuracoes') ?>" class="btn btn-default btn-block">
                                            <i class="fa fa-cog"></i> Configurações
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>