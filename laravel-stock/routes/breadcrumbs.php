<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;

// ホーム
Breadcrumbs::for('home', function (Trail $trail) {
    $trail->push('ホーム', url('/'));
});

// 車両一覧
Breadcrumbs::for('cars.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('中古車在庫情報', route('cars.index'));
});

// 検索結果
Breadcrumbs::for('cars.search', function (Trail $trail) {
    $trail->parent('cars.index');
    $trail->push('検索結果', route('cars.search'));
});

// 車両詳細
Breadcrumbs::for('cars.detail', function (Trail $trail, $car) {
    $trail->parent('cars.maker', $car['manufacturer_name'] ?? 'メーカー');
    $trail->push($car['car_model_name'] ?? '車種詳細', route('cars.detail', ['id' => $car['id']]));
});

// お問い合わせフォーム
Breadcrumbs::for('inquiry.create', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('お問い合わせ', route('inquiry.create'));
});

// お問い合わせ確認
Breadcrumbs::for('inquiry.confirm', function (Trail $trail) {
    $trail->parent('inquiry.create');
    $trail->push('確認');
});

// お問い合わせ完了
Breadcrumbs::for('inquiry.thanks', function (Trail $trail) {
    $trail->parent('inquiry.create');
    $trail->push('完了');
});

//利用規約
Breadcrumbs::for('terms', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('利用規約', route('terms')); 
});
//プライバシーポリシー
Breadcrumbs::for('privacypolicy', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('プライバシーポリシー', route('privacypolicy')); 
});


// メーカー一覧
Breadcrumbs::for('cars.makerlist', function (Trail $trail) {
    $trail->parent('cars.index');
    $trail->push('メーカー一覧', route('cars.makerlist'));
});

// メーカー別車種一覧（例：トヨタ）
Breadcrumbs::for('cars.maker', function (Trail $trail, $makerName) {
    $trail->parent('cars.makerlist');
    $trail->push($makerName, route('cars.maker.models', ['maker' => $makerName])); // 該当品牌の車種一覧ページへリンク
});
