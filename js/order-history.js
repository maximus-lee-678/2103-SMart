//# - ID
//. = class

//    $(document).on("click", function (e) {
//        console.log(e);
//    });

// Loads order history
function load_history(type, page) {
    $.ajax({
        type: 'POST',
        url: 'order-history-process.php',
        data: {type: type, page: page},
        success: function (data) {
            $('.accordion[type="' + type + '"]').html(data);
        }
    });
}

$(document).ready(function () {

    // On start, load page 1
    load_history('pack', 1);
    load_history('deliver', 1);
    load_history('complete', 1);

    // Previous Page Button handler
    $(document).on("click", '.prev-page', function (e) {
        e.preventDefault();

        var type = $($(this).closest('.accordion')).attr('type');
        var page = parseInt($(this).parent().parent().find($(".current-page")).text()) - 1;

        load_history(type, page);
    });

    // Next Page Button handler
    $(document).on("click", '.next-page', function (e) {
        e.preventDefault();

        var type = $($(this).closest('.accordion')).attr('type');
        var page = parseInt($(this).parent().parent().find($(".current-page")).text()) + 1;

        load_history(type, page);
    });

    // Accordion open/close capability
    $(document).on("click", ".accordion-header", function () {
        if ($(this).next().is(":visible")) {
            $(this).next().hide("slow");
//            $(this).next().slideUp();
        } else {
            $(".accordion-body").hide();
            $(this).next().show("Slow");
//            $(".accordion-body").slideUp();
//            $(this).next().slideDown();
        }
    });
});


