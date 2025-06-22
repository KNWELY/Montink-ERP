<?php
require_once __DIR__ . '/../models/Cupom.php';

class CupomController {
    private $cupomModel;

    public function __construct() {
        $this->cupomModel = new Cupom();
    }

    public function index() {
        $cupons = $this->cupomModel->listarTodos();
        include __DIR__ . '/../views/cupom/index.php';
    }

    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = trim($_POST['codigo'] ?? '');
            $tipo = $_POST['tipo'] ?? 'valor_fixo';
            $valor = floatval($_POST['valor'] ?? 0);
            $valor_minimo = floatval($_POST['valor_minimo'] ?? 0);
            $validade = $_POST['validade'] ?? null;

            if ($codigo && $valor > 0) {
                $this->cupomModel->criar($codigo, $tipo, $valor, $valor_minimo, $validade);
                $_SESSION['sucesso'] = 'Cupom criado com sucesso.';
                header('Location: /montink-erp/cupom');
                exit;
            } else {
                $_SESSION['erro'] = 'Dados inválidos para criar cupom.';
            }
        }
        include __DIR__ . '/../views/cupom/criar.php';
    }

    public function deletar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->cupomModel->deletar($id);
            $_SESSION['sucesso'] = 'Cupom removido.';
        } else {
            $_SESSION['erro'] = 'ID do cupom ausente.';
        }
        header('Location: /montink-erp/cupom');
        exit;
    }
}
