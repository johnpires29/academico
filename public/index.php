<?php
/**
 * Arquivo principal de entrada da aplicação
 * Implementa um sistema de roteamento simples
 */

// Definir constantes do sistema
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('HELPERS_PATH', BASE_PATH . '/helpers');

// Carregar autoloader e funções auxiliares
require_once HELPERS_PATH . '/functions.php';

// Iniciar sessão
session_start();

// Verifica e adiciona a coluna dados_pessoais se não existir
try {
    // Carrega o arquivo de configuração do banco de dados
    $dbConfig = require_once BASE_PATH . '/config/database.php';
    
    // Conecta ao banco de dados
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $db = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verifica se a coluna dados_pessoais existe na tabela usuarios
    $stmt = $db->prepare("SHOW COLUMNS FROM usuarios LIKE 'dados_pessoais'");
    $stmt->execute();
    
    // Se a coluna não existir, adiciona
    if ($stmt->rowCount() === 0) {
        $db->exec("ALTER TABLE usuarios ADD COLUMN dados_pessoais TEXT DEFAULT NULL AFTER foto");
    }
} catch (PDOException $e) {
    // Ignora erros para não interromper o carregamento da aplicação
}

// Processar a URL para determinar o controlador e a ação
$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Definir controlador, ação e parâmetros
$controller = isset($url[0]) && !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';

// Converter ação com hífen para camelCase (ex: upload-photo -> uploadPhoto)
$action = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';
if (strpos($action, '-') !== false) {
    $action = preg_replace_callback('/-([a-z])/', function($matches) {
        return strtoupper($matches[1]);
    }, $action);
}

// Remover controlador e ação da URL, deixando apenas os parâmetros
unset($url[0], $url[1]);
$params = array_values($url);

// Verificar se o arquivo do controlador existe
$controllerFile = APP_PATH . '/controllers/' . $controller . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Instanciar o controlador
    $controller = new $controller();
    
    // Verificar se o método existe
    if (method_exists($controller, $action)) {
        // Chamar o método com os parâmetros
        call_user_func_array([$controller, $action], $params);
    } else {
        // Método não encontrado
        require_once APP_PATH . '/views/error/404.php';
    }
} else {
    // Controlador não encontrado
    require_once APP_PATH . '/views/error/404.php';
}