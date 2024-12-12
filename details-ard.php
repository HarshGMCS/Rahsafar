<?php session_start(); ?>



<?php


$departure = $_GET['departure'] ?? 0;
$arrival = $_GET['arrival'] ?? 0;
$search = json_decode($_SESSION['search'],true);



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareRule',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"EndUserIp": "49.43.3.128",
"TokenId": "'.$_SESSION["token"].'",
"TraceId": "'.$search["Response"]["TraceId"].'",
"ResultIndex": "'.$search["Response"]["Results"][0][$departure]['ResultIndex'].'"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$fare_rules = curl_exec($curl);

curl_close($curl);


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareRule',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"EndUserIp": "49.43.3.128",
"TokenId": "'.$_SESSION["token"].'",
"TraceId": "'.$search["Response"]["TraceId"].'",
"ResultIndex": "'.$search["Response"]["Results"][1][$arrival]['ResultIndex'].'"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$fare_rules_arrival = curl_exec($curl);

curl_close($curl);


?>

<?php
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareQuote',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"EndUserIp": "49.43.3.128",
"TokenId": "'.$_SESSION["token"].'",
"TraceId": "'.$search["Response"]["TraceId"].'",
"ResultIndex": "'.$search["Response"]["Results"][0][$departure]['ResultIndex'].'"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$fq1 = curl_exec($curl);

curl_close($curl);


$fq2 = json_decode($fq1,true);


if(isset($fq2['Response']['Results'])){
$fare_quote = $fq2['Response']['Results'];
}else{
    header('Location: http://localhost/2024/rahsafar/');
}

$_SESSION['fare_quote'] = $fare_quote;




$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareQuote',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"EndUserIp": "49.43.3.128",
"TokenId": "'.$_SESSION["token"].'",
"TraceId": "'.$search["Response"]["TraceId"].'",
"ResultIndex": "'.$search["Response"]["Results"][1][$arrival]['ResultIndex'].'"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$fq1_arrival = curl_exec($curl);

curl_close($curl);


$fq2_arrival = json_decode($fq1_arrival,true);


if(isset($fq2_arrival['Response']['Results'])){
$fare_quote_arrival = $fq2_arrival['Response']['Results'];
}else{
    header('Location: http://localhost/2024/rahsafar/');
}

$_SESSION['fare_quote_arrival'] = $fare_quote_arrival;

/*echo '<pre>';
print_r($fare_quote_arrival);
print_r($fare_quote);
echo '</pre>';

die();*/

/*echo '<pre>';
print_r($fare_quote);
echo '</pre>';

die();*/



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airline Ticket Booking Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php /*<link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">*/ ?>

     <!-- Load Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.1/dist/vue.global.prod.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="assets/styles.css">
    <style type="text/css">
        label{
            font-size: 12px;
            font-weight: 500;
        }


        input::placeholder, .form-control {
            font-size: 14px;
        }


        .select2-container--default .select2-selection--single{
            --bs-input-color: var(--ds-text);
            --bs-input-bg: var(--ds-background-input);
            --bs-input-border-color: var(--ds-border-input);
            --bs-input-hover-bg: var(--ds-background-input-hovered);
            --bs-input-focus-color: var(--ds-text);
            --bs-input-focus-bg: var(--ds-background-input);
            --bs-input-focus-border-color: var(--ds-border-focused);
            --bs-input-disabled-color: var(--ds-text-disabled);
            --bs-input-disabled-bg: var(--ds-background-disabled);
            --bs-input-disabled-border-color: var(--ds-border-disabled);
            color: var(--bs-input-color);
            border-color: var(--bs-input-border-color);
            background-color: var(--bs-input-bg);
            height: 34px;
            display: block;
            width: 100%;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.4285714286;
            color: var(--ds-text);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: var(--ds-background-input);
            background-clip: padding-box;
            border: 2px solid var(--ds-border-input);
            border-radius: .25rem;
            transition: background-color .2s ease-in-out, border-color .2s ease-in-out;
        }


        .select2-container--default .select2-selection--single .select2-selection__rendered{
            font-size: 14px;
            line-height: 32px;
        }

        .select2-container{
            border:1px solid  #ced4da;
            border-radius:.25rem;
        }


       label{
        color:#000;
       }

       table{
        width: 100%;
        margin-top:15px;
       }

       table th, table td{
        text-align: center;
        vertical-align: middle;
        padding:5px;
       }



    </style>
</head>
<body>
<div id="app">

