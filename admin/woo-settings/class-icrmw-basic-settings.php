<?php
/**
 * Admin Basic Settings Tab defined inside this file.
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
 * Class ICRMW_Basic_Settings
 *
 * @category ICRMW_Rental
 * @package  RentMeWoo
 * @author   RentMeWP
 * @license  https://rentmewp.com/ GPL-2.0+
 * @link     https://rentmewp.com/
 */
class ICRMW_Basic_Settings {
    /**
     * Constructor
     */
    public function __construct() {
        // Tab ID.
        $this->id = 'rentmewoo';

        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'icrmw_output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'icrmw_save_function_for_basic' ) );
    }

    /**
     * Basic Settings Array
     *
     * @return array
     */
    private function icrmw_settings() {

        global $current_section;

        if ( '' !== $current_section ) {
            return array();
        }

        wp_nonce_field( 'icrmw_basicsettings', 'icrmw_basicsettings_nonce' );

        $settings = array(
            array(
                'name' => esc_html__( 'Global Settings', 'rentmewoo' ),
                'type' => 'title',
                'desc' => esc_html__( 'Configure global settings for your booking and rental plugin.', 'rentmewoo' ),
            ),
            array(
                'name'              => esc_html__( 'Deposit', 'rentmewoo' ),
                'desc'              => 'Yes <span class="icrmw_pro_label">PRO</span>',
                'desc_tip'          => esc_html__( 'Enable the Deposit feature for bookings so that customers can pay a deposit equal to the total amount.', 'rentmewoo' ),
                'id'                => 'icrmw_basic_enable_deposit',
                'type'              => 'checkbox',
                'custom_attributes' => array(
                    'disabled' => 'disabled',
                    'readonly' => 'readonly',
                ),
                'class'             => 'icrmw_basic_enable_deposit',
            ),
            array(
                'name'     => esc_html__( 'Booking Tab', 'rentmewoo' ),
                'desc'     => 'Yes',
                'desc_tip' => esc_html__( 'Display the booking tab on product pages, enabling customers to book products online and make payments.', 'rentmewoo' ),
                'id'       => 'icrmw_basic_enable_booking_tab',
                'type'     => 'checkbox',
            ),
            array(
                'name'     => esc_html__( 'Request Tab', 'rentmewoo' ),
                'desc'     => 'Yes',
                'desc_tip' => esc_html__( 'Display the request tab on product pages. enabling customers to submit booking inquiries for products.', 'rentmewoo' ),
                'id'       => 'icrmw_basic_enable_request_tab',
                'type'     => 'checkbox',
            ),
            array(
                'type' => 'sectionend',
            ),
        );

        return $settings;
    }

    /**
     * Basic Settings Output
     *
     * @return void
     */
    public function icrmw_output() {
        WC_Admin_Settings::output_fields( $this->icrmw_settings() );
    }

    /**
     * Basic Settings Save fields data in Options Table
     *
     * @return void
     */
    public function icrmw_save_function_for_basic() {

        global $current_section;

        if ( isset( $_GET['tab'] ) && 'rentmewoo' === $_GET['tab'] && '' === $current_section && check_admin_referer( 'icrmw_basicsettings', 'icrmw_basicsettings_nonce' ) ) {

            $icrmw_enable_booking_tab = isset( $_POST['icrmw_basic_enable_booking_tab'] ) ? 'yes' : 'no';
            $icrmw_enable_request_tab = isset( $_POST['icrmw_basic_enable_request_tab'] ) ? 'yes' : 'no';

            update_option( 'icrmw_basic_enable_booking_tab', $icrmw_enable_booking_tab, 'no' );
            update_option( 'icrmw_basic_enable_request_tab', $icrmw_enable_request_tab, 'no' );
        }
    }
}
new ICRMW_Basic_Settings();
