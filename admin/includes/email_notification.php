<?php
/**
 * Email Notification System for RansHotel
 * Sends email notifications for bookings and reservations
 */

class EmailNotification {
    private $smtpHost;
    private $smtpPort;
    private $smtpUsername;
    private $smtpPassword;
    private $fromEmail;
    private $fromName;
    private $managerEmail;
    private $managerPhone;
    
    public function __construct() {
        // Email Configuration
        $this->smtpHost = 'smtp.gmail.com';
        $this->smtpPort = 465; // SSL
        $this->smtpUsername = 'eyramdela14@gmail.com';
        $this->smtpPassword = 'vdxfxvwsyfjgsvav'; // App password
        $this->fromEmail = 'eyramdela14@gmail.com';
        $this->fromName = 'RansHotel';
        $this->managerEmail = 'eyramdela14@gmail.com';
        $this->managerPhone = '0540202096';
    }
    
    /**
     * Send booking confirmation email to customer
     */
    public function sendBookingConfirmation($customerEmail, $customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount = null) {
        $subject = "Booking Confirmation - RansHotel";
        $htmlBody = $this->buildBookingConfirmationEmail($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount);
        $altBody = $this->buildBookingConfirmationText($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount);
        
        return $this->sendEmail($customerEmail, $subject, $htmlBody, $altBody);
    }
    
    /**
     * Send new booking notification to manager
     */
    public function sendManagerNotification($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country) {
        $subject = "New Booking Alert - RansHotel";
        $htmlBody = $this->buildManagerNotificationEmail($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country);
        $altBody = $this->buildManagerNotificationText($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country);
        
        return $this->sendEmail($this->managerEmail, $subject, $htmlBody, $altBody);
    }
    
    /**
     * Send cancellation email to customer
     */
    public function sendCancellationEmail($customerEmail, $customerName, $bookingId) {
        $subject = "Booking Cancellation - RansHotel";
        $htmlBody = $this->buildCancellationEmail($customerName, $bookingId);
        $altBody = $this->buildCancellationText($customerName, $bookingId);
        
        return $this->sendEmail($customerEmail, $subject, $htmlBody, $altBody);
    }
    
    /**
     * Send check-in reminder email
     */
    public function sendCheckInReminder($customerEmail, $customerName, $checkIn, $roomType) {
        $subject = "Check-in Reminder - RansHotel";
        $htmlBody = $this->buildReminderEmail($customerName, $checkIn, $roomType);
        $altBody = $this->buildReminderText($customerName, $checkIn, $roomType);
        
        return $this->sendEmail($customerEmail, $subject, $htmlBody, $altBody);
    }
    
