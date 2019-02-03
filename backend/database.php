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

	function queryFreeGuide( $params ){
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

	function queryFreeGuidePeriod( $params ) {
		if ( isset( $params ['date'] ) ) {
			return $this->queryFreeGuide( $params );
		}

		$currentDate = strtotime( $params[ 'minDate' ] );
		$maxDate = strtotime( $params[ 'maxDate' ] );
		while ( $currentDate <= $maxDate ) {
			$dateStr = date( "Y-m-d", $currentDate );
			$obj = $this->queryFreeGuide( [ "date" => $dateStr ] );
			$obj->date = $dateStr;
			$resp[] = $obj;
			$currentDate = strtotime("+1 day", $currentDate );
		}
		return $resp;
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

	private function buildWhereArray( $params ) {
		$whereArr = [];
		foreach ( $params as $key => $value ) {
			$whereArr[] = $key.'= "'.$value.'"';
		}

		return $whereArr;
	}

	function queryGeneric( $table, $params ) {
		return $this->query( $table, $this->buildWhereArray( $params ) );
	}

	private function query( $table, $whereArr, $removeToken = true ) {
		$sqlStr = 'SELECT * FROM '.$table;
		if ( !empty( $whereArr ) ) {
			$sqlStr = $sqlStr.' WHERE '.join( " AND ", $whereArr );
		}

		global $wpdb;
		$resp = $wpdb->get_results( $sqlStr, OBJECT );
		if ( $removeToken ) {
			foreach ( $resp as $row ) {
				unset( $row->token );
			}
		}
		return $resp;
	}

	function insert( $table, $data ) {
		if ( !$data[ 'paid' ] ) {
			global $wpdb;
			$data[ 'token' ] = wp_generate_uuid4();
			$wpdb->insert( $table, $data );

			$sqlStr = 'SELECT * FROM '.$table.' WHERE id='.$wpdb->insert_id;
			return $wpdb->get_results( $sqlStr, OBJECT );
		}
		else {
			return [];
		}
	}

	function update( $table, $data ) {
		if ( isset( $data[ 'token' ] ) ) {
			global $wpdb;
			$search['id']=$data['id'];
			$search['token']=$data['token'];
			unset( $data['id'] );
			unset( $data['token'] );
			return $wpdb->update( $table, $data, $search );
		}
		else {
			return false;
		}
	}

	function deleteRows( $table, $data ) {
		if ( isset( $data['token'] ) ) {
			global $wpdb;
			return $wpdb->delete( $table, $data );
		}
		else {
			return false;
		}
	}

	static function tableNames() {
		global $wpdb;

		$prefix = $wpdb->prefix . "kinlen_";
    $tNames[ 'booking' ] = $prefix . "booking";
		$tNames[ 'guide' ] = $prefix . "guide";
		$tNames[ 'guideHolidays' ] = $prefix . "guide_holiday";
		$tNames[ 'restaurantHolidays' ] = $prefix . "restaurant_holiday";
		$tNames[ 'restaurant' ] = $prefix . "restaurant";
		$tNames[ 'coupon' ] = $prefix . "coupon";
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
			adults int(10),
			children int(10),
			coupon varchar(15),
			adultPrice int(10),
			childrenPrice int(10),
			couponValue int(10),
			paidAmount int(10),
			paid int(1),
			name varchar(255),
			email varchar(255),
			paymentProvider varchar(255),
			paymentId varchar(255),
			currency varchar(3),
			trasactionTimeStamp timestamp,
			token varchar(255),
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
			token varchar(255),
			PRIMARY KEY (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    maybe_create_table( $tNames->guide, $sql );

		$sql = "CREATE TABLE $tNames->guideHolidays (
			id int(10),
			date date,
			token varchar(255)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  maybe_create_table( $tNames->guideHolidays, $sql );

		$sql = "CREATE TABLE $tNames->restaurantHolidays (
			id int(10),
			date date,
			token varchar(255)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  maybe_create_table( $tNames->restaurantHolidays, $sql );

		$sql = "CREATE TABLE $tNames->restaurant (
			id int(10),
			name varchar(255),
			adultPrice int(10),
			childrenPrice int(10),
			description text,
			excerpt varchar(255),
			services varchar(255),
			foodTypes varchar(255),
			dishSpecials varchar(255),
			valoration float(2,2),
			numberOfReviews int(10),
			includes varchar(255),
			excludes varchar(255),
			phone varchar(255),
			staffNames varchar(255),
			address varchar(255),
			googleMaps varchar(255),
			images varchar(255),
			paypal varchar(255),
			token varchar(255),
			PRIMARY KEY (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  maybe_create_table( $tNames->restaurant, $sql );

		$sql = "CREATE TABLE $tNames->coupon (
			id int(10),
			code varchar(20),
			validUntil date,
			value int(10),
			valueType varchar(10),
			commission int(10),
			commisionistId int(10),
			token varchar(255),
			PRIMARY KEY (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  maybe_create_table( $tNames->coupon, $sql );

	}

  static function createIndexes() {

  }

}
