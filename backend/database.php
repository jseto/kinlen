<?php

/**
 * @package           KinLen
 */

class Database {

  function getGuideBooking( $params ){
    global $wpdb;
    $table = $wpdb->prefix . 'kinlen_guide_booking';

    if ( $params['date'] != '' ){
      $date = $params['date'];
      return $wpdb->get_results( "SELECT * FROM $table WHERE date='$date'", OBJECT );
    }
    else {
      return $wpdb->get_results( "SELECT * FROM $table", OBJECT );
    }
  }

  static function createDB(){
    Database::createTables();
    Database::createIndexes();
  }

  static function createTables(){
    global $wpdb;
    $guideBookingsTableName = $wpdb->prefix . "kinlen_guide_booking";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $guideBookingsTableName (
      id int(10) NOT NULL AUTO_INCREMENT,
      date date,
      time time,
      time_lenght int(10),
      restaurant_booking_id int(10),
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
