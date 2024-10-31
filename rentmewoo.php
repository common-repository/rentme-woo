<?php
/**
 * Woocommerce Rental & Booking Plugin.
 *
 * @category  ICRMW_Rental
 * @package   RentMeWoo
 * @author    RentMeWP <info@icubes.org>
 * @copyright 2024 RentMeWP
 * @license   https://rentmewp.com/ GPL-2.0+
 * @link      https://rentmewp.com/
 *
 * Plugin Name: Rentme Woo
 * Plugin URI: https://rentmewp.com/
 * Description: Transform your WooCommerce store into a powerful booking and rental platform with our feature-rich plugin.
 * Version: 1.0.1
 * Text Domain: rentmewoo
 * Domain Path: /languages
 * Requires at least: 5.5
 * Requires PHP: 7.0
 * Author: RentMeWP
 * Author URI: https://icubes.org/
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

/**
 * Class ICRMW_Rental_Product
 *
 * @category ICRMW_Rental
 * @package  RentMeWoo
 * @author   RentMeWP <info@icubes.org>
 * @license  https://rentmewp.com/ GPL-2.0+
 * @link     https://rentmewp.com/
 */
class ICRMW_Rental_Product {
    /**
     * Constructor
     */
    public function __construct() {

        $this->icrmw_define_constants();
        $this->icrmw_includes();
        add_action( 'plugins_loaded', [ $this, 'init' ] );

        add_filter( 'plugin_action_links_' . ICRMW_PLUGIN_BASE, [ $this, 'icrmw_action_setting_link' ] );

        // Hook the icrmw_plugin_activate callback function to the activation hook.
        register_activation_hook( ICRMW_PLUGIN_FILE, [ $this, 'icrmw_plugin_activate' ] );
    }

    /**
     * Init
     *
     * @return void
     */
    public function init() {
        add_filter( 'woocommerce_product_class', array( $this, 'icrmw_set_rental_product_class' ), 10, 2 );

        // Register wc rental product type at admin.
        include_once ICRMW_PLUGIN_PATH . '/admin/product-edit/class-icrmw-rental-wc-product.php';
    }

    /**
     * Define constants
     *
     * @return void
     */
    public function icrmw_define_constants() {
        define( 'ICRMW_VERSION', '1.0.1' );
        define( 'ICRMW_PLUGIN_FILE', __FILE__ );
        define( 'ICRMW_PLUGIN_BASE', plugin_basename( ICRMW_PLUGIN_FILE ) );
        define( 'ICRMW_PLUGIN_URI', plugin_dir_url( ICRMW_PLUGIN_FILE ) );
        define( 'ICRMW_PLUGIN_PATH', plugin_dir_path( ICRMW_PLUGIN_FILE ) );
    }

    /**
     * Plugin action links.
     *
     * Adds action links to the plugin list table
     *
     * Fired by `plugin_action_links` filter.
     *
     * @param array $links An array of plugin action links.
     *
     * @since  1.0.0
     * @access public
     *
     * @return array An array of plugin action links.
     */
    public function icrmw_action_setting_link( $links ) {
        $settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=wc-settings&tab=rentmewoo' ), esc_html__( 'Settings', 'rentmewoo' ) );

        array_unshift( $links, $settings_link );

        return $links;
    }

    /**
     * Include files
     *
     * @return void
     */
    public function icrmw_includes() {

        // Action & Filter hooks - Frontend.
        include_once ICRMW_PLUGIN_PATH . '/includes/frontend-hooks.php';

        // Action & Filter hooks - Admin.
        include_once ICRMW_PLUGIN_PATH . '/includes/admin-hooks.php';

        // Add custom fields to admin.
        include_once ICRMW_PLUGIN_PATH . '/admin/product-edit/class-icrmw-cf-handling.php';

        // Admin settings.
        include_once ICRMW_PLUGIN_PATH . '/admin/woo-settings/class-icrmw-admin.php';

        // Enqueue scripts and styles.
        include_once ICRMW_PLUGIN_PATH . '/includes/register-assets.php';

        // All custom functions inside.
        include_once ICRMW_PLUGIN_PATH . '/includes/custom-functions.php';
    }

    /**
     * Installation Part
     *
     * @return void
     */
    public function icrmw_plugin_activate() {

        // Check if the plugin is being activated for the first time.
        if ( get_option( 'icrmw_plugin_activated' ) !== '1' ) {

            add_option( 'icrmw_basic_enable_deposit', 'yes', '', 'no' );
            add_option( 'icrmw_basic_enable_booking_tab', 'yes', '', 'no' );
            add_option( 'icrmw_basic_enable_request_tab', 'yes', '', 'no' );

            $icrmw_request_email_recipient = [ get_option( 'admin_email' ) ];
            $icrmw_request_email_recipient = implode( ',', $icrmw_request_email_recipient );
            add_option( 'icrmw_request_form_email_recipient', $icrmw_request_email_recipient, '', 'no' );

            $icrmw_request_email_subject = 'Request For Booking';
            add_option( 'icrmw_request_form_email_subject', $icrmw_request_email_subject, '', 'no' );

            $request_mail_template = 'Hi {full_name},<br /><br />Thank you for your interest in our {icrmw_product_name}. We have received your inquiry and will get back to you shortly to assist with your rental request.<br /><br />Here are the details you provided:<br /><br /><b>Name :</b>{full_name}<br /><b>Email :</b>{user_email}<br /><b>Phone Number :</b>{user_phone}<br /><b>Check-in :</b>{icrmw_check_in}<br /><b>Checkout :</b>{icrmw_check_out}<br /><br /><br />Our team will review your request and reach out to you with more information, answers to your questions, and assistance in planning your upcoming trip.We appreciate your interest in our services and look forward to helping you with your rental needs.';
            $request_mail_template = wp_kses_post( $request_mail_template );
            add_option( 'icrmw_request_form_email_body', $request_mail_template, '', 'no' );

            // Mark the plugin as activated to avoid re-insertion on future activations.
            update_option( 'icrmw_plugin_activated', '1', 'no' );
        }
    }

    /**
     * Set Rental Product Class
     *
     * @param  string $classname    name of the product class.
     * @param  string $product_type Product Type.
     *
     * @return string
     */
    public function icrmw_set_rental_product_class( $classname, $product_type ) {
        if ( 'icrmw_rental' === $product_type ) {
            $classname = 'ICRMW_Rental_WC_Product';
        }
        return $classname;
    }
}



if ( ! function_exists( 'is_plugin_active' ) ) {
    // Required plugin.php to use is_plugin_active().
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    new ICRMW_Rental_Product();
}
