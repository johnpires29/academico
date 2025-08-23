<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Conexão com o Banco de Dados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 20px;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        ul {
            background-color: #f9f9f9;
            padding: 15px 15px 15px 35px;
            border-radius: 5px;
        }
        li {
            margin-bottom: 5px;
        }
        .info-box {
            background-color: #e7f3fe;
            border-left: 4px solid #2196F3;
            padding: 10px 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Teste de Conexão com o Banco de Dados</h1>
        
        <?php
        // Inclui o arquivo de conexão
        require_once __DIR__ . '/config/Connection.php';
        
        try {
            // Tenta obter uma instância da conexão
            $connection = Connection::getInstance();
            
            // Se chegou até aqui, a conexão foi bem-sucedida
            echo "<div class='success'>Conexão com o banco de dados 'academico' estabelecida com sucesso!</div>";
            
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
                
                // Exibe informações sobre a versão do MySQL
                $stmt = $pdo->query("SELECT version() as version");
                $version = $stmt->fetch();
                echo "<div class='info-box'>Versão do MySQL: {$version['version']}</div>";
                
                // Exibe informações sobre o charset e collation do banco
                $stmt = $pdo->query("SELECT @@character_set_database as charset, @@collation_database as collation");
                $charset = $stmt->fetch();
                echo "<div class='info-box'>Charset do banco: {$charset['charset']}<br>Collation: {$charset['collation']}</div>";
            } else {
                echo "<p>Nenhuma tabela encontrada no banco de dados.</p>";
                echo "<div class='info-box'>O banco de dados 'academico' existe, mas ainda não possui tabelas. Você pode precisar executar o script de criação das tabelas.</div>";
            }
            
        } catch (Exception $e) {
            echo "<h2 class='error'>Erro ao conectar com o banco de dados</h2>";
            echo "<p class='error'>{$e->getMessage()}</p>";
            
            // Verifica se o erro é relacionado ao banco de dados não existir
            if (strpos($e->getMessage(), "Unknown database") !== false) {
                echo "<div class='info-box'>O banco de dados 'academico' não existe. Você precisa criar o banco de dados antes de continuar.</div>";
            }
            
            echo "<h2>Verificações a fazer:</h2>";
            echo "<ul>";
            echo "<li>O servidor MySQL está em execução?</li>";
            echo "<li>O banco de dados 'academico' existe?</li>";
            echo "<li>O usuário e senha estão corretos?</li>";
            echo "<li>As configurações em /config/database.php estão corretas?</li>";
            echo "</ul>";
            
            // Exibe as configurações atuais (sem a senha)
            $config = require_once __DIR__ . '/config/database.php';
            echo "<h2>Configurações atuais:</h2>";
            echo "<ul>";
            echo "<li>Host: {$config['host']}</li>";
            echo "<li>Banco de dados: {$config['dbname']}</li>";
            echo "<li>Usuário: {$config['username']}</li>";
            echo "<li>Charset: {$config['charset']}</li>";
            echo "</ul>";
        }
        ?>
    </div>
</body>
</html>