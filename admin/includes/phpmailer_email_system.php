<?php
/**
 * PHPMailer Email System for SwiftForge Voting
 * Professional email system using PHPMailer
 */

// Load Composer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PHPMailerEmailSystem {
    private $managerEmail;
    private $managerPhone;
    private $smtpHost;
    private $smtpPort;
    private $smtpUsername;
    private $smtpPassword;
    private $fromEmail;
    private $fromName;
    
    public function __construct() {
        // Manager contact details
        $this->managerEmail = 'eyramdela14@gmail.com';
        $this->managerPhone = '0540202096';
        
        // SMTP Configuration (Gmail)
        $this->smtpHost = 'smtp.gmail.com';
        $this->smtpPort = 465; // SSL port
        $this->smtpUsername = 'swiftforge25@gmail.com';
        $this->smtpPassword = 'vdxfxvwsyfjgsvav'; // App password
        $this->fromEmail = 'swiftforge25@gmail.com';
        $this->fromName = 'SwiftForge Voting';
    }
    
    /**
     * Send booking confirmation email to customer
     */
    public function sendBookingConfirmation($customerEmail, $customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount = null) {
        $subject = "Booking Confirmation - SwiftForge Voting";
        $htmlBody = $this->buildBookingConfirmationHTML($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount);
        $textBody = $this->buildBookingConfirmationText($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount);
        
        return $this->sendEmail($customerEmail, $subject, $htmlBody, $textBody);
    }
    
    /**
     * Send new booking notification to manager
     */
    public function sendManagerNotification($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country) {
        $subject = "New Booking Alert - SwiftForge Voting";
        $htmlBody = $this->buildManagerNotificationHTML($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country);
        $textBody = $this->buildManagerNotificationText($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country);
        
        return $this->sendEmail($this->managerEmail, $subject, $htmlBody, $textBody);
    }
    
    /**
     * Build HTML booking confirmation email
     */
    private function buildBookingConfirmationHTML($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount = null) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        $year = date('Y');
        
        return "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Booking Confirmation - SwiftForge Voting</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #1a73e8; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 10px 0 0; font-size: 16px; opacity: 0.9; }
        .content { padding: 30px; }
        .booking-details { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .booking-details h3 { margin-top: 0; color: #1a73e8; }
        .detail-row { display: flex; justify-content: space-between; margin: 10px 0; padding: 8px 0; border-bottom: 1px solid #e9ecef; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: bold; color: #6c757d; }
        .detail-value { color: #333; }
        .total-amount { background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px; margin: 20px 0; text-align: center; }
        .total-amount h3 { margin: 0; color: #155724; }
        .info-box { background-color: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-box h4 { margin-top: 0; color: #0066cc; }
        .info-box ul { margin: 10px 0; padding-left: 20px; }
        .contact-info { text-align: center; margin: 30px 0; }
        .contact-info p { margin: 5px 0; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 14px; }
        .btn { display: inline-block; background-color: #1a73e8; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"header\">
            <h1>SwiftForge Voting</h1>
            <p>Tsito, Ghana</p>
        </div>
        
        <div class=\"content\">
            <h2 style=\"color: #1a73e8; margin-top: 0;\">Booking Confirmed! 🎉</h2>
            
            <p>Dear <strong>{$customerName}</strong>,</p>
            
            <p>Thank you for choosing SwiftForge Voting! Your booking has been confirmed and we're excited to welcome you to our beautiful hotel in Tsito, Ghana.</p>
            
            <div class=\"booking-details\">
                <h3>📋 Booking Details</h3>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Booking ID:</span>
                    <span class=\"detail-value\"><strong>{$bookingId}</strong></span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Room Type:</span>
                    <span class=\"detail-value\">{$roomType}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Check-in Date:</span>
                    <span class=\"detail-value\">{$checkInFormatted}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Check-out Date:</span>
                    <span class=\"detail-value\">{$checkOutFormatted}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Duration:</span>
                    <span class=\"detail-value\">{$days} night(s)</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Meal Plan:</span>
                    <span class=\"detail-value\">{$mealPlan}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Phone:</span>
                    <span class=\"detail-value\">{$phone}</span>
                </div>
            </div>";
            
        if ($totalAmount) {
            $html .= "
            <div class=\"total-amount\">
                <h3>💰 Total Amount: ₵{$totalAmount}</h3>
            </div>";
        }
        
        $html .= "
            <div class=\"info-box\">
                <h4>ℹ️ Important Information</h4>
                <ul>
                    <li><strong>Check-in time:</strong> 2:00 PM</li>
                    <li><strong>Check-out time:</strong> 11:00 AM</li>
                    <li>Please bring a valid ID for check-in</li>
                    <li>Contact us for any special requests or dietary requirements</li>
                    <li>Free WiFi is available throughout the hotel</li>
                </ul>
            </div>
            
            <div class=\"contact-info\">
                <h4>📞 Need Assistance?</h4>
                <p><strong>Phone:</strong> +233 (0)302 936 062</p>
                <p><strong>Email:</strong> info@ranshotel.com</p>
                <p><strong>Address:</strong> Tsito, Volta Region, Ghana</p>
                <p><strong>Website:</strong> <a href=\"https://www.ranshotel.com\">www.ranshotel.com</a></p>
            </div>
            
            <p>We look forward to providing you with an exceptional stay at SwiftForge Voting!</p>
            
            <p>Best regards,<br>
            <strong>The SwiftForge Voting Team</strong></p>
        </div>
        
        <div class=\"footer\">
            <p>© {$year} SwiftForge Voting. All rights reserved.</p>
            <p>Tsito, Volta Region, Ghana</p>
        </div>
    </div>
</body>
</html>";
        
        return $html;
    }
    
    /**
     * Build text booking confirmation email
     */
    private function buildBookingConfirmationText($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount = null) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        $text = "SwiftForge Voting - Booking Confirmation\n\n";
        $text .= "Dear {$customerName},\n\n";
        $text .= "Thank you for choosing SwiftForge Voting! Your booking has been confirmed.\n\n";
        $text .= "BOOKING DETAILS:\n";
        $text .= "Booking ID: {$bookingId}\n";
        $text .= "Room Type: {$roomType}\n";
        $text .= "Check-in: {$checkInFormatted}\n";
        $text .= "Check-out: {$checkOutFormatted}\n";
        $text .= "Duration: {$days} night(s)\n";
        $text .= "Meal Plan: {$mealPlan}\n";
        $text .= "Phone: {$phone}\n";
        
        if ($totalAmount) {
            $text .= "Total Amount: ₵{$totalAmount}\n";
        }
        
        $text .= "\nIMPORTANT INFORMATION:\n";
        $text .= "- Check-in time: 2:00 PM\n";
        $text .= "- Check-out time: 11:00 AM\n";
        $text .= "- Please bring a valid ID for check-in\n";
        $text .= "- Contact us for any special requests\n\n";
        $text .= "CONTACT US:\n";
        $text .= "Phone: +233 (0)302 936 062\n";
        $text .= "Email: info@ranshotel.com\n";
        $text .= "Address: Tsito, Volta Region, Ghana\n\n";
        $text .= "We look forward to welcoming you to SwiftForge Voting!\n\n";
        $text .= "Best regards,\n";
        $text .= "The SwiftForge Voting Team";
        
        return $text;
    }
    
    /**
     * Build HTML manager notification email
     */
    private function buildManagerNotificationHTML($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        $year = date('Y');
        
        return "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>New Booking Alert - SwiftForge Voting</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #dc3545; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 10px 0 0; font-size: 16px; opacity: 0.9; }
        .content { padding: 30px; }
        .alert-box { background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .alert-box h3 { margin-top: 0; color: #856404; }
        .booking-details { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .booking-details h3 { margin-top: 0; color: #dc3545; }
        .detail-row { display: flex; justify-content: space-between; margin: 10px 0; padding: 8px 0; border-bottom: 1px solid #e9ecef; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: bold; color: #6c757d; }
        .detail-value { color: #333; }
        .action-box { background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center; }
        .action-box h4 { margin-top: 0; color: #0c5460; }
        .btn { display: inline-block; background-color: #dc3545; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 0; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 14px; }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"header\">
            <h1>🚨 New Booking Alert</h1>
            <p>SwiftForge Voting Management System</p>
        </div>
        
        <div class=\"content\">
            <div class=\"alert-box\">
                <h3>⚠️ Action Required</h3>
                <p>A new booking has been received and requires your attention. Please review the details below and take appropriate action.</p>
            </div>
            
            <h2 style=\"color: #dc3545; margin-top: 0;\">Customer Information</h2>
            
            <div class=\"booking-details\">
                <h3>👤 Customer Details</h3>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Name:</span>
                    <span class=\"detail-value\"><strong>{$customerName}</strong></span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Email:</span>
                    <span class=\"detail-value\">{$customerEmail}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Phone:</span>
                    <span class=\"detail-value\">{$customerPhone}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Nationality:</span>
                    <span class=\"detail-value\">{$nationality}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Country:</span>
                    <span class=\"detail-value\">{$country}</span>
                </div>
            </div>
            
            <h2 style=\"color: #dc3545;\">Booking Information</h2>
            
            <div class=\"booking-details\">
                <h3>🏨 Booking Details</h3>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Booking ID:</span>
                    <span class=\"detail-value\"><strong>{$bookingId}</strong></span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Room Type:</span>
                    <span class=\"detail-value\">{$roomType}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Check-in Date:</span>
                    <span class=\"detail-value\">{$checkInFormatted}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Check-out Date:</span>
                    <span class=\"detail-value\">{$checkOutFormatted}</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Duration:</span>
                    <span class=\"detail-value\">{$days} night(s)</span>
                </div>
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Meal Plan:</span>
                    <span class=\"detail-value\">{$mealPlan}</span>
                </div>
            </div>
            
            <div class=\"action-box\">
                <h4>🔧 Next Steps</h4>
                <p>Please log into the admin panel to manage this booking:</p>
                <a href=\"http://localhost/Hotel/admin/\" class=\"btn\">Go to Admin Panel</a>
                <p style=\"margin-top: 15px; font-size: 14px;\">Manager: Eyram Dela | Phone: {$this->managerPhone}</p>
            </div>
        </div>
        
        <div class=\"footer\">
            <p>© {$year} SwiftForge Voting Management System</p>
        </div>
    </div>
</body>
</html>";
    }
    
    /**
     * Build text manager notification email
     */
    private function buildManagerNotificationText($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        $text = "🚨 NEW BOOKING ALERT - SwiftForge Voting\n\n";
        $text .= "CUSTOMER INFORMATION:\n";
        $text .= "Name: {$customerName}\n";
        $text .= "Email: {$customerEmail}\n";
        $text .= "Phone: {$customerPhone}\n";
        $text .= "Nationality: {$nationality}\n";
        $text .= "Country: {$country}\n\n";
        $text .= "BOOKING DETAILS:\n";
        $text .= "Booking ID: {$bookingId}\n";
        $text .= "Room Type: {$roomType}\n";
        $text .= "Check-in: {$checkInFormatted}\n";
        $text .= "Check-out: {$checkOutFormatted}\n";
        $text .= "Duration: {$days} night(s)\n";
        $text .= "Meal Plan: {$mealPlan}\n\n";
        $text .= "Please log into the admin panel to manage this booking.\n\n";
        $text .= "Admin Panel: http://localhost/Hotel/admin/\n";
        $text .= "Manager: Eyram Dela\n";
        $text .= "Phone: {$this->managerPhone}";
        
        return $text;
    }
    
    /**
     * Send email using PHPMailer
     */
    private function sendEmail($to, $subject, $htmlBody, $textBody) {
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL encryption for port 465
            $mail->Port = $this->smtpPort;
            
            // Recipients
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($to);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->AltBody = $textBody;
            
            $mail->send();
            
            return [
                'success' => true,
                'to' => $to,
                'subject' => $subject,
                'message' => 'Email sent successfully via PHPMailer'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'PHPMailer Error: ' . $mail->ErrorInfo,
                'to' => $to,
                'subject' => $subject
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
        
        $subject = "Email Test - SwiftForge Voting System";
        $htmlBody = "<h2>Email Test - SwiftForge Voting System</h2><p>This is a test email from the SwiftForge Voting booking system.</p><p>If you receive this email, the email system is working correctly.</p><p>Test Time: " . date('Y-m-d H:i:s') . "</p>";
        $textBody = "Email Test - SwiftForge Voting System\n\nThis is a test email from the SwiftForge Voting booking system.\n\nIf you receive this email, the email system is working correctly.\n\nTest Time: " . date('Y-m-d H:i:s');
        
        return $this->sendEmail($testEmail, $subject, $htmlBody, $textBody);
    }
}
?>
