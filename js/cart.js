//# - ID
//. = class

$(document).ready(function () {
//
//    $(document).on("click", function (e) {
//        console.log(e);
//    });

    $(document).on("click", '.add-to-cart', function () {
        if (loggedIn) {
            var answer = confirm('u sure u want to add to cart?');

            if (answer) {
                $.ajax({
                    type: 'POST',
                    url: 'cart-update.php',
                    data: {prod_id: $(this).parent().parent().attr('id'), quantity: 1},
                    success: function () {
                        $.ajax({
                        type: 'POST',
                        url: 'cart-list.php',
                        success: function (data) {
                            $(".shopping-cart").html(data);
                        }
                    });
                    }
                });
            }
        } else {
            alert('login first eh?');
        }
    });
    
    $(document).on("click", '.remove-from-cart', function () {
        console.log($($(this).parent()).attr('id').slice(7));
        if (loggedIn) {
            var answer = confirm('u sure u want to delete dis?');

            if (answer) {
                $.ajax({
                    type: 'POST',
                    url: 'cart-update.php',
                    data: {remove: $($(this).parent()).attr('id').slice(7)},
                    success: function () {
                        $.ajax({
                        type: 'POST',
                        url: 'cart-list.php',
                        success: function (data) {
                            $(".shopping-cart").html(data);
                        }
                    });
                    }
                });
            }
        } else {
            alert('login first eh?');
        }
    });
});