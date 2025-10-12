<?php
/**
 * Notification Manager for RansHotel
 * Manages both SMS and Email notifications
 */

require_once 'sms_notification.php';
require_once 'phpmailer_email_system.php';

class NotificationManager {
    private $smsNotification;
    private $emailNotification;
    private $managerEmail;
    private $managerPhone;
    
    public function __construct() {
        $this->smsNotification = new SMSNotification();
        $this->emailNotification = new PHPMailerEmailSystem();
        $this->managerEmail = 'eyramdela14@gmail.com';
        $this->managerPhone = '0540202096';
    }
    
    /**
     * Send booking confirmation to customer and notification to manager
     */
    public function sendBookingNotifications($bookingData) {
        $results = [];
        
        // Send confirmation email to customer
        $emailResult = $this->emailNotification->sendBookingConfirmation(
            $bookingData['email'],
            $bookingData['customerName'],
            $bookingData['roomType'],
            $bookingData['checkIn'],
            $bookingData['checkOut'],
            $bookingData['bookingId'],
            $bookingData['phone'],
            $bookingData['mealPlan']
        );
        $results['customer_email'] = $emailResult;
        
        // Send confirmation SMS to customer
        $smsResult = $this->smsNotification->sendBookingConfirmation(
            $bookingData['phone'],
            $bookingData['customerName'],
            $bookingData['roomType'],
            $bookingData['checkIn'],
            $bookingData['checkOut'],
            $bookingData['bookingId']
        );
        $results['customer_sms'] = $smsResult;
        
        // Send notification to manager
        $managerEmailResult = $this->emailNotification->sendManagerNotification(
            $bookingData['customerName'],
            $bookingData['email'],
            $bookingData['phone'],
            $bookingData['roomType'],
            $bookingData['checkIn'],
            $bookingData['checkOut'],
            $bookingData['bookingId'],
            $bookingData['mealPlan'],
            $bookingData['nationality'],
            $bookingData['country']
        );
        $results['manager_email'] = $managerEmailResult;
        
        // Send SMS notification to manager
        $managerSmsResult = $this->smsNotification->sendManagerNotification(
            $this->managerPhone,
            $bookingData['customerName'],
            $bookingData['roomType'],
            $bookingData['checkIn'],
            $bookingData['checkOut'],
            $bookingData['phone'],
            $bookingData['email']
        );
        $results['manager_sms'] = $managerSmsResult;
        
        return $results;
    }
    
    /**
     * Send admin confirmation notifications to customer
     */
    public function sendAdminConfirmationNotifications($email, $customerName, $phone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country, $totalAmount = null) {
        $results = [];
        
        // Send confirmation email to customer
        $emailResult = $this->emailNotification->sendBookingConfirmation(
            $email,
            $customerName,
            $roomType,
            $checkIn,
            $checkOut,
            $bookingId,
            $phone,
            $mealPlan,
            $totalAmount
        );
        $results['customer_email'] = $emailResult;
        
        // Send confirmation SMS to customer
        $smsResult = $this->smsNotification->sendBookingConfirmation(
            $phone,
            $customerName,
            $roomType,
            $checkIn,
            $checkOut,
            $bookingId,
            $totalAmount
        );
        $results['customer_sms'] = $smsResult;
        
        return $results;
    }
    
    /**
     * Send cancellation notifications
     */
    public function sendCancellationNotifications($customerEmail, $customerPhone, $customerName, $bookingId) {
        $results = [];
        
        // Send cancellation email to customer
        $emailResult = $this->emailNotification->sendCancellationEmail(
            $customerEmail,
            $customerName,
            $bookingId
        );
        $results['customer_email'] = $emailResult;
        
        // Send cancellation SMS to customer
        $smsResult = $this->smsNotification->sendCancellationNotification(
            $customerPhone,
            $customerName,
            $bookingId
        );
        $results['customer_sms'] = $smsResult;
        
        return $results;
    }
    
    /**
     * Send check-in reminder
     */
    public function sendCheckInReminder($customerEmail, $customerPhone, $customerName, $checkIn, $roomType) {
        $results = [];
        
        // Send reminder email
        $emailResult = $this->emailNotification->sendCheckInReminder(
            $customerEmail,
            $customerName,
            $checkIn,
            $roomType
        );
        $results['customer_email'] = $emailResult;
        
        // Send reminder SMS
        $smsResult = $this->smsNotification->sendCheckInReminder(
            $customerPhone,
            $customerName,
            $checkIn,
            $roomType
        );
        $results['customer_sms'] = $smsResult;
        
        return $results;
    }
    
    /**
     * Send bulk notifications to all customers
     */
    public function sendBulkNotification($message, $customers) {
        $results = [];
        
        foreach ($customers as $customer) {
            // Send SMS
            $smsResult = $this->smsNotification->sendSMS($customer['phone'], $message);
            $results['sms'][] = $smsResult;
            
            // Send email
            $emailResult = $this->emailNotification->sendEmail(
                $customer['email'],
                'Important Update from RansHotel',
                $this->buildBulkEmailBody($message, $customer['name']),
                $message
            );
            $results['email'][] = $emailResult;
        }
        
        return $results;
    }
    
    /**
     * Build email body for bulk notifications
     */
    private function buildBulkEmailBody($message, $customerName) {
        $year = date('Y');
        
        return "<!doctype html>
<html lang=\"en\">
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
  <title>Important Update - RansHotel</title>
</head>
<body style=\"margin:0; padding:0; background:#f5f7fb;\">
  <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background:#f5f7fb;\">
    <tr>
      <td align=\"center\" style=\"padding:24px;\">
        <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"max-width:640px; background:#ffffff; border-radius:12px; overflow:hidden;\">
          <tr>
            <td style=\"background:#1a73e8; padding:24px 28px; text-align:center;\">
              <h1 style=\"margin:0; font-family:Arial, sans-serif; font-size:20px; color:#ffffff;\">RansHotel</h1>
            </td>
          </tr>
          <tr>
            <td style=\"padding:28px;\">
              <p style=\"margin:0 0 20px; font-family:Arial, sans-serif; font-size:16px; color:#111827;\">Hi <strong>{$customerName}</strong>,</p>
              <p style=\"margin:0 0 20px; font-family:Arial, sans-serif; font-size:15px; color:#374151;\">{$message}</p>
              <p style=\"margin:0; font-family:Arial, sans-serif; font-size:14px; color:#6b7280;\">Contact us: +233 (0)302 936 062 | info@ranshotel.com</p>
            </td>
          </tr>
          <tr>
            <td style=\"background:#f9fafb; padding:18px 28px; text-align:center;\">
              <p style=\"margin:0; font-family:Arial, sans-serif; font-size:12px; color:#6b7280;\">© {$year} RansHotel. All rights reserved.</p>
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
     * Get notification status
     */
    public function getNotificationStatus($results) {
        $status = [
            'total_sent' => 0,
            'total_failed' => 0,
            'details' => []
        ];
        
        foreach ($results as $type => $result) {
            if (is_array($result) && isset($result['success'])) {
                if ($result['success']) {
                    $status['total_sent']++;
                } else {
                    $status['total_failed']++;
                }
                $status['details'][$type] = $result;
            }
        }
        
        return $status;
    }
}
?>
