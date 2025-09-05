<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Accounting.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: accounting.php");
    	 exit();
    }
    $id = $_GET['id'];
    $entry = get_accounting_entry_by_id($conn, $id);

    if ($entry == 0) {
    	 header("Location: accounting.php");
    	 exit();
    }

     delete_accounting_entry($conn, $id);
     $sm = "Accounting entry deleted successfully";
     header("Location: accounting.php?success=$sm");
     exit();

 }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>
