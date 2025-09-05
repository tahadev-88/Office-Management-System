<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {

if (isset($_POST['type']) && isset($_POST['year'])) {
	include "../DB_connection.php";
    include "Model/Report.php";
    include "Model/Accounting.php";
    include "Model/User.php";
    include "Model/Task.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$type = validate_input($_POST['type']);
	$year = validate_input($_POST['year']);
	$month = !empty($_POST['month']) ? validate_input($_POST['month']) : null;
	$admin_id = $_SESSION['id'];

	if (empty($type)) {
		echo json_encode(['success' => false, 'message' => 'Report type is required']);
	    exit();
	}else if (empty($year)) {
		echo json_encode(['success' => false, 'message' => 'Year is required']);
	    exit();
	}else {
        
        // Create reports directory if it doesn't exist
        $reports_dir = "../reports/";
        if (!file_exists($reports_dir)) {
            mkdir($reports_dir, 0777, true);
        }

        // Generate report title and filename
        $months = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        switch($type) {
            case 'employee_month':
                if (!$month) {
                    echo json_encode(['success' => false, 'message' => 'Month is required for monthly reports']);
                    exit();
                }
                $title = "Employee of the Month - " . $months[$month] . " " . $year;
                $period = $months[$month] . " " . $year;
                $filename = "employee_month_" . $year . "_" . sprintf("%02d", $month) . ".docx";
                break;
            case 'employee_year':
                $title = "Employee of the Year " . $year;
                $period = $year;
                $filename = "employee_year_" . $year . ".docx";
                break;
            case 'accounting':
                if (!$month) {
                    echo json_encode(['success' => false, 'message' => 'Month is required for accounting reports']);
                    exit();
                }
                $title = "Accounting Report - " . $months[$month] . " " . $year;
                $period = $months[$month] . " " . $year;
                $filename = "accounting_" . $year . "_" . sprintf("%02d", $month) . ".docx";
                break;
            case 'tasks':
                if (!$month) {
                    echo json_encode(['success' => false, 'message' => 'Month is required for tasks reports']);
                    exit();
                }
                $title = "Tasks Report - " . $months[$month] . " " . $year;
                $period = $months[$month] . " " . $year;
                $filename = "tasks_" . $year . "_" . sprintf("%02d", $month) . ".docx";
                break;
            case 'sales':
                if (!$month) {
                    echo json_encode(['success' => false, 'message' => 'Month is required for sales reports']);
                    exit();
                }
                $title = "Sales Report - " . $months[$month] . " " . $year;
                $period = $months[$month] . " " . $year;
                $filename = "sales_" . $year . "_" . sprintf("%02d", $month) . ".docx";
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid report type']);
                exit();
        }

        $file_path = "reports/" . $filename;
        
        // Insert report record first
        $data = array($title, $type, $period, $year, $month, $file_path, null, $admin_id, 'generating', null);
        $report_id = insert_report($conn, $data);

        if ($report_id) {
            try {
                // Generate the actual report content
                $report_content = generateReportContent($conn, $type, $year, $month);
                
                // Create Word document
                $success = createWordDocument($reports_dir . $filename, $title, $report_content);
                
                if ($success) {
                    $file_size = filesize($reports_dir . $filename);
                    update_report_status($conn, $report_id, 'completed', $file_path, $file_size);
                    echo json_encode(['success' => true, 'message' => 'Report generated successfully']);
                } else {
                    update_report_status($conn, $report_id, 'failed');
                    echo json_encode(['success' => false, 'message' => 'Failed to create Word document']);
                }
            } catch (Exception $e) {
                update_report_status($conn, $report_id, 'failed');
                echo json_encode(['success' => false, 'message' => 'Error generating report: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create report record']);
        }
        exit();
	}
}else {
   echo json_encode(['success' => false, 'message' => 'Invalid request data']);
   exit();
}

}else{ 
   echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
   exit();
}

function generateReportContent($conn, $type, $year, $month) {
    $content = [];
    
    try {
        switch($type) {
            case 'employee_month':
            case 'employee_year':
                $performance = get_employee_performance($conn, $year, $month);
                
                $content['title'] = $type == 'employee_month' ? 'Employee of the Month Report' : 'Employee of the Year Report';
                $content['period'] = $month ? date('F Y', mktime(0, 0, 0, $month, 1, $year)) : $year;
                $content['data'] = $performance;
                $content['summary'] = $performance && count($performance) > 0 ? 
                    "Top performer: " . $performance[0]['full_name'] . " with " . $performance[0]['completed_tasks'] . " completed tasks" : 
                    "No performance data available";
                break;
            
        case 'accounting':
            $entries = get_all_accounting_entries($conn);
            $summary = get_financial_summary($conn);
            
            $content['title'] = 'Accounting Report';
            $content['period'] = $month ? date('F Y', mktime(0, 0, 0, $month, 1, $year)) : $year;
            $content['data'] = $entries;
            $content['summary'] = is_array($summary) ? 
                "Total Revenue: PKR " . number_format($summary['total_revenue']) . ", Total Expenses: PKR " . number_format($summary['total_expenses']) :
                "No accounting data available";
            break;
            
        case 'tasks':
            $tasks_summary = get_tasks_summary($conn, $year, $month);
            
            $content['title'] = 'Tasks Report';
            $content['period'] = $month ? date('F Y', mktime(0, 0, 0, $month, 1, $year)) : $year;
            $content['data'] = $tasks_summary;
            $content['summary'] = $tasks_summary['total_tasks'] . " total tasks, " . 
                                 $tasks_summary['completed_tasks'] . " completed, " . 
                                 $tasks_summary['pending_tasks'] . " pending";
            break;
            
        case 'sales':
            // Mock sales data for demonstration
            $content['title'] = 'Sales Report';
            $content['period'] = $month ? date('F Y', mktime(0, 0, 0, $month, 1, $year)) : $year;
            $content['data'] = [
                'total_sales' => rand(80000, 150000),
                'new_clients' => rand(5, 15),
                'conversion_rate' => rand(50, 80),
                'revenue_growth' => rand(10, 25)
            ];
            $content['summary'] = "Total Sales: PKR " . number_format($content['data']['total_sales']) . 
                                ", New Clients: " . $content['data']['new_clients'] . 
                                ", Conversion Rate: " . $content['data']['conversion_rate'] . "%";
            break;
        }
    } catch (Exception $e) {
        $content['title'] = 'Error Report';
        $content['period'] = $month ? date('F Y', mktime(0, 0, 0, $month, 1, $year)) : $year;
        $content['data'] = [];
        $content['summary'] = 'Error generating report data: ' . $e->getMessage();
    }
    
    return $content;
}

function createWordDocument($filepath, $title, $content) {
    try {
        // Include Composer autoloader
        require_once '../vendor/autoload.php';
        
        // Create new PHPWord instance
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // Add document properties
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Digitazio Panel');
        $properties->setCompany('Digitazio');
        $properties->setTitle($title);
        $properties->setDescription('Generated report from Digitazio Panel');
        
        // Create a section
        $section = $phpWord->addSection();
        
        // Add title
        $section->addText($title, array('size' => 18, 'bold' => true, 'color' => '8CC63F'));
        $section->addTextBreak(1);
        
        // Add header info
        $section->addText('Period: ' . $content['period'], array('size' => 12));
        $section->addText('Generated: ' . date('F j, Y \a\t g:i A'), array('size' => 12));
        $section->addText('Generated by: Digitazio Panel Admin', array('size' => 12));
        $section->addTextBreak(2);
        
        // Add summary
        $section->addText('Executive Summary', array('size' => 14, 'bold' => true));
        $section->addText($content['summary'], array('size' => 12));
        $section->addTextBreak(2);
        
        // Add detailed information
        if (isset($content['data']) && is_array($content['data']) && !empty($content['data'])) {
            $section->addText('Detailed Information', array('size' => 14, 'bold' => true));
            $section->addTextBreak(1);
            
            if (isset($content['data'][0]) && is_array($content['data'][0])) {
                // Create table
                $table = $section->addTable(array('borderSize' => 6, 'borderColor' => '999999'));
                
                // Add header row
                $table->addRow();
                foreach (array_keys($content['data'][0]) as $header) {
                    $table->addCell(2000)->addText(ucwords(str_replace('_', ' ', $header)), array('bold' => true));
                }
                
                // Add data rows
                foreach ($content['data'] as $row) {
                    $table->addRow();
                    foreach ($row as $value) {
                        $table->addCell(2000)->addText($value);
                    }
                }
            } else {
                // Key-value pairs
                $table = $section->addTable(array('borderSize' => 6, 'borderColor' => '999999'));
                foreach ($content['data'] as $key => $value) {
                    $table->addRow();
                    $table->addCell(3000)->addText(ucwords(str_replace('_', ' ', $key)), array('bold' => true));
                    $table->addCell(3000)->addText($value);
                }
            }
        }
        
        // Add footer
        $section->addTextBreak(3);
        $section->addText('This report was automatically generated by the Digitazio Panel system.', array('size' => 10, 'italic' => true));
        $section->addText('For questions or concerns, please contact the system administrator.', array('size' => 10, 'italic' => true));
        
        // Save the document
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($filepath);
        
        return true;
        
    } catch (Exception $e) {
        return false;
    }
}
?>
