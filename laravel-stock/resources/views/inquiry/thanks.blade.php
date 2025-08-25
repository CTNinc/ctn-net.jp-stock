@extends('layouts.app')

@section('content')
@section('header_text', 'お問合せありがとうございました。')

<link rel="stylesheet" href="{{('assets/css/inquiry.css') }}">

{{ Breadcrumbs::render('inquiry.create') }}

<div class="inquiry-container">
    <div class="step-progress">
        <div class="step confirm_first">お問い合わせ種別</div>
        <div class="step ">内容の確認</div>
        <div class="step current">完了</div>
    </div>
</div>

<div class="inquiry-container main">
    <div class="flex column" style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 400px;">
        @if (session('warning'))
        <div class="warning-message" style="background: #fff3cd; color: #856404; padding: 15px; margin-bottom: 20px; border: 1px solid #ffeaa7; border-radius: 4px;">
            {{ session('warning') }}
        </div>
        @endif
        
        <!-- 車両情報表示 -->
        <!-- @if(session('inquiry_vehicle') || request()->old('vehicle_manufacturer'))
        <div class="car-info">
            <h3>お問い合わせをした車両情報</h3>
            <div class="car-detail">
                <div class="car-image"></div>
                <div class="car-specs">
                    @php
                        // セッションまたはフォームデータから車両情報を取得
                        $vehicle = session('inquiry_vehicle', []);
                        $manufacturer = $vehicle['manufacturer'] ?? request()->old('vehicle_manufacturer', '---');
                        $model = $vehicle['model'] ?? request()->old('vehicle_model', '---');
                        $grade = $vehicle['grade'] ?? request()->old('vehicle_grade', '---');
                        $year = $vehicle['year'] ?? request()->old('vehicle_year', '---');
                        $mileage = $vehicle['mileage'] ?? request()->old('vehicle_mileage', '---');
                        $price = $vehicle['price'] ?? request()->old('vehicle_price', '---');
                        $bodyPrice = $vehicle['body_price'] ?? request()->old('vehicle_body_price', '---');
                        $inspection = $vehicle['inspection'] ?? request()->old('vehicle_inspection', '---');
                        $displacement = $vehicle['displacement'] ?? request()->old('vehicle_displacement', '---');
                    @endphp
                    
                    <p>{{ $manufacturer }} {{ $model }} {{ $grade }}</p>
                    <ul>
                        <li>年式：{{ $year != '---' ? $year . '年' : '---' }}</li>
                        <li>走行：{{ $mileage != '---' ? number_format($mileage) . 'km' : '---' }}</li>
                        <li>車検：{{ $inspection != '---' ? $inspection : '---' }}</li>
                        <li>排気量：{{ $displacement != '---' ? intval($displacement) . 'cc' : '---' }}</li>
                    </ul>
                    <p>支払総額（税込）：<span class="highlight">{{ $price != '---' ? number_format($price / 10000, 1) . '万円' : '---' }}</span></p>
                    <p>車両本体価格（税込）：<span class="highlight">{{ $bodyPrice != '---' ? number_format($bodyPrice / 10000, 1) . '万円' : '---' }}</span></p>
                </div>
            </div>
        </div>
        @endif -->

        <div class="thanks-message" style="text-align: center;">
            <style>
                .thanks-message p {
                    text-align: center !important;
                    padding-left: 0 !important;
                }
            </style>
            <h2>お問い合わせを受け付けました。</h2>
            <p>ご入力いただいた内容は、該当の中古車販売店に直接送信されました。<br>
               後日、1～3営業日以内に販売店よりご連絡を致しますので<br>
               それまで今しばらくお待ちくださいますようよろしくお願い申し上げます。</p>

            <p class="attention">ご入力のメールアドレス宛に、確認メールをお送りしております。<br>
               ※メールが届かない場合は、迷惑メールフォルダ等もご確認ください。</p>
               
            <p>本サービスは中古車情報の掲載を行っておりますが、<br>
               お車の詳細・価格・在庫状況などは各販売事業者が直接ご案内いたします。<br>
               回答までお時間をいただく場合がございます。予めご了承ください。</p>

            <div class="return-link">
                <a href="{{ url('/') }}">中古車検索トップへ戻る</a>
            </div>
        </div>
    </div>
</div>

@endsection