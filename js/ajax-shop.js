$(document).ready(function () {

    let filter = "";
    load_more_data(999999);

    $(window).on('scroll', function () {
        let lastId = $('.product-list-tail').attr('id');
        if (($(window).scrollTop() + $(window).height() > $(document).height() - 50)) {
            console.log("Loading more data!");
            load_more_data(lastId);
        }
    });
    
    $("#product_searchBtn").click(() => {
        filter = $('#product_search').val();        
        $('#product-list').empty();
        $('.product-list-tail').attr('id', 0);
        load_more_data(0);
    });
    
    $("#product_clearBtn").click(() => {
        filter = "";        
        $('#product_search').val("");
        $('#product-list').empty();
        $('.product-list-tail').attr('id', 0);
        load_more_data(0);
    });


    function load_more_data(lastId) {

        let category = "";
        let searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('category'))
            category = searchParams.get('category');

        $.ajax({
            type: 'GET',
            url: 'shop-backend.php',
            data: {lastId: lastId, category: category, filter: filter},
            success: function (response) {                
                if(!response) 
                    return console.log(response);  
                
                console.log($.parseJSON(response)); 
                var resObj = $.parseJSON(response);
                if ($('.product-list-tail').attr('id') > resObj["lastId"]) {
                    $('#product-list').append(resObj["data"]);
                    $('.product-list-tail').attr('id', resObj["lastId"]);
                }

            }
        });
    }
});