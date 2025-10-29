<?php
session_start();
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
