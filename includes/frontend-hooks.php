<?php
/**
 * Front Side hooks used inside this file.
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
 * Validate function on add to cart hook
 *
 * @param bool $passed     Validation result. True if validation passes, false otherwise.
 * @param int  $product_id Product ID of the product.
 * @param int  $quantity   Quantity of the product.
 *
 * @return boolean
 */
function icrmw_plugin_add_to_cart_validation( $passed, $product_id, $quantity ) {
    $product = wc_get_product( $product_id );
    // Check product type: rental.
    if ( $product->get_type() === 'icrmw_rental' ) {

        // Verify nonce.
        if ( ! isset( $_POST['icrmw_bookingform_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['icrmw_bookingform_nonce'] ) ), 'icrmw_bookingform' ) ) {
            // Nonce verification failed, prevent adding to cart.
            return wc_add_notice( 'Nonce verification failed.', 'error' );
        }

        // custom validation logic here.
        $merge_check_in    = isset( $_POST['icrmw_bcheck_in'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_bcheck_in'] ) ) : '';
        $merge_check_out   = isset( $_POST['icrmw_bcheck_out'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_bcheck_out'] ) ) : '';
        $icrmw_charge_type = get_post_meta( $product_id, 'icrmw_rent_charge_type', true );
        $validate_dates    = icrmw_validate_checkin_checkout_dates( $merge_check_in, $merge_check_out, $icrmw_charge_type );

        if ( true !== $validate_dates ) {
            // Prevent adding to cart.
            $passed = false;
        }
    }
    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'icrmw_plugin_add_to_cart_validation', 10, 3 );


/**
 * Add custom cart item data
 *
 * @param array $cart_item_data Cart Item Data.
 * @param int   $product_id     Product ID.
 * @param array $variation_id   Variation ID.
 *
 * @return array
 */
function icrmw_plugin_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
    $product = wc_get_product( $product_id );
    // Check product type: rental.
    if ( ! $product || $product->get_type() !== 'icrmw_rental' ) {
        return;
    }

    // Verify nonce.
    if ( ! isset( $_POST['icrmw_bookingform_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['icrmw_bookingform_nonce'] ) ), 'icrmw_bookingform' ) ) {
        return;
    }

    // calcution part start.
    $merge_check_in  = isset( $_POST['icrmw_bcheck_in'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_bcheck_in'] ) ) : '';
    $merge_check_out = isset( $_POST['icrmw_bcheck_out'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_bcheck_out'] ) ) : '';
    $product_id      = isset( $product_id ) ? absint( sanitize_text_field( wp_unslash( $product_id ) ) ) : '';
    $total_price     = 0;

    if ( '' !== $merge_check_in && '' !== $merge_check_out ) {

        $icrmw_charge_type = get_post_meta( $product_id, 'icrmw_rent_charge_type', true );
        if ( 'hour' === $icrmw_charge_type ) {
            $icrmw_price = (int) get_post_meta( $product_id, '_icrmw_price', true );
            $hours       = icrmw_calculate_hours_between_dates( $merge_check_in, $merge_check_out );

            $total_price += $icrmw_price * $hours;
        } else {
            $icrmw_get_weekdays_between_dates = icrmw_get_weekdays_between_dates( $merge_check_in, $merge_check_out );
            $icrmw_price                      = (int) get_post_meta( $product_id, '_icrmw_price', true );

            $total_price += $icrmw_price * count( $icrmw_get_weekdays_between_dates );
        }
    }

    $payment_full = $total_price;
    // calcution part end.

    if ( isset( $_POST['icrmw_bcheck_in'] ) && ( isset( $_POST['icrmw_bookingform_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['icrmw_bookingform_nonce'] ) ), 'icrmw_bookingform' ) ) ) {

        $cart_item_data['icrmw_bcheck_in'] = sanitize_text_field( wp_unslash( $_POST['icrmw_bcheck_in'] ) );
        $cart_item_data['fullPaymentAmt']  = sanitize_text_field( wp_unslash( $payment_full ) );
        $cart_item_data['paidAmt']         = sanitize_text_field( wp_unslash( $total_price ) );
        $remaining_amt                     = (float) $cart_item_data['fullPaymentAmt'] - (float) $cart_item_data['paidAmt'];
        $cart_item_data['remainingAmt']    = sanitize_text_field( wp_unslash( $remaining_amt ) );

    }

    if ( isset( $_POST['icrmw_bcheck_out'] ) && ( isset( $_POST['icrmw_bookingform_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['icrmw_bookingform_nonce'] ) ), 'icrmw_bookingform' ) ) ) {
        $cart_item_data['icrmw_bcheck_out'] = sanitize_text_field( wp_unslash( $_POST['icrmw_bcheck_out'] ) );
    }

    if ( isset( $_POST['icrmw_adults'] ) && ( isset( $_POST['icrmw_bookingform_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['icrmw_bookingform_nonce'] ) ), 'icrmw_bookingform' ) ) ) {
        $cart_item_data['icrmw_adults'] = sanitize_text_field( wp_unslash( $_POST['icrmw_adults'] ) );
    }

    if ( isset( $_POST['icrmw_childrens'] ) && ( isset( $_POST['icrmw_bookingform_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['icrmw_bookingform_nonce'] ) ), 'icrmw_bookingform' ) ) ) {
        $cart_item_data['icrmw_childrens'] = sanitize_text_field( wp_unslash( $_POST['icrmw_childrens'] ) );
    }

    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'icrmw_plugin_add_cart_item_data', 10, 3 );


/**
 * Display custom item data in the cart
 *
 * @param  array $item_data      Item Data.
 * @param  array $cart_item_data Cart Item Data.
 *
 * @return array
 */
function icrmw_plugin_custom_get_item_data( $item_data, $cart_item_data ) {
    if ( isset( $cart_item_data['icrmw_bcheck_in'] ) ) {
        $item_data[] = array(
            'key'   => __( 'Check-in', 'rentmewoo' ),
            'value' => wc_clean( $cart_item_data['icrmw_bcheck_in'] ),
        );
    }

    if ( isset( $cart_item_data['icrmw_bcheck_out'] ) ) {
        $item_data[] = array(
            'key'   => __( 'Checkout', 'rentmewoo' ),
            'value' => wc_clean( $cart_item_data['icrmw_bcheck_out'] ),
        );
    }

    if ( isset( $cart_item_data['icrmw_adults'] ) ) {
        $item_data[] = array(
            'key'   => __( 'Adult', 'rentmewoo' ),
            'value' => wc_clean( $cart_item_data['icrmw_adults'] ),
        );
    }

    if ( isset( $cart_item_data['icrmw_childrens'] ) && ! empty( $cart_item_data['icrmw_childrens'] ) ) {
        $item_data[] = array(
            'key'   => __( 'Children', 'rentmewoo' ),
            'value' => wc_clean( $cart_item_data['icrmw_childrens'] ),
        );
    }
    return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'icrmw_plugin_custom_get_item_data', 10, 2 );


/**
 * Add custom meta to order
 *
 * @param WC_Order_Item_Product $item       The order item being created.
 * @param string                $cart_item_key  Cart item key.
 * @param array                 $values      Cart item data.
 * @param WC_Order              $order         The order object.
 *
 * @return void
 */
function icrmw_plugin_custom_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['icrmw_bcheck_in'] ) ) {
        $item->add_meta_data(
            __( 'Check-in', 'rentmewoo' ),
            $values['icrmw_bcheck_in'],
            true
        );
    }

    if ( isset( $values['icrmw_bcheck_out'] ) ) {
        $item->add_meta_data(
            __( 'Checkout', 'rentmewoo' ),
            $values['icrmw_bcheck_out'],
            true
        );
    }

    if ( isset( $values['icrmw_adults'] ) ) {
        $item->add_meta_data(
            __( 'Adult', 'rentmewoo' ),
            $values['icrmw_adults'],
            true
        );
    }

    if ( isset( $values['icrmw_childrens'] ) && ! empty( $values['icrmw_childrens'] ) ) {
        $item->add_meta_data(
            __( 'Children', 'rentmewoo' ),
            $values['icrmw_childrens'],
            true
        );
    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'icrmw_plugin_custom_checkout_create_order_line_item', 10, 4 );


/**
 * Function for `woocommerce_order_item_display_meta_value` filter-hook.
 *
 * @param  string        $meta_value display value of the order item meta data.
 * @param  WC_Meta_Data  $meta meta data object.
 * @param  WC_Order_Item $item order item object.
 *
 * @return string
 */
function icrmw_woo_order_item_display_meta_value_filter( $meta_value, $meta, $item ) {
    if ( 'Total Amount' === $meta->key ) {
        $meta_value = get_woocommerce_currency_symbol() . $meta_value;
    }
    if ( 'Deposit Amount' === $meta->key ) {
        $meta_value = get_woocommerce_currency_symbol() . $meta_value;
    }
    if ( 'Remaining Amount' === $meta->key ) {
        $meta_value = get_woocommerce_currency_symbol() . $meta_value;
    }
    if ( 'Partial Amount Paid' === $meta->key ) {
        $meta_value = get_woocommerce_currency_symbol() . $meta_value;
    }
    return $meta_value;
}
add_filter( 'woocommerce_order_item_display_meta_value', 'icrmw_woo_order_item_display_meta_value_filter', 10, 3 );


/**
 * WP Ajax product price calculation
 */
function icrmw_product_total_price_calculation() {

    check_ajax_referer( 'icrmw_bookingform', 'icrmw_bookingform_nonce' );

    $merge_check_in  = isset( $_POST['merge_check_in'] ) ? sanitize_text_field( wp_unslash( $_POST['merge_check_in'] ) ) : '';
    $merge_check_out = isset( $_POST['merge_check_out'] ) ? sanitize_text_field( wp_unslash( $_POST['merge_check_out'] ) ) : '';
    $product_id      = isset( $_POST['product_id'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) ) : '';
    $total_price     = 0;

    if ( '' !== $merge_check_in && '' !== $merge_check_out ) {
        $icrmw_charge_type = get_post_meta( $product_id, 'icrmw_rent_charge_type', true );
        if ( 'hour' === $icrmw_charge_type ) {
            $icrmw_price = (int) get_post_meta( $product_id, '_icrmw_price', true );

            $hours = icrmw_calculate_hours_between_dates( $merge_check_in, $merge_check_out );

            $total_price += $icrmw_price * $hours;
        } else {
            $icrmw_get_weekdays_between_dates = icrmw_get_weekdays_between_dates( $merge_check_in, $merge_check_out );

            $icrmw_price = (int) get_post_meta( $product_id, '_icrmw_price', true );

            $total_price += $icrmw_price * count( $icrmw_get_weekdays_between_dates );
        }
    }

    // Full Amount.
    $payment_full = $total_price;

    $msg = [
        'status' => 'success',
        'price'  => sprintf( '%.2f', $total_price ),
        'msg'    => 'price calculated successfully',
    ];

    wp_send_json( $msg );
}
add_action( 'wp_ajax_icrmw_product_price_calculation', 'icrmw_product_total_price_calculation' );
add_action( 'wp_ajax_nopriv_icrmw_product_price_calculation', 'icrmw_product_total_price_calculation' );


/**
 * Cart price update for icrmw_Rental
 *
 * @param  WC_Cart $cart_object Cart Object.
 */
function icrmw_plugin_cart_recalculate_price( $cart_object ) {
    foreach ( $cart_object->get_cart() as $hash => $value ) {

        // Check product type: rental.
        if ( ! $value['data']->is_type( 'icrmw_rental' ) ) {
            continue;
        }

        $new_price = $value['paidAmt'];
        $value['data']->set_price( $new_price );
    }
}
add_action( 'woocommerce_before_calculate_totals', 'icrmw_plugin_cart_recalculate_price' );


/**
 * Booking-Request Tabs on Single Product Page
 */
function icrmw_plugin_single_product_booking_request_forms() {
    $custom_template = locate_template( 'rentmewoo/templates/booking-request.php' );

    if ( $custom_template ) {
        include $custom_template;
    } else {
        include ICRMW_PLUGIN_PATH . '/templates/booking-request.php';
    }
}
add_action( 'woocommerce_product_meta_end', 'icrmw_plugin_single_product_booking_request_forms', 10 );


/**
 * WP Ajax request tab form button click
 */
function icrmw_request_form_submit() {

    check_ajax_referer( 'icrmw_requestform', 'icrmw_requestform_nonce' );

    $sitename = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    $siteurl  = wp_specialchars_decode( get_option( 'home' ), ENT_QUOTES );

    $icrmw_request_email_subject = ! empty( get_option( 'icrmw_request_form_email_subject' ) ) ? sanitize_text_field( get_option( 'icrmw_request_form_email_subject' ) ) : 'Request For Booking';
    $icrmw_request_email_body    = ! empty( get_option( 'icrmw_request_form_email_body' ) ) ? wp_kses_post( wp_unslash( get_option( 'icrmw_request_form_email_body' ) ) ) : '';

    $icrmw_req_name   = isset( $_POST['icrmw_req_name'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_req_name'] ) ) : '';
    $icrmw_req_email  = isset( $_POST['icrmw_req_email'] ) ? sanitize_email( wp_unslash( $_POST['icrmw_req_email'] ) ) : '';
    $icrmw_req_phone  = isset( $_POST['icrmw_req_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_req_phone'] ) ) : '';
    $icrmw_rcheck_in  = isset( $_POST['icrmw_rcheck_in'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_rcheck_in'] ) ) : '';
    $icrmw_rcheck_out = isset( $_POST['icrmw_rcheck_out'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_rcheck_out'] ) ) : '';
    $product_id       = isset( $_POST['product_id'] ) ? abs( sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) ) : '';
    $product          = wc_get_product( $product_id );
    $product_name     = $product->get_name();

    $product_url = get_permalink( $product->get_id() );

    $icrmw_request_email_recipient = ! empty( get_option( 'icrmw_request_form_email_recipient' ) ) ? sanitize_text_field( get_option( 'icrmw_request_form_email_recipient' ) ) : '';
    $icrmw_request_email_recipient = str_replace( '{user_email}', $icrmw_req_email, $icrmw_request_email_recipient );
    $icrmw_request_email_recipient = explode( ',', $icrmw_request_email_recipient );

    // $icrmw_request_email_recipient1 = [get_option('admin_email'), $icrmw_req_email];
    // $icrmw_request_email_recipient = array_merge($icrmw_request_email_recipient,$icrmw_request_email_recipient1);
    // var_dump($icrmw_request_email_recipient);

    $to      = $icrmw_request_email_recipient;
    $subject = $icrmw_request_email_subject;
    $body    = $icrmw_request_email_body;

    $product_name_with_url = '<a href="' . $product_url . '" target="_blank">' . $product_name . '</a>';

    $body = str_replace( '{full_name}', $icrmw_req_name, $body );
    $body = str_replace( '{user_email}', $icrmw_req_email, $body );
    $body = str_replace( '{user_phone}', $icrmw_req_phone, $body );
    $body = str_replace( '{icrmw_check_in}', $icrmw_rcheck_in, $body );
    $body = str_replace( '{icrmw_check_out}', $icrmw_rcheck_out, $body );
    $body = str_replace( '{icrmw_product_name}', $product_name_with_url, $body );
    $body = str_replace( '{sitename}', $sitename, $body );
    $body = str_replace( '{site_url}', $siteurl, $body );

    // do_action( 'my_custom_email_body_change_content', $product );
    // $body = apply_filters( 'my_custom_email_body_change_content', $body );

    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

    $mail_sts = wp_mail( $to, $subject, $body, $headers );
    $mail_msg = ( true === $mail_sts ) ? 'request form clicked & mail sent' : 'request form clicked & mail not sent';

    $msg = [
        'status'  => 'success',
        'mailSts' => $mail_sts,
        'msg'     => $mail_msg,
    ];

    wp_send_json( $msg );
}
add_action( 'wp_ajax_icrmw_request_form_submit', 'icrmw_request_form_submit' );
add_action( 'wp_ajax_nopriv_icrmw_request_form_submit', 'icrmw_request_form_submit' );


/**
 * Rental product price html change - shown with Rent Type
 *
 * @param  string $price_html Price Html.
 * @param  mixed  $product    Product.
 *
 * @return string
 */
function icrmw_plugin_custom_rental_price_html( $price_html, $product ) {

    $product_id        = $product->get_id();
    $icrmw_price       = $product->get_price();
    $product_rent_type = get_post_meta( $product_id, 'icrmw_rent_charge_type', true );

    // Check if the product is of the "Rental" type.
    if ( 'icrmw_rental' === $product->get_type() && ! empty( $product_rent_type ) && ! empty( $icrmw_price ) ) {
        $product_rent_type = ( 'day' === $product_rent_type ) ? 'Day' : 'Hour';
        // Append a string after the price.
        $price_html .= ' / ' . $product_rent_type;
    }
    return $price_html;
}
add_filter( 'woocommerce_get_price_html', 'icrmw_plugin_custom_rental_price_html', 10, 2 );
