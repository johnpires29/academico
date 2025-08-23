<?php
/**
 * Script para testar a conexão com o banco de dados
 */

require_once __DIR__ . '/config/Connection.php';

try {
    // Tenta obter uma instância da conexão
    $connection = Connection::getInstance();
    
    // Se chegou até aqui, a conexão foi bem-sucedida
    echo "<h1>Teste de Conexão</h1>";
    echo "<p style='color: green; font-weight: bold;'>Conexão com o banco de dados 'academico' estabelecida com sucesso!</p>";
    
    // Tenta executar uma consulta simples para verificar se o banco está funcionando
    $pdo = $connection->getConnection();
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Tabelas encontradas no banco de dados:</h2>";
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>{$table}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Nenhuma tabela encontrada no banco de dados.</p>";
    }
    
} catch (Exception $e) {
    echo "<h1>Erro de Conexão</h1>";
    echo "<p style='color: red; font-weight: bold;'>Erro ao conectar com o banco de dados: " . $e->getMessage() . "</p>";
    
    echo "<h2>Verificações a fazer:</h2>";
    echo "<ul>";
    echo "<li>O servidor MySQL está em execução?</li>";
    echo "<li>O banco de dados 'academico' existe?</li>";
    echo "<li>O usuário e senha estão corretos?</li>";
    echo "<li>As configurações em /config/database.php estão corretas?</li>";
    echo "</ul>";
}