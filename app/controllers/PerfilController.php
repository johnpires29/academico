<?php
/**
 * Controlador de Perfil
 */

require_once APP_PATH . '/controllers/Controller.php';
require_once APP_PATH . '/models/UsuarioModel.php';

class PerfilController extends Controller {
    private $usuarioModel;
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }
    
    /**
     * Exibe a página de edição de perfil
     */
    public function index() {
        // Verifica se o usuário está autenticado
        if (!$this->requireAuth()) {
            return;
        }
        
        // Obtém os dados do usuário atual
        $usuario = $this->usuarioModel->findById($_SESSION['user_id']);
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuário não encontrado.';
            $this->redirect(base_url('dashboard'));
            return;
        }
        
        // Prepara os dados para a view
        // Verifica se a coluna dados_pessoais existe e tem dados
        try {
            $dados_pessoais = [];
            if (isset($usuario['dados_pessoais']) && !empty($usuario['dados_pessoais'])) {
                $dados_pessoais = json_decode($usuario['dados_pessoais'], true) ?: [];
            }
        } catch (\Exception $e) {
            $dados_pessoais = [];
        }
        
        $data = [
            'usuario' => $usuario,
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'perfil' => (int)$usuario['perfil_id'],
            'dados_pessoais' => $dados_pessoais
        ];
        
        // Carrega a view de edição de perfil
        $this->view('perfil/edit', $data);
    }
    
    /**
     * Processa o formulário de atualização de perfil
     */
    public function update() {
        // Verifica se o usuário está autenticado
        if (!$this->requireAuth()) {
            return;
        }
        
        // Verifica se é uma requisição POST
        if (!$this->isPost()) {
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Verifica o token CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['error'] = 'Token de segurança inválido. Tente novamente.';
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Obtém os dados do formulário
        $data = $this->getPostData(['nome', 'telefone', 'endereco', 'cidade', 'estado', 'cep']);
        
        // Valida os dados
        if (empty($data['nome'])) {
            $_SESSION['error'] = 'O nome é obrigatório.';
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Prepara os dados pessoais
        $dadosPessoais = [
            'telefone' => $data['telefone'] ?? '',
            'endereco' => $data['endereco'] ?? '',
            'cidade' => $data['cidade'] ?? '',
            'estado' => $data['estado'] ?? '',
            'cep' => $data['cep'] ?? ''
        ];
        
        try {
            // Atualiza o nome do usuário
            $this->usuarioModel->update($_SESSION['user_id'], ['nome' => $data['nome']]);
            
            // Atualiza os dados pessoais
            $this->usuarioModel->updatePersonalData($_SESSION['user_id'], $dadosPessoais);
            
            // Atualiza o nome na sessão
            $_SESSION['user_name'] = $data['nome'];
        } catch (\Exception $e) {
            // Em caso de erro, exibe mensagem amigável
            $_SESSION['error'] = 'Ocorreu um erro ao atualizar o perfil. Apenas o nome foi atualizado.';
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Exibe mensagem de sucesso
        $_SESSION['success'] = 'Perfil atualizado com sucesso.';
        $this->redirect(base_url('perfil'));
    }
    
    /**
     * Processa o upload de foto de perfil
     */
    public function uploadPhoto() {
        // Método também pode ser acessado via upload-photo na URL
        // Verifica se o usuário está autenticado
        if (!$this->requireAuth()) {
            return;
        }
        
        // Verifica se é uma requisição POST
        if (!$this->isPost()) {
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Verifica o token CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['error'] = 'Token de segurança inválido. Tente novamente.';
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Verifica se foi enviado um arquivo
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Erro ao enviar a foto. Tente novamente.';
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Verifica o tipo do arquivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['foto']['type'], $allowedTypes)) {
            $_SESSION['error'] = 'Tipo de arquivo não permitido. Use apenas JPG, PNG ou GIF.';
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Verifica o tamanho do arquivo (máximo 2MB)
        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] = 'A foto deve ter no máximo 2MB.';
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Gera um nome único para o arquivo
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fileName = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
        
        // Define o caminho para salvar o arquivo
        $uploadDir = PUBLIC_PATH . '/assets/images/users/';
        
        // Cria o diretório se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move o arquivo para o diretório de upload
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $uploadDir . $fileName)) {
            $_SESSION['error'] = 'Erro ao salvar a foto. Tente novamente.';
            $this->redirect(base_url('perfil'));
            return;
        }
        
        // Atualiza a foto do usuário no banco de dados
        $fotoPath = 'assets/images/users/' . $fileName;
        $this->usuarioModel->updatePhoto($_SESSION['user_id'], $fotoPath);
        
        // Exibe mensagem de sucesso
        $_SESSION['success'] = 'Foto de perfil atualizada com sucesso.';
        $this->redirect(base_url('perfil'));
    }
}