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

// Processar a URL para determinar o controlador e a ação
$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Definir controlador, ação e parâmetros
$controller = isset($url[0]) && !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$action = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';

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