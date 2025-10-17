<?php
/**
 * Email Configuration for RansHotel
 * Configure your email service here
 */

// Email Service Configuration
define('EMAIL_SERVICE', 'smtp'); // Options: 'web_service', 'smtp', 'mail_function'

// Manager Contact Details
define('MANAGER_EMAIL', 'eyramdela14@gmail.com');
define('MANAGER_PHONE', '0540202096');

// Hotel Information
define('HOTEL_NAME', 'RANS HOTEL');
define('HOTEL_PHONE', '+233 (0)302 936 062');
define('HOTEL_EMAIL', 'swiftforge25@gmail.com');
define('HOTEL_ADDRESS', 'Tsito, Volta Region, Ghana');

// Email Service URLs (replace with your actual service URLs)
define('WEB_EMAIL_SERVICE_URL', 'https://your-email-service.com/send');
define('WEB_EMAIL_API_KEY', 'your-api-key-here');

// SMTP Configuration (if using SMTP)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465); // SSL port for Gmail
define('SMTP_USERNAME', 'swiftforge25@gmail.com');
define('SMTP_PASSWORD', 'vdxfxvwsyfjgsvav');
define('SMTP_FROM_EMAIL', 'swiftforge25@gmail.com');
define('SMTP_FROM_NAME', 'RANS HOTEL');

// Email Templates
define('EMAIL_TEMPLATE_BOOKING_CONFIRMATION', 'booking_confirmation');
define('EMAIL_TEMPLATE_MANAGER_NOTIFICATION', 'manager_notification');
define('EMAIL_TEMPLATE_CANCELLATION', 'cancellation');
define('EMAIL_TEMPLATE_REMINDER', 'reminder');

// Logging
define('EMAIL_LOG_ENABLED', true);
define('EMAIL_LOG_FILE', __DIR__ . '/../logs/email_log.txt');

// Debug Mode
define('EMAIL_DEBUG_MODE', false);

/**
 * Get email service configuration
 */
function getEmailConfig() {
    return [
        'service' => EMAIL_SERVICE,
        'manager_email' => MANAGER_EMAIL,
        'manager_phone' => MANAGER_PHONE,
        'hotel_name' => HOTEL_NAME,
        'hotel_phone' => HOTEL_PHONE,
        'hotel_email' => HOTEL_EMAIL,
        'hotel_address' => HOTEL_ADDRESS,
        'web_service_url' => WEB_EMAIL_SERVICE_URL,
        'web_service_api_key' => WEB_EMAIL_API_KEY,
        'smtp_host' => SMTP_HOST,
        'smtp_port' => SMTP_PORT,
        'smtp_username' => SMTP_USERNAME,
        'smtp_password' => SMTP_PASSWORD,
        'smtp_from_email' => SMTP_FROM_EMAIL,
        'smtp_from_name' => SMTP_FROM_NAME,
        'log_enabled' => EMAIL_LOG_ENABLED,
        'log_file' => EMAIL_LOG_FILE,
        'debug_mode' => EMAIL_DEBUG_MODE
    ];
}

/**
 * Log email activity
 */
function logEmailActivity($message, $level = 'INFO') {
    if (!EMAIL_LOG_ENABLED) {
        return;
    }
    
    $logMessage = date('Y-m-d H:i:s') . " [$level] $message" . PHP_EOL;
    file_put_contents(EMAIL_LOG_FILE, $logMessage, FILE_APPEND | LOCK_EX);
}

/**
 * Get email template
 */
function getEmailTemplate($templateName, $data = []) {
    $templates = [
        'booking_confirmation' => [
            'subject' => 'Booking Confirmation - ' . HOTEL_NAME,
            'greeting' => 'Dear {customer_name},',
            'message' => 'Thank you for choosing ' . HOTEL_NAME . '! Your booking has been confirmed.',
            'footer' => 'We look forward to welcoming you to ' . HOTEL_NAME . '!'
        ],
        'manager_notification' => [
            'subject' => 'New Booking Alert - ' . HOTEL_NAME,
            'greeting' => 'New booking received:',
            'message' => 'A new booking has been made and requires your attention.',
            'footer' => 'Please log into the admin panel to manage this booking.'
        ],
        'cancellation' => [
            'subject' => 'Booking Cancellation - ' . HOTEL_NAME,
            'greeting' => 'Dear {customer_name},',
            'message' => 'Your booking has been cancelled as requested.',
            'footer' => 'We hope to serve you again in the future.'
        ],
        'reminder' => [
            'subject' => 'Check-in Reminder - ' . HOTEL_NAME,
            'greeting' => 'Dear {customer_name},',
            'message' => 'This is a reminder about your upcoming check-in.',
            'footer' => 'We look forward to welcoming you!'
        ]
    ];
    
    return $templates[$templateName] ?? $templates['booking_confirmation'];
}
?>
