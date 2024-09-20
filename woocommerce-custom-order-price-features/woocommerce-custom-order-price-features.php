<?php
/**
 * Plugin Name: WooCommerce Custom Order and Price Features
 * Description: Adiciona funcionalidades como preço comparativo por base de medida, status de encomenda personalizados, rastreamento e notificações de preços e estoque no WooCommerce.
 * Version: 1.0.5
 * Author: Ricardo
 * Text Domain: wc-custom-order-price
 */

// Verificar se WooCommerce está ativo antes de carregar o plugin
function wc_custom_order_price_woocommerce_active_check() {
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
        return true;
    } else {
        return false;
    }
}

// Mensagem de erro se WooCommerce não estiver ativo
function wc_custom_order_price_woocommerce_inactive_notice() {
    echo '<div class="error"><p><strong>WooCommerce Custom Order and Price Features</strong> requer o WooCommerce ativo. Por favor, ative WooCommerce primeiro.</p></div>';
}

// Executar o plugin apenas se WooCommerce estiver ativo
if (wc_custom_order_price_woocommerce_active_check()) {
    // Carregar todas as funcionalidades do plugin
    add_action('plugins_loaded', 'wc_custom_order_price_init');
    function wc_custom_order_price_init() {
        // Carregar os arquivos de funções do plugin
        require_once plugin_dir_path(__FILE__) . 'includes/pricing-comparative.php';
        require_once plugin_dir_path(__FILE__) . 'includes/csv-handling.php';
    }
} else {
    add_action('admin_notices', 'wc_custom_order_price_woocommerce_inactive_notice');
}
?>