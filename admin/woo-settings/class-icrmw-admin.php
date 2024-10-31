<?php
/**
 * Admin woo settings sections/tabs defined inside this file.
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
 * Admin Class handler
 */
class ICRMW_Admin {
    /**
     * Class initialize
     */
    public function __construct() {
    	// Tab ID.
        $tab_id = 'rentmewoo';
    	add_action( 'init', array( $this, 'icrmw_admin_includes' ) );
    	// Hook into WooCommerce settings tabs.
    	add_filter( 'woocommerce_settings_tabs_array', array( $this, 'icrmw_custom_woo_setting_tab' ), 21 );
        add_action( 'woocommerce_sections_' . $tab_id, array( $this, 'icrmw_new_sections_in_custom_tab' ) );
    }

    /**
	 * Include any classes we need within admin.
	 */
	public function icrmw_admin_includes() {
        include_once ICRMW_PLUGIN_PATH . '/admin/woo-settings/class-icrmw-basic-settings.php';
        include_once ICRMW_PLUGIN_PATH . '/admin/woo-settings/class-icrmw-labels-placeholders-settings.php';
        include_once ICRMW_PLUGIN_PATH . '/admin/woo-settings/class-icrmw-request-form-settings.php';
	}

	/**
     * Adding custom tab inside woo settings.
     *
     * @param  array $tabs Woo Setting Tabs.
     * @return array
     */
    public function icrmw_custom_woo_setting_tab( $tabs ) {
        // Add a new tab with a unique slug and a label.
        $tabs['rentmewoo'] = __( 'Rentme Woo', 'rentmewoo' );
        return $tabs;
    }

    /**
     * Output sections for the custom tab.
     */
    public function icrmw_new_sections_in_custom_tab() {
    	global $current_section;

	    $sections = array(
	        ''                    => esc_html__( 'Global Settings', 'rentmewoo' ),
	        'labels-placeholders' => esc_html__( 'Manage labels & placeholder', 'rentmewoo' ),
	        'request-settings'    => esc_html__( 'Request Settings', 'rentmewoo' ),
	    );

	    echo '<ul class="subsubsub">';

	    foreach ( $sections as $id => $label ) {
	        $url = add_query_arg(
	            array(
	                'page'    => 'wc-settings',
	                'tab'     => 'rentmewoo',
	                'section' => $id,
	            ),
	            admin_url( 'admin.php' )
	        );

	        $current = $current_section === $id ? 'class=current' : '';

	        $keys      = array_keys( $sections );
	        $separator = end( $keys ) === $id ? '' : '|';

	        // echo "<li><a href=\"$url\" $current>$label</a> $separator </li>";.
	        echo '<li><a href="' . esc_url( $url ) . '" ' . esc_attr( $current ) . '>' . esc_html( $label ) . '</a> ' . esc_html( $separator ) . '</li>';

	    }

	    echo '</ul><br class="clear" />';
    }
}
new ICRMW_Admin();
