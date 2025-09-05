-- Create accounting table for financial management
CREATE TABLE IF NOT EXISTS `accounting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` enum('assets','shares','revenue','expenses','salaries') NOT NULL,
  `type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO `accounting` (`category`, `type`, `description`, `amount`, `date`) VALUES
('assets', 'Equipment', 'Office computers and laptops', 25000.00, '2024-01-15'),
('assets', 'Property', 'Office building rent deposit', 50000.00, '2024-01-01'),
('revenue', 'Client Payment', 'Website development project', 15000.00, '2024-02-10'),
('revenue', 'Service Income', 'Monthly maintenance contract', 5000.00, '2024-02-15'),
('expenses', 'Office Supplies', 'Stationery and printing materials', 2500.00, '2024-02-01'),
('expenses', 'Utilities', 'Electricity and internet bills', 3000.00, '2024-02-05'),
('salaries', 'Developer Salary', 'Monthly salary for senior developer', 8000.00, '2024-02-01'),
('salaries', 'Designer Salary', 'Monthly salary for UI/UX designer', 6000.00, '2024-02-01'),
('shares', 'Company Shares', 'Initial share capital investment', 100000.00, '2024-01-01');
