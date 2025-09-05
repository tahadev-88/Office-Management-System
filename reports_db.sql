-- Create reports table for storing generated reports
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` enum('employee_month','employee_year','accounting','tasks','sales') NOT NULL,
  `period` varchar(20) NOT NULL,
  `year` int(4) NOT NULL,
  `month` int(2) DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `generated_by` int(11) NOT NULL,
  `status` enum('generating','completed','failed') NOT NULL DEFAULT 'generating',
  `data_summary` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `generated_by` (`generated_by`),
  KEY `type_period` (`type`, `year`, `month`),
  FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample report data
INSERT INTO `reports` (`title`, `type`, `period`, `year`, `month`, `file_path`, `file_size`, `generated_by`, `status`, `data_summary`) VALUES
('Employee of the Month - August 2024', 'employee_month', 'August 2024', 2024, 8, 'reports/employee_month_2024_08.docx', 45632, 1, 'completed', 'Top performer: John with 15 completed tasks'),
('Accounting Report - Q3 2024', 'accounting', 'Q3 2024', 2024, NULL, 'reports/accounting_q3_2024.docx', 78945, 1, 'completed', 'Total Revenue: $125,000, Total Expenses: $85,000'),
('Tasks Summary - July 2024', 'tasks', 'July 2024', 2024, 7, 'reports/tasks_2024_07.docx', 32145, 1, 'completed', '45 tasks completed, 12 pending, 3 overdue'),
('Employee of the Year 2023', 'employee_year', '2023', 2023, NULL, 'reports/employee_year_2023.docx', 67890, 1, 'completed', 'Top performer: Elias A. with exceptional performance'),
('Sales Report - August 2024', 'sales', 'August 2024', 2024, 8, 'reports/sales_2024_08.docx', 54321, 1, 'completed', 'Total Sales: $95,000, New Clients: 8, Conversion Rate: 65%');
