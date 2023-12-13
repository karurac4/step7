
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}"> 

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">商品一覧画面</h1>
        </div>
    </div>

    <form method="GET" action="{{ route('products.index') }}">
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

            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-sm">検索</button>
            </div>
        </div>
    </form>

    
<table class="table">
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
<div class="pagenation">
    {{ $products->links() }}
</div>