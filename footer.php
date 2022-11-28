<!-- footer section starts  -->

<section class="footer">

    <div class="box-container">

        <div class="box">
            <h3>quick links</h3>
            <a href="home.php"> <i class="fas fa-arrow-right"></i> home</a>
            <a href="shop.php"> <i class="fas fa-arrow-right"></i> shop</a>
<!--            <img src="image/payment.png" class="payment" alt="">-->
        </div>

        <?php
        //Check if user is logged in
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
            ?>
            <div class="box">
                <h3>extra links</h3>
                <a href="order-history.php"> <i class="fas fa-arrow-right"></i> order history </a>
                <a href="cart.php"> <i class="fas fa-arrow-right"></i> my cart </a>
                <a href="profile.php"> <i class="fas fa-arrow-right"></i> my account </a>
                <a href="productReview.php"> <i class="fas fa-arrow-right"></i> product review </a>
            </div>
            <?php
        } else {
            ?>
            <div class="box">
                <h3>extra links</h3>
                <a href="login.php"> <i class="fas fa-arrow-right"></i> order history </a>
                <a href="login.php"> <i class="fas fa-arrow-right"></i> my favorite </a>
                <a href="login.php"> <i class="fas fa-arrow-right"></i> my account </a>
                <a href="productReview.php"> <i class="fas fa-arrow-right"></i> product review </a>
            </div>
            <?php
        }
        ?>

        <div class="box">
            <h3>Social Media</h3>
            <a href="#"> <i class="fab fa-facebook-f"></i> facebook </a>
            <a href="#"> <i class="fab fa-twitter"></i> twitter </a>
            <a href="#"> <i class="fab fa-youtube"></i> youtube </a>
            <a href="#"> <i class="fab fa-instagram"></i> instagram </a>
        </div>
    </div>
</section>

<!-- footer section ends -->