-- Create requests table for employee requests to admin
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `category` enum('equipment','leave','support','other') NOT NULL DEFAULT 'other',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('pending','approved','rejected','in_review') NOT NULL DEFAULT 'pending',
  `admin_response` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `reviewed_by` (`reviewed_by`),
  FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample request data
INSERT INTO `requests` (`employee_id`, `title`, `message`, `category`, `priority`, `status`) VALUES
(2, 'New Laptop Request', 'My current laptop is running very slow and affecting my productivity. I need a new laptop with better specifications for development work.', 'equipment', 'high', 'pending'),
(7, 'Leave Request for Medical Appointment', 'I need to take a half day leave on Friday for a medical appointment. Please approve my leave request.', 'leave', 'medium', 'pending'),
(2, 'Software Installation Request', 'I need Adobe Creative Suite installed on my workstation for the upcoming design project. Please provide access or installation.', 'support', 'medium', 'approved'),
(8, 'Office Chair Replacement', 'My office chair is broken and causing back pain. I request a replacement ergonomic chair.', 'equipment', 'high', 'in_review'),
(7, 'Remote Work Setup', 'I would like to request work from home setup for 2 days a week. I have a proper home office setup ready.', 'other', 'low', 'rejected');
