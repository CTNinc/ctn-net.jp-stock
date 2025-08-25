@extends('layouts.app')

@section('title', $makerId . 'の車種一覧｜中古車の最安値検索は【CTN中古車販売】')

@section('content')
{{ Breadcrumbs::render('cars.maker', $makerId) }}

<link rel="stylesheet" href="{{ asset('assets/css/maker-models.css') }}">
<link rel="stylesheet" href="{{('/assets/css/index.css') }}">



<div class="makerlist-wrapper">
  <div class="maker-models-page">
    <div class="">
      <h1>{{ $makerId }}の車種一覧</h1>
      <div class="header-actions">
        <button type="submit" form="model-search-form" class="search-btn" id="search-button" disabled>
          チェックした車種で検索（<span id="selected-count">0</span>/10車種）
        </button>
        <!-- <a href="#" class="view-all-link">すべての{{ $makerId }}の中古車を見る</a> -->
      </div>
    </div>
<!-- 
    <div class="kana-filter">
      <ul>
        <li><a href="#">人気車種</a></li>
        <li><a href="#">英数</a></li>
        <li><a href="#">ア行</a></li>
        <li><a href="#">カ行</a></li>
        <li><a href="#">サ行</a></li>
        <li><a href="#">タ行</a></li>
        <li><a href="#">ナ行</a></li>
        <li><a href="#">ハ行</a></li>
        <li><a href="#">マ行</a></li>
        <li><a href="#">ヤ行</a></li>
        <li><a href="#">ラ行</a></li>
        <li><a href="#">ワ行</a></li>
      </ul>
    </div> -->

    <form method="GET" action="{{ route('cars.index') }}" id="model-search-form">
      <input type="hidden" name="maker" value="{{ $makerId }}">

      <div class="model-grid">
        @foreach ($models as $model)
          <div class="model-card">
            <label>
              <img src="{{ $model['image'] }}" alt="{{ $model['name'] }}" onerror="this.src='{{ asset('assets/img/car-image/no-image.webp') }}'">
              <div class="model-name">
                <input type="checkbox" name="models[]" value="{{ $model['name'] }}" class="model-checkbox">
                {{ $model['name'] }} ({{ $model['count'] }})
              </div>
            </label>
          </div>
        @endforeach
      </div>

      <!-- <button type="submit">検索</button> -->
    </form>

  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.model-checkbox');
    const searchButton = document.getElementById('search-button');
    const selectedCountSpan = document.getElementById('selected-count');
    const maxSelections = 10;

    function updateSelectionCount() {
        const checkedBoxes = document.querySelectorAll('.model-checkbox:checked');
        const count = checkedBoxes.length;
        
        // カウント表示を更新
        selectedCountSpan.textContent = count;
        
        // ボタンの状態を制御
        searchButton.disabled = count === 0;
        
        // 最大選択数に達した場合、他のチェックボックスを無効化
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.disabled = count >= maxSelections;
            }
        });
        
        // ボタンテキストを更新
        if (count === 0) {
            searchButton.textContent = `チェックした車種で検索（${count}/10車種）`;
        } else if (count >= maxSelections) {
            searchButton.innerHTML = `チェックした車種で検索（<span id="selected-count">${count}</span>/10車種・上限到達）`;
            selectedCountSpan = document.getElementById('selected-count'); // 参照を再取得
        } else {
            searchButton.innerHTML = `チェックした車種で検索（<span id="selected-count">${count}</span>/10車種）`;
            selectedCountSpan = document.getElementById('selected-count'); // 参照を再取得
        }
    }

    // 各チェックボックスにイベントリスナーを追加
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionCount);
    });

    // フォーム送信前の検証
    document.getElementById('model-search-form').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.model-checkbox:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('少なくとも1つの車種を選択してください。');
            return false;
        }
        if (checkedBoxes.length > maxSelections) {
            e.preventDefault();
            alert(`最大${maxSelections}車種まで選択できます。`);
            return false;
        }
    });

    // 初期状態を設定
    updateSelectionCount();
});
</script>

@endsection