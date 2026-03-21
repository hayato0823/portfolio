<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 商品登録処理
    public function enter(Request $request)
    {
        try {
            $name = $request->input('name');
            $price = $request->input('price');

            // 新しい商品インスタンスを作成
            $product = new Product([
                'name' => $name,
                'price' => $price,
            ]);

            $exists = Product::where('name', $name)->exists(); // 重複回避機能
            if ($exists) {
                return response()->json([
                    "success" => false,
                    "error" => "同じ商品名の商品がすでに登録されています"
                ]);
            }

            // DBに保存
            $product->save();

            $itemId = Product::where('name', $name)->value('id'); // 登録した商品のid

            return response()->json([ // 商品名、価格、idをjsに返す
                'success' => true,
                'received_name' => $name,
                'received_price' => $price,
                'itemId' => $itemId,
            ]);

        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                "error" => $error->getMessage(),
            ]);
        }
    }

    // 画面表示（jsでリスト追加をしていくが、初期画面でリストを表示させるため）
    public function reflect(Request $request)
    {
        $products = Product::all();
        return view('/add_product', compact('products'));
    }

    // 商品削除
    public function delete(Request $request)
    {
        $deleteId = $request->input('id');
        $product = Product::find($deleteId);
        if ($product) {
            $product->delete();
            return response()->json([
                'success' => true,
                'message' => '削除しました'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => '該当商品が見つかりません'
            ]);
        }
    }

    // 商品編集ーーーここでjsから更新内容を受け取ってdb更新、jsに成功メッセージを返す（画面更新のために）
    public function edit(Request $request)
    {
        $editId = $request->input('id');

        $product = Product::find($editId); // 編集する行のid
        $product->name = $request->input('name');
        $product->price = $request->input('price');

        $exists = Product::where('name', $product->name)->exists(); // 重複回避機能
        if ($exists) {
            return response()->json([
                "success" => false,
                "error" => "同じ商品名の商品がすでに登録されています"
            ]);
        }

        $product->save(); // db保存
        return response()->json([
            'success' => true,
            'received_name' => $product->name,
            'received_price' => $product->price,
            'message' => '更新しました'
        ]);
    }
}
