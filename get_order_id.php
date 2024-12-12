<?php


include('razorpay/Razorpay.php');

use Razorpay\Api\Api;

$api_key = 'rzp_test_Cfjlk2vvybCXyI';

$api_secret = 'pGfLwK3l9aSjSRL14SZYWxHQ';

// Retrieve dynamic values from GET or POST request
$receipt_number = $_POST['receipt_number']; 
$amount = $_POST['amount']; // Amount in INR

// Convert amount to paise (1 INR = 100 paise)
$amount_in_paise = $amount * 100;


$api = new Api($api_key, $api_secret);

// Create an order in Razorpay
$orderData = [
    'receipt'         => $receipt_number,
    'amount'          => $amount_in_paise, // Amount in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$order_id = $razorpayOrder['id'];

echo json_encode(['order_id' => $order_id]);


?>