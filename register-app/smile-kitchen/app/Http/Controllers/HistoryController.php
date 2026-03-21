<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Register_reconciliation;
use App\Models\Sales_history;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class HistoryController extends Controller
{
    //
    public function show() // 販売履歴画面表示
    {
        return view('/history');
    }
    public function enter(Request $request)
    {
        try {
            // JSONから日付を取得してフォーマット
            $currentDate = json_decode($request->getContent());
            $currentDate = Carbon::parse($currentDate)->format('Y-m-d');//形式を整える

            // 親テーブルから該当日のデータを取得
            $record = Register_reconciliation::whereDate('created_at', '=', $currentDate)->first();

            if (!$record) { //存在しなかった場合
                return response()->json([
                    "success" => false,
                    "message" => "選択された日付のデータが見つかりません。",
                ], 404);
            }

            $id = $record->id;//親idを取得
            session(['register_reconciliation_id' => $id]);//セッションに保存

            // 子テーブル（売上履歴）を取得
            $sales = Sales_history::where('register_reconciliation_id', '=', $id)->get();
            session(['sales' => $sales]);//セッションに保存（削除の際に使用する）


            if ($sales) { //履歴があった場合
                return response()->json([
                    "success" => true,
                    "history" => $sales,
                ]);
            } else { //履歴がまだなかった場合
                return response()-> json([
                    "success" => false,
                    "message" => "販売履歴はまだ存在しません",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => "サーバーエラー",
                "details" => $e->getMessage(), 
            ], 500);
        }
    }
    //削除機能
    public function remove(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['id'], $data['day'])) {
                return response()->json(['success' => false, 'message' => 'パラメータが不正です。'], 400);
            }

            $slipId = (string)$data['id'];
            $currentDate = Carbon::parse($data['day'])->format('Y-m-d'); 

            $registerReconciliationId = session('register_reconciliation_id');
            if (!$registerReconciliationId) {
                return response()->json([
                    'success' => false,
                    'message' => 'セッションが無効です。ページをリロードしてください。',
                ], 419);
            }

            // 該当日の親レコード取得
            $record = Register_reconciliation::whereDate('created_at', $currentDate)->first();
            if (!$record) {
                return response()->json(['success' => false, 'message' => '該当日のレコードが見つかりません。'], 404);
            }

            $close = (int)$record->actual_cash;

            if ($close === 0) {
                //削除する伝票の合計金額を取得
                $deleteCash = Sales_history::where('slip_number', $slipId)
                    ->where('register_reconciliation_id', $registerReconciliationId)
                    ->sum('total');
                    //決済方法を取得
                $deleteType = Sales_history::where('slip_number', $slipId)
                    ->where('register_reconciliation_id', $registerReconciliationId)
                    ->value('pay_type');
                // 伝票削除
                Sales_history::where('slip_number', $slipId)
                    ->where('register_reconciliation_id', $registerReconciliationId)
                    ->delete();
                //db更新
                $registerSale = $record->sale;//総売上取得
                $registerSale = $registerSale - $deleteCash;//総売上更新
                if ($deleteType ==='cash') {
                    $registerExpected_cash = $record->expected_cash;//理論現金残高取得
                    $registerExpected_cash = $registerExpected_cash - $deleteCash;//理論現金残高更新
                    $record -> expected_cash = $registerExpected_cash;
                } else if ($deleteType ==='credit') {
                    $registerExpected_credit = $record->expected_credit;//理論クレジット残高取得
                    $registerExpected_credit = $registerExpected_credit - $deleteCash;//理論クレジット残高更新
                    $record -> expected_credit = $registerExpected_credit;
                }
                $record -> sale = $registerSale;
                $record->save();

                // セッション sales を更新
                $sales = session('sales', collect());
                if (!($sales instanceof \Illuminate\Support\Collection)) $sales = collect($sales);

                $filteredSales = $sales->reject(fn($sale) => (string)data_get($sale, 'slip_number') === $slipId)
                                        ->values();

                session(['sales' => $filteredSales]);

                return response()->json(['success' => true, 'newSales' => $filteredSales, 'deleteType' => $deleteType], 200);
            } else {
                return response()->json(['success' => false, 'message'], 200);
            }

            return response()->json(['success' => false, 'message' => '削除できません'], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => 'サーバーエラー',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    
}
