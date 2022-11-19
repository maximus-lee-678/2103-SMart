<!DOCTYPE html>
<?php
session_start();
?>

<html lang="en">
    <title>My Recipes</title>
        <?php
        include "head.php";
        ?>
        <body>
            <!-- header section starts  -->
            <?php include "nav.php"; ?>
            <!-- header section ends -->
            
            <div class="heading">
                <h1>My Recipes</h1>
                <p> <a href="home.php">Home >></a> My Recipes </p>
            </div>

            <section class="myShoppingCart cartcontainer">
                <h1 class="carttitle">
                    <span>My Recipes</span> 
                </h1>              
                
                
                <div class="box-container" style="margin-top: 30px;">
                    
                    <div class="box" style="font-size: 1.4rem; color: #666;">
                        <table>
                            <tr>
                                <th><image src="image/category/meat-seafood.png" style="width: 170px; height: 170px;" /></th>
                            </tr>
                            <tr>
                                <td style="border: 0px solid black;">
                                    <a href="MyIndividualRecipe.php" style="font-size: 1.4rem; color: #666;">
                                        <h3>Recipe Name 1</h3>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="box" style="font-size: 1.4rem; color: #666;">
                        <table>
                            <tr>
                                <th><image src="image/category/fruit-vegetable.png" style="width: 170px; height: 170px;" /></th>
                            </tr>
                            <tr>
                                <td style="border: 0px solid black;">
                                    <a href="MyIndividualRecipe.php" style="font-size: 1.4rem; color: #666;">
                                        <h3>Recipe Name 1</h3>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="box" style="font-size: 1.4rem; color: #666;">
                        <table>
                            <tr>
                                <th><image src="image/category/rice-noodles-pasta.png" style="width: 170px; height: 170px;" /></th>
                            </tr>
                            <tr>
                                <td style="border: 0px solid black;">
                                    <a href="MyIndividualRecipe.php" style="font-size: 1.4rem; color: #666;">
                                        <h3>Recipe Name 1</h3>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
                
                
                <div class="box-container" style="margin-top: 30px;">
                    
                    <div class="box" style="font-size: 1.4rem; color: #666;">
                        <table>
                            <tr>
                                <th><image src="image/category/meat-seafood.png" style="width: 170px; height: 170px;" /></th>
                            </tr>
                            <tr>
                                <td style="border: 0px solid black;">
                                    <a href="MyIndividualRecipe.php" style="font-size: 1.4rem; color: #666;">
                                        <h3>Recipe Name 1</h3>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="box" style="font-size: 1.4rem; color: #666;">
                        <table>
                            <tr>
                                <th><image src="image/category/fruit-vegetable.png" style="width: 170px; height: 170px;" /></th>
                            </tr>
                            <tr>
                                <td style="border: 0px solid black;">
                                    <a href="MyIndividualRecipe.php" style="font-size: 1.4rem; color: #666;">
                                        <h3>Recipe Name 1</h3>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="box" style="font-size: 1.4rem; color: #666;">
                        <table>
                            <tr>
                                <th><image src="image/category/rice-noodles-pasta.png" style="width: 170px; height: 170px;" /></th>
                            </tr>
                            <tr>
                                <td style="border: 0px solid black;">
                                    <a href="MyIndividualRecipe.php" style="font-size: 1.4rem; color: #666;">
                                        <h3>Recipe Name 1</h3>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
                
                <div class="box-container" style="margin-top: 30px;">
                    
                    <div class="box" style="font-size: 1.4rem; color: #666;">
                        <table>
                            <tr>
                                <th><image src="image/category/meat-seafood.png" style="width: 170px; height: 170px;" /></th>
                            </tr>
                            <tr>
                                <td style="border: 0px solid black;">
                                    <a href="MyIndividualRecipe.php" style="font-size: 1.4rem; color: #666;">
                                        <h3>Recipe Name 1</h3>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="box" style="font-size: 1.4rem; color: #666;">
                        <table>
                            <tr>
                                <th><image src="image/category/fruit-vegetable.png" style="width: 170px; height: 170px;" /></th>
                            </tr>
                            <tr>
                                <td style="border: 0px solid black;">
                                    <a href="MyIndividualRecipe.php" style="font-size: 1.4rem; color: #666;">
                                        <h3>Recipe Name 1</h3>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="box" style="font-size: 1.4rem; color: #666;">
                        <table>
                            <tr>
                                <th><image src="image/category/rice-noodles-pasta.png" style="width: 170px; height: 170px;" /></th>
                            </tr>
                            <tr>
                                <td style="border: 0px solid black;">
                                    <a href="MyIndividualRecipe.php" style="font-size: 1.4rem; color: #666;">
                                        <h3>Recipe Name 1</h3>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
                                
            </section>

            <!-- footer section starts  -->
            <?php include "footer.php"; ?>
            <!-- footer section ends -->
        </body>
</html>