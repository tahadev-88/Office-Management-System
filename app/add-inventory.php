<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {

if (isset($_POST['item_name']) && isset($_POST['category']) && isset($_POST['status'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$item_name = validate_input($_POST['item_name']);
	$category = validate_input($_POST['category']);
	$brand = validate_input($_POST['brand']);
	$model = validate_input($_POST['model']);
	$serial_number = validate_input($_POST['serial_number']);
	$assigned_to = !empty($_POST['assigned_to']) ? validate_input($_POST['assigned_to']) : null;
	$status = validate_input($_POST['status']);
	$purchase_date = !empty($_POST['purchase_date']) ? validate_input($_POST['purchase_date']) : null;
	$purchase_price = !empty($_POST['purchase_price']) ? validate_input($_POST['purchase_price']) : null;
	$warranty_expiry = !empty($_POST['warranty_expiry']) ? validate_input($_POST['warranty_expiry']) : null;
	$description = validate_input($_POST['description']);

	if (empty($item_name)) {
		$em = "Item name is required";
	    header("Location: ../add-inventory.php?error=$em");
	    exit();
	}else if (empty($category)) {
		$em = "Category is required";
	    header("Location: ../add-inventory.php?error=$em");
	    exit();
	}else if (empty($status)) {
		$em = "Status is required";
	    header("Location: ../add-inventory.php?error=$em");
	    exit();
	}else {
    
       include "Model/Inventory.php";

       // If assigned to someone, set status to assigned
       if($assigned_to && $status == 'available') {
           $status = 'assigned';
       }

       $data = array($item_name, $category, $brand, $model, $serial_number, $assigned_to, $status, $purchase_date, $purchase_price, $warranty_expiry, $description);
       insert_inventory_item($conn, $data);

       $sm = "Inventory item added successfully";
	    header("Location: ../inventory.php?success=$sm");
	    exit();
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../add-inventory.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../login.php?error=$em");
   exit();
}
