<?php
/**
 * Script para corrigir a senha do administrador
 * Versão em Português do Brasil
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
        
        // Verificar se a senha foi realmente atualizada
        $checkStmt = $db->prepare("SELECT senha FROM usuarios WHERE email = :email");
        $checkStmt->bindValue(':email', 'admin@exemplo.com');
        $checkStmt->execute();
        $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify('admin123', $user['senha'])) {
            echo "<p style='color: green;'>Verificação de senha: OK! A senha foi corretamente armazenada e pode ser verificada.</p>";
        } else {
            echo "<p style='color: orange;'>Aviso: A senha foi atualizada, mas há um problema na verificação. Contate o administrador do sistema.</p>";
        }
    } else {
        echo "<h1 style='color: red;'>Falha ao redefinir a senha do administrador.</h1>";
        echo "<p>Não foi possível atualizar a senha no banco de dados.</p>";
    }
    
    // Exibir informações sobre a tabela de usuários
    echo "<h2>Informações da tabela de usuários:</h2>";
    $infoStmt = $db->query("SELECT id, nome, email, perfil, ativo FROM usuarios LIMIT 5");
    $users = $infoStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f2f2f2;'><th>ID</th><th>Nome</th><th>Email</th><th>Perfil</th><th>Ativo</th></tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['nome']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['perfil']}</td>";
        echo "<td>{$user['ativo']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (Exception $e) {
    echo "<h1 style='color: red;'>Erro ao corrigir senha do administrador</h1>";
    echo "<p>Mensagem de erro: {$e->getMessage()}</p>";
}

echo "<p><a href='public/auth/login' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;'>Ir para a página de login</a></p>";