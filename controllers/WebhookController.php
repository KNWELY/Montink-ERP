<?php
require_once __DIR__ . '/../models/Pedido.php';

class WebhookController {
    private $pedidoModel;

    public function __construct() {
        $this->pedidoModel = new Pedido();
    }

    public function receber() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!$data || !isset($data['id'], $data['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            exit;
        }

        $pedidoId = $data['id'];
        $status = $data['status'];
        $statusPermitidos = ['pendente', 'pago', 'cancelado'];

        if (!in_array($status, $statusPermitidos)) {
            http_response_code(400);
            echo json_encode(['error' => 'Status inválido']);
            exit;
        }

        if ($status === 'cancelado') {
            $this->pedidoModel->deletar($pedidoId);
            http_response_code(200);
            echo json_encode(['message' => "Pedido $pedidoId removido"]);
            exit;
        }

        $this->pedidoModel->atualizarStatus($pedidoId, $status);
        http_response_code(200);
        echo json_encode(['message' => "Pedido $pedidoId atualizado para status $status"]);
        exit;
    }
}
