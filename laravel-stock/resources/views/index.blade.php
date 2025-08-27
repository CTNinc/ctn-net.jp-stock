@extends('layouts.app')

@section('title')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/index.css?ver=0808') }}">

<main>
  <!-- ・コーポレート：corp.ctn-net.jp/????????
  ・サービス総合トップ：ctn-net.jp/
  ・車販売トップ：ctn-net.jp/stock/
  ・車買取：ctn-net.jp/kaitori/car/
  ・バイク買取：ctn-net.jp/kaitori/bike/ -->
  <section class="top-fv">
    <div class="inner_pt1">
      <div class="top-fv__message flex pc">
        <p>比較してみてください<br><span>同じ車なのに</span></p>
        <p>驚きの安さ</p>
      </div>

      <div class="top-fv__message_second">
        <p>全国の販売店の車両を<br>最安値で多数掲載中</p>
      </div>
      <div class="top-fv__message flex sp">
        <p>比較してみてください<br><span>同じ車なのに</span></p>
        <p>驚きの安さ</p>
      </div>
      <img src="{{ asset('assets/img/first_view_girl.webp') }}" class="first_view_img girl">
      <img src="{{ asset('assets/img/first_view_mockup.webp') }}" class="first_view_img mockup" alt="First View Mockup">
      <div class="front">








        <div class="label-box">中古車を探す</div>

        <div class="category-box">
          <button class="cat-button active">
            <img src="{{ asset('assets/img/icon_heading_top_maker.svg') }}" alt="メーカー・車種から探す" />
            <span>メーカー・車種<br><small>から探す</small></span>
          </button>
          <button class="cat-button" data-target="area-modal">
            <img src="{{ asset('assets/img/icon_heading_top_area.svg') }}" alt="地域から探す" />
            <span>地域<br><small>から探す</small></span>
          </button>
          <button class="cat-button">
            <img src="{{ asset('assets/img/icon_heading_top_particular.svg') }}" alt="こだわり条件から探す" />
            <span>こだわり条件<br><small>から探す</small></span>
          </button>
          <button class="cat-button search">検索する</button>
        </div>

        <!-- このselected-boxは「メーカー・車種、地域、こだわり」未選択の場合もしくは初期状態では、非表示。ひとつでも選択された時点で表示させる。 -->
        <div class="selected-box">
          <!-- 「検索条件：」は固定表示 -->
          <span>検索条件：</span>

          <!-- モーダルウィンドウ内で選択されたメーカー・車種名を動的に表示（１つのメーカーにつき複数の車種が選択されていた場合「、」で区切って表示） -->
          <span>例）トヨタ プリウス、アルファード</span>
          <span>/</span>
          <!-- モーダルウィンドウ内で選択されたメーカー・車種名を動的に表示（複数メーカーが選択されていた場合、メーカーごとに「/」で区切る） -->
          <span>例）日産 セレナ</span>
          <span>/</span>
          <!-- モーダルウィンドウ内で選択された地域を表示（複数選択の場合「、」で区切る） -->
          <span>例）大阪、京都、兵庫</span>
          <span>/</span>
          <!-- モーダルウィンドウ内で選択された価格を表示（複数選択の場合「、」で区切る） -->
          <span>例）~20万円</span>
          <span>/</span>
          <!-- モーダルウィンドウ内で選択された価格を表示（複数選択の場合「、」で区切る） -->
          <span>例）~10万km</span>
          <span>/</span>
          <!-- モーダルウィンドウ内で選択された価格を表示（複数選択の場合「、」で区切る） -->
          <span>例）~8名</span>
          <span>/</span>
          <!-- モーダルウィンドウ内で選択された価格を表示（複数選択の場合「、」で区切る） -->
          <span>例）~1500cc</span>

          <!-- ※モーダルウィンドウ内で選択された内容がselected-box内に収まらない場合は、右端を「...」とし省略表示させる -->
        </div>

        <div class="keyword-box pc">
          <input type="text" placeholder="メーカー名、車種名、地域名で簡単検索！" />
          <button>キーワードで検索</button>
        </div>





        <!-- モーダルオーバーレイ -->
        <div class="overlay" id="overlay"></div>




        <!-- 地域から探すモーダル new -->
        <div class="modal" id="area-modal">
          <div class="modal-header">
            <h3>
              <!-- ここ！ -->
              <span class="heading-dot"></span>
              地域を選択する
            </h3>
            <p class="heading-text">最大10件まで複数選択が可能です。</p>
            <button class="close-btn" onclick="closeModal()">&times;</button>
          </div>
          <div class="modal-body">
            <!-- <p style="margin-bottom: 12px; font-size: 14px; text-align: right;">最大10件まで複数選択が可能です。</p> -->
            <form class="area-form">
              <!-- 全国 -->
              <div class="area-block">
                <label><input type="checkbox" /> 全国 <span>(123)<!-- 全国 --></span></label>
              </div>

              <!-- 北海道・東北 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北海道・東北 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 北海道 <span>(123)</span></label>
                  <label><input type="checkbox" /> 青森県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 岩手県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 宮城県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 秋田県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山形県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福島県 <span>(123)</span></label>
                </div>
              </div>

              <!-- 北陸・甲信越 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北陸・甲信越 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 新潟県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 富山県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 石川県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福井県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山梨県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 長野県 <span>(123)</span></label>
                </div>
              </div>

              <!-- 北陸・甲信越 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北陸・甲信越 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 新潟県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 富山県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 石川県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福井県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山梨県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 長野県 <span>(123)</span></label>
                </div>
              </div>
              <!-- 北陸・甲信越 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北陸・甲信越 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 新潟県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 富山県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 石川県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福井県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山梨県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 長野県 <span>(123)</span></label>
                </div>
              </div>
              <!-- 北陸・甲信越 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北陸・甲信越 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 新潟県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 富山県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 石川県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福井県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山梨県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 長野県 <span>(123)</span></label>
                </div>
              </div>
              <!-- 北陸・甲信越 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北陸・甲信越 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 新潟県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 富山県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 石川県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福井県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山梨県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 長野県 <span>(123)</span></label>
                </div>
              </div>
              <!-- 北陸・甲信越 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北陸・甲信越 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 新潟県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 富山県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 石川県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福井県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山梨県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 長野県 <span>(123)</span></label>
                </div>
              </div>
              <!-- 北陸・甲信越 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北陸・甲信越 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 新潟県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 富山県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 石川県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福井県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山梨県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 長野県 <span>(123)</span></label>
                </div>
              </div>
              <!-- 北陸・甲信越 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北陸・甲信越 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 新潟県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 富山県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 石川県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福井県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山梨県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 長野県 <span>(123)</span></label>
                </div>
              </div>
              <!-- 北陸・甲信越 -->
              <div class="area-block">
                <label><input type="checkbox" /> 北陸・甲信越 <span>(123)</span></label>
                <div class="prefecture-list">
                  <label><input type="checkbox" /> 新潟県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 富山県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 石川県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 福井県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 山梨県 <span>(123)</span></label>
                  <label><input type="checkbox" /> 長野県 <span>(123)</span></label>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" onclick="closeModal()">検索する</button>
          </div>
        </div>

      </div>
    </div>

    <!-- FV底辺カーブ -->
    <div class="fv-curve">
      <img src="{{ asset('assets/img/first_view_girl.webp') }}" class="first_view_img girl">

      <img src="{{ asset('assets/img/first_view_mockup-sp.webp') }}" class="first_view_img" alt="First View Mockup">
      <svg viewBox="0 -32 1440 150" xmlns="http://www.w3.org/2000/svg">
        <path fill="white" d="M0,0 C480,150 960,150 1440,0 L1440,150 L0,150 Z" />
      </svg>
    </div>
  </section>


  <!-- お知らせスライダー -->
  <!-- <section class="news-section">
    <div class="inner_pt1">
      <div class="news-slider-wrapper relative overflow-hidden">
        <div class="news-arrow left" onclick="prevSlide()">
          <img src="{{ asset('assets/img/circle-arrow-left.svg') }}" alt="左へ">
        </div>

        <div class="news-slider" id="news-slider" style="display: flex; transition: transform 0.5s ease;">
          @foreach($newsList as $news)
          <div class="news-item flex-shrink-0" style="min-width: 100%;">
            <span class="news-date">{{ $news['date'] }}</span>
            <p class="news-text">{{ $news['text'] }}</p>
          </div>
          @endforeach
        </div>

        <div class="news-arrow right" onclick="nextSlide()">
          <img src="{{ asset('assets/img/circle-arrow-right.svg') }}" alt="右へ">
        </div>
      </div>
    </div>
  </section> -->

  <!-- 一時的な静的スライダー -->
  <section class="news-section">
    <div class="inner_pt1">
      <div class="news-slider-wrapper relative overflow-hidden">
        <div class="news-arrow left" onclick="prevSlide()">
          <img src="{{ asset('assets/img/circle-arrow-left.svg') }}" alt="左へ">
        </div>

        <!-- スライダー全体（横並び） -->
        <div class="news-slider" id="news-slider" style="display: flex; transition: transform 0.5s ease;">
          <div class="news-item flex-shrink-0" style="min-width: 100%;">
            <span class="news-date">2025.08.05</span>
            <p class="news-text">新規中古車情報を 12 件追加！</p>
          </div>
          <div class="news-item flex-shrink-0" style="min-width: 100%;">
            <span class="news-date">2025.08.04</span>
            <p class="news-text">車両情報を 8 件更新！</p>
          </div>
          <div class="news-item flex-shrink-0" style="min-width: 100%;">
            <span class="news-date">2025.08.03</span>
            <p class="news-text">特選車を3台ピックアップ中！</p>
          </div>
        </div>

        <div class="news-arrow right" onclick="nextSlide()">
          <img src="{{ asset('assets/img/circle-arrow-right.svg') }}" alt="右へ">
        </div>
      </div>
    </div>
  </section>




  <!-- セクション：検索 -->
  <section class="search__section">

    <!-- <div class="keyword-box sp">
      <input type="text" placeholder="メーカー名、車種名、地域名で簡単検索！" />
      <button>検索する</button>
    </div> -->



    <!-- メーカーから探す -->
    <div class="maker__container">
      <div class="inner_pt1">
        <div class="container-heading">
          <img src="{{ asset('assets/img/icon_heading_top_maker.svg') }}" alt="">
          <h2>メーカーから探す</h2>
        </div>
        <div class="maker-category-box">
          <!-- 1. 国産車 -->
          <div class="maker-class">
            <div class="maker-class-title">
              <h3>国産車</h3>
            </div>
            <div class="maker-class-grid">
              <!-- vehicles.jsonファイルにあるメーカー（manufacturer_name）のみ表示させる　※ただし国産車のみ -->
              <!-- 遷移先はそれぞれ異なる -->
              <div class="maker-class-grid jp">
                @foreach ([
                'トヨタ' => 'toyota', 'ホンダ' => 'honda', '日産' => 'nissan', 'マツダ' => 'mazda',
                'スバル' => 'subaru', 'スズキ' => 'suzuki', '三菱' => 'mitsubishi', 'ダイハツ' => 'daihatsu',
                'レクサス' => 'lexus'
                ] as $makerName => $fileName)
                <a href="{{ url('/cars/maker/' . $fileName) }}" class="maker-class-item" style="display: inline-block; text-align:center; margin:10px;">
                  <div class="maker-logo">
                    <img src="{{ asset('assets/img/maker-logo/maker-logo-' . $fileName . '.png') }}" alt="{{ $makerName }}">
                  </div>
                  <div class="maker-name">{{ $makerName }}</div>
                </a>
                @endforeach
              </div>
            </div>
          </div>
          <!-- 2. 輸入車 -->
          <div class="maker-class">
            <div class="maker-class-title">
              <h3>輸入車</h3>
            </div>
            <div class="maker-class-grid">
              <!-- vehicles.jsonファイルにあるメーカー（manufacturer_name）のみ表示させる　※ただし輸入車のみ -->
              <!-- 遷移先はそれぞれ異なる -->
              @foreach ([
              'メルセデス・ベンツ' => 'mercedes-benz',
              'BMW' => 'bmw',
              'アウディ' => 'audi',
              'フォルクスワーゲン' => 'volkswagen',
              'ポルシェ' => 'porsche',
              'テスラ' => 'tesla',
              'フォード' => 'ford',
              'シボレー' => 'chevrolet',
              'ジープ' => 'jeep',
              'フェラーリ' => 'ferrari'
              ] as $makerName => $fileName)
              <a href="{{ url('/cars/maker/' . $fileName) }}" class="maker-class-item" style="display: inline-block; text-align:center;">
                <div class="maker-logo">
                  <img src="{{ asset('assets/img/maker-logo/maker-logo-' . $fileName . '.png') }}" alt="{{ $makerName }}" loading="lazy">
                </div>
                <div class="maker-name">
                  @if ($makerName === 'メルセデス・ベンツ')
                  {!! 'メルセデス・<br>ベンツ' !!}
                  @elseif ($makerName === 'フォルクスワーゲン')
                  {!! 'フォルクス<br>ワーゲン' !!}
                  @else
                  {{ $makerName }}
                  @endif
                </div>
              </a>
              @endforeach

            </div>
            <div class="more-pt2-link">
              <a href="/stock/cars/makerlist/">
                <span>
                  全てのメーカーを見る
                </span>
                <img src="{{ asset('assets/img/circle-arrow-right.svg') }}" alt="">
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ボディタイプから探す -->
    <div class="bodytype__container">
      <div class="inner_pt1">
        <div class="container-heading">
          <img src="{{ asset('assets/img/icon_heading_top_bodytype.svg') }}" alt="" loading="lazy">
          <h2>ボディタイプから探す</h2>
        </div>
        <div class="search-list-box">
          <div class="maker-grid">
            @php
            $bodyTypeDisplayNames = [
            'ステーションワゴン' => 'ステーションワゴン',
            'クロカン4WD＆SUV' => 'クロカン・SUV',
            'ハイブリッド' => 'ハイブリッド',
            'コンパクト' => 'コンパクト',
            'ミニバン＆1BOX' => 'ミニバン・ワンボックス',
            'クーペ' => 'クーペ',
            '軽カー' => '軽自動車',
            'トラック・バス' => 'トラック・バス',
            '輸入車' => '輸入車',
            '福祉車両' => '福祉車両',
            'セダン' => 'セダン',
            'その他' => 'その他',
            ];

            $bodyTypeIds = [
            'ステーションワゴン' => 1,
            'クロカン4WD＆SUV' => 2,
            'ハイブリッド' => 3,
            'コンパクト' => 4,
            'ミニバン＆1BOX' => 5,
            'クーペ' => 6,
            '軽カー' => 7,
            'トラック・バス' => 8,
            '輸入車' => 9,
            '福祉車両' => 10,
            'セダン' => 11,
            'その他' => 12,
            ];
            @endphp
            @foreach (array_keys($bodyTypeDisplayNames) as $bodyTypeKey)
            @php
            $id = $bodyTypeIds[$bodyTypeKey] ?? 0; // default 0 nếu không có id
            @endphp
            <a href="{{ route('cars.index', ['bodyType' => $bodyTypeKey]) }}" class="maker-item">
              <div class="icon">
                <img src="{{ asset('assets/img/bodytype-img/bodytype-icons-' . $id . '.webp') }}" alt="{{ $bodyTypeDisplayNames[$bodyTypeKey] }}" loading="lazy">
              </div>
              <div class="maker">{{ $bodyTypeDisplayNames[$bodyTypeKey] }}</div>
            </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- こだわり条件で探す -->
    <div class="particular__container">
      <div class="inner_pt1">
        <div class="container-heading">
          <img src="{{ asset('assets/img/icon_heading_top_particular.svg') }}" alt="" loading="lazy">
          <h2>こだわり条件で探す</h2>
        </div>
        <!-- 価格から探す -->
        <div class="search-list-box mb-24">
          <div class="category-header">
            <span class="dot"></span>
            <span class="group-car">価格から探す</span>
          </div>
          <div class="number-flex scroll" data-simplebar>
            @foreach ([
            ['min' => null, 'max' => 200000, 'label' => '〜20万円'],
            ['min' => null, 'max' => 300000, 'label' => '〜30万円'],
            ['min' => null, 'max' => 400000, 'label' => '〜40万円'],
            ['min' => null, 'max' => 500000, 'label' => '〜50万円'],
            ['min' => null, 'max' => 600000, 'label' => '〜60万円'],
            ['min' => null, 'max' => 700000, 'label' => '〜70万円'],
            ['min' => null, 'max' => 800000, 'label' => '〜80万円'],
            ['min' => null, 'max' => 900000, 'label' => '〜90万円'],
            ['min' => null, 'max' => 1000000, 'label' => '〜100万円'],
            ['min' => null, 'max' => 1500000, 'label' => '〜150万円'],
            ['min' => null, 'max' => 2000000, 'label' => '〜200万円'],
            ['min' => null, 'max' => 2500000, 'label' => '〜250万円'],
            ['min' => null, 'max' => 3000000, 'label' => '〜300万円'],
            ['min' => null, 'max' => 3500000, 'label' => '〜350万円'],
            ['min' => null, 'max' => 4000000, 'label' => '〜400万円'],
            ['min' => null, 'max' => 5000000, 'label' => '〜500万円'],
            ['min' => 5000000, 'max' => null, 'label' => '500万円以上'],
            ] as $price)
            @php
            $query = [];
            if (!is_null($price['min'])) $query['price_min'] = $price['min'];
            if (!is_null($price['max'])) $query['price_max'] = $price['max'];
            $url = route('cars.index') . '?' . http_build_query($query);
            @endphp

            <a href="{{ $url }}" class="number-item">
              <div class="text">
                {{ $price['label'] }}
              </div>
            </a>
            @endforeach
          </div>

        </div>
        <!-- 走行距離から探す -->
        <div class="search-list-box mb-24">
          <div class="category-header">
            <span class="dot"></span>
            <span class="group-car">走行距離から探す</span>
          </div>
          <div class="number-flex scroll" data-simplebar>
            @foreach ([
            ['min' => null, 'max' => 10000, 'label' => '〜10,000km'],
            ['min' => null, 'max' => 20000, 'label' => '〜20,000km'],
            ['min' => null, 'max' => 50000, 'label' => '〜50,000km'],
            ['min' => null, 'max' => 60000, 'label' => '〜60,000km'],
            ['min' => null, 'max' => 80000, 'label' => '〜80,000km'],
            ['min' => null, 'max' => 100000, 'label' => '〜100,000km'],
            ['min' => null, 'max' => 120000, 'label' => '〜120,000km'],
            ['min' => null, 'max' => 140000, 'label' => '〜140,000km'],
            ['min' => null, 'max' => 160000, 'label' => '〜160,000km'],
            ['min' => null, 'max' => 180000, 'label' => '〜180,000km'],
            ['min' => null, 'max' => 200000, 'label' => '〜200,000km'],
            ['min' => null, 'max' => 220000, 'label' => '〜220,000km'],
            ['min' => null, 'max' => 240000, 'label' => '〜240,000km'],
            ['min' => null, 'max' => 280000, 'label' => '〜280,000km'],
            ['min' => 300000, 'max' => null, 'label' => '300,000km以上'],
            ] as $mileage)
            @php
            $query = [];
            if (!is_null($mileage['min'])) $query['mileage_min'] = $mileage['min'];
            if (!is_null($mileage['max'])) $query['mileage_max'] = $mileage['max'];
            $url = route('cars.index') . '?' . http_build_query($query);
            @endphp

            <a href="{{ $url }}" class="number-item">
              <div class="text">
                {{ $mileage['label'] }}
              </div>
            </a>
            @endforeach
          </div>
        </div>
        <!-- 乗車定員から探す・排気量から探す -->
        <div class="search-list-box-col2">
          <!-- 乗車定員から探す -->
          <div class="search-list-box w-half">
            <div class="category-header">
              <span class="dot"></span>
              <span class="group-car">乗車定員から探す</span>
            </div>
            <div class="number-flex scroll" data-simplebar>
              @foreach ([
              ['min' => 4, 'max' =>null, 'label' => '〜4名'],
              ['min' => 8, 'max' =>null, 'label' => '〜8名'],
              ['min' => 12, 'max' =>null, 'label' => '〜12名'],
              ['min' => 16, 'max' =>null, 'label' => '〜16名'],
              ['min' => 24, 'max' =>null, 'label' => '〜24名'],
              ['min' => 30, 'max' =>null, 'label' => '〜30名'],
              ['min' => 40, 'max' =>null, 'label' => '40名以上'],
              ] as $passenger)
              @php
              $query = [];
              if (!is_null($passenger['min'])) $query['passenger_capacity_min'] = $passenger['min'];
              if (!is_null($passenger['max'])) $query['passenger_capacity_max'] = $passenger['max'];
              $url = route('cars.index') . '?' . http_build_query($query);
              @endphp

              <a href="{{ $url }}" class="number-item">
                <div class="text">
                  {{ $passenger['label'] }}
                </div>
              </a>
              @endforeach
            </div>
          </div>
          <!-- 排気量から探す -->
          <div class="search-list-box w-half">
            <div class="category-header">
              <span class="dot"></span>
              <span class="group-car">排気量から探す</span>
            </div>

            <div class="number-flex scroll" data-simplebar>
              @foreach ([
              ['min' => null, 'max' => 1000, 'label' => '〜1000cc'],
              ['min' => null, 'max' => 1500, 'label' => '〜1500cc'],
              ['min' => null, 'max' => 2000, 'label' => '〜2000cc'],
              ['min' => null, 'max' => 2500, 'label' => '〜2500cc'],
              ['min' => null, 'max' => 3000, 'label' => '〜3000cc'],
              ['min' => null, 'max' => 4000, 'label' => '〜4000cc'],
              ['min' => 4000, 'max' => null, 'label' => '4000cc以上'],
              ] as $engine)
              @php
              $query = [];
              if (!is_null($engine['min'])) $query['engine_displacement_min'] = $engine['min'];
              if (!is_null($engine['max'])) $query['engine_displacement_max'] = $engine['max'];
              $url = route('cars.index') . '?' . http_build_query($query);
              @endphp

              <a href="{{ $url }}" class="number-item">
                <div class="text">
                  {{ $engine['label'] }}
                </div>
              </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>






    <!-- ★バナー設置 -->
    <!-- <div class="banner__container">
      <div class="inner_pt1">
        <div class="banner-box-col2">
          <a href="#" class="banner-box w-half">
            <img src="{{ asset('assets/img/test.png') }}" alt="遷移バナー">
          </a>
          <a href="#" class="banner-box w-half">
            <img src="{{ asset('assets/img/test.png') }}" alt="遷移バナー">
          </a>
        </div>
      </div>
    </div> -->

  </section>

  <!-- ★新着中古車両セクション -->
  @php
  if (!function_exists('toWareki')) {
  function toWareki($yearInt) {
  if ($yearInt >= 2019) {
  $g = '令和';
  $n = $yearInt - 2018;
  } elseif ($yearInt >= 1989) {
  $g = '平成';
  $n = $yearInt - 1988;
  } elseif ($yearInt >= 1926) {
  $g = '昭和';
  $n = $yearInt - 1925;
  } else {
  return '';
  }
  return "({$g}" . ($n === 1 ? '元' : $n) . "年)";
  }
  }
  @endphp
  <section class="commons-section">
    <div class="inner_pt1">
      <div class="commons-title">
        <img src="{{ asset('assets/img/icon_heading_top_new_arrival.svg') }}" alt="新着中古車両アイコン">
        <h2>新着中古車両</h2>
      </div>
      <p class="commons-desc">毎日更新！人気車種が驚きの価格で続々登場！</p>
      <div class="commons-cards">
        <div class="swiper commons-swiper">
          @if(count($vehicles) > 0)
          <div class="swiper-wrapper">
            @foreach($vehicles as $vehicle)
            <div class="swiper-slide">

              <a href="{{ url('cars/detail/' . $vehicle['id']) }}" class="commons-card"
                onclick="addToRecentlyViewed('{{ $vehicle['id'] }}')">
                @php
                $mainImage = null;

                if (!empty($vehicle['images'])) {
                foreach ($vehicle['images'] as $img) {
                if (strpos($img['image_url'], '_01.jpg') !== false) {
                $mainImage = $img['image_url'];
                break;
                }
                }
                }
                @endphp

                <div class="commons-image">
                  {{-- 7日以内のNEW+日付を表示し、7日外の日付のみ表示します --}}
                  @if (!empty($vehicle['created_at']))
                    @php
                    $createdDate = \Carbon\Carbon::parse($vehicle['created_at']);
                    $isRecent = $createdDate->gt(\Carbon\Carbon::now()->subDays(7));
                    @endphp
                    
                    <div class="new-badge {{ $isRecent ? 'with-new' : 'date-only' }}">
                      @if ($isRecent)
                      <span class="new-text">NEW</span>
                      @endif
                      <span class="new-date">{{ $createdDate->format('n/j') }}</span>
                    </div>
                  @endif
                  <img src="{{ $mainImage ?? asset('assets/img/test.png') }}" alt="" loading="lazy">
                </div>
                <div class="commons-info">
                  <div class="maker-heading">
                    <p class="maker">{{ $vehicle['manufacturer_name'] }}</p>
                  </div>
                  <p class="car-name">{{ $vehicle['car_model_name'] }} {{ $vehicle['grade_name'] ?? '' }}</p>
                  <p class="info-line price">
                    <span class="label">車両価格</span>
                    <span class="value">{{ number_format(($vehicle['price_incl_tax'] ?? 0) / 10000, 1) }}</span>
                    <span class="unit">万円</span>
                  </p>
                  <p class="info-line price">
                    @php
                    $miscFees = (intval($vehicle['total_payment'] ?? 0)) - (intval($vehicle['price_incl_tax'] ?? 0));
                    @endphp
                    <span class="label">諸費用</span>
                    <span class="value">{{ number_format($miscFees / 10000, 1) }}</span>
                    <span class="unit">万円</span>
                  </p>
                  <p class="info-line total">
                    <span class="label">支払総額</span>
                    <span class="value">{{ number_format(($vehicle['total_payment'] ?? 0) / 10000, 1) }}</span>
                    <span class="unit">万円</span>
                  </p>
                  @php
                  $carbonDate = \Carbon\Carbon::parse($vehicle['first_registration_at']);
                  $year = $carbonDate->format('Y年');
                  $wareki = toWareki($carbonDate->year);
                  @endphp
                  <p class="info-line year">
                    <span class="label">年式</span>
                    <span class="value">{{ $year ?? '-' }}</span>
                    <span class="unit">{{ $wareki }}</span>
                  </p>
                  <p class="info-line distance">
                    <span class="label">走行距離</span>
                    <span class="value">{{ number_format($vehicle['mileage'] ?? 0) }}</span>
                    <span class="unit">km</span>
                  </p>
                  <p class="info-line shop">
                    <span class="value">
                      @if(isset($vehicle['dealer']['name']) && $vehicle['dealer']['name'])
                      {{ $vehicle['dealer']['name'] }}
                      @elseif(isset($vehicle['dealer']['store_id']))
                      店舗ID: {{ $vehicle['dealer']['store_id'] }}
                      @else
                      販売店情報取得中
                      @endif
                    </span>
                  </p>
                </div>
              </a>
            </div>
            @endforeach
          </div>
          @else
          <p></p>
          @endif
          <div class="swiper-pagination"></div>
        </div>
      </div>

      <div class="more-commons-link">

        <a href="{{ route('cars.index', ['sort' => 'new_arrival']) }}" class="btn btn-primary">
          <span>新着中古車両をもっと見る</span>
          <img src="{{ asset('assets/img/circle-arrow-right.svg') }}" alt="">
        </a>
      </div>
    </div>
  </section>

  <!-- ★閲覧履歴セクション -->
  <section class="commons-section">
    <div class="inner_pt1">
      <div class="commons-title">
        <img src="{{ asset('assets/img/icon_heading_top_history.svg') }}" alt="閲覧履歴アイコン">
        <h2>閲覧履歴</h2>
      </div>
      <p class="commons-desc">最近見た車両をチェック！気になった車両をもう一度確認できます。</p>
      <div class="commons-cards">
        <div class="swiper commons-swiper">
          @if(count($historyVehicles) > 0)
          <div class="swiper-wrapper">
            @foreach($historyVehicles as $vehicle)
            <div class="swiper-slide">
              <a href="{{ url('cars/detail/' . $vehicle['id']) }}" class="commons-card"
                onclick="addToRecentlyViewed('{{ $vehicle['id'] }}')">
                @php
                $mainImage = null;

                if (!empty($vehicle['images'])) {
                foreach ($vehicle['images'] as $img) {
                if (strpos($img['image_url'], '_01.jpg') !== false) {
                $mainImage = $img['image_url'];
                break;
                }
                }
                }
                @endphp

                <div class="commons-image">
                  {{-- 閲覧履歴は日付のみ表示 --}}
                  @if (!empty($vehicle['created_at']))
                    @php
                    $createdDate = \Carbon\Carbon::parse($vehicle['created_at']);
                    @endphp
                    
                    <div class="new-badge date-only">
                      <span class="new-date">{{ $createdDate->format('n/j') }}</span>
                    </div>
                  @endif
                  <img src="{{ $mainImage ?? asset('assets/img/test.png') }}" alt="" loading="lazy">
                </div>
                <div class="commons-info">
                  <div class="maker-heading">
                    <p class="maker">{{ $vehicle['manufacturer_name'] }}</p>
                  </div>
                  <p class="car-name">{{ $vehicle['car_model_name'] }} {{ $vehicle['grade_name'] ?? '' }}</p>
                  <p class="info-line price">
                    <span class="label">車両価格</span>
                    <span class="value">{{ number_format(($vehicle['price_incl_tax'] ?? 0) / 10000, 1) }}</span>
                    <span class="unit">万円</span>
                  </p>
                  <p class="info-line price">
                    @php
                    $miscFees = (intval($vehicle['total_payment'] ?? 0)) - (intval($vehicle['price_incl_tax'] ?? 0));
                    @endphp
                    <span class="label">諸費用</span>
                    <span class="value">{{ number_format($miscFees / 10000, 1) }}</span>
                    <span class="unit">万円</span>
                  </p>
                  <p class="info-line total">
                    <span class="label">支払総額</span>
                    <span class="value">{{ number_format(($vehicle['total_payment'] ?? 0) / 10000, 1) }}</span>
                    <span class="unit">万円</span>
                  </p>
                  @php
                  $carbonDate = \Carbon\Carbon::parse($vehicle['first_registration_at']);
                  $year = $carbonDate->format('Y年');
                  $wareki = toWareki($carbonDate->year);
                  @endphp
                  <p class="info-line year">
                    <span class="label">年式</span>
                    <span class="value">{{ $year ?? '-' }}</span>
                    <span class="unit">{{ $wareki }}</span>
                  </p>
                  <p class="info-line distance">
                    <span class="label">走行距離</span>
                    <span class="value">{{ number_format($vehicle['mileage'] ?? 0) }}</span>
                    <span class="unit">km</span>
                  </p>
                  <p class="info-line shop">
                    <span class="value">
                      @if(isset($vehicle['dealer']['name']) && $vehicle['dealer']['name'])
                      {{ $vehicle['dealer']['name'] }}
                      @elseif(isset($vehicle['dealer']['store_id']))
                      店舗ID: {{ $vehicle['dealer']['store_id'] }}
                      @else
                      販売店情報取得中
                      @endif
                    </span>
                  </p>
                </div>
              </a>
            </div>
            @endforeach
          </div>
          @else
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div class="commons-card" style="text-align: center; padding: 40px;">
                <p>閲覧履歴がありません</p>
              </div>
            </div>
          </div>
          @endif
          <div class="swiper-pagination"></div>
        </div>
      </div>

      <div class="more-commons-link">
        <a href="{{ route('cars.index') }}" class="btn btn-primary">
          <span>閲覧履歴をもっと見る</span>
          <img src="{{ asset('assets/img/circle-arrow-right.svg') }}" alt="">
        </a>
      </div>
    </div>
  </section>

  <!-- ★おすすめ車両セクション -->
  <section class="commons-section">
    <div class="inner_pt1">
      <div class="commons-title">
        <img src="{{ asset('assets/img/icon_heading_top_recommend.svg') }}" alt="おすすめ車両アイコン">
        <h2>おすすめ車両</h2>
      </div>
      <p class="commons-desc">あなたにぴったりの車両をセレクト！特選車両をご紹介します。</p>
      <div class="commons-cards">
        <div class="swiper commons-swiper">
          @if(count($recommendedVehicles) > 0)
          <div class="swiper-wrapper">
            @foreach($recommendedVehicles as $vehicle)
            <div class="swiper-slide">
              <a href="{{ url('cars/detail/' . $vehicle['id']) }}" class="commons-card"
                onclick="addToRecentlyViewed('{{ $vehicle['id'] }}')">
                @php
                $mainImage = null;

                if (!empty($vehicle['images'])) {
                foreach ($vehicle['images'] as $img) {
                if (strpos($img['image_url'], '_01.jpg') !== false) {
                $mainImage = $img['image_url'];
                break;
                }
                }
                }
                @endphp

                <div class="commons-image">
                  {{-- おすすめ車両は「おすすめ」ラベル付きで表示 --}}
                  @if (!empty($vehicle['created_at']))
                    @php
                    $createdDate = \Carbon\Carbon::parse($vehicle['created_at']);
                    @endphp
                    
                    <div class="new-badge with-new">
                      <span class="new-text">おすすめ</span>
                      <span class="new-date">{{ $createdDate->format('n/j') }}</span>
                    </div>
                  @endif
                  <img src="{{ $mainImage ?? asset('assets/img/test.png') }}" alt="" loading="lazy">
                </div>
                <div class="commons-info">
                  <div class="maker-heading">
                    <p class="maker">{{ $vehicle['manufacturer_name'] }}</p>
                  </div>
                  <p class="car-name">{{ $vehicle['car_model_name'] }} {{ $vehicle['grade_name'] ?? '' }}</p>
                  <p class="info-line price">
                    <span class="label">車両価格</span>
                    <span class="value">{{ number_format(($vehicle['price_incl_tax'] ?? 0) / 10000, 1) }}</span>
                    <span class="unit">万円</span>
                  </p>
                  <p class="info-line price">
                    @php
                    $miscFees = (intval($vehicle['total_payment'] ?? 0)) - (intval($vehicle['price_incl_tax'] ?? 0));
                    @endphp
                    <span class="label">諸費用</span>
                    <span class="value">{{ number_format($miscFees / 10000, 1) }}</span>
                    <span class="unit">万円</span>
                  </p>
                  <p class="info-line total">
                    <span class="label">支払総額</span>
                    <span class="value">{{ number_format(($vehicle['total_payment'] ?? 0) / 10000, 1) }}</span>
                    <span class="unit">万円</span>
                  </p>
                  @php
                  $carbonDate = \Carbon\Carbon::parse($vehicle['first_registration_at']);
                  $year = $carbonDate->format('Y年');
                  $wareki = toWareki($carbonDate->year);
                  @endphp
                  <p class="info-line year">
                    <span class="label">年式</span>
                    <span class="value">{{ $year ?? '-' }}</span>
                    <span class="unit">{{ $wareki }}</span>
                  </p>
                  <p class="info-line distance">
                    <span class="label">走行距離</span>
                    <span class="value">{{ number_format($vehicle['mileage'] ?? 0) }}</span>
                    <span class="unit">km</span>
                  </p>
                  <p class="info-line shop">
                    <span class="value">
                      @if(isset($vehicle['dealer']['name']) && $vehicle['dealer']['name'])
                      {{ $vehicle['dealer']['name'] }}
                      @elseif(isset($vehicle['dealer']['store_id']))
                      店舗ID: {{ $vehicle['dealer']['store_id'] }}
                      @else
                      販売店情報取得中
                      @endif
                    </span>
                  </p>
                </div>
              </a>
            </div>
            @endforeach
          </div>
          @else
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div class="commons-card" style="text-align: center; padding: 40px;">
                <p>おすすめ車両がありません</p>
              </div>
            </div>
          </div>
          @endif
          <div class="swiper-pagination"></div>
        </div>
      </div>

      <div class="more-commons-link">
        <a href="{{ route('cars.index') }}" class="btn btn-primary">
          <span>おすすめ車両をもっと見る</span>
          <img src="{{ asset('assets/img/circle-arrow-right.svg') }}" alt="">
        </a>
      </div>
    </div>
  </section>





  <!-- ★CTN車販売について -->
  <section class="about-section">
    <div class="inner_pt2">
      <div class="about__content">
        <div class="about__image">
          <img src="{{ asset('assets/img/test.png') }}" alt="CTN車販売サービスロゴ" loading="lazy">
        </div>
        <div class="about__text">
          <h3>CTN中古車検索</h3>
          <p>CTN中古車検索は、「できるだけ安く、でもちゃんと選びたい」そんな方にぴったりの中古車検索サイトです。トヨタ・日産・ホンダなどの人気国産車から、輸入車まで幅広く掲載中。価格にこだわりながら、安心して選べるクルマをご紹介しています。 SUV、軽自動車、ミニバン、セダンなどボディタイプからの絞り込みも簡単。さらに、自社ローンや、修理・メンテナンスに関する情報も掲載しており、購入後も安心のサポート体制を整えています。 初めてのクルマ選びにも、買い替えにも、お得に探せるCTN中古車検索をぜひご活用ください！
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



