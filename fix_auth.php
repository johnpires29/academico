<?php
/**
 * Script para verificar e corrigir problemas de autenticação
 */

// Definir constantes do sistema
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('HELPERS_PATH', BASE_PATH . '/helpers');

// Carregar autoloader e funções auxiliares
require_once HELPERS_PATH . '/functions.php';
require_once CONFIG_PATH . '/Connection.php';

// Iniciar sessão
session_start();

// Função para exibir mensagens formatadas
function showMessage($message, $type = 'info') {
    $color = 'black';
    switch ($type) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        case 'warning':
            $color = 'orange';
            break;
    }
    echo "<div style='color: {$color}; margin: 10px 0;'>{$message}</div>";
}

// Função para verificar a estrutura da tabela de usuários
function checkUserTable() {
    try {
        $db = Connection::getInstance()->getConnection();
        
        // Verificar se a tabela existe
        $stmt = $db->query("SHOW TABLES LIKE 'usuarios'");
        if ($stmt->rowCount() === 0) {
            showMessage("A tabela 'usuarios' não existe no banco de dados.", 'error');
            return false;
        }
        
        // Verificar a estrutura da tabela
        $stmt = $db->query("DESCRIBE usuarios");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        showMessage("Colunas encontradas na tabela 'usuarios':");
        echo "<ul>";
        foreach ($columns as $column) {
            echo "<li>{$column}</li>";
        }
        echo "</ul>";
        
        // Verificar se as colunas necessárias existem
        $requiredColumns = ['id', 'nome', 'email', 'senha', 'perfil_id'];
        $missingColumns = array_diff($requiredColumns, $columns);
        
        if (!empty($missingColumns)) {
            showMessage("Colunas necessárias ausentes: " . implode(', ', $missingColumns), 'error');
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        showMessage("Erro ao verificar a tabela de usuários: " . $e->getMessage(), 'error');
        return false;
    }
}

// Função para verificar os usuários cadastrados
function checkUsers() {
    try {
        $db = Connection::getInstance()->getConnection();
        
        // Buscar todos os usuários
        $stmt = $db->query("SELECT * FROM usuarios");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($users)) {
            showMessage("Nenhum usuário encontrado no banco de dados.", 'warning');
            return false;
        }
        
        showMessage("Usuários encontrados no banco de dados:");
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Senha (hash)</th><th>Perfil ID</th></tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['nome']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['senha']}</td>";
            echo "<td>{$user['perfil_id']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        return true;
    } catch (Exception $e) {
        showMessage("Erro ao verificar usuários: " . $e->getMessage(), 'error');
        return false;
    }
}

// Função para corrigir a senha do administrador
function fixAdminPassword() {
    try {
        $db = Connection::getInstance()->getConnection();
        
        // Verificar se o usuário admin existe
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindValue(':email', 'admin@exemplo.com');
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin) {
            showMessage("Usuário administrador não encontrado.", 'error');
            return false;
        }
        
        // Gerar nova senha hash para 'admin123'
        $newPassword = password_hash('admin123', PASSWORD_DEFAULT);
        
        // Atualizar a senha do administrador
        $stmt = $db->prepare("UPDATE usuarios SET senha = :senha WHERE id = :id");
        $stmt->bindValue(':senha', $newPassword);
        $stmt->bindValue(':id', $admin['id']);
        $result = $stmt->execute();
        
        if ($result) {
            showMessage("Senha do administrador redefinida com sucesso para 'admin123'.", 'success');
            return true;
        } else {
            showMessage("Falha ao redefinir a senha do administrador.", 'error');
            return false;
        }
    } catch (Exception $e) {
        showMessage("Erro ao corrigir senha do administrador: " . $e->getMessage(), 'error');
        return false;
    }
}

// Função para testar a autenticação
function testAuthentication($email, $password) {
    try {
        $db = Connection::getInstance()->getConnection();
        
        // Buscar o usuário pelo email
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            showMessage("Usuário não encontrado com o email: {$email}", 'error');
            return false;
        }
        
        // Verificar a senha
        $passwordVerified = password_verify($password, $user['senha']);
        
        if ($passwordVerified) {
            showMessage("Autenticação bem-sucedida para o usuário: {$email}", 'success');
            return true;
        } else {
            showMessage("Falha na autenticação para o usuário: {$email}", 'error');
            showMessage("Senha fornecida: {$password}", 'info');
            showMessage("Hash armazenado: {$user['senha']}", 'info');
            return false;
        }
    } catch (Exception $e) {
        showMessage("Erro ao testar autenticação: " . $e->getMessage(), 'error');
        return false;
    }
}

// Exibir cabeçalho HTML
echo "<!DOCTYPE html>";
echo "<html lang='pt-br'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Correção de Autenticação</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }";
echo "h1, h2 { color: #333; }";
echo "hr { margin: 20px 0; border: 0; border-top: 1px solid #eee; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<h1>Verificação e Correção de Autenticação</h1>";

// Verificar a tabela de usuários
echo "<h2>1. Verificando a estrutura da tabela de usuários</h2>";
$tableOk = checkUserTable();

echo "<hr>";

// Verificar os usuários cadastrados
echo "<h2>2. Verificando usuários cadastrados</h2>";
$usersOk = checkUsers();

echo "<hr>";

// Corrigir a senha do administrador se necessário
echo "<h2>3. Corrigindo a senha do administrador</h2>";
if ($tableOk && $usersOk) {
    $passwordFixed = fixAdminPassword();
} else {
    showMessage("Não é possível corrigir a senha do administrador devido a problemas anteriores.", 'warning');
    $passwordFixed = false;
}

echo "<hr>";

// Testar a autenticação
echo "<h2>4. Testando a autenticação</h2>";
if ($tableOk && $usersOk && $passwordFixed) {
    testAuthentication('admin@exemplo.com', 'admin123');
} else {
    showMessage("Não é possível testar a autenticação devido a problemas anteriores.", 'warning');
}

echo "<hr>";

// Exibir instruções finais
echo "<h2>Instruções Finais</h2>";
echo "<p>Após corrigir os problemas, tente fazer login novamente com as seguintes credenciais:</p>";
echo "<ul>";
echo "<li><strong>Email:</strong> admin@exemplo.com</li>";
echo "<li><strong>Senha:</strong> admin123</li>";
echo "</ul>";

echo "<p><a href='" . base_url('auth/login') . "'>Ir para a página de login</a></p>";

echo "</body>";
echo "</html>";