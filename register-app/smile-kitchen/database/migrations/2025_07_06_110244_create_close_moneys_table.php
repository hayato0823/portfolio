<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('close_moneys', function (Blueprint $table) {
            $table->id();
            $table->integer('denomination');//金種
            $table->integer('roll')->default(0);//棒金数
            $table->integer('quantity')->default(0);//バラの合計
            $table->integer('total')->default(0);//棒金とバラの合計
            $table->integer('register_reconciliation_id');//親テーブルid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('close_moneys');
    }
};
