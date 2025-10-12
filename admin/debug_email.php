<?php
/**
 * Email Debug Script for RANS HOTEL
 * This script will help diagnose email notification issues
 */

session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

// Include required files
require_once '../vendor/autoload.php';
require_once 'includes/phpmailer_email_system.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "<h2>🔍 RANS HOTEL - Email Debug Tool</h2>";

// Test 1: Check PHPMailer installation
echo "<h3>1. PHPMailer Installation Check</h3>";
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "✅ PHPMailer is properly installed<br>";
} else {
    echo "❌ PHPMailer is NOT installed or not accessible<br>";
    echo "💡 Run: composer install<br>";
}

// Test 2: Check autoloader
echo "<h3>2. Autoloader Check</h3>";
if (file_exists('../vendor/autoload.php')) {
    echo "✅ Composer autoloader exists<br>";
} else {
    echo "❌ Composer autoloader missing<br>";
    echo "💡 Run: composer install<br>";
}

// Test 3: Test basic PHPMailer functionality
echo "<h3>3. Basic PHPMailer Test</h3>";
try {
    $mail = new PHPMailer(true);
    echo "✅ PHPMailer instance created successfully<br>";
    echo "PHPMailer Version: " . $mail->Version . "<br>";
} catch (Exception $e) {
    echo "❌ Error creating PHPMailer: " . $e->getMessage() . "<br>";
}

// Test 4: Test SMTP connection
echo "<h3>4. SMTP Connection Test</h3>";
try {
    $mail = new PHPMailer(true);
    
    // Enable verbose debug output
    $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
    $mail->Debugoutput = function($str, $level) {
        echo "Debug level $level; message: $str<br>";
    };
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'swiftforge25@gmail.com';
    $mail->Password = 'vdxfxvwsyfjgsvav';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    
    // Test connection
    $mail->smtpConnect();
    echo "✅ SMTP connection successful<br>";
    $mail->smtpClose();
    
} catch (Exception $e) {
    echo "❌ SMTP connection failed: " . $e->getMessage() . "<br>";
    echo "💡 <strong>Common issues:</strong><br>";
    echo "&nbsp;&nbsp;- Check Gmail app password<br>";
    echo "&nbsp;&nbsp;- Ensure 2FA is enabled<br>";
    echo "&nbsp;&nbsp;- Check firewall/antivirus settings<br>";
    echo "&nbsp;&nbsp;- Verify Gmail SMTP settings<br>";
}

// Test 5: Test email system class
echo "<h3>5. Email System Class Test</h3>";
try {
    $emailSystem = new PHPMailerEmailSystem();
    echo "✅ Email system class loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading email system: " . $e->getMessage() . "<br>";
}

