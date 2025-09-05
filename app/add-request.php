<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {

if (isset($_POST['title']) && isset($_POST['message']) && isset($_POST['category']) && isset($_POST['priority'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$title = validate_input($_POST['title']);
	$message = validate_input($_POST['message']);
	$category = validate_input($_POST['category']);
	$priority = validate_input($_POST['priority']);
	$employee_id = $_SESSION['id'];

	if (empty($title)) {
		$em = "Request title is required";
	    header("Location: ../create_request.php?error=$em");
	    exit();
	}else if (empty($message)) {
		$em = "Request message is required";
	    header("Location: ../create_request.php?error=$em");
	    exit();
	}else if (empty($category)) {
		$em = "Category is required";
	    header("Location: ../create_request.php?error=$em");
	    exit();
	}else if (empty($priority)) {
		$em = "Priority is required";
	    header("Location: ../create_request.php?error=$em");
	    exit();
	}else {
    
       include "Model/Request.php";

       $data = array($employee_id, $title, $message, $category, $priority);
       insert_request($conn, $data);

       $sm = "Request submitted successfully";
	    header("Location: ../my_requests.php?success=$sm");
	    exit();
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../create_request.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../login.php?error=$em");
   exit();
}
