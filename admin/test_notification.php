<?php
session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
initAccessControl('notifications');
require_once 'includes/notification_manager.php';
require_once 'includes/sms_notification.php';
require_once 'includes/phpmailer_email_system.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Notifications - RansHotel</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <h2>Test Notification System</h2>
        
        <?php
        // Get customer data
        $sql = "SELECT DISTINCT Email as email, CONCAT(FName, ' ', LName) as name, Phone as phone FROM roombook WHERE Email IS NOT NULL AND Email != ''";
        $query = mysqli_query($con, $sql);
        
        $customers = [];
        while($row = mysqli_fetch_assoc($query)) {
            $customers[] = $row;
        }
        
        echo "<h3>Customer Data</h3>";
        echo "<p>Found " . count($customers) . " customers</p>";
        
        if(!empty($customers)) {
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Email</th><th>Name</th><th>Phone</th></tr>";
            foreach($customers as $customer) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($customer['email']) . "</td>";
                echo "<td>" . htmlspecialchars($customer['name']) . "</td>";
                echo "<td>" . htmlspecialchars($customer['phone'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Test SMS
            echo "<h3>Test SMS Notification</h3>";
            $smsNotification = new SMSNotification();
            $testMessage = "Test message from RansHotel - " . date('Y-m-d H:i:s');
            
            foreach($customers as $customer) {
                if(!empty($customer['phone'])) {
                    echo "<h4>Testing SMS to: " . htmlspecialchars($customer['phone']) . "</h4>";
                    $result = $smsNotification->sendSMS($customer['phone'], $testMessage);
                    
                    echo "<div class='alert " . ($result['success'] ? 'alert-success' : 'alert-danger') . "'>";
                    echo "<strong>SMS Result:</strong><br>";
                    echo "Success: " . ($result['success'] ? 'Yes' : 'No') . "<br>";
                    if(isset($result['error'])) {
                        echo "Error: " . htmlspecialchars($result['error']) . "<br>";
                    }
                    if(isset($result['response'])) {
                        echo "Response: " . htmlspecialchars($result['response']) . "<br>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='alert alert-warning'>No phone number for " . htmlspecialchars($customer['name']) . "</div>";
                }
            }
            
            // Test Email
            echo "<h3>Test Email Notification</h3>";
            $emailNotification = new PHPMailerEmailSystem();
            
            foreach($customers as $customer) {
                if(!empty($customer['email'])) {
                    echo "<h4>Testing Email to: " . htmlspecialchars($customer['email']) . "</h4>";
                    $result = $emailNotification->sendEmail(
                        $customer['email'],
                        'Test Email from RansHotel',
                        '<h2>Test Email</h2><p>This is a test email from RansHotel notification system.</p><p>Time: ' . date('Y-m-d H:i:s') . '</p>',
                        'Test Email - This is a test email from RansHotel notification system. Time: ' . date('Y-m-d H:i:s')
                    );
                    
                    echo "<div class='alert " . ($result['success'] ? 'alert-success' : 'alert-danger') . "'>";
                    echo "<strong>Email Result:</strong><br>";
                    echo "Success: " . ($result['success'] ? 'Yes' : 'No') . "<br>";
                    if(isset($result['error'])) {
                        echo "Error: " . htmlspecialchars($result['error']) . "<br>";
                    }
                    if(isset($result['message'])) {
                        echo "Message: " . htmlspecialchars($result['message']) . "<br>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='alert alert-warning'>No email for " . htmlspecialchars($customer['name']) . "</div>";
                }
            }
            
            // Test NotificationManager
            echo "<h3>Test NotificationManager</h3>";
            $notificationManager = new NotificationManager();
            $testMessage = "Test bulk notification from RansHotel - " . date('Y-m-d H:i:s');
            
            echo "<h4>Testing Bulk Notification</h4>";
            $results = $notificationManager->sendBulkNotification($testMessage, $customers);
            
            echo "<h5>SMS Results:</h5>";
            if(isset($results['sms'])) {
                foreach($results['sms'] as $i => $result) {
                    echo "<div class='alert " . ($result['success'] ? 'alert-success' : 'alert-danger') . "'>";
                    echo "<strong>SMS " . ($i + 1) . ":</strong><br>";
                    echo "Success: " . ($result['success'] ? 'Yes' : 'No') . "<br>";
                    if(isset($result['error'])) {
                        echo "Error: " . htmlspecialchars($result['error']) . "<br>";
                    }
                    if(isset($result['response'])) {
                        echo "Response: " . htmlspecialchars($result['response']) . "<br>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<div class='alert alert-warning'>No SMS results found</div>";
            }
            
            echo "<h5>Email Results:</h5>";
            if(isset($results['email'])) {
                foreach($results['email'] as $i => $result) {
                    echo "<div class='alert " . ($result['success'] ? 'alert-success' : 'alert-danger') . "'>";
                    echo "<strong>Email " . ($i + 1) . ":</strong><br>";
                    echo "Success: " . ($result['success'] ? 'Yes' : 'No') . "<br>";
                    if(isset($result['error'])) {
                        echo "Error: " . htmlspecialchars($result['error']) . "<br>";
                    }
                    if(isset($result['message'])) {
                        echo "Message: " . htmlspecialchars($result['message']) . "<br>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<div class='alert alert-warning'>No email results found</div>";
            }
            
        } else {
            echo "<div class='alert alert-warning'>No customers found in database</div>";
        }
        ?>
        
        <hr>
        <p><a href="notifications.php" class="btn btn-primary">← Back to Notifications</a></p>
    </div>
</body>
</html>
