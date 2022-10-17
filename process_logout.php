<?php
session_start();
$success = true;
//Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) {
    $success = false;
} else {
    $fname = $_SESSION["fname"];
    $lname = $_SESSION["lname"];
    //Reset all session var
    unset($_SESSION["loggedin"]);
    unset($_SESSION["fname"]);
    unset($_SESSION["lname"]);
    unset($_SESSION["id"]);
}
?>

<html lang="en">
    <?php include "head.php"; ?>
    <body>
        <?php
        //Success handling
        if ($success) {
            echo "<div class='section-header text-center'><h2>Logout successful!</h2>";
            echo "<p>See you soon, $fname $lname.</p>";
            echo "<form action='home.php' method='get'><button type='submit' class='btn btn-success'>Return to Home</button></form></div>";
        } else {
            echo "<div class='section-header text-center'><h2>Oops!</h2>";
            echo "<h2>The following errors were detected:</h2>";
            echo "<p>You are not logged in!</p>";
            echo "<form action='home.php' method='get'><button type='submit' class='btn btn-danger'>Return to Home</button></form></div>";
        }
        ?> 
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->
    </body>
</html>