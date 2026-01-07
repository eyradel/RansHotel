<?php
/**
 * SMS Notification System for RansHotel
 * Sends SMS notifications for bookings and reservations
 */

class SMSNotification {
    private $apiKey;
    private $senderId;
    private $baseUrl;
    
    public function __construct() {
        // SMS API Configuration - mNotify API v2
        $this->apiKey = "DdvlHWQz25LP9a8mmGwgGSjxU"; // Your SMS API key
        $this->senderId = "Rans Hotel"; // Sender ID
        $this->baseUrl = "https://api.mnotify.com/api/sms/quick";
    }
    
    /**
     * Send SMS to customer about reservation received (Pending status)
     */
    public function sendReservationNotification($phone, $customerName, $roomType, $checkIn, $checkOut, $bookingId, $totalAmount = null, $numberOfRooms = 1) {
        $priceInfo = $totalAmount ? " Total: ₵" . number_format($totalAmount, 0) : "";
        $roomsInfo = $numberOfRooms > 1 ? " {$numberOfRooms} × {$roomType}" : " {$roomType}";
        $message = "Hi {$customerName}, your reservation at RansHotel has been received! " .
                  "Room" . ($numberOfRooms > 1 ? "s" : "") . ":{$roomsInfo}, Check-in: {$checkIn}, Check-out: {$checkOut}. " .
                  "Reservation ID: {$bookingId}.{$priceInfo} We will confirm it shortly. Thank you for choosing RansHotel!";
        
        return $this->sendSMS($phone, $message);
    }
    
    /**
     * Send SMS to customer about booking confirmation (Confirmed status)
     */
    public function sendBookingConfirmation($phone, $customerName, $roomType, $checkIn, $checkOut, $bookingId, $totalAmount = null) {
        $priceInfo = $totalAmount ? " Total: ₵" . number_format($totalAmount, 0) : "";
        $message = "Hi {$customerName}, great news! Your booking at RansHotel has been confirmed! " .
                  "Room: {$roomType}, Check-in: {$checkIn}, Check-out: {$checkOut}. " .
                  "Booking ID: {$bookingId}.{$priceInfo} We look forward to welcoming you! Thank you for choosing RansHotel!";
        
        return $this->sendSMS($phone, $message);
    }
    
    /**
     * Send SMS to customer about booking status: Processing
     */
    public function sendProcessingNotification($phone, $customerName, $roomType, $checkIn, $checkOut, $bookingId) {
        $message = "Hi {$customerName}, your reservation at RansHotel is being processed! " .
                  "Room: {$roomType}, Check-in: {$checkIn}, Check-out: {$checkOut}. " .
                  "Reservation ID: {$bookingId}. We will update you shortly. Thank you for choosing RansHotel!";
        
        return $this->sendSMS($phone, $message);
    }
    
    /**
     * Send SMS to customer about booking status: Pending
     */
    public function sendPendingNotification($phone, $customerName, $roomType, $checkIn, $checkOut, $bookingId, $totalAmount = null) {
        $priceInfo = $totalAmount ? " Total: ₵" . number_format($totalAmount, 0) . ". " : "";
        $message = "Hi {$customerName}, your reservation at RansHotel is pending review. " .
                  "Room: {$roomType}, Check-in: {$checkIn}, Check-out: {$checkOut}. " .
                  "Reservation ID: {$bookingId}.{$priceInfo}We will confirm it shortly. Thank you for choosing RansHotel!";
        
        return $this->sendSMS($phone, $message);
    }
    
    /**
     * Send SMS to customer about booking status: Declined
     */
    public function sendDeclinedNotification($phone, $customerName, $bookingId, $reason = null) {
        $reasonText = $reason ? " Reason: {$reason}. " : "";
        $message = "Hi {$customerName}, we regret to inform you that your reservation {$bookingId} at RansHotel has been declined.{$reasonText}" .
                  "Please contact us at +233 (0)302 936 062 for assistance or to make a new reservation. Thank you.";
        
        return $this->sendSMS($phone, $message);
    }
    
    /**
     * Send SMS to manager about new booking
     */
    public function sendManagerNotification($managerPhone, $customerName, $roomType, $checkIn, $checkOut, $phone, $email) {
        $message = "New booking at RansHotel! Customer: {$customerName}, " .
                  "Room: {$roomType}, Check-in: {$checkIn}, Check-out: {$checkOut}. " .
                  "Contact: {$phone}, Email: {$email}";
        
        return $this->sendSMS($managerPhone, $message);
    }
    
    /**
     * Send SMS to customer about booking cancellation
     */
    public function sendCancellationNotification($phone, $customerName, $bookingId) {
        $message = "Hi {$customerName}, your booking {$bookingId} at RansHotel has been cancelled. " .
                  "If you have any questions, please contact us at +233 (0)302 936 062";
        
        return $this->sendSMS($phone, $message);
    }
    
