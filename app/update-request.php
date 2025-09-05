<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {

if (isset($_POST['request_id']) && isset($_POST['status'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$request_id = validate_input($_POST['request_id']);
	$status = validate_input($_POST['status']);
	$admin_response = validate_input($_POST['admin_response']);
	$admin_id = $_SESSION['id'];

	if (empty($request_id)) {
		echo json_encode(['success' => false, 'message' => 'Request ID is required']);
	    exit();
	}else if (empty($status)) {
		echo json_encode(['success' => false, 'message' => 'Status is required']);
	    exit();
	}else {
    
       include "Model/Request.php";

       $data = array($status, $admin_response, $admin_id, $request_id);
       $result = update_request_status($conn, $data);

       if ($result) {
           echo json_encode(['success' => true, 'message' => 'Request updated successfully']);
       } else {
           echo json_encode(['success' => false, 'message' => 'Failed to update request']);
       }
       exit();
	}
}else {
   echo json_encode(['success' => false, 'message' => 'Invalid request data']);
   exit();
}

}else{ 
   echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
   exit();
}
