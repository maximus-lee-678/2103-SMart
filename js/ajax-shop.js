$(document).ready(function () {

    load_more_data(0);

    $(window).on('scroll', function () {

        let lastId = $('.product-list-tail').attr('id');
        if (($(window).scrollTop() + $(window).height() > $(document).height() - 50)) {
            console.log("Loading more data!");
            load_more_data(lastId);
        }
    });


    function load_more_data(lastId) {

        let category = "";
        let searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('category'))
            category = searchParams.get('category');

        $.ajax({
            type: 'GET',
            url: 'shop-backend.php',
            data: {lastId: lastId, category: category},
            success: function (response) {
                var resObj = $.parseJSON(response);
                if ($('.product-list-tail').attr('id') < resObj["lastId"]) {
                    $('#product-list').append(resObj["data"]);
                    $('.product-list-tail').attr('id', resObj["lastId"]);
                }

            }
        });
    }
});