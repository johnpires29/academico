<?php
/**
 * Script para corrigir a senha do administrador
 */

// Definir constantes do sistema
define('BASE_PATH', __DIR__);
define('CONFIG_PATH', BASE_PATH . '/config');

// Carregar a conexão com o banco de dados
require_once CONFIG_PATH . '/Connection.php';

try {
    // Obter conexão com o banco de dados
    $db = Connection::getInstance()->getConnection();
    
    // Gerar nova senha hash para 'admin123'
    $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Atualizar a senha do administrador
    $stmt = $db->prepare("UPDATE usuarios SET senha = :senha WHERE email = :email");
    $stmt->bindValue(':senha', $newPassword);
    $stmt->bindValue(':email', 'admin@exemplo.com');
    $result = $stmt->execute();
    
    if ($result) {
        echo "<h1 style='color: green;'>Senha do administrador redefinida com sucesso!</h1>";
        echo "<p>A senha do usuário admin@exemplo.com foi redefinida para 'admin123'.</p>";
        echo "<p>Agora você pode fazer login com essas credenciais.</p>";
        echo "<p>Nova hash da senha: {$newPassword}</p>";
    } else {
        echo "<h1 style='color: red;'>Falha ao redefinir a senha do administrador.</h1>";
        echo "<p>Não foi possível atualizar a senha no banco de dados.</p>";
    }
} catch (Exception $e) {
    echo "<h1 style='color: red;'>Erro ao corrigir senha do administrador</h1>";
    echo "<p>Mensagem de erro: {$e->getMessage()}</p>";
}

echo "<p><a href='public/auth/login'>Ir para a página de login</a></p>";