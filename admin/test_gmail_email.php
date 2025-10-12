<?php
/**
 * Test Gmail Email System for RANS HOTEL
 * This script tests the email system with your Gmail credentials
 */

session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

require_once 'includes/phpmailer_email_system.php';

echo "<h2>RANS HOTEL - Gmail Email System Test</h2>";

// Test 1: Create email system instance
echo "<h3>1. Testing Email System Instance...</h3>";
try {
    $emailSystem = new PHPMailerEmailSystem();
    echo "✅ Email system instance created successfully<br>";
} catch (Exception $e) {
    echo "❌ Error creating email system: " . $e->getMessage() . "<br>";
}

// Test 2: Test SMTP connection
echo "<h3>2. Testing SMTP Connection...</h3>";
try {
    $emailSystem = new PHPMailerEmailSystem();
    
    // Test sending a simple email
    $testEmail = 'eyramdela14@gmail.com'; // Manager email for testing
    $subject = 'RANS HOTEL - Email System Test';
    $htmlBody = '<h2>Email System Test</h2><p>This is a test email from the RANS HOTEL booking system.</p><p>If you receive this email, the system is working correctly!</p>';
    $textBody = 'Email System Test\n\nThis is a test email from the RANS HOTEL booking system.\n\nIf you receive this email, the system is working correctly!';
    
    $result = $emailSystem->sendEmail($testEmail, $subject, $htmlBody, $textBody);
    
    if ($result) {
        echo "✅ Test email sent successfully to $testEmail<br>";
        echo "📧 Check your email inbox for the test message<br>";
    } else {
        echo "❌ Failed to send test email<br>";
    }
    
} catch (Exception $e) {
    echo "❌ SMTP connection error: " . $e->getMessage() . "<br>";
    echo "💡 <strong>Troubleshooting tips:</strong><br>";
    echo "&nbsp;&nbsp;- Check if Gmail app password is correct<br>";
    echo "&nbsp;&nbsp;- Ensure 2-factor authentication is enabled on Gmail<br>";
    echo "&nbsp;&nbsp;- Verify the app password is generated correctly<br>";
}

// Test 3: Test booking confirmation email
echo "<h3>3. Testing Booking Confirmation Email...</h3>";
try {
    $emailSystem = new PHPMailerEmailSystem();
    
    $testData = [
        'customerEmail' => 'eyramdela14@gmail.com',
        'customerName' => 'Test Customer',
        'roomType' => 'Deluxe Room',
        'checkIn' => '2024-01-15',
        'checkOut' => '2024-01-17',
        'bookingId' => 'TEST123',
        'phone' => '+233 123 456 789',
        'mealPlan' => 'Breakfast',
        'totalAmount' => 1500
    ];
    
    $result = $emailSystem->sendBookingConfirmation(
        $testData['customerEmail'],
        $testData['customerName'],
        $testData['roomType'],
        $testData['checkIn'],
        $testData['checkOut'],
        $testData['bookingId'],
        $testData['phone'],
        $testData['mealPlan'],
        $testData['totalAmount']
    );
    
    if ($result) {
        echo "✅ Booking confirmation email sent successfully<br>";
        echo "📧 Check your email for the booking confirmation<br>";
    } else {
        echo "❌ Failed to send booking confirmation email<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Booking confirmation error: " . $e->getMessage() . "<br>";
}

// Test 4: Test manager notification
echo "<h3>4. Testing Manager Notification...</h3>";
try {
    $emailSystem = new PHPMailerEmailSystem();
    
    $testData = [
        'customerName' => 'Test Customer',
        'customerEmail' => 'test@example.com',
        'customerPhone' => '+233 123 456 789',
        'roomType' => 'Superior Room',
        'checkIn' => '2024-01-15',
        'checkOut' => '2024-01-17',
        'bookingId' => 'TEST123',
        'mealPlan' => 'Full Board',
        'nationality' => 'Ghanaian',
        'country' => 'Ghana',
        'totalAmount' => 2500
    ];
    
    $result = $emailSystem->sendManagerNotification(
        $testData['customerName'],
        $testData['customerEmail'],
        $testData['customerPhone'],
        $testData['roomType'],
        $testData['checkIn'],
        $testData['checkOut'],
        $testData['bookingId'],
        $testData['mealPlan'],
        $testData['nationality'],
        $testData['country'],
        $testData['totalAmount']
    );
    
    if ($result) {
        echo "✅ Manager notification sent successfully<br>";
        echo "📧 Check manager email for the notification<br>";
    } else {
        echo "❌ Failed to send manager notification<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Manager notification error: " . $e->getMessage() . "<br>";
}

// Test 5: Configuration summary
echo "<h3>5. Email Configuration Summary...</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>SMTP Host</td><td>smtp.gmail.com</td></tr>";
echo "<tr><td>SMTP Port</td><td>465 (SSL)</td></tr>";
echo "<tr><td>SMTP Username</td><td>swiftforge25@gmail.com</td></tr>";
echo "<tr><td>From Email</td><td>swiftforge25@gmail.com</td></tr>";
echo "<tr><td>From Name</td><td>RANS HOTEL</td></tr>";
echo "<tr><td>Manager Email</td><td>eyramdela14@gmail.com</td></tr>";
echo "</table>";

echo "<br><h3>✅ Gmail Email System Test Complete!</h3>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>Check your email inbox for test messages</li>";
echo "<li>If emails are received, the system is working correctly</li>";
echo "<li>If no emails are received, check Gmail settings and app password</li>";
echo "<li>Test the booking system to ensure emails are sent automatically</li>";
echo "</ul>";

echo "<p><strong>Gmail Setup Requirements:</strong></p>";
echo "<ul>";
echo "<li>✅ 2-Factor Authentication enabled</li>";
echo "<li>✅ App Password generated (vdxfxvwsyfjgsvav)</li>";
echo "<li>✅ SMTP settings configured (smtp.gmail.com:465)</li>";
echo "<li>✅ SSL encryption enabled</li>";
echo "</ul>";
?>
