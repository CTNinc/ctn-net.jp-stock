@extends('layouts.app')



@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/maker-models.css') }}">
<link rel="stylesheet" href="{{('/assets/css/index.css') }}">



<div class="makerlist-wrapper">
  <div class="maker-models-page">

    <div class="">
      <h1>中古車 {{ $maker }}の車種一覧</h1>
      <div class="header-actions">
        <a href="#" class="search-btn">チェックした車種で検索（最大10車種まで）</a>
        <a href="#" class="view-all-link">すべての{{ $maker }}の中古車を見る</a>
      </div>
    </div>

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
    </div>

    <form method="GET" action="{{ route('cars.index') }}">
      <input type="hidden" name="maker" value="{{ $maker }}">

      <div class="model-grid">
       
      </div>
      @if($modelCount !== null)
<div class="alert alert-info">
    <strong>{{ $requestedModel }}</strong>の検索結果: 
    {{ number_format($modelCount) }}台が見つかりました
</div>
@endif
      <button type="submit">Lọc xe</button>
    </form>

  </div>
</div>
@endsection