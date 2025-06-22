<?php
$base = '/montink-erp';
$title = 'Pedido Realizado';
?>

<h1>Pedido Realizado com Sucesso</h1>

<?php if (!empty($pedido)): ?>
    <p><strong>ID do Pedido:</strong> <?= htmlspecialchars($pedido['id']) ?></p>
    <p><strong>Nome:</strong> <?= htmlspecialchars($pedido['cliente_nome']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($pedido['cliente_email']) ?></p>
    <p><strong>Endereço:</strong> <?= htmlspecialchars($pedido['cliente_endereco']) ?></p>
    <p><strong>Subtotal:</strong> R$ <?= number_format($pedido['subtotal'], 2, ',', '.') ?></p>
    <p><strong>Frete:</strong> R$ <?= number_format($pedido['frete'], 2, ',', '.') ?></p>
    <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($pedido['status']) ?></p>

    <a href="<?= $base ?>/produtos" class="btn btn-primary mt-3">Voltar para Produtos</a>
<?php else: ?>
    <p>Pedido não encontrado.</p>
    <a href="<?= $base ?>/produtos" class="btn btn-secondary mt-3">Voltar</a>
<?php endif; ?>
