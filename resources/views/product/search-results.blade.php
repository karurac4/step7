
<table class="table tablesorter">
    <thead>
        <tr>
            <th>ID</th>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫</th>
            <th>会社名</th>
            <th><a href="{{ route('products.create') }}" class="btn btn-warning">新規登録</a></th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if($product->img_path)
                        <img src="{{ asset('images/' . $product->img_path) }}" alt="{{ $product->product_name }}" style="max-width: 50px; max-height: 50px;">
                    @else
                        商品画像がありません
                    @endif
                </td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->companies->company_name }}</td>
                <td>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">詳細</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">削除</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination">
    {{ $products->links() }}
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>

<script>
    $(document).ready(function () {
        $(".tablesorter").tablesorter();
    });

     $('.btn-delete').on('click', function (e) {
                e.preventDefault();

                var productId = $(this).data('product-id');

                if (confirm('本当に削除しますか？')) {
                    $.ajax({
                        type: 'DELETE',
                        url: `/products/${productId}`,
                        success: function (data) {
                            if (data.success) {
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
        

</script>