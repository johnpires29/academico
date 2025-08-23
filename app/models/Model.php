<?php
/**
 * Classe base para todos os modelos
 */

require_once CONFIG_PATH . '/Connection.php';

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    /**
     * Construtor
     */
    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }
    
    /**
     * Busca todos os registros da tabela
     * 
     * @param string $orderBy Campo para ordenação
     * @param string $order Direção da ordenação (ASC ou DESC)
     * @return array Registros encontrados
     */
    public function findAll($orderBy = null, $order = 'ASC') {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy} {$order}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Busca um registro pelo ID
     * 
     * @param int $id ID do registro
     * @return array|false Registro encontrado ou false
     */
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Busca registros por um campo específico
     * 
     * @param string $field Campo a ser buscado
     * @param mixed $value Valor a ser buscado
     * @return array Registros encontrados
     */
    public function findBy($field, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Insere um novo registro
     * 
     * @param array $data Dados a serem inseridos
     * @return int|false ID do registro inserido ou false em caso de erro
     */
    public function create($data) {
        $fields = array_keys($data);
        $placeholders = array_map(function($field) {
            return ":$field";
        }, $fields);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $field => $value) {
            $stmt->bindValue(":$field", $value);
        }
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Atualiza um registro
     * 
     * @param int $id ID do registro
     * @param array $data Dados a serem atualizados
     * @return bool True em caso de sucesso, false em caso de erro
     */
    public function update($id, $data) {
        $fields = array_map(function($field) {
            return "$field = :$field";
        }, array_keys($data));
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        foreach ($data as $field => $value) {
            $stmt->bindValue(":$field", $value);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Remove um registro
     * 
     * @param int $id ID do registro
     * @return bool True em caso de sucesso, false em caso de erro
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }
    
    /**
     * Conta o número de registros na tabela
     * 
     * @param string $where Condição WHERE (opcional)
     * @param array $params Parâmetros para a condição WHERE (opcional)
     * @return int Número de registros
     */
    public function count($where = '', $params = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        
        if ($where) {
            $sql .= " WHERE $where";
        }
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $param => $value) {
            $stmt->bindValue(":$param", $value);
        }
        
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Executa uma consulta SQL personalizada
     * 
     * @param string $sql Consulta SQL
     * @param array $params Parâmetros para a consulta
     * @return array Resultados da consulta
     */
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $param => $value) {
            $stmt->bindValue(":$param", $value);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}