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
 * Return value of days inside 2 dates
 *
 * @param  string $date1 date.
 * @param  string $date2 date.
 *
 * @return int
 */
function icrmw_calculate_days_between_dates( $date1, $date2 ) {
    $date_time1 = new DateTime( $date1 );
    $date_time2 = new DateTime( $date2 );

    $interval = $date_time2->diff( $date_time1 );

    return $interval->days;
}


/**
 * Return value of hours inside 2 dates with time
 *
 * @param  string $date1 date.
 * @param  string $date2 date.
 *
 * @return int
 */
function icrmw_calculate_hours_between_dates( $date1, $date2 ) {
    $date_time1 = DateTime::createFromFormat( 'Y-m-d H:i', $date1 );
    $date_time2 = DateTime::createFromFormat( 'Y-m-d H:i', $date2 );

    $interval = $date_time2->diff( $date_time1 );

    $hours  = $interval->h; // Hours.
    $hours += $interval->days * 24; // Add the hours from days.

    // Add an extra hour only if there is extra minutes.
    if ( $interval->i > 0 ) {
        $hours += 1;
    }

    return $hours;
}


/**
 * Get all the Products which has Product Type: icrmw_rental
 *
 * @return array
 */
function icrmw_get_rental_all_products() {
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => '-1',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => 'icrmw_rental',
            ),
        ),
    );

    $results = new WP_Query( $args );

    return $results;
}


/**
 * Get current date in WordPress timezone
 *
 * @param  string $format Format.
 *
 * @return string
 */
function icrmw_get_current_date( $format = 'Y-m-d' ) {
    return date_i18n( $format );
}


/**
 * Get current time in WordPress timezone
 *
 * @param  string $format Format.
 *
 * @return string
 */
function icrmw_get_wp_current_time( $format = 'H:i:s' ) {
    return date_i18n( $format );
}


/**
 * Get current date in WordPress timezone in format "Month Date"
 *
 * @return string
 */
function icrmw_get_current_date_custom_format() {
    // Get current date in WordPress timezone.
    $current_date = current_time( 'Y-m-d' );

    // Convert the date to a DateTime object.
    $date_object = date_create( $current_date );

    // Format the date as "Month Date" (e.g., "August 15").
    $formatted_date = date_format( $date_object, 'F j' );

    return $formatted_date;
}


/**
 * Get Week days Between Dates
 *
 * @param  string $start_date Start Date.
 * @param  string $end_date   End Date.
 *
 * @return array
 */
function icrmw_get_weekdays_between_dates( $start_date, $end_date ) {
    $start    = new DateTime( $start_date );
    $end      = new DateTime( $end_date );
    $interval = new DateInterval( 'P1D' );
    $period   = new DatePeriod( $start, $interval, $end );
    $weekdays = [];
    foreach ( $period as $date ) {
        // 1 (Monday) through 7 (Sunday).
        $day_of_week                        = $date->format( 'N' );
        $weekdays[$date->format( 'Y-m-d' )] = $day_of_week;
    }

    if ( $start->format( 'Y-m-d' ) === $end->format( 'Y-m-d' ) ) {
        $day_of_week                           = $start->format( 'N' );
        $weekdays[ $start->format( 'Y-m-d' ) ] = $day_of_week;
    }

    return array_filter(
        $weekdays,
        function ( $day_of_week ) {
            // Filtering weekdays (Monday to Saturday).
            return $day_of_week <= 7;
        }
    );
}


/**
 * Get Week Day Name
 *
 * @param  string $day_of_week Day Of Week.
 *
 * @return string
 */
function icrmw_get_day_name( $day_of_week ) {
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    return $days[$day_of_week - 1];
}


/**
 * Validate checkin checkout dates correct format or not
 *
 * @param  string $checkin_date    Checkin Date.
 * @param  string $checkout_date   Checkout Date.
 * @param  string $icrmw_charge_type Rent Type.
 *
 * @return mixed|boolean
 */
function icrmw_validate_checkin_checkout_dates( $checkin_date, $checkout_date, $icrmw_charge_type ) {
    // Get the current local WordPress time in the format Y-m-d.
    $current_date = current_time( 'Y-m-d' );

    // Check if the provided dates are in the correct format.
    if ( ! DateTime::createFromFormat( 'Y-m-d H:i', $checkin_date ) && 'hour' === $icrmw_charge_type ) {
        return wc_add_notice( 'Check-in date is not in the correct format (Y-m-d H:i).', 'error' );
    }

    if ( 'day' === $icrmw_charge_type && ! DateTime::createFromFormat( 'Y-m-d', $checkin_date ) ) {
        return wc_add_notice( 'Check-in date is not in the correct format (Y-m-d).', 'error' );
    }

    if ( ! DateTime::createFromFormat( 'Y-m-d H:i', $checkout_date ) && 'hour' === $icrmw_charge_type ) {
        return wc_add_notice( 'Checkout date is not in the correct format (Y-m-d H:i).', 'error' );
    }

    if ( 'day' === $icrmw_charge_type && ! DateTime::createFromFormat( 'Y-m-d', $checkout_date ) ) {
        return wc_add_notice( 'Checkout date is not in the correct format (Y-m-d).', 'error' );
    }

    // Compare the provided check-in and checkout dates with the current date.
    if ( $checkin_date < $current_date || $checkout_date < $current_date ) {
        return wc_add_notice( 'Both check-in and checkout dates must be greater than the current date.', 'error' );
    }

    // If all checks pass, return true indicating the dates are valid.
    return true;
}


/**
 * Convert dates to H:i am/pm format from 24 hour time format
 *
 * @param  string $time Time.
 *
 * @return string
 */
function icrmw_convert_time_format( $time ) {
    $formatted_time = '';
    if ( ! empty( $time ) ) {
        $formatted_time = date( 'h:i A', strtotime( $time ) );
    }
    return $formatted_time;
}
