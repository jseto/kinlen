<?php

$db = new Database();

$booking = $db->getBooking( $_GET )[0];
$day = new DateTime( $booking->date );
$time = new DateTime( $booking->time );

echo '<table>';
echo '  <tr>';
echo '    <td>Your name</td>';
echo '    <td>'.$booking->name.'</td>';
echo '  </tr>';
echo '  <tr>';
echo '    <td>Your email</td>';
echo '    <td>'.$booking->email.'</td>';
echo '  </tr>';
echo '  <tr>';
echo '    <td>Adult guests</td>';
echo '    <td>'.$booking->adults.'</td>';
echo '  </tr>';
echo '  <tr>';
echo '    <td>Children guests</td>';
echo '    <td>'.$booking->children.'</td>';
echo '  </tr>';
echo '  <tr>';
echo '    <td>Booking date</td>';
echo '    <td>'.$day->format('D, d M Y').'</td>';
echo '  </tr>';
echo '  <tr>';
echo '    <td>Booking time</td>';
echo '    <td>'.$time->format('H:i').'</td>';
echo '  </tr>';
echo '</table>';
