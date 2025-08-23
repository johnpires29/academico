<?php
/**
 * Classe de conexão com o banco de dados
 */

class Connection {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $config = require_once __DIR__ . '/database.php';
        
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            throw new Exception("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}