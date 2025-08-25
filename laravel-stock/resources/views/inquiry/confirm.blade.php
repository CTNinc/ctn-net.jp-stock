@extends('layouts.app')

@section('content')
@section('header_text', '問合せ内容の確認')

<link rel="stylesheet" href="{{('assets/css/inquiry.css') }}">

{{ Breadcrumbs::render('inquiry.create') }}

<div class="inquiry-container">
    <div class="step-progress">
        <div class="step confirm_first">お問い合わせ種別</div>
        <div class="step current">内容の確認</div>
        <div class="step confirm_second">完了</div>
    </div>
</div>
<div class="inquiry-container main">
    <div class="flex column">
        @if (isset($formData['vehicle_manufacturer']) && !empty(trim($formData['vehicle_manufacturer'] ?? '')))
        <div class="car-info">
            <h3>お問い合わせをする車両情報</h3>
            
            <!-- 車両画像 -->
            <div class="car-image-section" style="margin-bottom: 20px; text-align: center;">
                <img src="{{ $formData['vehicle_image'] ?? asset('assets/img/car-image/no-image.webp') }}" alt="車両画像" style="width: 100%; max-width: 400px; border-radius: 8px; margin: 0 auto; display: block;">
            </div>
            
            <!-- 車両情報 -->
            <div class="car-specs-section">
                <h4 class="car-title" style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #333;">{{ $formData['vehicle_manufacturer'] ?? '' }} {{ $formData['vehicle_model'] ?? '' }} {{ $formData['vehicle_grade'] ?? '' }}</h4>
                <div class="car-specs-grid" style="margin-bottom: 15px;">
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">年式：</span>
                        <span class="spec-value" style="color: #333;">{{ $formData['vehicle_year'] ?? '---' }}</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">走行：</span>
                        <span class="spec-value" style="color: #333;">{{ number_format($formData['vehicle_mileage'] ?? 0) }}km</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">車検：</span>
                        <span class="spec-value" style="color: #333;">{{ $formData['vehicle_inspection'] ?? '---' }}</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">排気量：</span>
                        <span class="spec-value" style="color: #333;">{{ $formData['vehicle_displacement'] ? intval($formData['vehicle_displacement']) . 'cc' : '---' }}</span>
                    </div>
                </div>
                <div class="price-section" style="margin-top: 15px;">
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">車両本体価格（税込）：</span>
                        <span class="spec-value highlight" style="color: #ff6b35;">{{ number_format(($formData['vehicle_body_price'] ?? 0) / 10000, 1) }}万円</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                        <span class="spec-label" style="font-weight: bold; color: #333;">支払総額（税込）：</span>
                        <span class="spec-value highlight" style="color: #ff6b35; font-size: 18px; font-weight: bold;">{{ number_format(($formData['vehicle_price'] ?? 0) / 10000, 1) }}万円</span>
                    </div>
                </div>
            </div>
            
            @if (isset($formData['vehicle_retailer']) && $formData['vehicle_retailer'])
            <div class="retailer-info" style="margin-top: 15px;">
                <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                    <span class="spec-label" style="font-weight: 500; color: #666;">販売店：</span>
                    <span class="spec-value" style="color: #333; font-weight: bold;">{{ $formData['vehicle_retailer'] }}</span>
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="car-info">
            <h3>お問い合わせをする車両情報</h3>
            <p>車両情報がありません</p>
        </div>
        @endif

    <form method="POST" action="{{ route('inquiry.store') }}" class="form-section">
        @csrf

        {{-- hiddenでformDataをすべて送信（配列も対応） --}}
        @foreach ($formData as $key => $value)
            @if (is_array($value))
                @foreach ($value as $v)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        <table class="confirm-table">
            <tr><th>お問い合わせ種別</th><td>{{ implode('、', $formData['inquiry_type'] ?? []) }}</td></tr>
            <tr><th>来店予約希望日</th><td>{{ $formData['visit_date'] ?? '' }}</td></tr>
            <tr><th>来店予約希望時間</th><td>{{ $formData['visit_time'] ?? '' }}</td></tr>
            <tr><th>オンライン商談希望日</th><td>{{ $formData['online_date'] ?? '' }}</td></tr>
            <tr><th>オンライン商談希望時間</th><td>{{ $formData['online_time'] ?? '' }}</td></tr>
            <tr><th>お名前</th><td>{{ $formData['name'] ?? '' }}</td></tr>
            <tr><th>フリガナ</th><td>{{ $formData['furigana'] ?? '' }}</td></tr>
            <tr><th>メールアドレス</th><td>{{ $formData['email'] ?? '' }}</td></tr>
            <tr><th>郵便番号</th><td>{{ $formData['zip'] ?? '' }}</td></tr>
            <tr><th>電話番号</th><td>{{ $formData['phone'] ?? '' }}</td></tr>
            <tr><th>下取り車</th><td>{{ $formData['trade_in'] ?? '' }}</td></tr>
            <tr><th>ローン審査希望</th><td>{{ $formData['loan'] ?? '' }}</td></tr>
            <tr><th>ご質問・ご要望</th><td>{!! nl2br(e($formData['message'] ?? '')) !!}</td></tr>
        </table>

        <div class="confirm-buttons column">
            <button type="submit" class="confirm">この内容で送信する</button>
            <a href="{{ url()->previous() }}" class="back">戻る</a>
        </div>
    </form>
    </div>
</div>

@endsection

