<?php
ob_start();
$title = 'Carrinho de Compras';
$base = '/montink-erp';
?>

<h1>Carrinho de Compras</h1>

<?php if (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']) ?></div>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']) ?></div>
    <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?>

<?php if (empty($_SESSION['carrinho'])): ?>
    <p>Seu carrinho está vazio.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Variação</th>
                <th>Preço</th>
                <th>Qtd</th>
                <th>Subtotal</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $subtotal = 0;
            foreach ($_SESSION['carrinho'] as $key => $item):
                $itemSubtotal = $item['preco'] * $item['quantidade'];
                $subtotal += $itemSubtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td><?= htmlspecialchars($item['variacao']) ?></td>
                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($itemSubtotal, 2, ',', '.') ?></td>
                    <td>
                        <form action="<?= $base ?>/carrinho/remover" method="POST" style="display:inline;">
                            <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                            <button class="btn btn-danger btn-sm">Remover</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    if ($subtotal > 200) {
        $frete = 0;
    } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
        $frete = 15;
    } else {
        $frete = 20;
    }
    $total = $subtotal + $frete;
    ?>

    <p><strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
    <p><strong>Frete:</strong> R$ <?= number_format($frete, 2, ',', '.') ?></p>
    <p><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>

    <h3>Finalizar Pedido</h3>
    <form action="<?= $base ?>/pedido/finalizar" method="POST">
        <div class="mb-3">
            <label>Nome do Cliente</label>
            <input type="text" name="cliente_nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email do Cliente</label>
            <input type="email" name="cliente_email" class="form-control">
        </div>
        <div class="mb-3">
            <label>CEP</label>
            <input type="text" name="cliente_cep" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Endereço (opcional)</label>
            <input type="text" name="cliente_endereco" class="form-control">
        </div>
        <div class="mb-3">
            <label>Cupom de Desconto</label>
            <input type="text" name="cupom_codigo" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Finalizar Pedido</button>
    </form>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
