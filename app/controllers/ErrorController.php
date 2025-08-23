<?php
/**
 * Controlador de erros
 */

require_once APP_PATH . '/controllers/Controller.php';

class ErrorController extends Controller {
    /**
     * Construtor
     */
    public function __construct() {
        // Construtor vazio
    }
    
    /**
     * Exibe a página de erro 404 (não encontrado)
     */
    public function notFound() {
        http_response_code(404);
        $this->view('error/404');
    }
    
    /**
     * Exibe a página de erro 403 (acesso negado)
     */
    public function forbidden() {
        http_response_code(403);
        $this->view('error/403');
    }
    
    /**
     * Exibe a página de erro 500 (erro interno do servidor)
     */
    public function serverError() {
        http_response_code(500);
        $this->view('error/500');
    }
}