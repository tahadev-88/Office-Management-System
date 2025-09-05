<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {

if (isset($_POST['id']) && isset($_POST['item_name']) && isset($_POST['category']) && isset($_POST['status'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$id = validate_input($_POST['id']);
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
	    header("Location: ../edit-inventory.php?error=$em&id=$id");
	    exit();
	}else if (empty($category)) {
		$em = "Category is required";
	    header("Location: ../edit-inventory.php?error=$em&id=$id");
	    exit();
	}else if (empty($status)) {
		$em = "Status is required";
	    header("Location: ../edit-inventory.php?error=$em&id=$id");
	    exit();
	}else {
    
       include "Model/Inventory.php";

       // If assigned to someone, set status to assigned
       if($assigned_to && $status == 'available') {
           $status = 'assigned';
       }
       // If unassigned, set status to available
       if(!$assigned_to && $status == 'assigned') {
           $status = 'available';
       }

       $data = array($item_name, $category, $brand, $model, $serial_number, $assigned_to, $status, $purchase_date, $purchase_price, $warranty_expiry, $description, $id);
       update_inventory_item($conn, $data);

       $sm = "Inventory item updated successfully";
	    header("Location: ../edit-inventory.php?success=$sm&id=$id");
	    exit();
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../inventory.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../login.php?error=$em");
   exit();
}
