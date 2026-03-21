<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_history extends Model
{
    use HasFactory;

    protected $table = 'sales_historys';

    protected $fillable = [
        'slip_number',//伝票番号
        'item_name',//商品名
        'price',//価格
        'quantity',//販売個数
        'total',//合計金額
        'pay_type',//支払方法
        'register_reconciliation_id',//親テーブルid
    ];
}
