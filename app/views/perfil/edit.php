<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Editar Perfil <small><?= $nome ?></small></h1>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Foto de Perfil</h3>
                </div>
                <div class="panel-body text-center">
                    <?php if (isset($usuario['foto']) && !empty($usuario['foto'])): ?>
                        <img src="<?= base_url($usuario['foto']) ?>" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    <?php else: ?>
                        <img src="<?= base_url('assets/images/user-default.png') ?>" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    <?php endif; ?>
                    
                    <form action="<?= base_url('perfil/upload-photo') ?>" method="post" enctype="multipart/form-data" class="mt-3">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <div class="form-group">
                            <label for="foto" class="sr-only">Nova Foto</label>
                            <input type="file" name="foto" id="foto" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Atualizar Foto</button>
                    </form>
                </div>
            </div>
            
            <div class="list-group">
                <a href="<?= base_url('dashboard') ?>" class="list-group-item"><i class="fa fa-dashboard"></i> Dashboard</a>
                <a href="<?= base_url('perfil') ?>" class="list-group-item active"><i class="fa fa-user"></i> Editar Perfil</a>
                <a href="<?= base_url('auth/change-password') ?>" class="list-group-item"><i class="fa fa-lock"></i> Alterar Senha</a>
            </div>
        </div>
        
        <div class="col-md-9">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success'] ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Informações Pessoais</h3>
                </div>
                <div class="panel-body">
                    <form action="<?= base_url('perfil/update') ?>" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="form-group">
                            <label for="nome">Nome Completo</label>
                            <input type="text" name="nome" id="nome" class="form-control" value="<?= $nome ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control" value="<?= $email ?>" readonly>
                            <p class="help-block">O email não pode ser alterado.</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="tel" name="telefone" id="telefone" class="form-control" value="<?= isset($dados_pessoais) && isset($dados_pessoais['telefone']) ? $dados_pessoais['telefone'] : '' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" name="endereco" id="endereco" class="form-control" value="<?= isset($dados_pessoais) && isset($dados_pessoais['endereco']) ? $dados_pessoais['endereco'] : '' ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cidade">Cidade</label>
                                    <input type="text" name="cidade" id="cidade" class="form-control" value="<?= isset($dados_pessoais) && isset($dados_pessoais['cidade']) ? $dados_pessoais['cidade'] : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <input type="text" name="estado" id="estado" class="form-control" value="<?= isset($dados_pessoais) && isset($dados_pessoais['estado']) ? $dados_pessoais['estado'] : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cep">CEP</label>
                                    <input type="text" name="cep" id="cep" class="form-control" value="<?= isset($dados_pessoais) && isset($dados_pessoais['cep']) ? $dados_pessoais['cep'] : '' ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Tipo de Perfil</label>
                            <p class="form-control-static">
                                <?php
                                $perfilNome = '';
                                switch ($perfil) {
                                    case 1: $perfilNome = 'Aluno'; break;
                                    case 2: $perfilNome = 'Professor'; break;
                                    case 3: $perfilNome = 'Coordenador'; break;
                                    case 4: $perfilNome = 'Secretário'; break;
                                    case 5: $perfilNome = 'Administrador'; break;
                                    default: $perfilNome = 'Desconhecido';
                                }
                                echo $perfilNome;
                                ?>
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-default">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>