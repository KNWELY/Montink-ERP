<?php
require_once __DIR__ . '/../config/db.php';

class Pedido {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conectar();
    }

    public function criar($cliente_nome, $cliente_email, $cliente_cep, $cliente_endereco, $subtotal, $frete, $total, $status, $cupom_id = null) {
        $query = "INSERT INTO pedidos (cliente_nome, cliente_email, cliente_cep, cliente_endereco, subtotal, frete, total, status, cupom_id) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$cliente_nome, $cliente_email, $cliente_cep, $cliente_endereco, $subtotal, $frete, $total, $status, $cupom_id]);
        return $this->conn->lastInsertId();
    }

    public function adicionarItem($pedido_id, $produto_id, $variacao_id, $quantidade, $preco_unitario) {
        $query = "INSERT INTO pedido_itens (pedido_id, produto_id, variacao_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(1, $pedido_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $produto_id, PDO::PARAM_INT);
        if ($variacao_id === null || $variacao_id === '' || !is_numeric($variacao_id)) {
            $stmt->bindValue(3, null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(3, (int)$variacao_id, PDO::PARAM_INT);
        }
        $stmt->bindValue(4, $quantidade, PDO::PARAM_INT);
        $stmt->bindValue(5, $preco_unitario);

        return $stmt->execute();
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function deletar($id) {
        $stmtItens = $this->conn->prepare("DELETE FROM pedido_itens WHERE pedido_id = ?");
        $stmtItens->execute([$id]);

        $stmtPedido = $this->conn->prepare("DELETE FROM pedidos WHERE id = ?");
        return $stmtPedido->execute([$id]);
    }

    public function listarTodos() {
        $stmt = $this->conn->query("SELECT * FROM pedidos ORDER BY criado_em DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function variacaoExiste($variacao_id) {
        $query = "SELECT COUNT(*) FROM variacoes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$variacao_id]);
        return $stmt->fetchColumn() > 0;
    }
}
