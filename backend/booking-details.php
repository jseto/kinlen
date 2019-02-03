<?php

$db = new Database();

$search = $_GET;
if ( !isset( $search['id'] ) ) {
	$search['id']='1';
	unset( $search['post'] );
	unset( $search['action'] );
}

$booking = $db->getBooking( $search )[0];
$day = new DateTime( $booking->date );
$time = new DateTime( $booking->time );
?>

<table align="center">
  <tr>
    <td><strong>Your name<strong></td>
    <td><?php echo $booking->name ?></td>
  </tr>
  <tr>
    <td><strong>Your email<strong></td>
    <td><?php echo $booking->email ?></td>
  </tr>
  <tr>
    <td><strong>Adult guests<strong></td>
    <td><?php echo $booking->adults ?></td>
  </tr>
  <tr>
    <td><strong>Children guests<strong></td>
    <td><?php echo $booking->children ?></td>
  </tr>
  <tr>
    <td><strong>Your comments<strong></td>
    <td><?php echo $booking->comment ?></td>
  </tr>
  <tr>
    <td><strong>Booking date<strong></td>
    <td><?php echo $day->format('D, d M Y') ?></td>
  </tr>
  <tr>
    <td><strong>Booking time<strong></td>
    <td><?php echo $time->format('H:i') ?></td>
  </tr>
</table>
