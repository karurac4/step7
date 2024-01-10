<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function purchase(Request $request)
    {
        
        DB::beginTransaction();

        try {
            $productId = $request->input('product_id');
            
            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception('商品が見つかりませんでした');
            }

            if ($product->stock <= 0) {
                throw new \Exception('在庫切れです');
            }

            Sale::create([
                'product_id' => $productId,
            ]);

            $product->decrement('stock');

            DB::commit();

            return response()->json(['message' => '購入が成功しました'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => '購入が失敗しました。' . $e->getMessage()], 500);
        }
    }
}