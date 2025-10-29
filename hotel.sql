-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 06:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phoneno` int(10) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `cdate` date DEFAULT NULL,
  `approval` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(10) UNSIGNED NOT NULL,
  `usname` varchar(30) DEFAULT NULL,
  `pass` varchar(32) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('admin','manager','staff') DEFAULT 'staff',
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `password_reset_expires` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `usname`, `pass`, `email`, `full_name`, `role`, `phone`, `created_at`, `last_login`, `is_active`, `password_reset_token`, `password_reset_expires`) VALUES
(4, 'admin', '81dc9bdb52d04dc20036dbd8313ed055', 'admin@ranshotel.com', 'RansHotel Administrator', 'admin', '0540202096', '2025-10-12 17:57:39', NULL, 1, NULL, NULL),
(5, 'manager', '81dc9bdb52d04dc20036dbd8313ed055', 'manager@ranshotel.com', 'RansHotel Manager', 'manager', '0540202096', '2025-10-12 17:57:39', NULL, 1, NULL, NULL),
(6, 'staff', '81dc9bdb52d04dc20036dbd8313ed055', 'staff@ranshotel.com', 'RansHotel Staff', 'staff', '0540202096', '2025-10-12 17:57:39', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_backup`
--

CREATE TABLE `login_backup` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `usname` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `pass` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `full_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `role` enum('admin','manager','staff') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'staff',
  `phone` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `password_reset_token` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `password_reset_expires` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meal_pricing`
--

CREATE TABLE `meal_pricing` (
  `id` int(10) UNSIGNED NOT NULL,
  `meal_plan` varchar(50) NOT NULL,
  `price_per_person_per_day` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'GHS',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletterlog`
--

CREATE TABLE `newsletterlog` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(52) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `news` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) DEFAULT NULL,
  `title` varchar(5) DEFAULT NULL,
  `fname` varchar(30) DEFAULT NULL,
  `lname` varchar(30) DEFAULT NULL,
  `troom` varchar(30) DEFAULT NULL,
  `tbed` varchar(30) DEFAULT NULL,
  `nroom` int(11) DEFAULT NULL,
  `cin` date DEFAULT NULL,
  `cout` date DEFAULT NULL,
  `ttot` double(8,2) DEFAULT NULL,
  `fintot` double(8,2) DEFAULT NULL,
  `mepr` double(8,2) DEFAULT NULL,
  `meal` varchar(30) DEFAULT NULL,
  `btot` double(8,2) DEFAULT NULL,
  `noofdays` int(11) DEFAULT NULL,
  `currency` varchar(3) DEFAULT 'GHS',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_number` varchar(10) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `bedding` varchar(10) DEFAULT NULL,
  `place` varchar(10) DEFAULT NULL,
  `cusid` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT 1,
  `max_occupancy` int(11) DEFAULT 2,
  `status` varchar(20) DEFAULT 'Available',
  `amenities` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `room_number`, `type`, `bedding`, `place`, `cusid`, `floor`, `max_occupancy`, `status`, `amenities`, `price`) VALUES
