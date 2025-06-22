<?php

class Carrinho {
    public static function getItens() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['carrinho'] ?? [];
    }

    public static function calcularSubtotal() {
        $itens = self::getItens();
        $subtotal = 0;
        foreach ($itens as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        return $subtotal;
    }

    public static function calcularFrete($subtotal) {
        if ($subtotal > 200) {
            return 0;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15;
        } else {
            return 20;
        }
    }

    public static function limpar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['carrinho']);
    }
}
