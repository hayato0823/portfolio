<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register_reconciliation extends Model
{
    use HasFactory;

    protected $fillable = [
        'actual_cash',//実ドロア残高
        'expected_cash',//理論現金残高
        'expected_credit',//理論クレジット残高
        'before_cash',//開店前レジ金
        'difference',//差異
        'sale',//売上
        'created_at',//日付
    ];
}