<div class="container my-5">

    <div class="row">
    <div class="col-md-8">
    <div class="card border-0 shadow p-3 flight-card mb-4">

         <h5 class="card-title">Flight Details</h5>
           <div class="row align-items-center justify-content-center mb-4">
            <div class="col-md-3 text-center">
                <div class="logo-sec mt-3">
                    <img :src="'images/airlines/'+ flight.Segments[0][0].Airline.AirlineCode +'.webp'" class="img-fluid" alt="">
                    <p class="title mt-2">{{ flight.Segments[0][0].Airline.AirlineName}}</p>
                </div>
            </div>
            <div class="col-md-9 text-center">
                <div class="row airport-part d-flex justify-content-between align-items-center">
                    <div class="col-4 airport-name">
                        <h4>{{ convertDTT(flight.Segments[0][0].Origin.DepTime)}}</h4>
                        <h6>{{flight.Segments[0][0].Origin.Airport.AirportName}}, {{flight.Segments[0][0].Origin.Airport.CityName}}, {{flight.Segments[0][0].Origin.Airport.CountryName}}</h6>
                    </div>
                    <div class="col-4 airport-progress px-3">
                        
                        <div class="mb-2">

                             <strong>{{ convertMTHM(flight.Segments[0].length > 1 ? flight.Segments[0][flight.Segments[0].length - 1].AccumulatedDuration : flight.Segments[0][0].Duration) }}</strong>
                             

                        </div>
                        <div class="stop">
                           
                            {{ flight.Segments[0].length - 1 === 0 ? 'Non Stop' : (flight.Segments[0].length - 1) + ((flight.Segments[0].length - 1) > 1 ? ' Stops' : ' Stop') }}
                           
                            
                        </div>
                    </div>
                    <div class="col-4 airport-name arrival">
                        <h4>{{ convertDTT(flight.Segments[0][flight.Segments[0].length - 1].Destination.ArrTime) }}</h4>
                        <h6>{{flight.Segments[0][flight.Segments[0].length - 1].Destination.Airport.AirportName}}, {{flight.Segments[0][flight.Segments[0].length - 1].Destination.Airport.CityName}}, {{flight.Segments[0][flight.Segments[0].length - 1].Destination.Airport.CountryName}}</h6>
                    </div>
                </div>
            </div>

            </div>


            <h5 class="card-title">Return Flight Details</h5>
           <div class="row align-items-center justify-content-center">
            <div class="col-md-3 text-center">
                <div class="logo-sec mt-3">
                    <img :src="'images/airlines/'+ flight_arrival.Segments[0][0].Airline.AirlineCode +'.webp'" class="img-fluid" alt="">
                    <p class="title mt-2">{{ flight_arrival.Segments[0][0].Airline.AirlineName}}</p>
                </div>
            </div>
            <div class="col-md-9 text-center">
                <div class="row airport-part d-flex justify-content-between align-items-center">
                    <div class="col-4 airport-name">
                        <h4>{{ convertDTT(flight_arrival.Segments[0][0].Origin.DepTime)}}</h4>
                        <h6>{{flight_arrival.Segments[0][0].Origin.Airport.AirportName}}, {{flight_arrival.Segments[0][0].Origin.Airport.CityName}}, {{flight_arrival.Segments[0][0].Origin.Airport.CountryName}}</h6>
                    </div>
                    <div class="col-4 airport-progress px-3">
                        
                        <div class="mb-2">

                             <strong>{{ convertMTHM(flight_arrival.Segments[0].length > 1 ? flight_arrival.Segments[0][flight_arrival.Segments[0].length - 1].AccumulatedDuration : flight_arrival.Segments[0][0].Duration) }}</strong>
                             

                        </div>
                        <div class="stop">
                           
                            {{ flight_arrival.Segments[0].length - 1 === 0 ? 'Non Stop' : (flight_arrival.Segments[0].length - 1) + ((flight_arrival.Segments[0].length - 1) > 1 ? ' Stops' : ' Stop') }}
                           
                            
                        </div>
                    </div>
                    <div class="col-4 airport-name arrival">
                        <h4>{{ convertDTT(flight_arrival.Segments[0][flight_arrival.Segments[0].length - 1].Destination.ArrTime) }}</h4>
                        <h6>{{flight_arrival.Segments[0][flight_arrival.Segments[0].length - 1].Destination.Airport.AirportName}}, {{flight_arrival.Segments[0][flight_arrival.Segments[0].length - 1].Destination.Airport.CityName}}, {{flight_arrival.Segments[0][flight_arrival.Segments[0].length - 1].Destination.Airport.CountryName}}</h6>
                    </div>
                </div>
            </div>

            </div>

            <?php /*
            <div class="col-md-2 text-center">
                <div class="price">
                    <div>
                        <h4>INR {{ Math.ceil(flight.Fare.PublishedFare) }}</h4>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center">
                <div class="book-flight">
                    <a href="" class="btn btn-orange fw-bold">Book Now</a>
                </div>
            </div>
            */ ?>
        
    </div>
    </div>

    <div class="col-md-4">
        <div class="card p-4 border-0 shadow">
            <h5 class="card-title">Fare Details</h5>

          <div class="row">
                <div class="col-6 text-start font-weight-medium">
                    Base Fare
                </div>
                <div class="col-6 text-right">
                   {{ (parseFloat(fare_quote.Fare.BaseFare) + parseFloat(fare_quote_arrival.Fare.BaseFare)).toFixed(2) }}
                </div>
            </div>

            <div class="row">
                <div class="col-6 text-start font-weight-medium">
                    Taxes
                </div>
                <div class="col-6 text-right">
                    {{ (parseFloat(fare_quote.Fare.Tax) + parseFloat(fare_quote_arrival.Fare.Tax)).toFixed(2) }}
                </div>
            </div>

            <div class="row">
                <div class="col-6 text-start font-weight-medium">
                    Other Charges
                </div>
                <div class="col-6 text-right">
                   {{ (parseFloat(fare_quote.Fare.OtherCharges) + parseFloat(fare_quote_arrival.Fare.OtherCharges)).toFixed(2) }}
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-6 text-start font-weight-medium">
                    Total
                </div>
                <div class="col-6 text-right">
                   INR {{ (parseFloat(fare_quote.Fare.PublishedFare) + parseFloat(fare_quote_arrival.Fare.PublishedFare)).toFixed(2) }}
                </div>
            </div>

        </div>
    </div>

    </div>
</div>

