<?php
$title = "Finalizar Pedido";
include __DIR__ . '/../layout.php';
?>

<form method="POST" action="/montink-erp/pedido/finalizar" class="mb-4">
    <div class="mb-3">
        <label for="cliente_nome" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" id="cliente_nome" name="cliente_nome" required />
    </div>
    <div class="mb-3">
        <label for="cliente_email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="cliente_email" name="cliente_email" />
    </div>
    <div class="mb-3">
        <label for="cliente_cep" class="form-label">CEP</label>
        <input type="text" class="form-control" id="cliente_cep" name="cliente_cep" maxlength="9" pattern="\d{5}-?\d{3}" required />
        <div class="form-text">Formato: 00000-000</div>
    </div>
    <div class="mb-3">
        <label for="cliente_endereco" class="form-label">Endereço</label>
        <input type="text" class="form-control" id="cliente_endereco" name="cliente_endereco" readonly />
    </div>
    <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
</form>

<script>
    document.getElementById('cliente_cep').addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');
        if (cep.length !== 8) return;

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    const endereco = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                    document.getElementById('cliente_endereco').value = endereco;
                } else {
                    alert('CEP não encontrado.');
                    document.getElementById('cliente_endereco').value = '';
                }
            })
            .catch(() => {
                alert('Erro ao consultar CEP.');
            });
    });
</script>
