<?php
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../models/Variacao.php';
require_once __DIR__ . '/../models/Estoque.php';

class ProdutoController {
    private $produtoModel;
    private $variacaoModel;
    private $estoqueModel;

    public function __construct() {
        $this->produtoModel = new Produto();
        $this->variacaoModel = new Variacao();
        $this->estoqueModel = new Estoque();
    }

    public function index() {
        $base = '/montink-erp';
        $produtos = $this->produtoModel->listarTodos();
        $produto = null;
        $variacoes = [];

    if (isset($_GET['id'])) {
        $produto = $this->produtoModel->buscarPorId($_GET['id']);
        if ($produto) {
            $variacoes = $this->variacaoModel->listarPorProduto($produto['id']);
        }
    }

    include __DIR__ . '/../views/produtos/form.php';
}

    public function salvar() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $id = $_POST['id'] ?? null;
        $nome = $_POST['nome'] ?? '';
        $preco = $_POST['preco'] ?? 0;
        $descricao = $_POST['descricao'] ?? '';

        if ($id) {
            $this->produtoModel->atualizar($id, $nome, $preco, $descricao);
        } else {
            $id = $this->produtoModel->criar($nome, $preco, $descricao);
        }

        $variacoes = $_POST['variacoes'] ?? [];
        $estoques = $_POST['estoques'] ?? [];

        $this->variacaoModel->deletarPorProduto($id);

        foreach ($variacoes as $index => $varNome) {
            $varNome = trim($varNome);
            if ($varNome === '') continue;

            $varId = $this->variacaoModel->criar($id, $varNome);
            $quantidade = isset($estoques[$index]) ? (int)$estoques[$index] : 0;
            $this->estoqueModel->criar($id, $varId, $quantidade);
        }

        $_SESSION['sucesso'] = 'Produto salvo com sucesso.';
        header('Location: /montink-erp/produtos?id=' . $id);
        exit;
    }

    public function comprar() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $produtoId = $_POST['produto_id'] ?? null;
        $variacaoId = $_POST['variacao_id'] ?? null;
        $quantidade = (int) ($_POST['quantidade'] ?? 1);

        if (!$produtoId || !$variacaoId || $quantidade < 1) {
            $_SESSION['erro'] = 'Dados inválidos.';
            header('Location: /montink-erp/produtos');
            exit;
        }

        $produto = $this->produtoModel->buscarPorId($produtoId);
        $estoque = $this->estoqueModel->buscarPorProdutoVariacao($produtoId, $variacaoId);

        if (!$produto || !$estoque || $estoque['quantidade'] < $quantidade) {
            $_SESSION['erro'] = 'Estoque insuficiente.';
            header('Location: /montink-erp/produtos');
            exit;
        }

        $_SESSION['carrinho'] ??= [];
        $key = "{$produtoId}-{$variacaoId}";

        if (isset($_SESSION['carrinho'][$key])) {
            $_SESSION['carrinho'][$key]['quantidade'] += $quantidade;
        } else {
            $variacao = $this->variacaoModel->buscarPorId($variacaoId);
            $_SESSION['carrinho'][$key] = [
                'produto_id' => $produtoId,
                'variacao_id' => $variacaoId,
                'nome' => $produto['nome'],
                'variacao' => $variacao['nome'] ?? '',
                'preco' => $produto['preco'],
                'quantidade' => $quantidade,
            ];
        }

        $_SESSION['sucesso'] = 'Produto adicionado ao carrinho';
        header('Location: /montink-erp/produtos');
        exit;
    }
}
