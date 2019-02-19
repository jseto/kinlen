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

$guide = $db->queryGeneric( Database::tableNames()->guide, array(
	'id' => $booking->guide_id
))[0];

$restaurant = $db->queryGeneric( Database::tableNames()->restaurant, array(
	'id' => $booking->restaurant_id
))[0];

?>

<table align="center">
	<tr>
    <td><strong>Booking reference<strong></td>
    <td><?php echo str_pad( $booking->id, 5, '0', STR_PAD_LEFT).$day->format('ymd').$time->format('His') ?></td>
  </tr>
	<tr>
    <td><strong>Restaurant name<strong></td>
    <td><?php echo $restaurant->name ?></td>
  </tr>
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
    <td><strong>Booking date<strong></td>
    <td><?php echo $day->format('D, d M Y') ?></td>
  </tr>
  <tr>
    <td><strong>Booking time<strong></td>
    <td><?php echo $time->format('H:i') ?></td>
  </tr>
	<tr>
    <td><strong>Your comments<strong></td>
    <td><?php echo $booking->comment ?></td>
  </tr>
	<tr>
    <td><strong>Assigned guide<strong></td>
    <td><?php echo $guide->name ?></td>
  </tr>
</table>
