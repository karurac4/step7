
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}"> 


<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
        <h1>商品登録</h1>
        </div>
    </div>


    <form method="post" action="{{route('store')}}" enctype="multipart/form-data">
        @csrf
        <div class="content-wrapper">
        <div class="form-group">
            <label for="product_name" class="font-weight-bold required-label">商品名</label>
            <input type="text" name="product_name" class="form-control" id="product_name">
            @error('product_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="company_name" class="font-weight-bold required-label">メーカー名</label>
            <select name="company_name" id="company_name" class="form-control">
                <option value="" disabled selected></option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
            @error('company_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="price" class="font-weight-bold required-label">価格</label>
            <input type="text" name="price" class="form-control" id="price">
            @error('price')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="stock" class="font-weight-bold required-label" >在庫数</label>
            <input type="text" name="stock" class="form-control" id="stock">
            @error('stock')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="comment" class="font-weight-bold">コメント</label>
            <textarea name="comment" class="form-control" id="comment" cols="30" rows="5"></textarea>
        </div>

        <div class="form-group">
            <label for="img_path" class="font-weight-bold">商品画像</label>
            <input id="img_path" type="file" name="img_path" class="form-control-file">
        </div>

        <button class="btn btn-warning" type="submit">新規登録</button>
        <a href="{{ route('products.index') }}" class="btn btn-primary">戻る</a>
    </form>
</div>
</div>
