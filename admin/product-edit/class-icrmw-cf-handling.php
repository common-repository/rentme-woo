<?php
/**
 * All Custom Fields Handling done inside this file.
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

/**
 * ICRMW_CF_Handling Class handler for custom fields in product type rental
 */
class ICRMW_CF_Handling {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'woocommerce_product_options_general_product_data', array( $this, 'icrmw_add_rental_product_custom_fields' ) );
        add_action( 'woocommerce_process_product_meta_icrmw_rental', array( $this, 'icrmw_save_rental_product_custom_fields' ) );
    }

    /**
     * Show Rental custom fields in admin
     *
     * @return void
     */
    public function icrmw_add_rental_product_custom_fields() {
        include ICRMW_PLUGIN_PATH . '/admin/product-edit/custom-fields.php';
    }


    /**
     * Save Rental custom fields to meta
     *
     * @param  int $product_id contains the id of the product.
     *
     * @return void
     */
    public function icrmw_save_rental_product_custom_fields( $product_id ) {
        // Verify nonce.
        check_admin_referer( 'icrmw_saveproductmeta', 'icrmw_saveproductmeta_nonce' );
        // if ( empty( $_POST['icrmw_saveproductmeta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['icrmw_saveproductmeta_nonce'] ) ), 'icrmw_saveproductmeta' ) ) {
        // return;
        // }

        // save rental product type inside meta key.
        $product_type = isset( $_POST['product-type'] ) ? sanitize_title( wp_unslash( $_POST['product-type'] ) ) : '';
        update_post_meta( $product_id, '_product_type', $product_type );

        update_post_meta( $product_id, '_sold_individually', 'yes' );

        $icrmw_price = ! empty( $_POST['_icrmw_price'] ) ? abs( sanitize_text_field( wp_unslash( $_POST['_icrmw_price'] ) ) ) : '';
        update_post_meta( $product_id, '_icrmw_price', $icrmw_price );
        update_post_meta( $product_id, '_price', $icrmw_price );
        update_post_meta( $product_id, '_regular_price', $icrmw_price );

        $icrmw_charge_type = isset( $_POST['icrmw_rent_charge_type'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_rent_charge_type'] ) ) : '';
        update_post_meta( $product_id, 'icrmw_rent_charge_type', $icrmw_charge_type );

        $icrmw_show_timepicker = ( 'day' === $icrmw_charge_type ) ? 'false' : 'true';

        update_post_meta( $product_id, 'icrmw_show_timepicker', $icrmw_show_timepicker );

        $icrmw_check_in_time = isset( $_POST['icrmw_check_in_time'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_check_in_time'] ) ) : '';
        update_post_meta( $product_id, 'icrmw_check_in_time', $icrmw_check_in_time );

        $icrmw_check_out_type = isset( $_POST['icrmw_check_out_type'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_check_out_type'] ) ) : '';
        update_post_meta( $product_id, 'icrmw_check_out_type', $icrmw_check_out_type );

        $icrmw_check_out_time = isset( $_POST['icrmw_check_out_time'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_check_out_time'] ) ) : '';
        update_post_meta( $product_id, 'icrmw_check_out_time', $icrmw_check_out_time );

        $icrmw_max_adults = ! empty( $_POST['icrmw_max_adults'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['icrmw_max_adults'] ) ) ) : '';
        update_post_meta( $product_id, 'icrmw_max_adults', $icrmw_max_adults );

        $icrmw_max_childs = ! empty( $_POST['icrmw_max_childs'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['icrmw_max_childs'] ) ) ) : '';
        update_post_meta( $product_id, 'icrmw_max_childs', $icrmw_max_childs );
    }
}
new ICRMW_CF_Handling();
