<?php
/**
 * Test Notification System
 * This file tests SMS and Email notifications
 */

require_once 'includes/notification_manager.php';

// Test data
$testBookingData = [
    'booking_id' => 999,
    'customerName' => 'John Doe',
    'email' => 'test@example.com',
    'phone' => '0540202096', // Use manager's phone for testing
    'roomType' => 'Standard',
    'checkIn' => '2024-01-15',
    'checkOut' => '2024-01-17',
    'bookingId' => 999,
    'mealPlan' => 'Breakfast Only',
    'nationality' => 'Ghanaian',
    'country' => 'Ghana',
    'totalAmount' => 500
];

echo "<h2>Testing Notification System</h2>";

try {
    $notificationManager = new NotificationManager();
    
    echo "<h3>Sending Test Notifications...</h3>";
    
    $results = $notificationManager->sendBookingNotifications($testBookingData);
    
    echo "<h3>Results:</h3>";
    echo "<pre>";
    print_r($results);
    echo "</pre>";
    
    $status = $notificationManager->getNotificationStatus($results);
    
    echo "<h3>Status Summary:</h3>";
    echo "<pre>";
    print_r($status);
    echo "</pre>";
    
    echo "<h3>Detailed Results:</h3>";
    foreach ($status['details'] as $type => $result) {
        $statusIcon = $result['success'] ? '✅' : '❌';
        echo "<p>{$statusIcon} <strong>" . ucfirst(str_replace('_', ' ', $type)) . ":</strong> ";
        if ($result['success']) {
            echo "Success";
        } else {
            echo "Failed - " . ($result['error'] ?? 'Unknown error');
        }
        echo "</p>";
    }
    
} catch (Exception $e) {
    echo "<h3>Error:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><a href='reservation_classic.php'>Back to Reservation Form</a></p>";
?>
