//# - ID
//. = class

//    $(document).on("click", function (e) {
//        console.log(e);
//    });

// String extended functionality to allow mass replacement
String.prototype.replaceArray = function (find, replace) {
    var replaceString = this;

    for (var i = 0; i < find.length; i++) {
        replaceString = replaceString.replace(find[i], replace);
    }
    return replaceString;
};

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

        case "packed":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: "pack", page: page, search: search},
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

        case "delivered":
            $.ajax({
                type: 'POST',
                url: 'employee-home-process.php',
                data: {operation: "delivery", page: page, search: search},
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

// Fills the popup with content based on category
function render_popup(operation, args) {

    $.ajax({
        type: 'POST',
        url: 'employee-home-popup-process.php',
        data: {operation: operation, args: args},
        success: function (data) {
            $(".popup").html(data);
        }
    });

    $('body').attr('style', 'overflow:hidden;');
    $('.overlay').attr('style', 'display:block;');
    $('.popup').removeAttr('hidden');
}

// Removes the popup
function remove_popup() {
    $('body').attr('style', '');
    $('.overlay').attr('style', 'display:none;');
    $('.popup').attr('hidden', 'true');
    $('.popup').html('');
}

// Either adds, deletes or edits
function do_something(operation, args) {
    var pureOperation;

    var extraTerms = [
        '-add',
        '-edit',
        '-delete',
        '-staging',
        '-commit'
    ];

    $.ajax({
        type: 'POST',
        url: 'employee-home-popup-process.php',
        data: {operation: operation, args: args},
        success: function (data) {
            if (operation.includes("delete")) {
                alert(JSON.parse(data).response);
            }

            pureOperation = operation;
            pureOperation = pureOperation.replaceArray(extraTerms, "");
            console.log(pureOperation);
            
            refresh_tables(pureOperation);
        }
    });
}

// Used to refresh appropriate tables upon update
function refresh_tables(pageType) {
    switch (pageType) {
        case "staff":
            render_table("staff", 1, "");

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
            break;

        case "product":
            render_table("product", 1, "");

            if ($('#viewproductstock').length > 0) {
                render_table("stock", 1, "", 0);
            }
            if ($('#viewpacking').length > 0) {
                render_table("pack", 1, "");
            }
            if ($('#deliveryinformation').length > 0) {
                render_table("delivery", 1, "");
            }
            break;

        case "supermarket":
            render_table("supermarket", 1, "");

            if ($('#myProducts').length > 0) {
                render_table("product", 1, "");
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
            break;

        case "category":
            render_table("category", 1, "");

            if ($('#myProducts').length > 0) {
                render_table("product", 1, "");
            }
            if ($('#viewpacking').length > 0) {
                render_table("pack", 1, "");
            }
            if ($('#deliveryinformation').length > 0) {
                render_table("delivery", 1, "");
            }
            break;

        case "brand":
            render_table("brand", 1, "");

            if ($('#myProducts').length > 0) {
                render_table("product", 1, "");
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
            break;

        case "stock":
            render_table("stock", 1, "", 0);

            if ($('#myProducts').length > 0) {
                render_table("product", 1, "");
            }
            break;

        case "pack":
            render_table("pack", 1, "");

            if ($('#orderinformation').length > 0) {
                render_table("order_all", 1, "", 0);
            }
            break;

        case "packed":
            render_table("pack", 1, "");

            if ($('#orderinformation').length > 0) {
                render_table("order_all", 1, "", 0);
            }
            break;

        case "delivery":
            render_table("delivery", 1, "");

            if ($('#orderinformation').length > 0) {
                render_table("order_all", 1, "", 0);
            }
            break;

        case "delivered":
            render_table("delivery", 1, "");

            if ($('#orderinformation').length > 0) {
                render_table("order_all", 1, "", 0);
            }
            break;

        case "order_all":
            render_table("order_all", 1, "", 0);
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
        var headElement = $(this).parent().parent();
        var operation = headElement.attr('id').replace("-contents", "");
        var search = headElement.find('.type-search').val();

        if (operation.includes("stock")) {
            var addit_args = headElement.find("#dropdown_stock:selected").val();
            render_table(operation, 1, search, addit_args);
        } else if (operation.includes("order_all")) {
            var addit_args = headElement.find("#dropdown_order_all:selected").val();
            render_table(operation, 1, search, addit_args);
        } else {
            render_table(operation, 1, search);
        }
    });

    // Search Button handler
    $(document).on("keypress", 'input[name="search-field"]', function (e) {
        if (e.which !== 13) {
            return;
        }

        e.preventDefault();

        // Targets span element the table is to be placed in
        var headElement = $(this).parent().parent();
        var operation = headElement.attr('id').replace("-contents", "");
        var search = headElement.find('.type-search').val();

        if (operation.includes("stock")) {
            var addit_args = headElement.find("#dropdown_stock:selected").val();
            render_table(operation, 1, search, addit_args);
        } else if (operation.includes("order_all")) {
            var addit_args = headElement.find("#dropdown_order_all:selected").val();
            render_table(operation, 1, search, addit_args);
        } else {
            render_table(operation, 1, search);
        }
    });

    // Clear Button handler
    $(document).on("click", 'input[name="clear-button"]', function () {
        // Targets span element the table is to be placed in
        var headElement = $(this).parent().parent();
        var operation = headElement.attr('id').replace("-contents", "");

        if (operation.includes("stock")) {
            var addit_args = headElement.find("#dropdown_stock:selected").val();
            render_table(operation, 1, "", addit_args);
        } else if (operation.includes("order_all")) {
            var addit_args = headElement.find("#dropdown_order_all:selected").val();
            render_table(operation, 1, "", addit_args);
        } else {
            render_table(operation, 1, "");
        }
    });

    // Previous Page Button handler
    $(document).on("click", '.prev-page', function (e) {
        e.preventDefault();

        // Targets span element the table is to be placed in
        var headElement = $(this).parent().parent().parent();
        var operation = headElement.attr('id').replace("-contents", "");
        var page = parseInt(headElement.find('.current-page').text()) - 1;
        var search = headElement.find('.type-search').val();

        if (operation.includes("stock")) {
            var addit_args = headElement.find("#dropdown_stock:selected").val();
            render_table(operation, page, search, addit_args);
        } else if (operation.includes("order_all")) {
            var addit_args = headElement.find("#dropdown_order_all:selected").val();
            render_table(operation, page, search, addit_args);
        } else {
            render_table(operation, page, search);
        }
    });

    // Next Page Button handler
    $(document).on("click", '.next-page', function (e) {
        e.preventDefault();

        // Targets span element the table is to be placed in
        var headElement = $(this).parent().parent().parent();
        var operation = headElement.attr('id').replace("-contents", "");
        var page = parseInt(headElement.find('.current-page').text()) + 1;
        var search = headElement.find('.type-search').val();

        if (operation.includes("stock")) {
            var addit_args = headElement.find("#dropdown_stock:selected").val();
            render_table(operation, page, search, addit_args);
        } else if (operation.includes("order_all")) {
            var addit_args = headElement.find("#dropdown_order_all:selected").val();
            render_table(operation, page, search, addit_args);
        } else {
            render_table(operation, page, search);
        }
    });

    // Stock page dropdown handler
    $(document).on("change", '#dropdown_stock', function () {
        // Targets span element the table is to be placed in
        var headElement = $(this).parent().parent();
        var search = headElement.find('.type-search').val();
        var addit_args = $(this).find(":selected").val();

        render_table("stock", 1, search, addit_args);
    });

    // All Orders page dropdown handler
    $(document).on("change", '#dropdown_order_all', function () {
        // Targets span element the table is to be placed in
        var headElement = $(this).parent().parent();
        var search = headElement.find('.type-search').val();
        var addit_args = $(this).find(":selected").val();

        render_table("order_all", 1, search, addit_args);
    });

    // When edit button pressed
    $(document).on("click", '.edit', function (e) {
        e.preventDefault();

        var operation = $(this).parent().parent().attr('operation');
        var id = $(this).parent().parent().attr(operation.concat('_id'));

        render_popup(operation.concat('-edit-staging'), id);
    });

    // When delete button pressed
    $(document).on("click", '.delete', function (e) {
        e.preventDefault();

        var operation = $(this).parent().parent().attr('operation');
        var id = $(this).parent().parent().attr(operation.concat('_id'));
        var answer;

        if (!operation.includes("order_all")) {
            answer = confirm('Are you sure you want to delete '.concat(operation, ' ID ', id, '?'));

            if (!answer) {
                return;
            }

            answer = confirm('Are you very sure? This could cause catastrophic consequences!');

            if (!answer) {
                return;
            }

            do_something(operation.concat('-delete'), id);
        } else {
            render_popup(operation.concat('-delete'), id);
        }


    });

    // When add button pressed
    $(document).on("click", 'input[name="add-button"]', function (e) {
        e.preventDefault();

        var headElement = $(this).parent().parent();
        var operation = headElement.attr('id').replace("-contents", "");

        render_popup(operation.concat('-add-staging'));
    });

    // When confirm button pressed
    $(document).on("click", 'input[name="confirm-button"]', function (e) {
        e.preventDefault();

        var answer = confirm('Are you sure you want to '.concat($(this).val(), '?'));

        if (!answer) {
            return;
        }

        var operation = $(this).attr('operation');
        operation = operation.replace("staging", "commit");

        var args = [];

        args.push($(this).parent().parent().find(".popup-id").html());

        var inputs = $('.popup-input-field').map((vo_id, x) => x.value).get();
        args.push(...inputs);

        var checkboxes = $('.checkbox-field:checked');
        checkboxes.each(function () {
            args.push($(this).attr('value'));
        });
        console.log(args);
        do_something(operation, args);

        remove_popup();
    });

    // Function that closes pop up when close is clicked
    $(document).on('click', '#close-view', function (e) {
        e.preventDefault();

        remove_popup();
    });
});