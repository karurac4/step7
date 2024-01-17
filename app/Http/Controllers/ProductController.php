<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException; 

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

 // 価格の検索条件
 $minPrice = $request->input('min_price');
 $maxPrice = $request->input('max_price');
 if ($minPrice !== null) {
     $query->where('price', '>=', $minPrice);
 }
 if ($maxPrice !== null) {
     $query->where('price', '<=', $maxPrice);
 }

 // 在庫数の検索条件
 $minStock = $request->input('min_stock');
 $maxStock = $request->input('max_stock');
 if ($minStock !== null) {
     $query->where('stock', '>=', $minStock);
 }
 if ($maxStock !== null) {
     $query->where('stock', '<=', $maxStock);
 }


    
    $products = $query->paginate(5);

    if ($request->ajax()) {
        return view('product.search-results', compact('products'));
    }

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
    
        try{
                DB::beginTransaction();

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
        
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

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

    DB::beginTransaction();

    try{

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

        DB::commit();
            
        return redirect()->route('products.index')->with('success', '商品が更新されました');

    } catch (\Exception $e) {
        
        DB::rollBack();
        throw $e;
    }

}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
    
            $product = Product::find($id);
    
            if (!$product) {
                return response()->json(['success' => false, 'error' => '商品が見つかりません。']);
            }
    
            $product->delete();
    
            DB::commit();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json(['success' => false, 'error' => '削除に失敗しました。']);
        }
    }
}
