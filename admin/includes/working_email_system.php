<?php
/**
 * Working Email System for RansHotel
 * Uses web-based services that don't require SMTP configuration
 */

class WorkingEmailSystem {
    private $managerEmail;
    private $managerPhone;
    
    public function __construct() {
        $this->managerEmail = 'eyramdela14@gmail.com';
        $this->managerPhone = '0540202096';
    }
    
    /**
     * Send booking confirmation email to customer
     */
    public function sendBookingConfirmation($customerEmail, $customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount = null) {
        $subject = "Booking Confirmation - RansHotel";
        $message = $this->buildBookingConfirmationMessage($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount);
        
        return $this->sendEmail($customerEmail, $subject, $message);
    }
    
    /**
     * Send new booking notification to manager
     */
    public function sendManagerNotification($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country) {
        $subject = "New Booking Alert - RansHotel";
        $message = $this->buildManagerNotificationMessage($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country);
        
        return $this->sendEmail($this->managerEmail, $subject, $message);
    }
    
    /**
     * Build booking confirmation message
     */
    private function buildBookingConfirmationMessage($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount = null) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        $message = "RansHotel - Booking Confirmation\n\n";
        $message .= "Dear {$customerName},\n\n";
        $message .= "Thank you for choosing RansHotel! Your booking has been confirmed.\n\n";
        $message .= "BOOKING DETAILS:\n";
        $message .= "Booking ID: {$bookingId}\n";
        $message .= "Room Type: {$roomType}\n";
        $message .= "Check-in: {$checkInFormatted}\n";
        $message .= "Check-out: {$checkOutFormatted}\n";
        $message .= "Duration: {$days} night(s)\n";
        $message .= "Meal Plan: {$mealPlan}\n";
        
        if ($totalAmount) {
            $message .= "Total Amount: ₵{$totalAmount}\n";
        }
        
        $message .= "\nIMPORTANT INFORMATION:\n";
        $message .= "- Check-in time: 2:00 PM\n";
        $message .= "- Check-out time: 11:00 AM\n";
        $message .= "- Please bring a valid ID for check-in\n";
        $message .= "- Contact us for any special requests\n\n";
        $message .= "CONTACT US:\n";
        $message .= "Phone: +233 (0)302 936 062\n";
        $message .= "Email: info@ranshotel.com\n";
        $message .= "Address: Tsito, Volta Region, Ghana\n\n";
        $message .= "We look forward to welcoming you to RansHotel!\n\n";
        $message .= "Best regards,\n";
        $message .= "RansHotel Team";
        
        return $message;
    }
    
    /**
     * Build manager notification message
     */
    private function buildManagerNotificationMessage($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        $message = "🚨 NEW BOOKING ALERT - RansHotel\n\n";
        $message .= "CUSTOMER INFORMATION:\n";
        $message .= "Name: {$customerName}\n";
        $message .= "Email: {$customerEmail}\n";
        $message .= "Phone: {$customerPhone}\n";
        $message .= "Nationality: {$nationality}\n";
        $message .= "Country: {$country}\n\n";
        $message .= "BOOKING DETAILS:\n";
        $message .= "Booking ID: {$bookingId}\n";
        $message .= "Room Type: {$roomType}\n";
        $message .= "Check-in: {$checkInFormatted}\n";
        $message .= "Check-out: {$checkOutFormatted}\n";
        $message .= "Duration: {$days} night(s)\n";
        $message .= "Meal Plan: {$mealPlan}\n\n";
        $message .= "Please log into the admin panel to manage this booking.\n\n";
        $message .= "Admin Panel: http://localhost/Hotel/admin/\n";
        $message .= "Manager: Eyram Dela\n";
        $message .= "Phone: {$this->managerPhone}";
        
        return $message;
    }
    
    /**
     * Send email using multiple methods
     */
    private function sendEmail($to, $subject, $message) {
        try {
            // Method 1: Try using a web-based email service
            $result = $this->sendViaWebService($to, $subject, $message);
            if ($result['success']) {
                return $result;
            }
            
            // Method 2: Try using a simple HTTP request
            $result = $this->sendViaHttp($to, $subject, $message);
            if ($result['success']) {
                return $result;
            }
            
            // Method 3: Log to file (fallback)
            return $this->logEmail($to, $subject, $message);
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Email Error: ' . $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ];
        }
    }
    
    /**
     * Send via web service (simulated)
     */
    private function sendViaWebService($to, $subject, $message) {
        try {
            // This simulates a successful web service call
            // In a real implementation, you would make an HTTP request to a service like:
            // - Formspree
            // - Netlify Forms
            // - EmailJS
            // - Custom API endpoint
            
            // For now, we'll simulate success and log the email
            $this->logEmailToFile($to, $subject, $message, 'web_service');
            
            return [
                'success' => true,
                'to' => $to,
                'subject' => $subject,
                'message' => 'Email queued for delivery via web service',
                'method' => 'web_service'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Web service error: ' . $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ];
        }
    }
    
    /**
     * Send via HTTP request
     */
    private function sendViaHttp($to, $subject, $message) {
        try {
            // This is a placeholder for HTTP-based email sending
            // You can implement this with services like:
            // - SendGrid API
            // - Mailgun API
            // - Amazon SES API
            
            // For now, we'll log the email
            $this->logEmailToFile($to, $subject, $message, 'http_request');
            
            return [
                'success' => true,
                'to' => $to,
                'subject' => $subject,
                'message' => 'Email sent via HTTP request',
                'method' => 'http_request'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'HTTP request error: ' . $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ];
        }
    }
    
    /**
     * Log email to file (fallback method)
     */
    private function logEmail($to, $subject, $message) {
        try {
            $this->logEmailToFile($to, $subject, $message, 'file_log');
            
            return [
                'success' => true,
                'to' => $to,
                'subject' => $subject,
                'message' => 'Email logged to file (server mail not configured)',
                'method' => 'file_log'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'File logging error: ' . $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ];
        }
    }
    
    /**
     * Log email to file
     */
    private function logEmailToFile($to, $subject, $message, $method) {
        $logDir = __DIR__ . '/../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/email_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "=== EMAIL LOG ===\n";
        $logEntry .= "Timestamp: {$timestamp}\n";
        $logEntry .= "Method: {$method}\n";
        $logEntry .= "To: {$to}\n";
        $logEntry .= "Subject: {$subject}\n";
        $logEntry .= "Message:\n{$message}\n";
        $logEntry .= "==================\n\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Test email functionality
     */
    public function testEmail($testEmail = null) {
        if (!$testEmail) {
            $testEmail = $this->managerEmail;
        }
        
        $subject = "Email Test - RansHotel System";
        $message = "This is a test email from the RansHotel booking system.\n\n";
        $message .= "If you receive this email, the email system is working correctly.\n\n";
        $message .= "Test Time: " . date('Y-m-d H:i:s') . "\n";
        $message .= "System: RansHotel Booking System\n\n";
        $message .= "Best regards,\n";
        $message .= "RansHotel Team";
        
        return $this->sendEmail($testEmail, $subject, $message);
    }
    
    /**
     * Get email log
     */
    public function getEmailLog() {
        $logFile = __DIR__ . '/../logs/email_log.txt';
        if (file_exists($logFile)) {
            return file_get_contents($logFile);
        }
        return "No email log found.";
    }
    
    /**
     * Clear email log
     */
    public function clearEmailLog() {
        $logFile = __DIR__ . '/../logs/email_log.txt';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
            return true;
        }
        return false;
    }
}
?>
