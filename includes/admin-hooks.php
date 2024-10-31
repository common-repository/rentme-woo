<?php
/**
 * Admin Side hooks used inside this file.
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
 * ADD A CUSTOM PRODUCT TYPE
 *
 * @param  array $types Types.
 *
 * @return array
 */
function icrmw_add_rental_product_type( $types ) {
    $types['icrmw_rental'] = 'Rental';
    return $types;
}
add_filter( 'product_type_selector', 'icrmw_add_rental_product_type', 10, 1 );
