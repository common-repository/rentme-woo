<?php
/**
 * The template for displaying booking form content within single
 *
 * This template can be overridden by copying it to yourtheme/rentmewoo/templates/booking-request.php
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

global $product;

// Check product type: rental.
if ( ! $product || 'icrmw_rental' !== $product->get_type() ) {
    return;
}


// get basic option value.
$icrmw_enable_booking_tab = ! empty( get_option( 'icrmw_basic_enable_booking_tab' ) ) ? sanitize_text_field( get_option( 'icrmw_basic_enable_booking_tab' ) ) : '';
$icrmw_enable_request_tab = ! empty( get_option( 'icrmw_basic_enable_request_tab' ) ) ? sanitize_text_field( get_option( 'icrmw_basic_enable_request_tab' ) ) : '';


// get options values.
$icrmw_booking_label        = ! empty( get_option( 'icrmw_booking_tab_main_label' ) ) ? sanitize_text_field( get_option( 'icrmw_booking_tab_main_label' ) ) : 'Booking';
$icrmw_request_label        = ! empty( get_option( 'icrmw_request_tab_main_label' ) ) ? sanitize_text_field( get_option( 'icrmw_request_tab_main_label' ) ) : 'Request Booking';
$icrmw_adult_title          = ! empty( get_option( 'icrmw_tab_adult_title' ) ) ? sanitize_text_field( get_option( 'icrmw_tab_adult_title' ) ) : 'Adult';
$icrmw_children_title       = ! empty( get_option( 'icrmw_tab_children_title' ) ) ? sanitize_text_field( get_option( 'icrmw_tab_children_title' ) ) : 'Children';
$icrmw_total_payment_title  = ! empty( get_option( 'icrmw_tab_total_payment_title' ) ) ? sanitize_text_field( get_option( 'icrmw_tab_total_payment_title' ) ) : 'Total Payment';
$icrmw_booking_button_title = ! empty( get_option( 'icrmw_booking_tab_button_title' ) ) ? sanitize_text_field( get_option( 'icrmw_booking_tab_button_title' ) ) : 'Book Now';
$icrmw_request_button_title = ! empty( get_option( 'icrmw_request_tab_button_title' ) ) ? sanitize_text_field( get_option( 'icrmw_request_tab_button_title' ) ) : 'Request Now';
$icrmw_name_title           = ! empty( get_option( 'icrmw_request_tab_name_title' ) ) ? sanitize_text_field( get_option( 'icrmw_request_tab_name_title' ) ) : 'Name';
$icrmw_name_placeholder     = ! empty( get_option( 'icrmw_request_tab_name_placeholder' ) ) ? sanitize_text_field( get_option( 'icrmw_request_tab_name_placeholder' ) ) : 'John Deo';
$icrmw_email_title          = ! empty( get_option( 'icrmw_request_tab_email_title' ) ) ? sanitize_text_field( get_option( 'icrmw_request_tab_email_title' ) ) : 'Email';
$icrmw_email_placeholder    = ! empty( get_option( 'icrmw_request_tab_email_placeholder' ) ) ? sanitize_text_field( get_option( 'icrmw_request_tab_email_placeholder' ) ) : 'johndeo@gmail.com';
$icrmw_phone_title          = ! empty( get_option( 'icrmw_request_tab_phone_title' ) ) ? sanitize_text_field( get_option( 'icrmw_request_tab_phone_title' ) ) : 'Phone';
$icrmw_phone_placeholder    = ! empty( get_option( 'icrmw_request_tab_phone_placeholder' ) ) ? sanitize_text_field( get_option( 'icrmw_request_tab_phone_placeholder' ) ) : '555-555-1212';
$icrmw_booking_dates_label  = ! empty( get_option( 'icrmw_booking_tab_dates_label' ) ) ? sanitize_text_field( get_option( 'icrmw_booking_tab_dates_label' ) ) : 'Booking Dates';
$icrmw_request_dates_label  = ! empty( get_option( 'icrmw_request_tab_dates_label' ) ) ? sanitize_text_field( get_option( 'icrmw_request_tab_dates_label' ) ) : 'Preferred Dates';

$rental_price          = get_post_meta( get_the_id(), '_price', true );
$icrmw_price           = get_post_meta( get_the_id(), '_icrmw_price', true );
$icrmw_max_adults      = get_post_meta( get_the_id(), 'icrmw_max_adults', true );
$icrmw_max_childs      = get_post_meta( get_the_id(), 'icrmw_max_childs', true );
$icrmw_show_timepicker = get_post_meta( get_the_id(), 'icrmw_show_timepicker', true );
$icrmw_check_in_time   = get_post_meta( get_the_id(), 'icrmw_check_in_time', true );
$icrmw_check_out_time  = get_post_meta( get_the_id(), 'icrmw_check_out_time', true );

?>

<?php if ( 'yes' === $icrmw_enable_booking_tab || 'yes' === $icrmw_enable_request_tab ) { ?>
<div class="icrmw_booking_form_main" id="icrmw_booking_form_main"> 
    <div class="icrmw_tabs">
    <?php if ( 'yes' === $icrmw_enable_booking_tab ) { ?>
        <button class="icrmw-tab active" ><?php echo esc_html( $icrmw_booking_label ); ?></button>
    <?php } ?>
    <?php if ( 'yes' === $icrmw_enable_request_tab ) { ?>
        <button class="icrmw-tab" ><?php echo esc_html( $icrmw_request_label ); ?></button>
    <?php } ?>   
    </div>

    <div class="icrmw_form_details">
        <?php if ( 'yes' === $icrmw_enable_booking_tab ) { ?>
        <div id="icrmw_btab" class="icrmw-tab-content active">
            <div class="icrmw_loader"></div>
            <form class="icrmw_form <?php print ( '' !== $rental_price && '' !== $icrmw_price ) ? 'icrmw_booking_form' : ''; ?>" id="icrmw_booking_form" action="" method="post" enctype="multipart/form-data" >
                <div class="icrmw_bdate_picker icrmw_form_group"> 
                        <label for="check_in_date"><?php echo esc_html( $icrmw_booking_dates_label ); ?></label>
                    <input type="text" id="icrmw_bdatetimepicker" class="icrmw_date_time" name="icrmw_date_time" placeholder="Checkin - Checkout" data-date="<?php echo esc_attr( icrmw_get_current_date_custom_format() ); ?>" data-timepicker="<?php echo esc_attr( $icrmw_show_timepicker ); ?>" data-cintime="<?php echo esc_attr( icrmw_convert_time_format( $icrmw_check_in_time ) ); ?>" data-couttime="<?php echo esc_attr( icrmw_convert_time_format( $icrmw_check_out_time ) ); ?>" readonly autocomplete="off">       
                </div>
                <div class="icrmw_passengers_field icrmw_form_group">
                    <div class="icrmw-passengers-content">
                        <div class="icrmw-passenger-adult">
                            <div class="icrmw-passenger-label">
                                <label><?php echo esc_html( $icrmw_adult_title ); ?>  </label>
                            </div>
                            <div class="icrmw-passenger-field-group">
                                <span class="icrmw_minus_adult icrmw_disabled">-</span>
                                <input type="number" name="icrmw_adults" id="icrmw_adults" class="icrmw_exclude icrmw_adults" value="1" min="1" max="<?php echo esc_attr( $icrmw_max_adults ); ?>">
                                <span class="icrmw_plus_adult">+</span>
                            </div>
                        </div>
                        <div class="icrmw-passenger-child">
                            <div class="icrmw-passenger-label"> 
                                <label><?php echo esc_html( $icrmw_children_title ); ?></label>
                            </div>
                            <div class="icrmw-passenger-field-group">   
                            <span class="icrmw_minus_child icrmw_disabled">-</span>                                     
                                <input type="number" name="icrmw_childrens" id="icrmw_childrens" class="icrmw_exclude icrmw_childrens" value="0" min="0" max="<?php echo esc_attr( $icrmw_max_childs ); ?>">
                                <span class="icrmw_plus_child">+</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="icrmw_footer_group">
                    <div class="icrmw_total_amount">
                        <label class="icrmw_amount_label"><?php echo esc_html( $icrmw_total_payment_title ); ?> </label> 
                            <span class="icrmw_currency"><?php print ( '' !== $rental_price && '' !== $icrmw_price ) ? esc_html( get_woocommerce_currency_symbol() ) : ''; ?>
                            <span class="icrmw_total_price"><?php print ( '' !== $rental_price && '' !== $icrmw_price ) ? '0.00' : 'NaN'; ?>
                            </span>
                            </span>
                    </div>
                </div>
                <?php if ( '' !== $rental_price && '' !== $icrmw_price ) { ?>
                <div class="icrmw_btn_group"> 
                    <button type="submit" class="submit icrmw_booking_btn"><?php echo esc_html( $icrmw_booking_button_title ); ?></button>
                </div> 
                <?php } ?>
                <input type="hidden" name="icrmw_bproduct_id" class="icrmw_bproduct_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
                <input type="hidden" name="icrmw_bcheck_in" class="icrmw_bcheck_in" value="">
                <input type="hidden" name="icrmw_bcheck_out" class="icrmw_bcheck_out" value="">
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
                <input type="hidden" name="quantity" value="1">
                <?php wp_nonce_field( 'icrmw_bookingform', 'icrmw_bookingform_nonce' ); ?>
            </form>
        </div>
        <?php } ?>


        <?php if ( 'yes' === $icrmw_enable_request_tab ) { ?>
        <div id="icrmw_rtab" class="icrmw-tab-content">
            <div class="icrmw_rloader"></div>
            <form class="icrmw_form icrmw-request-tab" id="icrmw_request_form" action="" method="post" enctype="multipart/form-data">
                <div class="icrmw_name_field icrmw_form_group"> 
                    <label><?php echo esc_html( $icrmw_name_title ); ?> <span class="icrmw_required">*</span> </label>
                    <input type="text" id="icrmw_req_name" class="icrmw_req_name" name="icrmw_req_name" placeholder="<?php echo esc_attr( $icrmw_name_placeholder ); ?>">
                    <div class="icrmw_error_msg"> 
                        <label><?php esc_html_e( 'Name is required', 'rentmewoo' ); ?></label>
                    </div>
                </div>
                <div class="icrmw_email_field icrmw_form_group"> 
                    <label><?php echo esc_html( $icrmw_email_title ); ?> <span class="icrmw_required">*</span></label>
                    <input type="text" id="icrmw_req_email" class="icrmw_req_email" name="icrmw_req_email" placeholder="<?php echo esc_attr( $icrmw_email_placeholder ); ?> ">
                    <div class="icrmw_error_msg"> 
                        <label><?php esc_html_e( 'Email is required', 'rentmewoo' ); ?></label>
                    </div> 
                </div>
                <div class="icrmw_phone_field icrmw_form_group"> 
                    <label><?php echo esc_html( $icrmw_phone_title ); ?> <span class="icrmw_required">*</span></label>
                    <input type="number" id="icrmw_req_phone" class="icrmw_req_phone" name="icrmw_req_phone" placeholder="<?php echo esc_attr( $icrmw_phone_placeholder ); ?>">
                    <div class="icrmw_error_msg"> 
                        <label><?php esc_html_e( 'Phone number is required', 'rentmewoo' ); ?></label>
                    </div> 
                </div>
                <div class="icrmw_rdate_picker icrmw_form_group"> 
                    <label for="check_in_date"><?php echo esc_html( $icrmw_request_dates_label ); ?> <span class="icrmw_required">*</span> </label>
                    <input type="text" id="icrmw_rdatetimepicker" class="icrmw_date_time" name="icrmw_date_time" data-date="<?php echo esc_attr( icrmw_get_current_date_custom_format() ); ?>" placeholder="Checkin - Checkout" readonly autocomplete="off">
                    <div class="icrmw_error_msg"> 
                        <label><?php esc_html_e( 'Checkin and Checkout is required', 'rentmewoo' ); ?></label>
                    </div>
                </div>
                <div class="icrmw_success_msg"><?php esc_html_e( 'Your request has been successfully submitted. Thank you!', 'rentmewoo' ); ?>
                </div>
                <div class="icrmw_mail_error_msg"><?php esc_html_e( 'Your request has not been submitted. You can submit again!', 'rentmewoo' ); ?>
                </div>
                <input type="hidden" name="icrmw_product_name" value="<?php echo esc_attr( $product->get_name() ); ?>">
                <input type="hidden" class="icrmw_rproduct_id" name="icrmw_rproduct_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
                <input type="hidden" name="icrmw_rcheck_in" class="icrmw_rcheck_in" value="">
                <input type="hidden" name="icrmw_rcheck_out" class="icrmw_rcheck_out" value="">
                <input type="hidden" name="icrmw_request_tab" value="icrmw_request_tab">
                <?php wp_nonce_field( 'icrmw_requestform', 'icrmw_requestform_nonce' ); ?>
                <div class="icrmw_btn_group"> 
                    <button type="submit" class="submit icrmw_request_btn"><?php echo esc_html( $icrmw_request_button_title ); ?></button>
                </div>
            </form>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>