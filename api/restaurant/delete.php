<?php
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/reservation.php';

$database = new Database();
$db = $database->connect();

// Instantiate reservation object
$reservation = new reservation($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set which Guest Number to delete
$reservation->Guest_Number = $data->Guest_Number;

// Update reservation
if ($reservation->delete()) {
    echo json_encode(
        array('message' => 'Reservation deleted', 'Guest_Number' => $reservation->Guest_Number)
    );
} else {
    echo json_encode(
        array('message' => 'Reservation not deleted')
    );
}