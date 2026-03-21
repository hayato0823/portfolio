<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Register_reconciliation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OpencloseController extends Controller
{
    public function show()//レジ開け閉め画面表示制御
    {
        $today = Carbon::today('Asia/Tokyo');
        $record = Register_reconciliation::whereDate('created_at','=',$today)->first();
        $sales = $record ? $record->sales : 0;
        return view('open_close', ['record' => $record, 'sales' => $sales]);     
    }
}