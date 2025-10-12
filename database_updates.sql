-- Database Updates for RansHotel Admin System
-- These updates improve security and add new functionality

-- 1. Update the login table to be more secure and feature-rich
ALTER TABLE `login` 
ADD COLUMN `email` VARCHAR(100) DEFAULT NULL AFTER `pass`,
ADD COLUMN `full_name` VARCHAR(100) DEFAULT NULL AFTER `email`,
ADD COLUMN `role` ENUM('admin', 'manager', 'staff') DEFAULT 'staff' AFTER `full_name`,
ADD COLUMN `phone` VARCHAR(20) DEFAULT NULL AFTER `role`,
ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `phone`,
ADD COLUMN `last_login` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`,
ADD COLUMN `is_active` TINYINT(1) DEFAULT 1 AFTER `last_login`,
ADD COLUMN `password_reset_token` VARCHAR(255) DEFAULT NULL AFTER `is_active`,
ADD COLUMN `password_reset_expires` TIMESTAMP NULL DEFAULT NULL AFTER `password_reset_token`;

-- 2. Update existing admin user with better credentials
UPDATE `login` SET 
    `usname` = 'admin',
    `pass` = MD5('RansHotel2024!'),
    `email` = 'eyramdela14@gmail.com',
    `full_name` = 'RansHotel Administrator',
    `role` = 'admin',
    `phone` = '0540202096'
WHERE `id` = 1;

-- 3. Add a new manager user
INSERT INTO `login` (`usname`, `pass`, `email`, `full_name`, `role`, `phone`) VALUES
('manager', MD5('Manager2024!'), 'eyramdela14@gmail.com', 'RansHotel Manager', 'manager', '0540202096');

-- 4. Create a new table for room pricing in Ghanaian Cedis
CREATE TABLE IF NOT EXISTS `room_pricing` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Insert default room pricing in Ghanaian Cedis
INSERT INTO `room_pricing` (`room_type`, `bedding_type`, `price_per_night`, `currency`) VALUES
('Deluxe Room', 'Single', 450.00, 'GHS'),
('Deluxe Room', 'Double', 550.00, 'GHS'),
('Deluxe Room', 'Triple', 650.00, 'GHS'),
('Deluxe Room', 'Quad', 750.00, 'GHS'),
('Superior Room', 'Single', 350.00, 'GHS'),
('Superior Room', 'Double', 450.00, 'GHS'),
('Superior Room', 'Triple', 550.00, 'GHS'),
('Superior Room', 'Quad', 650.00, 'GHS'),
('Guest House', 'Single', 250.00, 'GHS'),
('Guest House', 'Double', 350.00, 'GHS'),
('Guest House', 'Quad', 450.00, 'GHS'),
('Single Room', 'Single', 200.00, 'GHS'),
('Single Room', 'Double', 300.00, 'GHS'),
('Single Room', 'Triple', 400.00, 'GHS');

-- 6. Create a table for meal plan pricing
CREATE TABLE IF NOT EXISTS `meal_pricing` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `meal_plan` varchar(50) NOT NULL,
    `price_per_person_per_day` decimal(10,2) NOT NULL,
    `currency` varchar(3) DEFAULT 'GHS',
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_meal_plan` (`meal_plan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Insert default meal plan pricing in Ghanaian Cedis
INSERT INTO `meal_pricing` (`meal_plan`, `price_per_person_per_day`, `currency`) VALUES
('Room only', 0.00, 'GHS'),
('Breakfast', 25.00, 'GHS'),
('Half Board', 45.00, 'GHS'),
('Full Board', 65.00, 'GHS');

-- 8. Create a table for system settings
CREATE TABLE IF NOT EXISTS `system_settings` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `setting_key` varchar(100) NOT NULL,
    `setting_value` text,
    `setting_type` enum('text', 'number', 'boolean', 'json') DEFAULT 'text',
    `description` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Insert default system settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_type`, `description`) VALUES
('hotel_name', 'RANS HOTEL', 'text', 'Hotel name'),
('hotel_location', 'Tsito, Ghana', 'text', 'Hotel location'),
('hotel_phone', '+233 (0)302 936 062', 'text', 'Hotel phone number'),
('hotel_email', 'info@ranshotel.com', 'text', 'Hotel email address'),
('manager_email', 'eyramdela14@gmail.com', 'text', 'Manager email address'),
('manager_phone', '0540202096', 'text', 'Manager phone number'),
('currency', 'GHS', 'text', 'Default currency'),
('currency_symbol', '₵', 'text', 'Currency symbol'),
('checkin_time', '14:00', 'text', 'Standard check-in time'),
('checkout_time', '11:00', 'text', 'Standard check-out time'),
('tax_rate', '15', 'number', 'Tax rate percentage'),
('service_charge_rate', '10', 'number', 'Service charge percentage'),
('sms_enabled', '1', 'boolean', 'Enable SMS notifications'),
('email_enabled', '1', 'boolean', 'Enable email notifications'),
('auto_reminders', '0', 'boolean', 'Enable automatic check-in reminders');

-- 10. Create a table for activity logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned DEFAULT NULL,
    `action` varchar(100) NOT NULL,
    `description` text,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_action` (`action`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Add foreign key constraints
ALTER TABLE `activity_logs` 
ADD CONSTRAINT `fk_activity_logs_user` 
FOREIGN KEY (`user_id`) REFERENCES `login` (`id`) ON DELETE SET NULL;

-- 12. Update roombook table to include pricing information
ALTER TABLE `roombook` 
ADD COLUMN `room_price` decimal(10,2) DEFAULT NULL AFTER `nodays`,
ADD COLUMN `meal_price` decimal(10,2) DEFAULT NULL AFTER `room_price`,
ADD COLUMN `total_amount` decimal(10,2) DEFAULT NULL AFTER `meal_price`,
ADD COLUMN `tax_amount` decimal(10,2) DEFAULT NULL AFTER `total_amount`,
ADD COLUMN `service_charge` decimal(10,2) DEFAULT NULL AFTER `tax_amount`,
ADD COLUMN `final_amount` decimal(10,2) DEFAULT NULL AFTER `service_charge`,
ADD COLUMN `currency` varchar(3) DEFAULT 'GHS' AFTER `final_amount`,
ADD COLUMN `payment_status` enum('pending', 'paid', 'cancelled', 'refunded') DEFAULT 'pending' AFTER `currency`,
ADD COLUMN `payment_method` varchar(50) DEFAULT NULL AFTER `payment_status`,
ADD COLUMN `created_at` timestamp DEFAULT CURRENT_TIMESTAMP AFTER `payment_method`,
ADD COLUMN `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- 13. Create indexes for better performance
CREATE INDEX `idx_roombook_email` ON `roombook` (`Email`);
CREATE INDEX `idx_roombook_checkin` ON `roombook` (`cin`);
CREATE INDEX `idx_roombook_checkout` ON `roombook` (`cout`);
CREATE INDEX `idx_roombook_status` ON `roombook` (`stat`);
CREATE INDEX `idx_roombook_payment_status` ON `roombook` (`payment_status`);

-- 14. Update payment table to use GHS currency
ALTER TABLE `payment` 
ADD COLUMN `currency` varchar(3) DEFAULT 'GHS' AFTER `noofdays`,
ADD COLUMN `created_at` timestamp DEFAULT CURRENT_TIMESTAMP AFTER `currency`,
ADD COLUMN `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- 15. Create a backup of the old login data (optional)
CREATE TABLE IF NOT EXISTS `login_backup` AS SELECT * FROM `login`;

-- Show completion message
SELECT 'Database updates completed successfully!' as message;
