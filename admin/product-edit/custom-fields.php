<?php
/**
 * All Custom Admin Fields defined inside this file.
 *
 * @category ICRMW_Rental
 * @package  RentMeWoo
 * @author   RentMeWP
 * @license  https://rentmewp.com/ GPL-2.0+
 * @link     https://rentmewp.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit();
}


global $woocommerce, $post;

echo '<div class="options_group show_if_icrmw_rental">';

wp_nonce_field( 'icrmw_saveproductmeta', 'icrmw_saveproductmeta_nonce' );

woocommerce_wp_select(
    array(
        'id'          => 'icrmw_rent_charge_type',
        'class'       => 'select short icrmw_rent_type',
        'label'       => esc_html__( 'Rent Type', 'rentmewoo' ),
        'description' => esc_html__( 'Specify rental duration (e.g., daily, hourly).', 'rentmewoo' ),
        'desc_tip'    => 'true',
        'options'     => array(
            'day'  => esc_html__( 'Day', 'rentmewoo' ),
            'hour' => esc_html__( 'Hour', 'rentmewoo' ),
        ),
        'value'       => get_post_meta( $post->ID, 'icrmw_rent_charge_type', true ),
    )
);

woocommerce_wp_text_input(
    array(
        'id'          => '_icrmw_price',
        'class'       => 'short wc_input_price',
        'label'       => esc_html__( 'Regular Rental Price', 'rentmewoo' ),
        'placeholder' => '',
        'desc_tip'    => 'true',
        'description' => esc_html__( 'Enter the regular rental price for the product.', 'rentmewoo' ),
        'type'        => 'text',
        'value'       => get_post_meta( $post->ID, '_icrmw_price', true ),
    )
);


echo '<div class="icrmw_product_rent_type_fields_box">';

woocommerce_wp_text_input(
    array(
        'id'          => 'icrmw_check_in_time',
        'label'       => esc_html__( 'Check-in Time', 'rentmewoo' ),
        'description' => esc_html__( 'Specific the Checkin time.', 'rentmewoo' ),
        'desc_tip'    => 'true',
        'type'        => 'time',
        // 'value'       => icrmw_get_wp_current_time('H:i'),
    )
);

woocommerce_wp_select(
    array(
        'id'          => 'icrmw_check_out_type',
        'label'       => esc_html__( 'Checkout Type', 'rentmewoo' ),
        'description' => esc_html__( 'Select either "Same day" or "Next day" checkout.', 'rentmewoo' ),
        'desc_tip'    => 'true',
        'options'     => array(
            'same' => esc_html__( 'Same Day', 'rentmewoo' ),
            'next' => esc_html__( 'Next Day', 'rentmewoo' ),
        ),
        // 'value'      => get_post_meta( $post->ID, '_rental_enable_deposit', true ),
    )
);

woocommerce_wp_text_input(
    array(
        'id'          => 'icrmw_check_out_time',
        'label'       => esc_html__( 'Check-out Time', 'rentmewoo' ),
        'description' => esc_html__( 'Specific the Checkout time.', 'rentmewoo' ),
        'desc_tip'    => 'true',
        'type'        => 'time',
        // 'value'        => icrmw_get_wp_current_time('H:i'),
    )
);

echo '</div>';

woocommerce_wp_text_input(
    array(
        'id'          => 'icrmw_max_adults',
        'label'       => esc_html__( 'Max. Adult', 'rentmewoo' ),
        'desc_tip'    => 'true',
        'placeholder' => '10',
        'description' => esc_html__( 'Maximum adults allowed.', 'rentmewoo' ),
        'type'        => 'number',
    )
);

woocommerce_wp_text_input(
    array(
        'id'          => 'icrmw_max_childs',
        'label'       => esc_html__( 'Max. Children', 'rentmewoo' ),
        'desc_tip'    => 'true',
        'placeholder' => '5',
        'description' => esc_html__( 'Maximum children allowed.', 'rentmewoo' ),
        'type'        => 'number',
    )
);

echo '</div>';
