<?php
session_start();


$id = $_GET['id'] ?? 0;
$search = json_decode($_SESSION['search'],true);
//print_r($_SESSION['payment_response']);

$array = $_SESSION['passengers'];


/*echo '<pre>';
print_r($_SESSION);
echo '</pre>';


die();*/

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
    "ResultIndex": "'.$search["Response"]["Results"][0][$id]['ResultIndex'].'",
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

//echo $response;

//die();

/*

$data = [
    "Response" => [
        "B2B2BStatus" => "",
        "Error" => [
            "ErrorCode" => 0,
            "ErrorMessage" => ""
        ],
        "ResponseStatus" => 1,
        "TraceId" => "f74393fb-7dbc-4699-8660-933230d4ad28",
        "Response" => [
            "PNR" => "KGL79R",
            "BookingId" => 1913805,
            "SSRDenied" => "",
            "SSRMessage" => "",
            "Status" => 1,
            "IsPriceChanged" => "",
            "IsTimeChanged" => "",
            "FlightItinerary" => [
                "CommentDetails" => "",
                "IsAutoReissuanceAllowed" => 1,
                "IssuancePcc" => "APITESTID",
                "JourneyType" => 1,
                "SearchCombinationType" => 2,
                "TripIndicator" => 1,
                "BookingAllowedForRoamer" => 1,
                "BookingId" => 1913805,
                "IsCouponAppilcable" => 1,
                "IsManual" => "",
                "PNR" => "KGL79R",
                "IsDomestic" => 1,
                "ResultFareType" => "RegularFare",
                "Source" => 3,
                "Origin" => "DEL",
                "Destination" => "BOM",
                "AirlineCode" => "SG",
                "LastTicketDate" => "9999-12-31T00:00:00",
                "ValidatingAirlineCode" => "SG",
                "AirlineRemark" => "SpiceJet Main",
                "AirlineTollFreeNo" => "9876543210",
                "IsLCC" => 1,
                "NonRefundable" => "",
                "FareType" => "PUB",
                "CreditNoteNo" => "",
                "Fare" => [
                    "Currency" => "INR",
                    "BaseFare" => 2908,
                    "Tax" => 1314,
                    "TaxBreakup" => [
                        [
                            "key" => "K3",
                            "value" => 196
                        ],
                        [
                            "key" => "PSF",
                            "value" => 91
                        ],
                        [
                            "key" => "UDF",
                            "value" => 12
                        ],
                        [
                            "key" => "YR",
                            "value" => 65
                        ],
                        [
                            "key" => "YQTax",
                            "value" => 900
                        ],
                        [
                            "key" => "TotalTax",
                            "value" => 1314
                        ],
                        [
                            "key" => "OtherTaxes",
                            "value" => 50
                        ]
                    ],
                    "YQTax" => 900,
                    "AdditionalTxnFeeOfrd" => 0,
                    "AdditionalTxnFeePub" => 0,
                    "PGCharge" => 0,
                    "OtherCharges" => 1.77,
                    "ChargeBU" => [
                        [
                            "key" => "TBOMARKUP",
                            "value" => 0
                        ],
                        [
                            "key" => "GLOBALPROCUREMENTCHARGE",
                            "value" => 0
                        ],
                        [
                            "key" => "OTHERCHARGE",
                            "value" => 1.77
                        ],
                        [
                            "key" => "CONVENIENCECHARGE",
                            "value" => 0
                        ]
                    ],
                    "Discount" => 0,
                    "PublishedFare" => 4223.77,
                    "CommissionEarned" => 47.69,
                    "PLBEarned" => 89.2,
                    "IncentiveEarned" => 140.73,
                    "OfferedFare" => 3946.15,
                    "TdsOnCommission" => 19.08,
                    "TdsOnPLB" => 35.68,
                    "TdsOnIncentive" => 56.29,
                    "ServiceFee" => 0,
                    "TotalBaggageCharges" => 0,
                    "TotalMealCharges" => 0,
                    "TotalSeatCharges" => 0,
                    "TotalSpecialServiceCharges" => 0
                ],
                "CreditNoteCreatedOn" => "",
                "Passenger" => [
                    [
                        "BarcodeDetails" => [
                            "Id" => 3143703,
                            "Barcode" => [
                                [
                                    "Index" => 1,
                                    "Format" => "PDF417",
                                    "Content" => "M1NAME/TESTING KGL79R DELBOMSG 8709 219Y00000000 000",
                                    "BarCodeInBase64" => "",
                                    "JourneyWayType" => 3
                                ]
                            ]
                        ],
                        "DocumentDetails" => "",
                        "GuardianDetails" => "",
                        "PaxId" => 3143703,
                        "Title" => "Mr",
                        "FirstName" => "Testing",
                        "LastName" => "Name",
                        "PaxType" => 1,
                        "Gender" => 1,
                        "IsPANRequired" => "",
                        "IsPassportRequired" => "",
                        "PAN" => "",
                        "PassportNo" => "",
                        "AddressLine1" => "xyz",
                        "Fare" => [
                            "Currency" => "INR",
                            "BaseFare" => 2908,
                            "Tax" => 1314,
                            "TaxBreakup" => [
                                [
                                    "key" => "K3",
                                    "value" => 196
                                ],
                                [
                                    "key" => "PSF",
                                    "value" => 91
                                ],
                                [
                                    "key" => "UDF",
                                    "value" => 12
                                ],
                                [
                                    "key" => "YR",
                                    "value" => 65
                                ],
                                [
                                    "key" => "YQTax",
                                    "value" => 900
                                ],
                                [
                                    "key" => "TotalTax",
                                    "value" => 1314
                                ],
                                [
                                    "key" => "OtherTaxes",
                                    "value" => 50
                                ]
                            ],
                            "YQTax" => 900,
                            "AdditionalTxnFeeOfrd" => 0,
                            "AdditionalTxnFeePub" => 0,
                            "PGCharge" => 0,
                            "OtherCharges" => 1.77,
                            "ChargeBU" => [
                                [
                                    "key" => "TBOMARKUP",
                                    "value" => 0
                                ],
                                [
                                    "key" => "GLOBALPROCUREMENTCHARGE",
                                    "value" => 0
                                ],
                                [
                                    "key" => "OTHERCHARGE",
                                    "value" => 1.77
                                ],
                                [
                                    "key" => "CONVENIENCECHARGE",
                                    "value" => 0
                                ]
                            ],
                            "Discount" => 0,
                            "PublishedFare" => 4223.77,
                            "CommissionEarned" => 47.69,
                            "PLBEarned" => 89.2,
                            "IncentiveEarned" => 140.73,
                            "OfferedFare" => 3946.15,
                            "TdsOnCommission" => 19.08,
                            "TdsOnPLB" => 35.68,
                            "TdsOnIncentive" => 56.29,
                            "ServiceFee" => 0,
                            "TotalBaggageCharges" => 0,
                            "TotalMealCharges" => 0,
                            "TotalSeatCharges" => 0,
                            "TotalSpecialServiceCharges" => 0
                        ],
                        "City" => "Mumbai",
                        "CountryCode" => "IN",
                        "CountryName" => "India",
                        "Nationality" => "Indian",
                        "ContactNo" => "9999999999",
                        "Email" => "a@a.com",
                        "IsLeadPax" => 1,
                        "FFAirlineCode" => "",
                        "FFNumber" => "",
                        "Ssr" => [],
                        "Ticket" => [
                            "TicketId" => 2199022,
                            "TicketNumber" => "KGL79R",
                            "IssueDate" => "2024-08-06T16:59:16",
                            "ValidatingAirline" => "001",
                            "Remarks" => "",
                            "ServiceFeeDisplayType" => "ShowInTax",
                            "Status" => "OK",
                            "ConjunctionNumber" => "",
                            "TicketType" => "N"
                        ],
                        "GSTCompanyAddress" => "",
                        "GSTCompanyContactNumber" => "",
                        "GSTCompanyEmail" => "",
                        "GSTCompanyName" => "",
                        "GSTNumber" => "",
                        "SegmentAdditionalInfo" => [
                            [
                                "SegmentId" => 1,
                                "FlightNo" => "8709",
                                "Departure" => "2024-08-06T19:00:00",
                                "Arrival" => "2024-08-06T21:25:00",
                                "Duration" => "145",
                                "BaggageAllowance" => "15 KG checked, 7 KG cabin",
                                "MealDetails" => "",
                                "FlightType" => 1
                            ]
                        ]
                    ]
                ],
                "Invoice" => [
                    "InvoiceId" => "ID/2425/7071",
                    "InvoiceAmount" => 4057,
                    "InvoiceStatus" => "Generated",
                    "GSTNumber" => "",
                    "GSTCompanyName" => "",
                    "GSTCompanyAddress" => "",
                    "GSTCompanyContactNumber" => "",
                    "GSTCompanyEmail" => ""
                ],
                "Rules" => [
                    "CancellationRules" => "As per the airline's cancellation policy, which allows for cancellation with applicable charges. For domestic bookings, cancellations must be submitted at least 2 hours before the airline's policy.",
                    "RefundRules" => "Refunds are processed as per the airline's refund policy. Please check with the airline for more details.",
                    "ChangeRules" => "Changes to the booking are permitted as per the airline's change policy. Charges may apply."
                ]
            ]
        ]
    ]
];

*/

$data = json_decode($response, true);

//print_r($data);

// Extract necessary data
$booking = $data['Response']['Response'];
$flightItinerary = $booking['FlightItinerary'];
$passengers = $flightItinerary['Passenger'];

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
            font-family: Poppins, sans-serif;
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
                        <td><?php echo isset($p['ContactNo']) ? htmlspecialchars($p['ContactNo']) : ''; ?></td>
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