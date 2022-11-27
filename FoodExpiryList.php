<!DOCTYPE html>

<script defer src="js/food-expiry-list.js"></script>

<html lang="en">
    <title>My Food List</title>
    <?php
    session_start();
    include "head.php";
    ?>    
    <body>

        <style>
            /*ax accordiance*/

            .accordion {
                background-color: #6d6875;
                color: whitesmoke;
                cursor: pointer;
                padding: 18px;
                width: 100%;
                border: none;
                text-align: left;
                outline: none;
                font-size: 15px;
                transition: 0.4s;
            }

            .active, .accordion:hover {
                background-color: #b5838d;
            }

            .accordion:after {
                content: '\002B';
                color: whitesmoke;
                font-weight: bold;
                float: right;
                margin-left: 5px;

            }

            .active:after {
                content: "\2212";
            }

            .panel {
                padding: 0 18px;
                background-color: white;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.2s ease-out;
            }
        </style>


        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->
        <div class="heading">
            <h1>My Food List</h1>
        </div>

        <section class="products">
            <div>
                <p class="expire_summary" style="font-size: 1.4rem; background-color: greenyellow; border: 1px solid grey; padding: 10px;">You have 4 food item that is Expiring in 3 Days!</p>
            </div>
            <br>
            <div class="tab2" style="float: none;">
                <button class="tablinks" onclick="openCity(event, 'all')" id="defaultOpen">All</button>
                <button class="tablinks" onclick="openCity(event, 'expiring')">Expiring & Unacknowledged</button>
            </div>

            <div id="all" class="tabcontent2">
                <div class="wrapper" type="all" style="margin-bottom: 30px;">  

                </div>
            </div>

            <div id="expiring" class="tabcontent2">
                <div class="wrapper" type="expiring" style="margin-bottom: 30px;">  

                </div>
            </div>
        </section>

        <?php include "footer.php"; ?>
    </body>
</html>


