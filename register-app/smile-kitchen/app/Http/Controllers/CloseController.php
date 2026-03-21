<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Register_reconciliation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CloseController extends Controller
{
    public function show()//レジ締金登録画面表示制御
    {
        $table = 'register_reconciliations';
        $today = Carbon::today('Asia/Tokyo');
        $record = Register_reconciliation::whereDate('created_at','=',$today)->first();

        if (!$record) {//テーブルが存在しない場合（レジ金が登録されてないってこと）
            return redirect('/')->with('error','before');
        }
        $actual_cash = $record->actual_cash;
        if ($actual_cash > 0) {
            return redirect('/')->with('error','finish');
        }        
        $sale = $record->sale;
        $expected_cash = $record->expected_cash;
        $difference = $actual_cash - $expected_cash;
            return view('close_register', compact('expected_cash','difference'));//開く
    }

    public function enter(Request $request)//レジ締金登録
    {
        DB::beginTransaction();
        try {
            $data = $request->json()->all();
            $money = $data['breakdown']; // 金種別明細配列
            $total = $data['total'];     // 合計
            $difference = $data['difference'];//差異

            $today = Carbon::today('Asia/Tokyo');
            $record = Register_reconciliation::whereDate('created_at','=',$today)->first();//今日のレコード
            $register_reconciliation_id = $record->id;//親id
            //親テーブルへの保存
            $record->difference = $difference;
            $record->actual_cash = $total;
            $record->updated_at = now();
            $record->save();
            //レジ締テーブルへの保存
            foreach ($money as &$row) {//各要素に親IDを付与
                $row['register_reconciliation_id'] = $register_reconciliation_id;
                $row['created_at'] = now();
                $row['updated_at'] = now();
            }

            DB::table('close_moneys')->insert($money);//金種別明細の一括保存

            DB::commit();
            return response()->json([
                "success" => true,
                "day" => $today,//日付確認用
            ]);
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                "error" => $error->getMessage(),
            ]);
        }
    }
}
