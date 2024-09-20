<?php
// Adicionar os campos personalizados ao processo de exportação de CSV
function wc_custom_order_price_export_custom_fields($columns) {
    $columns['enable_measurement_price'] = 'Ativar Preço por Base de Medida';
    $columns['measurement_unit'] = 'Unidade de Medida (100ml ou 1L)';
    return $columns;
}
add_filter('woocommerce_product_export_column_names', 'wc_custom_order_price_export_custom_fields');
add_filter('woocommerce_product_export_product_default_columns', 'wc_custom_order_price_export_custom_fields');

// Preencher os valores dos campos personalizados na exportação de CSV
function wc_custom_order_price_export_custom_field_data($value, $product, $column) {
    switch ($column) {
        case 'enable_measurement_price':
            $value = get_post_meta($product->get_id(), '_enable_measurement_price', true);
            break;
        case 'measurement_unit':
            $value = get_post_meta($product->get_id(), '_measurement_unit', true);
            break;
    }
    return $value;
}
add_filter('woocommerce_product_export_product_column_enable_measurement_price', 'wc_custom_order_price_export_custom_field_data', 10, 3);
add_filter('woocommerce_product_export_product_column_measurement_unit', 'wc_custom_order_price_export_custom_field_data', 10, 3);

// Registrar os campos personalizados para que apareçam no mapeamento de importação de CSV
function wc_custom_order_price_register_custom_import_columns($columns) {
    $columns['enable_measurement_price'] = 'Ativar Preço por Base de Medida';
    $columns['measurement_unit'] = 'Unidade de Medida (100ml ou 1L)';
    return $columns;
}
add_filter('woocommerce_csv_product_import_mapping_options', 'wc_custom_order_price_register_custom_import_columns');

// Garantir que os campos personalizados apareçam no dropdown de mapeamento durante a importação
function wc_custom_order_price_add_custom_import_columns_to_mapping($columns) {
    $columns['Ativar Preço por Base de Medida'] = 'enable_measurement_price';
    $columns['Unidade de Medida (100ml ou 1L)'] = 'measurement_unit';
    return $columns;
}
add_filter('woocommerce_csv_product_import_mapping_default_columns', 'wc_custom_order_price_add_custom_import_columns_to_mapping');

// Preencher os dados dos campos personalizados durante a importação
function wc_custom_order_price_import_custom_fields($object, $data) {
    // Verificar e processar o campo "Ativar Preço por Base de Medida"
    if (isset($data['enable_measurement_price']) && !empty($data['enable_measurement_price'])) {
        $enable_measurement_price = ($data['enable_measurement_price'] === 'yes') ? 'yes' : 'no';
        update_post_meta($object->get_id(), '_enable_measurement_price', $enable_measurement_price);
    } else {
        // Se não estiver definido, garantir que o valor padrão seja 'no'
        update_post_meta($object->get_id(), '_enable_measurement_price', 'no');
    }

    // Verificar e processar o campo "Unidade de Medida (100ml ou 1L)"
    if (isset($data['measurement_unit']) && !empty($data['measurement_unit'])) {
        update_post_meta($object->get_id(), '_measurement_unit', sanitize_text_field($data['measurement_unit']));
    } else {
        // Se não estiver definido, garantir que haja um valor padrão
        update_post_meta($object->get_id(), '_measurement_unit', '100ml'); // Exemplo: valor padrão
    }

    return $object;
}
add_filter('woocommerce_product_import_inserted_product_object', 'wc_custom_order_price_import_custom_fields', 10, 2);

// Adicionar BOM (Byte Order Mark) para garantir a codificação UTF-8 correta na exportação de CSV
function wc_custom_order_price_add_bom_to_csv($filename) {
    $bom = "\xEF\xBB\xBF"; // UTF-8 BOM
    // Modificar o conteúdo do arquivo antes de exportar
    file_put_contents($filename, $bom . file_get_contents($filename));
}
add_filter('woocommerce_product_export_file_path', 'wc_custom_order_price_add_bom_to_csv');

// Corrigir problemas de codificação no CSV (remover o BOM e garantir UTF-8)
function wc_custom_order_price_fix_csv_output() {
    ob_start(function($buffer) {
        // Remover possíveis espaços e garantir que a saída esteja em UTF-8 sem BOM
        $buffer = preg_replace('/^\xEF\xBB\xBF/', '', $buffer); // Remove BOM, se existir
        $buffer = mb_convert_encoding($buffer, 'UTF-8', 'UTF-8'); // Garante UTF-8 correto
        return $buffer;
    });
}
add_action('init', 'wc_custom_order_price_fix_csv_output');