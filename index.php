<?php
ob_start();
session_start();

$basePath = '/montink-erp';
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace($basePath, '', $requestUri);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    case '/':
        $title = 'Montink ERP';
        ob_start();
        ?>
        <h1 class="mb-4">Bem-vindo ao Montink ERP</h1>
        <p>Use o menu acima para navegar pelas funcionalidades.</p>
        <?php
        $content = ob_get_clean();
        include __DIR__ . '/views/layout.php';
        break;

    case '/produtos':
        require_once __DIR__ . '/controllers/ProdutoController.php';
        $controller = new ProdutoController();
        $controller->index();
        break;

    case '/produtos/salvar':
        require_once __DIR__ . '/controllers/ProdutoController.php';
        $controller = new ProdutoController();
        $controller->salvar();
        break;

    case '/produtos/comprar':
        if ($method === 'POST') {
            require_once __DIR__ . '/controllers/ProdutoController.php';
            $controller = new ProdutoController();
            $controller->comprar();
        }
        break;

    case '/carrinho':
        require_once __DIR__ . '/controllers/CarrinhoController.php';
        $controller = new CarrinhoController();
        $controller->visualizar();
        break;

    case '/carrinho/adicionar':
        if ($method === 'POST') {
            require_once __DIR__ . '/controllers/CarrinhoController.php';
            $controller = new CarrinhoController();
            $controller->adicionar();
        }
        break;

    case '/carrinho/remover':
        require_once __DIR__ . '/controllers/CarrinhoController.php';
        $controller = new CarrinhoController();
        $controller->remover();
        break;

    case '/pedidos':
        require_once __DIR__ . '/controllers/PedidoController.php';
        $controller = new PedidoController();
        $controller->index();
        break;

    case '/pedido/finalizar':
        require_once __DIR__ . '/controllers/PedidoController.php';
        $controller = new PedidoController();
        $controller->finalizar();
        break;

    case '/pedido/sucesso':
        require_once __DIR__ . '/controllers/PedidoController.php';
        $controller = new PedidoController();
        $controller->sucesso();
        break;

    case '/cupom':
        require_once __DIR__ . '/controllers/CupomController.php';
        $controller = new CupomController();
        $controller->index();
        break;

    case '/cupom/criar':
        require_once __DIR__ . '/controllers/CupomController.php';
        $controller = new CupomController();
        $controller->criar();
        break;

    case '/webhook':
        require_once __DIR__ . '/controllers/WebhookController.php';
        $controller = new WebhookController();
        $controller->receber();
        break;

    default:
        http_response_code(404);
        echo "<h1>Erro 404</h1><p>Rota não encontrada: <strong>$uri</strong></p>";
        break;
}

ob_end_flush();
