

<!DOCTYPE html>
<?php
session_start();
include "helper-functions.php";

$db = make_mongo_connection();
$recipeId = (int) sanitize_input($_GET["id"]);

$query = array("id" => $recipeId);
$result = $db->Recipes->find($query)->toArray()[0];
$ingredients = $result["ingredients"];

$conn = make_connection();
?>



<html lang="en">
    <title>My Recipes</title>
    <?php
    include "head.php";
    ?>
    <script defer src="js/recipe.js"></script>
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

            <!--<image src="image/recipe/pasta.jpg" style="width: 100%; height: 400px; margin: 10px;" />-->
            <image src="<?= $result["image_url"] ?>" style="width: 170px; height: 170px; margin: 10px;" />


            <h2 style="width: 50%; margin-top: 40px;">One-Pan Pasta with Canned Tomatoes</h3>                

                <p style="width: 50%; ">Category: <?= $result["category"] ?></p>
                <p style="width: 50%; ">Servings: <?= $result["servings"] ?></p>
                <p style="width: 50%; ">Difficulty: <?= $result["level"] ?></p>
                <p style="width: 50%; ">Ingredients:</p>
                <div class="wrapper" type="all" style="margin-bottom: 30px;">  

                    <?php
                    foreach ($ingredients as $ig) {
                        $query = "SELECT * FROM Product WHERE MATCH(name) AGAINST (? IN NATURAL LANGUAGE MODE) LIMIT 4;";
                        $resultIg = payload_deliver($conn, $query, "s", $params = array($ig["name"]));
                        ?>
                        <button class="accordion" style="font-size: 1.4rem; margin-top: 20px;"><label style="float: left;"><?= $ig["name"] ?></label><label style="float: right;"><?= $ig["units"] ?></label></button>
                        <div class="panel" style="font-size: 1.4rem;">     
                            <section class ="products">
                                <div class="box-container" id = "product-list">
                                    <?php
                                    if ($resultIg->num_rows > 0) {
                                        while ($row = $resultIg->fetch_assoc()) {
                                            ?>
                                            <div class="box" id="<?= $row["id"] ?>">
                                                <div class="icons">
                                                    <a href="#" class="fas fa-shopping-cart add-to-cart"></a>
                                                    <a href="#" class="fas fa-heart"></a>
                                                    <a href="productPage.php?id=<?= $row["id"] ?>" class="fas fa-eye"></a>
                                                </div>
                                                <div class="image">
                                                    <img src="<?= $row["image_url"] ?>" alt="">
                                                </div>
                                                <div class="content">
                                                    <h3><?= $row["name"] ?></h3>
                                                    <div class="price">$<?= $row["price"] ?></div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </section>

                        </div>
                        <?php
                    }
                    ?>
                </div>

                <p>Cooking Time: <?= $result["cooking_time"] ?></p>
                <p>Preparation Time: <?= $result["preparation_time"] ?></p>

                <div style="text-align: left; float: left; margin-top: 60px;">
                    <h3>How to Cook</h3>
                    <ol style="margin-top: 15px;">
                        <?php
                        $steps = $result["steps"];
                        foreach ($steps as $step) {
                            echo "<b><u>" . str_replace("_"," ",$step["header"]) . "</u></b>";
                            foreach($step["steps"] as $sub){
                                echo "<li>" . $sub . "</li>";
                            }
                            echo "<br>";
                        }
                        ?>
                    </ol>
                </div>

        </section>



        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->
    </body>
</html>