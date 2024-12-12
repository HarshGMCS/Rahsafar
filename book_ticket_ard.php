<?php
session_start();


if(!$_SESSION['search']){
    header('Location: index.html');
}

$departure = $_GET['departure'] ?? 0;
$arrival = $_GET['arrival'] ?? 0;
$search = json_decode($_SESSION['search'],true);
//print_r($_SESSION['payment_response']);

$array = $_SESSION['passengers'];



$isLCC = isset($_SESSION['fare_quote']['IsLCC']) ? $_SESSION['fare_quote']['IsLCC'] : false;

// Initialize fare breakdown array
$fareBreakdown = [];
foreach ($_SESSION['fare_quote']['FareBreakdown'] as $fare) {
    $fareBreakdown[$fare['PassengerType']] = $fare;
}

// Process array into passenger data
$passengers = [];
$GSTDetails = [
    'GSTCompanyAddress' => '',
    'GSTCompanyContactNumber' => '',
    'GSTCompanyName' => '',
    'GSTNumber' => '',
    'GSTCompanyEmail' => ''
];

$fieldMapping = [
    'title' => 'Title',
    'firstName' => 'FirstName',
    'lastName' => 'LastName',
    'gender' => 'Gender',
    'nationality' => 'Nationality'
];

$a1Details = [
    'ContactNo' => '',
    'Email' => '',
    'AddressLine1' => '',
    'City' => '',
    'CountryCode' => ''
];

foreach ($array as $item) {
    if (strpos($item['name'], '_A1') !== false) {
        $field = explode('_', $item['name'])[0];
        if (isset($fieldMapping[$field])) {
            $a1Details[$fieldMapping[$field]] = $item['value'];
        }
        if ($field == 'cellCountryCode') {
            //$a1Details['ContactNo'] = $item['value'];
        }
        if ($field == 'contactNumber') {
            $a1Details['ContactNo'] .= $item['value'];
        }
        if ($field == 'email') {
            $a1Details['Email'] = $item['value'];
        }
        if ($field == 'address') {
            $a1Details['AddressLine1'] = $item['value'];
        }
        if ($field == 'city') {
            $a1Details['City'] = $item['value'];
        }
        if ($field == 'countryCode') {
            $a1Details['CountryCode'] = $item['value'];
        }
    }
}

foreach ($array as $item) {
    $nameParts = explode('_', $item['name']);
    $field = $nameParts[0];
    $index = isset($nameParts[1]) ? $nameParts[1] : '';

    if ($index) {
        if (!isset($passengers[$index])) {
            $paxType = strpos($index, 'A') === 0 ? 1 : (strpos($index, 'C') === 0 ? 2 : 3);
            $passengers[$index] = [
                "Title" => "",
                "FirstName" => "",
                "LastName" => "",
                "PaxType" => $paxType,
                "Gender" => "",
                "AddressLine1" => $a1Details['AddressLine1'],
                "City" => $a1Details['City'],
                "CountryCode" => $a1Details['CountryCode'],
                "CountryName" => "India",
                "Nationality" => "",
                "ContactNo" => $a1Details['ContactNo'],
                "Email" => $a1Details['Email'],
                "PassportNo" => "",
                "PassportExpiry" => "",
                "IsLeadPax" => strpos($index, 'A1') === 0,
                "GSTCompanyAddress" => "",
                "GSTCompanyContactNumber" => "",
                "GSTCompanyName" => "",
                "GSTNumber" => "",
                "GSTCompanyEmail" => "",
                "Fare" => $fareBreakdown[$paxType] ?? []
            ];
        }
        if (isset($fieldMapping[$field])) {
            $passengers[$index][$fieldMapping[$field]] = $item['value'];
        }
    } else {
        $GSTDetails[$item['name']] = $item['value'];
    }
}

foreach ($passengers as &$passenger) {
    foreach ($GSTDetails as $key => $value) {
        $passenger[$key] = $value;
    }
}

foreach ($passengers as &$passenger) {
    $passenger['Gender'] = strtolower($passenger['Gender']) == 'male' ? 1 : 2;
}

$passengers = array_values($passengers);


//echo json_encode($passengers, JSON_PRETTY_PRINT);

//die();






$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Ticket',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "PreferredCurrency": null,
    "ResultIndex": "'.$search["Response"]["Results"][0][$departure]['ResultIndex'].'",
    "Passengers": '.json_encode($passengers).',
    "EndUserIp": "49.43.3.128",
    "TokenId": "'.$_SESSION['token'].'",
    "TraceId": "'.$search["Response"]["TraceId"].'"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

echo $response;

//die();



$data = json_decode($response, true);


// Extract necessary data
$booking = $data['Response']['Response'];
$flightItinerary = $booking['FlightItinerary'];
$passengers = $flightItinerary['Passenger'];





$isLCC_arrival = isset($_SESSION['fare_quote_arrival']['IsLCC']) ? $_SESSION['fare_quote_arrival']['IsLCC'] : false;

