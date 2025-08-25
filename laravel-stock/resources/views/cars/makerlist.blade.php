@extends('layouts.app')

@section('title', 'メーカーから最安値を探す｜中古車の最安値検索は【CTN中古車販売】')

@section('content')
{{ Breadcrumbs::render('cars.makerlist') }}
<link rel="stylesheet" href="{{ asset('assets/css/makerlist.css') }}">

  <link rel="icon" type="image/x-icon" href="https://ctn-net.jp/wp-content/themes/ctncoporation/common/images/favicon.ico?20220226">
  <link rel="apple-touch-icon" href="https://ctn-net.jp/wp-content/themes/ctncoporation/common/images/apple-touch-icon.png">
  <link rel="icon" type="image/png" href="https://ctn-net.jp/wp-content/themes/ctncoporation/common/images/android-chrome-192x192.png">

<!-- キー画像をプリロードする -->
<link rel="preload" href="{{ asset('assets/img/country-icon/jp.png') }}" as="image">
<link rel="preload" href="{{ asset('assets/img/country-icon/ger.png') }}" as="image">
<link rel="preload" href="{{ asset('assets/img/country-icon/usa.png') }}" as="image">

<main>



    <div class="makerlist-wrapper">
        <h1 class="page-title">中古車 メーカー一覧</h1>

        {{-- スマホ専用：メーカー国一覧ナビ --}}
        <div class="sp-country-nav">
            <p class="sp-country-nav-title">メーカー国一覧</p>
            <div class="sp-country-scroll scroll" data-simplebar>
                <a href="#jp"><img src="{{ asset('assets/img/country-icon/jp.png') }}" alt="日本"><span>日本</span></a>
                <a href="#ger"><img src="{{ asset('assets/img/country-icon/ger.png') }}" alt="ドイツ"><span>ドイツ</span></a>
                <a href="#uk"><img src="{{ asset('assets/img/country-icon/uk.png') }}" alt="イギリス"><span>イギリス</span></a>
                <a href="#usa"><img src="{{ asset('assets/img/country-icon/usa.png') }}" alt="アメリカ"><span>アメリカ</span></a>
                <a href="#it"><img src="{{ asset('assets/img/country-icon/it.png') }}" alt="イタリア"><span>イタリア</span></a>
                <a href="#fr"><img src="{{ asset('assets/img/country-icon/fr.png') }}" alt="フランス"><span>フランス</span></a>
                <a href="#sw"><img src="{{ asset('assets/img/country-icon/sw.png') }}" alt="スウェーデン"><span>スウェーデン</span></a>
                <a href="#ko"><img src="{{ asset('assets/img/country-icon/ko.png') }}" alt="韓国"><span>韓国</span></a>
                <a href="#aus"><img src="{{ asset('assets/img/country-icon/aus.png') }}" alt="オーストリア"><span>オーストリア</span></a>
            </div>
        </div>

        @foreach ($makersByCountry as $country => $makers)
            <section class="country-section" id="{{ \Str::slug($country) }}">
                <h2 class="country-heading">
                    <img src="{{ asset('assets/img/country-icon/' . \Str::slug($country) . '.png') }}" alt=""> {{ $country }}
                </h2>
                <ul class="maker-list">
                    @foreach ($makers as $maker)
                    <li>
                        <a href="{{ $makerUrls[$maker]['url'] }}" class="{{ $makerUrls[$maker]['class'] }}">
                            {{ $maker }} <span class="count">({{ $makerUrls[$maker]['count'] }})</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </section>
            <hr>
            @endforeach





    </div>





    <!-- ★CTN車販売について -->
    <section class="about-section">
        <div class="inner_pt2">
            <div class="about__content">
                <div class="about__image">
                    <img src="{{ asset('assets/img/test.png') }}" alt="CTN車販売サービスロゴ">
                </div>
                <div class="about__text">
                    <h3>CTN中古車検索について</h3>
                    <p>
                       CTN中古車検索は、「できるだけ安く、でもちゃんと選びたい」そんな方にぴったりの中古車検索サイトです。トヨタ・日産・ホンダなどの人気国産車から、輸入車まで幅広く掲載中。価格にこだわりながら、安心して選べるクルマをご紹介しています。 SUV、軽自動車、ミニバン、セダンなどボディタイプからの絞り込みも簡単。さらに、自社ローンや、修理・メンテナンスに関する情報も掲載しており、購入後も安心のサポート体制を整えています。 初めてのクルマ選びにも、買い替えにも、お得に探せるCTN中古車検索をぜひご活用ください！
                    </p>
                </div>
            </div>
        </div>
        <!-- ページ最上部へスクロール -->
        <a href="#" class="pagetop">
            PAGETOP
            <img src="{{ asset('assets/img/arrow-up-white.svg') }}" alt="">
        </a>
    </section>



</main>

