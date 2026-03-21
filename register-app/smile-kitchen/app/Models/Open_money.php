<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Open_money extends Model
{
    use HasFactory;

    protected $fillable = [
        'denomination',//金種
        'roll',//棒金数（一本＝50枚）
        'quantity',//バラの枚数
        'total',//棒金とバラの合計
        'register_reconciliation_id',//親テーブルid

    ];
}
