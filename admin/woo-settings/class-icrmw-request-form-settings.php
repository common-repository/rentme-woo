<?php
/**
 * Admin Request Settings Tab defined inside this file.
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
 * Class ICRMW_Request_Form_Settings
 *
 * @category ICRMW_Rental
 * @package  RentMeWoo
 * @author   RentMeWP
 * @license  https://rentmewp.com/ GPL-2.0+
 * @link     https://rentmewp.com/
 */
class ICRMW_Request_Form_Settings {
    /**
     * Constructor
     */
    public function __construct() {
        // Tab ID.
        $this->id = 'rentmewoo';

        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'icrmw_output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'icrmw_save_function_for_request_settings' ) );
    }

    /**
     * LP Settings Array
     *
     * @return array
     */
    private function icrmw_settings() {

        global $current_section;

        if ( 'request-settings' !== $current_section ) {
            return array();
        }

        wp_nonce_field( 'icrmw_requestsettings', 'icrmw_requestsettings_nonce' );

        $settings = array(
            array(
                'name' => esc_html__( 'Request Settings', 'rentmewoo' ),
                'type' => 'title',
                'desc' => esc_html__( 'Customize Request Form Email Content to suit your requirements.', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Email Recipient(s)', 'rentmewoo' ),
                'id'          => 'icrmw_request_form_email_recipient',
                'type'        => 'text',
                'desc_tip'    => esc_html__( 'Enter recipients (comma separated) for this email.', 'rentmewoo' ),
                'placeholder' => '',
            ),
            array(
                'name'        => esc_html__( 'Email Subject', 'rentmewoo' ),
                'id'          => 'icrmw_request_form_email_subject',
                'type'        => 'text',
                'placeholder' => esc_html__( 'Request For Booking', 'rentmewoo' ),
            ),
            array(
                'name'        => esc_html__( 'Email Body', 'rentmewoo' ),
                'id'          => 'icrmw_request_form_email_body',
                'type'        => 'textarea',
                'desc'        => '<a class="button icrmw_reset_email_template">Reset to Default Email Template</a> The message body. Use <code>{full_name}</code> to insert full name in the email. <code>{user_email}</code> to insert user email address. <code>{user_phone}</code> to insert user phone number.<code>{sitename}</code> to insert your site name. <code>{site_url}</code>  to insert your site URL. If you want to insert checkin time use <code>{icrmw_check_in}</code> and for checkout time use <code>{icrmw_check_out}</code>. To insert the product name for which the form was filled use <code>{icrmw_product_name}</code>.',
                'placeholder' => '',
                'value'       => wp_kses_post( wp_unslash( get_option( 'icrmw_request_form_email_body' ) ) ),

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
    public function icrmw_save_function_for_request_settings() {
        global $current_section;

        if ( isset( $_GET['tab'] ) && 'rentmewoo' === $_GET['tab'] && 'request-settings' === $current_section && check_admin_referer( 'icrmw_requestsettings', 'icrmw_requestsettings_nonce' ) ) {

            $icrmw_request_email_recipient = isset( $_POST['icrmw_request_form_email_recipient'] ) ? sanitize_email( wp_unslash( $_POST['icrmw_request_form_email_recipient'] ) ) : '';
            $icrmw_request_email_subject   = isset( $_POST['icrmw_request_form_email_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['icrmw_request_form_email_subject'] ) ) : '';
            $icrmw_request_email_body      = isset( $_POST['icrmw_request_form_email_body'] ) ? wp_kses_post( wp_unslash( $_POST['icrmw_request_form_email_body'] ) ) : '';

            update_option( 'icrmw_request_form_email_recipient', $icrmw_request_email_recipient, 'no' );
            update_option( 'icrmw_request_form_email_subject', $icrmw_request_email_subject, 'no' );
            update_option( 'icrmw_request_form_email_body', $icrmw_request_email_body, 'no' );

        }
    }
}
new ICRMW_Request_Form_Settings();
