<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {

if (isset($_POST['category']) && isset($_POST['type']) && isset($_POST['description']) && isset($_POST['amount']) && isset($_POST['date'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$category = validate_input($_POST['category']);
	$type = validate_input($_POST['type']);
	$description = validate_input($_POST['description']);
	$amount = validate_input($_POST['amount']);
	$date = validate_input($_POST['date']);

	if (empty($category)) {
		$em = "Category is required";
	    header("Location: ../add-accounting.php?error=$em");
	    exit();
	}else if (empty($type)) {
		$em = "Type is required";
	    header("Location: ../add-accounting.php?error=$em");
	    exit();
	}else if (empty($description)) {
		$em = "Description is required";
	    header("Location: ../add-accounting.php?error=$em");
	    exit();
	}else if (empty($amount) || $amount <= 0) {
		$em = "Valid amount is required";
	    header("Location: ../add-accounting.php?error=$em");
	    exit();
	}else if (empty($date)) {
		$em = "Date is required";
	    header("Location: ../add-accounting.php?error=$em");
	    exit();
	}else {
    
       include "Model/Accounting.php";

       $data = array($category, $type, $description, $amount, $date);
       insert_accounting_entry($conn, $data);

       $sm = "Accounting entry added successfully";
	    header("Location: ../accounting.php?success=$sm");
	    exit();
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../add-accounting.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../login.php?error=$em");
   exit();
}
