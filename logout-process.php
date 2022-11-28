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
    unset($_SESSION["staff"]);
    unset($_SESSION["fname"]);
    unset($_SESSION["lname"]);
    unset($_SESSION["id"]);
}
?>


<html>
    <title>Login Results</title>
    <?php include "head.php"; ?>
    <body>
        <?php include "nav.php"; ?>
        <main class = "resultContainer">
            <div class="content">
                <?php
                if ($success) {
                    echo "<h2>Logout successful!</h2>";
                    echo "<h4>See you soon, $fname $lname.</h4>";
                    echo "<meta http-equiv=\"refresh\" content=\"3;URL=home.php\">";
                } else {
                    echo "<h2>Oops!</h2>";
                    echo "<h4> You're not logged in! </h4>";
                    echo "<p>" . $errorMsg . "</p>";
                    echo "<meta http-equiv=\"refresh\" content=\"3;URL=login.php\">";
                }
                ?>
                <br>
            </div>
        </main>
    </body>
</html>