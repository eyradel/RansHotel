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
    // Check database connection
    $dbStatus = mysqli_ping($con);
    
    // Check if email system files exist
    $emailStatus = file_exists('../includes/email_config.php') && file_exists('../includes/phpmailer_email_system.php');
    
    // Check if SMS system files exist
    $smsStatus = file_exists('../includes/sms_notification.php');
    
    // Check if access control is working
    $accessControlStatus = file_exists('../includes/access_control.php');
    
    $status = [
        'database' => $dbStatus,
        'email' => $emailStatus,
        'sms' => $smsStatus,
        'access_control' => $accessControlStatus
    ];
    
    echo json_encode([
        'success' => true,
        'status' => $status,
        'timestamp' => date('H:i:s')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error checking system status: ' . $e->getMessage()
    ]);
}
?>
