
$(document).ready(function() {
    // 削除ボタンの非同期処理
    $('.btn-danger').on('click', function (e) {
        e.preventDefault();

        var productId = $(this).data('product-id');

        if (confirm('本当に削除しますか？')) {
            $.ajax({
                type: 'POST',
                url: `/products/${productId}`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function (data) {
                    if (data.success) {
                        // 削除成功時の処理（例: 行を非表示にする）
                        $(`#productRow${productId}`).hide();
                        alert('商品を削除しました');
                    } else {
                        alert('削除できませんでした');
                    }
                },
                error: function () {
                    alert('削除できませんでした');
                }
            });
        }
    });

    // tablesorterの初期化
    $(".tablesorter").tablesorter();
});

