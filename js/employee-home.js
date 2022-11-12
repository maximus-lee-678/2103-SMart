//# - ID
//. = class

//    $(document).on("click", function (e) {
//        console.log(e);
//    });

// Startup Function
function render_all() {
    if ($('#myStaff').length > 0) {
        render_table("staff", 1, "");
    }
    if ($('#myProducts').length > 0) {
        render_table("product", 1, "");
    }
    if ($('#editsupermarkettable').length > 0) {
        render_table("supermarket", 1, "");
    }
    if ($('#editCategory').length > 0) {
        render_table("category", 1, "");
    }
    if ($('#editbrand').length > 0) {
        render_table("brand", 1, "");
    }
    if ($('#viewproductstock').length > 0) {
        render_table("stock", 1, "", 0);
    }
    if ($('#viewpacking').length > 0) {
        render_table("pack", 1, "");
    }
    if ($('#deliveryinformation').length > 0) {
        render_table("delivery", 1, "");
    }
    if ($('#orderinformation').length > 0) {
        render_table("order_all", 1, "", 0);
    }
}

// Load a table
function render_table(pageType, page, search, addit_args) {
    // Fills different elements based on parameters 
    // (pageType to select element, others are arguments to populate properly)
    switch (pageType) {
        case "staff":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: pageType, page: page, search: search},
                success: function (data) {
                    $("#staff-contents").html(data);
                }
            });
            break;

        case "product":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: pageType, page: page, search: search},
                success: function (data) {
                    $("#product-contents").html(data);
                }
            });
            break;

        case "supermarket":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: pageType, page: page, search: search},
                success: function (data) {
                    $("#supermarket-contents").html(data);
                }
            });
            break;

        case "category":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: pageType, page: page, search: search},
                success: function (data) {
                    $("#category-contents").html(data);
                }
            });
            break;

        case "brand":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: pageType, page: page, search: search},
                success: function (data) {
                    $("#brand-contents").html(data);
                }
            });
            break;

        case "stock":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: pageType, page: page, search: search, addit_args: addit_args},
                success: function (data) {
                    $("#stock-contents").html(data);
                }
            });
            break;

        case "pack":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: pageType, page: page, search: search},
                success: function (data) {
                    $("#pack-contents").html(data);
                }
            });
            break;

        case "delivery":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: pageType, page: page, search: search},
                success: function (data) {
                    $("#delivery-contents").html(data);
                }
            });
            break;

        case "order_all":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: pageType, page: page, search: search, addit_args: addit_args},
                success: function (data) {
                    $("#order_all-contents").html(data);
                }
            });
            break;

        default:
            break;
    }
}

$(document).ready(function () {
    // Load all allowable tables
    render_all();

    // Focus on the first applicable section
    $(".tablinks").first().attr('id', 'defaultOpen');

    // Search Button handler
    $(document).on("click", 'input[name="search-button"]', function () {
        // Targets span element the table is to be placed in
        var category = $(this).parent().parent();
        var pageType = category.attr('id').replace("-contents", "");
        var search = category.find('.type-search').val();

        if (pageType.includes("stock")) {
            var addit_args = category.find("#dropdown_stock :selected").val();
            render_table(pageType, 1, search, addit_args);
        } else if (pageType.includes("order_all")) {
            var addit_args = category.find("#dropdown_order_all :selected").val();
            render_table(pageType, 1, search, addit_args);
        } else {
            render_table(pageType, 1, search);
        }
    });

    // Clear Button handler
    $(document).on("click", 'input[name="clear-button"]', function () {
        // Targets span element the table is to be placed in
        var category = $(this).parent().parent();
        var pageType = category.attr('id').replace("-contents", "");

        if (pageType.includes("stock")) {
            var addit_args = category.find("#dropdown_stock :selected").val();
            render_table(pageType, 1, "", addit_args);
        } else if (pageType.includes("order_all")) {
            var addit_args = category.find("#dropdown_order_all :selected").val();
            render_table(pageType, 1, "", addit_args);
        } else {
            render_table(pageType, 1, "");
        }
    });

    // Previous Page Button handler
    $(document).on("click", '.prev-page', function (e) {
        e.preventDefault();

        // Targets span element the table is to be placed in
        var category = $(this).parent().parent().parent();
        var pageType = category.attr('id').replace("-contents", "");
        var page = parseInt(category.find('.current-page').text()) - 1;
        var search = category.find('.type-search').val();

        if (pageType.includes("stock")) {
            var addit_args = category.find("#dropdown_stock :selected").val();
            render_table(pageType, page, search, addit_args);
        } else if (pageType.includes("order_all")) {
            var addit_args = category.find("#dropdown_order_all :selected").val();
            render_table(pageType, page, search, addit_args);
        } else {
            render_table(pageType, page, search);
        }
    });

    // Next Page Button handler
    $(document).on("click", '.next-page', function (e) {
        e.preventDefault();

        // Targets span element the table is to be placed in
        var category = $(this).parent().parent().parent();
        var pageType = category.attr('id').replace("-contents", "");
        var page = parseInt(category.find('.current-page').text()) + 1;
        var search = category.find('.type-search').val();

        if (pageType.includes("stock")) {
            var addit_args = category.find("#dropdown_stock :selected").val();
            render_table(pageType, page, search, addit_args);
        } else if (pageType.includes("order_all")) {
            var addit_args = category.find("#dropdown_order_all :selected").val();
            render_table(pageType, page, search, addit_args);
        } else {
            render_table(pageType, page, search);
        }
    });

    // Stock page dropdown handler
    $(document).on("change", '#dropdown_stock', function () {
        // Targets span element the table is to be placed in
        var category = $(this).parent().parent();
        var search = category.find('.type-search').val();
        var addit_args = $(this).find(":selected").val();

        render_table("stock", 1, search, addit_args);
    });

    // All Orders page dropdown handler
    $(document).on("change", '#dropdown_order_all', function () {
        // Targets span element the table is to be placed in
        var category = $(this).parent().parent();
        var search = category.find('.type-search').val();
        var addit_args = $(this).find(":selected").val();

        render_table("order_all", 1, search, addit_args);
    });
});