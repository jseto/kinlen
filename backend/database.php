<?php

/**
 * @package           KinLen
 */

class Database {
	/**
	 * Generic booking table fetch.
	 * Values in $params array are transformed in a sql where clause
	 * @param  array $params array of fields for query
	 * @return response response results of the query
	 */
  function getBooking( $params ){
    global $wpdb;
    $table = $wpdb->prefix . 'kinlen_booking';

		$whereClause = '';
		foreach ($params as $key => $value) {
			$whereClause = $whereClause . $key .'= "' . $value . '" AND ';
		}
		if ( $whereClause != '' ) {
			$whereClause = substr( $whereClause, 0, -5 );
			$whereClause = ' WHERE ' . $whereClause;
		}

	 	return $wpdb->get_results( "SELECT * FROM $table" . $whereClause, OBJECT );

		//
		// if ( isset( $params['id'] ) ) {
		// 	$id = $params['id'];
		// 	return $wpdb->get_results( "SELECT * FROM $table WHERE id='$id'", OBJECT );
		// }
		// else if ( isset( $params['date'] ) ){
    //   $date = $params['date'];
    //   return $wpdb->get_results( "SELECT * FROM $table WHERE date='$date'", OBJECT );
    // }
    // else {
    //   return $wpdb->get_results( "SELECT * FROM $table", OBJECT );
    // }
  }

	function getFreeGuides( $params ){
    global $wpdb;
    $tableGuide = $wpdb->prefix . 'kinlen_guide';
		$tableBooking = $wpdb->prefix . 'kinlen_booking';
		$date = $params[ 'date' ];

		$whereClause = 'SELECT * FROM $tableGuide WHERE guide_id NOT IN ( SELECT guide_id FROM $tableBooking WHERE date = $date )';
	}

  static function createDB(){
    Database::createTables();
    Database::createIndexes();
  }

  static function createTables(){
    global $wpdb;
    $guideBookingsTableName = $wpdb->prefix . "kinlen_booking";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $guideBookingsTableName (
      id int(10) NOT NULL AUTO_INCREMENT,
      date date,
      time time,
      time_length int(10),
      restaurant_id int(10),
      guide_id int(10),
      booked_seats int(10),
      PRIMARY KEY  (id)
      ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    maybe_create_table( $guideBookingsTableName, $sql );
  }

  static function createIndexes() {

  }

}
