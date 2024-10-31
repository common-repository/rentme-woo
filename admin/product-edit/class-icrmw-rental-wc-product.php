<?php
/**
 * Register icrmw_rental Product type.
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
 * ICRMW Rental Product Class handler
 */
class ICRMW_Rental_WC_Product extends WC_Product {
    /**
     * Constructor
     *
     * @param mixed $product Product instance.
     */
    public function __construct( $product ) {
        $this->product_type = 'icrmw_rental';
        parent::__construct( $product );
    }
}
