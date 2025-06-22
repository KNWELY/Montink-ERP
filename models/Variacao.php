<?php
require_once __DIR__ . '/../config/db.php';

class Variacao {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function listarPorProduto($produto_id) {
        $query = "SELECT * FROM variacoes WHERE produto_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($produto_id, $nome) {
        $query = "INSERT INTO variacoes (produto_id, nome) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$produto_id, $nome]);
        return $this->conn->lastInsertId();
    }

    public function deletarPorProduto($produto_id) {
        $query = "DELETE FROM variacoes WHERE produto_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$produto_id]);
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM variacoes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
