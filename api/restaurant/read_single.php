<?php
// Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/reservation.php';

$database = new Database();
$db = $database->connect();

// Instantiate reservation object
$reservation = new reservation($db);

// Get reservation_id
$reservation->reservation_id = isset($_GET['reservation_id']) ? $_GET['reservation_id'] : die();

// Get reservation
$reservation->read_single();

// Create reservation array
$reservation_arr = array(
    'Guest_Number' => $reservation->Guest_Number,
    'fname' => $reservation->fname,
    'lname' => $reservation->lname,
    'date' => $reservation->date,
    'time' => $reservation->time,
    'email' => $reservation->email,
    'table_type' => $reservation->table_type,
    'note' => $reservation->note,
    'placement' => $reservation->placement,
    'reservation_id' => $reservation->reservation_id,
);

// Output as JSON
print_r(json_encode($reservation_arr));
