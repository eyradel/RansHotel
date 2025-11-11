<?php
session_start();
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
register_shutdown_function(function () {
	$err = error_get_last();
	if ($err && !headers_sent()) {
		http_response_code(500);
		echo json_encode(['success' => false, 'message' => 'Server error: ' . $err['message']]);
	}
});

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
	http_response_code(404);
	echo json_encode(['error' => 'Booking not found']);
}
?>
