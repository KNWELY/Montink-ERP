<?php
require_once __DIR__ . '/../config/db.php';

class Estoque {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function listarPorProduto($produto_id) {
        $query = "SELECT e.*, v.nome AS variacao_nome 
                  FROM estoque e
                  LEFT JOIN variacoes v ON e.variacao_id = v.id
                  WHERE e.produto_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarQuantidade($produto_id, $variacao_id, $quantidade) {
        $query = "UPDATE estoque 
                  SET quantidade = ? 
                  WHERE produto_id = ? AND variacao_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$quantidade, $produto_id, $variacao_id]);
    }

    public function criar($produto_id, $variacao_id, $quantidade) {
        $query = "INSERT INTO estoque (produto_id, variacao_id, quantidade)
                  VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$produto_id, $variacao_id, $quantidade]);
    }

    public function buscarPorProdutoVariacao($produto_id, $variacao_id) {
        $query = "SELECT * FROM estoque WHERE produto_id = ? AND variacao_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$produto_id, $variacao_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
