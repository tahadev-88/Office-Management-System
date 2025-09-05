<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {

if (isset($_POST['client_name']) && 
    isset($_POST['contact_info']) && 
    isset($_POST['salesman']) && 
    isset($_POST['platform'])) {
    
    include "../DB_connection.php";
    include "Model/Client.php";

    $client_name = $_POST['client_name'];
    $contact_info = $_POST['contact_info'];
    $salesman = $_POST['salesman'];
    $platform = $_POST['platform'];

    $data = array($client_name, $contact_info, $salesman, $platform);

    if (empty($client_name)) {
        $em = "Client name is required";
        header("Location: ../clients.php?error=$em");
        exit();
    } else if (empty($contact_info)) {
        $em = "Contact information is required";
        header("Location: ../clients.php?error=$em");
        exit();
    } else if (empty($salesman)) {
        $em = "Salesman name is required";
        header("Location: ../clients.php?error=$em");
        exit();
    } else if (empty($platform)) {
        $em = "Platform is required";
        header("Location: ../clients.php?error=$em");
        exit();
    } else {
        insert_client($conn, $data);
        $sm = "Client added successfully!";
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
