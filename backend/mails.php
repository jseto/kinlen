<?php

/**
 * @package           KinLen
 */

class Mails {
	static function bookingConfirmation( $booking ) {
		$day = new DateTime( $booking->date );
		$time = new DateTime( $booking->time );

		$body = file_get_contents( KINLEN_PATH . 'templates/booking-confirmation.html' );
		$body = strtr( $body, array(
			'$name' => $booking->name,
			'$email' => $booking->email,
			'$adults' => $booking->adults,
			'$children' => $booking->children,
			'$comments' => $booking->comment,
			'$date' => $day->format('D, d M Y'),
			'$time' => $time->format('H:i'),
 		));

		return wp_mail( $booking->email, 'KinLen booking confirmation', $body, array(
			'Content-Type: text/html; charset=UTF-8',
			'From: Kinlen Booking Manager <bookings@bestthaifood.info>',
			'Bcc: Kinlen Booking Manager <kinlen.bkk@gmail.com>'
		));
	}
}
//
// $booking['name']='test test test';
// $booking['email']='jsetojseto@gmail.com';
// $booking['adults']='2';
// $booking['children']='0';
// $booking['comment']='Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
// $booking['date']='2019-01-20';
// $booking['time']='19:00';
//
// if ( Mails::bookingConfirmation($booking) ) {
// 	echo 'funciona ';
// }
// else {
// 	echo 'fALLA';
// }
