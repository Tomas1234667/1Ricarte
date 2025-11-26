<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
       
        $dsn = "mysql:host=localhost:3307;dbname=codelib2;charset=utf8mb4";
        try {
            
            $this->pdo = new PDO($dsn, 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            
            echo "<h1> ¡ERROR CRÍTICO!</h1>";
            die("Error de conexión: " . $e->getMessage());
        }
    }

   
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function selectAll($table) {
        $stmt = $this->pdo->prepare("SELECT * FROM $table");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}