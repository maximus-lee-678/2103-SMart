<!DOCTYPE html>
<html>
    <title>Shop</title>
    <?php
    session_start();
    include "head.php";
    ?>    
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->
        <div class="heading">
            <h1>order history</h1>
            <p> <a href="home.php">home >></a> product comparison </p>
        </div>

        <section class="products">
            <h1 class="title"> product <span>comparison</span> </h1>

            <div class="compare">
                <div class="columns">
                    <ul class="price">
                        <li class="top"><img src="hi.jpg"></li>
                        <li class="grey">price</li>
                        <li>name</li>
                        <li>mart</li>
                        <li>weight</li>
                        <li>brand</li>
                        <li class="grey"><a href="#" class="button">add to cart</a></li>
                    </ul>
                </div>

                <div class="columns">
                    <ul class="price">
                        <li class="top"><img src="hi.jpg"></li>
                        <li class="grey">price</li>
                        <li>name</li>
                        <li>mart</li>
                        <li>weight</li>
                        <li>brand</li>
                        <li class="grey"><a href="#" class="button">add to cart</a></li>
                    </ul>
                </div>
                
                <div class="columns">
                    <ul class="price">
                        <li class="top"><img src="hi.jpg"></li>
                        <li class="grey">price</li>
                        <li>name</li>
                        <li>mart</li>
                        <li>weight</li>
                        <li>brand</li>
                        <li class="grey"><a href="#" class="button">add to cart</a></li>
                    </ul>
                </div>
                
                 <div class="columns">
                    <ul class="price">
                        <li class="top"><img src="hi.jpg"></li>
                        <li class="grey">price</li>
                        <li>name</li>
                        <li>mart</li>
                        <li>weight</li>
                        <li>brand</li>
                        <li class="grey"><a href="#" class="button">add to cart</a></li>
                    </ul>
                </div>
            </div>
        </section>
        <?php include "footer.php"; ?>
    </body>
</html>

