<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['full_name']) && isset($_POST['job']) && $_SESSION['role'] == 'admin') {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$user_name = validate_input($_POST['user_name']);
	$password = validate_input($_POST['password']);
	$full_name = validate_input($_POST['full_name']);
	$job = validate_input($_POST['job']);
	$id = validate_input($_POST['id']);


	if (empty($user_name)) {
		$em = "User name is required";
	    header("Location: ../edit-user.php?error=$em&id=$id");
	    exit();
	}else if (empty($password)) {
		$em = "Password is required";
	    header("Location: ../edit-user.php?error=$em&id=$id");
	    exit();
	}else if (empty($full_name)) {
		$em = "Full name is required";
	    header("Location: ../edit-user.php?error=$em&id=$id");
	    exit();
	}else if (empty($job)) {
		$em = "Job title is required";
	    header("Location: ../edit-user.php?error=$em&id=$id");
	    exit();
	}else {
    
       include "Model/User.php";
       
       // Check if username already exists (excluding current user)
       if (check_username_exists($conn, $user_name, $id)) {
           $em = "Username already exists. Please choose a different username.";
           header("Location: ../edit-user.php?error=$em&id=$id");
           exit();
       }
       
       $password = password_hash($password, PASSWORD_DEFAULT);

       $data = array($full_name, $user_name, $password, "employee", $job, $id, "employee");
       update_user($conn, $data);

       $em = "User updated successfully";
	    header("Location: ../edit-user.php?success=$em&id=$id");
	    exit();

    
	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../edit-user.php?error=$em");
   exit();
}

}else{ 
   $em = "First login";
   header("Location: ../edit-user.php?error=$em");
   exit();
}