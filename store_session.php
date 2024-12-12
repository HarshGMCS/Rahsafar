<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['responseData']) && isset($_POST['formData'])) {
    $responseData = json_decode($_POST['responseData'], true);
    //$passengers = json_decode($_POST['formData'], true);
    
    // Store the data in a session variable
    $_SESSION['payment_response'] = $responseData;
    $_SESSION['passengers'] = $_POST['formData'];


  

    //print_r( $_SESSION['passengers']);
    //die();
    
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>