<?php
session_start();
header('Content-Type: application/json');

// Robust error capture for fatals/notices to avoid empty responses
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
	echo json_encode(['success' => false, 'message' => 'Unauthorized']);
	exit;
}

include('../db.php');

$input = json_decode(file_get_contents('php://input'), true);
$bookingId = $input['bookingId'] ?? null;
$roomId = $input['roomId'] ?? null;

if(!$bookingId || !$roomId) {
	echo json_encode(['success' => false, 'message' => 'Missing booking ID or room ID']);
	exit;
}

$bookingId = mysqli_real_escape_string($con, $bookingId);
$roomId = mysqli_real_escape_string($con, $roomId);

// Start transaction
mysqli_begin_transaction($con);

try {
	// Get booking details
	$booking_query = "SELECT * FROM roombook WHERE id = '$bookingId'";
	$booking_result = mysqli_query($con, $booking_query);
	$booking = mysqli_fetch_assoc($booking_result);
	
	if(!$booking) {
		throw new Exception('Booking not found');
	}
	
	// Check if room is available
	$room_check = "SELECT * FROM room WHERE id = '$roomId' AND place = 'Free' AND status = 'Available'";
	$room_result = mysqli_query($con, $room_check);
	$room = mysqli_fetch_assoc($room_result);
	
	if(!$room) {
		throw new Exception('Room is not available');
	}
	
	// Get room number for assignment
	$room_number = $room['room_number'] ?? '';
	
	// Assign room to booking
	$assign_query = "UPDATE room SET place = 'NotFree', status = 'Occupied', cusid = '$bookingId' WHERE id = '$roomId'";
	if(!mysqli_query($con, $assign_query)) {
		throw new Exception('Failed to assign room: ' . mysqli_error($con));
	}
	
	// Update booking with room assignment (both ID and number)
	$update_booking = "UPDATE roombook SET assigned_room_id = '$roomId', assigned_room_number = '$room_number' WHERE id = '$bookingId'";
	if(!mysqli_query($con, $update_booking)) {
		throw new Exception('Failed to update booking: ' . mysqli_error($con));
	}
	
	// Commit transaction
	mysqli_commit($con);
	
	echo json_encode(['success' => true, 'message' => 'Room assigned successfully']);
	
} catch (Exception $e) {
	// Rollback transaction
	mysqli_rollback($con);
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
