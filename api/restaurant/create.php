<?php
//Header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/reservation.php';

$database = new Database();
$db = $database->connect();

// Instantiate reservation object
$reservation = new reservation($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set reservation properties
$reservation->fname = $data->fname;
$reservation->lname = $data->lname;
$reservation->date = $data->date;
$reservation->time = $data->time;
$reservation->Guest_Number = $data->Guest_Number;
$reservation->email = $data->email;
$reservation->table_type = $data->table_type;
$reservation->note = $data->note;
$reservation->placement = $data->placement;
$reservation->reservation_id = $data->reservation_id;


// Create reservation
if ($reservation->create()) {
    echo json_encode(
        array('message' => 'Reservation created')
    );
} else {
    echo json_encode(
        array('message' => 'Reservation not created')
    );
}
