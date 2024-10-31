<?php
/**
 * Admin Labels & Placeholders Settings Tab defined inside this file.
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
 * Class ICRMW_Labels_Placeholders_Settings
 *
 * @category ICRMW_Rental
 * @package  RentMeWoo
 * @author   RentMeWP
 * @license  https://rentmewp.com/ GPL-2.0+
 * @link     https://rentmewp.com/
 */
class ICRMW_Labels_Placeholders_Settings {
    /**
     * Constructor
     */
    public function __construct() {
        // Tab ID.
        $this->id = 'rentmewoo';

        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'icrmw_output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'icrmw_save_function_for_labels_placeholders' ) );
    }

    /**
     * LP Settings Array
     *
     * @return array
     */
    private function icrmw_settings() {

        global $current_section;

        if ( 'labels-placeholders' !== $current_section ) {
            return array();
        }

        wp_nonce_field( 'icrmw_labelsplaceholders', 'icrmw_labelsplaceholders_nonce' );

        $settings = array(
            array(
                'name' => esc_html__( 'Manage labels & placeholder', 'rentmewoo' ),
                'type' => 'title',
                'desc' => esc_html__( 'Customize Booking & Request Form Labels and Placeholders to suit your requirements.', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Booking Tab Label', 'rentmewoo' ),
                'id'          => 'icrmw_booking_tab_main_label',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Booking', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Booking date Label', 'rentmewoo' ),
                'id'          => 'icrmw_booking_tab_dates_label',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Booking Dates', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Adult Label', 'rentmewoo' ),
                'id'          => 'icrmw_tab_adult_title',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Adult', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Children Label', 'rentmewoo' ),
                'id'          => 'icrmw_tab_children_title',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Children', 'rentmewoo' ),
            ),
            array(
                'name'              => esc_html__( 'Extra Service Label', 'rentmewoo' ),
                'id'                => 'icrmw_tab_extra_service_title',
                'type'              => 'text',
                'desc'              => '<span class="icrmw_pro_label">PRO</span>',
                'placeholder'       => esc_html__( 'Extra Service', 'rentmewoo' ),
                'custom_attributes' => array(
                    'disabled' => 'disabled',
                    'readonly' => 'readonly',
                ),
                'class'             => 'icrmw_tab_extra_service_title',
            ),
            array(
                'name'              => esc_html__( 'Full Payment Label', 'rentmewoo' ),
                'id'                => 'icrmw_tab_full_payment_title',
                'type'              => 'text',
                'placeholder'       => esc_html__( 'Full Payment', 'rentmewoo' ),
                'desc'              => '<span class="icrmw_pro_label">PRO</span>',
                'custom_attributes' => array(
                    'disabled' => 'disabled',
                    'readonly' => 'readonly',
                ),
            ),
            array(
                'name'              => esc_html__( 'Pay Deposit Label', 'rentmewoo' ),
                'id'                => 'icrmw_tab_pay_deposit_title',
                'type'              => 'text',
                'placeholder'       => esc_html__( 'Pay Deposit', 'rentmewoo' ),
                'desc'              => '<span class="icrmw_pro_label">PRO</span>',
                'custom_attributes' => array(
                    'disabled' => 'disabled',
                    'readonly' => 'readonly',
                ),
            ),
            array(
                'name'        => esc_html__( 'Total Payment Label', 'rentmewoo' ),
                'id'          => 'icrmw_tab_total_payment_title',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Total Payment', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Book Now button Label', 'rentmewoo' ),
                'id'          => 'icrmw_booking_tab_button_title',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Book Now', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Request Tab Label', 'rentmewoo' ),
                'id'          => 'icrmw_request_tab_main_label',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Request Booking', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Name Label', 'rentmewoo' ),
                'id'          => 'icrmw_request_tab_name_title',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Name', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Name Placeholder', 'rentmewoo' ),
                'id'          => 'icrmw_request_tab_name_placeholder',
                'type'        => 'text',
                'placeholder' => esc_html__( 'John Deo', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Email Label', 'rentmewoo' ),
                'id'          => 'icrmw_request_tab_email_title',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Email', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Email Placeholder', 'rentmewoo' ),
                'id'          => 'icrmw_request_tab_email_placeholder',
                'type'        => 'text',
                'placeholder' => esc_html__( 'johndeo@gmail.com', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Phone Label', 'rentmewoo' ),
                'id'          => 'icrmw_request_tab_phone_title',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Phone', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Phone Placeholder', 'rentmewoo' ),
                'id'          => 'icrmw_request_tab_phone_placeholder',
                'type'        => 'text',
                'placeholder' => esc_html__( '555-555-1212', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Prefered Dates Label', 'rentmewoo' ),
                'id'          => 'icrmw_request_tab_dates_label',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Preferred Dates', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Request Now button Label', 'rentmewoo' ),
                'id'          => 'icrmw_request_tab_button_title',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Request Now', 'rentmewoo' ),
            ),
            array(
                'type' => 'sectionend',
            ),
        );

        return $settings;
    }

    /**
     * LP Settings Output
     *
     * @return void
     */
    public function icrmw_output() {
        WC_Admin_Settings::output_fields( $this->icrmw_settings() );
    }

    /**
     * LP Settings Save fields data in Options Table
     *
     * @return void
     */
    public function icrmw_save_function_for_labels_placeholders() {

        global $current_section;

        if ( isset( $_GET['tab'] ) && 'rentmewoo' === $_GET['tab'] && 'labels-placeholders' === $current_section && check_admin_referer( 'icrmw_labelsplaceholders', 'icrmw_labelsplaceholders_nonce' ) ) {

            $icrmw_booking_label        = isset( $_POST['icrmw_booking_tab_main_label'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_booking_tab_main_label'] ) ) : '';
            $icrmw_adult_title          = isset( $_POST['icrmw_tab_adult_title'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_tab_adult_title'] ) ) : '';
            $icrmw_children_title       = isset( $_POST['icrmw_tab_children_title'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_tab_children_title'] ) ) : '';
            $icrmw_total_payment_title  = isset( $_POST['icrmw_tab_total_payment_title'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_tab_total_payment_title'] ) ) : '';
            $icrmw_booking_button_title = isset( $_POST['icrmw_booking_tab_button_title'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_booking_tab_button_title'] ) ) : '';
            $icrmw_request_button_title = isset( $_POST['icrmw_request_tab_button_title'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_tab_button_title'] ) ) : '';
            $icrmw_request_label        = isset( $_POST['icrmw_request_tab_main_label'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_tab_main_label'] ) ) : '';
            $icrmw_name_title           = isset( $_POST['icrmw_request_tab_name_title'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_tab_name_title'] ) ) : '';
            $icrmw_name_placeholder     = isset( $_POST['icrmw_request_tab_name_placeholder'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_tab_name_placeholder'] ) ) : '';
            $icrmw_email_title          = isset( $_POST['icrmw_request_tab_email_title'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_tab_email_title'] ) ) : '';
            $icrmw_email_placeholder    = isset( $_POST['icrmw_request_tab_email_placeholder'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_tab_email_placeholder'] ) ) : '';
            $icrmw_phone_title          = isset( $_POST['icrmw_request_tab_phone_title'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_tab_phone_title'] ) ) : '';
            $icrmw_phone_placeholder    = isset( $_POST['icrmw_request_tab_phone_placeholder'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_tab_phone_placeholder'] ) ) : '';
            $icrmw_booking_dates_label  = isset( $_POST['icrmw_booking_tab_dates_label'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_booking_tab_dates_label'] ) ) : '';
            $icrmw_request_dates_label  = isset( $_POST['icrmw_request_tab_dates_label'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_tab_dates_label'] ) ) : '';

            update_option( 'icrmw_booking_tab_main_label', $icrmw_booking_label, 'no' );
            update_option( 'icrmw_tab_adult_title', $icrmw_adult_title, 'no' );
            update_option( 'icrmw_tab_children_title', $icrmw_children_title, 'no' );
            update_option( 'icrmw_tab_total_payment_title', $icrmw_total_payment_title, 'no' );
            update_option( 'icrmw_booking_tab_button_title', $icrmw_booking_button_title, 'no' );
            update_option( 'icrmw_request_tab_button_title', $icrmw_request_button_title, 'no' );
            update_option( 'icrmw_request_tab_main_label', $icrmw_request_label, 'no' );
            update_option( 'icrmw_request_tab_name_title', $icrmw_name_title, 'no' );
            update_option( 'icrmw_request_tab_name_placeholder', $icrmw_name_placeholder, 'no' );
            update_option( 'icrmw_request_tab_email_title', $icrmw_email_title, 'no' );
            update_option( 'icrmw_request_tab_email_placeholder', $icrmw_email_placeholder, 'no' );
            update_option( 'icrmw_request_tab_phone_title', $icrmw_phone_title, 'no' );
            update_option( 'icrmw_request_tab_phone_placeholder', $icrmw_phone_placeholder, 'no' );
            update_option( 'icrmw_booking_tab_dates_label', $icrmw_booking_dates_label, 'no' );
            update_option( 'icrmw_request_tab_dates_label', $icrmw_request_dates_label, 'no' );

        }
    }
}
new ICRMW_Labels_Placeholders_Settings();
