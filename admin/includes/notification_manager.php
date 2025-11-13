<?php
/**
 * Notification Manager for RansHotel
 * Manages both SMS and Email notifications
 */

require_once 'sms_notification.php';
require_once 'phpmailer_email_system.php';
require_once 'notification_config.php';

class NotificationManager {
    private $smsNotification;
    private $emailNotification;
    private $managerEmail;
    private $managerPhone;
    
    public function __construct() {
        $this->smsNotification = new SMSNotification();
        // Instantiate email system only if enabled to avoid autoload side effects
        $this->emailNotification = (defined('SEND_EMAIL_NOTIFICATIONS') && SEND_EMAIL_NOTIFICATIONS)
            ? new PHPMailerEmailSystem()
            : null;
        $this->managerEmail = defined('MANAGER_EMAIL') ? MANAGER_EMAIL : 'eyramdela14@gmail.com';
        $this->managerPhone = defined('MANAGER_PHONE') ? MANAGER_PHONE : '0540202096';
    }
    
    /**
     * Send reservation notifications to customer (when booking is created with Pending status)
     */
    public function sendReservationNotifications($bookingData) {
        $results = [];
        
        // Send reservation email to customer (only if enabled)
        if ($this->emailNotification && defined('SEND_EMAIL_NOTIFICATIONS') && SEND_EMAIL_NOTIFICATIONS) {
            $emailResult = $this->emailNotification->sendReservationNotification(
            $bookingData['email'],
            $bookingData['customerName'],
            $bookingData['roomType'],
            $bookingData['checkIn'],
            $bookingData['checkOut'],
            $bookingData['bookingId'],
            $bookingData['phone'],
            $bookingData['mealPlan'],
                $bookingData['totalAmount'] ?? null,
                $bookingData['numberOfRooms'] ?? 1
        );
        $results['customer_email'] = $emailResult;
        } else {
            $results['customer_email'] = ['success' => false, 'error' => 'Email disabled'];
        }
        
        // Send reservation SMS to customer
        if (defined('SEND_SMS_NOTIFICATIONS') && SEND_SMS_NOTIFICATIONS) {
            $smsResult = $this->smsNotification->sendReservationNotification(
            $bookingData['phone'],
            $bookingData['customerName'],
            $bookingData['roomType'],
            $bookingData['checkIn'],
            $bookingData['checkOut'],
                $bookingData['bookingId'],
                $bookingData['totalAmount'] ?? null,
                $bookingData['numberOfRooms'] ?? 1
        );
        $results['customer_sms'] = $smsResult;
        } else {
            $results['customer_sms'] = ['success' => false, 'error' => 'SMS disabled'];
        }
        
        // Send notification to manager (only if enabled)
        if ($this->emailNotification && defined('SEND_MANAGER_NOTIFICATIONS') && SEND_MANAGER_NOTIFICATIONS) {
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
        } else {
            $results['manager_email'] = ['success' => false, 'error' => 'Manager email disabled'];
        }
        
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
     * Send confirmation notifications to customer (when booking is confirmed)
     */
    public function sendConfirmationNotifications($bookingData) {
        $results = [];
        
        // Send confirmation email to customer (only if enabled)
        if ($this->emailNotification && defined('SEND_EMAIL_NOTIFICATIONS') && SEND_EMAIL_NOTIFICATIONS) {
        $emailResult = $this->emailNotification->sendBookingConfirmation(
                $bookingData['email'],
                $bookingData['customerName'],
                $bookingData['roomType'],
                $bookingData['checkIn'],
                $bookingData['checkOut'],
                $bookingData['bookingId'],
                $bookingData['phone'],
                $bookingData['mealPlan'] ?? 'Room only',
                $bookingData['totalAmount'] ?? null
        );
        $results['customer_email'] = $emailResult;
        } else {
            $results['customer_email'] = ['success' => false, 'error' => 'Email disabled'];
        }
        
        // Send confirmation SMS to customer
        if (defined('SEND_SMS_NOTIFICATIONS') && SEND_SMS_NOTIFICATIONS) {
        $smsResult = $this->smsNotification->sendBookingConfirmation(
                $bookingData['phone'],
                $bookingData['customerName'],
                $bookingData['roomType'],
                $bookingData['checkIn'],
                $bookingData['checkOut'],
                $bookingData['bookingId'],
                $bookingData['totalAmount'] ?? null
        );
        $results['customer_sms'] = $smsResult;
        } else {
            $results['customer_sms'] = ['success' => false, 'error' => 'SMS disabled'];
        }
        
        return $results;
    }
    
    /**
     * Send admin confirmation notifications to customer (legacy method - kept for compatibility)
     */
    public function sendAdminConfirmationNotifications($email, $customerName, $phone, $roomType, $checkIn, $checkOut, $bookingId, $mealPlan, $nationality, $country, $totalAmount = null) {
        return $this->sendConfirmationNotifications([
            'email' => $email,
            'customerName' => $customerName,
            'phone' => $phone,
            'roomType' => $roomType,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'bookingId' => $bookingId,
            'mealPlan' => $mealPlan,
            'totalAmount' => $totalAmount
        ]);
    }
    
    /**
     * Send cancellation notifications
     */
    public function sendCancellationNotifications($customerEmail, $customerPhone, $customerName, $bookingId) {
        $results = [];
        
        // Send cancellation email to customer (only if enabled)
        if ($this->emailNotification && defined('SEND_EMAIL_NOTIFICATIONS') && SEND_EMAIL_NOTIFICATIONS) {
        $emailResult = $this->emailNotification->sendCancellationEmail(
            $customerEmail,
            $customerName,
            $bookingId
        );
        $results['customer_email'] = $emailResult;
        } else {
            $results['customer_email'] = ['success' => false, 'error' => 'Email disabled'];
        }
        
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
