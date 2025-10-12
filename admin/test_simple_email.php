<?php
/**
 * Simple Email Test for RANS HOTEL
 * Quick test to verify email notifications are working
 */

session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

require_once 'includes/phpmailer_email_system.php';

echo "<h2>📧 RANS HOTEL - Simple Email Test</h2>";

try {
    $emailSystem = new PHPMailerEmailSystem();
    
    // Test 1: Simple email
    echo "<h3>Test 1: Simple Email</h3>";
        $result1 = $emailSystem->sendEmail(
            'eyramdela14@gmail.com',
            'SwiftForge Voting - Simple Test',
            '<h2>Simple Test Email</h2><p>This is a simple test email from SwiftForge Voting.</p>',
            'Simple Test Email\n\nThis is a simple test email from SwiftForge Voting.'
        );
    
    if ($result1 && $result1['success']) {
        echo "✅ Simple email sent successfully!<br>";
    } else {
        echo "❌ Simple email failed<br>";
        if (isset($result1['error'])) {
            echo "Error: " . $result1['error'] . "<br>";
        }
    }
    
    // Test 2: Booking confirmation
    echo "<h3>Test 2: Booking Confirmation Email</h3>";
    $result2 = $emailSystem->sendBookingConfirmation(
        'eyramdela14@gmail.com',
        'Test Customer',
        'Deluxe Room',
        '2024-01-15',
        '2024-01-17',
        'TEST123',
        '+233 123 456 789',
        'Breakfast',
        1500
    );
    
    if ($result2 && $result2['success']) {
        echo "✅ Booking confirmation email sent successfully!<br>";
    } else {
        echo "❌ Booking confirmation email failed<br>";
        if (isset($result2['error'])) {
            echo "Error: " . $result2['error'] . "<br>";
        }
    }
    
    // Test 3: Manager notification
    echo "<h3>Test 3: Manager Notification Email</h3>";
    $result3 = $emailSystem->sendManagerNotification(
        'Test Customer',
        'test@example.com',
        '+233 123 456 789',
        'Superior Room',
        '2024-01-15',
        '2024-01-17',
        'TEST123',
        'Full Board',
        'Ghanaian',
        'Ghana',
        2500
    );
    
    if ($result3 && $result3['success']) {
        echo "✅ Manager notification email sent successfully!<br>";
    } else {
        echo "❌ Manager notification email failed<br>";
        if (isset($result3['error'])) {
            echo "Error: " . $result3['error'] . "<br>";
        }
    }
    
    echo "<br><h3>📧 Check Your Email Inbox!</h3>";
    echo "<p>If all tests passed, you should receive 3 test emails at: <strong>eyramdela14@gmail.com</strong></p>";
    echo "<p>Check your spam folder if you don't see them in your inbox.</p>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "<br><h3>🔧 Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Check Gmail app password: vdxfxvwsyfjgsvav</li>";
    echo "<li>Ensure 2FA is enabled on Gmail</li>";
    echo "<li>Check firewall settings</li>";
    echo "<li>Run the debug script: <a href='debug_email.php'>debug_email.php</a></li>";
    echo "</ul>";
}
?>
