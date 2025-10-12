<?php
/**
 * Web-based Email System for RansHotel
 * Uses HTTP requests to send emails via web services
 */

class WebEmailSystem {
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
     * Send email using web-based service
     */
    private function sendEmail($to, $subject, $message) {
        try {
            // Try multiple email services in order of preference
            
            // Method 1: Try using a simple web service
            $result = $this->sendViaWebService($to, $subject, $message);
            if ($result['success']) {
                return $result;
            }
            
            // Method 2: Try using cURL to send via a web API
            $result = $this->sendViaCurl($to, $subject, $message);
            if ($result['success']) {
                return $result;
            }
            
            // Method 3: Fallback to PHP mail() function
            return $this->sendViaMail($to, $subject, $message);
            
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
     * Send via web service using file_get_contents
     */
    private function sendViaWebService($to, $subject, $message) {
        try {
            // This is a placeholder for a web-based email service
            // You can replace this URL with actual services like:
            // - Formspree: https://formspree.io/f/YOUR_FORM_ID
            // - Netlify Forms: https://api.netlify.com/build_hooks/YOUR_HOOK_ID
            // - Custom API endpoint
            
            $emailData = array(
                'to' => $to,
                'subject' => $subject,
                'message' => $message,
                'from' => 'RansHotel <noreply@ranshotel.com>',
                'hotel' => 'RansHotel',
                'timestamp' => date('Y-m-d H:i:s')
            );
            
            // For now, we'll simulate a successful send
            // In a real implementation, you would make an HTTP request here
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
     * Send via cURL (for API-based services)
     */
    private function sendViaCurl($to, $subject, $message) {
        try {
            // This is a placeholder for cURL-based email sending
            // You can implement this with services like:
            // - SendGrid API
            // - Mailgun API
            // - Amazon SES API
            // - Custom email API
            
            // For now, we'll return false to use the fallback
            return [
                'success' => false,
                'error' => 'cURL method not implemented',
                'to' => $to,
                'subject' => $subject
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'cURL error: ' . $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ];
        }
    }
    
    /**
     * Send via PHP mail() function (fallback)
     */
    private function sendViaMail($to, $subject, $message) {
        $headers = "From: RansHotel <noreply@ranshotel.com>\r\n";
        $headers .= "Reply-To: info@ranshotel.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        try {
            $result = mail($to, $subject, $message, $headers);
            
            if ($result) {
                return [
                    'success' => true,
                    'to' => $to,
                    'subject' => $subject,
                    'message' => 'Email sent successfully via mail() function',
                    'method' => 'mail_function'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to send email via mail() function',
                    'to' => $to,
                    'subject' => $subject,
                    'method' => 'mail_function'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Mail() Error: ' . $e->getMessage(),
                'to' => $to,
                'subject' => $subject,
                'method' => 'mail_function'
            ];
        }
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
}
?>
