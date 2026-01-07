<?php
// Allow public access for reservation page (no session check needed)
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

register_shutdown_function(function () {
	$err = error_get_last();
	if ($err && ($err['type'] === E_ERROR || $err['type'] === E_PARSE || $err['type'] === E_CORE_ERROR)) {
		if (!headers_sent()) {
			http_response_code(500);
			echo json_encode(['success' => false, 'message' => 'Server error: ' . $err['message'], 'file' => $err['file'], 'line' => $err['line']]);
		}
	}
});

try {
	include('../db.php');
	
	if (!isset($con) || !$con) {
		throw new Exception('Database connection failed');
	}

	$roomType = $_GET['type'] ?? '';
	
	if (empty($roomType)) {
		echo json_encode(['success' => false, 'message' => 'Room type is required']);
		exit;
	}
	
	$roomType = mysqli_real_escape_string($con, $roomType);
	
	// Get total count of available rooms for this type
	// Available = Free AND Available status AND not assigned to active bookings
	$query = "SELECT COUNT(*) as available_count 
	          FROM room r
	          WHERE r.type = '$roomType' 
	          AND r.place = 'Free' 
	          AND r.status = 'Available'
	          AND (r.cusid IS NULL OR r.cusid NOT IN (
	              SELECT id FROM roombook 
	              WHERE stat NOT IN ('Cancelled', 'Checked Out') 
	              AND cout >= CURDATE()
	          ))";
	
	$result = mysqli_query($con, $query);
	
	if (!$result) {
		throw new Exception('Query failed: ' . mysqli_error($con));
	}
	
	$row = mysqli_fetch_assoc($result);
	$availableCount = (int)($row['available_count'] ?? 0);
	
	// Also get total count for this room type
	$totalQuery = "SELECT COUNT(*) as total_count FROM room WHERE type = '$roomType'";
	$totalResult = mysqli_query($con, $totalQuery);
	
	if (!$totalResult) {
		throw new Exception('Total count query failed: ' . mysqli_error($con));
	}
	
	$totalRow = mysqli_fetch_assoc($totalResult);
	$totalCount = (int)($totalRow['total_count'] ?? 0);
	
	echo json_encode([
		'success' => true,
		'available' => $availableCount,
		'total' => $totalCount,
		'room_type' => $roomType
	]);
	
} catch (Exception $e) {
	http_response_code(500);
	echo json_encode([
		'success' => false,
		'message' => $e->getMessage(),
		'error' => 'Failed to get room availability'
	]);
}
?>

