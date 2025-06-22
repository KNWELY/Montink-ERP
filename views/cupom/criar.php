<?php
ob_start();
$base = '/montink-erp';
$title = 'Novo Cupom';
?>

<h1>Novo Cupom</h1>

<form method="POST" action="<?= $base ?>/cupom/criar">
    <div class="mb-3">
        <label for="codigo" class="form-label">Código do Cupom</label>
        <input type="text" name="codigo" id="codigo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="tipo" class="form-label">Tipo de Desconto</label>
        <select name="tipo" id="tipo" class="form-select">
            <option value="valor_fixo">Valor Fixo</option>
            <option value="porcentagem">Porcentagem</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="valor" class="form-label">Valor do Desconto</label>
        <input type="number" step="0.01" name="valor" id="valor" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="valor_minimo" class="form-label">Valor Mínimo do Pedido</label>
        <input type="number" step="0.01" name="valor_minimo" id="valor_minimo" class="form-control">
    </div>

    <div class="mb-3">
        <label for="validade" class="form-label">Validade</label>
        <input type="date" name="validade" id="validade" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Salvar Cupom</button>
    <a href="<?= $base ?>/cupom" class="btn btn-secondary">Voltar</a>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
