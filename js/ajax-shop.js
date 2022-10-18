$(document).ready(function () {
    $(window).on('scroll', function () {
        let category = "";
        let lastId = $('.product-list-tail').attr('id');
        let searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('category'))
            category = searchParams.get('category');
        if (($(window).scrollTop() + $(window).height() > $(document).height() - 50) ) {
            console.log("Loading more data!");
            load_more_data(lastId, category);
        }
    });
    function load_more_data(lastId, category) {
        $.ajax({
            type: 'GET',
            url: 'shop-backend.php',
            data: {lastId: lastId, category: category},
            success: function (response) {
                var resObj = $.parseJSON(response);
                $('#product-list').append(resObj["data"]);
                $('.product-list-tail').attr('id', resObj["lastId"]);
            }
        });
    }
});