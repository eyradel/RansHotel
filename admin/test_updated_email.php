<?php
/**
 * Test script for the updated email system
 * Tests the PHPMailer email system with proper hotel branding
 */

require_once __DIR__ . '/includes/phpmailer_email_system.php';

// Test data
$testCustomerEmail = 'test@example.com';
$testCustomerName = 'John Doe';
$testRoomType = 'Deluxe Suite';
$testCheckIn = '2024-02-15';
$testCheckOut = '2024-02-18';
$testBookingId = 'RANS-2024-001';
$testPhone = '+233 24 123 4567';
$testMealPlan = 'Full Board';
$testTotalAmount = '450.00';

echo "<h2>Testing Updated Email System</h2>\n";
echo "<p>Testing PHPMailer email system with RANS HOTEL branding...</p>\n";

try {
    $emailSystem = new PHPMailerEmailSystem();
    
    // Test booking confirmation email
    echo "<h3>1. Testing Booking Confirmation Email</h3>\n";
    $result = $emailSystem->sendBookingConfirmation(
        $testCustomerEmail,
        $testCustomerName,
        $testRoomType,
        $testCheckIn,
        $testCheckOut,
        $testBookingId,
        $testPhone,
        $testMealPlan,
        $testTotalAmount
    );
    
    if ($result['success']) {
        echo "<p style='color: green;'>✅ Booking confirmation email sent successfully!</p>\n";
        echo "<p><strong>To:</strong> " . htmlspecialchars($result['to']) . "</p>\n";
        echo "<p><strong>Subject:</strong> " . htmlspecialchars($result['subject']) . "</p>\n";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($result['message']) . "</p>\n";
    } else {
        echo "<p style='color: red;'>❌ Failed to send booking confirmation email</p>\n";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($result['error']) . "</p>\n";
    }
    
    // Test manager notification email
    echo "<h3>2. Testing Manager Notification Email</h3>\n";
    $result2 = $emailSystem->sendManagerNotification(
        $testCustomerName,
        $testCustomerEmail,
        $testPhone,
        $testRoomType,
        $testCheckIn,
        $testCheckOut,
        $testBookingId,
        $testMealPlan,
        'Ghanaian',
        'Ghana'
    );
    
    if ($result2['success']) {
        echo "<p style='color: green;'>✅ Manager notification email sent successfully!</p>\n";
        echo "<p><strong>To:</strong> " . htmlspecialchars($result2['to']) . "</p>\n";
        echo "<p><strong>Subject:</strong> " . htmlspecialchars($result2['subject']) . "</p>\n";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($result2['message']) . "</p>\n";
    } else {
        echo "<p style='color: red;'>❌ Failed to send manager notification email</p>\n";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($result2['error']) . "</p>\n";
    }
    
    echo "<h3>3. Email System Configuration</h3>\n";
    echo "<p><strong>SMTP Host:</strong> smtp.gmail.com</p>\n";
    echo "<p><strong>SMTP Port:</strong> 465 (SSL)</p>\n";
    echo "<p><strong>From Email:</strong> swiftforge25@gmail.com</p>\n";
    echo "<p><strong>From Name:</strong> RANS HOTEL</p>\n";
    echo "<p><strong>CharSet:</strong> UTF-8</p>\n";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error testing email system: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}

echo "<h3>4. Key Improvements Made</h3>\n";
echo "<ul>\n";
echo "<li>✅ Updated email branding from 'SwiftForge Voting' to 'RANS HOTEL'</li>\n";
echo "<li>✅ Added CharSet UTF-8 to PHPMailer configuration</li>\n";
echo "<li>✅ Improved HTML email template with table-based layout</li>\n";
echo "<li>✅ Added proper HTML escaping for security</li>\n";
echo "<li>✅ Updated contact information to match hotel details</li>\n";
echo "<li>✅ Professional email styling matching your working example</li>\n";
echo "</ul>\n";

echo "<p><strong>Note:</strong> This test uses the same SMTP configuration as your working voting system example.</p>\n";
?>

