<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Inventory.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: inventory.php");
    	 exit();
    }
    $id = $_GET['id'];
    $item = get_inventory_item_by_id($conn, $id);

    if ($item == 0) {
    	 header("Location: inventory.php");
    	 exit();
    }

     delete_inventory_item($conn, $id);
     $sm = "Inventory item deleted successfully";
     header("Location: inventory.php?success=$sm");
     exit();

 }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>