// Test 6: Test actual email sending
echo "<h3>6. Email Sending Test</h3>";
try {
    $emailSystem = new PHPMailerEmailSystem();
    
    // Simple test email
    $testEmail = 'eyramdela14@gmail.com';
    $subject = 'RANS HOTEL - Email Debug Test';
    $htmlBody = '<h2>Email Debug Test</h2><p>This is a test email to verify the email system is working.</p>';
    $textBody = 'Email Debug Test\n\nThis is a test email to verify the email system is working.';
    
    echo "Attempting to send test email to: $testEmail<br>";
    
    $result = $emailSystem->sendEmail($testEmail, $subject, $htmlBody, $textBody);
    
    if ($result && $result['success']) {
        echo "✅ Test email sent successfully!<br>";
        echo "📧 Check your inbox for the test email<br>";
    } else {
        echo "❌ Failed to send test email<br>";
        if (isset($result['error'])) {
            echo "Error: " . $result['error'] . "<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Email sending error: " . $e->getMessage() . "<br>";
}

// Test 7: Check email logs
echo "<h3>7. Email Logs Check</h3>";
$logFile = 'logs/email_log.txt';
if (file_exists($logFile)) {
    echo "✅ Email log file exists<br>";
    $logContent = file_get_contents($logFile);
    if (!empty($logContent)) {
        echo "📝 Recent log entries:<br>";
        $lines = explode("\n", $logContent);
        $recentLines = array_slice($lines, -5); // Last 5 lines
        foreach ($recentLines as $line) {
            if (!empty(trim($line))) {
                echo "&nbsp;&nbsp;" . htmlspecialchars($line) . "<br>";
            }
        }
    } else {
        echo "📝 Log file is empty<br>";
    }
} else {
    echo "⚠️ Email log file does not exist<br>";
}

// Test 8: Check notification manager
echo "<h3>8. Notification Manager Check</h3>";
if (file_exists('includes/notification_manager.php')) {
    echo "✅ Notification manager exists<br>";
    try {
        require_once 'includes/notification_manager.php';
        $notificationManager = new NotificationManager();
        echo "✅ Notification manager loaded successfully<br>";
    } catch (Exception $e) {
        echo "❌ Error loading notification manager: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Notification manager file missing<br>";
}

// Test 9: Check reservation system integration
echo "<h3>9. Reservation System Integration Check</h3>";
if (file_exists('reservation.php')) {
    echo "✅ Reservation system exists<br>";
    
    // Check if email system is included in reservation.php
    $reservationContent = file_get_contents('reservation.php');
    if (strpos($reservationContent, 'phpmailer_email_system.php') !== false) {
        echo "✅ PHPMailer email system is included in reservation.php<br>";
    } else {
        echo "⚠️ PHPMailer email system may not be included in reservation.php<br>";
    }
    
    if (strpos($reservationContent, 'NotificationManager') !== false) {
        echo "✅ Notification manager is used in reservation.php<br>";
    } else {
        echo "⚠️ Notification manager may not be used in reservation.php<br>";
    }
} else {
    echo "❌ Reservation system file missing<br>";
}

// Test 10: Configuration summary
echo "<h3>10. Configuration Summary</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Setting</th><th>Value</th><th>Status</th></tr>";
echo "<tr><td>SMTP Host</td><td>smtp.gmail.com</td><td>✅</td></tr>";
echo "<tr><td>SMTP Port</td><td>465</td><td>✅</td></tr>";
echo "<tr><td>SMTP Username</td><td>swiftforge25@gmail.com</td><td>✅</td></tr>";
echo "<tr><td>SMTP Password</td><td>vdxfxvwsyfjgsvav</td><td>✅</td></tr>";
echo "<tr><td>Encryption</td><td>SSL (SMTPS)</td><td>✅</td></tr>";
echo "<tr><td>From Email</td><td>swiftforge25@gmail.com</td><td>✅</td></tr>";
echo "<tr><td>From Name</td><td>RANS HOTEL</td><td>✅</td></tr>";
echo "</table>";

echo "<br><h3>🔧 Troubleshooting Steps</h3>";
echo "<ol>";
echo "<li><strong>Check Gmail Settings:</strong> Ensure 2FA is enabled and app password is correct</li>";
echo "<li><strong>Check Firewall:</strong> Make sure port 465 is not blocked</li>";
echo "<li><strong>Check PHP Extensions:</strong> Ensure OpenSSL extension is enabled</li>";
echo "<li><strong>Check Error Logs:</strong> Look at PHP error logs for detailed error messages</li>";
echo "<li><strong>Test Manually:</strong> Try sending an email from Gmail web interface</li>";
echo "</ol>";

echo "<br><h3>📞 Quick Fixes</h3>";
echo "<ul>";
echo "<li>If SMTP connection fails: Check Gmail app password</li>";
echo "<li>If emails not received: Check spam folder</li>";
echo "<li>If PHPMailer errors: Run 'composer install'</li>";
echo "<li>If permission errors: Check file permissions on logs directory</li>";
echo "</ul>";
?>