    /**
     * Send SMS to customer about booking modification
     */
    public function sendModificationNotification($phone, $customerName, $bookingId, $changes) {
        $message = "Hi {$customerName}, your booking {$bookingId} at RansHotel has been updated. " .
                  "Changes: {$changes}. Contact us at +233 (0)302 936 062 for any questions.";
        
        return $this->sendSMS($phone, $message);
    }
    
    /**
     * Send reminder SMS before check-in
     */
    public function sendCheckInReminder($phone, $customerName, $checkIn, $roomType) {
        $message = "Hi {$customerName}, this is a reminder that your check-in at RansHotel is tomorrow ({$checkIn}). " .
                  "Room: {$roomType}. We look forward to welcoming you! Contact: +233 (0)302 936 062";
        
        return $this->sendSMS($phone, $message);
    }
    
    /**
     * Core SMS sending function - mNotify API v2
     */
    public function sendSMS($phone, $message) {
        // Clean phone number (remove spaces, dashes, plus signs, etc.)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Format phone number for Ghana (should be like 0241234567 or 0201234567)
        // If starts with 233, remove it and add 0
        if (substr($phone, 0, 3) === '233') {
            $phone = '0' . substr($phone, 3);
        }
        // If doesn't start with 0, add it
        if (substr($phone, 0, 1) !== '0') {
            $phone = '0' . $phone;
        }
        
        // Build URL with API key as query parameter
        $url = $this->baseUrl . '?key=' . urlencode($this->apiKey);
        
        // Prepare JSON payload according to mNotify API v2 format
        $data = [
            'recipient' => [$phone], // Array of phone numbers
            'sender' => $this->senderId,
            'message' => $message,
            'is_schedule' => false,
            'schedule_date' => ''
        ];
        
        // Use cURL for POST request with JSON payload
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // Check for cURL errors
        if ($response === false || !empty($curlError)) {
            return [
                'success' => false,
                'error' => $curlError ?: 'cURL request failed',
                'phone' => $phone,
                'message' => $message,
                'http_code' => $httpCode
            ];
        }
        
        // Decode JSON response
        $result = json_decode($response, true);
        
        // Check HTTP status code
        if ($httpCode !== 200) {
            $errorMsg = isset($result['message']) ? $result['message'] : $response;
            return [
                'success' => false,
                'error' => "HTTP Error {$httpCode}: {$errorMsg}",
                'phone' => $phone,
                'message' => $message,
                'http_code' => $httpCode,
                'response' => $result
            ];
        }
        
        // Check API response status
        if (isset($result['status']) && $result['status'] === 'success') {
            return [
                'success' => true,
                'response' => $result,
                'phone' => $phone,
                'message' => $message,
                'http_code' => $httpCode
            ];
        } else {
            $errorMsg = isset($result['message']) ? $result['message'] : 'Unknown API error';
            return [
                'success' => false,
                'error' => $errorMsg,
                'phone' => $phone,
                'message' => $message,
                'http_code' => $httpCode,
                'response' => $result
            ];
        }
    }
    
    /**
     * Send bulk SMS to multiple customers - optimized for mNotify API v2
     */
    public function sendBulkSMS($recipients, $message) {
        // Format all phone numbers
        $formattedRecipients = [];
        foreach ($recipients as $phone) {
            // Clean phone number
            $phone = preg_replace('/[^0-9]/', '', $phone);
            
            // Format for Ghana
            if (substr($phone, 0, 3) === '233') {
                $phone = '0' . substr($phone, 3);
            }
            if (substr($phone, 0, 1) !== '0') {
                $phone = '0' . $phone;
            }
            
            $formattedRecipients[] = $phone;
        }
        
        // Build URL with API key
        $url = $this->baseUrl . '?key=' . urlencode($this->apiKey);
        
        // Prepare JSON payload for bulk send
        $data = [
            'recipient' => $formattedRecipients, // Array of all phone numbers
            'sender' => $this->senderId,
            'message' => $message,
            'is_schedule' => false,
            'schedule_date' => ''
        ];
        
        // Use cURL for POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($response === false || !empty($curlError)) {
            return [
                'success' => false,
                'error' => $curlError ?: 'cURL request failed',
                'http_code' => $httpCode
            ];
        }
        
        $result = json_decode($response, true);
        
        if ($httpCode === 200 && isset($result['status']) && $result['status'] === 'success') {
            return [
                'success' => true,
                'response' => $result,
                'recipients_count' => count($formattedRecipients)
            ];
        } else {
            return [
                'success' => false,
                'error' => isset($result['message']) ? $result['message'] : 'Unknown error',
                'response' => $result,
                'http_code' => $httpCode
            ];
        }
    }
}
?>
