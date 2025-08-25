@extends('layouts.app')

@section('title', 'CTN車販売')

@section('content')

<main>

    <section class="top-fv">
        <div class="inner_pt1">
        <div class="top-fv__message flex">
        <p>比較してみてください<br><span>同じ車なのに</span></p>
        <p>驚きの安さ</p>
        </div>
        <img src="{{ asset('assets/img/first_view_girl.webp') }}" class="first_view_img girl">
                <img src="{{ asset('assets/img/first_view_mockup.webp') }}" class="first_view_img mockup">
        <div class="front">

            <div class="listing-count">
            <div>
                掲載台数<strong id="listing-count" data-count="">0</strong>台
            </div>
            </div>






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

            <div class="keyword-box">
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
    </section>



    <section class="search__section">
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
                    <div class="maker-class-grid">
                        @foreach ([
                            'トヨタ' => 'toyota', 'ホンダ' => 'honda', '日産' => 'nissan', 'マツダ' => 'mazda',
                            'スバル' => 'subaru', 'スズキ' => 'suzuki', '三菱' => 'mitsubishi', 'ダイハツ' => 'daihatsu',
                            'レクサス' => 'lexus'
                        ] as $makerName => $fileName)
                            <a href="{{ route('cars.maker.test-models', ['maker' => $makerName]) }}" class="maker-class-item" style="display: inline-block; text-align:center; margin:10px;">
                                <div class="maker-logo">
                                    <img src="{{ asset('assets/img/maker-logo/maker-logo-' . $fileName . '.png') }}" alt="{{ $makerName }}">
                                </div>
                                <div class="maker-name">{{ $makerName }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div id="model-results"></div>



            <div class="maker-class">
                <div class="maker-class-title">
                <h3>輸入車</h3>
                </div>
                <div class="maker-class-grid">
                    @foreach ([
                        'メルセデス・ベンツ' => 'mercedes-benz', 'BMW' => 'bmw', 'アウディ' => 'audi', 'フォルクスワーゲン' => 'volkswagen',
                        'ポルシェ' => 'porsche', 'テスラ' => 'tesla', 'フォード' => 'ford', 'シボレー' => 'chevrolet',
                        'ジープ' => 'jeep', 'フェラーリ' => 'ferrari'
                    ] as $makerName => $fileName)
                        <a href="{{ route('search.results', ['maker' => $makerName]) }}" class="maker-class-item">
                            <div class="maker-logo">
                                <img src="{{ asset('assets/img/maker-logo/maker-logo-' . $fileName . '.png') }}" 
                                    alt="{{ $makerName }}"
                                    onerror="this.src='{{ asset('assets/img/maker-logo/maker-logo-default.png') }}'">
                            </div>
                            <div class="maker-name">{{ $makerName }}</div>
                        </a>
                    @endforeach

                </div>
                <div class="more-pt2-link">
                <a href="#">
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
                <img src="{{ asset('assets/img/icon_heading_top_bodytype.svg') }}" alt="">
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
                                $id = $bodyTypeIds[$bodyTypeKey] ?? 0;  // default 0 nếu không có id
                            @endphp
                            <a href="{{ route('search.results', ['bodyType' => $bodyTypeKey]) }}" class="maker-item">
                                <div class="icon">
                                    <img src="{{ asset('assets/img/bodytype-img/bodytype-icons-' . $id . '.webp') }}" alt="{{ $bodyTypeDisplayNames[$bodyTypeKey] }}">
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
          <img src="{{ asset('assets/img/icon_heading_top_particular.svg') }}" alt="">
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
                '0-200000' => '〜20万円',
                '200000-500000' => '20〜50万円',
                '500000-800000' => '50〜80万円',
                '800000-1000000' => '80〜100万円',
                '1000000-1500000' => '100〜150万円',
                '1500000-2000000' => '150〜200万円',
                '2000000-2500000' => '200〜250万円',
                '2500000-3000000' => '250〜300万円',
                '3000000-3500000' => '300〜350万円',
                '3500000-4000000' => '350〜400万円',
                '4000000-4500000' => '400〜450万円',
                '4500000-5000000' => '450〜500万円',
                '5000000-9999999' => '500万円以上',
            ] as $key => $label)
                <a href="{{ route('search.results', ['price' => $key]) }}"  class="number-item">
                  <div class="text">
                    {{ $label }}
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
                '0-20000' => '〜2万km',
                '20000-30000' => '1〜3万km',
                '30000-50000' => '3〜5万km',
                '50000-80000' => '5〜8万km',
                '80000-100000' => '8〜10万km',
                '100000-150000' => '10〜15万km',
                '150000-200000' => '15〜20万km',
                '200000-250000' => '20〜25万km',
                '250000-300000' => '25〜30万km',
                '300000-999999' => '30万キロ以上',
            ] as $key => $label)
                <a href="{{ route('search.results', ['mileage' => $key]) }}" class="number-item">
                  <div class="text">
                    {{ $label }}
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
                  '0-4' => '〜4名',
                  '4-8' => '4〜8名',
                  '8-12' => '8〜12名',
                  '12-16' => '12〜16名',
                  '16-24' => '16〜24名',
                  '24-30' => '24〜30名',
                  '30-40' => '30〜40名',
                  '40-99' => '40名以上',
              ] as $key => $label)
                  <a href="{{ route('search.results', ['capacityRange' => $key]) }}" class="number-item">
                    <div class="text">
                      {{ $label }}
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
                  '0-1000' => '〜1000cc',
                  '1000-1500' => '1000〜1500cc',
                  '1500-2000' => '1500〜2000cc',
                  '2000-2500' => '2000〜2500cc',
                  '2500-3000' => '2500〜3000cc',
                  '3000-4000' => '3000〜4000cc',
                  '4000-9999' => '4000cc以上',
              ] as $key => $label)
                  <a href="{{ route('search.results', ['engineRange' => $key]) }}" class="number-item">
                    <div class="text">
                      {{ $label }}
                    </div>
                  </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>




        <!-- <div class="bodytype__container">
            <div class="inner_pt1">
                <div class="container-heading">
                <img src="{{ asset('assets/img/icon_heading_top_bodytype.svg') }}" alt="">
                <h2>価格から探す</h2>
                </div>
                <div class="search-list-box">
                    <div class="maker-grid">
                        <div class="row">
                            @foreach ([
                                '0-200000' => '〜20万円',
                                '200000-500000' => '20〜50万円',
                                '500000-800000' => '50〜80万円',
                                '800000-1000000' => '80〜100万円',
                                '1000000-1500000' => '100〜150万円',
                                '1500000-2000000' => '150〜200万円',
                                '2000000-2500000' => '200〜250万円',
                                '2500000-3000000' => '250〜300万円',
                                '3000000-3500000' => '300〜350万円',
                                '3500000-4000000' => '350〜400万円',
                                '4000000-4500000' => '400〜450万円',
                                '4500000-5000000' => '450〜500万円',
                                '5000000-9999999' => '500万円以上',
                            ] as $key => $label)
                                <a href="{{ route('search.results', ['price' => $key]) }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="bodytype__container">
            <div class="inner_pt1">
                <div class="container-heading">
                <img src="{{ asset('assets/img/icon_heading_top_bodytype.svg') }}" alt="">
                <h2>走行距離から探す</h2>
                </div>
                <div class="search-list-box">
                    <div class="maker-grid">
                        <div class="row">
                            @foreach ([
                                '0-20000' => '〜2万km',
                                '20000-30000' => '1〜3万km',
                                '30000-50000' => '3〜5万km',
                                '50000-80000' => '5〜8万km',
                                '80000-100000' => '8〜10万km',
                                '100000-150000' => '10〜15万km',
                                '150000-200000' => '15〜20万km',
                                '200000-250000' => '20〜25万km',
                                '250000-300000' => '25〜30万km',
                                '300000-999999' => '30万キロ以上',
                            ] as $key => $label)
                                <a href="{{ route('search.results', ['mileage' => $key]) }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bodytype__container">
            <div class="inner_pt1">
                <div class="container-heading">
                <img src="{{ asset('assets/img/icon_heading_top_bodytype.svg') }}" alt="">
                <h2>乗車定員から探す</h2>
                </div>
                <div class="search-list-box">
                    <div class="maker-grid">
                        <div class="row">
                            @foreach ([
                                '0-4' => '〜4名',
                                '4-8' => '4〜8名',
                                '8-12' => '8〜12名',
                                '12-16' => '12〜16名',
                                '16-24' => '16〜24名',
                                '24-30' => '24〜30名',
                                '30-40' => '30〜40名',
                                '40-99' => '40名以上',
                            ] as $key => $label)
                                <a href="{{ route('search.results', ['capacityRange' => $key]) }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bodytype__container">
            <div class="inner_pt1">
                <div class="container-heading">
                <img src="{{ asset('assets/img/icon_heading_top_bodytype.svg') }}" alt="">
                <h2>排気量から探す</h2>
                </div>
                <div class="search-list-box">
                    <div class="maker-grid">
                        <div class="row">
                            @foreach ([
                                '0-1000' => '〜1000cc',
                                '1000-1500' => '1000〜1500cc',
                                '1500-2000' => '1500〜2000cc',
                                '2000-2500' => '2000〜2500cc',
                                '2500-3000' => '2500〜3000cc',
                                '3000-4000' => '3000〜4000cc',
                                '4000-9999' => '4000cc以上',
                            ] as $key => $label)
                                <a href="{{ route('search.results', ['engineRange' => $key]) }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </section>

</main>
    



