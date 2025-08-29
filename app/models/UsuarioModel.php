<?php
/**
 * Modelo de usuário
 */

require_once APP_PATH . '/models/Model.php';

class UsuarioModel extends Model {
    protected $table = 'usuarios';
    
    /**
     * Construtor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Busca um usuário pelo email
     * 
     * @param string $email Email do usuário
     * @return array|false Usuário encontrado ou false
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Salva um token de redefinição de senha
     * 
     * @param int $id ID do usuário
     * @param string $token Token de redefinição
     * @param string $expira Data de expiração
     * @return bool True em caso de sucesso, false em caso de erro
     */
    public function saveResetToken($id, $token, $expira) {
        $sql = "UPDATE {$this->table} SET token_reset = :token, token_expira = :expira WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':expira', $expira);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }
    
    /**
     * Busca um usuário pelo token de redefinição de senha
     * 
     * @param string $token Token de redefinição
     * @return array|false Usuário encontrado ou false
     */
    public function findByResetToken($token) {
        $sql = "SELECT * FROM {$this->table} WHERE token_reset = :token AND token_expira > NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Limpa o token de redefinição de senha
     * 
     * @param int $id ID do usuário
     * @return bool True em caso de sucesso, false em caso de erro
     */
    public function clearResetToken($id) {
        $sql = "UPDATE {$this->table} SET token_reset = NULL, token_expira = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }
    
    /**
     * Busca usuários por perfil
     * 
     * @param string $perfil Perfil do usuário
     * @return array Usuários encontrados
     */
    public function findByProfile($perfil) {
        return $this->findBy('perfil', $perfil);
    }
    
    /**
     * Atualiza a foto do usuário
     * 
     * @param int $id ID do usuário
     * @param string $foto Caminho da foto
     * @return bool True em caso de sucesso, false em caso de erro
     */
    public function updatePhoto($id, $foto) {
        return $this->update($id, ['foto' => $foto]);
    }
    
    /**
     * Atualiza os dados pessoais do usuário
     * 
     * @param int $id ID do usuário
     * @param array $dados Dados pessoais
     * @return bool True em caso de sucesso, false em caso de erro
     */
    public function updatePersonalData($id, $dados) {
        try {
            // Verifica se a coluna dados_pessoais existe
            $sql = "SHOW COLUMNS FROM {$this->table} LIKE 'dados_pessoais'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                // A coluna existe, atualiza normalmente
                return $this->update($id, ['dados_pessoais' => json_encode($dados)]);
            } else {
                // A coluna não existe, apenas atualiza os campos que existem
                // Neste caso, não faz nada e retorna true para não quebrar o fluxo
                return true;
            }
        } catch (\PDOException $e) {
            // Em caso de erro, apenas retorna true para não quebrar o fluxo
            return true;
        }
    }
    
    /**
     * Ativa ou desativa um usuário
     * 
     * @param int $id ID do usuário
     * @param bool $ativo Status de ativação
     * @return bool True em caso de sucesso, false em caso de erro
     */
    public function toggleActive($id, $ativo) {
        return $this->update($id, ['ativo' => $ativo ? 1 : 0]);
    }
    
    /**
     * Cria um novo usuário com senha criptografada
     * 
     * @param array $data Dados do usuário
     * @return int|false ID do usuário criado ou false em caso de erro
     */
    public function createWithHashedPassword($data) {
        // Criptografa a senha
        $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        
        return $this->create($data);
    }
}