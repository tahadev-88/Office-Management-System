-- Create clients table for Digitazio Panel
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) NOT NULL,
  `contact_info` text NOT NULL,
  `salesman` varchar(255) NOT NULL,
  `platform` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_platform` (`platform`),
  INDEX `idx_salesman` (`salesman`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (optional)
INSERT INTO `clients` (`client_name`, `contact_info`, `salesman`, `platform`) VALUES
('ABC Corporation', 'Phone: +1-555-0123\nEmail: contact@abccorp.com\nAddress: 123 Business St, City, State 12345', 'John Smith', 'Website'),
('XYZ Marketing', 'Phone: +1-555-0456\nEmail: info@xyzmarketing.com\nLinkedIn: linkedin.com/company/xyz-marketing', 'Sarah Johnson', 'Social Media'),
('Tech Solutions Inc', 'Phone: +1-555-0789\nEmail: sales@techsolutions.com\nAddress: 456 Tech Park, Innovation City, State 67890', 'Mike Davis', 'Referral');
