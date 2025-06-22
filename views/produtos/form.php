<?php
ob_start();
$base = '/montink-erp';
$title = 'Cadastro de Produtos';
?>

<h1>Cadastro de Produtos</h1>

<?php if (isset($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']) ?></div>
    <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']) ?></div>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>

<form method="POST" action="<?= $base ?>/produtos/salvar">
    <input type="hidden" name="id" value="<?= isset($produto) ? htmlspecialchars($produto['id']) : '' ?>">

    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="nome" class="form-control" required
               value="<?= isset($produto) ? htmlspecialchars($produto['nome']) : '' ?>">
    </div>

    <div class="mb-3">
        <label>Preço (R$)</label>
        <input type="number" step="0.01" name="preco" class="form-control" required
               value="<?= isset($produto) ? htmlspecialchars($produto['preco']) : '' ?>">
    </div>

    <div class="mb-3">
        <label>Descrição</label>
        <textarea name="descricao" class="form-control"><?= isset($produto) ? htmlspecialchars($produto['descricao']) : '' ?></textarea>
    </div>

    <div class="mb-3">
        <label>Variações e Estoque</label>
        <div id="variacoes-container">
            <?php
            if (isset($produtos)) {
                foreach ($produtos as $p) {
                    if (isset($p['id']) && $p['id'] == ($produto['id'] ?? 0)) {
                        $variacoes = (new Variacao())->listarPorProduto($p['id']);
                        $estoques = (new Estoque())->listarPorProduto($p['id']);
                        foreach ($variacoes as $i => $var) {
                            $estoqueQty = 0;
                            foreach ($estoques as $e) {
                                if ($e['variacao_id'] == $var['id']) {
                                    $estoqueQty = $e['quantidade'];
                                    break;
                                }
                            }
                            ?>
                            <div class="input-group mb-2">
                                <input type="text" name="variacoes[]" class="form-control" placeholder="Variação"
                                       value="<?= htmlspecialchars($var['nome']) ?>">
                                <input type="number" name="estoques[]" class="form-control" placeholder="Estoque" min="0"
                                       value="<?= $estoqueQty ?>" style="max-width:100px;">
                                <button type="button" class="btn btn-danger" onclick="removerVariacao(this)">Excluir</button>
                            </div>
                            <?php
                        }
                    }
                }
            }
            ?>
        </div>
        <button type="button" class="btn btn-secondary" onclick="adicionarVariacao()">Adicionar Variação</button>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Produto</button>
</form>

<?php if (isset($produto['id'])): ?>
    <hr>
    <h2>Comprar Produto</h2>
    <form method="POST" action="<?= $base ?>/carrinho/adicionar">
        <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">

        <div class="mb-3">
            <label for="variacao_id">Variação</label>
            <select name="variacao_id" class="form-select" required>
                <?php foreach ($variacoes as $v): ?>
                    <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="quantidade">Quantidade</label>
            <input type="number" name="quantidade" class="form-control" min="1" value="1" required>
        </div>

        <button type="submit" class="btn btn-success">Comprar</button>
    </form>
<?php endif; ?>

<script>
    function adicionarVariacao() {
        const container = document.getElementById('variacoes-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" name="variacoes[]" class="form-control" placeholder="Variação (ex: Tamanho M)">
            <input type="number" name="estoques[]" class="form-control" placeholder="Estoque" min="0" value="0" style="max-width:100px;">
            <button type="button" class="btn btn-danger" onclick="removerVariacao(this)">Excluir</button>
        `;
        container.appendChild(div);
    }

    function removerVariacao(btn) {
        btn.parentNode.remove();
    }
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
