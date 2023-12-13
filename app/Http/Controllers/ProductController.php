<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Company::all();
        $query = Product::with('companies');
    
        $companyFilter = $request->input('company_filter');
        $keyword = $request->input('keyword');
    
        if ($companyFilter) {
            $query->where('company_id', $companyFilter);
        }
    
        if ($keyword) {
            $query->where('product_name', 'like', '%' . $keyword . '%');
        }
    
        $products = $query->paginate(5);
    
        return view('product.index', compact('companies', 'products'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $companies = Company::all();
        $products = Product::with('companies')->get();
        return view ('product.create',compact('companies','products'));
        
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'product_name' => 'required|max:255',
            'company_name' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'comment' => 'max:1000',
            'img_path' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        
        $data = [
            'product_name' => $request->product_name,
            'company_id' => $request->company_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment,
        ];
    
        
        if ($request->hasFile('img_path')) {
            $image = $request->file('img_path');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['img_path'] = $imageName;
        }
    
        $result = Product::create($data);
    
        // 投稿後の処理
        if ($result) {
            session()->flash('flash_message', '登録完了');
        } else {
            session()->flash('flash_error_message', '登録できませんでした');
        }
    
        return redirect('/products');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $products
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    $product = Product::findOrFail($id);
    $companies = Company::all(); 

    return view('product.show', compact('product', 'companies'));
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $products
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
{
    
    $product = Product::findOrFail($id);
    $companies = company::all(); 

    return view('product.edit', compact('product', 'companies'));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, $id)
{
    $request->validate([
        'product_name' => 'required|max:255',
        'company_id' => 'required|integer',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'comment' => 'nullable|max:1000',
        'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $product = Product::findOrFail($id);

    $product->update([
        'product_name' => $request->input('product_name'),
        'company_id' => $request->input('company_id'),
        'price' => $request->input('price'),
        'stock' => $request->input('stock'),
        'comment' => $request->input('comment'),
    ]);

    if ($request->hasFile('img_path')) {
        // 画像がアップロードされた場合の処理
        $imagePath = $request->file('img_path')->store('products', 'public');
        $product->img_path = $imagePath;
        $product->save();
    }

    return redirect()->route('products.index')->with('success', '商品が更新されました');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $products = Product::findOrFail($id);
        $products->delete();

        return redirect()->route('products.index')->with('success', '商品が削除されました');
    }
}
