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
$reservation->reservation_id = $data->reservation_id;

// Update reservation
if ($reservation->delete()) {
    echo json_encode(
        array('message' => 'Reservation deleted', 'reservation_id' => $reservation->reservation_id)
    );
} else {
    echo json_encode(
        array('message' => 'Reservation not deleted')
    );
}
