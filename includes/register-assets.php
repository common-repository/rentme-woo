<?php
/**
 * All Custom Functions defined inside this file.
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
 * ICRMW_Register_Assets Class handler for admin & frontend assets
 */
class ICRMW_Register_Assets extends WP_Scripts {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct( 'admin-main-js', false );
        add_action( 'admin_enqueue_scripts', array( $this, 'icrmw_enqueue_admin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'icrmw_frontend_enqueue_styles_and_scripts' ), 10, 0 );
    }

    /**
     * Enqueue Admin Scripts
     */
    public function icrmw_enqueue_admin_scripts() {
        wp_enqueue_script( 'admin-main-js', ICRMW_PLUGIN_URI . 'assets/js/admin-main.min.js', array( 'jquery' ), ICRMW_VERSION, true );
        wp_localize_script( 'admin-main-js', 'admin_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

        // admin css file.
        wp_enqueue_style( 'admin-main-css', ICRMW_PLUGIN_URI . 'assets/css/admin-main.css', array(), ICRMW_VERSION );
    }

    /**
     * Frontend Enqueue Styles and Scripts
     */
    public function icrmw_frontend_enqueue_styles_and_scripts() {
        wp_enqueue_style( 'frontend-main-css', ICRMW_PLUGIN_URI . 'assets/css/frontend-main.css', array(), ICRMW_VERSION );

        wp_enqueue_script( 'daterangepicker-js', ICRMW_PLUGIN_URI . 'external-libraries/daterangepicker/daterangepicker.js', array( 'jquery', 'moment' ), ICRMW_VERSION, true );

        wp_enqueue_style( 'daterangepicker-css', ICRMW_PLUGIN_URI . 'external-libraries/daterangepicker/daterangepicker.css', array(), ICRMW_VERSION );

        wp_enqueue_script( 'frontend-main-js', ICRMW_PLUGIN_URI . 'assets/js/frontend-main.min.js', array( 'jquery' ), ICRMW_VERSION, true );
        wp_localize_script( 'frontend-main-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}
new ICRMW_Register_Assets();
