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

// Get Guest_Number
$reservation->Guest_Number = isset($_GET['Guest_Number']) ? $_GET['Guest_Number'] : die();

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
);

// Output as JSON
print_r(json_encode($reservation_arr));