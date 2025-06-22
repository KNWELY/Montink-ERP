<?php
$base = '/montink-erp';
$title = 'Cupons disponíveis';
ob_start();
?>

<h1>Cupons</h1>

<a href="<?= $base ?>/cupom/criar" class="btn btn-primary mb-3">Novo Cupom</a>

<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']) ?></div>
    <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']) ?></div>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr><th>Código</th><th>Tipo</th><th>Valor</th><th>Min. pedido</th><th>Validade</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php foreach ($cupons as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['codigo']) ?></td>
            <td><?= htmlspecialchars($c['tipo']) ?></td>
            <td><?= htmlspecialchars($c['valor']) ?></td>
            <td><?= htmlspecialchars($c['valor_minimo']) ?></td>
            <td><?= htmlspecialchars($c['validade']) ?></td>
            <td><a href="<?= $base ?>/cupom/deletar?id=<?= $c['id'] ?>" class="btn btn-danger btn-sm">Excluir</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
