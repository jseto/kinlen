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
    $table = Database::tableNames()->booking;

		$whereClause = '';
		foreach ($params as $key => $value) {
			$whereClause = $whereClause . $key .'= "' . $value . '" AND ';
		}
		if ( $whereClause != '' ) {
			$whereClause = substr( $whereClause, 0, -5 );
			$whereClause = ' WHERE ' . $whereClause;
		}

		global $wpdb;
	 	return $wpdb->get_results( "SELECT * FROM $table" . $whereClause, OBJECT );
  }

	function getFreeGuide( $params ){
    $guideTable = Database::tableNames()->guide;
		$bookingsTable = Database::tableNames()->booking;
		$guideHolidaysTable = Database::tableNames()->guideHolidays;
		$date = $params[ 'date' ];

		$sqlArr[] =	'SELECT * FROM';
		$sqlArr[] = $guideTable;
		$sqlArr[] = 'WHERE ( id NOT IN ( SELECT guide_id FROM';
		$sqlArr[] = $bookingsTable;
		$sqlArr[] = 'WHERE date =';
		$sqlArr[] = '"' . $params[ 'date' ] . '"';
		$sqlArr[] = ') ) AND ( id not in ( SELECT id FROM';
		$sqlArr[] = $guideHolidaysTable;
		$sqlArr[] = 'WHERE date =';
		$sqlArr[] = '"' . $params[ 'date' ] . '"';
		$sqlArr[] = ') ) ORDER BY score DESC;';

		global $wpdb;
		$resp = $wpdb->get_results( join( ' ', $sqlArr ), OBJECT );
		if ( sizeof( $resp ) ) {
			return $resp[0];
		}
		else {
			return (object)[];
		}
	}

	function queryPeriod( $table, $params ) {
		$whereArr[] = 'date >= "'.$params[ 'minDate' ].'"';
		$whereArr[] = 'date <= "'.$params[ 'maxDate' ].'"';
		unset( $params[ 'minDate' ] );
		unset( $params[ 'maxDate' ] );
		foreach( $params as $key => $value ) {
			$whereArr[] = $key.'= "'.$value.'"';
		}

		return $this->query( $table, $whereArr );
  }

	private function queryGeneric( $table, $params ) {
		foreach ( $params as $key => $value ) {
			$whereArr[] = $key.'= "'.$value.'"';
		}

		return $this->query( $table, $whereArr );
	}

	private function query( $table, $whereArr ) {
		$sqlStr = 'SELECT * FROM '.$table;
		if ( !empty( $whereArr ) ) {
			$sqlStr = $sqlStr.' WHERE '.join( " AND ", $whereArr );
		}

		global $wpdb;
		return $wpdb->get_results( $sqlStr, OBJECT );
	}

	static function tableNames() {
		global $wpdb;

		$prefix = $wpdb->prefix . "kinlen_";
    $tNames[ 'booking' ] = $prefix . "booking";
		$tNames[ 'guide' ] = $prefix . "guide";
		$tNames[ 'guideHolidays' ] = $prefix . "guide_holiday";
		$tNames[ 'restaurantHolidays' ] = $prefix . "restaurant_holiday";
		return (object)$tNames;
	}

  static function createDB(){
    Database::createTables();
    Database::createIndexes();
  }

  static function createTables(){
		global $wpdb;
		$tNames = Database::tableNames();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $tNames->booking (
      id int(10) NOT NULL AUTO_INCREMENT,
      date date,
      time time,
      time_length int(10),
			comment text,
      restaurant_id int(10),
      guide_id int(10),
      booked_seats int(10),
      PRIMARY KEY  (id)
      ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    maybe_create_table( $tNames->booking, $sql );

		$sql = "CREATE TABLE $tNames->guide (
			id int(10) NOT NULL AUTO_INCREMENT,
			name varchar(255),
			score int(3),
			phone varchar(10),
			email varchar(255),
			line_id varchar(255),
			paypal varchar(255),
			PRIMARY KEY (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    maybe_create_table( $tNames->guide, $sql );

		$sql = "CREATE TABLE $tNames->guideHolidays (
			id int(10),
			date date
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  maybe_create_table( $tNames->guideHolidays, $sql );

		$sql = "CREATE TABLE $tNames->restaurantHolidays (
			id int(10),
			date date
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  maybe_create_table( $tNames->restaurantHolidays, $sql );
	}

  static function createIndexes() {

  }

}
