<?php
add_action('init', function () {
    global $product;

    if(isset($_GET['update_prices'])){
        $args = [
            'status' => 'draft',
            'type' => array('variable', 'simple'),
            'limit' => -1,
        ];

        $products = wc_get_products($args);


        foreach ($products as $product) {
            if(get_field('prices_updated', $product->get_id()) == 'yes'){
                echo "<strong>Already handled</strong><br>";
                continue;
            }

            echo $product->get_id()."<br>";

            if($product->get_sale_price()){
                $product->set_sale_price($product->get_sale_price() * 1.25);
            }

            if($product->get_price()){
                $product->set_price($product->get_price() * 1.25);
            }

            if($product->get_regular_price()){
                $product->set_regular_price($product->get_regular_price() * 1.25);
            }

            $product->save();

            if($product->is_type( 'variable' )){

                $variations = $product->get_available_variations();
                $variations_id = wp_list_pluck($variations, 'variation_id');

                if($variations_id){
                    foreach ($variations_id as $variation_id) {
                        $variation = wc_get_product($variation_id);

                        if($variation->get_sale_price()){
                            $variation->set_sale_price($variation->get_sale_price() * 1.25);
                        }

                        if($variation->get_regular_price()){
                            $variation->set_regular_price($variation->get_regular_price() * 1.25);
                        }

                        if($variation->get_price()){
                            $variation->set_price($variation->get_price() * 1.25);
                        }

                        $variation->save();
                    }
                }
            }

            update_field('prices_updated', 'yes', $product->get_id());
        }
    }
});