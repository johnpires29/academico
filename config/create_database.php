<?php
/**
 * Script para criar o banco de dados e tabelas iniciais
 */

try {
    // Conectar ao MySQL sem especificar um banco de dados
    $pdo = new PDO('mysql:host=localhost', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Criar o banco de dados se não existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS academico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "Banco de dados 'academico' criado com sucesso!\n";
    
    // Conectar ao banco de dados academico
    $pdo = new PDO('mysql:host=localhost;dbname=academico;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Executar o script SQL
    $sql = file_get_contents(__DIR__ . '/create_database.sql');
    
    // Dividir o SQL em comandos individuais
    $commands = array_filter(array_map('trim', explode(';', $sql)));
    
    // Executar cada comando
    foreach ($commands as $command) {
        if (!empty($command)) {
            $pdo->exec($command);
        }
    }
    
    echo "Tabelas e dados iniciais criados com sucesso!\n";
    
    // Restaurar a configuração original do arquivo database.php
    $config = file_get_contents(__DIR__ . '/database.php');
    $config = str_replace("'dbname' => 'mysql',", "'dbname' => 'academico',", $config);
    file_put_contents(__DIR__ . '/database.php', $config);
    
    echo "Configuração do banco de dados restaurada para 'academico'!\n";
    echo "Instalação concluída com sucesso!\n";
    
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}