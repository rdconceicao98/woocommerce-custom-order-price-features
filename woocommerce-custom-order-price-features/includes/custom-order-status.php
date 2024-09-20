<?php
// Adicionar status personalizados
function wc_custom_register_order_statuses() {
    register_post_status('wc-encomenda-recebida', array(
        'label'                     => _x('Encomenda Recebida', 'Order status', 'wc-custom-order-status'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Encomenda Recebida <span class="count">(%s)</span>', 'Encomenda Recebida <span class="count">(%s)</span>', 'wc-custom-order-status')
    ));

    register_post_status('wc-em-preparacao', array(
        'label'                     => _x('Em Preparação', 'Order status', 'wc-custom-order-status'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Em Preparação <span class="count">(%s)</span>', 'Em Preparação <span class="count">(%s)</span>', 'wc-custom-order-status')
    ));

    register_post_status('wc-pronta-expedicao', array(
        'label'                     => _x('Encomenda Pronta Para Expedição', 'Order status', 'wc-custom-order-status'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Pronta Para Expedição <span class="count">(%s)</span>', 'Pronta Para Expedição <span class="count">(%s)</span>', 'wc-custom-order-status')
    ));

    register_post_status('wc-expedida', array(
        'label'                     => _x('Encomenda Expedida', 'Order status', 'wc-custom-order-status'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Expedida <span class="count">(%s)</span>', 'Expedida <span class="count">(%s)</span>', 'wc-custom-order-status')
    ));

    register_post_status('wc-entregue', array(
        'label'                     => _x('Encomenda Entregue', 'Order status', 'wc-custom-order-status'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Entregue <span class="count">(%s)</span>', 'Entregue <span class="count">(%s)</span>', 'wc-custom-order-status')
    ));
}
add_action('init', 'wc_custom_register_order_statuses');

// Adicionar os novos status personalizados ao WooCommerce
function wc_custom_add_order_statuses($order_statuses) {
    $new_order_statuses = array();

    // Inserir os novos status após o status "on-hold"
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;

        if ('wc-on-hold' === $key) {
            $new_order_statuses['wc-encomenda-recebida'] = _x('Encomenda Recebida', 'Order status', 'wc-custom-order-status');
            $new_order_statuses['wc-em-preparacao'] = _x('Em Preparação', 'Order status', 'wc-custom-order-status');
            $new_order_statuses['wc-pronta-expedicao'] = _x('Pronta Para Expedição', 'Order status', 'wc-custom-order-status');
            $new_order_statuses['wc-expedida'] = _x('Expedida', 'Order status', 'wc-custom-order-status');
            $new_order_statuses['wc-entregue'] = _x('Entregue', 'Order status', 'wc-custom-order-status');
        }
    }

    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'wc_custom_add_order_statuses');

// Remover status que não utilizamos
function wc_custom_remove_unnecessary_order_statuses($order_statuses) {
    unset($order_statuses['wc-completed']); // Concluído
    unset($order_statuses['wc-partially-refunded']);
    unset($order_statuses['wc-partially-captured']);

    return $order_statuses;
}
add_filter('wc_order_statuses', 'wc_custom_remove_unnecessary_order_statuses');
?>