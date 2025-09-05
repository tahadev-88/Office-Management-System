<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {

if (isset($_POST['id']) && isset($_POST['category']) && isset($_POST['type']) && isset($_POST['description']) && isset($_POST['amount']) && isset($_POST['date'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$id = validate_input($_POST['id']);
	$category = validate_input($_POST['category']);
	$type = validate_input($_POST['type']);
	$description = validate_input($_POST['description']);
	$amount = validate_input($_POST['amount']);
	$date = validate_input($_POST['date']);

	if (empty($category)) {
		$em = "Category is required";
	    header("Location: ../edit-accounting.php?error=$em&id=$id");
	    exit();
	}else if (empty($type)) {
		$em = "Type is required";
	    header("Location: ../edit-accounting.php?error=$em&id=$id");
	    exit();
	}else if (empty($description)) {
		$em = "Description is required";
	    header("Location: ../edit-accounting.php?error=$em&id=$id");
	    exit();
	}else if (empty($amount) || $amount <= 0) {
		$em = "Valid amount is required";
	    header("Location: ../edit-accounting.php?error=$em&id=$id");
	    exit();
	}else if (empty($date)) {
		$em = "Date is required";
	    header("Location: ../edit-accounting.php?error=$em&id=$id");
	    exit();
	}else {
    
       include "Model/Accounting.php";

       $data = array($category, $type, $description, $amount, $date, $id);
       update_accounting_entry($conn, $data);

       $sm = "Accounting entry updated successfully";
	    header("Location: ../edit-accounting.php?success=$sm&id=$id");
	    exit();
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../accounting.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../login.php?error=$em");
   exit();
}
