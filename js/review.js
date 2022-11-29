// star mouseover
$(".rating-component .star").on("mouseover", function () {
    var onStar = parseInt($(this).data("value"), 10);
    $(this).parent().children("i.star").each(function (e) {
        if (e < onStar) {
            $(this).addClass("hover");
        } else {
            $(this).removeClass("hover");
        }
    });
});

// star mouseoff
$(".rating-component .star").on("mouseout", function () {
    $(this).parent().children("i.star").each(function () {
        $(this).removeClass("hover");
    });
});

// star clicked
$(".rating-component .stars-box .star").on("click", function () {
    var onStar = parseInt($(this).data("value"), 10);
    var stars = $(this).parent().children("i.star");

    // magnificent
    var rating_count = "";
    if (onStar > 1) {
        rating_count = onStar;
    } else {
        rating_count = onStar;
    }

    // Highlights stars
    for (i = 0; i < stars.length; i++) {
        $(stars[i]).removeClass("selected");
    }
    for (i = 0; i < onStar; i++) {
        $(stars[i]).addClass("selected");
    }

    // Place selected stars in parent
    $(this).parent().attr("value", rating_count);
});

// Attempt to submit review
$(".done").on("click", function () {
    var reviews = {};
    var flag = true;

    var count = 0;
    $('.ratingReview').each(function () {
        reviews[count] = {"prod_id": $(this).attr("prod_id"), "stars": "", "comment": ""};
        count++;
    });

    count = 0;
    $('.stars-box').each(function () {
        if (typeof $(this).attr("value") === 'undefined') {
            alert("No rating given for " + $(this).parent().parent().find(".prodDetails").children().html() + "!");
            flag = false;
        }

        reviews[count].stars = $(this).attr("value");
        count++;
    });

    if (!flag) {
        return;
    }

    count = 0;
    $('input[name="comment"]').each(function () {
        reviews[count].comment = $(this).val();
        count++;
    });
    
    console.log(reviews);

    $.ajax({
        type: 'POST',
        url: 'review-process.php',
        data: reviews,
        success: function () {
            // hide form stuff, show load bar
            $(".rating-component").hide();
            $(".master-msg").hide();
            $(".tags-box").hide();
            $(".button-box").hide();
            $(".submited-box").show();
            $(".submited-box .loader").show();

            // hide load bar, show thanks
            setTimeout(function () {
                $(".submited-box .loader").hide();
                $(".submited-box .success-message").show();
            }, 1000);//1s

            // redirect
            setTimeout(function () {
                window.location.replace("order-history.php");
            }, 3000);//3s
        }
    });


});