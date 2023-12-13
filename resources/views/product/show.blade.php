

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}"> 

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
        <h1>{{ $product->product_name }} の詳細</h1>
        </div>
    </div>

    <div class="content-wrapper">
    <p>ID: {{ $product->id }}</p>
    <p>
        @if($product->img_path)
        <img src="{{ asset('images/' . $product->img_path) }}" alt="{{ $product->product_name }}" style="max-width: 50px; max-height: 50px;">
        @else
        商品画像がありません
        @endif
    </p>
    <p>価格: {{ $product->price }}</p>
    <p>在庫: {{ $product->stock }}</p>
    <p>会社名: {{ $product->companies->company_name }}</p>

    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">編集</a>
    <a href="{{ route('products.index') }}" class="btn btn-primary">戻る</a>
    </div>
</div>