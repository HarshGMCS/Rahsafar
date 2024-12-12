<?php
include('razorpay/Razorpay.php');

use Razorpay\Api\Api;

$api_key = 'rzp_test_Cfjlk2vvybCXyI';

$api_secret = 'pGfLwK3l9aSjSRL14SZYWxHQ';

$api = new Api($api_key, $api_secret);


$attributes = array(
    'razorpay_order_id' => $_POST['razorpay_order_id'],
    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
    'razorpay_signature' => $_POST['razorpay_signature']
);

try {
    $api->utility->verifyPaymentSignature($attributes);

    // Fetch payment details
    $payment = $api->payment->fetch($_POST['razorpay_payment_id']);
    
    // Prepare response data
    $response = [
        'status' => 'success',
        'transaction_id' => $payment['id'],
        'amount' => $payment['amount'],
        'currency' => $payment['currency'],
        'method' => $payment['method'],
        'description' => $payment['description'],
        'created_at' => $payment['created_at']
    ];

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Payment verification failed: ' . $e->getMessage()
    ]);
}

?>
