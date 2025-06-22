<?php
class Database {
    private $host = "localhost";
    private $db_name = "montink_erp";
    private $username = "root";
    private $password = "";
    public $conn;

    public function conectar() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8mb4");
        } catch (PDOException $exception) {
            die("Erro de conexão: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
