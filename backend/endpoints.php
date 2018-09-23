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

  function getGuideBooking( $data ) {
    return $this->db->getGuideBooking( $data->get_params() );
  }

  function getAvailMap( $data ) {
    return $this->db->getGuideBooking( $data->get_params() );
  }

  function createEndpoints() {
    register_rest_route( 'kinlen', '/guide_booking/(?P<id>\d+)', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getGuideBooking' ),
    ) );

    register_rest_route( 'kinlen', '/avail_map', array(
      'methods' => 'GET',
      'callback' => array( $this, 'getAvailMap' ),
    ) );
  }
}
