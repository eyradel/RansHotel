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
        // SMS API Configuration
        $this->apiKey = "qqYaIprq4RZ25q9JENdRqQbKZ"; // Your SMS API key
        $this->senderId = "RansHotel";
        $this->baseUrl = "https://apps.mnotify.net/smsapi";
    }
    
    /**
     * Send SMS to customer about reservation received (Pending status)
     */
    public function sendReservationNotification($phone, $customerName, $roomType, $checkIn, $checkOut, $bookingId, $totalAmount = null) {
        $priceInfo = $totalAmount ? " Total: ₵" . number_format($totalAmount, 0) : "";
        $message = "Hi {$customerName}, your reservation at RansHotel has been received! " .
                  "Room: {$roomType}, Check-in: {$checkIn}, Check-out: {$checkOut}. " .
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
     * Core SMS sending function
     */
    public function sendSMS($phone, $message) {
        // Clean phone number (remove spaces, dashes, etc.)
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Ensure phone number starts with country code
        if (substr($phone, 0, 1) !== '+') {
            if (substr($phone, 0, 3) === '233') {
                $phone = '+' . $phone;
            } else {
                $phone = '+233' . ltrim($phone, '0');
            }
        }
        
        $encodedMessage = urlencode($message);
        $url = "{$this->baseUrl}?key={$this->apiKey}&to={$phone}&msg={$encodedMessage}&sender_id={$this->senderId}";
        
        try {
            $response = file_get_contents($url);
            return [
                'success' => true,
                'response' => $response,
                'phone' => $phone,
                'message' => $message
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'phone' => $phone,
                'message' => $message
            ];
        }
    }
    
    /**
     * Send bulk SMS to multiple customers
     */
    public function sendBulkSMS($recipients, $message) {
        $results = [];
        foreach ($recipients as $phone) {
            $results[] = $this->sendSMS($phone, $message);
        }
        return $results;
    }
}
?>