    /**
     * Build HTML email for booking confirmation
     */
    private function buildBookingConfirmationEmail($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan, $totalAmount = null) {
        $year = date('Y');
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        return "<!doctype html>
<html lang=\"en\">
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
  <title>RansHotel Booking Confirmation</title>
</head>
<body style=\"margin:0; padding:0; background:#f5f7fb; -webkit-font-smoothing:antialiased;\">
  <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#f5f7fb;\">
    <tr>
      <td align=\"center\" style=\"padding:24px;\">
        <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width:640px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(16,24,40,0.06);\">
          <tr>
            <td style=\"background:#1a73e8; padding:24px 28px; text-align:center;\">
              <h1 style=\"margin:0; font-family:Arial, sans-serif; font-size:24px; line-height:32px; color:#ffffff;\">RansHotel</h1>
              <p style=\"margin:8px 0 0; font-family:Arial, sans-serif; font-size:14px; color:#e8f0fe;\">Tsito, Ghana</p>
            </td>
          </tr>
          <tr>
            <td style=\"padding:28px;\">
              <h2 style=\"margin:0 0 16px; font-family:Arial, sans-serif; font-size:20px; color:#1a73e8;\">Booking Confirmed!</h2>
              <p style=\"margin:0 0 20px; font-family:Arial, sans-serif; font-size:16px; line-height:24px; color:#111827;\">Hi <strong>{$customerName}</strong>,</p>
              <p style=\"margin:0 0 20px; font-family:Arial, sans-serif; font-size:15px; line-height:22px; color:#374151;\">Thank you for choosing RansHotel! Your booking has been confirmed.</p>
              
              <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border:1px solid #e5e7eb; border-radius:10px; margin-bottom:20px;\">
                <tr>
                  <td style=\"padding:20px;\">
                    <h3 style=\"margin:0 0 16px; font-family:Arial, sans-serif; font-size:16px; color:#1a73e8;\">Booking Details</h3>
                    <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280; width:40%;\">Booking ID:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$bookingId}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Room Type:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$roomType}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Check-in:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$checkInFormatted}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Check-out:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$checkOutFormatted}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Duration:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$days} night(s)</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Meal Plan:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$mealPlan}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              
              <div style=\"background:#f0f9ff; border:1px solid #0ea5e9; border-radius:8px; padding:16px; margin:20px 0;\">
                <h4 style=\"margin:0 0 8px; font-family:Arial, sans-serif; font-size:14px; color:#0c4a6e;\">Important Information</h4>
                <ul style=\"margin:0; padding-left:20px; font-family:Arial, sans-serif; font-size:13px; color:#0c4a6e;\">
                  <li>Check-in time: 2:00 PM</li>
                  <li>Check-out time: 11:00 AM</li>
                  <li>Please bring a valid ID for check-in</li>
                  <li>Contact us for any special requests</li>
                </ul>
              </div>
              
              <div style=\"text-align:center; margin:24px 0;\">
                <p style=\"margin:0 0 8px; font-family:Arial, sans-serif; font-size:14px; color:#374151;\">Need assistance?</p>
                <p style=\"margin:0; font-family:Arial, sans-serif; font-size:14px; color:#1a73e8;\">
                  Phone: +233 (0)302 936 062<br>
                  Email: info@ranshotel.com
                </p>
              </div>
            </td>
          </tr>
          <tr>
            <td style=\"background:#f9fafb; padding:18px 28px; text-align:center;\">
              <p style=\"margin:0; font-family:Arial, sans-serif; font-size:12px; line-height:18px; color:#6b7280;\">© {$year} RansHotel. All rights reserved.</p>
              <p style=\"margin:4px 0 0; font-family:Arial, sans-serif; font-size:12px; color:#6b7280;\">Tsito, Volta Region, Ghana</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>";
    }
    
