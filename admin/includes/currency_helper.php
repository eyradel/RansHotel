<?php
/**
 * Currency Helper for RANS HOTEL
 * Provides functions to format and display prices in Ghanaian Cedis
 */

class CurrencyHelper {
    const CURRENCY_SYMBOL = '₵';
    const CURRENCY_CODE = 'GHS';
    
    /**
     * Format a price with Ghanaian Cedis symbol
     */
    public static function formatPrice($amount, $showSymbol = true) {
        $formatted = number_format($amount, 2);
        return $showSymbol ? self::CURRENCY_SYMBOL . $formatted : $formatted;
    }
    
    /**
     * Format a price for display in tables
     */
    public static function formatTablePrice($amount) {
        return self::CURRENCY_SYMBOL . number_format($amount, 2);
    }
    
    /**
     * Get currency symbol
     */
    public static function getSymbol() {
        return self::CURRENCY_SYMBOL;
    }
    
    /**
     * Get currency code
     */
    public static function getCode() {
        return self::CURRENCY_CODE;
    }
    
    /**
     * Convert USD to GHS (approximate rate)
     */
    public static function convertUSDToGHS($usdAmount) {
        // Approximate conversion rate (1 USD = 12 GHS)
        return $usdAmount * 12;
    }
    
    /**
     * Get room pricing from database
     */
    public static function getRoomPrice($roomType, $beddingType, $con) {
        $sql = "SELECT price_per_night FROM room_pricing WHERE room_type = ? AND bedding_type = ? AND is_active = 1";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $roomType, $beddingType);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return $row ? $row['price_per_night'] : 0;
    }
    
    /**
     * Get meal plan pricing from database
     */
    public static function getMealPrice($mealPlan, $con) {
        $sql = "SELECT price_per_person_per_day FROM meal_pricing WHERE meal_plan = ? AND is_active = 1";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $mealPlan);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        return $row ? $row['price_per_person_per_day'] : 0;
    }
    
    /**
     * Calculate total booking amount
     */
    public static function calculateBookingTotal($roomPrice, $mealPrice, $days, $rooms, $taxRate = 15, $serviceCharge = 10) {
        $roomTotal = $roomPrice * $days * $rooms;
        $mealTotal = $mealPrice * $days * $rooms;
        $subtotal = $roomTotal + $mealTotal;
        
        $taxAmount = ($subtotal * $taxRate) / 100;
        $serviceAmount = ($subtotal * $serviceCharge) / 100;
        
        $total = $subtotal + $taxAmount + $serviceAmount;
        
        return [
            'room_total' => $roomTotal,
            'meal_total' => $mealTotal,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'service_charge' => $serviceAmount,
            'total' => $total
        ];
    }
    
    /**
     * Format price for email/SMS notifications
     */
    public static function formatNotificationPrice($amount) {
        return self::CURRENCY_SYMBOL . number_format($amount, 0); // No decimals for notifications
    }
}
?>
