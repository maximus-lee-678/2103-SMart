//# - ID
//. = class

//    $(document).on("click", function (e) {
//        console.log(e);
//    });

// Loads number of expiring products
function load_expiring() {
    $.ajax({
        type: 'POST',
        url: 'expiry-list-process.php',
        data: {type: "expire_summary"},
        success: function (data) {
            $('.expire_summary').html(data);
        }
    });
}

// Loads order details
function load_history(type, page) {
    $.ajax({
        type: 'POST',
        url: 'expiry-list-process.php',
        data: {type: type, page: page},
        success: function (data) {
            $('.wrapper[type="' + type + '"]').html(data);
        }
    });
}

// Updates acknowledgement status
function update_acknowledgement(id) {
    $.ajax({
        type: 'POST',
        url: 'expiry-list-process.php',
        data: {type: "update", id: id},
        success: function () {
            load_all();
        }
    });
}

function load_all() {
    load_expiring();
    load_history("all", 1);
    load_history("expiring", 1);
}

$(document).ready(function () {
    load_all();

    // Accordion open/close capability
    $(document).on("click", ".accordion", function () {
        var table = $(this).next().find($("table"));
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

    // Acknowledge Button handler
    $(document).on("click", '.acknowledge', function (e) {
        e.preventDefault();

        var answer = confirm('Acknowledge this product?');

        if (!answer) {
            return;
        }

        update_acknowledgement($(this).attr("order_item_id"));
    });
});