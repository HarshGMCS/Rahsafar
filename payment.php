<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <button id="pay-button">Pay with Razorpay</button>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $(document).ready(function(){
            $('#pay-button').click(function(){
            	var receiptNumber = '12345';
                var amount = 200;
                $.ajax({
                    url: 'get_order_id.php', // This is the script that creates the order
                    type: 'POST',
                    data: {
                        receipt_number: receiptNumber,
                        amount: amount,
                    },
                    success: function(data) {
                        var response = JSON.parse(data);
                        var order_id = response.order_id;

                        var options = {
                            "key": "rzp_test_Cfjlk2vvybCXyI", // Enter the Key ID generated from the Dashboard
                            "amount": "10000", // Amount is in currency subunits. Default currency is INR. Hence, 10000 means 100.00 INR
                            "currency": "INR",
                            "name": "Rahsafar Travels",
                            "description": "Test Transaction",
                            "image": "https://rahsafar.com/wp-content/uploads/2023/10/logo.jpg",
                            "order_id": order_id, // This is the order_id created in the backend
                            "handler": function (response){
                                $.ajax({
                                    url: 'verify_payment.php',
                                    type: 'POST',
                                    data: {
                                        razorpay_payment_id: response.razorpay_payment_id,
                                        razorpay_order_id: response.razorpay_order_id,
                                        razorpay_signature: response.razorpay_signature
                                    },
                                    success: function(data) {
                                    	console.log(data);
                                        //alert('Payment successful!');
                                    }
                                });
                            },
                            "prefill": {
                                "name": "John Doe",
                                "email": "john.doe@example.com"
                            },
                            "theme": {
                                "color": "#F37254"
                            }
                        };

                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    }
                });
            });
        });
    </script>
</body>
</html>
