
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}"> 


@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">商品一覧画面</h1>
            </div>
        </div>

        <form id="searchForm" method="GET" action="{{ route('products.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="keyword" id="keyword" class="form-control form-control-sm" placeholder="検索キーワード" value="{{ request('keyword') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <select name="company_filter" id="company_filter" class="form-control form-control-sm">
                            <option value="" selected>メーカー名</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_filter') == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

     <div class="col-md-2">
            <div class="form-group">
                <label for="min_price">価格（下限）</label>
                <input type="number" name="min_price" id="min_price" class="form-control form-control-sm" value="{{ request('min_price') }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="max_price">価格（上限）</label>
                <input type="number" name="max_price" id="max_price" class="form-control form-control-sm" value="{{ request('max_price') }}">
            </div>
    </div>

    <div class="col-md-2">
            <div class="form-group">
                <label for="min_stock">在庫数（下限）</label>
                <input type="number" name="min_stock" id="min_stock" class="form-control form-control-sm" value="{{ request('min_stock') }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="max_stock">在庫数（上限）</label>
                <input type="number" name="max_stock" id="max_stock" class="form-control form-control-sm" value="{{ request('max_stock') }}">
            </div>
    </div>


                <div class="col-md-4">
                    <button type="button" id="searchButton" class="btn btn-primary btn-sm">検索</button>
                </div>
            </div>
        </form>

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
                        <button type="submit" class="btn btn-danger" data-product-id="{{ $product->id }}">削除</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination">
    {{ $products->links() }}
</div>


        <div id="searchResults">
        </div>
    </div>

    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>

<script>
$(document).ready(function () {
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

    $(".tablesorter").tablesorter();
});
</script>

    

    
    
    <script>
  $(document).ready(function() {
    $('#searchButton').on('click', function() {
        $('tbody').empty();
        $('.pagination').empty();

        $.ajax({
            url: "{{ route('products.index') }}",
            type: "GET",
            data: $('#searchForm').serialize(),
            success: function(data) {
                $('table:not(#searchResults table)').hide();
                $('#searchResults').html(data);
            },
            error: function(error) {
                console.error('Ajax request failed', error);
            }
        });
    });
});


    </script>

    
@endsection