// Initialize fare breakdown array
$fareBreakdown_arrival = [];
foreach ($_SESSION['fare_quote_arrival']['FareBreakdown'] as $fare) {
    $fareBreakdown_arrival[$fare['PassengerType']] = $fare;
}

// Process array into passenger data
$passengers_arrival = [];
$GSTDetails_arrival = [
    'GSTCompanyAddress' => '',
    'GSTCompanyContactNumber' => '',
    'GSTCompanyName' => '',
    'GSTNumber' => '',
    'GSTCompanyEmail' => ''
];

$fieldMapping_arrival = [
    'title' => 'Title',
    'firstName' => 'FirstName',
    'lastName' => 'LastName',
    'gender' => 'Gender',
    'nationality' => 'Nationality'
];

$a1Details_arrival = [
    'ContactNo' => '',
    'Email' => '',
    'AddressLine1' => '',
    'City' => '',
    'CountryCode' => ''
];

foreach ($array as $item) {
    if (strpos($item['name'], '_A1') !== false) {
        $field = explode('_', $item['name'])[0];
        if (isset($fieldMapping_arrival[$field])) {
            $a1Details_arrival[$fieldMapping_arrival[$field]] = $item['value'];
        }
        if ($field == 'cellCountryCode') {
            //$a1Details['ContactNo'] = $item['value'];
        }
        if ($field == 'contactNumber') {
            $a1Details['ContactNo'] = $item['value'];
        }
        if ($field == 'email') {
            $a1Details['Email'] = $item['value'];
        }
        if ($field == 'address') {
            $a1Details['AddressLine1'] = $item['value'];
        }
        if ($field == 'city') {
            $a1Details['City'] = $item['value'];
        }
        if ($field == 'countryCode') {
            $a1Details['CountryCode'] = $item['value'];
        }
    }
}

foreach ($array as $item) {
    $nameParts = explode('_', $item['name']);
    $field = $nameParts[0];
    $index = isset($nameParts[1]) ? $nameParts[1] : '';

    if ($index) {
        if (!isset($passengers[$index])) {
            $paxType = strpos($index, 'A') === 0 ? 1 : (strpos($index, 'C') === 0 ? 2 : 3);
            $passengers[$index] = [
                "Title" => "",
                "FirstName" => "",
                "LastName" => "",
                "PaxType" => $paxType,
                "Gender" => "",
                "AddressLine1" => $a1Details['AddressLine1'],
                "City" => $a1Details['City'],
                "CountryCode" => $a1Details['CountryCode'],
                "CountryName" => "India",
                "Nationality" => "",
                "ContactNo" => $a1Details['ContactNo'],
                "Email" => $a1Details['Email'],
                "PassportNo" => "",
                "PassportExpiry" => "",
                "IsLeadPax" => strpos($index, 'A1') === 0,
                "GSTCompanyAddress" => "",
                "GSTCompanyContactNumber" => "",
                "GSTCompanyName" => "",
                "GSTNumber" => "",
                "GSTCompanyEmail" => "",
                "Fare" => $fareBreakdown[$paxType] ?? []
            ];
        }
        if (isset($fieldMapping[$field])) {
            $passengers[$index][$fieldMapping[$field]] = $item['value'];
        }
    } else {
        $GSTDetails_arrival[$item['name']] = $item['value'];
    }
}

foreach ($passengers as &$passenger) {
    foreach ($GSTDetails_arrival as $key => $value) {
        $passenger[$key] = $value;
    }
}

foreach ($passengers as &$passenger) {
    $passenger['Gender'] = strtolower($passenger['Gender']) == 'male' ? 1 : 2;
}

$passengers = array_values($passengers);


//echo json_encode($passengers, JSON_PRETTY_PRINT);

//die();


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Ticket',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "PreferredCurrency": null,
    "ResultIndex": "'.$search["Response"]["Results"][1][$arrival]['ResultIndex'].'",
    "Passengers": '.json_encode($passengers).',
    "EndUserIp": "49.43.3.128",
    "TokenId": "'.$_SESSION['token'].'",
    "TraceId": "'.$search["Response"]["TraceId"].'"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response_arrival = curl_exec($curl);

echo $response_arrival;


curl_close($curl);



$data_arrival = json_decode($response_arrival, true);




// Extract necessary data
$booking_arrival = $data_arrival['Response']['Response'];
$flightItinerary_arrival = $booking_arrival['FlightItinerary'];
$passengers_arrival = $flightItinerary_arrival['Passenger'];




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,700;&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .section h4 {
            margin-top: 0;
            color: #3498db;
            font-weight: bold;
            font-size:18px;
        }
        .details {
            margin: 10px 0;
        }
        .details label {
            font-weight: bold;
        }
        .details p {
            margin: 5px 0;
        }
        .flight-info, .passenger-info, .invoice-info {
            margin-top: 10px;
        }
        .flight-info table, .passenger-info table, .invoice-info table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .flight-info th, .passenger-info th, .invoice-info th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
        }
        .flight-info td, .passenger-info td, .invoice-info td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .invoice-info {
            background-color: #f9f9f9;
        }
    </style>

