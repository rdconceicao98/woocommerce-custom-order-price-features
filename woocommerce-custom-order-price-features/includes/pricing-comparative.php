<?php
// Adicionar campo personalizado para escolher entre preço por 100ml ou 1L
function wc_custom_order_price_add_measurement_field() {
    global $post;
    
    echo '<div class="options_group">';

    // Checkbox para ativar o cálculo por base de medida
    woocommerce_wp_checkbox( array(
        'id'            => '_enable_measurement_price',
        'label'         => __('Ativar Preço por Base de Medida?', 'wc-custom-order-price'),
        'description'   => __('Marque para ativar o cálculo de preço por base de medida.', 'wc-custom-order-price'),
    ));

    // Campo para escolher entre 100ml ou 1L
    woocommerce_wp_select( array(
        'id'          => '_measurement_unit',
        'label'       => __('Unidade de Medida', 'wc-custom-order-price'),
        'description' => __('Escolha se o preço será calculado por 100ml ou por 1L.', 'wc-custom-order-price'),
        'options'     => array(
            '100ml' => __('100ml', 'wc-custom-order-price'),
            '1l'    => __('1L', 'wc-custom-order-price')
        )
    ));

    echo '</div>';
}
add_action('woocommerce_product_options_pricing', 'wc_custom_order_price_add_measurement_field');

// Salvar as opções personalizadas ao salvar o produto
function wc_custom_order_price_save_measurement_field($post_id) {
    $enable_measurement_price = isset($_POST['_enable_measurement_price']) ? 'yes' : 'no';
    update_post_meta($post_id, '_enable_measurement_price', $enable_measurement_price);

    if (isset($_POST['_measurement_unit'])) {
        update_post_meta($post_id, '_measurement_unit', sanitize_text_field($_POST['_measurement_unit']));
    }
}
add_action('woocommerce_process_product_meta', 'wc_custom_order_price_save_measurement_field');

// Exibir o preço comparativo por 100ml ou 1L na página do produto com JavaScript
function wc_custom_order_price_display_measurement_price_with_js() {
    global $product;

    // Verifica se estamos na página de um único produto
    if (is_product()) {
        // Verificar se a funcionalidade está ativada para o produto atual
        if (get_post_meta($product->get_id(), '_enable_measurement_price', true) === 'yes') {
            // Pegar o atributo de quantidade (usando o slug correto do atributo "ml")
            $quantity_ml = $product->get_attribute('ml'); // Usando o slug 'ml'

            // Remover "ml" e garantir que temos apenas o valor numérico
            $quantity_ml_numeric = (int) filter_var($quantity_ml, FILTER_SANITIZE_NUMBER_INT);

            // Pegar a unidade selecionada (100ml ou 1L)
            $measurement_unit = get_post_meta($product->get_id(), '_measurement_unit', true);
            
            if ($quantity_ml_numeric && is_numeric($quantity_ml_numeric)) {
                $price = $product->get_price(); // Preço do produto

                if ($measurement_unit === '1l') {
                    // Calcular o preço comparativo por 1L
                    $price_per_1l = ($price / $quantity_ml_numeric) * 1000;
                    $comparative_price_html = '<p class="price-comparative" style="font-size: 0.9em; margin-top: 10px;">Preço/1L: ' . wc_price($price_per_1l) . '</p>';
                } else {
                    // Calcular o preço comparativo por 100ml
                    $price_per_100ml = ($price / $quantity_ml_numeric) * 100;
                    $comparative_price_html = '<p class="price-comparative" style="font-size: 0.9em; margin-top: 10px;">Preço/100ml: ' . wc_price($price_per_100ml) . '</p>';
                }

                // Injetar o texto usando JavaScript logo abaixo do preço principal e acima do histórico de preços
                ?>
                <script type="text/javascript">
                    document.addEventListener("DOMContentLoaded", function() {
                        // Localizar o contêiner principal de preço
                        var productPriceContainer = document.querySelector('.price');
                        if (productPriceContainer) {
                            // Injetar o HTML do preço comparativo logo após o preço principal
                            var comparativePriceElement = document.createElement('div');
                            comparativePriceElement.innerHTML = '<?php echo addslashes($comparative_price_html); ?>';

                            // Inserir antes do histórico de preços, se ele existir
                            var priceHistory = document.querySelector('.wc-price-history');
                            if (priceHistory) {
                                priceHistory.parentNode.insertBefore(comparativePriceElement, priceHistory);
                            } else {
                                productPriceContainer.appendChild(comparativePriceElement);
                            }
                        }
                    });
                </script>
                <?php
            }
        }
    }
}
add_action('wp_footer', 'wc_custom_order_price_display_measurement_price_with_js');

// Adicionar CSS personalizado para ajustar o posicionamento
function wc_custom_order_price_add_custom_css() {
    ?>
    <style>
        /* Estilo para garantir que o preço comparativo seja posicionado corretamente */
        .price-comparative {
            font-size: 0.9em;
            margin-top: 10px;
            color: #333;
        }
    </style>
    <?php
}
add_action('wp_head', 'wc_custom_order_price_add_custom_css');
?>
    <style>
        /* Estilo para garantir que o preço comparativo seja posicionado corretamente */
        .price-comparative {
            font-size: 0.9em;
            margin-top: 10px;
            color: #333;
        }
    </style>
    <?php
}
add_action('wp_head', 'wc_custom_order_price_add_custom_css');
?>