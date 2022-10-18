<?php
session_start();
$email = $fname = $lname = $errorMsg = $pwd_hashed = "";
$success = true;

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) {

    //Email
    if (empty($_POST["user_emailaddress"]) || empty($_POST["user_password"])) {       //Check if email exist
        $errorMsg .= "Please enter an email and password.<br>";
        $success = false;
    } else {
        $email = sanitize_input($_POST["user_emailaddress"]);
    }

    //DB handling
    if ($success) {
        authenticateUser();
    }
} else {
    $errorMsg .= "You are already logged in!<br>";
    $success = false;
}

//Helper function that checks input for malicious or unwanted content.
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function authenticateUser() {
    global $fname, $lname, $email, $pwd_hashed, $errorMsg, $success;
    // Create database connection.    
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);
    // Check connection    
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
        $conn->close();
        return;
    }
    // Prepare the statement:        
    $stmt = $conn->prepare("SELECT * FROM Customer WHERE email=?");
    // Bind & execute the query statement:        
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Note that email field is unique, so should only have            
        // one row in the result set.            
        $row = $result->fetch_assoc();
        $fname = $row["first_name"];
        $lname = $row["last_name"];
        $pwd_hashed = $row["password"];
        // Check if the password matches:            
        if (!password_verify($_POST["user_password"], $pwd_hashed)) {
            // Don't be too specific with the error message - hackers don't                
            // need to know which one they got right or wrong. :)                
            $errorMsg .= "Email not found or password doesn't match...\n";
            $success = false;
        } else {
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["fname"] = $row["first_name"];
            $_SESSION["lname"] = $row["last_name"];
        }
    } else {
        $errorMsg .= "Email not found or password doesn't match...";
        $success = false;
    }
    $stmt->close();

    $conn->close();
}
?>

<!--<html lang="en">
    //<?php include "head.php"; ?>
    <body>
        //<?php
//        //Success handling
//        if ($success) {
////            echo "<div class='section-header text-center'><h2>Login successful!</h2>";
////            echo "<form action='home.php' method='get'><button type='submit' class='btn btn-success'>Start Shopping!</button></form></div>";
////            echo "<p>Welcome back, " . $fname . " " . $lname . ".</p>";
//        } else {
//            echo "<div class='section-header text-center'><h2>Oops!</h2>";
//            echo "<p>" . $errorMsg . "</p>";
//            echo "<form action='login.php' method='get'><button type='submit' class='btn btn-danger'>Return to Log In</button></form></div>";
//        }
//        
?> 
         header section starts  
        //<?php include "nav.php"; ?>
         header section ends 
    </body>
</html>-->


<html>
    <title>Login Results</title>
    <?php include "head.php"; ?>
    <body>
        <?php include "nav.php"; ?>
        <main class = "resultContainer">
            <div class ="content">
                <?php
                if ($success) {
                    echo "<h2>Login successful!</h2>";
                    echo "<h4>Welcome back, ", $fname . " " . $lname . ".</h4>";
                    echo "<meta http-equiv=\"refresh\" content=\"3;URL=home.php\">";
                } else {
                    echo "<h2>Oops!</h2>";
                    echo "<h4>The following errors were detected:</h4>";
                    echo "<p>" . $errorMsg . "</p>";
                    echo "<a href='login.php' class='btn btn-danger'>Return to Login</a>";
                }
                ?>
                <br>
            </div>
        </main>
    </body>
</html>