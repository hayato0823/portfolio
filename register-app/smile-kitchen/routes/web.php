<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OpenController;
use App\Http\Controllers\CloseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\RegiController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\OpencloseController;
use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//新規ユーザー登録は/registerにアクセス
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//指定ない時はホームに飛ばす
Route::get('/', [HomeController::class, 'show'])->middleware('auth');
//ホーム画面表示
Route::get('/home', [HomeController::class, 'show'])->middleware('auth');
//レジ開け締め選択画面
Route::get('/open_close', [OpencloseController::class, 'show'])->middleware('auth');
//レジ画面表示
Route::get('/regi', [RegiController::class, 'show'])->middleware('auth');
Route::post('/regi', [RegiController::class, 'enter']);

// 商品追加画面の表示
Route::get('/add_product', [ProductController::class, 'reflect'])->name('product.index')->middleware('auth');

// 商品登録処理
Route::post('/enter', [ProductController::class, 'enter'])->name('product.enter');
// 商品削除処理
Route::post('/delete', [ProductController::class, 'delete'])->name('product.delete');
// 商品編集処理
Route::post('/edit', [ProductController::class, 'edit'])->name('product.edit');

//レジ開け画面表示
Route::get('/open_register', [OpenController::class, 'show'])->middleware('auth');
//レジ金登録
Route::post('/open_register', [OpenController::class, 'enter']);

//レジ締画面表示
Route::get('/close_register', [CloseController::class, 'show'])->middleware('auth');
Route::post('/close_register', [CloseController::class, 'enter']);
//データ管理選択画面
Route::get('/history_management', function(){
    return view('history_management');
})->middleware('auth');
//販売履歴画面表示
Route::get('/history', [HistoryController::class, 'show'])->middleware('auth');
//日付に合わせた情報通信
Route::post('/send_date', [HistoryController::class, 'enter'])->name('send_date');
//履歴削除
Route::post('/remove', [HistoryController::class, 'remove'])->name('remove');
//売上管理画面表示
Route::get('/management', [ManagementController::class, 'show'])->middleware('auth');
//売上日付の取得
Route::post('/search', [ManagementController::class, 'search'])->middleware('auth');





require __DIR__.'/auth.php';