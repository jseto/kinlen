<?php

/**
 * @package           KinLen
 */

class Mails {

	static function bookingConfirmation( $booking, $guide, $restaurant ) {
		$html = file_get_contents( KINLEN_PATH . 'templates/booking-confirmation.html' );
		$text = file_get_contents( KINLEN_PATH . 'templates/booking-confirmation.md' );

		$message['text/plain'] = Mails::replaceVars( $text, $booking, $guide, $restaurant );
		$message['text/html'] = Mails::replaceVars( $html, $booking, $guide, $restaurant );

		$to = $booking->name.' <'.$booking->email.'>';
		$subject = 'KinLen booking confirmation';
		$header = array(
			'MIME-Version: 1.0',
			'Content-Type: text/html',
			'From: Kinlen Booking Manager <info@bestthaifood.info>',
			'Bcc: Kinlen <kinlen.bkk@gmail.com>, '.$guide->name.' <'.$guide->email.'>'
		);

		return wp_mail( $to, $subject, $message['text/html'], $header );
	}

	private static function multipart( $message ) {
		foreach ($message as $key => $value) {
			$bodyLines[] = '--'.Mails::boundary();
			$bodyLines[] = 'Content-Type: '.$key."; charset=UTF-8\n";
			$bodyLines[] = $value;

		}
		$bodyLines[] =  '--'.Mails::boundary()."--\n";
		return join( "\n", $bodyLines );
	}

	private static function boundary() {
		return 'frontier';
	}

	private static function replaceVars( $txt, $booking, $guide, $restaurant ) {
		$day = new DateTime( $booking->date );
		$time = new DateTime( $booking->time );

		return strtr( $txt, array(
			'$restaurantName' => $restaurant->name,
			'$name' => $booking->name,
			'$email' => $booking->email,
			'$adults' => $booking->adults,
			'$children' => $booking->children,
			'$comments' => $booking->comment,
			'$date' => $day->format('D, d M Y'),
			'$time' => $time->format('H:i'),
			'$bookingReference' => str_pad( $booking->id, 5, '0', STR_PAD_LEFT).$day->format('ymd').$time->format('His'),
			'$guideName' => $guide->name
		));
	}

	static function sendTestBookingConfirmation( $to = '' ) {
		if ( $to == '' ) {
			if ( isset( $_GET['to'] ) ) {
				$to = $_GET['to'];
			}
			else {
				$to='jsetojseto@gmail.com';
			}
		}
		echo 'Sending email to: '.$to."\n";

		$booking['id']='300';
		$booking['name']='test test test';
		$booking['email']=$to;
		$booking['adults']='2';
		$booking['children']='0';
		$booking['comment']='Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
		$booking['date']='2019-01-20';
		$booking['time']='19:00';
		$guide['name']='Somchai';
		$guide['email']='somchai@bestthaifood.info';
		$restaurant['name']='Beautiful restauant';

		if ( Mails::bookingConfirmation( (object)$booking, (object)$guide, (object)$restaurant ) ) {
			echo 'funciona ';
		}
		else {
			echo 'fALLA';
		}
	}
}
