<?php
session_start();
if(!isset($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

include('../db.php');

$bookingId = $_GET['id'] ?? '';
$bookingId = mysqli_real_escape_string($con, $bookingId);

$query = "SELECT id, assigned_room_id, assigned_room_number, FName, LName, TRoom, cin, cout, stat 
          FROM roombook 
          WHERE id = '$bookingId'";

$result = mysqli_query($con, $query);
$booking = mysqli_fetch_assoc($result);

if($booking) {
    echo json_encode($booking);
} else {
    echo json_encode(['error' => 'Booking not found']);
}
?>