<script>
  // 掲載台数アニメーション
  function animateCountUp(el, target, duration = 1500) {
    const start = 0;
    const startTime = performance.now();

    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const easedProgress = 1 - Math.pow(1 - progress, 4); // イージング

      const current = Math.floor(start + (target - start) * easedProgress);
      el.textContent = current.toLocaleString();

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        el.textContent = target.toLocaleString(); // 最終値に確実に一致
      }
    }

    requestAnimationFrame(update);
  }

  // DOMが読み込まれたら実行
  document.addEventListener('DOMContentLoaded', () => {
    const countEl = document.getElementById('listing-count');
    const target = Number(countEl.dataset.count || 0);
    animateCountUp(countEl, target);
  });
  // 掲載台数アニメーションここまで
</script>




<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const ids = JSON.parse(localStorage.getItem('recently_viewed') || "[]");

        if (ids.length > 0) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('cars.index') }}"; // Route xử lý

            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = "{{ csrf_token() }}";
            form.appendChild(token);

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });
</script> -->








<script>
  // ファーストビューのタブボタン挙動20250502
  const buttons = document.querySelectorAll(".cat-button");
  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      buttons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
    });
  });


  // FV検索のモーダル実装
  const overlay = document.getElementById('overlay');
  const modals = document.querySelectorAll('.modal');

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-target');
      document.getElementById(targetId).classList.add('active');
      overlay.classList.add('active');
    });
  });

  function closeModal() {
    modals.forEach(modal => modal.classList.remove('active'));
    overlay.classList.remove('active');
  }

  overlay.addEventListener('click', closeModal);



  // お知らせスライダー
  const slider = document.getElementById('news-slider');
  const items = slider.querySelectorAll('.news-item');
  const total = items.length;
  let index = 0;

  function updateSlidePosition() {
    slider.style.transform = `translateX(-${index * 100}%)`;
  }

  function nextSlide() {
    index = (index + 1) % total;
    updateSlidePosition();
  }

  function prevSlide() {
    index = (index - 1 + total) % total;
    updateSlidePosition();
  }

  // 自動スライド
  setInterval(nextSlide, 5000);


  // セクション：メーカー検索・ボディタイプ検索
  document.addEventListener("DOMContentLoaded", function() {
    const searchTabs = document.querySelectorAll(".search-tab");
    const makerTab = document.querySelector(".maker-tab");
    const bodytypeTab = document.querySelector(".bodytype-tab");

    searchTabs.forEach((tab, index) => {
      tab.addEventListener("click", () => {
        // タブ切り替え
        searchTabs.forEach(t => t.classList.remove("active"));
        tab.classList.add("active");

        // 表示切り替え
        if (index === 0) {
          makerTab.style.display = "block";
          bodytypeTab.style.display = "none";
        } else {
          makerTab.style.display = "none";
          bodytypeTab.style.display = "block";
        }
      });
    });

    // 初期表示
    makerTab.style.display = "block";
    bodytypeTab.style.display = "none";
  });
  // セクション：メーカー検索・ボディタイプ検索ここまで



  // ランキング：ボディタイプ別
  const rankBodytypeTabs = document.querySelectorAll(".bodytypetab");
  const bodytypeLists = document.querySelectorAll(".bodytypelist");

  rankBodytypeTabs.forEach(tab => {
    tab.addEventListener("click", () => {
      // タブ切り替え
      rankBodytypeTabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");

      // 表示切り替え
      const target = tab.dataset.type;
      bodytypeLists.forEach(list => {
        list.classList.toggle("active", list.dataset.type === target);
      });
    });
  });
  // ランキング：ボディタイプ別ここまで



  // ランキング：メーカー別
  const rankMakerTabs = document.querySelectorAll(".makertab");
  const makerLists = document.querySelectorAll(".makerlist");

  rankMakerTabs.forEach(tab => {
    tab.addEventListener("click", () => {
      // タブ切り替え
      rankMakerTabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");

      // 表示切り替え
      const target = tab.dataset.maker;
      makerLists.forEach(list => {
        list.classList.toggle("active", list.dataset.maker === target);
      });
    });
  });
  // ランキング：メーカー別ここまで



  // ページトップボタン
  document.querySelector('.pagetop').addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });



  // マッピングホバーアニメーション（地域から探す）
  document.querySelectorAll('area[data-region]').forEach(area => {
    area.addEventListener('mouseenter', () => {
      const region = area.dataset.region;
      document.querySelector(`[data-region-name="${region}"]`)?.classList.add('highlight');
    });

    area.addEventListener('mouseleave', () => {
      const region = area.dataset.region;
      document.querySelector(`[data-region-name="${region}"]`)?.classList.remove('highlight');
    });
  });
</script>

@endsection