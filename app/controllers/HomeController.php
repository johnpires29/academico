<?php
/**
 * Controlador da página inicial
 */

require_once APP_PATH . '/controllers/Controller.php';

class HomeController extends Controller {
    /**
     * Construtor
     */
    public function __construct() {
        // Construtor vazio
    }
    
    /**
     * Exibe a página inicial
     */
    public function index() {
        // Se o usuário estiver autenticado, redireciona para o dashboard
        if (is_authenticated()) {
            $this->redirect(base_url('dashboard'));
        }
        
        // Caso contrário, exibe a página inicial
        $this->view('home/index');
    }
    
    /**
     * Exibe a página sobre
     */
    public function about() {
        $this->view('home/about');
    }
    
    /**
     * Exibe a página de contato
     */
    public function contact() {
        $this->view('home/contact');
    }
    
    /**
     * Processa o formulário de contato
     */
    public function sendContact() {
        // Verifica se é uma requisição POST
        if (!$this->isPost()) {
            $this->redirect(base_url('home/contact'));
        }
        
        // Verifica o token CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['error'] = 'Token de segurança inválido. Tente novamente.';
            $this->redirect(base_url('home/contact'));
        }
        
        // Obtém os dados do formulário
        $data = $this->getPostData(['nome', 'email', 'assunto', 'mensagem']);
        
        // Valida os dados
        if (empty($data['nome']) || empty($data['email']) || empty($data['assunto']) || empty($data['mensagem'])) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos.';
            $this->redirect(base_url('home/contact'));
        }
        
        // Aqui seria implementado o envio do email
        // Por enquanto, apenas simula o envio
        
        // Exibe mensagem de sucesso
        $_SESSION['success'] = 'Mensagem enviada com sucesso. Entraremos em contato em breve.';
        $this->redirect(base_url('home/contact'));
    }
}