(1, 'Chad 30', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(2, 'Senegal 23', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(3, 'Liberia 32', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(4, 'Namibia 19', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(5, 'Libya 25', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(6, 'Morocco 14', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(7, 'Algeria 27', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(8, 'Gabon 16', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(9, 'Equatorial', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(10, 'Mali 22', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(11, 'Nigeria 31', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(12, 'Togo 24', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(13, 'Côte d\'Ivo', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(14, 'Lesotho 18', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(15, 'Egypt 13', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(16, 'Tunisia 26', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(17, 'Sudan 15', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(18, 'Niger 28', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(19, 'Botswana 1', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(20, 'Chad 30B', 'Standard', 'Single', 'Free', NULL, 1, 2, 'Available', NULL, 230.00),
(21, 'Ethiopia 4', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(22, 'Rwanda 3', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(23, 'Burundi 5', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(24, 'Kenya 6', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(25, 'Gambia 12', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(26, 'Zimbabwe 1', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(27, 'Swaziland ', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(28, 'Malawi 9', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(29, 'Angola 8', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(30, 'DR Congo 7', 'Mini Executive', 'Double', 'Free', NULL, 1, 2, 'Available', NULL, 280.00),
(31, 'South Afri', 'Executive', 'King', 'Free', NULL, 1, 2, 'Available', NULL, 300.00),
(32, 'Ghana 1', 'Executive', 'King', 'Free', NULL, 1, 2, 'Available', NULL, 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `roombook`
--

CREATE TABLE `roombook` (
  `id` int(10) UNSIGNED NOT NULL,
  `Title` varchar(5) DEFAULT NULL,
  `FName` text DEFAULT NULL,
  `LName` text DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `National` varchar(30) DEFAULT NULL,
  `Country` varchar(30) DEFAULT NULL,
  `Phone` text DEFAULT NULL,
  `TRoom` varchar(50) DEFAULT NULL,
  `Bed` varchar(10) DEFAULT NULL,
  `NRoom` varchar(2) DEFAULT NULL,
  `Meal` varchar(15) DEFAULT NULL,
  `cin` date DEFAULT NULL,
  `cout` date DEFAULT NULL,
  `stat` varchar(15) DEFAULT NULL,
  `nodays` int(11) DEFAULT NULL,
  `room_price` decimal(10,2) DEFAULT NULL,
  `meal_price` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `service_charge` decimal(10,2) DEFAULT NULL,
  `final_amount` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) DEFAULT 'GHS',
  `payment_status` enum('pending','paid','cancelled','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_availability`
--

CREATE TABLE `room_availability` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('available','occupied','maintenance','reserved') DEFAULT 'available',
  `booking_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_pricing`
--

CREATE TABLE `room_pricing` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `bedding_type` varchar(20) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'GHS',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_pricing`
--

INSERT INTO `room_pricing` (`id`, `room_type`, `bedding_type`, `price_per_night`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
(19, 'Standard', 'Single', 230.00, 'GHS', 1, '2025-10-25 23:01:45', '2025-10-25 23:01:45'),
(20, 'Mini Executive', 'Single', 280.00, 'GHS', 1, '2025-10-25 23:01:45', '2025-10-25 23:01:45'),
(21, 'Executive', 'Single', 300.00, 'GHS', 1, '2025-10-25 23:01:45', '2025-10-25 23:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `room_type_descriptions`
--

CREATE TABLE `room_type_descriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `max_occupancy` int(3) DEFAULT 1,
  `room_size` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_type_descriptions`
--

INSERT INTO `room_type_descriptions` (`id`, `room_type`, `description`, `amenities`, `max_occupancy`, `room_size`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Standard', 'Comfortable standard rooms with essential amenities for a pleasant stay', 'Air conditioning, TV, WiFi, Private bathroom, Daily housekeeping', 1, '25 sqm', 1, '2025-10-25 23:01:27', '2025-10-25 23:01:27'),
(2, 'Mini Executive', 'Enhanced rooms with additional comfort features and better views', 'Air conditioning, TV, WiFi, Private bathroom, Mini fridge, Work desk, Daily housekeeping', 1, '30 sqm', 1, '2025-10-25 23:01:27', '2025-10-25 23:01:27'),
(3, 'Executive', 'Premium rooms with luxury amenities and superior comfort', 'Air conditioning, TV, WiFi, Private bathroom, Mini fridge, Work desk, Room service, Daily housekeeping, Premium toiletries', 1, '35 sqm', 1, '2025-10-25 23:01:27', '2025-10-25 23:01:27');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','number','boolean','json') DEFAULT 'text',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'hotel_name', 'RansHotel', 'text', 'Hotel name', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(2, 'hotel_location', 'Tsito, Ghana', 'text', 'Hotel location', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(3, 'hotel_phone', '+233 (0)302 936 062', 'text', 'Hotel phone number', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(4, 'hotel_email', 'info@ranshotel.com', 'text', 'Hotel email address', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(5, 'manager_email', 'eyramdela14@gmail.com', 'text', 'Manager email address', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(6, 'manager_phone', '0540202096', 'text', 'Manager phone number', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(7, 'currency', 'GHS', 'text', 'Default currency', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(8, 'currency_symbol', '₵', 'text', 'Currency symbol', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(9, 'checkin_time', '14:00', 'text', 'Standard check-in time', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(10, 'checkout_time', '11:00', 'text', 'Standard check-out time', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(11, 'tax_rate', '15', 'number', 'Tax rate percentage', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(12, 'service_charge_rate', '10', 'number', 'Service charge percentage', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(13, 'sms_enabled', '1', 'boolean', 'Enable SMS notifications', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(14, 'email_enabled', '1', 'boolean', 'Enable email notifications', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(15, 'auto_reminders', '0', 'boolean', 'Enable automatic check-in reminders', '2025-10-12 06:14:39', '2025-10-12 06:14:39'),
(16, 'standard_rooms_count', '20', 'number', 'Number of Standard rooms available', '2025-10-25 23:01:27', '2025-10-25 23:01:27'),
(17, 'mini_executive_rooms_count', '10', 'number', 'Number of Mini Executive rooms available', '2025-10-25 23:01:27', '2025-10-25 23:01:27'),
(18, 'executive_rooms_count', '2', 'number', 'Number of Executive rooms available', '2025-10-25 23:01:27', '2025-10-25 23:01:27'),
(19, 'room_structure_updated', '1', 'boolean', 'Room structure has been updated to new format', '2025-10-25 23:01:27', '2025-10-25 23:01:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meal_pricing`
--
ALTER TABLE `meal_pricing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_meal_plan` (`meal_plan`);

--
-- Indexes for table `newsletterlog`
--
ALTER TABLE `newsletterlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_room_type` (`type`),
  ADD KEY `idx_room_status` (`place`);

--
-- Indexes for table `roombook`
--
ALTER TABLE `roombook`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_roombook_email` (`Email`),
  ADD KEY `idx_roombook_checkin` (`cin`),
  ADD KEY `idx_roombook_checkout` (`cout`),
  ADD KEY `idx_roombook_status` (`stat`),
  ADD KEY `idx_roombook_payment_status` (`payment_status`);

--
-- Indexes for table `room_availability`
--
ALTER TABLE `room_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_date` (`room_id`,`date`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `room_pricing`
--
ALTER TABLE `room_pricing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_bedding` (`room_type`,`bedding_type`),
  ADD KEY `idx_room_pricing_type` (`room_type`);

--
-- Indexes for table `room_type_descriptions`
--
ALTER TABLE `room_type_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_type` (`room_type`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_setting_key` (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `meal_pricing`
--
ALTER TABLE `meal_pricing`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsletterlog`
--
ALTER TABLE `newsletterlog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `roombook`
--
ALTER TABLE `roombook`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_availability`
--
ALTER TABLE `room_availability`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_pricing`
--
ALTER TABLE `room_pricing`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `room_type_descriptions`
--
ALTER TABLE `room_type_descriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activity_logs_user` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `room_availability`
--
ALTER TABLE `room_availability`
  ADD CONSTRAINT `fk_room_availability_room` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
