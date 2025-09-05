<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {

if (isset($_GET['id'])) {
    
    include "../DB_connection.php";
    include "Model/Client.php";

    $id = $_GET['id'];

    if (empty($id)) {
        $em = "Client ID is required";
        header("Location: ../clients.php?error=$em");
        exit();
    } else {
        delete_client($conn, $id);
        $sm = "Client deleted successfully!";
        header("Location: ../clients.php?success=$sm");
        exit();
    }

} else {
    $em = "An error occurred";
    header("Location: ../clients.php?error=$em");
    exit();
}

} else {
    $em = "First login";
    header("Location: ../login.php?error=$em");
    exit();
}
?>
