//# - ID
//. = class

//    $(document).on("click", function (e) {
//        console.log(e);
//    });

// Loads order details
function load_history(type, page) {
    $.ajax({
        type: 'POST',
        url: 'food-expiry-list-process.php',
        data: {type: type, page: page},
        success: function (data) {
            $('.wrapper[type="' + type + '"]').html(data);
        }
    });
}

$(document).ready(function () {
    load_history("all", 1);
    load_history("expiring", 1);

    // Accordion open/close capability
    $(document).on("click", ".accordion", function () {
        var table = $(this).next().find($("table"));
        var scrollHeight = $('.panel').prop('padding') + "px";
        console.log(scrollHeight);
//        var tableHeight = (table.height() + 2 * $('.panel').prop('scrollHeight')) + "px";
        var tableHeight = (table.height() + 2 * parseInt(table.css("margin-top").replace("px", ""))) + "px";

        $(this).toggleClass('active');
        $(this).next().css("max-height", (_, attr) => attr === tableHeight ? "0px" : tableHeight);
    });

    // Previous Page Button handler
    $(document).on("click", '.prev-page', function (e) {
        e.preventDefault();

        var type = $($(this).closest('div')).attr('type'); 
        var page = parseInt($(this).parent().parent().find($(".current-page")).text()) - 1;
        
        load_history(type, page);
    });

    // Next Page Button handler
    $(document).on("click", '.next-page', function (e) {
        e.preventDefault();

        var type = $($(this).closest('div')).attr('type');
        var page = parseInt($(this).parent().parent().find($(".current-page")).text()) + 1;

        load_history(type, page);
    });

});