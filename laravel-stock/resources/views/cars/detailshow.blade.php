@extends('layouts.app')

@section('content')
    <h1>車両詳細情報</h1>

    @if(session('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif

    @if ($vehicle)
        <div class="vehicle-detail">
            <h2>{{ $vehicle['manufacturer_name'] ?? '---' }} - {{ $vehicle['car_model_name'] ?? '---' }}</h2>
            <p><strong>グレード:</strong> {{ $vehicle['grade_name'] ?? '---' }}</p>
            <p><strong>車両クラス:</strong> {{ $vehicle['vehicle_class'] ?? '---' }}</p>
            <p><strong>初度登録:</strong> {{ $vehicle['first_registration_at'] ?? '---' }}</p>
            <p><strong>年式:</strong> {{ $vehicle['model_year'] ?? '---' }}</p>
            <p><strong>販売状態:</strong> {{ $vehicle['sales_status'] ?? '---' }}</p>
            <p><strong>ボディタイプ:</strong> {{ $vehicle['body_shape_type'] ?? '---' }}</p>
            <p><strong>外装色:</strong> {{ $vehicle['exterior_color'] ?? '---' }}</p>
            <p><strong>内装色:</strong> {{ $vehicle['interior_color'] ?? '---' }}</p>
            <p><strong>ハンドル:</strong> {{ $vehicle['steering_wheel'] ?? '---' }}</p>
            <p><strong>エンジン排気量:</strong> {{ $vehicle['engine_displacement'] ?? '---' }} cc</p>
            <p><strong>乗車定員:</strong> {{ $vehicle['passenger_capacity'] ?? '---' }} 人</p>
            <p><strong>走行距離:</strong> {{ number_format($vehicle['mileage'] ?? 0) }} km</p>
            <p><strong>価格:</strong> {{ number_format($vehicle['total_payment'] ?? 0) }} 円</p>
            <!-- Thêm các trường khác bạn cần hiển thị -->
        </div>
    @else
        <p>車両情報が見つかりませんでした。</p>
    @endif

    <a href="{{ url()->previous() }}">戻る</a>
@endsection
