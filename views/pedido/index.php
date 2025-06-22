<?php
$title = 'Histórico de Pedidos';
ob_start();
?>

<h1>Pedidos</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Status</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?= $pedido['id'] ?></td>
            <td><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
            <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($pedido['status']) ?></td>
            <td><?= htmlspecialchars($pedido['criado_em']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
