<?php
require_once __DIR__ . '/../config/db.php';

class Produto {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function listarTodos() {
        $query = "SELECT * FROM produtos ORDER BY criado_em DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($nome, $preco, $descricao) {
        $query = "INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nome, $preco, $descricao]);
        return $this->conn->lastInsertId();
    }

    public function atualizar($id, $nome, $preco, $descricao) {
        $query = "UPDATE produtos SET nome = ?, preco = ?, descricao = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nome, $preco, $descricao, $id]);
    }
}
