<?php
// Registrar histórico de preços dos produtos
function wc_custom_order_price_register_price_history($product_id) {
    $current_price = wc_get_product($product_id)->get_price();
    $price_history = get_post_meta($product_id, '_price_history', true);
    
    if (!is_array($price_history)) {
        $price_history = array();
    }
    
    $price_history[] = array(
        'date' => current_time('mysql'),
        'price' => $current_price,
    );
    
    update_post_meta($product_id, '_price_history', $price_history);
}
add_action('save_post', 'wc_custom_order_price_register_price_history');