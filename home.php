<html lang="en">
    <?php
    session_start();
    include "head.php";
    ?>
    <body>
        <!-- header section starts -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->

        <section class="home">
            <div class="slides-container">
                <div class="slide active">
                    <div class="content">
                        <span>fresh and organic</span>    
                        <h3>Cold Storage</h3>
                        <a href="shop.php" class="btn">shop now</a>
                    </div>
                    <div class="image">
                        <img src="image/home-img-1.png" alt="">
                    </div>
                </div>

                <div class="slide">
                    <div class="content">
                        <span>fresh and finest</span>
                        <h3>NTUC</h3>
                        <a href="shop.php" class="btn">shop now</a>
                    </div>
                    <div class="image">
                        <img src="image/home-img-2.png" alt="">
                    </div>
                </div>

                <div class="slide">
                    <div class="content">
                        <span>the hypermarket</span>
                        <h3>Giant</h3>
                        <a href="shop.php" class="btn">shop now</a>
                    </div>
                    <div class="image">
                        <img src="image/home-img-3.png" alt="">
                    </div>
                </div>

                <div class="slide">
                    <div class="content">
                        <span>The saavy mart</span>
                        <h3>Sheng Shiong</h3>
                        <a href="shop.php" class="btn">shop now</a>
                    </div>
                    <div class="image">
                        <img src="image/home-img-4.png" alt="">
                    </div>
                </div>

            </div>

            <div id="next-slide" class="fas fa-angle-right" onclick="next()"></div>
            <div id="prev-slide" class="fas fa-angle-left" onclick="prev()"></div>

        </section>

        <?php include "footer.php"; ?>

    </body>
</html>
