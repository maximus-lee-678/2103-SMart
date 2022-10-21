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
        case "checkout-page":
            $.ajax({
                type: 'POST',
                url: 'cart-list.php',
                data: {operation: operation},
                success: function (data) {
                    $("#checkout-contents").html(data);
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
    
    // Function only runs on ordersummary.php
    if ((window.location.href).includes("ordersummary.php")) {
        load_cart("checkout-page");
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

        if (this.classList.contains("cart-major"))
            update_cart({operation: "decrement-item", prod_id: $($(this).closest('tr')).attr('id').slice(7)});
        else if (this.classList.contains("cart-minor"))
            update_cart({operation: "decrement-item", prod_id: $($(this).closest('.box')).attr('id').slice(7)});
    });

    $(document).on("click", '.plus-button', function (e) {
        e.preventDefault();

        if (this.classList.contains("cart-major"))
            update_cart({operation: "increment-item", prod_id: $($(this).closest('tr')).attr('id').slice(7)});
        else if (this.classList.contains("cart-minor"))
            update_cart({operation: "increment-item", prod_id: $($(this).closest('.box')).attr('id').slice(7)});
    });

    $(document).on("click", '.empty-cart', function (e) {
        e.preventDefault();

        var answer = confirm('Are you sure you want to empty your cart?');

        if (answer) {
            update_cart({operation: "empty-cart"});
        }
    });

    $(document).on("click", '.edit-quantity', function (e) {
        e.preventDefault();

        var closestInput = $($(this).closest('td')).find('.numbertextbox');

        // Toggles image and allows editing
        if ($(this).attr('src') === 'image/edit.png') {
            $(this).attr('src', 'image/check.png');
            closestInput.removeAttr("disabled");
            // Restores image, disables editing, updates quantity if changed
        } else {
            $(this).attr('src', 'image/edit.png');
            closestInput.attr("disabled", true);

            var newQuantity = closestInput.val();

            // no change
            if (newQuantity === closestInput.val(closestInput.attr("value")))
                return;
            // null value, reset to original
            if (newQuantity === "")
                closestInput.val(closestInput.attr("value"));
            // exceed 100 (how???)
            if (newQuantity > 100)
                newQuantity = 100;
            // negative (how???)
            if (newQuantity < 0)
                newQuantity = 0;

            if (newQuantity > 0) {    // update quantity
                update_cart({operation: "modify-item-count", prod_id: $($(this).closest('tr')).attr('id').slice(7), quantity: newQuantity});
                closestInput.attr("val", newQuantity);
            } else {    //remove item from cart
                var answer = confirm('Delete this item? Click confirm to continue.');

                if (answer) {
                    update_cart({operation: "remove-item", prod_id: $($(this).closest('tr')).attr('id').slice(7)});
                } else {
                    closestInput.val(closestInput.attr("value"));
                }
            }
        }
    });

});
