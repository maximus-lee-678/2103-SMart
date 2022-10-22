<header class="header">

    <a href="home.php" class="logo"> <i class="fas fa-shopping-basket"></i> S-Mart </a>

    <nav class="navbar">
        <a href="home.php">home</a>
        <a href="shop.php">shop</a>
    </nav>

    <div class="icons">
        <div id="menu-btn" class="fas fa-bars"></div>
        <div id="search-btn" class="fas fa-search"></div>
        <div id="cart-btn" class="fas fa-shopping-cart"></div>
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
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
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
            
            <div class="inputBox">
                <a href="orderHistory.php" class="btn">Purchase History</a>
            </div>
            
            <div class="inputBox">
                <a href="process_logout.php" class="btn">Logout</a>
            </div>

            <?php
        } else {
            ?>
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
