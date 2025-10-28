-- Add room_number and price columns if they don't exist
ALTER TABLE room ADD COLUMN IF NOT EXISTS room_number VARCHAR(50) DEFAULT NULL;
ALTER TABLE room ADD COLUMN IF NOT EXISTS price DECIMAL(10,2) DEFAULT 0.00;

-- Clear existing rooms
DELETE FROM room;

-- Insert Standard Rooms Ground floor
INSERT INTO room (id, type, bedding, place, cusid, room_number, price) VALUES
(1, 'Standard', 'Single', 'Free', NULL, 'Chad 30', 230),
(2, 'Standard', 'Single', 'Free', NULL, 'Senegal 23', 230),
(3, 'Standard', 'Single', 'Free', NULL, 'Liberia 32', 230),
(4, 'Standard', 'Single', 'Free', NULL, 'Namibia 19', 230),
(5, 'Standard', 'Single', 'Free', NULL, 'Libya 25', 230),
(6, 'Standard', 'Single', 'Free', NULL, 'Morocco 14', 230),
(7, 'Standard', 'Single', 'Free', NULL, 'Algeria 27', 230),
(8, 'Standard', 'Single', 'Free', NULL, 'Gabon 16', 230),
(9, 'Standard', 'Single', 'Free', NULL, 'Equatorial Guinea 29', 230),
(10, 'Standard', 'Single', 'Free', NULL, 'Mali 22', 230),
(11, 'Standard', 'Single', 'Free', NULL, 'Nigeria 31', 230),
(12, 'Standard', 'Single', 'Free', NULL, 'Togo 24', 230),
(13, 'Standard', 'Single', 'Free', NULL, 'Côte d\'Ivoire 21', 230),
(14, 'Standard', 'Single', 'Free', NULL, 'Lesotho 18', 230),
(15, 'Standard', 'Single', 'Free', NULL, 'Egypt 13', 230),
(16, 'Standard', 'Single', 'Free', NULL, 'Tunisia 26', 230),
(17, 'Standard', 'Single', 'Free', NULL, 'Sudan 15', 230),
(18, 'Standard', 'Single', 'Free', NULL, 'Niger 28', 230),
(19, 'Standard', 'Single', 'Free', NULL, 'Botswana 17', 230),
(20, 'Standard', 'Single', 'Free', NULL, 'Chad 30B', 230);

-- Insert Top Floor Mini Executive Rooms
INSERT INTO room (id, type, bedding, place, cusid, room_number, price) VALUES
(21, 'Mini Executive', 'Double', 'Free', NULL, 'Ethiopia 4', 280),
(22, 'Mini Executive', 'Double', 'Free', NULL, 'Rwanda 3', 280),
(23, 'Mini Executive', 'Double', 'Free', NULL, 'Burundi 5', 280),
(24, 'Mini Executive', 'Double', 'Free', NULL, 'Kenya 6', 280),
(25, 'Mini Executive', 'Double', 'Free', NULL, 'Gambia 12', 280),
(26, 'Mini Executive', 'Double', 'Free', NULL, 'Zimbabwe 11', 280),
(27, 'Mini Executive', 'Double', 'Free', NULL, 'Swaziland 10', 280),
(28, 'Mini Executive', 'Double', 'Free', NULL, 'Malawi 9', 280),
(29, 'Mini Executive', 'Double', 'Free', NULL, 'Angola 8', 280),
(30, 'Mini Executive', 'Double', 'Free', NULL, 'DR Congo 7', 280);

-- Insert Top Floor Executive Rooms
INSERT INTO room (id, type, bedding, place, cusid, room_number, price) VALUES
(31, 'Executive', 'King', 'Free', NULL, 'South Africa 2', 300),
(32, 'Executive', 'King', 'Free', NULL, 'Ghana 1', 300);

-- Reset auto increment
ALTER TABLE room AUTO_INCREMENT = 33;
