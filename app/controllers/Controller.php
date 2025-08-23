<?php
/**
 * Classe base para todos os controladores
 */

class Controller {
    /**
     * Carrega uma view
     * 
     * @param string $view Nome da view a ser carregada
     * @param array $data Dados a serem passados para a view
     * @return void
     */
    protected function view($view, $data = []) {
        // Adicionar token CSRF aos dados
        $data['csrf_token'] = generate_csrf_token();
        
        // Usar a função helper para carregar a view
        view($view, $data);
    }
    
    /**
     * Redireciona para uma URL específica
     * 
     * @param string $url URL para redirecionamento
     * @return void
     */
    protected function redirect($url) {
        redirect($url);
    }
    
    /**
     * Verifica se o usuário está autenticado
     * Se não estiver, redireciona para a página de login
     * 
     * @return bool True se autenticado, false caso contrário
     */
    protected function requireAuth() {
        if (!is_authenticated()) {
            $this->redirect(base_url('auth/login'));
            return false;
        }
        
        return true;
    }
    
    /**
     * Verifica se o usuário tem permissão para acessar determinada área
     * Se não tiver, redireciona para a página de acesso negado
     * 
     * @param string|array $profiles Perfil ou array de perfis permitidos
     * @return bool True se tem permissão, false caso contrário
     */
    protected function requirePermission($profiles) {
        if (!$this->requireAuth()) {
            return false;
        }
        
        if (!has_permission($profiles)) {
            $this->redirect(base_url('error/forbidden'));
            return false;
        }
        
        return true;
    }
    
    /**
     * Retorna dados do formulário POST sanitizados
     * 
     * @param array $fields Campos a serem obtidos (opcional)
     * @return array Dados sanitizados
     */
    protected function getPostData($fields = null) {
        if ($fields === null) {
            return sanitize($_POST);
        }
        
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = isset($_POST[$field]) ? sanitize($_POST[$field]) : null;
        }
        
        return $data;
    }
    
    /**
     * Verifica se a requisição é do tipo POST
     * 
     * @return bool True se for POST, false caso contrário
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Verifica se o token CSRF é válido
     * 
     * @return bool True se válido, false caso contrário
     */
    protected function validateCsrfToken() {
        if (!isset($_POST['csrf_token'])) {
            return false;
        }
        
        return verify_csrf_token($_POST['csrf_token']);
    }
    
    /**
     * Retorna uma resposta JSON
     * 
     * @param mixed $data Dados a serem retornados
     * @param int $status Código de status HTTP
     * @return void
     */
    protected function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}