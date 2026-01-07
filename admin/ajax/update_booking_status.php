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
$action = $input['action'] ?? null; // 'confirm', 'cancel', 'processing', 'pending', 'declined'
$status = $input['status'] ?? null; // Direct status value (optional)
$declineReason = $input['declineReason'] ?? null; // Reason for decline (optional)

if(!$bookingId || (!$action && !$status)) {
	echo json_encode(['success' => false, 'message' => 'Missing booking ID or action/status']);
	exit;
}

$bookingId = mysqli_real_escape_string($con, $bookingId);
$action = $action ? mysqli_real_escape_string($con, $action) : null;
$status = $status ? mysqli_real_escape_string($con, $status) : null;
$declineReason = $declineReason ? mysqli_real_escape_string($con, $declineReason) : null;

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
	
	$current_status = $booking['stat'];
	
	// Determine new status from action or direct status
	if($status) {
		$new_status = $status;
	} elseif($action === 'confirm') {
		$new_status = 'Confirmed';
	} elseif($action === 'cancel') {
		$new_status = 'Cancelled';
	} elseif($action === 'processing') {
		$new_status = 'Processing';
	} elseif($action === 'pending') {
		$new_status = 'Pending';
	} elseif($action === 'decline' || $action === 'declined') {
		$new_status = 'Declined';
	} else {
		throw new Exception('Invalid action or status');
	}
	
	// If booking is cancelled or declined, free the assigned room
	if(($new_status === 'Cancelled' || $new_status === 'Declined') && !empty($booking['assigned_room_id'])) {
		$room_id = mysqli_real_escape_string($con, $booking['assigned_room_id']);
		$free_room = "UPDATE room SET place = 'Free', status = 'Available', cusid = NULL WHERE id = '$room_id'";
		if(!mysqli_query($con, $free_room)) {
			throw new Exception('Failed to free room: ' . mysqli_error($con));
		}
	}
	
	// Update booking status
	$update_query = "UPDATE roombook SET stat = '$new_status' WHERE id = '$bookingId'";
	if(!mysqli_query($con, $update_query)) {
		throw new Exception('Failed to update booking: ' . mysqli_error($con));
	}
	
	// Send notifications based on new status
	if(!empty($booking['Phone']) && !empty($booking['Email'])) {
		require_once('../includes/notification_manager.php');
		try {
			$notificationManager = new NotificationManager();
			$customerName = trim(($booking['Title'] ?? '') . ' ' . ($booking['FName'] ?? '') . ' ' . ($booking['LName'] ?? ''));
			if(empty($customerName)) {
				$customerName = 'Guest';
			}
			
			// Use the new status update notification method
			$notificationManager->sendStatusUpdateNotifications([
				'email' => $booking['Email'],
				'phone' => $booking['Phone'] ?? '',
				'customerName' => $customerName,
				'roomType' => $booking['TRoom'] ?? '',
				'checkIn' => $booking['cin'] ?? '',
				'checkOut' => $booking['cout'] ?? '',
				'bookingId' => $bookingId,
				'mealPlan' => $booking['Meal'] ?? 'Room only',
				'totalAmount' => $booking['final_amount'] ?? null,
				'declineReason' => $declineReason
			], $new_status);
		} catch (Throwable $e) {
			// Notification failure should not break status update
			error_log('Status update notification failed: ' . $e->getMessage());
		}
	}
	
	// Commit transaction
	mysqli_commit($con);
	
	echo json_encode([
		'success' => true, 
		'message' => 'Booking ' . ($action === 'confirm' ? 'confirmed' : 'cancelled') . ' successfully',
		'new_status' => $new_status
	]);
	
} catch (Exception $e) {
	// Rollback transaction
	mysqli_rollback($con);
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
