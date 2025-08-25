<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 台数取得API（APIをDB連携か？）
// Route::get('/listing-count', function () {
//     // 仮実装：アクセス成功で8,000台と表示！！！
//     $count = 8000;
//     return response()->json([
//         'count' => $count  // 数値型で返す！
//     ]);
// });


// これにより、fetch('/api/listing-count') で vehicles.json の台数が常に取得される
Route::get('/listing-count', function () {
    $jsonPath = public_path('data/vehicles.json');

    if (!File::exists($jsonPath)) {
        return response()->json(['count' => 0], 404);
    }

    $vehicles = json_decode(File::get($jsonPath), true);
    $count = is_array($vehicles) ? count($vehicles) : 0;

    return response()->json([
        'count' => $count
    ]);
});