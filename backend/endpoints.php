<?php

/**
 * @package           KinLen
 */

define( 'KINLEN_PATH', plugin_dir_path( __FILE__ ) );

require_once 'database.php';
require_once 'mails.php';

class EndPoints {
  protected $db = NULL;

  function __construct() {
    $this->db = new Database();
  }

  function getBooking( $data ) {
		$table = Database::tableNames()->booking;
		return $this->db->queryGeneric( $table, $data->get_params() );
    // return $this->db->getBooking( $data->get_params() );
  }

	function insertBooking( $data ) {
		$table = Database::tableNames()->booking;
    return $this->db->insert( $table, $data->get_json_params() );
  }

	function updateBooking( $data ) {
		$table = Database::tableNames()->booking;
    $retVal = $this->db->update( $table, $data->get_json_params() );
		if ( $retVal ) {
			$search = array( 'id' => $data->get_json_params()['id'] );
			$retVal = Mails::bookingConfirmation( $this->db->queryGeneric( $table, $search )[0] );
		}
		return $retVal;
  }

	function deleteBooking( $data ) {
		$table = Database::tableNames()->booking;
    return $this->db->deleteRows( $table, $data->get_params() );
  }

	function getFreeGuide( $data ) {
    return $this->db->queryFreeGuidePeriod( $data->get_params() );
  }

	function getRestaurantHolidayPeriod( $data ) {
		$table = Database::tableNames()->restaurantHolidays;

		return $this->db->queryPeriod( $table, $data->get_params() );
	}

	function getBookingPeriod( $data ) {
		$table = Database::tableNames()->booking;
		return $this->db->queryPeriod( $table, $data->get_params() );
	}

	function getRestaurant( $data ) {
		$table = Database::tableNames()->restaurant;

		return $this->db->queryGeneric( $table, $data->get_params() );
	}

	function getCoupon( $data ) {
		$table = Database::tableNames()->coupon;

		return $this->db->queryGeneric( $table, $data->get_params() );
	}

  function createEndpoints() {
		// posible calls:
		// 		http://localhost/wp-json/kinlen/booking/?date=2018-09-25   => all bookings for the day
		// 		http://localhost/wp-json/kinlen/booking/?id=3   => 1 booking by id
		// 		http://localhost/wp-json/kinlen/booking/?date=2018-09-25&time=19:00:00   => all bookings for the day at time
		// 		http://localhost/wp-json/kinlen/booking   => all bookings in the system
    register_rest_route( 'kinlen', '/booking/', array(
			array(
      	'methods' => 'GET',
      	'callback' => array( $this, 'getBooking' )
			),
			array(
      	'methods' => 'POST',
      	'callback' => array( $this, 'insertBooking' )
			),
			array(
      	'methods' => 'DELETE',
      	'callback' => array( $this, 'deleteBooking' )
			),
			array(
      	'methods' => 'PUT',
      	'callback' => array( $this, 'updateBooking' )
			),
    ) );

		// posible calls:
		// 		http://localhost/wp-json/kinlen/booking_period/?restaurant_id=1&minDate=2019-08-01&maxDate=2019-08-31   => all bookings for restaurant_id between minDate and maxDate
    register_rest_route( 'kinlen', '/booking_period/', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getBookingPeriod' ),
    ) );

		// posible calls:
		// 		http://localhost/wp-json/kinlen/free_guide/?date=2018-09-25   => all guides not assigned to booking for the day
		register_rest_route( 'kinlen', '/free_guide/', array(
			'methods' => 'GET',
			'callback' => array( $this, 'getFreeGuide' ),
		) );

		// posible calls:
		// 		http://localhost/wp-json/kinlen/guide_holiday/?id=4&date=2001-05-01   => does guide id have hollidays on date
    register_rest_route( 'kinlen', '/guide_holiday/', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getGuideHoliday' ),
    ) );

		// posible calls:
		// 		http://localhost/wp-json/kinlen/restaurant_holiday/?id=4&date=2001-05-01   => does restaurant id have hollidays on date
    register_rest_route( 'kinlen', '/restaurant_holiday/', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getHoliday' ),
    ) );

		// posible calls:
		// 		http://localhost/wp-json/kinlen/restaurant_holiday_period/?id=1&minDate=2010-09-01&maxDate=2010-09-31   => all hollidays of restaurant id between  minDate and maxDate
		register_rest_route( 'kinlen', '/restaurant_holiday_period/', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getRestaurantHolidayPeriod' ),
    ) );

		register_rest_route( 'kinlen', '/restaurant/', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getRestaurant' ),
    ) );

		register_rest_route( 'kinlen', '/coupon/', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getCoupon' ),
    ) );
  }
}
