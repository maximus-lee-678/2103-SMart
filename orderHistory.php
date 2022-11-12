<!DOCTYPE html>
<html lang="en">
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
            <p> <a href="home.php">home >></a> order history </p>
        </div>

        <section class="history">
            <h1 class="title"> order <span>history</span> </h1>
            
            <!--if got purchase history-->
            <div class="wrapper">  
                <!-- Accordion Starts Here -->
                <div class="acc-container">
                    <div class="accordion">
                        <div class="accordion-item ">
                            <label class="accordion-header">
                                <table class="historyIDTitle">
                                    <tr style="text-align: right; background: white;">
                                        <td>Order ID: 1234</td>
                                        <td>Order Status: Processing</td>
                                        <td>Purchased Date: 12/12/12</td>
                                    </tr>
                                </table>
                                <span></span>
                            </label>
                            <div class="accordion-body">
                                <table class="historyIDTable">
                                    <tr style="text-align: right; background: white;">
                                        <td>Order ID: </td>
                                        <td>1234</td>
                                    </tr>
                                    <tr style="text-align: right; background: white;">
                                        <td>Purchase Status: </td>
                                        <td>Processing</td>
                                    </tr>
                                    <tr style="text-align: right; background: white;">
                                        <td>Purchase Date: </td>
                                        <td>12/12/12</td>
                                    </tr>
                                </table>

                                <table class="historyTable">
                                    <tr style="text-align: center; background: #6D6875; color: white;">
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Quantity</th>
                                    </tr>
                                    <tr>
                                        <td>' '</td>
                                        <td>' '</td>
                                        <td>' '</td>
                                        <td>' '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: center;">Total: </td>
                                        <td>' '</td>
                                        <td>' '</td>
                                    </tr>
                                </table>

                                <table class="historyPayTable">
                                    <tr style="text-align: center; background: white;">
                                        <td>Delivery Fee (5%): </td>
                                        <td>' '</td>
                                    </tr>
                                    <tr style="text-align: center; background: white;">
                                        <td>Service Fee: </td>
                                        <td>' '</td>
                                    </tr>
                                    <tr style="text-align: center; background: white;">
                                        <td>Final Cost: </td>
                                        <td>' '</td>
                                    </tr>
                                </table>
                                <div class="box-container">
                                    <div class="box">
                                        <div class="image">
                                            <img src="image/product-1.jpg" alt="">
                                        </div>
                                        <div class="content">
                                            <h3>organic food</h3>
                                            <div class="price">$18.99</div>
                                            <div class="stars">
                                                <a href="#">Buy Again</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box">
                                        <div class="image">
                                            <img src="image/product-1.jpg" alt="">
                                        </div>
                                        <div class="content">
                                            <h3>organic food</h3>
                                            <div class="price">$18.99</div>
                                            <div class="stars">
                                                <a href="#">Buy Again</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item ">
                            <label class="accordion-header">
                                <table class="historyIDTitle">
                                    <tr style="text-align: right; background: white;">
                                        <td>Order ID: 1234</td>
                                        <td>Order Status: Processing</td>
                                        <td>Purchase Date: 12/12/12</td>
                                    </tr>
                                </table>
                                <span></span>
                            </label>
                            <div class="accordion-body">
                                <table class="historyIDTable">
                                    <tr style="text-align: right; background: white;">
                                        <td>Order ID: </td>
                                        <td>1234</td>
                                    </tr>
                                    <tr style="text-align: right; background: white;">
                                        <td>Purchase Status: </td>
                                        <td>Processing</td>
                                    </tr>
                                    <tr style="text-align: right; background: white;">
                                        <td>Purchase Date: </td>
                                        <td>12/12/12</td>
                                    </tr>
                                </table>

                                <table class="historyTable">
                                    <tr style="text-align: center; background: #6D6875; color: white;">
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Quantity</th>
                                    </tr>
                                    <tr>
                                        <td>' '</td>
                                        <td>' '</td>
                                        <td>' '</td>
                                        <td>' '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: center;">Total: </td>
                                        <td>' '</td>
                                        <td>' '</td>
                                    </tr>
                                </table>

                                <table class="historyPayTable">
                                    <tr style="text-align: center; background: white;">
                                        <td>Delivery Fee (5%): </td>
                                        <td>' '</td>
                                    </tr>
                                    <tr style="text-align: center; background: white;">
                                        <td>Service Fee: </td>
                                        <td>' '</td>
                                    </tr>
                                    <tr style="text-align: center; background: white;">
                                        <td>Final Cost: </td>
                                        <td>' '</td>
                                    </tr>
                                </table>
                                <div class="box-container">
                                    <div class="box">
                                        <div class="image">
                                            <img src="image/product-1.jpg" alt="">
                                        </div>
                                        <div class="content">
                                            <h3>organic food</h3>
                                            <div class="price">$18.99</div>
                                            <div class="stars">
                                                <a href="#">Buy Again</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box">
                                        <div class="image">
                                            <img src="image/product-1.jpg" alt="">
                                        </div>
                                        <div class="content">
                                            <h3>organic food</h3>
                                            <div class="price">$18.99</div>
                                            <div class="stars">
                                                <a href="#">Buy Again</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>     
                    </div>
                </div>
            </div>

            <!--if no purchase history-->
            <!--                <div class = "resultContainer2">
                                <div class ="content">
                                    <h2>no purchased history!</h2>
                                    <h4>press <a href="shop.php"> ME </a>to start shopping now!</h4>
                                    <p>can uncomment the top part from orderHistory.php if there is a purchased history</p>
                                </div>
                            </div>-->

        </section>
        <?php include "footer.php"; ?>

        <script>
            $(document).ready(function () {
                accordion();
            });

            function accordion() {
                $(".accordion-header").click(function () {
                    if ($(this).next().is(":visible")) {
                        $(this).next().hide("slow");
                        //                        $(this).next().slideUp();
                    } else {
                        $(".accordion-body").hide();
                        $(this).next().show("Slow");
                        //                        $(".accordion-body").slideUp();
                        //                        $(this).next().slideDown();
                    }
                });
            }
        </script>
    </body>
</html>


