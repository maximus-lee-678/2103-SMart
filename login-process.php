<?php
session_start();
require 'vendor/autoload.php';

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
    
    $config = parse_ini_file('../../private/mongo-config.ini');
    $client = new MongoDB\Client("mongodb://" . $config['username'] . ":" . $config['password'] . "@localhost:27017");
    $db = $client->SMart;
    
    
    // Prepare the statement:        
    $query = array("email" => $email);
    $result = $db->Customer->find($query)->toArray()[0];
    
    if (!empty($result)) {
        // Note that email field is unique, so should only have            
        // one row in the result set.   
        $fname = $result["first_name"];
        $lname = $result["last_name"];
        $pwd_hashed = $result["password"];
        // Check if the password matches:            
        if (!password_verify($_POST["user_password"], $pwd_hashed)) {
            // Don't be too specific with the error message - hackers don't                
            // need to know which one they got right or wrong. :)                
            $errorMsg .= "Password doesn't match...\n";
            $success = false;
        } else {
            $_SESSION["loggedin"] = true;
            $_SESSION["staff"] = false;
            $_SESSION["id"] = $result["id"];
            $_SESSION["fname"] = $result["first_name"];
            $_SESSION["lname"] = $result["last_name"];
        }
    } else {
        // Prepare the statement:        
        $stmt = $conn->prepare("SELECT * FROM Staff WHERE email=?");
        // Bind & execute the query statement:        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $fname = $row["first_name"];
            $lname = $row["last_name"];
            $pwd_hashed = $row["password"];
            if (!password_verify($_POST["user_password"], $pwd_hashed)) {
                $errorMsg .= "Password doesn't match...\n";
                $success = false;
            } else {
                $_SESSION["loggedin"] = true;
                $_SESSION["staff"] = true;
                $_SESSION["id"] = $row["id"];
                $_SESSION["fname"] = $row["first_name"];
                $_SESSION["lname"] = $row["last_name"];
            }
        } else {
            $errorMsg .= "Email not found ...";
            $success = false;
        }
    }
    

    $conn->close();
}
?>

<html>
    <title>Login Results</title>
    <?php include "head.php"; ?>
    <body>
        <?php include "nav.php"; ?>
        <main class = "resultContainer">
            <div class ="content">
                <?php
                if ($success) {
                    if ($_SESSION["staff"]) {
                        echo "<h2>Login successful!</h2>";
                        echo "<h4>Welcome back, ", $fname . " " . $lname . ".</h4>";
                        echo "<meta http-equiv=\"refresh\" content=\"3;URL=staff-home.php\">";
                    } else {
                        echo "<h2>Login successful!</h2>";
                        echo "<h4>Welcome back, ", $fname . " " . $lname . ".</h4>";
                        echo "<meta http-equiv=\"refresh\" content=\"3;URL=home.php\">";
                    }
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