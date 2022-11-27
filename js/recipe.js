//# - ID
//. = class

//    $(document).on("click", function (e) {
//        console.log(e);
//    });

$(document).ready(function () {

    // Accordion open/close capability
    $(document).on("click", ".accordion", function () {
        var table = $(this).next().find($("table"));
//        var tableHeight = (table.height() + 2 * parseInt(table.css("margin-top").replace("px", ""))) + "px";

        $(this).toggleClass('active');
        $(this).next().css("max-height", (_, attr) => attr === "400px" ? "0px" : "400px"); // change later
    });
});