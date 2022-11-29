<?php
session_start();

include "helper-functions.php";

if (!(isset($_SESSION["id"]) && isset($_POST["order_id"]))) {
    header("refresh: 0; url=order-history.php");
    exit();
}

$cust_id = sanitize_input($_SESSION["id"]);
$order_id = sanitize_input($_POST["order_id"]);

$conn = make_connection();

// 1. Check if this user is allowed to review for this order_id
$query = 'SELECT * FROM SMart.Order WHERE id = ? AND cust_id = ?';
$result = payload_deliver($conn, $query, "ii", $params = array($cust_id, $order_id));

if (mysqli_num_rows($result) != 0) {
    $conn->close();
    header("refresh: 0; url=order-history.php");
    exit();
}

// 2. Get order items
$query = 'SELECT oi.prod_id, p.name, p.image_url FROM Order_Items as oi 
        INNER JOIN Product as p ON oi.prod_id = p.id WHERE oi.order_id = ?';
$result = payload_deliver($conn, $query, "i", $params = array($order_id));

// Store data into array
$order_items = array();

while ($row = mysqli_fetch_assoc($result)) {
    array_push($order_items, $row);
}

$conn->close();
?>

<!DOCTYPE html>

<script defer src="js/review.js"></script>

<html lang="en">
    <title>Shop</title>
    <?php
    include "head.php";
    ?>    
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->
        <div class="heading">
            <h1>order history</h1>
            <p> <a href="home.php">home >></a> product review </p>
        </div>

        <section class="products">
            <h1 class="title"> product <span>review</span> </h1>

            <?php
            foreach ($order_items as $order_items_row) {
                echo '<div class="ratingReview" prod_id="' . $order_items_row["prod_id"] . '">
                        <div class="master-msg">
                            <div class="prodDetails"> 
                                <h1>' . $order_items_row["name"] . '</h1>
                            </div>
                            <h2>rating:</h2>
                        </div>
                            <div class="rating-component">
                                <div class="stars-box">
                                    <i class="star fa fa-star" title="1 star"  data-value="1"></i>
                                    <i class="star fa fa-star" title="2 stars" data-value="2"></i>
                                    <i class="star fa fa-star" title="3 stars" data-value="3"></i>
                                    <i class="star fa fa-star" title="4 stars" data-value="4"></i>
                                    <i class="star fa fa-star" title="5 stars" data-value="5"></i>
                                </div>
                            </div>
                            <div class="master-msg">
                                <h2>write your review:</h2>
                            </div>
                            <div class="tags-box">
                                <input style="height:100px; width:100%;" type="text" class="tag form-control" name="comment" placeholder="please enter your review">
                            </div>
                        </div>
                        <br>';
            }
            ?>
            <div class="button-box">
                <input type="submit" class="done btn btn-warning" style="display: inline-block;" value="Add review"/>
            </div>
            
            <div class="submited-box">
                <div class="loader">
                </div>
                <div class="success-message"><h1> Thank you! <h1>
                </div>
            </div>

        </section>

        <?php include "footer.php"; ?>

    </body>
</html>