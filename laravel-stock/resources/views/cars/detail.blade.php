@extends('layouts.app')

@section('title', ($vehicle['manufacturer_name'] ?? '---') . ($vehicle['car_model_name'] ?? '---') . ' ' . ($vehicle['grade_name'] ?? '---') . 'の中古車 ｜中古車の最安値検索は【CTNの中古車販売】')

@section('content')
@if ($vehicle)
{{ Breadcrumbs::render('cars.detail', $vehicle) }}
  <link rel="stylesheet" href="{{ asset('assets/css/index.css?ver=0808') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/cars-index.css?ver=0808') }}">

  <link rel="stylesheet" href="{{ asset('assets/css/cars-detail.css?ver=0808') }}">

<main>
  <section class="cars__detail-info-top">
    <div class="inner_pt1">
      <!-- 車両名称 -->
      <h2 class="maker-label">
        <!-- <span class="new-badge">NEW</span> -->
        <!-- <span class="maker-name">トヨタ</span> -->
        <span class="maker-name">{{ $vehicle['manufacturer_name'] ?? '---' }}</span>
      </h2>
      <!-- <h3 class="car-title">アルファード ハイブリッド 2.5 Z E-Four 4WD 寒冷地 デジタルミラー HUD ムーンルーフ</h3> -->
      <h3 class="car-title">{{ $vehicle['car_model_name'] ?? '---' }} - {{ $vehicle['grade_name'] ?? '---' }}</h3>

      <div class="car-detail-grid">
        <!-- 左：画像スライダー -->
        <div class="car-images">
          @php
          $mainImage = null;
          $thumbnailImages = [];

          foreach ($vehicle['images'] as $img) {
          if (strpos($img['image_url'], '_01.jpg') !== false) {
          $mainImage = $img['image_url'];
          } else {
          $thumbnailImages[] = $img['image_url'];
          }
          }

          // メイン画像をサムネイル先頭に追加（重複チェック付き）
          if ($mainImage && !in_array($mainImage, $thumbnailImages)) {
          array_unshift($thumbnailImages, $mainImage);
          }
          @endphp

          <div class="main-image">
            {{-- 详情页NEW标志 - 7天内显示NEW+日期，7天外只显示日期 --}}
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
            <img
              id="mainCarImage"
              src="{{ $mainImage ?: 'https://ctn-net.jp/stock/assets/img/test.png' }}"
              alt="Main car image"
              style="width: 100%; border-radius: 8px;">
          </div>


          @if(count($thumbnailImages) > 0)
          <div class="thumbnail-list">
            <div class="thumbnails" style="display: flex; gap: 4px; margin-top: 10px;flex-wrap: wrap;">
              @foreach ($thumbnailImages as $thumb)
              <div class="thumbnail" style="width: 80px; cursor: pointer;" onclick="changeMainImage('{{ $thumb }}')">
                <img src="{{ $thumb }}" alt="サムネイル" style="width: 100%; border-radius: 4px; border: 2px solid transparent;">
              </div>
              @endforeach
            </div>
          </div>
          @endif
        </div>

        <script>
          function changeMainImage(imageUrl) {
            const mainImage = document.getElementById('mainCarImage');
            if (mainImage) {
              mainImage.src = imageUrl;
            }
          }
        </script>


        <!-- 右：情報ブロック -->
        <div class="car-info">

          <!-- 金額 -->
          <div class="price-box pc">
            <div class="price-item total">
              <p class="label">支払総額（税込）</p>
              <div class="price-border"></div>
              <p class="value main">{{ number_format((intval($vehicle['total_payment'] ?? 0)) / 10000, 1) }}<span class="unit">万円</span></p>
            </div>
            <div class="price-item other">
              <p class="label">車両本体価格（税込）</p>
              <div class="price-border"></div>
              <p class="value sub">{{ number_format((intval($vehicle['price_incl_tax'] ?? 0)) / 10000, 1) }}<span class="unit">万円</span></p>
            </div>
            <div class="price-item other">
              <p class="label">諸費用（税込）</p>
              <div class="price-border"></div>
              @php
              $miscFees = (intval($vehicle['total_payment'] ?? 0)) - (intval($vehicle['price_incl_tax'] ?? 0));
              @endphp
              <p class="value sub">{{ number_format($miscFees / 10000, 1) }}<span class="unit">万円</span></p>
            </div>
          </div>

          <div class="price-box sp">
            <div class="price-item other">
              <p class="label">車両本体価格（税込）</p>
              <p class="value sub">{{ number_format((intval($vehicle['price_incl_tax'] ?? 0)) / 10000, 1) }}<span class="unit">万円</span></p>
            </div>
            <div class="price-item other">
              <p class="label">諸費用（税込）</p>
              @php
              $miscFees = (intval($vehicle['total_payment'] ?? 0)) - (intval($vehicle['price_incl_tax'] ?? 0));
              @endphp
              <p class="value sub">{{ number_format($miscFees / 10000, 1) }}<span class="unit">万円</span></p>
            </div>

            <div class="price-item total">
              <p class="label">支払総額（税込）</p>
              <p class="value main">{{ number_format((intval($vehicle['total_payment'] ?? 0)) / 10000, 1) }}<span class="unit">万円</span></p>
            </div>

          </div>

          <!-- 車両スペック -->
          <div class="specs">
            <div class="spec-item">
              <div class="label">年式</div>
              <div class="value">
                @if(isset($vehicle['first_registration_at']) && $vehicle['first_registration_at'])
                {{ date('Y年', strtotime($vehicle['first_registration_at'])) }}
                @else
                ---
                @endif
              </div>
            </div>
            <div class="spec-item">
              <div class="label">走行</div>
              <div class="value">{{ number_format($vehicle['mileage'] ?? 0) }}km</div>
            </div>
            <div class="spec-item">
              <div class="label">排気量</div>
              <div class="value">{{ $vehicle['engine_displacement'] ? intval($vehicle['engine_displacement']) . 'cc' : '---' }}</div>
            </div>
            <div class="spec-item">
              <div class="label">車検</div>
              <div class="value">
                @if(isset($vehicle['inspection_expiry']) && $vehicle['inspection_expiry'])
                {{ date('Y年m月', strtotime($vehicle['inspection_expiry'])) }}
                @else
                未記載
                @endif
              </div>
            </div>
            <div class="spec-item">
              <div class="label">修復歴</div>
              <div class="value">{{ isset($vehicle['repair_history_flag']) && $vehicle['repair_history_flag'] ? 'あり' : 'なし' }}</div>
            </div>
            <div class="spec-item">
              <div class="label">車体色</div>
              <div class="value">{{ $vehicle['exterior_color'] ?? $vehicle['exterior_color_code'] ?? '---' }}</div>
            </div>
            <div class="spec-item">
              <div class="label">ミッション</div>
              <div class="value">{{ $vehicle['transmission_type'] ?? '---' }}</div>
            </div>
            <div class="spec-item">
              <div class="label">乗車定員</div>
              <div class="value">{{ isset($vehicle['passenger_capacity']) && $vehicle['passenger_capacity'] ? $vehicle['passenger_capacity'] . '名' : '---' }}</div>
            </div>
            <div class="spec-item">
              <div class="label">法定整備</div>
              <div class="value">{{ isset($vehicle['legal_maintenance_flag']) && $vehicle['legal_maintenance_flag'] ? 'あり' : 'なし' }}</div>
            </div>
            <div class="spec-item">
              <div class="label">保証</div>
              <div class="value">{{ isset($vehicle['warranty_flag']) && $vehicle['warranty_flag'] ? 'あり' : 'なし' }}</div>
            </div>
          </div>


          <!-- 店舗情報 -->
          <div class="shop-box">
            <div class="shop-box-heading">
              <h4 class="shop-title">販売店情報</h4>
            </div>
            <div class="shop-box-detail">
              @if($dealerInfo && $shopName)
              <p class="shop-name">{{ $shopName }}</p>
              @php
              $fullAddress = '';
              if (!empty($dealerInfo['postal_code'])) {
              $fullAddress .= '〒' . $dealerInfo['postal_code'] . ' ';
              }
              if (!empty($dealerInfo['prefecture'])) {
              $fullAddress .= $dealerInfo['prefecture'];
              }
              if (!empty($dealerInfo['address1'])) {
              $fullAddress .= $dealerInfo['address1'];
              }
              if (!empty($dealerInfo['address2'])) {
              $fullAddress .= $dealerInfo['address2'];
              }
              @endphp
              <p><span class="detail-item">住所</span>：{{ $fullAddress ?: 'お問い合わせください' }}</p>
              <p><span class="detail-item">定休日</span>：お問い合わせください</p>
              <a href="{{ route('inquiry.create', ['vehicle_id' => $vehicle['id'], 'shop_name' => $shopName]) }}">在庫確認・見積り依頼</a>
              @else
              <p class="shop-name">店舗情報取得中</p>
              <p><span class="detail-item">住所</span>：お問い合わせください</p>
              <p><span class="detail-item">定休日</span>：お問い合わせください</p>
              <a href="{{ route('inquiry.create', ['vehicle_id' => $vehicle['id']]) }}">在庫確認・見積り依頼</a>
              @endif
              <!-- htem -->
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>



  <!-- 無料保証 -->
  <section class="cars__detail-info-set">
    <div class="inner_pt1">
      <h4 class="car-set-info-heading">無料保証</h4>
      <div class="car-set-info-grid">
        <!-- 1行目 -->
        <div class="set-info-label">保証期間</div>
        <div class="set-info-value">
          @if((isset($vehicle['warranty_years']) && $vehicle['warranty_years']) || (isset($vehicle['warranty_months']) && $vehicle['warranty_months']) || (isset($vehicle['warranty_days']) && $vehicle['warranty_days']))
          {{ isset($vehicle['warranty_years']) && $vehicle['warranty_years'] ? $vehicle['warranty_years'] . '年' : '' }}
          {{ isset($vehicle['warranty_months']) && $vehicle['warranty_months'] ? $vehicle['warranty_months'] . 'ヶ月' : '' }}
          {{ isset($vehicle['warranty_days']) && $vehicle['warranty_days'] ? $vehicle['warranty_days'] . '日' : '' }}
          @else
          1ヶ月
          @endif
        </div>
        <div class="set-info-label">走行距離</div>
        <div class="set-info-value">
          {{ isset($vehicle['warranty_mileage_limit']) && $vehicle['warranty_mileage_limit'] ? number_format($vehicle['warranty_mileage_limit']) . 'km' : '5,000km' }}
        </div>
        <!-- 2行目 -->
        <div class="set-info-label">上限金額</div>
        <div class="set-info-value">
          {{ isset($vehicle['warranty_cost']) && $vehicle['warranty_cost'] ? number_format($vehicle['warranty_cost']) . '円' : '30,000円' }}
        </div>
        <div class="set-info-label">保証詳細</div>
        <div class="set-info-value">
          {{ isset($vehicle['warranty_details']) && $vehicle['warranty_details'] ? (mb_strlen($vehicle['warranty_details']) > 200 ? mb_substr($vehicle['warranty_details'], 0, 200) . '...' : $vehicle['warranty_details']) : 'なし' }}
        </div>
      </div>
    </div>
  </section>




  <!-- 車両情報 -->
  <section class="cars__detail-info-set">
    <div class="inner_pt1">
      <div class="car-set-info-section-heading">
        <h3 class="set-info-section-up">
          <span class="set-info-section-dot"></span>
          <span class="set-info-section-title">車両情報</span>
        </h3>
        <div class="set-info-section-border"></div>
      </div>
      <!-- 状態 -->
      <h4 class="car-set-info-heading">状態</h4>
      <div class="car-set-info-grid continued">
        <!-- 1行目 -->
        <div class="set-info-label">修復歴</div>
        <div class="set-info-value">{{ isset($vehicle['repair_history_flag']) && $vehicle['repair_history_flag'] ? 'あり' : 'なし' }}</div>
        <div class="set-info-label">走行距離</div>
        <div class="set-info-value">{{ number_format(intval($vehicle['mileage'] ?? 0)) }}km</div>
        <!-- 2行目 -->
        <div class="set-info-label">車検</div>
        <div class="set-info-value">
          @if(isset($vehicle['inspection_expiry']) && $vehicle['inspection_expiry'])
          {{ date('Y年m月', strtotime($vehicle['inspection_expiry'])) }}
          @else
          未記載
          @endif
        </div>
        <div class="set-info-label">初年度登録</div>
        <div class="set-info-value">
          @if(isset($vehicle['first_registration_at']) && $vehicle['first_registration_at'])
          {{ date('Y年m月', strtotime($vehicle['first_registration_at'])) }}
          @else
          ---
          @endif
        </div>
      </div>
      <!-- 基本スペック -->
      <h4 class="car-set-info-heading">基本スペック</h4>
      <div class="car-set-info-grid">
        <!-- 1行目 -->
        <div class="set-info-label">排気量</div>
        <div class="set-info-value">{{ $vehicle['engine_displacement'] ? intval($vehicle['engine_displacement']) . 'cc' : '---' }}</div>
        <div class="set-info-label">駆動方式</div>
        <div class="set-info-value">{{ $vehicle['drive_type'] ?? '---' }}</div>
        <!-- 2行目 -->
        <div class="set-info-label">ミッション</div>
        <div class="set-info-value">{{ $vehicle['transmission_type'] ?? '---' }}</div>
        <div class="set-info-label">燃料</div>
        <div class="set-info-value">
          {{ $vehicle['fuel_type'] ?? '-' }}
        </div>
        <!-- 3行目 -->
        <div class="set-info-label">ドア数</div>
        <div class="set-info-value">{{ $vehicle['door_count'] ? intval($vehicle['door_count']) . 'ドア' : '---' }}</div>
        <div class="set-info-label">乗車定員</div>
        <div class="set-info-value">{{ isset($vehicle['passenger_capacity']) && $vehicle['passenger_capacity'] ? $vehicle['passenger_capacity'] . '名' : '---' }}</div>
      </div>
    </div>
  </section>



  <!-- 装備仕様 -->
  <section class="cars__detail-info-set">
    <div class="inner_pt1">
      <div class="car-set-info-section-heading">
        <h3 class="set-info-section-up">
          <span class="set-info-section-dot"></span>
          <span class="set-info-section-title">装備仕様</span>
        </h3>
        <div class="set-info-section-border"></div>
      </div>
      @php
      $safetyEquipments = [
      '36' => 'パワステ',
      '60' => 'ABS',
      '61' => 'ESC',
      '62' => 'エアバッグ',
      '63' => 'Wエアバッグ',
      '64' => '助手席エアバッグ',
      '65' => 'サイドエアバッグ',
      '69' => 'セキュリティ',
      '70' => 'キーレスエントリー'
      ];
      @endphp
      <!-- 安全装備 -->
      <h4 class="car-set-info-heading">
        安全装備
        @php
        $availableCount = 0;
        foreach ($safetyEquipments as $equipId => $equipName) {
            if (isset($vehicle['equipments']) && isset($vehicle['equipments'][$equipId])) {
                $availableCount++;
            }
        }
        @endphp
        <span class="equipment-count">({{ $availableCount }}/{{ count($safetyEquipments) }})</span>
      </h4>
      <div class="car-set-info-flex continued">
        @foreach ($safetyEquipments as $equipId => $equipName)
        @php
        // 機器が存在する場合は有効と表示されます。存在しない場合は無効と表示されます。
        $isAvailable = isset($vehicle['equipments']) && isset($vehicle['equipments'][$equipId]);
        @endphp
        <div class="set-info-item{{ $isAvailable ? '' : ' disabled' }}" title="{{ $isAvailable ? '装備あり' : '装備なし' }}">
          {{ $equipName }}
        </div>
        @endforeach
      </div>




      @php
      $comfortEquipments = [
      '33' => 'エアコン',
      '34' => 'オートエアコン',
      '37' => 'パワーウィンドウ',
      '38' => 'フルセグTV',
      '39' => 'ナビゲーション',
      '44' => 'CDデッキ',
      '47' => 'バックモニター',
      '48' => 'ETC',
      '66' => 'パワーシート'
      ];
      @endphp
      <!-- 快適装置 -->
      <h4 class="car-set-info-heading">
        快適装置
        @php
        $availableCount = 0;
        foreach ($comfortEquipments as $equipId => $equipName) {
            if (isset($vehicle['equipments']) && isset($vehicle['equipments'][$equipId])) {
                $availableCount++;
            }
        }
        @endphp
        <span class="equipment-count">({{ $availableCount }}/{{ count($comfortEquipments) }})</span>
      </h4>
      <div class="car-set-info-flex continued">
        @foreach ($comfortEquipments as $equipId => $equipName)
        @php
        // 機器が存在する場合は有効と表示されます。存在しない場合は無効と表示されます。
        $isAvailable = isset($vehicle['equipments']) && isset($vehicle['equipments'][$equipId]);
        @endphp
        <div class="set-info-item{{ $isAvailable ? '' : ' disabled' }}" title="{{ $isAvailable ? '装備あり' : '装備なし' }}">
          {{ $equipName }}
        </div>
        @endforeach
      </div>
      @php
      $interiorEquipments = [
      '24' => '禁煙車',
      '32' => 'フル装備',
      '74' => 'メーカー保証'
      ];
      @endphp
      <!-- インテリア -->
      <h4 class="car-set-info-heading">
        インテリア
        @php
        $availableCount = 0;
        foreach ($interiorEquipments as $equipId => $equipName) {
            if (isset($vehicle['equipments']) && isset($vehicle['equipments'][$equipId])) {
                $availableCount++;
            }
        }
        @endphp
        <span class="equipment-count">({{ $availableCount }}/{{ count($interiorEquipments) }})</span>
      </h4>
      <div class="car-set-info-flex continued">
        @foreach ($interiorEquipments as $equipId => $equipName)
        @php
        // 機器が存在する場合は有効と表示されます。存在しない場合は無効と表示されます。
        $isAvailable = isset($vehicle['equipments']) && isset($vehicle['equipments'][$equipId]);
        @endphp
        <div class="set-info-item{{ $isAvailable ? '' : ' disabled' }}" title="{{ $isAvailable ? '装備あり' : '装備なし' }}">
          {{ $equipName }}
        </div>
        @endforeach
      </div>
      @php
      $exteriorEquipments = [
      '28' => 'ディーゼル車',
      '49' => 'アルミ',
      '58' => 'マフラー'
      ];
      @endphp
      <!-- エクステリア -->
      <h4 class="car-set-info-heading">
        エクステリア
        @php
        $availableCount = 0;
        foreach ($exteriorEquipments as $equipId => $equipName) {
            if (isset($vehicle['equipments']) && isset($vehicle['equipments'][$equipId])) {
                $availableCount++;
            }
        }
        @endphp
        <span class="equipment-count">({{ $availableCount }}/{{ count($exteriorEquipments) }})</span>
      </h4>
      <div class="car-set-info-flex continued">
        @foreach ($exteriorEquipments as $equipId => $equipName)
        @php
        // 機器が存在する場合は有効と表示されます。存在しない場合は無効と表示されます。
        $isAvailable = isset($vehicle['equipments']) && isset($vehicle['equipments'][$equipId]);
        @endphp
        <div class="set-info-item{{ $isAvailable ? '' : ' disabled' }}" title="{{ $isAvailable ? '装備あり' : '装備なし' }}">
          {{ $equipName }}
        </div>
        @endforeach
      </div>
    </div>

    <!-- 店舗情報 -->
    <div class="shop-box second">

      <div class="shop-box-detail">
        @if($dealerInfo && $shopName)
        <p class="shop-name">{{ $shopName }}</p>
        <div class="flex">
          <div class="shop_left">
            @php
            $fullAddress = '';
            if (!empty($dealerInfo['postal_code'])) {
            $fullAddress .= '〒' . $dealerInfo['postal_code'] . ' ';
            }
            if (!empty($dealerInfo['prefecture'])) {
            $fullAddress .= $dealerInfo['prefecture'];
            }
            if (!empty($dealerInfo['address1'])) {
            $fullAddress .= $dealerInfo['address1'];
            }
            if (!empty($dealerInfo['address2'])) {
            $fullAddress .= $dealerInfo['address2'];
            }
            @endphp
            <p><span class="detail-item">住所</span>：{{ $fullAddress ?: 'お問い合わせください' }}</p>
            <p><span class="detail-item">定休日</span>：お問い合わせください</p>
          </div>
          <!-- htem -->
          <div class="shop_right">
            <a href="{{ route('inquiry.create', ['vehicle_id' => $vehicle['id'], 'shop_name' => $shopName]) }}">在庫確認・見積り依頼</a>
          </div>
        </div>
        @else
        <p class="shop-name">店舗情報取得中</p>
        <div class="flex">
          <div class="shop_left">
            <p><span class="detail-item">住所</span>：お問い合わせください</p>
            <p><span class="detail-item">定休日</span>：お問い合わせください</p>
          </div>
          <!-- htem -->
          <div class="shop_right">
            <a href="{{ route('inquiry.create', ['vehicle_id' => $vehicle['id']]) }}">在庫確認・見積り依頼</a>
          </div>
        </div>
        @endif
      </div>
  </section>

  <!-- 新車時の基本スペック -->
  <section class="cars__detail-info-set">
    <div class="inner_pt1">
      <h4 class="car-set-info-heading">新車時の基本スペック</h4>
      <div class="car-set-info-grid">
        <!-- 1行目 -->
        <div class="set-info-label">発売年月</div>
        <div class="set-info-value">{{ $vehicle['model_release_date'] ?? '-' }}</div>

        <div class="set-info-label">ホイールベース</div>
        <div class="set-info-value">{{ $vehicle['wheel_base'] ?? '-' }}</div>

        <!-- 2行目 -->
        <div class="set-info-label">車体寸法</div>
        <div class="set-info-value">{{ $vehicle['car_size'] ?? '-' }}</div>

        <div class="set-info-label">燃料</div>
        <div class="set-info-value">{{ $vehicle['fuel_type'] ?? '-' }}</div>

        <!-- 3行目 -->
        <div class="set-info-label">シート列数</div>
        <div class="set-info-value">{{ $vehicle['seat_rows'] ?? '-' }}</div>

        <div class="set-info-label">車両重量</div>
        <div class="set-info-value">{{ $vehicle['car_weight'] ?? '-' }}</div>

        <!-- 4行目 -->
        <div class="set-info-label">室内</div>
        <div class="set-info-value">{{ $vehicle['interior_size'] ?? '-' }}</div>

        <div class="set-info-label">駆動方式</div>
        <div class="set-info-value">{{ $vehicle['drive_system'] ?? '-' }}</div>

        <!-- 5行目 -->
        <div class="set-info-label">JC08燃費</div>
        <div class="set-info-value">{{ $vehicle['fuel_consumption_jc08'] ?? '-' }}</div>

        <div class="set-info-label">10・15燃費</div>
        <div class="set-info-value">{{ $vehicle['fuel_consumption_10_15'] ?? '-' }}</div>
      </div>
    </div>
  </section>

  <!-- TODO：条件分岐で表示 -->
  <section class="cars__index-filtertype-container">
    <!-- メーカー別ボディタイプ一覧 -->
    <div class="cars__index-filtertype">
      <div class="inner_pt1">
        <div class="filtertype-box">
          <p class="filtertype-heading">ボディタイプから探す</p>
          <ul class="filtertype-list">
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
            <li>
              <a href="{{ route('cars.index', ['bodyType' => $bodyTypeKey]) }}">
                {{ $bodyTypeDisplayNames[$bodyTypeKey] }}
              </a>
            </li>
            @endforeach

            <!-- <li><a href="cars/maker/***/?type=4">ミニバン・ワンボックス</a></li>
            <li><a href="cars/maker/***/?type=4">クーペ</a></li>
            <li><a href="cars/maker/***/?type=4">ステーションワゴン</a></li>
            <li><a href="cars/maker/***/?type=4">SUV・クロカン</a></li>
            <li><a href="cars/maker/***/?type=4">コンパクト</a></li>
            <li><a href="cars/maker/***/?type=4">軽自動車</a></li>
            <li><a href="cars/maker/***/?type=4">セダン</a></li>
            <li><a href="cars/maker/***/?type=4">ハイブリッド</a></li>
            <li><a href="cars/maker/***/?type=4">輸入車</a></li>
            <li><a href="cars/maker/***/?type=4">トラック・バス</a></li>
            <li><a href="cars/maker/***/?type=4">福祉車両</a></li>
            <li><a href="cars/maker/***/?type=12">その他</a></li> -->
          </ul>
        </div>
      </div>
    </div>
    <!-- メーカー別価格一覧 -->
    <div class="cars__index-filtertype">
      <div class="inner_pt1">
        <div class="filtertype-box">
          <p class="filtertype-heading">価格から探す</p>
          <ul class="filtertype-list">

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
            <li>
              <a href="{{ $url }}">
                {{ $price['label'] }}
              </a>
            </li>

            @endforeach


            <!-- <li><a href="cars/">~20万円</a></li>
            <li><a href="cars/">~30万円</a></li>
            <li><a href="cars/">~40万円</a></li>
            <li><a href="cars/">~50万円</a></li>
            <li><a href="cars/">~60万円</a></li>
            <li><a href="cars/">~70万円</a></li>
            <li><a href="cars/">~80万円</a></li>
            <li><a href="cars/">~90万円</a></li>
            <li><a href="cars/">~100万円</a></li>
            <li><a href="cars/">~150万円</a></li>
            <li><a href="cars/">~200万円</a></li>
            <li><a href="cars/">~250万円</a></li>
            <li><a href="cars/">~300万円</a></li>
            <li><a href="cars/">~350万円</a></li>
            <li><a href="cars/">~400万円</a></li>
            <li><a href="cars/">~500万円</a></li>
            <li><a href="cars/">500万円以上</a></li> -->
          </ul>
        </div>
      </div>
    </div>
    <!-- メーカー別地域一覧 -->
    <div class="cars__index-filtertype">
      <div class="inner_pt1">
        <div class="filtertype-box">
          <p class="filtertype-heading">地域から探す</p>
          <!-- TODO：現在検索結果のメーカーの取り扱いがある地域を表示（例：検索結果がトヨタの場合、トヨタの取り扱いがある地域のみを表示。検索結果がホンダとスズキの場合、ホンダとスズキの取り扱いがある地域のみを表示。） -->
          <ul class="filtertype-list">
            @foreach([
            '北海道',
            '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県',
            '岐阜県', '静岡県', '愛知県', '三重県',
            '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
            '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県',
            '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県',
            '沖縄県'
            ] as $prefecture)
            <li>
              <a href="{{ route('cars.index', ['pref' => $prefecture]) }}">
                {{ $prefecture }}
              </a>
            </li>
            @endforeach

            <!-- <li><a href="{{ url('cars/pref/hokkaido') }}">北海道</a></li>
            <li><a href="{{ url('cars/pref/aomori') }}">青森県</a></li>
            <li><a href="{{ url('cars/pref/iwate') }}">岩手県</a></li>
            <li><a href="{{ url('cars/pref/miyagi') }}">宮城県</a></li>
            <li><a href="{{ url('cars/pref/akita') }}">秋田県</a></li>
            <li><a href="{{ url('cars/pref/yamagata') }}">山形県</a></li>
            <li><a href="{{ url('cars/pref/fukushima') }}">福島県</a></li>
            <li><a href="{{ url('cars/pref/ibaraki') }}">茨城県</a></li>
            <li><a href="{{ url('cars/pref/tochigi') }}">栃木県</a></li>
            <li><a href="{{ url('cars/pref/gunma') }}">群馬県</a></li>
            <li><a href="{{ url('cars/pref/saitama') }}">埼玉県</a></li>
            <li><a href="{{ url('cars/pref/chiba') }}">千葉県</a></li>
            <li><a href="{{ url('cars/pref/tokyo') }}">東京都</a></li>
            <li><a href="{{ url('cars/pref/kanagawa') }}">神奈川県</a></li>
            <li><a href="{{ url('cars/pref/niigata') }}">新潟県</a></li>
            <li><a href="{{ url('cars/pref/toyama') }}">富山県</a></li>
            <li><a href="{{ url('cars/pref/ishikawa') }}">石川県</a></li>
            <li><a href="{{ url('cars/pref/fukui') }}">福井県</a></li>
            <li><a href="{{ url('cars/pref/yamanashi') }}">山梨県</a></li>
            <li><a href="{{ url('cars/pref/nagano') }}">長野県</a></li>
            <li><a href="{{ url('cars/pref/gifu') }}">岐阜県</a></li>
            <li><a href="{{ url('cars/pref/shizuoka') }}">静岡県</a></li>
            <li><a href="{{ url('cars/pref/aichi') }}">愛知県</a></li>
            <li><a href="{{ url('cars/pref/mie') }}">三重県</a></li>
            <li><a href="{{ url('cars/pref/shiga') }}">滋賀県</a></li>
            <li><a href="{{ url('cars/pref/kyoto') }}">京都府</a></li>
            <li><a href="{{ url('cars/pref/osaka') }}">大阪府</a></li>
            <li><a href="{{ url('cars/pref/hyogo') }}">兵庫県</a></li>
            <li><a href="{{ url('cars/pref/nara') }}">奈良県</a></li>
            <li><a href="{{ url('cars/pref/wakayama') }}">和歌山県</a></li>
            <li><a href="{{ url('cars/pref/tottori') }}">鳥取県</a></li>
            <li><a href="{{ url('cars/pref/shimane') }}">島根県</a></li>
            <li><a href="{{ url('cars/pref/okayama') }}">岡山県</a></li>
            <li><a href="{{ url('cars/pref/hiroshima') }}">広島県</a></li>
            <li><a href="{{ url('cars/pref/yamaguchi') }}">山口県</a></li>
            <li><a href="{{ url('cars/pref/tokushima') }}">徳島県</a></li>
            <li><a href="{{ url('cars/pref/kagawa') }}">香川県</a></li>
            <li><a href="{{ url('cars/pref/ehime') }}">愛媛県</a></li>
            <li><a href="{{ url('cars/pref/kochi') }}">高知県</a></li>
            <li><a href="{{ url('cars/pref/fukuoka') }}">福岡県</a></li>
            <li><a href="{{ url('cars/pref/saga') }}">佐賀県</a></li>
            <li><a href="{{ url('cars/pref/nagasaki') }}">長崎県</a></li>
            <li><a href="{{ url('cars/pref/kumamoto') }}">熊本県</a></li>
            <li><a href="{{ url('cars/pref/oita') }}">大分県</a></li>
            <li><a href="{{ url('cars/pref/miyazaki') }}">宮崎県</a></li>
            <li><a href="{{ url('cars/pref/kagoshima') }}">鹿児島県</a></li>
            <li><a href="{{ url('cars/pref/okinawa') }}">沖縄県</a></li> -->
          </ul>
        </div>
      </div>
    </div>
    <!-- その他メーカー一覧 -->
    <div class="cars__index-filtertype">
      <div class="inner_pt1">
        <div class="filtertype-box">
          <p class="filtertype-heading">その他メーカーから車を探す</p>
          <ul class="filtertype-list">

            @foreach ([
            'トヨタ' => 'toyota', 'ホンダ' => 'honda', '日産' => 'nissan', 'マツダ' => 'mazda',
            'スバル' => 'subaru', 'スズキ' => 'suzuki', '三菱' => 'mitsubishi', 'ダイハツ' => 'daihatsu',
            'レクサス' => 'lexus', 'メルセデス・ベンツ' => 'mercedes-benz', 'BMW' => 'bmw', 'アウディ' => 'audi', 'フォルクスワーゲン' => 'volkswagen',
            'ポルシェ' => 'porsche', 'テスラ' => 'tesla', 'フォード' => 'ford', 'シボレー' => 'chevrolet',
            'ジープ' => 'jeep', 'フェラーリ' => 'ferrari', 'フィアット' => 'fiat', 'マセラティ' => 'maserati', 'ジャガー' => 'jaguar', 'ランドローバー' => 'landrover',
            'アストンマーティン' => 'astonmartin', 'ミニ' => 'mini', 'ルノー' => 'renault', 'プジョー' => 'peugeot', 'シトロエン' => 'citroen'
            ] as $makerName => $fileName)
            <li>
                              <a href="{{ url('/cars/maker/' . urlencode($makerName)) }}">
                {{ $makerName }}
              </a>
            </li>
            @endforeach


            <!-- TODO：現在検索結果のメーカー以外を表示（例：検索結果がトヨタの場合、トヨタ以外を表示。検索結果がホンダとスズキの場合、ホンダとスズキ以外を表示。） -->
            <!-- <li><a href="cars/maker/toyota/">トヨタ</a></li>
            <li><a href="cars/maker/nissan/">日産</a></li>
            <li><a href="cars/maker/honda/">ホンダ</a></li>
            <li><a href="cars/maker/mazda/">マツダ</a></li>
            <li><a href="cars/maker/subaru/">スバル</a></li>
            <li><a href="cars/maker/suzuki/">スズキ</a></li>
            <li><a href="cars/maker/mitsubishi/">三菱</a></li>
            <li><a href="cars/maker/daihatsu/">ダイハツ</a></li>
            <li><a href="cars/maker/lexus/">レクサス</a></li>
            <li><a href="cars/maker/mercedes-benz/">メルセデス・ベンツ</a></li>
            <li><a href="cars/maker/bmw/">BMW</a></li>
            <li><a href="cars/maker/audi/">アウディ</a></li>
            <li><a href="cars/maker/volkswagen/">フォルクスワーゲン</a></li>
            <li><a href="cars/maker/porsche/">ポルシェ</a></li>
            <li><a href="cars/maker/tesla/">テスラ</a></li>
            <li><a href="cars/maker/ford/">フォード</a></li>
            <li><a href="cars/maker/chevrolet/">シボレー</a></li>
            <li><a href="cars/maker/jeep/">ジープ</a></li>
            <li><a href="cars/maker/ferrari/">フェラーリ</a></li>
            <li><a href="cars/maker/lamborghini/">ランボルギーニ</a></li>
            <li><a href="cars/maker/fiat/">フィアット</a></li>
            <li><a href="cars/maker/maserati/">マセラティ</a></li>
            <li><a href="cars/maker/jaguar/">ジャガー</a></li>
            <li><a href="cars/maker/landrover/">ランドローバー</a></li>
            <li><a href="cars/maker/astonmartin/">アストンマーティン</a></li>
            <li><a href="cars/maker/mini/">ミニ</a></li>
            <li><a href="cars/maker/renault/">ルノー</a></li>
            <li><a href="cars/maker/peugeot/">プジョー</a></li>
            <li><a href="cars/maker/citroen/">シトロエン</a></li> -->
          </ul>
        </div>
      </div>
    </div>
  </section>





</main>
@else
<p>車両情報が見つかりませんでした。</p>
@endif


@endsection