    /**
     * Build text version of booking confirmation email
     */
    private function buildBookingConfirmationText($customerName, $roomType, $checkIn, $checkOut, $bookingId, $phone, $mealPlan) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        return "RansHotel - Booking Confirmation\n\n" .
               "Hi {$customerName},\n\n" .
               "Thank you for choosing RansHotel! Your booking has been confirmed.\n\n" .
               "BOOKING DETAILS:\n" .
               "Booking ID: {$bookingId}\n" .
               "Room Type: {$roomType}\n" .
               "Check-in: {$checkInFormatted}\n" .
               "Check-out: {$checkOutFormatted}\n" .
               "Duration: {$days} night(s)\n" .
               "Meal Plan: {$mealPlan}\n\n" .
               "IMPORTANT INFORMATION:\n" .
               "- Check-in time: 2:00 PM\n" .
               "- Check-out time: 11:00 AM\n" .
               "- Please bring a valid ID for check-in\n" .
               "- Contact us for any special requests\n\n" .
               "CONTACT US:\n" .
               "Phone: +233 (0)302 936 062\n" .
               "Email: info@ranshotel.com\n\n" .
               "Thank you for choosing RansHotel!\n" .
               "Tsito, Volta Region, Ghana";
    }
    
    /**
     * Build HTML email for manager notification
     */
    private function buildManagerNotificationEmail($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country) {
        $year = date('Y');
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        return "<!doctype html>
<html lang=\"en\">
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
  <title>New Booking Alert - RansHotel</title>
</head>
<body style=\"margin:0; padding:0; background:#f5f7fb;\">
  <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#f5f7fb;\">
    <tr>
      <td align=\"center\" style=\"padding:24px;\">
        <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width:640px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(16,24,40,0.06);\">
          <tr>
            <td style=\"background:#dc2626; padding:24px 28px; text-align:center;\">
              <h1 style=\"margin:0; font-family:Arial, sans-serif; font-size:20px; line-height:28px; color:#ffffff;\">🚨 New Booking Alert</h1>
              <p style=\"margin:8px 0 0; font-family:Arial, sans-serif; font-size:14px; color:#fecaca;\">RansHotel Management</p>
            </td>
          </tr>
          <tr>
            <td style=\"padding:28px;\">
              <h2 style=\"margin:0 0 16px; font-family:Arial, sans-serif; font-size:18px; color:#dc2626;\">New Reservation Received</h2>
              
              <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border:1px solid #e5e7eb; border-radius:10px; margin-bottom:20px;\">
                <tr>
                  <td style=\"padding:20px;\">
                    <h3 style=\"margin:0 0 16px; font-family:Arial, sans-serif; font-size:16px; color:#dc2626;\">Customer Information</h3>
                    <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280; width:30%;\">Name:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$customerName}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Email:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$customerEmail}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Phone:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$customerPhone}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Nationality:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$nationality}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Country:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$country}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              
              <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border:1px solid #e5e7eb; border-radius:10px; margin-bottom:20px;\">
                <tr>
                  <td style=\"padding:20px;\">
                    <h3 style=\"margin:0 0 16px; font-family:Arial, sans-serif; font-size:16px; color:#dc2626;\">Booking Details</h3>
                    <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280; width:30%;\">Booking ID:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$bookingId}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Room Type:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$roomType}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Check-in:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$checkInFormatted}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Check-out:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$checkOutFormatted}</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Duration:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$days} night(s)</td>
                      </tr>
                      <tr>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Meal Plan:</td>
                        <td style=\"padding:8px 0; font-family:Arial, sans-serif; font-size:14px; color:#111827; font-weight:600;\">{$mealPlan}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              
              <div style=\"text-align:center; margin:24px 0;\">
                <p style=\"margin:0; font-family:Arial, sans-serif; font-size:14px; color:#374151;\">Please log into the admin panel to manage this booking.</p>
              </div>
            </td>
          </tr>
          <tr>
            <td style=\"background:#f9fafb; padding:18px 28px; text-align:center;\">
              <p style=\"margin:0; font-family:Arial, sans-serif; font-size:12px; line-height:18px; color:#6b7280;\">© {$year} RansHotel Management System</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>";
    }
    
    /**
     * Build text version of manager notification email
     */
    private function buildManagerNotificationText($customerName, $customerEmail, $customerPhone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        $checkOutFormatted = date('F j, Y', strtotime($checkOut));
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        
        return "NEW BOOKING ALERT - RansHotel\n\n" .
               "CUSTOMER INFORMATION:\n" .
               "Name: {$customerName}\n" .
               "Email: {$customerEmail}\n" .
               "Phone: {$customerPhone}\n" .
               "Nationality: {$nationality}\n" .
               "Country: {$country}\n\n" .
               "BOOKING DETAILS:\n" .
               "Booking ID: {$bookingId}\n" .
               "Room Type: {$roomType}\n" .
               "Check-in: {$checkInFormatted}\n" .
               "Check-out: {$checkOutFormatted}\n" .
               "Duration: {$days} night(s)\n" .
               "Meal Plan: {$mealPlan}\n\n" .
               "Please log into the admin panel to manage this booking.";
    }
    
    /**
     * Build cancellation email
     */
    private function buildCancellationEmail($customerName, $bookingId) {
        $year = date('Y');
        return "<!doctype html>
<html lang=\"en\">
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
  <title>Booking Cancellation - RansHotel</title>
</head>
<body style=\"margin:0; padding:0; background:#f5f7fb;\">
  <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#f5f7fb;\">
    <tr>
      <td align=\"center\" style=\"padding:24px;\">
        <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width:640px; background:#ffffff; border-radius:12px; overflow:hidden;\">
          <tr>
            <td style=\"background:#dc2626; padding:24px 28px; text-align:center;\">
              <h1 style=\"margin:0; font-family:Arial, sans-serif; font-size:20px; color:#ffffff;\">Booking Cancelled</h1>
            </td>
          </tr>
          <tr>
            <td style=\"padding:28px;\">
              <p style=\"margin:0 0 20px; font-family:Arial, sans-serif; font-size:16px; color:#111827;\">Hi <strong>{$customerName}</strong>,</p>
              <p style=\"margin:0 0 20px; font-family:Arial, sans-serif; font-size:15px; color:#374151;\">Your booking <strong>{$bookingId}</strong> at RansHotel has been cancelled.</p>
              <p style=\"margin:0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">If you have any questions, please contact us at +233 (0)302 936 062</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>";
    }
    
    /**
     * Build cancellation text
     */
    private function buildCancellationText($customerName, $bookingId) {
        return "Booking Cancellation - RansHotel\n\n" .
               "Hi {$customerName},\n\n" .
               "Your booking {$bookingId} at RansHotel has been cancelled.\n\n" .
               "If you have any questions, please contact us at +233 (0)302 936 062";
    }
    
    /**
     * Build reminder email
     */
    private function buildReminderEmail($customerName, $checkIn, $roomType) {
        $year = date('Y');
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        
        return "<!doctype html>
<html lang=\"en\">
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
  <title>Check-in Reminder - RansHotel</title>
</head>
<body style=\"margin:0; padding:0; background:#f5f7fb;\">
  <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#f5f7fb;\">
    <tr>
      <td align=\"center\" style=\"padding:24px;\">
        <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width:640px; background:#ffffff; border-radius:12px; overflow:hidden;\">
          <tr>
            <td style=\"background:#1a73e8; padding:24px 28px; text-align:center;\">
              <h1 style=\"margin:0; font-family:Arial, sans-serif; font-size:20px; color:#ffffff;\">Check-in Reminder</h1>
            </td>
          </tr>
          <tr>
            <td style=\"padding:28px;\">
              <p style=\"margin:0 0 20px; font-family:Arial, sans-serif; font-size:16px; color:#111827;\">Hi <strong>{$customerName}</strong>,</p>
              <p style=\"margin:0 0 20px; font-family:Arial, sans-serif; font-size:15px; color:#374151;\">This is a reminder that your check-in at RansHotel is tomorrow ({$checkInFormatted}).</p>
              <p style=\"margin:0 0 20px; font-family:Arial, sans-serif; font-size:15px; color:#374151;\">Room: <strong>{$roomType}</strong></p>
              <p style=\"margin:0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">We look forward to welcoming you! Contact: +233 (0)302 936 062</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>";
    }
    
    /**
     * Build reminder text
     */
    private function buildReminderText($customerName, $checkIn, $roomType) {
        $checkInFormatted = date('F j, Y', strtotime($checkIn));
        
        return "Check-in Reminder - RansHotel\n\n" .
               "Hi {$customerName},\n\n" .
               "This is a reminder that your check-in at RansHotel is tomorrow ({$checkInFormatted}).\n" .
               "Room: {$roomType}\n\n" .
               "We look forward to welcoming you!\n" .
               "Contact: +233 (0)302 936 062";
    }
    
    /**
     * Core email sending function using SMTP
     */
    private function sendEmail($to, $subject, $htmlBody, $altBody) {
        // Try SMTP first, fallback to mail() if SMTP fails
        $smtpResult = $this->sendEmailViaSMTP($to, $subject, $htmlBody, $altBody);
        
        if ($smtpResult['success']) {
            return $smtpResult;
        }
        
        // Fallback to mail() function
        return $this->sendEmailViaMail($to, $subject, $htmlBody, $altBody);
    }
    
    /**
     * Send email via SMTP using fsockopen
     */
    private function sendEmailViaSMTP($to, $subject, $htmlBody, $altBody) {
        try {
            // Use Gmail SMTP settings
            $smtpHost = 'smtp.gmail.com';
            $smtpPort = 587;
            $smtpUsername = 'swiftforge25@gmail.com';
            $smtpPassword = 'vdxfxvwsyfjgsvav';
            
            // Create connection
            $connection = fsockopen($smtpHost, $smtpPort, $errno, $errstr, 30);
            
            if (!$connection) {
                return [
                    'success' => false,
                    'error' => "SMTP Connection failed: $errstr ($errno)",
                    'to' => $to,
                    'subject' => $subject
                ];
            }
            
            // SMTP conversation
            $this->smtpCommand($connection, '', '220');
            $this->smtpCommand($connection, 'EHLO localhost', '250');
            $this->smtpCommand($connection, 'STARTTLS', '220');
            stream_socket_enable_crypto($connection, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $this->smtpCommand($connection, 'EHLO localhost', '250');
            $this->smtpCommand($connection, 'AUTH LOGIN', '334');
            $this->smtpCommand($connection, base64_encode($smtpUsername), '334');
            $this->smtpCommand($connection, base64_encode($smtpPassword), '235');
            $this->smtpCommand($connection, "MAIL FROM: <{$this->fromEmail}>", '250');
            $this->smtpCommand($connection, "RCPT TO: <$to>", '250');
            $this->smtpCommand($connection, 'DATA', '354');
            
            // Send email headers and body
            $emailData = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
            $emailData .= "To: $to\r\n";
            $emailData .= "Subject: $subject\r\n";
            $emailData .= "MIME-Version: 1.0\r\n";
            $emailData .= "Content-Type: text/html; charset=UTF-8\r\n";
            $emailData .= "\r\n";
            $emailData .= $htmlBody . "\r\n";
            $emailData .= ".\r\n";
            
            fwrite($connection, $emailData);
            $response = fgets($connection, 515);
            
            if (substr($response, 0, 3) == '250') {
                $this->smtpCommand($connection, 'QUIT', '221');
                fclose($connection);
                
                return [
                    'success' => true,
                    'to' => $to,
                    'subject' => $subject,
                    'message' => 'Email sent successfully via SMTP'
                ];
            } else {
                fclose($connection);
                return [
                    'success' => false,
                    'error' => 'SMTP send failed: ' . $response,
                    'to' => $to,
                    'subject' => $subject
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'SMTP Error: ' . $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ];
        }
    }
    
    /**
     * Send email via PHP mail() function (fallback)
     */
    private function sendEmailViaMail($to, $subject, $htmlBody, $altBody) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: {$this->fromName} <{$this->fromEmail}>" . "\r\n";
        $headers .= "Reply-To: {$this->fromEmail}" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        try {
            $result = mail($to, $subject, $htmlBody, $headers);
            return [
                'success' => $result,
                'to' => $to,
                'subject' => $subject,
                'message' => $result ? 'Email sent successfully via mail()' : 'Failed to send email via mail()'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Mail() Error: ' . $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ];
        }
    }
    
    /**
     * Helper function for SMTP commands
     */
    private function smtpCommand($connection, $command, $expectedCode) {
        if ($command) {
            fwrite($connection, $command . "\r\n");
        }
        $response = fgets($connection, 515);
        if (substr($response, 0, 3) != $expectedCode) {
            throw new Exception("SMTP Error: Expected $expectedCode, got " . substr($response, 0, 3) . " - $response");
        }
        return $response;
    }
}
?>