<div class="container mt-5">


    
    <form id="payment-form">

        <div class="card mb-5">
        <div class="card-body">
        <h5 class="card-title">Passenger Details</h5>


        <div class="alert alert-primary" role="alert">
          <div class="d-flex gap-4">
            <span><i class="fa-solid fa-circle-info icon-primary"></i></span>
            <div class="d-flex flex-column gap-2">
              <h6 class="mb-0">Important</h6>
              <p class="mb-0">Enter name as mentioned on your passport or Government approved IDs.</p>
            </div>
          </div>
        </div>
        


        <?php $adultCount = $_SESSION['data']['adultCount'] ?>

        <?php if(isset($adultCount) && $adultCount > 0): ?>

        <?php for($i = 0; $i < $adultCount; $i++): ?>
        <!-- Start Card -->
        <div class="card my-4">
        <div class="card-body">


        <h6 class="card-title">Adult <?php echo $i + 1; ?></h6>
        <div class="row">
            <div class="form-group col-md-1 px-2">
                <label for="title_A<?php echo $i + 1; ?>">Title</label>
                <select id="title" name="title_A<?php echo $i + 1; ?>" class="form-control" required style="padding:0px;">
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Ms">Ms</option>
                </select>
            </div>
            <div class="form-group col-md-3 px-2">
                <label for="firstName_A<?php echo $i + 1; ?>">First Name</label>
                <input type="text" class="form-control" name="firstName_A<?php echo $i + 1; ?>" id="firstName" placeholder="First Name" required>

            </div>
            <div class="form-group col-md-3 px-2">
                <label for="lastName_A<?php echo $i + 1; ?>">Last Name</label>
                <input type="text" class="form-control" name="lastName_A<?php echo $i + 1; ?>" id="lastName" placeholder="Last Name" required>
            </div>
           
            <div class="form-group col-md-2 px-2">
                <label for="gender">Gender</label>
                <select id="gender" name="gender_A<?php echo $i + 1; ?>" class="form-control" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>


             <div class="form-group col-md-3 px-2">
                <label for="nationality_A<?php echo $i + 1; ?>">Nationality</label>
                <select name="nationality_A<?php echo $i + 1; ?>" class="form-control select2 selectNationality" id="selectNationality" required>
                    <option value="Afghan">Afghan</option>
                    <option value="Albanian">Albanian</option>
                    <option value="Algerian">Algerian</option>
                    <option value="American">American</option>
                    <option value="Andorran">Andorran</option>
                    <option value="Angolan">Angolan</option>
                    <option value="Antiguan and Barbudan">Antiguan and Barbudan</option>
                    <option value="Argentine">Argentine</option>
                    <option value="Armenian">Armenian</option>
                    <option value="Australian">Australian</option>
                    <option value="Austrian">Austrian</option>
                    <option value="Azerbaijani">Azerbaijani</option>
                    <option value="Bahamian">Bahamian</option>
                    <option value="Bahraini">Bahraini</option>
                    <option value="Bangladeshi">Bangladeshi</option>
                    <option value="Barbadian">Barbadian</option>
                    <option value="Belarusian">Belarusian</option>
                    <option value="Belgian">Belgian</option>
                    <option value="Belizean">Belizean</option>
                    <option value="Beninese">Beninese</option>
                    <option value="Bhutanese">Bhutanese</option>
                    <option value="Bolivian">Bolivian</option>
                    <option value="Bosnian and Herzegovinian">Bosnian and Herzegovinian</option>
                    <option value="Botswanan">Botswanan</option>
                    <option value="Brazilian">Brazilian</option>
                    <option value="British">British</option>
                    <option value="Bruneian">Bruneian</option>
                    <option value="Bulgarian">Bulgarian</option>
                    <option value="Burkinabé">Burkinabé</option>
                    <option value="Burmese">Burmese</option>
                    <option value="Burundian">Burundian</option>
                    <option value="Cabo Verdean">Cabo Verdean</option>
                    <option value="Cambodian">Cambodian</option>
                    <option value="Cameroonian">Cameroonian</option>
                    <option value="Canadian">Canadian</option>
                    <option value="Central African">Central African</option>
                    <option value="Chadian">Chadian</option>
                    <option value="Chilean">Chilean</option>
                    <option value="Chinese">Chinese</option>
                    <option value="Colombian">Colombian</option>
                    <option value="Comorian">Comorian</option>
                    <option value="Congolese (Congo-Brazzaville)">Congolese (Congo-Brazzaville)</option>
                    <option value="Congolese (Congo-Kinshasa)">Congolese (Congo-Kinshasa)</option>
                    <option value="Costa Rican">Costa Rican</option>
                    <option value="Croatian">Croatian</option>
                    <option value="Cuban">Cuban</option>
                    <option value="Cypriot">Cypriot</option>
                    <option value="Czech">Czech</option>
                    <option value="Danish">Danish</option>
                    <option value="Djiboutian">Djiboutian</option>
                    <option value="Dominican">Dominican</option>
                    <option value="Dominican (Dominican Republic)">Dominican (Dominican Republic)</option>
                    <option value="Dutch">Dutch</option>
                    <option value="East Timorese">East Timorese</option>
                    <option value="Ecuadorian">Ecuadorian</option>
                    <option value="Egyptian">Egyptian</option>
                    <option value="Emirati">Emirati</option>
                    <option value="Equatorial Guinean">Equatorial Guinean</option>
                    <option value="Eritrean">Eritrean</option>
                    <option value="Estonian">Estonian</option>
                    <option value="Ethiopian">Ethiopian</option>
                    <option value="Fijian">Fijian</option>
                    <option value="Filipino">Filipino</option>
                    <option value="Finnish">Finnish</option>
                    <option value="French">French</option>
                    <option value="Gabonese">Gabonese</option>
                    <option value="Gambian">Gambian</option>
                    <option value="Georgian">Georgian</option>
                    <option value="German">German</option>
                    <option value="Ghanaian">Ghanaian</option>
                    <option value="Greek">Greek</option>
                    <option value="Grenadian">Grenadian</option>
                    <option value="Guatemalan">Guatemalan</option>
                    <option value="Guinean">Guinean</option>
                    <option value="Bissau-Guinean">Bissau-Guinean</option>
                    <option value="Guyanese">Guyanese</option>
                    <option value="Haitian">Haitian</option>
                    <option value="Honduran">Honduran</option>
                    <option value="Hungarian">Hungarian</option>
                    <option value="Icelandic">Icelandic</option>
                    <option value="Indian">Indian</option>
                    <option value="Indonesian">Indonesian</option>
                    <option value="Iranian">Iranian</option>
                    <option value="Iraqi">Iraqi</option>
                    <option value="Irish">Irish</option>
                    <option value="Israeli">Israeli</option>
                    <option value="Italian">Italian</option>
                    <option value="Ivorian">Ivorian</option>
                    <option value="Jamaican">Jamaican</option>
                    <option value="Japanese">Japanese</option>
                    <option value="Jordanian">Jordanian</option>
                    <option value="Kazakhstani">Kazakhstani</option>
                    <option value="Kenyan">Kenyan</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korean">Korean</option>
                    <option value="Kosovar">Kosovar</option>
                    <option value="Kuwaiti">Kuwaiti</option>
                    <option value="Kyrgyzstani">Kyrgyzstani</option>
                    <option value="Lao">Lao</option>
                    <option value="Latvian">Latvian</option>
                    <option value="Lebanese">Lebanese</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberian">Liberian</option>
                    <option value="Libyan">Libyan</option>
                    <option value="Liechtensteiner">Liechtensteiner</option>
                    <option value="Lithuanian">Lithuanian</option>
                    <option value="Luxembourger">Luxembourger</option>
                    <option value="Maldivian">Maldivian</option>
                    <option value="Malian">Malian</option>
                    <option value="Maltese">Maltese</option>
                    <option value="Marshallese">Marshallese</option>
                    <option value="Mauritanian">Mauritanian</option>
                    <option value="Mauritian">Mauritian</option>
                    <option value="Mexican">Mexican</option>
                    <option value="Micronesian">Micronesian</option>
                    <option value="Moldovan">Moldovan</option>
                    <option value="Monégasque">Monégasque</option>
                    <option value="Mongolian">Mongolian</option>
                    <option value="Montenegrin">Montenegrin</option>
                    <option value="Moroccan">Moroccan</option>
                    <option value="Mozambican">Mozambican</option>
                    <option value="Namibian">Namibian</option>
                    <option value="Nauruan">Nauruan</option>
                    <option value="Nepalese">Nepalese</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaraguan">Nicaraguan</option>
                    <option value="Nigerian">Nigerian</option>
                    <option value="Nigerien">Nigerien</option>
                    <option value="North Macedonian">North Macedonian</option>
                    <option value="Norwegian">Norwegian</option>
                    <option value="Omani">Omani</option>
                    <option value="Pakistani">Pakistani</option>
                    <option value="Palauan">Palauan</option>
                    <option value="Palestinian">Palestinian</option>
                    <option value="Panamanian">Panamanian</option>
                    <option value="Papua New Guinean">Papua New Guinean</option>
                    <option value="Paraguayan">Paraguayan</option>
                    <option value="Peruvian">Peruvian</option>
                    <option value="Polish">Polish</option>
                    <option value="Portuguese">Portuguese</option>
                    <option value="Qatari">Qatari</option>
                    <option value="Romanian">Romanian</option>
                    <option value="Russian">Russian</option>
                    <option value="Rwandan">Rwandan</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint Lucian">Saint Lucian</option>
                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                    <option value="Salvadoran">Salvadoran</option>
                    <option value="Samoan">Samoan</option>
                    <option value="San Marinese">San Marinese</option>
                    <option value="São Toméan">São Toméan</option>
                    <option value="Saudi">Saudi</option>
                    <option value="Senegalese">Senegalese</option>
                    <option value="Serbian">Serbian</option>
                    <option value="Seychellois">Seychellois</option>
                    <option value="Sierra Leonean">Sierra Leonean</option>
                    <option value="Singaporean">Singaporean</option>
                    <option value="Slovak">Slovak</option>
                    <option value="Slovenian">Slovenian</option>
                    <option value="Solomon Islander">Solomon Islander</option>
                    <option value="Somali">Somali</option>
                    <option value="South African">South African</option>
                    <option value="South Sudanese">South Sudanese</option>
                    <option value="Spanish">Spanish</option>
                    <option value="Sri Lankan">Sri Lankan</option>
                    <option value="Sudanese">Sudanese</option>
                    <option value="Surinamese">Surinamese</option>
                    <option value="Swazi">Swazi</option>
                    <option value="Swedish">Swedish</option>
                    <option value="Swiss">Swiss</option>
                    <option value="Syrian">Syrian</option>
                    <option value="Taiwanese">Taiwanese</option>
                    <option value="Tajikistani">Tajikistani</option>
                    <option value="Tanzanian">Tanzanian</option>
                    <option value="Thai">Thai</option>
                    <option value="Togolese">Togolese</option>
                    <option value="Tongan">Tongan</option>
                    <option value="Trinidadian and Tobagonian">Trinidadian and Tobagonian</option>
                    <option value="Tunisian">Tunisian</option>
                    <option value="Turkish">Turkish</option>
                    <option value="Turkmen">Turkmen</option>
                    <option value="Tuvaluan">Tuvaluan</option>
                    <option value="Ugandan">Ugandan</option>
                    <option value="Ukrainian">Ukrainian</option>
                    <option value="Uruguayan">Uruguayan</option>
                    <option value="Uzbekistani">Uzbekistani</option>
                    <option value="Vanuatuan">Vanuatuan</option>
                    <option value="Vatican">Vatican</option>
                    <option value="Venezuelan">Venezuelan</option>
                    <option value="Vietnamese">Vietnamese</option>
                    <option value="Yemeni">Yemeni</option>
                    <option value="Zambian">Zambian</option>
                    <option value="Zimbabwean">Zimbabwean</option>
                </select>
            </div>


            <?php if($i == 0): ?>
             <div class="form-group col-md-3 px-2">
                <label for="cellCountryCode_A<?php echo $i + 1; ?>">Mobile Country Code</label>
                <select class="select2 form-control mobile-country-code" id="mobile-country-code" name="cellCountryCode_A<?php echo $i + 1; ?>" required></select>
            </div>
       
            <div class="form-group col-md-3 px-2">
                <label for="contactNumber_A<?php echo $i + 1; ?>">Mobile Number</label>
                <input type="text" class="form-control" id="contactNumber" placeholder="Mobile Number" name="contactNumber_A<?php echo $i + 1; ?>" required>
            </div>
           
            <div class="form-group col-md-3 px-2">
                <label for="email_A<?php echo $i + 1; ?>">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email_A<?php echo $i + 1; ?>" required>
            </div>


            <div class="form-group col-md-3 px-2">
                <label for="address_A<?php echo $i + 1; ?>">Address</label>
                <input type="text" class="form-control" id="address" placeholder="Address" name="address_A<?php echo $i + 1; ?>" required>
            </div>
           

            <div class="form-group col-md-3 px-2">
                <label for="city_A<?php echo $i + 1; ?>">City</label>
                <input type="text" class="form-control" id="city" placeholder="City" name="city_A<?php echo $i + 1; ?>" required>
            </div>
            <div class="form-group col-md-3 px-2">
                <label for="countryCode_A<?php echo $i + 1; ?>">Country Code</label>
                <select class="select2 form-control select-country-code" id="select-country-code" name="countryCode_A<?php echo $i + 1; ?>" required></select>
            </div>

            <?php endif; ?>

           


           


            <div class="form-group col-md-3 px-2" v-if="fare_quote.IsPassportRequiredAtBook == 1">
                <label for="passportNumber_A<?php echo $i + 1; ?>">Passport Number</label>
                <input type="text" class="form-control" id="passportNumber" placeholder="Passport Number" name="passportNumber_A<?php echo $i + 1; ?>" required>
            </div>
      
            <div class="form-group col-md-3 px-2" v-if="fare_quote.IsPassportRequiredAtBook == 1">
                <label for="passportExpiry_A<?php echo $i + 1; ?>">Passport Expiry</label>
                <input type="date" class="form-control" id="passportExpiry" name="passportExpiry_A<?php echo $i + 1; ?>" required>
            </div>
       
       
       
           
            
        </div>

        </div>
        </div><!-- End of Card -->

    <?php endfor; ?>
    <?php endif; ?>






    <?php $childCount = $_SESSION['data']['childCount'] ?>

        <?php if(isset($childCount) && $childCount > 0): ?>

        <?php for($i = 0; $i < $childCount; $i++): ?>
        <!-- Start Card -->
        <div class="card my-4">
        <div class="card-body">


        <h6 class="card-title">Child <?php echo $i + 1; ?></h6>
        <div class="row">
            <div class="form-group col-md-1 px-2">
                <label for="title_C<?php echo $i + 1; ?>">Title</label>
                <select id="title" name="title_C<?php echo $i + 1; ?>" class="form-control" required style="padding: 0px;">
                    <option value="Master">Master</option>
                    <option value="Miss">Miss</option>
                </select>
            </div>
            <div class="form-group col-md-3 px-2">
                <label for="firstName_C<?php echo $i + 1; ?>">First Name</label>
                <input type="text" class="form-control" name="firstName_C<?php echo $i + 1; ?>" id="firstName" placeholder="First Name" required>
            </div>
            <div class="form-group col-md-3 px-2">
                <label for="lastName_C<?php echo $i + 1; ?>">Last Name</label>
                <input type="text" class="form-control" name="lastName_C<?php echo $i + 1; ?>" id="lastName" placeholder="Last Name" required>
            </div>
           
            <div class="form-group col-md-2 px-2">
                <label for="gender_C<?php echo $i + 1; ?>">Gender</label>
                <select id="gender" name="gender_C<?php echo $i + 1; ?>" class="form-control" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>


             <div class="form-group col-md-3 px-2">
                <label for="nationality_C<?php echo $i + 1; ?>">Nationality</label>
                <select name="nationality_C<?php echo $i + 1; ?>" class="form-control select2 selectNationality" id="selectNationality" required>
                    <option value="Afghan">Afghan</option>
                    <option value="Albanian">Albanian</option>
                    <option value="Algerian">Algerian</option>
                    <option value="American">American</option>
                    <option value="Andorran">Andorran</option>
                    <option value="Angolan">Angolan</option>
                    <option value="Antiguan and Barbudan">Antiguan and Barbudan</option>
                    <option value="Argentine">Argentine</option>
                    <option value="Armenian">Armenian</option>
                    <option value="Australian">Australian</option>
                    <option value="Austrian">Austrian</option>
                    <option value="Azerbaijani">Azerbaijani</option>
                    <option value="Bahamian">Bahamian</option>
                    <option value="Bahraini">Bahraini</option>
                    <option value="Bangladeshi">Bangladeshi</option>
                    <option value="Barbadian">Barbadian</option>
                    <option value="Belarusian">Belarusian</option>
                    <option value="Belgian">Belgian</option>
                    <option value="Belizean">Belizean</option>
                    <option value="Beninese">Beninese</option>
                    <option value="Bhutanese">Bhutanese</option>
                    <option value="Bolivian">Bolivian</option>
                    <option value="Bosnian and Herzegovinian">Bosnian and Herzegovinian</option>
                    <option value="Botswanan">Botswanan</option>
                    <option value="Brazilian">Brazilian</option>
                    <option value="British">British</option>
                    <option value="Bruneian">Bruneian</option>
                    <option value="Bulgarian">Bulgarian</option>
                    <option value="Burkinabé">Burkinabé</option>
                    <option value="Burmese">Burmese</option>
                    <option value="Burundian">Burundian</option>
                    <option value="Cabo Verdean">Cabo Verdean</option>
                    <option value="Cambodian">Cambodian</option>
                    <option value="Cameroonian">Cameroonian</option>
                    <option value="Canadian">Canadian</option>
                    <option value="Central African">Central African</option>
                    <option value="Chadian">Chadian</option>
                    <option value="Chilean">Chilean</option>
                    <option value="Chinese">Chinese</option>
                    <option value="Colombian">Colombian</option>
                    <option value="Comorian">Comorian</option>
                    <option value="Congolese (Congo-Brazzaville)">Congolese (Congo-Brazzaville)</option>
                    <option value="Congolese (Congo-Kinshasa)">Congolese (Congo-Kinshasa)</option>
                    <option value="Costa Rican">Costa Rican</option>
                    <option value="Croatian">Croatian</option>
                    <option value="Cuban">Cuban</option>
                    <option value="Cypriot">Cypriot</option>
                    <option value="Czech">Czech</option>
                    <option value="Danish">Danish</option>
                    <option value="Djiboutian">Djiboutian</option>
                    <option value="Dominican">Dominican</option>
                    <option value="Dominican (Dominican Republic)">Dominican (Dominican Republic)</option>
                    <option value="Dutch">Dutch</option>
                    <option value="East Timorese">East Timorese</option>
                    <option value="Ecuadorian">Ecuadorian</option>
                    <option value="Egyptian">Egyptian</option>
                    <option value="Emirati">Emirati</option>
                    <option value="Equatorial Guinean">Equatorial Guinean</option>
                    <option value="Eritrean">Eritrean</option>
                    <option value="Estonian">Estonian</option>
                    <option value="Ethiopian">Ethiopian</option>
                    <option value="Fijian">Fijian</option>
                    <option value="Filipino">Filipino</option>
                    <option value="Finnish">Finnish</option>
                    <option value="French">French</option>
                    <option value="Gabonese">Gabonese</option>
                    <option value="Gambian">Gambian</option>
                    <option value="Georgian">Georgian</option>
                    <option value="German">German</option>
                    <option value="Ghanaian">Ghanaian</option>
                    <option value="Greek">Greek</option>
                    <option value="Grenadian">Grenadian</option>
                    <option value="Guatemalan">Guatemalan</option>
                    <option value="Guinean">Guinean</option>
                    <option value="Bissau-Guinean">Bissau-Guinean</option>
                    <option value="Guyanese">Guyanese</option>
                    <option value="Haitian">Haitian</option>
                    <option value="Honduran">Honduran</option>
                    <option value="Hungarian">Hungarian</option>
                    <option value="Icelandic">Icelandic</option>
                    <option value="Indian">Indian</option>
                    <option value="Indonesian">Indonesian</option>
                    <option value="Iranian">Iranian</option>
                    <option value="Iraqi">Iraqi</option>
                    <option value="Irish">Irish</option>
                    <option value="Israeli">Israeli</option>
                    <option value="Italian">Italian</option>
                    <option value="Ivorian">Ivorian</option>
                    <option value="Jamaican">Jamaican</option>
                    <option value="Japanese">Japanese</option>
                    <option value="Jordanian">Jordanian</option>
                    <option value="Kazakhstani">Kazakhstani</option>
                    <option value="Kenyan">Kenyan</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korean">Korean</option>
                    <option value="Kosovar">Kosovar</option>
                    <option value="Kuwaiti">Kuwaiti</option>
                    <option value="Kyrgyzstani">Kyrgyzstani</option>
                    <option value="Lao">Lao</option>
                    <option value="Latvian">Latvian</option>
                    <option value="Lebanese">Lebanese</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberian">Liberian</option>
                    <option value="Libyan">Libyan</option>
                    <option value="Liechtensteiner">Liechtensteiner</option>
                    <option value="Lithuanian">Lithuanian</option>
                    <option value="Luxembourger">Luxembourger</option>
                    <option value="Maldivian">Maldivian</option>
                    <option value="Malian">Malian</option>
                    <option value="Maltese">Maltese</option>
                    <option value="Marshallese">Marshallese</option>
                    <option value="Mauritanian">Mauritanian</option>
                    <option value="Mauritian">Mauritian</option>
                    <option value="Mexican">Mexican</option>
                    <option value="Micronesian">Micronesian</option>
                    <option value="Moldovan">Moldovan</option>
                    <option value="Monégasque">Monégasque</option>
                    <option value="Mongolian">Mongolian</option>
                    <option value="Montenegrin">Montenegrin</option>
                    <option value="Moroccan">Moroccan</option>
                    <option value="Mozambican">Mozambican</option>
                    <option value="Namibian">Namibian</option>
                    <option value="Nauruan">Nauruan</option>
                    <option value="Nepalese">Nepalese</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaraguan">Nicaraguan</option>
                    <option value="Nigerian">Nigerian</option>
                    <option value="Nigerien">Nigerien</option>
                    <option value="North Macedonian">North Macedonian</option>
                    <option value="Norwegian">Norwegian</option>
                    <option value="Omani">Omani</option>
                    <option value="Pakistani">Pakistani</option>
                    <option value="Palauan">Palauan</option>
                    <option value="Palestinian">Palestinian</option>
                    <option value="Panamanian">Panamanian</option>
                    <option value="Papua New Guinean">Papua New Guinean</option>
                    <option value="Paraguayan">Paraguayan</option>
                    <option value="Peruvian">Peruvian</option>
                    <option value="Polish">Polish</option>
                    <option value="Portuguese">Portuguese</option>
                    <option value="Qatari">Qatari</option>
                    <option value="Romanian">Romanian</option>
                    <option value="Russian">Russian</option>
                    <option value="Rwandan">Rwandan</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint Lucian">Saint Lucian</option>
                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                    <option value="Salvadoran">Salvadoran</option>
                    <option value="Samoan">Samoan</option>
                    <option value="San Marinese">San Marinese</option>
                    <option value="São Toméan">São Toméan</option>
                    <option value="Saudi">Saudi</option>
                    <option value="Senegalese">Senegalese</option>
                    <option value="Serbian">Serbian</option>
                    <option value="Seychellois">Seychellois</option>
                    <option value="Sierra Leonean">Sierra Leonean</option>
                    <option value="Singaporean">Singaporean</option>
                    <option value="Slovak">Slovak</option>
                    <option value="Slovenian">Slovenian</option>
                    <option value="Solomon Islander">Solomon Islander</option>
                    <option value="Somali">Somali</option>
                    <option value="South African">South African</option>
                    <option value="South Sudanese">South Sudanese</option>
                    <option value="Spanish">Spanish</option>
                    <option value="Sri Lankan">Sri Lankan</option>
                    <option value="Sudanese">Sudanese</option>
                    <option value="Surinamese">Surinamese</option>
                    <option value="Swazi">Swazi</option>
                    <option value="Swedish">Swedish</option>
                    <option value="Swiss">Swiss</option>
                    <option value="Syrian">Syrian</option>
                    <option value="Taiwanese">Taiwanese</option>
                    <option value="Tajikistani">Tajikistani</option>
                    <option value="Tanzanian">Tanzanian</option>
                    <option value="Thai">Thai</option>
                    <option value="Togolese">Togolese</option>
                    <option value="Tongan">Tongan</option>
                    <option value="Trinidadian and Tobagonian">Trinidadian and Tobagonian</option>
                    <option value="Tunisian">Tunisian</option>
                    <option value="Turkish">Turkish</option>
                    <option value="Turkmen">Turkmen</option>
                    <option value="Tuvaluan">Tuvaluan</option>
                    <option value="Ugandan">Ugandan</option>
                    <option value="Ukrainian">Ukrainian</option>
                    <option value="Uruguayan">Uruguayan</option>
                    <option value="Uzbekistani">Uzbekistani</option>
                    <option value="Vanuatuan">Vanuatuan</option>
                    <option value="Vatican">Vatican</option>
                    <option value="Venezuelan">Venezuelan</option>
                    <option value="Vietnamese">Vietnamese</option>
                    <option value="Yemeni">Yemeni</option>
                    <option value="Zambian">Zambian</option>
                    <option value="Zimbabwean">Zimbabwean</option>
                </select>
            </div>


            <?php /*
             <div class="form-group col-md-3">
                <label for="cellCountryCode_C<?php echo $i + 1; ?>">Mobile Country Code</label>
                <select class="select2 form-control mobile-country-code" id="mobile-country-code" name="cellCountryCode_C<?php echo $i + 1; ?>" required></select>
            </div>
       
            <div class="form-group col-md-3">
                <label for="contactNumber_C<?php echo $i + 1; ?>">Mobile Number</label>
                <input type="text" class="form-control" id="contactNumber" placeholder="Mobile Number" name="contactNumber_C<?php echo $i + 1; ?>" required>
            </div>
           
            <div class="form-group col-md-3">
                <label for="email_C<?php echo $i + 1; ?>">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email_C<?php echo $i + 1; ?>" required>
            </div>


            <div class="form-group col-md-3">
                <label for="address_C<?php echo $i + 1; ?>">Address</label>
                <input type="text" class="form-control" id="address" placeholder="Address" name="address_C<?php echo $i + 1; ?>" required>
            </div>
           

            <div class="form-group col-md-3">
                <label for="city_C<?php echo $i + 1; ?>">City</label>
                <input type="text" class="form-control" id="city" placeholder="City" name="city_C<?php echo $i + 1; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label for="countryCode_C<?php echo $i + 1; ?>">Country Code</label>
                <select class="select2 form-control select-country-code" id="select-country-code" name="countryCode_C<?php echo $i + 1; ?>" required></select>
            </div>


           */ ?>


           


            <div class="form-group col-md-3 px-2" v-if="fare_quote.IsPassportRequiredAtBook == 1">
                <label for="passportNumber_C<?php echo $i + 1; ?>">Passport Number</label>
                <input type="text" class="form-control" id="passportNumber" placeholder="Passport Number" name="passportNumber_C<?php echo $i + 1; ?>" required>
            </div>
      
            <div class="form-group col-md-3 px-2" v-if="fare_quote.IsPassportRequiredAtBook == 1">
                <label for="passportExpiry_C<?php echo $i + 1; ?>">Passport Expiry</label>
                <input type="date" class="form-control" id="passportExpiry" name="passportExpiry_C<?php echo $i + 1; ?>" required>
            </div>
       
       
       
           
            
        </div>

        </div>
        </div><!-- End of Card -->

    <?php endfor; ?>
    <?php endif; ?>





    <?php $infantCount = $_SESSION['data']['infantCount'] ?>

        <?php if(isset($infantCount) && $infantCount > 0): ?>

        <?php for($i = 0; $i < $infantCount; $i++): ?>
        <!-- Start Card -->
        <div class="card my-4">
        <div class="card-body">


        <h6 class="card-title">Infant <?php echo $i + 1; ?></h6>
        <div class="row">
            <div class="form-group col-md-1 px-2">
                <label for="title_I<?php echo $i + 1; ?>">Title</label>
                <select id="title" name="title_I<?php echo $i + 1; ?>" class="form-control" required style="padding:0px;">
                    <option value="Infant">Infant</option>
                </select>
            </div>
            <div class="form-group col-md-3 px-2">
                <label for="firstName_I<?php echo $i + 1; ?>">First Name</label>
                <input type="text" class="form-control" name="firstName_I<?php echo $i + 1; ?>" id="firstName" placeholder="First Name" required>
            </div>
            <div class="form-group col-md-3 px-2">
                <label for="lastName_I<?php echo $i + 1; ?>">Last Name</label>
                <input type="text" class="form-control" name="lastName_I<?php echo $i + 1; ?>" id="lastName" placeholder="Last Name" required>
            </div>
           
            <div class="form-group col-md-2 px-2">
                <label for="gender_I<?php echo $i + 1; ?>">Gender</label>
                <select id="gender" name="gender_I<?php echo $i + 1; ?>" class="form-control" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>


             <div class="form-group col-md-3 px-2">
                <label for="nationality_I<?php echo $i + 1; ?>">Nationality</label>
                <select name="nationality_I<?php echo $i + 1; ?>" class="form-control select2 selectNationality" id="selectNationality" required>
                    <option value="Afghan">Afghan</option>
                    <option value="Albanian">Albanian</option>
                    <option value="Algerian">Algerian</option>
                    <option value="American">American</option>
                    <option value="Andorran">Andorran</option>
                    <option value="Angolan">Angolan</option>
                    <option value="Antiguan and Barbudan">Antiguan and Barbudan</option>
                    <option value="Argentine">Argentine</option>
                    <option value="Armenian">Armenian</option>
                    <option value="Australian">Australian</option>
                    <option value="Austrian">Austrian</option>
                    <option value="Azerbaijani">Azerbaijani</option>
                    <option value="Bahamian">Bahamian</option>
                    <option value="Bahraini">Bahraini</option>
                    <option value="Bangladeshi">Bangladeshi</option>
                    <option value="Barbadian">Barbadian</option>
                    <option value="Belarusian">Belarusian</option>
                    <option value="Belgian">Belgian</option>
                    <option value="Belizean">Belizean</option>
                    <option value="Beninese">Beninese</option>
                    <option value="Bhutanese">Bhutanese</option>
                    <option value="Bolivian">Bolivian</option>
                    <option value="Bosnian and Herzegovinian">Bosnian and Herzegovinian</option>
                    <option value="Botswanan">Botswanan</option>
                    <option value="Brazilian">Brazilian</option>
                    <option value="British">British</option>
                    <option value="Bruneian">Bruneian</option>
                    <option value="Bulgarian">Bulgarian</option>
                    <option value="Burkinabé">Burkinabé</option>
                    <option value="Burmese">Burmese</option>
                    <option value="Burundian">Burundian</option>
                    <option value="Cabo Verdean">Cabo Verdean</option>
                    <option value="Cambodian">Cambodian</option>
                    <option value="Cameroonian">Cameroonian</option>
                    <option value="Canadian">Canadian</option>
                    <option value="Central African">Central African</option>
                    <option value="Chadian">Chadian</option>
                    <option value="Chilean">Chilean</option>
                    <option value="Chinese">Chinese</option>
                    <option value="Colombian">Colombian</option>
                    <option value="Comorian">Comorian</option>
                    <option value="Congolese (Congo-Brazzaville)">Congolese (Congo-Brazzaville)</option>
                    <option value="Congolese (Congo-Kinshasa)">Congolese (Congo-Kinshasa)</option>
                    <option value="Costa Rican">Costa Rican</option>
                    <option value="Croatian">Croatian</option>
                    <option value="Cuban">Cuban</option>
                    <option value="Cypriot">Cypriot</option>
                    <option value="Czech">Czech</option>
                    <option value="Danish">Danish</option>
                    <option value="Djiboutian">Djiboutian</option>
                    <option value="Dominican">Dominican</option>
                    <option value="Dominican (Dominican Republic)">Dominican (Dominican Republic)</option>
                    <option value="Dutch">Dutch</option>
                    <option value="East Timorese">East Timorese</option>
                    <option value="Ecuadorian">Ecuadorian</option>
                    <option value="Egyptian">Egyptian</option>
                    <option value="Emirati">Emirati</option>
                    <option value="Equatorial Guinean">Equatorial Guinean</option>
                    <option value="Eritrean">Eritrean</option>
                    <option value="Estonian">Estonian</option>
                    <option value="Ethiopian">Ethiopian</option>
                    <option value="Fijian">Fijian</option>
                    <option value="Filipino">Filipino</option>
                    <option value="Finnish">Finnish</option>
                    <option value="French">French</option>
                    <option value="Gabonese">Gabonese</option>
                    <option value="Gambian">Gambian</option>
                    <option value="Georgian">Georgian</option>
                    <option value="German">German</option>
                    <option value="Ghanaian">Ghanaian</option>
                    <option value="Greek">Greek</option>
                    <option value="Grenadian">Grenadian</option>
                    <option value="Guatemalan">Guatemalan</option>
                    <option value="Guinean">Guinean</option>
                    <option value="Bissau-Guinean">Bissau-Guinean</option>
                    <option value="Guyanese">Guyanese</option>
                    <option value="Haitian">Haitian</option>
                    <option value="Honduran">Honduran</option>
                    <option value="Hungarian">Hungarian</option>
                    <option value="Icelandic">Icelandic</option>
                    <option value="Indian">Indian</option>
                    <option value="Indonesian">Indonesian</option>
                    <option value="Iranian">Iranian</option>
                    <option value="Iraqi">Iraqi</option>
                    <option value="Irish">Irish</option>
                    <option value="Israeli">Israeli</option>
                    <option value="Italian">Italian</option>
                    <option value="Ivorian">Ivorian</option>
                    <option value="Jamaican">Jamaican</option>
                    <option value="Japanese">Japanese</option>
                    <option value="Jordanian">Jordanian</option>
                    <option value="Kazakhstani">Kazakhstani</option>
                    <option value="Kenyan">Kenyan</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korean">Korean</option>
                    <option value="Kosovar">Kosovar</option>
                    <option value="Kuwaiti">Kuwaiti</option>
                    <option value="Kyrgyzstani">Kyrgyzstani</option>
                    <option value="Lao">Lao</option>
                    <option value="Latvian">Latvian</option>
                    <option value="Lebanese">Lebanese</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberian">Liberian</option>
                    <option value="Libyan">Libyan</option>
                    <option value="Liechtensteiner">Liechtensteiner</option>
                    <option value="Lithuanian">Lithuanian</option>
                    <option value="Luxembourger">Luxembourger</option>
                    <option value="Maldivian">Maldivian</option>
                    <option value="Malian">Malian</option>
                    <option value="Maltese">Maltese</option>
                    <option value="Marshallese">Marshallese</option>
                    <option value="Mauritanian">Mauritanian</option>
                    <option value="Mauritian">Mauritian</option>
                    <option value="Mexican">Mexican</option>
                    <option value="Micronesian">Micronesian</option>
                    <option value="Moldovan">Moldovan</option>
                    <option value="Monégasque">Monégasque</option>
                    <option value="Mongolian">Mongolian</option>
                    <option value="Montenegrin">Montenegrin</option>
                    <option value="Moroccan">Moroccan</option>
                    <option value="Mozambican">Mozambican</option>
                    <option value="Namibian">Namibian</option>
                    <option value="Nauruan">Nauruan</option>
                    <option value="Nepalese">Nepalese</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaraguan">Nicaraguan</option>
                    <option value="Nigerian">Nigerian</option>
                    <option value="Nigerien">Nigerien</option>
                    <option value="North Macedonian">North Macedonian</option>
                    <option value="Norwegian">Norwegian</option>
                    <option value="Omani">Omani</option>
                    <option value="Pakistani">Pakistani</option>
                    <option value="Palauan">Palauan</option>
                    <option value="Palestinian">Palestinian</option>
                    <option value="Panamanian">Panamanian</option>
                    <option value="Papua New Guinean">Papua New Guinean</option>
                    <option value="Paraguayan">Paraguayan</option>
                    <option value="Peruvian">Peruvian</option>
                    <option value="Polish">Polish</option>
                    <option value="Portuguese">Portuguese</option>
                    <option value="Qatari">Qatari</option>
                    <option value="Romanian">Romanian</option>
                    <option value="Russian">Russian</option>
                    <option value="Rwandan">Rwandan</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint Lucian">Saint Lucian</option>
                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                    <option value="Salvadoran">Salvadoran</option>
                    <option value="Samoan">Samoan</option>
                    <option value="San Marinese">San Marinese</option>
                    <option value="São Toméan">São Toméan</option>
                    <option value="Saudi">Saudi</option>
                    <option value="Senegalese">Senegalese</option>
                    <option value="Serbian">Serbian</option>
                    <option value="Seychellois">Seychellois</option>
                    <option value="Sierra Leonean">Sierra Leonean</option>
                    <option value="Singaporean">Singaporean</option>
                    <option value="Slovak">Slovak</option>
                    <option value="Slovenian">Slovenian</option>
                    <option value="Solomon Islander">Solomon Islander</option>
                    <option value="Somali">Somali</option>
                    <option value="South African">South African</option>
                    <option value="South Sudanese">South Sudanese</option>
                    <option value="Spanish">Spanish</option>
                    <option value="Sri Lankan">Sri Lankan</option>
                    <option value="Sudanese">Sudanese</option>
                    <option value="Surinamese">Surinamese</option>
                    <option value="Swazi">Swazi</option>
                    <option value="Swedish">Swedish</option>
                    <option value="Swiss">Swiss</option>
                    <option value="Syrian">Syrian</option>
                    <option value="Taiwanese">Taiwanese</option>
                    <option value="Tajikistani">Tajikistani</option>
                    <option value="Tanzanian">Tanzanian</option>
                    <option value="Thai">Thai</option>
                    <option value="Togolese">Togolese</option>
                    <option value="Tongan">Tongan</option>
                    <option value="Trinidadian and Tobagonian">Trinidadian and Tobagonian</option>
                    <option value="Tunisian">Tunisian</option>
                    <option value="Turkish">Turkish</option>
                    <option value="Turkmen">Turkmen</option>
                    <option value="Tuvaluan">Tuvaluan</option>
                    <option value="Ugandan">Ugandan</option>
                    <option value="Ukrainian">Ukrainian</option>
                    <option value="Uruguayan">Uruguayan</option>
                    <option value="Uzbekistani">Uzbekistani</option>
                    <option value="Vanuatuan">Vanuatuan</option>
                    <option value="Vatican">Vatican</option>
                    <option value="Venezuelan">Venezuelan</option>
                    <option value="Vietnamese">Vietnamese</option>
                    <option value="Yemeni">Yemeni</option>
                    <option value="Zambian">Zambian</option>
                    <option value="Zimbabwean">Zimbabwean</option>
                </select>
            </div>


            <?php /*
             <div class="form-group col-md-3">
                <label for="cellCountryCode_I<?php echo $i + 1; ?>">Mobile Country Code</label>
                <select class="select2 form-control mobile-country-code" id="mobile-country-code" name="cellCountryCode_I<?php echo $i + 1; ?>" required></select>
            </div>
       
            <div class="form-group col-md-3">
                <label for="contactNumber_I<?php echo $i + 1; ?>">Mobile Number</label>
                <input type="text" class="form-control" id="contactNumber" placeholder="Mobile Number" name="contactNumber_I<?php echo $i + 1; ?>" required>
            </div>
           
            <div class="form-group col-md-3">
                <label for="email_I<?php echo $i + 1; ?>">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email_I<?php echo $i + 1; ?>" required>
            </div>


            <div class="form-group col-md-3">
                <label for="address_I<?php echo $i + 1; ?>">Address</label>
                <input type="text" class="form-control" id="address" placeholder="Address" name="address_I<?php echo $i + 1; ?>" required>
            </div>
           

            <div class="form-group col-md-3">
                <label for="city_I<?php echo $i + 1; ?>">City</label>
                <input type="text" class="form-control" id="city" placeholder="City" name="city_I<?php echo $i + 1; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label for="countryCode_I<?php echo $i + 1; ?>">Country Code</label>
                <select class="select2 form-control select-country-code" id="select-country-code" name="countryCode_I<?php echo $i + 1; ?>" required></select>
            </div>
    

            */ ?>

        

            <div class="form-group col-md-3 px-2" v-if="fare_quote.IsPassportRequiredAtBook == 1">
                <label for="passportNumber_I<?php echo $i + 1; ?>">Passport Number</label>
                <input type="text" class="form-control" id="passportNumber" placeholder="Passport Number" name="passportNumber_I<?php echo $i + 1; ?>" required>
            </div>
      
            <div class="form-group col-md-3 px-2" v-if="fare_quote.IsPassportRequiredAtBook == 1">
                <label for="passportExpiry_I<?php echo $i + 1; ?>">Passport Expiry</label>
                <input type="date" class="form-control" id="passportExpiry" name="passportExpiry_I<?php echo $i + 1; ?>" required>
            </div>
       
       
       
           
            
        </div>

        </div>
        </div><!-- End of Card -->

    <?php endfor; ?>
    <?php endif; ?>


        </div>
        </div>


        <div class="card mb-5" v-if="fare_quote.GSTAllowed == 1">
          <div class="card-body">
            <h5 class="card-title">GST Details (Optional)</h5>
            <div class="row">
                <div class="col-md-4 px-2">
                    <div class="form-group">
                        <label for="GSTCompanyName">Company Name</label>
                    <input type="text" class="form-control" name="GSTCompanyName" placeholder="Company Name">
                </div>
                </div>

                 <div class="col-md-4 px-2">
                     <div class="form-group">
                    <label for="GSTNumber">GST Number</label>
                    <input type="text" class="form-control" name="GSTNumber" placeholder="GST Number">
                </div>
                </div>

                 <div class="col-md-4 px-2">
                     <div class="form-group">
                    <label for="GSTCompanyAddress">Address</label>
                    <input type="text" class="form-control" name="GSTCompanyAddress" placeholder="Address">
                </div>
                </div>


                 <div class="col-md-4 px-2">
                     <div class="form-group">
                    <label for="GSTCompanyContactNumber">Contact Number</label>
                    <input type="text" class="form-control" name="GSTCompanyContactNumber" placeholder="Contact Number">
                </div>
                </div>


                 <div class="col-md-4 px-2">
                     <div class="form-group">
                    <label for="GSTCompanyEmail">Company Email</label>
                    <input type="email" class="form-control" name="GSTCompanyEmail" placeholder="Company Email">
                </div>
                </div>


                
            </div>
            
          
               
          </div>
        </div>
       
        <input type="submit" value="Complete Booking" class="btn btn-primary btn-orange border-0"/>
    </form>
