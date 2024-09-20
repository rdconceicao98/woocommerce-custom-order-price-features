<?php
// Botão de notificação de produto em estoque
function wc_custom_order_price_notify_me_when_in_stock() {
    global $product;
    
    if (!$product->is_in_stock()) {
        echo '<button id="notify_me_in_stock" data-product_id="' . $product->get_id() . '">Notifique-me quando estiver em estoque</button>';
    }
}
add_action('woocommerce_single_product_summary', 'wc_custom_order_price_notify_me_when_in_stock', 35);