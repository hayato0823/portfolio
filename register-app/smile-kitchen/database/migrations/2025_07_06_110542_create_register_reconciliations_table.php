<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->integer('before_cash')->default(0);      // 開店前レジ金
            $table->integer('actual_cash')->default(0);      // 実ドロア残高（レジ締終了時にあるお金）
            $table->integer('expected_cash')->default(0);    // 理論現金残高（現金での売り上げ＋開店前レジ金）理論上レジ締時にレジ内にあるはずの現金合計
            $table->integer('expected_credit')->default(0);  // 理論クレジット残高
            $table->integer('difference')->default(0);       // 差異（実ドロア残高ー理論現金残高）
            $table->integer('sale')->default(0);             // 売上（理論現金残高ー開店前レジ金＋理論クレジット残高）
            $table->timestamps();                            // 日付
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('register_reconciliations');
    }
}
