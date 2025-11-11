<?php
/**
 * Notification Configuration for RansHotel
 * Centralized configuration for SMS and Email settings
 */

// SMS Configuration
define('SMS_API_KEY', 'qqYaIprq4RZ25q9JENdRqQbKZ');
define('SMS_SENDER_ID', 'RansHotel');
define('SMS_API_URL', 'https://apps.mnotify.net/smsapi');

// Email Configuration
define('EMAIL_SMTP_HOST', 'smtp.gmail.com');
define('EMAIL_SMTP_PORT', 465);
define('EMAIL_SMTP_USERNAME', 'eyramdela14@gmail.com');
define('EMAIL_SMTP_PASSWORD', 'vdxfxvwsyfjgsvav');
define('EMAIL_FROM_ADDRESS', 'eyramdela14@gmail.com');
define('EMAIL_FROM_NAME', 'RansHotel');

// Manager Contact Information
define('MANAGER_EMAIL', 'eyramdela14@gmail.com');
define('MANAGER_PHONE', '0540202096');

// Hotel Information
define('HOTEL_NAME', 'RANS HOTEL');
define('HOTEL_LOCATION', 'Tsito, Ghana');
define('HOTEL_PHONE', '+233 (0)302 936 062');
define('HOTEL_EMAIL', 'info@ranshotel.com');

// Notification Settings
define('SEND_SMS_NOTIFICATIONS', true);
define('SEND_EMAIL_NOTIFICATIONS', false);
define('SEND_MANAGER_NOTIFICATIONS', false);
define('AUTO_SEND_REMINDERS', false); // Set to true to automatically send reminders

// Time settings for reminders (in hours before check-in)
define('REMINDER_HOURS_BEFORE_CHECKIN', 24);

// Message templates
define('SMS_BOOKING_CONFIRMATION_TEMPLATE', 'Hi {customer_name}, your booking at RansHotel has been confirmed! Room: {room_type}, Check-in: {check_in}, Check-out: {check_out}. Booking ID: {booking_id}. Thank you for choosing RansHotel!');

define('SMS_MANAGER_NOTIFICATION_TEMPLATE', 'New booking at RansHotel! Customer: {customer_name}, Room: {room_type}, Check-in: {check_in}, Check-out: {check_out}. Contact: {phone}, Email: {email}');

define('SMS_CANCELLATION_TEMPLATE', 'Hi {customer_name}, your booking {booking_id} at RansHotel has been cancelled. If you have any questions, please contact us at {hotel_phone}');

define('SMS_REMINDER_TEMPLATE', 'Hi {customer_name}, this is a reminder that your check-in at RansHotel is tomorrow ({check_in}). Room: {room_type}. We look forward to welcoming you! Contact: {hotel_phone}');

// Email templates
define('EMAIL_BOOKING_CONFIRMATION_SUBJECT', 'Booking Confirmation - RansHotel');
define('EMAIL_MANAGER_NOTIFICATION_SUBJECT', 'New Booking Alert - RansHotel');
define('EMAIL_CANCELLATION_SUBJECT', 'Booking Cancellation - RansHotel');
define('EMAIL_REMINDER_SUBJECT', 'Check-in Reminder - RansHotel');

// Error handling
define('LOG_NOTIFICATION_ERRORS', true);
define('NOTIFICATION_LOG_FILE', 'admin/logs/notification_errors.log');

// Rate limiting (to prevent spam)
define('MAX_SMS_PER_HOUR', 100);
define('MAX_EMAILS_PER_HOUR', 200);

// Debug mode
define('NOTIFICATION_DEBUG_MODE', false); // Set to true for testing
define('TEST_EMAIL_ADDRESS', 'test@example.com'); // Use this for testing emails
define('TEST_PHONE_NUMBER', '0540202096'); // Use this for testing SMS

// Database table names
define('BOOKINGS_TABLE', 'roombook');
define('CUSTOMERS_TABLE', 'roombook'); // Same table for this system

// Timezone
date_default_timezone_set('Africa/Accra'); // Ghana timezone
?>
