<?php

namespace App\Http\Controllers;
use App\Models\Register_reconciliation;
use App\Models\Sales_history;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class ManagementController extends Controller
{
    //
    public function show() {//初期表示
        return view('/management');
    }
    public function search(Request $request) {//日付を元にデータ検索
        try {
            $currentDate = json_decode($request->getContent());
            $currentDate = Carbon::parse($currentDate)->format('Y-m-d');//形式を整える

            $record = Register_reconciliation::whereDate('created_at', '=', $currentDate)->first();//親テーブルから選択日のレコードを取得

            if(!$record) {
                return response()->json([
                    "success" => false,
                    "message" => "選択された日付のデータが見つかりません。",
                ]);
            } else {
                return $this->calculate($record);//計算関数に渡す
            }

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage(),
            ],500);

        }
    }
    public function calculate($el) {//データを計算
        try {
            //大分類
            $id = $el->id;//親id
            $allSale = $el->sale;//総売上
            $allCash = $el->expected_cash - $el->before_cash;//現計合計
            $allCredit = $el->expected_credit;//クレジット合計
            $allTransaction = Sales_history::where('register_reconciliation_id', $id)//総取引数
            ->distinct('slip_number') // 伝票番号の重複を除く
            ->count('slip_number');   // その種類を数える
            if(!$allTransaction) {
                $allTransaction = 0; //まだ取引なかったら0にする
            }

            $allSale = number_format((int)($allSale ?? 0));
            $allCash = number_format((int)($allCash ?? 0));
            $allCredit = number_format((int)($allCredit ?? 0));
            //小分類
            $grouped = Sales_history::query()
                ->where('register_reconciliation_id', $id)
                ->selectRaw('item_name,ANY_VALUE(price) AS price, SUM(quantity) AS total_quantity, SUM(total) AS total_sales')
                ->groupBy('item_name')
                ->orderByDesc('total_sales')
                ->get();

            return response()->json([
                "allSale" => $allSale,
                "allCash" => $allCash,
                "allCredit" => $allCredit,
                "allTransaction" => $allTransaction,
                "items" => $grouped,
                "success" => true,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage(),
            ],500);

        }
    }
}
