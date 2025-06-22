<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Carrinho - Montink ERP</title>
    <link href="/public/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
<div class="container">
    <h1>Carrinho de Compras</h1>

    <?php if (empty($itens)): ?>
        <p>Seu carrinho está vazio.</p>
        <a href="/montink-erp" class="btn btn-primary">Voltar aos Produtos</a>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário (R$)</th>
                    <th>Subtotal (R$)</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $key => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td><?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td><?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                        <td>
                            <a href="/carrinho/remover?key=<?= urlencode($key) ?>" class="btn btn-danger btn-sm">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mb-3">
            <strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?><br />
            <strong>Frete:</strong> R$ <?= number_format($frete, 2, ',', '.') ?><br />
            <strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?>
        </div>

        <a href="/produtos" class="btn btn-secondary">Continuar Comprando</a>
        <a href="/pedido/finalizar" class="btn btn-success">Finalizar Pedido</a>
    <?php endif; ?>
</div>
</body>
</html>
