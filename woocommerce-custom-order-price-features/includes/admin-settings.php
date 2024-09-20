<?php
// Adicionar o menu do plugin no admin
function wc_custom_order_price_add_admin_menu() {
    add_menu_page(
        'Configurações WooCommerce Custom Order', // Título da página
        'WooCommerce Custom Order',               // Nome do menu
        'manage_options',                         // Permissão necessária
        'wc-custom-order-price-settings',         // Slug do menu
        'wc_custom_order_price_settings_page',    // Função que vai renderizar o conteúdo da página
        'dashicons-admin-generic',                // Ícone do menu
        56                                        // Posição do menu
    );
}
add_action('admin_menu', 'wc_custom_order_price_add_admin_menu');

// Função que gera a página de configurações
function wc_custom_order_price_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php
                // Segurança e campos ocultos para salvar as configurações
                settings_fields('wc_custom_order_price_settings_group');
                do_settings_sections('wc-custom-order-price-settings');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registrar configurações, seções e campos
function wc_custom_order_price_register_settings() {
    // Registrar as opções que serão salvas
    register_setting('wc_custom_order_price_settings_group', 'wc_custom_order_price_enable_measurement_price');
    register_setting('wc_custom_order_price_settings_group', 'wc_custom_order_price_enable_stock_notifications');
    
    // Adicionar seção de configurações gerais
    add_settings_section(
        'wc_custom_order_price_general_section',       // ID da seção
        'Configurações Gerais',                        // Título da seção
        'wc_custom_order_price_general_section_cb',    // Callback para o conteúdo da seção
        'wc-custom-order-price-settings'               // Slug da página onde a seção será exibida
    );

    // Campo para ativar/desativar o preço por base de medida
    add_settings_field(
        'wc_custom_order_price_enable_measurement_price',       // ID do campo
        'Ativar Preço por Base de Medida',                      // Rótulo do campo
        'wc_custom_order_price_enable_measurement_price_cb',    // Callback que renderiza o campo
        'wc-custom-order-price-settings',                       // Página onde o campo será exibido
        'wc_custom_order_price_general_section'                 // Seção à qual o campo pertence
    );

    // Campo para ativar/desativar notificações de produto em estoque
    add_settings_field(
        'wc_custom_order_price_enable_stock_notifications',       // ID do campo
        'Ativar Notificações de Estoque',                         // Rótulo do campo
        'wc_custom_order_price_enable_stock_notifications_cb',    // Callback que renderiza o campo
        'wc-custom-order-price-settings',                         // Página onde o campo será exibido
        'wc_custom_order_price_general_section'                   // Seção à qual o campo pertence
    );
}
add_action('admin_init', 'wc_custom_order_price_register_settings');

// Callback para a descrição da seção
function wc_custom_order_price_general_section_cb() {
    echo '<p>Ajustes gerais do plugin WooCommerce Custom Order and Price Features.</p>';
}

// Callback para o campo de ativar preço por base de medida
function wc_custom_order_price_enable_measurement_price_cb() {
    $option = get_option('wc_custom_order_price_enable_measurement_price');
    echo '<input type="checkbox" name="wc_custom_order_price_enable_measurement_price" value="1"' . checked(1, $option, false) . '/> Ativar';
}

// Callback para o campo de ativar notificações de estoque
function wc_custom_order_price_enable_stock_notifications_cb() {
    $option = get_option('wc_custom_order_price_enable_stock_notifications');
    echo '<input type="checkbox" name="wc_custom_order_price_enable_stock_notifications" value="1"' . checked(1, $option, false) . '/> Ativar';
}