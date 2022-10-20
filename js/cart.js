//# - ID
//. = class

function load_cart(operation) {
    switch (operation) {
        case "toolbar":
            $.ajax({
                type: 'POST',
                url: 'cart-list.php',
                data: {operation: operation},
                success: function (data) {
                    $(".shopping-cart").html(data);
                }
            });
            break;
        case "cart-page":
            $.ajax({
                type: 'POST',
                url: 'cart-list.php',
                data: {operation: operation},
                success: function (data) {
                    $("#cart-contents").html(data);
                }
            });
            break;
        default:
            break;
    }
}

function update_cart(operation, parameters) {
    switch (operation) {
        case "add-new":
            $.ajax({
                type: 'POST',
                url: 'cart-update.php',
                data: parameters,
                success: function () {
                    load_cart("toolbar");
                }
            });
            break;
        case "remove-item":
            $.ajax({
                type: 'POST',
                url: 'cart-update.php',
                data: parameters,
                success: function () {
                    load_cart("toolbar");
                }
            });
            break;
        default:
            break;
    }
}

$(document).ready(function () {
//
//    $(document).on("click", function (e) {
//        console.log(e);
//    });

    // Always load the toolbar cart
    load_cart("toolbar");

    // Function only runs on MyShoppingCart.php
    if ((window.location.href).includes("MyShoppingCart.php")) {
        load_cart("cart-page");
    }

    $(document).on("click", '.add-to-cart', function (e) {
        if (loggedIn) {
            e.preventDefault();
            var answer = confirm('Add item to Cart? Click Confirm to continue.');

            if (answer) {
                update_cart("add-new", {prod_id: $(this).parent().parent().attr('id'), quantity: 1});
            }
        } else {
            alert('login first eh?');
        }
    });

    $(document).on("click", '.remove-from-cart', function (e) {
        if (loggedIn) {
            e.preventDefault();
            var answer = confirm('Delete this item? Click confirm to continue.');

            if (answer) {
                update_cart("remove-item", {remove: $($(this).parent()).attr('id').slice(7)});
            }
        } else {
            alert('login first eh?');
        }
    });
    
    $(document).on('keypress',function(e) {
    if(e.which == 13) {
        alert('You pressed enter!');
    }
});
});

