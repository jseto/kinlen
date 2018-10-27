<?php

/**
 * @package           KinLen
 */

require_once 'database.php';

class EndPoints {
  protected $db = NULL;

  function __construct() {
    $this->db = new Database();
  }

  function getBooking( $data ) {
    return $this->db->getBooking( $data->get_params() );
  }

	function getFreeGuide( $data ) {
    return $this->db->getFreeGuide( $data->get_params() );
  }

  function getAvailMap( $data ) {
    return $this->db->getBooking( $data->get_params() );
  }

  function createEndpoints() {
		// posible calls:
		// 		http://localhost/wp-json/kinlen/booking/?date=2018-09-25   => all bookings for the day
		// 		http://localhost/wp-json/kinlen/booking/?id=3   => 1 booking by id
		// 		http://localhost/wp-json/kinlen/booking/?date=2018-09-25&time=19:00:00   => all bookings for the day at time
		// 		http://localhost/wp-json/kinlen/booking   => all bookings in the system
    register_rest_route( 'kinlen', '/booking/', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getBooking' ),
    ) );

		// posible calls:
		// 		http://localhost/wp-json/kinlen/free_guide/?date=2018-09-25   => all guides not assigned to booking for the day
		register_rest_route( 'kinlen', '/free_guide/', array(
			'methods' => 'GET',
			'callback' => array( $this, 'getFreeGuides' ),
		) );

    // check call http://localhost/wp-json/kinlen/avail_map/?date=2018-09-25
    register_rest_route( 'kinlen', '/avail_map/', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getAvailMap' ),
    ) );
  }
}
