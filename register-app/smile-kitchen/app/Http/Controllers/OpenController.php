<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Open_money;
use App\Models\Register_reconciliation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;


class OpenController extends Controller
{
    public function show()//レジ金登録画面は登録後開けなくする
    {
        $table = 'register_reconciliations';
        $column = 'before_cash';
        $today = Carbon::today();  

        if (!Schema::hasTable($table)) {// テーブルが存在しない場合は画面を表示

            return view('open_register');
        }
        $record = DB::table($table)//今日のレコードの有無
            ->whereDate('created_at', '=', $today)
            ->whereNotNull($column)
            ->first();

        if ($record) {//今日の日付レコードがあり、before_cash に値がある場合
            return redirect('/')->with('error','already');
        }
        return view('open_register');//なければ開く
    }

    public function enter(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->json()->all();
            $money = $data['breakdown']; // 金種別明細配列
            $total = $data['total'];     // 合計

            
            $register_reconciliation_id = DB::table('register_reconciliations')->insertGetId([//親テーブルに保存し、IDを取得
                'before_cash' => $total,
                'expected_cash' => $total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($money as &$row) {//各要素に親IDを付与
                $row['register_reconciliation_id'] = $register_reconciliation_id;
                $row['created_at'] = now();
                $row['updated_at'] = now();
            }

            DB::table('open_moneys')->insert($money);//金種別明細の一括保存

            DB::commit();
            return response()->json([
                "success" => true,
            ]);
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                "error" => $error->getMessage(),
            ]);
        }
    }
}
