<?php
class Database {
    
    private $host = "localhost";
    // IMPORTANTE: Aquí deberás crear una BD nueva en phpMyAdmin que tenga las tablas de ambos.
    // Por ahora le pondremos este nombre:
    private $db_name = "sistema_unificado"; 
    private $username = "root";       
    private $password = "";
    private $charset = "utf8mb4";
    
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        // Cadena de conexión completa
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     
            PDO::ATTR_EMULATE_PREPARES   => false,                  
        ];

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $exception) {
            echo "Error de Conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>