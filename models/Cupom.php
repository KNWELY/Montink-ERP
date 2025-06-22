<?php
require_once __DIR__ . '/../config/db.php';

class Cupom {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function listarTodos() {
        $query = "SELECT * FROM cupons ORDER BY criado_em DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($codigo, $tipo, $valor, $valor_minimo, $validade) {
        $query = "INSERT INTO cupons (codigo, tipo, valor, valor_minimo, validade) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$codigo, $tipo, $valor, $valor_minimo, $validade]);
    }

    public function buscarPorCodigo($codigo) {
        $query = "SELECT * FROM cupons WHERE codigo = ? AND validade >= CURDATE() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deletar($id) {
        $query = "DELETE FROM cupons WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
