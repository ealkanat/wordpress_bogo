<?php


add_action( 'woocommerce_before_calculate_totals', 'bogo_calculate' );
/**
 * @snippet       BOGO for WooCommerce by Erkan Alkanat
 * @how-to        twitter.com/erkanalkanat
 * @author        Erkan Alkanat - twitter -> @erkanalkanat
 * @compatible    Woo 4.5
 */
function bogo_calculate(){
    
    $cart = WC()->cart;
    if ( $cart->is_empty() ) return;

    $fee_desc = '2 al 1 öde kampanyası';
    $x_quantity = 2; //when bogo will start
    $total_item = 0;
    $prices = Array();
    $excluding_cats = Array('WALLABY');

    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {

        $product_id = $cart_item['product_id'];
        $terms = get_the_terms( $product_id, 'product_cat' );
        $product_should_calculate = true;
        
        foreach ($terms as $term) {
            foreach ( $excluding_cats as $cat_name ) {
                if($term->name == $cat_name) $product_should_calculate = false;
            }
        }

        if($product_should_calculate){
            $total_item += $cart_item['quantity'];
            $product = $cart_item['data'];
            $prices[] = $product->get_price_excluding_tax();
        }
        
     }
     
     if($total_item >= $x_quantity){
        // $fees = $cart->get_fees();
        // foreach ($fees as $key) {
        //     if($fees[$key]->name === $fee_desc) {
        //         unset($fees[$key]);
        //     }
        // }
        $lowestprice = min($prices);
        $lowestprice *= -1;
        $cart->add_fee( $fee_desc, $lowestprice, true);
    }
}