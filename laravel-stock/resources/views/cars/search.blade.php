@extends('layouts.app')

@section('title', 'CTN車販売')

@section('content')
<main>
  
  <!-- セクション： -->
  <section class="">

    <h1>検索一覧</h1>
    <h1>検索一覧</h1>
    <h1>検索一覧</h1>
    <h1>検索一覧</h1>
    <h1>検索一覧</h1>
    <h1>検索一覧</h1>
    <h1>検索一覧</h1>
    <h1>検索一覧</h1>
    <h1>検索一覧</h1>
    <h1>検索一覧</h1>
    <div class="tetetete">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse ($vehicles as $vehicle)
            <div class="border rounded p-3 shadow">
                <a href="{{ route('cars.detail', ['id' => $vehicle['id']]) }}">
                    <div class="maker-heading" bis_skin_checked="1">
                        <span></span>
                        <p class="maker">{{ $vehicle['manufacturer_name'] }}</p>
                    </div>
                    <p class="car-name">{{ $vehicle['car_model_name'] ?? '---' }}</p>
                    <p class="info-line price">
                        <span class="label">車両価格</span>
                        <span class="value">{{ number_format($vehicle['price_incl_tax']) }}万円</span>
                    </p>
                    <p class="info-line total">
                        <span class="label">支払総額</span>
                        <span class="value">{{ number_format($vehicle['total_payment']) }}万円</span>
                    </p>
                    <p class="info-line year">
                        <span class="label">年式</span>
                        <span class="value">{{ $vehicle['model_year'] }}</span>
                    </p>
                    <p class="info-line distance">
                        <span class="label">走行距離</span>
                        <span class="value">{{ number_format($vehicle['mileage']) }}㎞</span>
                    </p>
                    <p class="info-line shop">
                        <span class="value">{{ $vehicle['pref'] }}</span>
                    </p>
                </a>
            </div>
        @empty
            <p></p>
        @endforelse
    </div>
    <div>
        @if ($pagination)
            <div class="pagination">
                @php
                    $currentPage = $pagination['current_page'];
                    $lastPage = $pagination['last_page'];
                    $queryParams = request()->except('page');
                @endphp

                @if ($currentPage > 1)
                    <a href="{{ route('search.results', array_merge($queryParams, ['page' => $currentPage - 1])) }}">« Trước</a>
                @endif

                <span>Trang {{ $currentPage }} / {{ $lastPage }}</span>

                @if ($currentPage < $lastPage)
                    <a href="{{ route('search.results', array_merge($queryParams, ['page' => $currentPage + 1])) }}">Tiếp »</a>
                @endif
            </div>
        @endif
    </div>
  </div>

  </section>

</main>



<script>
  

</script>

@endsection
