

<?php

$servername = "localhost";
$username = "dev";
$password = ""; #input password
$dbName = 'SMart';

$conn = new mysqli($servername, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    $errorMsg = "Connection failed:" . $conn->connect_error;
    $success = false;
} else {
     $result =  $conn->query("SELECT * FROM Category");
     
     while ($row = $result->fetch_assoc()) {
         echo "<textarea readonly>" . $row['name'] . " </textarea>";
     }
}
?>
