
$(document).ready(function () {
    initializeTable();
    initializeDeleteEvent();

    $('#searchButton').on('click', function() {
        $('tbody').empty();
        $('.pagination').empty();

        $.ajax({
            url: productsIndexUrl,
            type: "GET",
            data: $('#searchForm').serialize(),
            success: function(data) {
                $('table:not(#searchResults table)').hide();
                $('#searchResults').html(data);
                initializeTable();
                initializeDeleteEvent();
               },
            error: function(error) {
                console.error('Ajax request failed', error);
            }
        });
    });


    function initializeTable() {
        $(".tablesorter").tablesorter();
    }

    function initializeDeleteEvent(){
    $('.btn-danger').on('click', function (e) {
        e.preventDefault();

        var productId = $(this).data('product-id');
        console.log('Product ID:', productId);

        if (confirm('本当に削除しますか？')) {
            $.ajax({
                type: 'POST',
                url: `/products/${productId}`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                    },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                success: function (data) {
                    console.log('Ajax request succeeded:', data);

                    if (data.success) {
                        $(`#productRow${productId}`).hide();
                        alert('商品を削除しました');

                        // ページの再読み込み
                        location.reload();
                    } else {
                        alert('削除できませんでした');
                    }
                },
                error: function () {
                    console.error('Ajax request failed');
                    alert('削除できませんでした');
                }
            });
        }
    });
    };

});

    

    
    


