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

function update_cart(parameters) {
    $.ajax({
        type: 'POST',
        url: 'cart-update.php',
        data: parameters,
        success: function () {
            render_carts();
        }
    });
}

function render_carts() {
    // Always load the toolbar cart
    load_cart("toolbar");

    // Function only runs on MyShoppingCart.php
    if ((window.location.href).includes("MyShoppingCart.php")) {
        load_cart("cart-page");
    }
}

$(document).ready(function () {

//    $(document).on("click", function (e) {
//        console.log(e);
//    });
    render_carts();

    $(document).on("click", '.add-to-cart', function (e) {
        if (loggedIn) {
            e.preventDefault();
            var answer = confirm('Add item to Cart? Click Confirm to continue.');

            if (answer) {
                update_cart({operation: "add-new", prod_id: $($(this).closest('.box')).attr('id')});
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
                if (this.classList.contains("cart-major"))
                    update_cart({operation: "remove-item", prod_id: $($(this).closest('tr')).attr('id').slice(7)});
                else if (this.classList.contains("cart-minor"))
                    update_cart({operation: "remove-item", prod_id: $($(this).closest('.box')).attr('id').slice(7)});
            }
        } else {
            alert('login first eh?');
        }
    });

    $(document).on("click", '.minus-button', function (e) {
        e.preventDefault();

        if (loggedIn) {
            if (this.classList.contains("cart-major"))
                update_cart({operation: "decrement-item", prod_id: $($(this).closest('tr')).attr('id').slice(7)});
            else if (this.classList.contains("cart-minor"))
                update_cart({operation: "decrement-item", prod_id: $($(this).closest('.box')).attr('id').slice(7)});
        } else {
            alert('login first eh?');
        }
    });

    $(document).on("click", '.plus-button', function (e) {
        e.preventDefault();

        if (loggedIn) {
            if (this.classList.contains("cart-major"))
                update_cart({operation: "increment-item", prod_id: $($(this).closest('tr')).attr('id').slice(7)});
            else if (this.classList.contains("cart-minor"))
                update_cart({operation: "increment-item", prod_id: $($(this).closest('.box')).attr('id').slice(7)});
        } else {
            alert('login first eh?');
        }
    });

    $(document).on("click", '.empty-cart', function (e) {
        e.preventDefault();

        if (loggedIn) {
            var answer = confirm('Are you sure you want to empty your cart?');

            if (answer) {
                update_cart({operation: "empty-cart"});
            }
        } else {
            alert('login first eh?');
        }
    });

//    $(document).on('keypress',function(e) {
//    if(e.which == 13) {
//        alert('You pressed enter!');
//    }
//});
});

