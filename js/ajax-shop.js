$(document).ready(function () {

    let filter = "";
    load_more_data(0);

    $(window).on('scroll', function () {
        let offset = $('.product-list-offset').attr('id');
        if (($(window).scrollTop() + $(window).height() > $(document).height() - 50)) {
            console.log("Loading more data!");
            load_more_data(offset);
        }
    });
    
    $("#product_searchBtn").click(() => {
        filter = $('#product_search').val();        
        $('#product-list').empty();
        $('.product-list-tail').attr('id', 999999);
        $('.product-list-offset').attr('id', 0);
        load_more_data(0);
    });
    
    $("#product_clearBtn").click(() => {
        filter = "";        
        $('#product_search').val("");
        $('#product-list').empty();
        $('.product-list-tail').attr('id', 999999);
        $('.product-list-offset').attr('id', 0);
        load_more_data(0);
    });


    function load_more_data(offset) {

        let category = "";
        let searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('category'))
            category = searchParams.get('category');

        $.ajax({
            type: 'GET',
            url: 'shop-backend.php',
            data: {offset: offset, category: category, filter: filter},
            success: function (response) {                
                if(!response) 
                    return console.log(response);  
                
                var resObj = $.parseJSON(response);
                if ($('.product-list-tail').attr('id') > resObj["lastId"]) {
                    $('#product-list').append(resObj["data"]);
                    let count = parseInt($('.product-list-offset').attr('id'));
                    $('.product-list-offset').attr('id', count + resObj["offset"]);
                    $('.product-list-tail').attr('id', resObj["lastId"]);
                }

            }
        });
    }
});