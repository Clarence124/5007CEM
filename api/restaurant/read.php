<?php
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/reservation.php';

$database = new Database();
$db = $database->connect();

// Instantiate reservation object
$reservation = new reservation($db);

// Reservation query
$result = $reservation->read();

$num = count($result);

if ($num > 0) {
    // Reservation array
    $reservation_arr = array();
    $reservation_arr['data'] = array();

    foreach ($result as $row) {
        extract($row);

        // Skip empty data
        if (empty($fname) || empty($lname) || empty($date) || empty($time) || empty($email) || empty($table_type) || empty($note) || empty($placement) || empty($reservation_id)) {
            continue;
        }

        $booking_item = array(
            'fname' => $fname,
            'lname' => $lname,
            'date' => $date,
            'time' => $time,
            'Guest_Number' => $Guest_Number,
            'email' => $email,
            'table_type' => $table_type,
            'note' => $note,
            'placement' => $placement,
            'reservation_id' => $reservation_id,
        );

        array_push($reservation_arr['data'], $booking_item);
    }

    echo json_encode($reservation_arr);
} else {
    // No reservations
    echo json_encode(
        array('message' => 'No reservations found')
    );
}
?>
