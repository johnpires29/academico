<?php require_once APP_PATH . '/views/layouts/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Alterar Senha</h3>
                </div>
                <div class="panel-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success'] ?>
                            <?php unset($_SESSION['success']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form role="form" method="post" action="<?= base_url('auth/update-password') ?>">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="form-group">
                            <label>Senha Atual</label>
                            <input class="form-control" name="senha_atual" type="password" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Nova Senha</label>
                            <input class="form-control" name="nova_senha" type="password" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Confirmar Nova Senha</label>
                            <input class="form-control" name="confirmar_senha" type="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Alterar Senha</button>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>