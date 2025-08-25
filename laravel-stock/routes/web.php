<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\TestListController;
use App\Http\Controllers\TestHomeController;
use App\Http\Controllers\CarApiController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    return 'Cache cleared';
});


// ログイン機能
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// 新規登録
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// パスワードリセットメール送信
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// 総合・メーカー別・ボディタイプ別：ランキング車両情報取得
Route::get('/', [CarController::class, 'index']);

// テスト一覧（確認用※）
Route::get('/test-list', [TestListController::class, 'index']);

// プライバシーポリシー
Route::get('/privacypolicy', function () {
    return view('privacypolicy');
})->name('privacypolicy'); 


// 利用規約
Route::get('/terms', [PageController::class, 'terms'])->name('terms');


// 車両一覧ページ
Route::get('/cars', [CarApiController::class, 'filter'])->name('cars.index');
// Route::get('/cars/bodyType', [CarApiController::class, 'filter'])->name('cars.body');
// Route::get('/cars/price', [CarApiController::class, 'filter'])->name('cars.price');

// 検索結果ページ（GETクエリ付き）

Route::get('/api/cars/filter', [CarApiController::class, 'filter'])->name('cars.filter');



// メーカー一覧
Route::get('/cars/makerlist', [CarController::class, 'makerList'])->name('cars.makerlist');


// 車両詳細ページ（車両IDをURLパラメータとして扱う）
Route::get('/cars/detail/{id}', [CarController::class, 'detail'])->name('cars.detail');

// 車両問い合わせページ（クエリパラメータ利用）
Route::get('/cars/inquiry_mm.php', [CarController::class, 'inquiry'])->name('cars.inquiry');

Route::get('/api/cars/filter', [CarApiController::class, 'filter'])->name('cars.filter');


// 全メーカー・車種一覧
Route::get('/cars/maker', [CarController::class, 'makerList'])->name('cars.maker.list');

// 特定メーカーの車種一覧
Route::get('/cars/maker/{maker}', [CarApiController::class, 'makerModels'])->name('cars.maker.models');
// Route::get('/cars/test-maker/{maker}', [TestHomeController::class, 'makerModels'])->name('cars.maker.test-models');

// 特定メーカー・車種の都道府県別一覧
Route::get('/cars/maker/area/{maker}/{model}', [CarController::class, 'makerModelArea'])->name('cars.maker.area');

// 地域別車種在庫一覧（実際の車両一覧ページ）
Route::get('/cars/{maker}/{model}/{prefecture}', [CarController::class, 'areaStockList'])->name('cars.area.stock');

//お問い合わせ
Route::get('/inquiry', [InquiryController::class, 'create'])->name('inquiry.create');
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');

Route::post('/inquiry/confirm', [InquiryController::class, 'confirm'])->name('inquiry.confirm');

Route::get('/inquiry/thanks', function () {
    return view('inquiry.thanks');
})->name('inquiry.thanks');


Route::get('/test-home', [TestHomeController::class, 'index']);

Route::get('/cars/search-results', [TestHomeController::class, 'search'])->name('search.results');

// Route::get('/cars/show-detail/{id}', [TestHomeController::class, 'show'])->name('cars.detailshow');
// Route::get('/cars/show-detail/{id}', [TestHomeController::class, 'detail'])->name('cars.detailshow');


// Route::get('/cached_models.json', function () {
//     $path = storage_path('app/cached_models.json');

//     if (!file_exists($path)) {
//         abort(404);
//     }

//     return response()->file($path, [
//         'Content-Type' => 'application/json',
//     ]);
// });