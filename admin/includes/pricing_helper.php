<?php
/**
 * Pricing Helper for RANS HOTEL
 * Provides functions to get and manage room and meal pricing from database
 */

class PricingHelper {
    
    /**
     * Get room price from database
     */
    public static function getRoomPrice($roomType, $beddingType, $con) {
        $sql = "SELECT price_per_night FROM room_pricing WHERE room_type = ? AND bedding_type = ? AND is_active = 1";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $roomType, $beddingType);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            return $row ? $row['price_per_night'] : 0;
        }
        return 0;
    }
    
    /**
     * Get meal plan price from database
     */
    public static function getMealPrice($mealPlan, $con) {
        $sql = "SELECT price_per_person_per_day FROM meal_pricing WHERE meal_plan = ? AND is_active = 1";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $mealPlan);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            return $row ? $row['price_per_person_per_day'] : 0;
        }
        return 0;
    }
    
    /**
     * Get all room pricing data
     */
    public static function getAllRoomPricing($con) {
        $sql = "SELECT * FROM room_pricing WHERE is_active = 1 ORDER BY room_type, bedding_type";
        $result = mysqli_query($con, $sql);
        $pricing = [];
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $pricing[] = $row;
            }
        }
        
        return $pricing;
    }
    
    /**
     * Get all meal pricing data
     */
    public static function getAllMealPricing($con) {
        $sql = "SELECT * FROM meal_pricing WHERE is_active = 1 ORDER BY meal_plan";
        $result = mysqli_query($con, $sql);
        $pricing = [];
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $pricing[] = $row;
            }
        }
        
        return $pricing;
    }
    
    /**
     * Calculate total booking amount with database pricing
     */
    public static function calculateBookingTotal($roomType, $beddingType, $mealPlan, $days, $rooms, $con, $taxRate = 15, $serviceCharge = 10) {
        $roomPrice = self::getRoomPrice($roomType, $beddingType, $con);
        $mealPrice = self::getMealPrice($mealPlan, $con);
        
        $roomTotal = $roomPrice * $days * $rooms;
        $mealTotal = $mealPrice * $days * $rooms;
        $subtotal = $roomTotal + $mealTotal;
        
        $taxAmount = ($subtotal * $taxRate) / 100;
        $serviceAmount = ($subtotal * $serviceCharge) / 100;
        
        $total = $subtotal + $taxAmount + $serviceAmount;
        
        return [
            'room_price' => $roomPrice,
            'meal_price' => $mealPrice,
            'room_total' => $roomTotal,
            'meal_total' => $mealTotal,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'service_charge' => $serviceAmount,
            'total' => $total
        ];
    }
    
    /**
     * Initialize pricing tables if they don't exist
     */
    public static function initializePricingTables($con) {
        // Check if room_pricing table exists
        $check_room = "SHOW TABLES LIKE 'room_pricing'";
        $result = mysqli_query($con, $check_room);
        
        if (mysqli_num_rows($result) == 0) {
            // Create room_pricing table
            $create_room = "CREATE TABLE `room_pricing` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `room_type` varchar(50) NOT NULL,
                `bedding_type` varchar(20) NOT NULL,
                `price_per_night` decimal(10,2) NOT NULL,
                `currency` varchar(3) DEFAULT 'GHS',
                `is_active` tinyint(1) DEFAULT 1,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_room_bedding` (`room_type`, `bedding_type`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            if (mysqli_query($con, $create_room)) {
                // Insert default room pricing
                $insert_room = "INSERT INTO `room_pricing` (`room_type`, `bedding_type`, `price_per_night`, `currency`) VALUES
                    ('Superior Room', 'Single', 3840.00, 'GHS'),
                    ('Superior Room', 'Double', 4500.00, 'GHS'),
                    ('Superior Room', 'Triple', 5500.00, 'GHS'),
                    ('Superior Room', 'Quad', 6500.00, 'GHS'),
                    ('Deluxe Room', 'Single', 2640.00, 'GHS'),
                    ('Deluxe Room', 'Double', 3200.00, 'GHS'),
                    ('Deluxe Room', 'Triple', 4000.00, 'GHS'),
                    ('Deluxe Room', 'Quad', 4800.00, 'GHS'),
                    ('Guest House', 'Single', 2160.00, 'GHS'),
                    ('Guest House', 'Double', 2600.00, 'GHS'),
                    ('Guest House', 'Triple', 3200.00, 'GHS'),
                    ('Guest House', 'Quad', 3800.00, 'GHS'),
                    ('Single Room', 'Single', 1800.00, 'GHS'),
                    ('Single Room', 'Double', 2200.00, 'GHS'),
                    ('Single Room', 'Triple', 2800.00, 'GHS')";
                
                mysqli_query($con, $insert_room);
            }
        }
        
        // Check if meal_pricing table exists
        $check_meal = "SHOW TABLES LIKE 'meal_pricing'";
        $result = mysqli_query($con, $check_meal);
        
        if (mysqli_num_rows($result) == 0) {
            // Create meal_pricing table
            $create_meal = "CREATE TABLE `meal_pricing` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `meal_plan` varchar(50) NOT NULL,
                `price_per_person_per_day` decimal(10,2) NOT NULL,
                `currency` varchar(3) DEFAULT 'GHS',
                `is_active` tinyint(1) DEFAULT 1,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_meal_plan` (`meal_plan`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            if (mysqli_query($con, $create_meal)) {
                // Insert default meal pricing
                $insert_meal = "INSERT INTO `meal_pricing` (`meal_plan`, `price_per_person_per_day`, `currency`) VALUES
                    ('Room only', 0.00, 'GHS'),
                    ('Breakfast', 25.00, 'GHS'),
                    ('Half Board', 45.00, 'GHS'),
                    ('Full Board', 65.00, 'GHS')";
                
                mysqli_query($con, $insert_meal);
            }
        }
    }
}
?>
