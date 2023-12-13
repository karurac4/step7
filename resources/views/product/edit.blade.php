

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}"> 


<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
        <h1>{{ $product->product_name }} の編集</h1>
        </div>
    </div>

    <form method="post" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="content-wrapper">
        <div class="form-group">
            <label for="product_name" class="font-weight-bold required-label">商品名</label>
            <input type="text" name="product_name" value="{{ $product->product_name }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="company_name" class="font-weight-bold required-label">メーカー名</label>
            <select name="company_id" class="form-control">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="price" class="font-weight-bold required-label">価格</label>
            <input type="text" name="price" value="{{ $product->price }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="stock" class="font-weight-bold required-label">在庫数</label>
            <input type="text" name="stock" value="{{ $product->stock }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="comment" class="font-weight-bold">コメント</label>
            <textarea name="comment" class="form-control">{{ $product->comment }}</textarea>
        </div>

        <div class="form-group">
            <label for="img_path" class="font-weight-bold">商品画像</label>
            <input id="img_path" type="file" name="img_path" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-warning">更新</button>
        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">戻る</a>
    </form>
</div>
</div>