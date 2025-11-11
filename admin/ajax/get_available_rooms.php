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

$roomType = $_GET['type'] ?? '';
$roomType = mysqli_real_escape_string($con, $roomType);

$query = "SELECT id, room_number, bedding, type FROM room 
          WHERE type = '$roomType' 
          AND place = 'Free' 
          AND status = 'Available' 
          ORDER BY room_number";

$result = mysqli_query($con, $query);
$rooms = [];

while($row = mysqli_fetch_assoc($result)) {
	$rooms[] = $row;
}

echo json_encode(['rooms' => $rooms]);
?>
