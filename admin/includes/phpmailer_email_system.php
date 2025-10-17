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
        "            <td style=\"background:#1a73e8; padding:24px 28px;\">\n" .
        "              <h1 style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:20px; line-height:28px; color:#ffffff;\">RANS HOTEL</h1>\n" .
        "              <p style=\"margin:8px 0 0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#ffffff; opacity:0.9;\">Tsito, Volta Region, Ghana</p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"padding:28px;\">\n" .
        "              <h2 style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; line-height:24px; color:#1a73e8;\">Booking Confirmed! 🎉</h2>\n" .
        "              <p style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; line-height:24px; color:#111827;\">Hi <strong>" . $safeCustomerName . "</strong>,</p>\n" .
        "              <p style=\"margin:0 0 20px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:22px; color:#374151;\">Thank you for choosing RANS HOTEL! Your booking has been confirmed and we're excited to welcome you to our beautiful hotel in Tsito, Volta Region, Ghana.</p>\n" .
        "              <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border:1px solid #e5e7eb; border-radius:10px;\">\n" .
        "                <tr>\n" .
        "                  <td style=\"padding:18px 20px;\">\n" .
        "                    <h3 style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; color:#1a73e8;\">📋 Booking Details</h3>\n" .
        "                    <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;\">\n" .
        "                      <div style=\"margin:0 0 8px; font-size:14px; color:#6b7280;\">Booking ID</div>\n" .
        "                      <div style=\"font-size:16px; font-weight:600; color:#111827; margin-bottom:16px;\">" . $safeBookingId . "</div>\n" .
        "                    </div>\n" .
        "                    <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;\">\n" .
        "                      <div style=\"margin:0 0 8px; font-size:14px; color:#6b7280;\">Room Type</div>\n" .
        "                      <div style=\"font-size:16px; font-weight:600; color:#111827; margin-bottom:16px;\">" . $safeRoomType . "</div>\n" .
        "                    </div>\n" .
        "                    <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;\">\n" .
        "                      <div style=\"margin:0 0 8px; font-size:14px; color:#6b7280;\">Check-in Date</div>\n" .
        "                      <div style=\"font-size:16px; font-weight:600; color:#111827; margin-bottom:16px;\">" . $checkInFormatted . "</div>\n" .
        "                    </div>\n" .
        "                    <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;\">\n" .
        "                      <div style=\"margin:0 0 8px; font-size:14px; color:#6b7280;\">Check-out Date</div>\n" .
        "                      <div style=\"font-size:16px; font-weight:600; color:#111827; margin-bottom:16px;\">" . $checkOutFormatted . "</div>\n" .
        "                    </div>\n" .
        "                    <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;\">\n" .
        "                      <div style=\"margin:0 0 8px; font-size:14px; color:#6b7280;\">Duration</div>\n" .
        "                      <div style=\"font-size:16px; font-weight:600; color:#111827; margin-bottom:16px;\">" . $days . " night(s)</div>\n" .
        "                    </div>\n" .
        "                    <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;\">\n" .
        "                      <div style=\"margin:0 0 8px; font-size:14px; color:#6b7280;\">Meal Plan</div>\n" .
        "                      <div style=\"font-size:16px; font-weight:600; color:#111827; margin-bottom:16px;\">" . $safeMealPlan . "</div>\n" .
        "                    </div>\n" .
        "                    <div style=\"font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;\">\n" .
        "                      <div style=\"margin:0 0 8px; font-size:14px; color:#6b7280;\">Phone</div>\n" .
        "                      <div style=\"font-size:16px; font-weight:600; color:#111827;\">" . $safePhone . "</div>\n" .
        "                    </div>\n" .
        "                  </td>\n" .
        "                </tr>\n" .
        "              </table>";
            
        if ($totalAmount) {
            $safeTotalAmount = htmlspecialchars($totalAmount, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $html .= "\n" .
            "              <div style=\"height:24px\"></div>\n" .
            "              <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#d4edda; border:1px solid #c3e6cb; border-radius:10px;\">\n" .
            "                <tr>\n" .
            "                  <td style=\"padding:18px 20px; text-align:center;\">\n" .
            "                    <h3 style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; color:#155724;\">💰 Total Amount: ₵" . $safeTotalAmount . "</h3>\n" .
            "                  </td>\n" .
            "                </tr>\n" .
            "              </table>";
        }
        
        $html .= "\n" .
        "              <div style=\"height:24px\"></div>\n" .
        "              <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#e7f3ff; border:1px solid #b3d9ff; border-radius:10px;\">\n" .
        "                <tr>\n" .
        "                  <td style=\"padding:18px 20px;\">\n" .
        "                    <h4 style=\"margin:0 0 12px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; color:#0066cc;\">ℹ️ Important Information</h4>\n" .
        "                    <ul style=\"margin:0; padding-left:20px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#374151;\">\n" .
        "                      <li><strong>Check-in time:</strong> 2:00 PM</li>\n" .
        "                      <li><strong>Check-out time:</strong> 11:00 AM</li>\n" .
        "                      <li>Please bring a valid ID for check-in</li>\n" .
        "                      <li>Contact us for any special requests or dietary requirements</li>\n" .
        "                      <li>Free WiFi is available throughout the hotel</li>\n" .
        "                    </ul>\n" .
        "                  </td>\n" .
        "                </tr>\n" .
        "              </table>\n" .
        "              <div style=\"height:24px\"></div>\n" .
        "              <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"text-align:center;\">\n" .
        "                <tr>\n" .
        "                  <td>\n" .
        "                    <h4 style=\"margin:0 0 12px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; color:#1a73e8;\">📞 Need Assistance?</h4>\n" .
        "                    <p style=\"margin:4px 0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#374151;\"><strong>Phone:</strong> +233 (0)302 936 062</p>\n" .
        "                    <p style=\"margin:4px 0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#374151;\"><strong>Email:</strong> swiftforge25@gmail.com</p>\n" .
        "                    <p style=\"margin:4px 0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#374151;\"><strong>Address:</strong> Tsito, Volta Region, Ghana</p>\n" .
        "                  </td>\n" .
        "                </tr>\n" .
        "              </table>\n" .
        "              <div style=\"height:24px\"></div>\n" .
        "              <p style=\"margin:0 0 16px; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:22px; color:#374151;\">We look forward to providing you with an exceptional stay at RANS HOTEL!</p>\n" .
        "              <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:22px; color:#374151;\">Best regards,<br><strong>The RANS HOTEL Team</strong></p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "          <tr>\n" .
        "            <td style=\"background:#f9fafb; padding:18px 28px; text-align:center;\">\n" .
        "              <p style=\"margin:0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; line-height:18px; color:#6b7280;\">© " . $year . " RANS HOTEL. All rights reserved.</p>\n" .
        "              <p style=\"margin:4px 0 0; font-family:-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; line-height:18px; color:#6b7280;\">Tsito, Volta Region, Ghana</p>\n" .
        "            </td>\n" .
        "          </tr>\n" .
        "        </table>\n" .
        "      </td>\n" .
        "    </tr>\n" .
        "  </table>\n" .
        "</body>\n" .
        "</html>\n";
        
        return $html;
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
            $text .= "Total Amount: ₵{$totalAmount}\n";
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
