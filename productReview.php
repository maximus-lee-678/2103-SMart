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
            <p> <a href="home.php">home >></a> product review </p>
        </div>

        <section class="products">
            <h1 class="title"> product <span>review</span> </h1>

            <div class="ratingReview">
                <div class="master-msg">
                    <!--product details ?-->
                    <div class="prodDetails"> 
                        <h1>product 1</h1>
                    </div>
                    <h2>rating:</h2>
                </div>
                <div class="rating-component">
                    <div class="status-msg">
                        <label>
                            <input  class="rating_msg" type="hidden" name="rating_msg" value=""/>
                        </label>
                    </div>
                    <div class="stars-box">
                        <i class="star fa fa-star" title="1 star"  data-value="1"></i>
                        <i class="star fa fa-star" title="2 stars" data-value="2"></i>
                        <i class="star fa fa-star" title="3 stars" data-value="3"></i>
                        <i class="star fa fa-star" title="4 stars" data-value="4"></i>
                        <i class="star fa fa-star" title="5 stars" data-value="5"></i>
                    </div>
                    <div class="starrate">
                        <label>
                            <input  class="ratevalue" type="hidden" name="rate_value" value=""/>
                        </label>
                    </div>
                </div>
                <div class="master-msg">
                    <h2>write your review:</h2>
                </div>
                <div class="tags-box">
                    <input style="height:100px; width:100%;" type="text" class="tag form-control" name="comment" id="inlineFormInputName" placeholder="please enter your review">
                </div>

                <div class="button-box">
                    <input type="submit" class=" done btn btn-warning" disabled="disabled" value="Add review" />
                </div>

                <div class="submited-box">
                    <div class="loader"></div>
                    <div class="success-message">
                        <h1> Thank you! <h1>
                    </div>
                </div>
            </div>
        </section>
        <?php include "footer.php"; ?>
        <script>
            $(".rating-component .star").on("mouseover", function () {
                var onStar = parseInt($(this).data("value"), 10);
                $(this).parent().children("i.star").each(function (e) {
                    if (e < onStar) {
                        $(this).addClass("hover");
                    } else {
                        $(this).removeClass("hover");
                    }
                });
            }).on("mouseout", function () {
                $(this).parent().children("i.star").each(function (e) {
                    $(this).removeClass("hover");
                });
            });

            $(".rating-component .stars-box .star").on("click", function () {
                var onStar = parseInt($(this).data("value"), 10);
                var stars = $(this).parent().children("i.star");
                
                var msg = "";
                if (onStar > 1) {
                    msg = onStar;
                } else {
                    msg = onStar;
                }
                $('.rating-component .starrate .ratevalue').val(msg);
                $(".button-box .done").show();

                if (onStar === 5) {
                    $(".button-box .done").removeAttr("disabled");
                } else {
                    $(".button-box .done").attr("disabled", "true");
                }

                for (i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass("selected");
                }

                for (i = 0; i < onStar; i++) {
                    $(stars[i]).addClass("selected");
                }

                $(".status-msg .rating_msg").val(ratingMessage);
                $(".status-msg").html(ratingMessage);
                $("[data-tag-set]").hide();
                $("[data-tag-set=" + onStar + "]").show();
            });

            $(".tags-box").on("click", function () {
                $(".button-box .done").removeAttr("disabled");
            });

            $(".done").on("click", function () {
                $(".rating-component").hide();
                $(".master-msg").hide();
                $(".tags-box").hide();
                $(".button-box").hide();
                $(".submited-box").show();
                $(".submited-box .loader").show();

                setTimeout(function () {
                    $(".submited-box .loader").hide();
                    $(".submited-box .success-message").show();
                }, 1500);
            });
        </script>
    </body>
</html>