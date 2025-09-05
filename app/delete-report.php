<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "../DB_connection.php";
    include "Model/Report.php";
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $report = get_report_by_id($conn, $id);
        
        if ($report != 0) {
            // Delete the physical file if it exists
            $file_path = "../" . $report['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Delete the database record
            delete_report($conn, $id);
            
            $sm = "Report deleted successfully";
            header("Location: ../reports.php?success=$sm");
            exit();
        } else {
            $em = "Report not found";
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
