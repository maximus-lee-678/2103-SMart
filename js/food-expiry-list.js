//# - ID
//. = class

//    $(document).on("click", function (e) {
//        console.log(e);
//    });

// Loads order details
function load_history(page) {
    $.ajax({
        type: 'POST',
        url: 'food-expiry-list-process.php',
        data: {page: page},
        success: function (data) {
            $('.wrapper').html(data);
        }
    });
}

$(document).ready(function () {
    load_history(1);

    // Accordion open/close capability
    $(document).on("click", ".accordion", function () {
        var scrollHeight = $('.panel').prop('scrollHeight') + "px";

        $(this).toggleClass('active');
        $(this).next().css("max-height", (_, attr) => attr === scrollHeight ? "0px" : scrollHeight);
    });

    // Previous Page Button handler
    $(document).on("click", '.prev-page', function (e) {
        e.preventDefault();

        var page = parseInt($(this).parent().parent().find($(".current-page")).text()) - 1;
        
        load_history(page);
    });

    // Next Page Button handler
    $(document).on("click", '.next-page', function (e) {
        e.preventDefault();

        var page = parseInt($(this).parent().parent().find($(".current-page")).text()) + 1;
        
        load_history(page);
    });

});