

<!DOCTYPE html>
<?php
session_start();
?>

<script defer src="js/recipe.js"></script>

<html lang="en">
    <title>My Recipes</title>
    <?php
    include "head.php";
    ?>
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->

        <style>
            table, tr, td {
                border: 0px solid black;
            }

            * {
                box-sizing: border-box;
            }

            /* Create three equal columns that floats next to each other */
            .column {
                text-align: left;
                float: left;
                width: 48%;
                padding: 10px;
            }

            /* Clear floats after the columns */
            .row:after {
                content: "";
                display: table;
                clear: both;
            }

            /* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
            @media screen and (max-width: 600px) {
                .column {
                    width: 100%;
                }
            }
        </style>

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
                color: whitesmoke;
                font-weight: bold;
                float: right;
                margin-left: 5px;

            }

            .panel {
                padding: 0 18px;
                background-color: white;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.2s ease-out;
            }
        </style>


        <section style="margin-top: 50px; margin-bottom: 800px; font-size: 1.4rem; color: #666;">

            <image src="image/recipe/pasta.jpg" style="width: 100%; height: 400px; margin: 10px;" />

            <h2 style="width: 50%; margin-top: 40px;">One-Pan Pasta with Canned Tomatoes</h3>                

            <p style="width: 50%; margin-top: 40px;">Serving Size: 2</p>

            <div class="wrapper" type="all" style="margin-bottom: 30px;">  
                <button class="accordion" style="font-size: 1.4rem; margin-top: 20px;"><label style="float: left;">Fettuccine</label><label style="float: right;">200g</label></button>
                <div class="panel" style="font-size: 1.4rem;">
                    <img src="image/warning.png" alt="alt">
                    <!-- fill this with products -->
                </div>
                <button class="accordion" style="font-size: 1.4rem; margin-top: 20px;"><label style="float: left;">Chopped Tomatoes</label><label style="float: right;">1 Can</label></button>
                <div class="panel" style="font-size: 1.4rem;">
                    <img src="image/warning.png" alt="alt">
                    <!-- fill this with products -->
                </div>
            </div>

<!--            <table style="width: 65%; text-align: left; float: left; margin-top: 50px;">
                <tr>
                    <th><h3>Ingredients</h3></th>
                </tr>
                <tr>
                    <td style="float: left;">
                        Fettuccine
                    </td>
                    <td style="float: right;">200 g</td>
                </tr>
                <tr>
                    <td style="float: left;">
                        Chopped Tomatoes
                    </td>
                    <td style="float: right;">1 can</td>
                </tr>
                <tr>
                    <td style="float: left;">
                        Small Onion
                    </td>
                    <td style="float: right;">1</td>
                </tr>
                <tr>
                    <td style="float: left;">
                        Basil
                    </td>
                    <td style="float: right;">2 sprigs</td>
                </tr>
                <tr>
                    <td style="float: left;">
                        Extra-Virgin Olive Oil
                    </td>
                    <td style="float: right;">1 tbsp</td>
                </tr>
                <tr>
                    <td style="float: left;">
                        Salt
                    </td>
                    <td style="float: right;"></td>
                </tr>
                <tr>
                    <td style="float: left;">
                        Ground Black Pepper
                    </td>
                    <td style="float: right;"></td>
                </tr>
                <tr>
                    <td style="float: left;">
                        Dry Red Wine [to taste or thin sauce (optional)]
                    </td>
                    <td style="float: right;"></td>
                </tr>
                <tr>
                    <td style="float: left;">
                        Parmesan Cheese (freshly grated)
                    </td>
                    <td style="float: right;"></td>
                </tr>
                <tr>
                    <td style="float: left;">
                        Water (plus more if needed to thin sauce)
                    </td>
                    <td style="float: right;">1.5 cups</td>
                </tr>
                <tr>
                </tr>

            </table>-->


            <div style="text-align: left; float: left; margin-top: 60px;">
                <h3>How to Cook</h3>
                <ol style="margin-top: 15px;">
                    <b><u>Start Cooking</u></b>
                    <li>Lay pasta flat with the canned tomatoes, onion, garlic, red pepper flakes, basil, oil, salt and pepper in a large pan.</li>
                    <li>Bring to a boil over high heat.</li>
                    <li>Stir and turn the pasta frequently with tongs or a fork until pasta is al dente.</li>
                    <li>If the sauce thickens too much or starts to stick to the pan, add more water or red wine as needed to thin the sauce and prevent burning.</li>
                    <li>Season with salt and pepper to taste before garnishing with basil and parmesan cheese.</li>
                    <li>Serve straight from the pan.</li>
                </ol>
            </div>






            <!--                <div class="row">
                                <div class="column" style="text-align: center; float: center;">
                                    <table>
                                <tr>
                                  <th colspan="2">Ingredients</th>
                                </tr>
                                <tr>
                                  <td>
                                  Fettuccine
                                  </td>
                                  <td>200 g</td>
                                </tr>
                                <tr>
                                  <td>
                                  Chopped Tomatoes
                                  </td>
                                  <td>1 can</td>
                                </tr>
                                <tr>
                                  <td>
                                  Small Onion
                                  </td>
                                  <td>1</td>
                                </tr>
                                <tr>
                                  <td>
                                  Basil
                                  </td>
                                  <td>2 sprigs</td>
                                </tr>
                                <tr>
                                  <td>
                                  Extra-Virgin Olive Oil
                                  </td>
                                  <td>1 tbsp</td>
                                </tr>
                                <tr>
                                  <td>
                                  Salt
                                  </td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td>
                                  Ground Black Pepper
                                  </td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td>
                                  Dry Red Wine [to taste or thin sauce (optional)]
                                  </td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td>
                                  Parmesan Cheese (freshly grated)
                                  </td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td>
                                  Water (plus more if needed to thin sauce)
                                  </td>
                                  <td>1.5 cups</td>
                                </tr>
                                <tr>
                                </tr>
            
                              </table>
                                </div>
                                <div class="column">
                                    <table>
                                <tr>
                                  <th>How to cook</th>
                                </tr>
                                <tr>
                                    <td rowspan="10" style="padding-left: 100px; padding-right: 100px;">
                                        <ol>
                                        <b><u>Start Cooking</u></b>
                                        <li>Lay pasta flat with the canned tomatoes, onion, garlic, red pepper flakes, basil, oil, salt and pepper in a large pan.</li>
                                        <li>Bring to a boil over high heat.</li>
                                        <li>Stir and turn the pasta frequently with tongs or a fork until pasta is al dente.</li>
                                        <li>If the sauce thickens too much or starts to stick to the pan, add more water or red wine as needed to thin the sauce and prevent burning.</li>
                                        <li>Season with salt and pepper to taste before garnishing with basil and parmesan cheese.</li>
                                        <li>Serve straight from the pan.</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                                </div>
                            </div>-->


        </section>



        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->
    </body>
</html>