</head>
<body>
    <div class="container">
        <h2>Booking Confirmation</h2>

        <div class="section">
            <h4>Booking Details</h4>
            <div class="details">
                <p><strong>PNR:</strong> <?php echo htmlspecialchars($booking['PNR']); ?></p>
                <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['BookingId']); ?></p>
                <p><strong>Status:</strong> <?php echo $booking['Status'] == 1 ? 'Confirmed' : 'Not Confirmed'; ?></p>
                
            </div>
        </div>

        <div class="section flight-info">
            <h4>Flight Information</h4>
            <table>
                <thead>
                    <tr>
                        <th>Flight Number</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>

                  <?php 
                  /*echo '<pre>';
                  print_r($booking); 
                  echo '</pre>';*/
                  ?>

                  <?php if(isset($flightItinerary['Segments'])): ?>


                  <?php foreach($flightItinerary['Segments'] as $fi): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($flightItinerary['AirlineCode']); ?></td>
                        <td><?php echo htmlspecialchars($fi['Origin']['Airport']['AirportCode']) .' '.htmlspecialchars($fi['Origin']['Airport']['AirportName']); ?></td>
                         <td><?php echo htmlspecialchars($fi['Destination']['Airport']['AirportCode']) .' '.htmlspecialchars($fi['Destination']['Airport']['AirportName']); ?></td>
                        <td><?php echo Date('l, F j, Y g:i A', strtotime($fi['Origin']['DepTime'])); ?></td>
                        <td><?php echo Date('l, F j, Y g:i A', strtotime($fi['Destination']['ArrTime'])); ?></td>
                        <td><?php echo htmlspecialchars($fi['Duration']); ?> minutes</td>
                    </tr>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
            </table>
        </div>




        <div class="section">
            <h4>Return Booking Details</h4>
            <div class="details">
                <p><strong>PNR:</strong> <?php echo htmlspecialchars($booking_arrival['PNR']); ?></p>
                <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking_arrival['BookingId']); ?></p>
                <p><strong>Status:</strong> <?php echo $booking_arrival['Status'] == 1 ? 'Confirmed' : 'Not Confirmed'; ?></p>
                
            </div>
        </div>


         <div class="section flight-info">
            <h4>Return Flight Information</h4>
            <table>
                <thead>
                    <tr>
                        <th>Flight Number</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>

                  <?php 
                  /*echo '<pre>';
                  print_r($booking); 
                  echo '</pre>';*/
                  ?>

                  <?php if(isset($flightItinerary_arrival['Segments'])): ?>


                  <?php foreach($flightItinerary_arrival['Segments'] as $fi): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($flightItinerary_arrival['AirlineCode']); ?></td>
                        <td><?php echo htmlspecialchars($fi['Origin']['Airport']['AirportCode']) .' '.htmlspecialchars($fi['Origin']['Airport']['AirportName']); ?></td>
                         <td><?php echo htmlspecialchars($fi['Destination']['Airport']['AirportCode']) .' '.htmlspecialchars($fi['Destination']['Airport']['AirportName']); ?></td>
                        <td><?php echo Date('l, F j, Y g:i A', strtotime($fi['Origin']['DepTime'])); ?></td>
                        <td><?php echo Date('l, F j, Y g:i A', strtotime($fi['Destination']['ArrTime'])); ?></td>
                        <td><?php echo htmlspecialchars($fi['Duration']); ?> minutes</td>
                    </tr>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
            </table>
        </div>


        <div class="section passenger-info">
            <h4>Passenger Information</h4>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Passenger Type</th>
                        <th>Contact No</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>

               <?php if (isset($passengers)): ?>
                    <?php foreach ($passengers as $p): ?>
                    <tr>
                        <td><?php echo isset($p['Title']) ? htmlspecialchars($p['Title']) : ''; ?></td>
                        <td><?php echo isset($p['FirstName']) ? htmlspecialchars($p['FirstName']) : ''; ?></td>
                        <td><?php echo isset($p['LastName']) ? htmlspecialchars($p['LastName']) : ''; ?></td>
                        <td><?php echo htmlspecialchars($p['PaxType'] == 1 ? 'Adult' : ($p['PaxType'] == 2 ? 'Child' : 'Infant')); ?></td>
                        <td><?php echo isset($p['ContactNo']) ? $p['ContactNo'] : ''; ?></td>
                        <td><?php echo isset($p['Email']) ? htmlspecialchars($p['Email']) : ''; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="section invoice-info">
            <h4>Invoice Information</h4>
            <table>
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Invoice Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($flightItinerary['InvoiceNo']); ?></td>
                        <td><?php echo number_format($flightItinerary['InvoiceAmount'], 2); ?> INR</td>
                        <td><?php echo $flightItinerary['InvoiceStatus'] == 3 ? 'Paid' : 'Unpaid'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>


<?php


session_destroy();


?>