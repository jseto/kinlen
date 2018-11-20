<?php

/**
 * @package           KinLen
 *
 * Plugin Name:       KinLen
 * Plugin URI:        http://github.com/jseto/kinlen
 * Description:       KinLen utilities
 * Version:           0.1.0
 * Author:            Josep Seto
 * Author URI:        http://github.com/jseto
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kinlen
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'KINLEN_VERSION', '0.1.0' );

require_once plugin_dir_path( __FILE__ ) . 'backend/endpoints.php';

class Kinlen {
  function __construct() {
    $endPoints = new EndPoints();
    add_action( 'rest_api_init', array( $endPoints, 'createEndpoints' ) );
  }

  function enqueueAdminScripts() {
    //  wp_enqueue_style( 'kinlenstyle', plugins_url( '/backend/css/kinlen.css', __FILE__) );
    //  wp_enqueue_script( 'kinlenjs', plugins_url( '/backend/js/kinlen.js', __FILE__) );
  }

	function enqueueFrontEndScripts() {
		wp_enqueue_style( 'kinlenstyle', plugins_url( '/frontend/css/kinlen.css', __FILE__), false, '0.1.0' );

//		wp_enqueue_script( 'kinlen-bookings-vendor', plugins_url( '/frontend/kinlen-bookings/vendor.kinlen.js', __FILE__) );
		wp_enqueue_script( 'kinlen-bookings', plugins_url( '/frontend/kinlen-bookings/main.kinlen.js', __FILE__), array( 'flatpickr' ), '', true );
		// wp_enqueue_script( 'kinlenjs', plugins_url( '/frontend/js/kinlen.js', __FILE__) );
  }

  function register() {
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminScripts' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueueFrontEndScripts' ) );
  }

  function activate(){
    Database::createDB();
    flush_rewrite_rules();
  }

  function deactivate(){
    flush_rewrite_rules();
  }
}

$kinlen = new Kinlen();
$kinlen->register();
register_activation_hook( __FILE__, array( $kinlen, 'activate' ) );
register_deactivation_hook( __FILE__, array( $kinlen, 'deactivate' ) );
