<?php
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../models/Variacao.php';
require_once __DIR__ . '/../models/Estoque.php';

class CarrinhoController {
    public function adicionar() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $produtoId = $_POST['produto_id'] ?? null;
        $variacaoId = $_POST['variacao_id'] ?? null;
        $quantidade = (int) ($_POST['quantidade'] ?? 1);

        if (!$produtoId || !$variacaoId || $quantidade < 1) {
            $_SESSION['erro'] = 'Dados inválidos.';
            header('Location: /montink-erp/produtos');
            exit;
        }

        $produtoModel = new Produto();
        $variacaoModel = new Variacao();
        $estoqueModel = new Estoque();

        $produto = $produtoModel->buscarPorId($produtoId);
        $estoque = $estoqueModel->buscarPorProdutoVariacao($produtoId, $variacaoId);

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
            $variacao = $variacaoModel->buscarPorId($variacaoId);
            $_SESSION['carrinho'][$key] = [
                'produto_id' => $produtoId,
                'variacao_id' => $variacaoId,
                'nome' => $produto['nome'],
                'variacao' => $variacao['nome'] ?? '',
                'preco' => $produto['preco'],
                'quantidade' => $quantidade,
            ];
        }

        $_SESSION['sucesso'] = 'Produto adicionado ao carrinho.';
        header('Location: /montink-erp/carrinho');
        exit;
    }

    public function visualizar() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $itens = $_SESSION['carrinho'] ?? [];

        $subtotal = 0;
        foreach ($itens as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        if ($subtotal > 200) {
            $frete = 0;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } else {
            $frete = 20;
        }

        $total = $subtotal + $frete;
        $base = '/montink-erp';

        include __DIR__ . '/../views/carrinho/visualizar.php';
    }

    public function remover() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $indice = $_POST['key'] ?? null;

        if ($indice !== null && isset($_SESSION['carrinho'][$indice])) {
            unset($_SESSION['carrinho'][$indice]);
            $_SESSION['sucesso'] = 'Item removido do carrinho.';
        } else {
            $_SESSION['erro'] = 'Item não encontrado no carrinho.';
        }

        header('Location: /montink-erp/carrinho');
        exit;
    }
}
