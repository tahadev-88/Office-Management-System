<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "../DB_connection.php";
    include "Model/Report.php";
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $report = get_report_by_id($conn, $id);
        
        if ($report != 0 && $report['status'] == 'completed') {
            $file_path = "../" . $report['file_path'];
            
            if (file_exists($file_path)) {
                // Set headers for file download
                header('Content-Description: File Transfer');
                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                
                // Clear output buffer
                ob_clean();
                flush();
                
                // Read and output file
                readfile($file_path);
                exit();
            } else {
                $em = "Report file not found";
                header("Location: ../reports.php?error=$em");
                exit();
            }
        } else {
            $em = "Report not found or not completed";
            header("Location: ../reports.php?error=$em");
            exit();
        }
    } else {
        $em = "Invalid request";
        header("Location: ../reports.php?error=$em");
        exit();
    }
} else {
    $em = "Unauthorized access";
    header("Location: ../login.php?error=$em");
    exit();
}
?>
