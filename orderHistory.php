<?php
session_start();

include "helper-functions.php";
?>

<!DOCTYPE html>

<script defer src="js/order-history.js"></script>

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
            <h1>my purchases</h1>
            <p> <a href="home.php">home >></a> my purchases </p>
        </div>

        <section class="products">
            <h1 class="title"> my <span>purchases</span> </h1>

            <div class="tab2" style="float: none;">
                <button class="tablinks" onclick="openCity(event, 'pack')" id="defaultOpen">Packing</button>
                <button class="tablinks" onclick="openCity(event, 'deliver')">Delivering</button>
                <button class="tablinks" onclick="openCity(event, 'complete')">Complete</button>
            </div>

            
            <div id="pack" class="tabcontent2">
                <!--if got purchase history-->
                <div class="wrapper">  
                    <!-- Accordion Starts Here -->
                    <div class="acc-container">
                        <div class="accordion" type="pack"> 
                        </div>
                    </div>
                </div>
            </div>

            <div id="deliver" class="tabcontent2" style="float: none;">
                <!--if got purchase history-->
                <div class="wrapper">  
                    <!-- Accordion Starts Here -->
                    <div class="acc-container">
                        <div class="accordion" type="deliver">
                    </div>
                </div>
            </div>

            <div id="complete" class="tabcontent2" style="float: none;">
                <!--if got purchase history-->
                <div class="wrapper">  
                    <!-- Accordion Starts Here -->
                    <div class="acc-container">
                        <div class="accordion" type="complete">
                    </div>
                </div>
            </div>
                
        </section>
        <?php include "footer.php"; ?>

    </body>
</html>