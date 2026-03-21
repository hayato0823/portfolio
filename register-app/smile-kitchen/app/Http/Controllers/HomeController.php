<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Register_reconciliation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function show()//レジ開け閉めボタン表示制御
    {

        $today = Carbon::today('Asia/Tokyo');
        $record = Register_reconciliation::whereDate('created_at','=',$today)->first();
        $actual_cash = $record?->actual_cash ?? 0;  
        $sales = $record?->sale ?? 0;

        return view('home', [
            'actual_cash' => $actual_cash,
            'sales' => $sales,
            'recordExists' => !is_null($record)  // true=レコードあり、false=レコードなし
        ]);

    }
}