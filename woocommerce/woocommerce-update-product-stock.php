<?php
add_action('init', function () {
    global $product;

    $args = [
        'status' => 'publish',
        'type' => 'variable',
        'limit' => 5,
        'stock_status' => 'outofstock',
    ];

    $products = wc_get_products($args);

    foreach ($products as $product) {
        # code...
        // $product = wc_get_product(11850);
        $product->set_manage_stock(false);
        $product->set_stock_status('instock');
        $product->save();

        $variations = $product->get_available_variations();
        $variations_id = wp_list_pluck($variations, 'variation_id');

        foreach ($variations_id as $variation_id) {
            $variation = wc_get_product($variation_id);
            $variation->set_manage_stock(false);
            $variation->set_stock_status('instock');
            $variation->save();
        }
    }
});