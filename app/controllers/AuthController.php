<?php
/**
 * Controlador de autenticação
 */

require_once APP_PATH . '/controllers/Controller.php';
require_once APP_PATH . '/models/UsuarioModel.php';

class AuthController extends Controller {
    private $usuarioModel;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }
    
    /**
     * Exibe a página de login
     */
    public function login() {
        // Se já estiver autenticado, redireciona para o dashboard
        if (is_authenticated()) {
            $this->redirect(base_url('dashboard'));
        }
        
        // Gera um novo token CSRF para o formulário
        $data['csrf_token'] = generate_csrf_token();
        
        $this->view('auth/login', $data);
    }
    
    /**
     * Processa o formulário de login
     */
    public function authenticate() {
        // Verifica se é uma requisição POST
        if (!$this->isPost()) {
            $this->redirect(base_url('auth/login'));
        }
        
        // Verifica o token CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['error'] = 'Token de segurança inválido. Tente novamente.';
            $this->redirect(base_url('auth/login'));
        }
        
        // Obtém os dados do formulário
        $data = $this->getPostData(['email', 'senha']);
        
        // Valida os dados
        if (empty($data['email']) || empty($data['senha'])) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos.';
            $this->redirect(base_url('auth/login'));
        }
        
        // Busca o usuário pelo email
        $usuario = $this->usuarioModel->findByEmail($data['email']);
        
        // Verifica se o usuário existe e a senha está correta
        if (!$usuario || !password_verify($data['senha'], $usuario['senha'])) {
            $_SESSION['error'] = 'Email ou senha incorretos.';
            $this->redirect(base_url('auth/login'));
        }
        
        // Verifica se o usuário está ativo
        if (!$usuario['ativo']) {
            $_SESSION['error'] = 'Sua conta está desativada. Entre em contato com o administrador.';
            $this->redirect(base_url('auth/login'));
        }
        
        // Cria a sessão do usuário
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nome'];
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['user_profile'] = $usuario['perfil_id'];
        
        // Regenera o token CSRF para evitar ataques de fixação de sessão
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        // Redireciona para o dashboard
        $this->redirect(base_url('dashboard'));
    }
    
    /**
     * Realiza o logout do usuário
     */
    public function logout() {
        // Limpa todas as variáveis de sessão
        $_SESSION = array();
        
        // Se houver um cookie de sessão, destrua-o também
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        // Destrói a sessão
        session_destroy();
        
        // Redireciona para a página de login
        $this->redirect(base_url('auth/login'));
    }
    
    /**
     * Exibe a página de redefinição de senha
     */
    public function forgotPassword() {
        // Gera um novo token CSRF para o formulário
        $data['csrf_token'] = generate_csrf_token();
        
        $this->view('auth/forgot_password', $data);
    }
    
    /**
     * Processa o formulário de redefinição de senha
     */
    public function resetPassword() {
        // Verifica se é uma requisição POST
        if (!$this->isPost()) {
            $this->redirect(base_url('auth/forgot-password'));
        }
        
        // Verifica o token CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['error'] = 'Token de segurança inválido. Tente novamente.';
            $this->redirect(base_url('auth/forgot-password'));
        }
        
        // Obtém os dados do formulário
        $data = $this->getPostData(['email']);
        
        // Valida os dados
        if (empty($data['email'])) {
            $_SESSION['error'] = 'Por favor, informe seu email.';
            $this->redirect(base_url('auth/forgot-password'));
        }
        
        // Busca o usuário pelo email
        $usuario = $this->usuarioModel->findByEmail($data['email']);
        
        // Verifica se o usuário existe
        if (!$usuario) {
            $_SESSION['error'] = 'Email não encontrado.';
            $this->redirect(base_url('auth/forgot-password'));
        }
        
        // Gera um token de redefinição de senha
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Salva o token no banco de dados
        $this->usuarioModel->saveResetToken($usuario['id'], $token, $expira);
        
        // Envia o email com o link de redefinição de senha
        // Implementação do envio de email será feita posteriormente
        
        // Exibe mensagem de sucesso
        $_SESSION['success'] = 'Um link para redefinição de senha foi enviado para o seu email.';
        $this->redirect(base_url('auth/login'));
    }
    
    /**
     * Exibe a página de alteração de senha
     */
    public function changePassword() {
        // Verifica se o usuário está autenticado
        if (!$this->requireAuth()) {
            return;
        }
        
        // Gera um novo token CSRF para o formulário
        $data['csrf_token'] = generate_csrf_token();
        
        $this->view('auth/change_password', $data);
    }
    
    /**
     * Processa o formulário de alteração de senha
     */
    public function updatePassword() {
        // Verifica se o usuário está autenticado
        if (!$this->requireAuth()) {
            return;
        }
        
        // Verifica se é uma requisição POST
        if (!$this->isPost()) {
            $this->redirect(base_url('auth/change-password'));
        }
        
        // Verifica o token CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['error'] = 'Token de segurança inválido. Tente novamente.';
            $this->redirect(base_url('auth/change-password'));
        }
        
        // Obtém os dados do formulário
        $data = $this->getPostData(['senha_atual', 'nova_senha', 'confirmar_senha']);
        
        // Valida os dados
        if (empty($data['senha_atual']) || empty($data['nova_senha']) || empty($data['confirmar_senha'])) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos.';
            $this->redirect(base_url('auth/change-password'));
        }
        
        // Verifica se as senhas coincidem
        if ($data['nova_senha'] !== $data['confirmar_senha']) {
            $_SESSION['error'] = 'As senhas não coincidem.';
            $this->redirect(base_url('auth/change-password'));
        }
        
        // Busca o usuário pelo ID
        $usuario = $this->usuarioModel->findById($_SESSION['user_id']);
        
        // Verifica se a senha atual está correta
        if (!password_verify($data['senha_atual'], $usuario['senha'])) {
            $_SESSION['error'] = 'Senha atual incorreta.';
            $this->redirect(base_url('auth/change-password'));
        }
        
        // Atualiza a senha
        $novaSenhaHash = password_hash($data['nova_senha'], PASSWORD_DEFAULT);
        $this->usuarioModel->update($_SESSION['user_id'], ['senha' => $novaSenhaHash]);
        
        // Exibe mensagem de sucesso
        $_SESSION['success'] = 'Senha alterada com sucesso.';
        $this->redirect(base_url('dashboard'));
    }
}