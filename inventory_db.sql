-- Create inventory table for office assets management
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) NOT NULL,
  `category` enum('laptop','desktop','monitor','keyboard','mouse','cable','usb','printer','phone','tablet','other') NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `status` enum('available','assigned','maintenance','damaged','retired') NOT NULL DEFAULT 'available',
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assigned_to` (`assigned_to`),
  FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample inventory data
INSERT INTO `inventory` (`item_name`, `category`, `brand`, `model`, `serial_number`, `assigned_to`, `status`, `purchase_date`, `purchase_price`, `warranty_expiry`, `description`) VALUES
('Dell Laptop', 'laptop', 'Dell', 'Inspiron 15 3000', 'DL123456789', NULL, 'available', '2024-01-15', 850.00, '2027-01-15', 'Core i5, 8GB RAM, 256GB SSD'),
('HP Desktop', 'desktop', 'HP', 'EliteDesk 800', 'HP987654321', NULL, 'available', '2024-01-10', 650.00, '2027-01-10', 'Core i7, 16GB RAM, 512GB SSD'),
('Samsung Monitor', 'monitor', 'Samsung', '24" LED', 'SM246810123', NULL, 'available', '2024-01-12', 200.00, '2027-01-12', '1920x1080 Full HD Display'),
('Logitech Keyboard', 'keyboard', 'Logitech', 'K380', 'LG135792468', NULL, 'available', '2024-01-08', 35.00, '2026-01-08', 'Wireless Bluetooth Keyboard'),
('Logitech Mouse', 'mouse', 'Logitech', 'MX Master 3', 'LG864209753', NULL, 'available', '2024-01-08', 85.00, '2026-01-08', 'Wireless Precision Mouse'),
('USB Cable', 'cable', 'Generic', 'USB-C to USB-A', 'USB123456', NULL, 'available', '2024-01-05', 15.00, NULL, '3ft USB-C to USB-A Cable'),
('SanDisk USB Drive', 'usb', 'SanDisk', 'Ultra 64GB', 'SD789012345', NULL, 'available', '2024-01-20', 25.00, NULL, '64GB USB 3.0 Flash Drive'),
('Canon Printer', 'printer', 'Canon', 'PIXMA TS3320', 'CN456789012', NULL, 'available', '2024-02-01', 120.00, '2026-02-01', 'Wireless All-in-One Printer'),
('iPhone 13', 'phone', 'Apple', 'iPhone 13', 'IP345678901', NULL, 'available', '2024-01-25', 699.00, '2025-01-25', '128GB Company Phone'),
('iPad Air', 'tablet', 'Apple', 'iPad Air 5th Gen', 'IA234567890', NULL, 'available', '2024-02-05', 599.00, '2025-02-05', '64GB WiFi Tablet for presentations');
