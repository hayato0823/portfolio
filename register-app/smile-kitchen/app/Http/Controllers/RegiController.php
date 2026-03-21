<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Register_reconciliation;
use App\Models\Sales_history;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class RegiController extends Controller
{
    public function show(Request $request) { //画面表示
        $table = 'register_reconciliations';
        $today = Carbon::today('Asia/Tokyo');
        $record = Register_reconciliation::whereDate('created_at','=',$today)->first();
        
        if (!$record) {//テーブルが存在しない場合（レジ金が登録されてないってこと）
            return redirect('/')->with('error','before');
        }
        $id = $record->id;//親idを取得
        $slip_number = Sales_history::where('register_reconciliation_id','=',$id)->orderByDesc('id')->value('slip_number');//伝票番号を取得

        if(!$slip_number) {//ない場合
            $slip_number = 1;
        } else {
            $slip_number = $slip_number + 1;//あった場合
        }
        $products = Product::all();
        return view('/regi', compact('products','slip_number'));
        
    }

    public function enter(Request $request) {
        // トランザクション（複数の処理を１つにまとめる）
        DB::beginTransaction();

        try {
            $today = Carbon::today('Asia/Tokyo');
            $record = Register_reconciliation::whereDate('created_at', '=', $today)->lockForUpdate()->first();

            if (!$record) {
                DB::rollBack(); //ロールバック（途中でエラーが出たらそれまでに行ったことを戻す）
                return response()->json([
                    "error" => "本日の精算レコードが見つかりません。",
                ], 404);
            }

            $id = $record->id; // 親IDを取得
            $data = $request->json()->all();//伝票データ
            
            // 追加する売上の合計額を計算
            $totalSum = 0;
            foreach ($data as $row) {
                if (isset($row[4]) && is_numeric($row[4])) {
                    $totalSum += $row[4];
                }
            }

            foreach ($data as &$row) {// 親IDを各行に追加
                $row[] = $id;
            }
            unset($row);

            $columns = ['slip_number', 'item_name', 'price', 'quantity', 'total', 'pay_type', 'register_reconciliation_id'];// DB登録用のカラム名

            $payload = array_map(function($row) use ($columns) {

                if (count($columns) !== count($row)) { // カラム数とデータ数が一致しない場合のエラーハンドリング
                    throw new \InvalidArgumentException('カラム数とデータの数が一致しません。');// エラーを投げてcatchブロックで処理させる
                }
                return array_combine($columns, $row);
            }, $data);
            
            DB::table('sales_historys')->insert($payload);//販売履歴へ保存
            //ここから親テーブルへ保存
            $type = $payload[0]['pay_type'];
            if($type == 'cash'){ 
                $record->increment('expected_cash', $totalSum);//保存
            } elseif($type == 'credit') {
                $record->increment('expected_credit', $totalSum);//保存
            } else {
                return response()->json([
                    "success" => false,
                    "type" => $type,
                ]);
            }
            $record->increment('sale', $totalSum);//保存
            DB::commit();
            
            return response()->json([
                "success" => true,
            ]);
        } catch (Exception $error) {
                DB::rollBack();// エラーが発生した場合はロールバック 
                return response()->json([
                    "error" => $error->getMessage(),
                ], 500); 
            }
    }
}
