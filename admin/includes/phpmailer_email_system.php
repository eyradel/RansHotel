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
        $this->fromName = 'RANS HOTEL';
    }
    
    /**
     * Send booking confirmation email to customer
     */
    public function sendBookingConfirmation($customerEmail, $customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount = null) {
        $subject = "Booking Confirmation - RANS HOTEL";
        $htmlBody = $this->buildBookingConfirmationHTML($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount);
        $textBody = $this->buildBookingConfirmationText($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount);
        
        return $this->sendEmail($customerEmail, $subject, $htmlBody, $textBody);
    }
    
    /**
     * Send new booking notification to manager
     */
    public function sendManagerNotification($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country) {
        $subject = "New Booking Alert - RANS HOTEL";
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
        
        $safeCustomerName = htmlspecialchars($customerName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $safeRoomType = htmlspecialchars($roomType, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $safeBookingId = htmlspecialchars($bookingId, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $safePhone = htmlspecialchars($phone, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $safeMealPlan = htmlspecialchars($mealPlan, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        
        // Calculate pricing breakdown
        $roomPrice = 0;
        $mealPrice = 0;
        $subtotal = 0;
        $tax = 0;
        $serviceCharge = 0;
        $finalTotal = 0;
        
        if ($totalAmount) {
            $finalTotal = $totalAmount;
            $subtotal = $finalTotal / 1.25; // Reverse calculate subtotal (15% tax + 10% service = 25%)
            $tax = $subtotal * 0.15;
            $serviceCharge = $subtotal * 0.10;
            $roomPrice = $subtotal * 0.8; // Estimate room cost as 80% of subtotal
            $mealPrice = $subtotal * 0.2; // Estimate meal cost as 20% of subtotal
        }
        
        return "<!doctype html>\n" .
        "<html lang=\"en\">\n" .
        "<head>\n" .
        "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n" .
        "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n" .
        "  <title>Booking Confirmation - RANS HOTEL</title>\n" .
        "  <meta name=\"x-apple-disable-message-reformatting\">\n" .
        "</head>\n" .
        "<body style=\"margin:0; padding:0; background:#f5f7fb; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;\">\n" .
        "  <div style=\"display:none; font-size:1px; color:#f5f7fb; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden;\">Your booking confirmation is inside.\n" .
        "  </div>\n" .
        "  <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#f5f7fb;\">\n" .
        "    <tr>\n" .
        "      <td align=\"center\" style=\"padding:24px;\">\n" .
        "        <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width:640px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(16,24,40,0.06);\">\n" .
        "          <tr>\n" .
        "            <td style=\"background:#1e3a8a; padding:24px 28px;\">\n" .
        "              <h1 style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:24px; line-height:32px; color:#ffffff; text-align:center;\">RANS HOTEL</h1>\n" .
        "              <p style=\"margin:8px 0 0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; color:#ffffff; opacity:0.9; text-align:center;\">Tsito, Volta Region, Ghana</p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"padding:0;\">\n" .
        "              <!-- Personal Information & Stay Details Section (Light Gray Background) -->\n" .
        "              <div style=\"background:#f8fafc; padding:24px 28px;\">\n" .
        "                <div style=\"margin-bottom:24px;\">\n" .
        "                  <h3 style=\"margin:0 0 12px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; color:#1e3a8a; border-bottom:2px solid #d4af37; padding-bottom:8px;\">Personal Information</h3>\n" .
        "                  <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:8px;\">\n" .
        "                    <span style=\"font-weight:600; color:#374151;\">Name:</span>\n" .
        "                    <span style=\"color:#374151; margin-left:8px;\">" . $safeCustomerName . "</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:8px;\">\n" .
        "                    <span style=\"font-weight:600; color:#374151;\">Phone:</span>\n" .
        "                    <span style=\"color:#374151; margin-left:8px;\">" . $safePhone . "</span>\n" .
        "                  </div>\n" .
        "                </div>\n" .
        "                <div>\n" .
        "                  <h3 style=\"margin:0 0 12px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; color:#1e3a8a; border-bottom:2px solid #d4af37; padding-bottom:8px;\">Stay Details</h3>\n" .
        "                  <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:8px;\">\n" .
        "                    <span style=\"font-weight:600; color:#374151;\">Room Type:</span>\n" .
        "                    <span style=\"color:#374151; margin-left:8px;\">" . $safeRoomType . "</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:8px;\">\n" .
        "                    <span style=\"font-weight:600; color:#374151;\">Check-in:</span>\n" .
        "                    <span style=\"color:#374151; margin-left:8px;\">" . $checkIn . "</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:8px;\">\n" .
        "                    <span style=\"font-weight:600; color:#374151;\">Check-out:</span>\n" .
        "                    <span style=\"color:#374151; margin-left:8px;\">" . $checkOut . "</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:8px;\">\n" .
        "                    <span style=\"font-weight:600; color:#374151;\">Duration:</span>\n" .
        "                    <span style=\"color:#374151; margin-left:8px;\">" . $days . " nights</span>\n" .
        "                  </div>\n" .
        "                </div>\n" .
        "              </div>\n" .
        "              \n" .
        "              <!-- Pricing Summary Section (Dark Blue Background) -->\n" .
        "              <div style=\"background:#1e3a8a; padding:24px 28px;\">\n" .
        "                <h3 style=\"margin:0 0 20px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:20px; color:#d4af37; text-align:center; font-weight:600;\">Pricing Summary</h3>\n" .
        "                <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;\">\n" .
        "                  <div style=\"display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #374151;\">\n" .
        "                    <span style=\"color:#e5e7eb;\">Room Cost:</span>\n" .
        "                    <span style=\"color:#e5e7eb; font-weight:600;\">C" . number_format($roomPrice, 2) . "</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #374151;\">\n" .
        "                    <span style=\"color:#e5e7eb;\">Meal Cost:</span>\n" .
        "                    <span style=\"color:#e5e7eb; font-weight:600;\">C" . number_format($mealPrice, 2) . "</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #374151;\">\n" .
        "                    <span style=\"color:#e5e7eb;\">Subtotal:</span>\n" .
        "                    <span style=\"color:#e5e7eb; font-weight:600;\">C" . number_format($subtotal, 2) . "</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #374151;\">\n" .
        "                    <span style=\"color:#e5e7eb;\">Tax (15%):</span>\n" .
        "                    <span style=\"color:#e5e7eb; font-weight:600;\">C" . number_format($tax, 2) . "</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:2px solid #d4af37;\">\n" .
        "                    <span style=\"color:#e5e7eb;\">Service Charge (10%):</span>\n" .
        "                    <span style=\"color:#e5e7eb; font-weight:600;\">C" . number_format($serviceCharge, 2) . "</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"display:flex; justify-content:space-between; align-items:center; padding:12px 0 0; margin-top:8px;\">\n" .
        "                    <span style=\"color:#e5e7eb; font-weight:600; font-size:16px;\">Total Amount:</span>\n" .
        "                    <span style=\"color:#d4af37; font-weight:700; font-size:18px;\">C" . number_format($finalTotal, 2) . "</span>\n" .
        "                  </div>\n" .
        "                </div>\n" .
        "              </div>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"padding:24px 28px; background:#ffffff;\">\n" .
        "              <div style=\"margin:16px 0; padding:16px; background:#f0f9ff; border-radius:8px; border-left:4px solid #0ea5e9;\">\n" .
        "                <h4 style=\"margin:0 0 8px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#0c4a6e;\">Contact Information</h4>\n" .
        "                <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#075985;\">If you have any questions or need to make changes to your booking, please contact us at <strong>+233 (0)302 936 062</strong> or email us at <strong>info@ranshotel.com</strong></p>\n" .
        "              </div>\n" .
        "              <p style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#6b7280;\">We look forward to welcoming you to RANS HOTEL and providing you with an exceptional stay experience.</p>\n" .
        "              <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#6b7280;\">Best regards,<br><strong>The RANS HOTEL Team</strong></p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"background:#f8fafc; padding:20px 28px; text-align:center;\">\n" .
        "              <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; color:#6b7280;\">© " . $year . " RANS HOTEL. All rights reserved. | Tsito, Volta Region, Ghana</p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "        </table>\n" .
        "      </td>\n" .
        "    </tr>\n" .
        "  </table>\n" .
        "</body>\n" .
        "</html>";
    }
    
    /**
     * Build text booking confirmation email
     */
    private function buildBookingConfirmationText($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount = null) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        $text = "RANS HOTEL - Booking Confirmation\n\n";
        $text .= "Dear {$customerName},\n\n";
        $text .= "Thank you for choosing RANS HOTEL! Your booking has been confirmed.\n\n";
        $text .= "BOOKING DETAILS:\n";
        $text .= "Booking ID: {$bookingId}\n";
        $text .= "Room Type: {$roomType}\n";
        $text .= "Check-in: {$checkInFormatted}\n";
        $text .= "Check-out: {$checkOutFormatted}\n";
        $text .= "Duration: {$days} night(s)\n";
        $text .= "Meal Plan: {$mealPlan}\n";
        $text .= "Phone: {$phone}\n";
        
        if ($totalAmount) {
            $text .= "Total Amount: C{$totalAmount}\n";
        }
        
        $text .= "\nIMPORTANT INFORMATION:\n";
        $text .= "- Check-in time: 2:00 PM\n";
        $text .= "- Check-out time: 11:00 AM\n";
        $text .= "- Please bring a valid ID for check-in\n";
        $text .= "- Contact us for any special requests\n\n";
        $text .= "CONTACT US:\n";
        $text .= "Phone: +233 (0)302 936 062\n";
        $text .= "Email: swiftforge25@gmail.com\n";
        $text .= "Address: Tsito, Volta Region, Ghana\n\n";
        $text .= "We look forward to welcoming you to RANS HOTEL!\n\n";
        $text .= "Best regards,\n";
        $text .= "The RANS HOTEL Team";
        
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
    public function sendEmail($to, $subject, $htmlBody, $textBody) {
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
            $mail->CharSet = 'UTF-8';
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
     * Send cancellation email to customer
     */
    public function sendCancellationEmail($customerEmail, $customerName, $bookingId) {
        $subject = "Booking Cancellation - RANS HOTEL";
        $htmlBody = $this->buildCancellationEmailHTML($customerName, $bookingId);
        $textBody = $this->buildCancellationEmailText($customerName, $bookingId);
        
        return $this->sendEmail($customerEmail, $subject, $htmlBody, $textBody);
    }
    
    /**
     * Send check-in reminder email to customer
     */
    public function sendCheckInReminder($customerEmail, $customerName, $checkIn, $roomType) {
        $subject = "Check-in Reminder - RANS HOTEL";
        $htmlBody = $this->buildCheckInReminderHTML($customerName, $checkIn, $roomType);
        $textBody = $this->buildCheckInReminderText($customerName, $checkIn, $roomType);
        
        return $this->sendEmail($customerEmail, $subject, $htmlBody, $textBody);
    }
    
    /**
     * Build HTML cancellation email
     */
    private function buildCancellationEmailHTML($customerName, $bookingId) {
        $year = date('Y');
        
        $safeCustomerName = htmlspecialchars($customerName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $safeBookingId = htmlspecialchars($bookingId, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        
        return "<!doctype html>\n" .
        "<html lang=\"en\">\n" .
        "<head>\n" .
        "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n" .
        "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n" .
        "  <title>Booking Cancellation - RANS HOTEL</title>\n" .
        "  <meta name=\"x-apple-disable-message-reformatting\">\n" .
        "</head>\n" .
        "<body style=\"margin:0; padding:0; background:#f5f7fb; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;\">\n" .
        "  <div style=\"display:none; font-size:1px; color:#f5f7fb; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden;\">Your booking cancellation confirmation is inside.\n" .
        "  </div>\n" .
        "  <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#f5f7fb;\">\n" .
        "    <tr>\n" .
        "      <td align=\"center\" style=\"padding:24px;\">\n" .
        "        <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width:640px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(16,24,40,0.06);\">\n" .
        "          <tr>\n" .
        "            <td style=\"background:#dc2626; padding:24px 28px;\">\n" .
        "              <h1 style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:24px; line-height:32px; color:#ffffff; text-align:center;\">RANS HOTEL</h1>\n" .
        "              <p style=\"margin:8px 0 0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; color:#ffffff; opacity:0.9; text-align:center;\">Tsito, Volta Region, Ghana</p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"padding:0;\">\n" .
        "              <!-- Cancellation Header Section -->\n" .
        "              <div style=\"background:#fef2f2; padding:24px 28px; border-left:4px solid #dc2626;\">\n" .
        "                <h2 style=\"margin:0 0 12px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:20px; color:#dc2626; text-align:center;\">❌ Booking Cancelled</h2>\n" .
        "                <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; color:#dc2626; text-align:center; font-weight:500;\">We're sorry to see you go, but we understand plans change.</p>\n" .
        "              </div>\n" .
        "              \n" .
        "              <!-- Cancellation Details Section -->\n" .
        "              <div style=\"background:#f8fafc; padding:24px 28px;\">\n" .
        "                <h3 style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; color:#1e3a8a; border-bottom:2px solid #d4af37; padding-bottom:8px;\">Cancellation Details</h3>\n" .
        "                <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:12px;\">\n" .
        "                  <span style=\"font-weight:600; color:#374151;\">Guest Name:</span>\n" .
        "                  <span style=\"color:#374151; margin-left:8px;\">" . $safeCustomerName . "</span>\n" .
        "                </div>\n" .
        "                <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:12px;\">\n" .
        "                  <span style=\"font-weight:600; color:#374151;\">Booking ID:</span>\n" .
        "                  <span style=\"color:#1e3a8a; margin-left:8px; font-weight:600;\">" . $safeBookingId . "</span>\n" .
        "                </div>\n" .
        "                <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:12px;\">\n" .
        "                  <span style=\"font-weight:600; color:#374151;\">Cancellation Date:</span>\n" .
        "                  <span style=\"color:#374151; margin-left:8px;\">" . date('F j, Y \a\t g:i A') . "</span>\n" .
        "                </div>\n" .
        "              </div>\n" .
        "              \n" .
        "              <!-- Refund Information Section -->\n" .
        "              <div style=\"background:#1e3a8a; padding:24px 28px;\">\n" .
        "                <h3 style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; color:#d4af37; text-align:center;\">Refund Information</h3>\n" .
        "                <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#e5e7eb;\">\n" .
        "                  <div style=\"margin-bottom:12px; padding:8px 0; border-bottom:1px solid #374151;\">\n" .
        "                    <span style=\"font-weight:600;\">Refund Status:</span>\n" .
        "                    <span style=\"margin-left:8px;\">Processing</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"margin-bottom:12px; padding:8px 0; border-bottom:1px solid #374151;\">\n" .
        "                    <span style=\"font-weight:600;\">Processing Time:</span>\n" .
        "                    <span style=\"margin-left:8px;\">3-5 business days</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"padding:8px 0;\">\n" .
        "                    <span style=\"font-weight:600;\">Refund Method:</span>\n" .
        "                    <span style=\"margin-left:8px;\">Original payment method</span>\n" .
        "                  </div>\n" .
        "                </div>\n" .
        "              </div>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"padding:24px 28px; background:#ffffff;\">\n" .
        "              <div style=\"margin:16px 0; padding:16px; background:#f0f9ff; border-radius:8px; border-left:4px solid #0ea5e9;\">\n" .
        "                <h4 style=\"margin:0 0 8px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#0c4a6e;\">Questions About Your Cancellation?</h4>\n" .
        "                <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#075985;\">If you have any questions about your cancellation or refund, please contact us at <strong>+233 (0)302 936 062</strong> or email us at <strong>info@ranshotel.com</strong></p>\n" .
        "              </div>\n" .
        "              <p style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#6b7280;\">We hope to welcome you back to RANS HOTEL in the future.</p>\n" .
        "              <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#6b7280;\">Thank you for considering RANS HOTEL.<br><strong>The RANS HOTEL Team</strong></p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"background:#f8fafc; padding:20px 28px; text-align:center;\">\n" .
        "              <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; color:#6b7280;\">© " . $year . " RANS HOTEL. All rights reserved. | Tsito, Volta Region, Ghana</p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "        </table>\n" .
        "      </td>\n" .
        "    </tr>\n" .
        "  </table>\n" .
        "</body>\n" .
        "</html>";
    }
    
    /**
     * Build text cancellation email
     */
    private function buildCancellationEmailText($customerName, $bookingId) {
        $text = "RANS HOTEL - Booking Cancellation\n\n";
        $text .= "Dear {$customerName},\n\n";
        $text .= "We're sorry to see you go, but we understand plans change.\n\n";
        $text .= "CANCELLATION DETAILS:\n";
        $text .= "Guest Name: {$customerName}\n";
        $text .= "Booking ID: {$bookingId}\n";
        $text .= "Cancellation Date: " . date('F j, Y \a\t g:i A') . "\n\n";
        $text .= "REFUND INFORMATION:\n";
        $text .= "- Refund Status: Processing\n";
        $text .= "- Processing Time: 3-5 business days\n";
        $text .= "- Refund Method: Original payment method\n\n";
        $text .= "CONTACT US:\n";
        $text .= "Phone: +233 (0)302 936 062\n";
        $text .= "Email: info@ranshotel.com\n";
        $text .= "Address: Tsito, Volta Region, Ghana\n\n";
        $text .= "We hope to welcome you back to RANS HOTEL in the future.\n\n";
        $text .= "Thank you for considering RANS HOTEL.\n\n";
        $text .= "Best regards,\n";
        $text .= "The RANS HOTEL Team";
        
        return $text;
    }
    
    /**
     * Build HTML check-in reminder email
     */
    private function buildCheckInReminderHTML($customerName, $checkIn, $roomType) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $year = date('Y');
        
        $safeCustomerName = htmlspecialchars($customerName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $safeRoomType = htmlspecialchars($roomType, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        
        return "<!doctype html>\n" .
        "<html lang=\"en\">\n" .
        "<head>\n" .
        "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n" .
        "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n" .
        "  <title>Check-in Reminder - RANS HOTEL</title>\n" .
        "  <meta name=\"x-apple-disable-message-reformatting\">\n" .
        "</head>\n" .
        "<body style=\"margin:0; padding:0; background:#f5f7fb; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;\">\n" .
        "  <div style=\"display:none; font-size:1px; color:#f5f7fb; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden;\">Your check-in reminder is inside.\n" .
        "  </div>\n" .
        "  <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#f5f7fb;\">\n" .
        "    <tr>\n" .
        "      <td align=\"center\" style=\"padding:24px;\">\n" .
        "        <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width:640px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(16,24,40,0.06);\">\n" .
        "          <tr>\n" .
        "            <td style=\"background:#1e3a8a; padding:24px 28px;\">\n" .
        "              <h1 style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:24px; line-height:32px; color:#ffffff; text-align:center;\">RANS HOTEL</h1>\n" .
        "              <p style=\"margin:8px 0 0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; color:#ffffff; opacity:0.9; text-align:center;\">Tsito, Volta Region, Ghana</p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"padding:0;\">\n" .
        "              <!-- Reminder Header Section -->\n" .
        "              <div style=\"background:#fef3c7; padding:24px 28px; border-left:4px solid #f59e0b;\">\n" .
        "                <h2 style=\"margin:0 0 12px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:20px; color:#92400e; text-align:center;\">⏰ Check-in Reminder</h2>\n" .
        "                <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; color:#92400e; text-align:center; font-weight:500;\">We're excited to welcome you to RANS HOTEL!</p>\n" .
        "              </div>\n" .
        "              \n" .
        "              <!-- Booking Details Section -->\n" .
        "              <div style=\"background:#f8fafc; padding:24px 28px;\">\n" .
        "                <h3 style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; color:#1e3a8a; border-bottom:2px solid #d4af37; padding-bottom:8px;\">Your Stay Details</h3>\n" .
        "                <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:12px;\">\n" .
        "                  <span style=\"font-weight:600; color:#374151;\">Guest Name:</span>\n" .
        "                  <span style=\"color:#374151; margin-left:8px;\">" . $safeCustomerName . "</span>\n" .
        "                </div>\n" .
        "                <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:12px;\">\n" .
        "                  <span style=\"font-weight:600; color:#374151;\">Room Type:</span>\n" .
        "                  <span style=\"color:#374151; margin-left:8px;\">" . $safeRoomType . "</span>\n" .
        "                </div>\n" .
        "                <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin-bottom:12px;\">\n" .
        "                  <span style=\"font-weight:600; color:#374151;\">Check-in Date:</span>\n" .
        "                  <span style=\"color:#1e3a8a; margin-left:8px; font-weight:600;\">" . $checkInFormatted . "</span>\n" .
        "                </div>\n" .
        "              </div>\n" .
        "              \n" .
        "              <!-- Important Information Section -->\n" .
        "              <div style=\"background:#1e3a8a; padding:24px 28px;\">\n" .
        "                <h3 style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; color:#d4af37; text-align:center;\">Important Check-in Information</h3>\n" .
        "                <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#e5e7eb;\">\n" .
        "                  <div style=\"margin-bottom:12px; padding:8px 0; border-bottom:1px solid #374151;\">\n" .
        "                    <span style=\"font-weight:600;\">Check-in Time:</span>\n" .
        "                    <span style=\"margin-left:8px;\">2:00 PM onwards</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"margin-bottom:12px; padding:8px 0; border-bottom:1px solid #374151;\">\n" .
        "                    <span style=\"font-weight:600;\">Check-out Time:</span>\n" .
        "                    <span style=\"margin-left:8px;\">11:00 AM</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"margin-bottom:12px; padding:8px 0; border-bottom:1px solid #374151;\">\n" .
        "                    <span style=\"font-weight:600;\">Required Documents:</span>\n" .
        "                    <span style=\"margin-left:8px;\">Valid ID or Passport</span>\n" .
        "                  </div>\n" .
        "                  <div style=\"padding:8px 0;\">\n" .
        "                    <span style=\"font-weight:600;\">Special Requests:</span>\n" .
        "                    <span style=\"margin-left:8px;\">Contact us in advance</span>\n" .
        "                  </div>\n" .
        "                </div>\n" .
        "              </div>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"padding:24px 28px; background:#ffffff;\">\n" .
        "              <div style=\"margin:16px 0; padding:16px; background:#f0f9ff; border-radius:8px; border-left:4px solid #0ea5e9;\">\n" .
        "                <h4 style=\"margin:0 0 8px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#0c4a6e;\">Need Assistance?</h4>\n" .
        "                <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#075985;\">If you have any questions or need to make changes to your booking, please contact us at <strong>+233 (0)302 936 062</strong> or email us at <strong>info@ranshotel.com</strong></p>\n" .
        "              </div>\n" .
        "              <p style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#6b7280;\">We look forward to providing you with an exceptional stay experience at RANS HOTEL.</p>\n" .
        "              <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#6b7280;\">Safe travels and see you soon!<br><strong>The RANS HOTEL Team</strong></p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"background:#f8fafc; padding:20px 28px; text-align:center;\">\n" .
        "              <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; color:#6b7280;\">© " . $year . " RANS HOTEL. All rights reserved. | Tsito, Volta Region, Ghana</p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "        </table>\n" .
        "      </td>\n" .
        "    </tr>\n" .
        "  </table>\n" .
        "</body>\n" .
        "</html>";
    }
    
    /**
     * Build text check-in reminder email
     */
    private function buildCheckInReminderText($customerName, $checkIn, $roomType) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        
        $text = "RANS HOTEL - Check-in Reminder\n\n";
        $text .= "Dear {$customerName},\n\n";
        $text .= "We're excited to welcome you to RANS HOTEL!\n\n";
        $text .= "YOUR STAY DETAILS:\n";
        $text .= "Guest Name: {$customerName}\n";
        $text .= "Room Type: {$roomType}\n";
        $text .= "Check-in Date: {$checkInFormatted}\n\n";
        $text .= "IMPORTANT CHECK-IN INFORMATION:\n";
        $text .= "- Check-in Time: 2:00 PM onwards\n";
        $text .= "- Check-out Time: 11:00 AM\n";
        $text .= "- Required Documents: Valid ID or Passport\n";
        $text .= "- Special Requests: Contact us in advance\n\n";
        $text .= "CONTACT US:\n";
        $text .= "Phone: +233 (0)302 936 062\n";
        $text .= "Email: info@ranshotel.com\n";
        $text .= "Address: Tsito, Volta Region, Ghana\n\n";
        $text .= "We look forward to providing you with an exceptional stay experience!\n\n";
        $text .= "Safe travels and see you soon!\n\n";
        $text .= "Best regards,\n";
        $text .= "The RANS HOTEL Team";
        
        return $text;
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
