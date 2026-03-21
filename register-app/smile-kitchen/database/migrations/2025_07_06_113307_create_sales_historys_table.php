<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_historys', function (Blueprint $table) {
            $table->id();
            $table->integer('slip_number')->default(0);// 伝票番号
            $table->string('item_name');  // 商品名
            $table->integer('price')->default(0);// 価格
            $table->integer('quantity')->default(0);// 販売個数
            $table->integer('total',)->default(0);// 合計金額
            $table->string('pay_type');// 支払方法
            $table->integer('register_reconciliation_id')->default(0); // 親テーブルID
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_historys');
    }
}
