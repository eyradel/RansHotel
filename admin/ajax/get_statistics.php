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
    // Initialize variables
    $c = 0; // Pending bookings
    $r = 0; // Confirmed bookings
    $f = 0; // Newsletter subscribers
    
    // Get booking statistics
    $sql = "select * from roombook";
    $re = mysqli_query($con, $sql);
    if($re) {
        while($row = mysqli_fetch_array($re)) {
            $new = $row['stat'];
            if($new == "Not Confirm") {
                $c = $c + 1;
            }
        }
    }
    
    // Get confirmed bookings count
    $rsql = "SELECT * FROM `roombook`";
    $rre = mysqli_query($con, $rsql);
    if($rre) {
        while($row = mysqli_fetch_array($rre)) {
            $br = $row['stat'];
            if($br == "Confirm") {
                $r = $r + 1;
            }
        }
    }
    
    // Get newsletter subscribers count
    $fsql = "SELECT * FROM `contact`";
    $fre = mysqli_query($con, $fsql);
    if($fre) {
        while($row = mysqli_fetch_array($fre)) {
            $f = $f + 1;
        }
    }
    
    $stats = [
        $c,                    // Pending Bookings
        $r,                    // Confirmed Bookings
        $f,                    // Newsletter Subscribers
        $c + $r               // Total Bookings
    ];
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'timestamp' => date('H:i:s')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching statistics: ' . $e->getMessage()
    ]);
}
?>
