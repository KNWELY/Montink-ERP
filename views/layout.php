<?php
$base = '/montink-erp';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title><?= $title ?? 'Montink ERP' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= $base ?>/produtos">Montink ERP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>/produtos">Produtos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>/cupom">Cupons</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>/carrinho">Carrinho</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $base ?>/pedidos">Pedidos</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?php
    if (isset($_SESSION['erro'])) {
        echo '<div class="alert alert-danger">'.htmlspecialchars($_SESSION['erro']).'</div>';
        unset($_SESSION['erro']);
    }
    if (isset($_SESSION['sucesso'])) {
        echo '<div class="alert alert-success">'.htmlspecialchars($_SESSION['sucesso']).'</div>';
        unset($_SESSION['sucesso']);
    }
    ?>

    <?= $content ?? '' ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
