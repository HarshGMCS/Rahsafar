<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "harsh";
$database = "2024_rahsafar_api";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Get user input
$q = $_GET['q'];

// Query database for auto-suggestions based on the input
$query = "
    SELECT city_name, city_code, country_name, country_code
    FROM airports
    WHERE city_name LIKE '%$q%'
    OR country_name LIKE '%$q%'
    LIMIT 5
";

$result = $conn->query($query);


// Check if query was successful
/*if (!$result) {
    die("Query failed: " . $conn->error);
}*/


// Check if any results were returned
if ($result->num_rows > 0) {
    // Fetch data and encode as JSON
    $suggestions = [];
    while($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
    echo json_encode($suggestions);
} else {
     $suggestions = [];

     echo json_encode($suggestions);
}

// Close connection
$conn->close();
?>