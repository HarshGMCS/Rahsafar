<?php
session_start();

$_SESSION = array();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"ClientId": "ApiIntegrationNew",
"UserName": "Rahsafar1",
"Password": "Rahsafar1@1234", 
"EndUserIp": "49.43.3.128"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$res1 = json_decode($response,TRUE);


if($res1){
    $token = $res1['TokenId'];

    $_SESSION['token'] = $token;
}

//echo json_encode(['status' => true]);
//return;



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $adultCount = isset($_POST["adults"]) ? $_POST["adults"] : 0;
    $childCount = isset($_POST["children"]) ? $_POST["children"] : 0;
    $infantCount = isset($_POST["infants"]) ? $_POST["infants"] : 0;
    $directFlight = isset($_POST["directFlight"]) ? "true" : "false";
    $origin = isset($_POST["origin"]) ? $_POST["origin"] : "";
    $destination = isset($_POST["destination"]) ? $_POST["destination"] : "";
    $departure = isset($_POST["departure"]) ? $_POST["departure"] : "";
    
    $tripType = isset($_POST["tripType"]) ? $_POST["tripType"] : 1;

    $arrival = (isset($_POST["arrival"]) && ($tripType == 2)) ? $_POST["arrival"] : "";
    $class = isset($_POST["class"]) ? $_POST["class"] : 1;


    $_SESSION["data"] = array(
        "adultCount" => $adultCount,
        "childCount" => $childCount,
        "infantCount" => $infantCount,
        "directFlight" => $directFlight,
        "origin" => $origin,
        "destination" => $destination,
        "departure" => $departure,
        "tripType" => $tripType,
        "class" => $class,
    );

   

    // Prepare data for API request
    $apiData = array(
        "EndUserIp" => $_SERVER['REMOTE_ADDR'],
        "TokenId" => $token, // Replace with your actual API token
        "AdultCount" => $adultCount,
        "ChildCount" => $childCount,
        "InfantCount" => $infantCount,
        "DirectFlight" => $directFlight,
        "OneStopFlight" => "false",
        "JourneyType" => $tripType, // Assuming one-way journey
        "PreferredAirlines" => null,
        "Segments" => array(
            array(
                "Origin" => $origin,
                "Destination" => $destination,
                "FlightCabinClass" => $class, 
                "PreferredDepartureTime" => date('Y-m-d', strtotime($departure)) . "T00:00:00",
                "PreferredArrivalTime" => date('Y-m-d', strtotime($departure)) . "T00:00:00"
            )
        ),
        "Sources" => null
    );


    if ($arrival) {
    // Add a return segment if arrival is set
    $apiData['Segments'][] = array(
        "Origin" => $destination, // Reverse origin and destination for return
        "Destination" => $origin,
        "FlightCabinClass" => $class,
        "PreferredDepartureTime" => date('Y-m-d', strtotime($arrival)) . "T00:00:00",
        "PreferredArrivalTime" => date('Y-m-d', strtotime($arrival)) . "T00:00:00"
    );
}

    // Convert data to JSON format
    $apiDataJson = json_encode($apiData);

    // Make API request using cURL
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Search',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $apiDataJson,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);



    curl_close($curl);


    if ($response === false) {

        echo json_encode(['status' => false]);
        return;
        //echo 'API request error: ' . curl_error($curl);
    } else {

       //$res = json_decode($response,TRUE);
       //$_SESSION['res'] = $res;

        echo $response;

        $_SESSION["search"] = $response;
        return;

       //header("Location: listing.php");
    }


} else {
    // If the form is not submitted, redirect to the form page
    echo json_encode(['status' => false]);
    exit();
}
?>