</div>

<div class="container my-5">
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">
            Fare Rules
        </h5>
        <?php 

            if(isset($fare_rules)){
                $fr = json_decode($fare_rules,true);

                if(isset($fr) && is_array($fr)){
                    echo isset($fr['Response']['FareRules'][0]['FareRuleDetail']) ? $fr['Response']['FareRules'][0]['FareRuleDetail'] : '';
                  
                }
            }
         ?>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            Return Fare Rules
        </h5>
        <?php 

            if(isset($fare_rules_arrival)){
                $fr_arrival = json_decode($fare_rules_arrival,true);

                if(isset($fr_arrival) && is_array($fr_arrival)){
                    echo isset($fr_arrival['Response']['FareRules'][0]['FareRuleDetail']) ? $fr_arrival['Response']['FareRules'][0]['FareRuleDetail'] : '';
                  
                }
            }
         ?>
    </div>
</div>
</div>



</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
$(document).ready(function() {

    $('.selectNationality').select2();
    $('.selectNationality').val('Indian').trigger('change');

    $('.select-country-code').select2();

    $.ajax({
        url: 'countrycodes.json',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            var select = $('.select-country-code');
            $.each(data, function(key, value) {
                select.append('<option value="' + value.code + '">' + value.name + ' (' + value.code + ') ' + '</option>');

                $('.select-country-code').val('IN').trigger('change');
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching JSON data:', error);
        }
    });


    $('.mobile-country-code').select2();

    $.ajax({
        url: 'countrycodes.json',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            var select = $('.mobile-country-code');
            $.each(data, function(key, value) {
                select.append('<option value="' + value.dial_code + '">' + value.name + ' (' + value.dial_code + ') ' + '</option>');
                $('.mobile-country-code').val('+91').trigger('change');
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching JSON data:', error);
        }
    });
});
</script>



<script>
    const app = Vue.createApp({
        data() {

           

            return {
                flight:<?php echo isset($search["Response"]["Results"][0][$departure]) ? json_encode($search["Response"]["Results"][0][$departure]) : null;?>,
                fare_quote: <?php echo isset($fare_quote) ? json_encode($fare_quote) : null;?>,

                flight_arrival:<?php echo isset($search["Response"]["Results"][1][$arrival]) ? json_encode($search["Response"]["Results"][1][$arrival]) : null;?>,
                fare_quote_arrival: <?php echo isset($fare_quote_arrival) ? json_encode($fare_quote_arrival) : null;?>,
            };
        },
      

        methods: {

       

            convertMTHM(minutes) {
              const hours = Math.floor(minutes / 60);
              const remainingMinutes = minutes % 60;
              return `${hours}h ${remainingMinutes}m`;
            },

            convertDTT(dateTimeString) {
              const dateTime = new Date(dateTimeString);
              const hours = dateTime.getHours().toString().padStart(2, '0'); // Get hours in 2-digit format
              const minutes = dateTime.getMinutes().toString().padStart(2, '0'); // Get minutes in 2-digit format
              return `${hours}:${minutes}`; // Combine hours and minutes with ":"
            },


        
        },

        mounted() {
          window.fareData = this.fare_quote;
          window.fareDataArrival = this.fare_quote_arrival;
        }
    });

    app.mount('#app');
    window.vueInstance = app;
</script>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $(document).ready(function(){

          

           $('#payment-form').on('submit', function(event) {
                // Prevent the default form submission
                event.preventDefault();

                // Check if the form is valid
                if (this.checkValidity()) {
                    var form = $(this);

                var formData = form.serializeArray();
                //console.log(formData);

                var receiptNumber = '12345';
                var amount = Math.ceil(window.fareData.Fare.PublishedFare) + Math.ceil(window.fareDataArrival.Fare.PublishedFare) ;
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
                                        razorpay_signature: response.razorpay_signature,
                                    },
                                    success: function(data) {
                                        $.ajax({
                                            url: 'store_session.php',
                                            type: 'POST',
                                            data: {
                                                responseData: JSON.stringify(data),
                                                formData: formData
                                            },
                                            success: function(response) {
                                                // Redirect to book_ticket.php on success
                                                //console.log(response);
                                                window.location.href = 'book_ticket_ard.php?departure=<?php echo $_GET['departure']; ?>&arrival=<?php echo $_GET['arrival']; ?>';
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Error storing session data:', error);
                                            }
                                        });
                                    }
                                });
                            },
                            "prefill": {
                                "name": form.find('input[name="firstName_A1"]').val(),
                                "email": form.find('input[name="email_A1"]').val()
                            },
                            "theme": {
                                "color": "#28B4A4"
                            }
                        };

                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    }
                });

                } //endif
            });
        });
    </script>



</body>
</html>


