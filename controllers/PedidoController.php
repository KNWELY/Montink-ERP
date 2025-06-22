<?php
require_once __DIR__ . '/../helpers/Carrinho.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../helpers/viacep.php';

class PedidoController {
    private $pedidoModel;

    public function __construct() {
        $this->pedidoModel = new Pedido();
    }

    public function index() {
        $pedidos = $this->pedidoModel->listarTodos();
        include __DIR__ . '/../views/pedido/index.php';
    }

    public function finalizar() {
        $itens = Carrinho::getItens();
        if (empty($itens)) {
            $_SESSION['erro'] = 'Carrinho vazio.';
            header('Location: /montink-erp/carrinho');
            exit;
        }

        $cliente_nome = trim($_POST['cliente_nome'] ?? '');
        $cliente_email = trim($_POST['cliente_email'] ?? '');
        $cliente_cep = preg_replace('/\D/', '', $_POST['cliente_cep'] ?? '');
        $cliente_endereco = trim($_POST['cliente_endereco'] ?? '');
        $cupom_codigo = trim($_POST['cupom_codigo'] ?? '');

        if (!$cliente_nome || !$cliente_cep) {
            $_SESSION['erro'] = 'Nome e CEP são obrigatórios.';
            header('Location: /montink-erp/carrinho');
            exit;
        }

        $dadosEndereco = consultarViaCEP($cliente_cep);
        if ($dadosEndereco) {
            $logradouro = $dadosEndereco['logradouro'] ?? '';
            $bairro = $dadosEndereco['bairro'] ?? '';
            $localidade = $dadosEndereco['localidade'] ?? '';
            $uf = $dadosEndereco['uf'] ?? '';
            $cliente_endereco = "{$logradouro}, {$bairro}, {$localidade} - {$uf}";
        } else {
            $_SESSION['erro'] = 'CEP inválido ou não encontrado.';
            header('Location: /montink-erp/carrinho');
            exit;
        }

        $subtotal = Carrinho::calcularSubtotal();
        $frete = Carrinho::calcularFrete($subtotal);
        $total = $subtotal + $frete;

        $cupom_id = null;
        if ($cupom_codigo) {
            require_once __DIR__ . '/../models/Cupom.php';
            $cupomModel = new Cupom();
            $cupom = $cupomModel->buscarPorCodigo($cupom_codigo);
            if ($cupom && $subtotal >= $cupom['valor_minimo']) {
                if ($cupom['tipo'] === 'valor_fixo') {
                    $total -= $cupom['valor'];
                } elseif ($cupom['tipo'] === 'porcentagem') {
                    $total -= $total * ($cupom['valor'] / 100);
                }
                if ($total < 0) $total = 0;
                $cupom_id = $cupom['id'];
            }
        }

        $pedido_id = $this->pedidoModel->criar(
            $cliente_nome,
            $cliente_email,
            $cliente_cep,
            $cliente_endereco,
            $subtotal,
            $frete,
            $total,
            'pendente',
            $cupom_id
        );

        foreach ($itens as $item) {
            $variacaoId = $item['variacao_id'];

            if (empty($variacaoId) || !is_numeric($variacaoId) || !$this->pedidoModel->variacaoExiste((int)$variacaoId)) {
                $variacaoId = null;
            } else {
                $variacaoId = (int)$variacaoId;
            }

            $this->pedidoModel->adicionarItem(
                $pedido_id,
                $item['produto_id'],
                $variacaoId,
                $item['quantidade'],
                $item['preco']
            );
        }

        Carrinho::limpar();

        $this->enviarEmailConfirmacao($cliente_email, $cliente_nome, $pedido_id, $cliente_endereco, $total);

        $_SESSION['sucesso'] = "Pedido #$pedido_id criado com sucesso e está pendente de pagamento.";
        header('Location: /montink-erp/pedido/sucesso?id=' . $pedido_id);
        exit;
    }

    private function enviarEmailConfirmacao($email, $nome, $pedido_id, $endereco, $total) {
        $assunto = "Confirmação do Pedido #$pedido_id";
        $mensagem = "Olá $nome,\n\nSeu pedido #$pedido_id foi recebido com sucesso.\n\nEndereço de entrega:\n$endereco\n\nValor total: R$ " . number_format($total, 2, ',', '.') . "\n\nObrigado pela compra!";
        $headers = "From: no-reply@montink-erp.com.br\r\nReply-To: no-reply@montink-erp.com.br";

        mail($email, $assunto, $mensagem, $headers);
    }

    public function sucesso() {
        $pedido_id = $_GET['id'] ?? null;
        if (!$pedido_id) {
            header('Location: /montink-erp/produtos');
            exit;
        }
        $pedido = $this->pedidoModel->buscarPorId($pedido_id);
        include __DIR__ . '/../views/pedido/sucesso.php';
    }

    public function atualizarStatus() {
        $pedido_id = $_POST['pedido_id'] ?? null;
        $novo_status = $_POST['status'] ?? null;

        if (!$pedido_id || !in_array($novo_status, ['pendente', 'pago', 'cancelado'])) {
            $_SESSION['erro'] = 'Dados inválidos para atualização de status.';
            header('Location: /montink-erp/pedidos');
            exit;
        }

        $this->pedidoModel->atualizarStatus($pedido_id, $novo_status);

        $_SESSION['sucesso'] = "Status do pedido #$pedido_id atualizado para '$novo_status'.";
        header('Location: /montink-erp/pedidos');
        exit;
    }
}
