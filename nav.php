<?php
$is_LoggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"];
$is_Staff = isset($_SESSION["staff"]) && $_SESSION["staff"];

echo $is_LoggedIn;
echo $is_Staff;
?>

<header class="header">

    <a href="" class="logo"> <i class="fas fa-shopping-basket"></i> S-Mart </a>

    <nav class="navbar">
        <?php
        if (!$is_Staff) {
            ?>
            <a href="home.php">home</a>
            <a href="shop.php">shop</a>
            <a href="MyRecipe.php">My Recipe</a>
            <?php
        }
        ?>
    </nav>

    <div class="icons">
        <!--<div id="menu-btn" class="fas fa-bars"></div>-->
        <?php
        if (!$is_Staff) {
            ?>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="cart-btn" class="fas fa-shopping-cart"></div>
            <?php
        }
        ?>        
        <div id="login-btn" class="fas fa-user"></div>
    </div>

    <form action="" class="search-form">
        <input type="search" placeholder="search here..." id="search-box">
        <label for="search-box" class="fas fa-search"></label>
    </form>

    <div class="shopping-cart">
    </div>

    <form action="" class="login-form">
        <?php
        //Check if user is logged in
        if ($is_LoggedIn) {
            ?>
            <script type='text/javascript'>
                var loggedIn = true;
            </script>

            <div class="inputBox" style="font-size: 1.4rem; color: #666; margin-bottom: 15px;">
                <P>Welcome back, <?php echo $_SESSION["fname"] . " " . $_SESSION["lname"] ?>!</P>
            </div>

            <div class="inputBox">
                <a href="ViewUserProfile.php" class="btn">My Profile</a>
            </div>
            <?php
            if ($is_LoggedIn && !$is_Staff) {
                ?>
                <div class = "inputBox">
                    <a href = "FoodExpiryList.php" class = "btn">My Food List <span name="nav-expire-count" style="border-radius: 50%; background-color: red; color: white; padding: 5px; top: -10px; right: -10px; margin-left: 5px;">0</span></a>
                </div>

                <div class = "inputBox">
                    <a href = "orderHistory.php" class = "btn">Purchased History</a>
                </div>
                <?php
            }
            ?>

            <div class = "inputBox">
                <a href = "process_logout.php" class = "btn">Logout</a>
            </div>

            <?php
        } else {
            ?>
            <script type='text/javascript'>
                var loggedIn = false;
            </script>
            <div class="inputBox">
                <a href="login.php" class="btn">Login Now</a>
            </div>

            <div class="inputBox">
                <a href="register.php" class="btn">Register An Account</a>
            </div>
            <?php
        }
        ?>
    </form>

</header>
