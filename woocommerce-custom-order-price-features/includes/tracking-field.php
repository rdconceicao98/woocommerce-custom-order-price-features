<?php
// Campo para adicionar código de rastreamento ao pedido
function wc_custom_order_price_add_tracking_field($post) {
    woocommerce_wp_text_input(array(
        'id' => '_tracking_code',
        'label' => __('Código de Rastreamento', 'wc-custom-order-price'),
        'description' => __('Adicione o código de rastreamento para o pedido.', 'wc-custom-order-price'),
        'desc_tip' => true,
    ));
}
add_action('woocommerce_admin_order_data_after_order_details', 'wc_custom_order_price_add_tracking_field');

// Salvar código de rastreamento
function wc_custom_order_price_save_tracking_code($post_id) {
    $tracking_code = isset($_POST['_tracking_code']) ? sanitize_text_field($_POST['_tracking_code']) : '';
    update_post_meta($post_id, '_tracking_code', $tracking_code);
}
add_action('woocommerce_process_shop_order_meta', 'wc_custom_order_price_save_tracking_code');