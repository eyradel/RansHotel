<?php
session_start();
if(!isset($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

include('../db.php');

header('Content-Type: application/json');

try {
    $tsql = "select * from roombook ORDER BY id DESC LIMIT 10";
    $tre = mysqli_query($con, $tsql);
    
    $bookings = [];
    if($tre) {
        while($trow = mysqli_fetch_array($tre)) {
            $bookings[] = [
                'id' => $trow['id'],
                'FName' => $trow['FName'],
                'LName' => $trow['LName'],
                'TRoom' => $trow['TRoom'],
                'cin' => $trow['cin'],
                'stat' => $trow['stat']
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'bookings' => $bookings,
        'timestamp' => date('H:i:s')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching bookings: ' . $e->getMessage()
    ]);
}
?>
