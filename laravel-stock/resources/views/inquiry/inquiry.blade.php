@extends('layouts.app')

@section('content')
@section('header_text', '問合せフォーム')

<link rel="stylesheet" href="{{('assets/css/inquiry.css') }}">
<script src="https://unpkg.com/kuromoji/build/kuromoji.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI（1.12.1） -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- 日本語化（同じ1.12.1のロケール） -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/i18n/datepicker-ja.min.js"></script>

{{ Breadcrumbs::render('inquiry.create') }}

<div class="inquiry-container">
    <div class="step-progress">
        <div class="step current">お問い合わせ種別</div>
        <div class="step">内容の確認</div>
        <div class="step">完了</div>
    </div>
</div>

<div class="inquiry-container main">
    <div class="flex column">
        @if ($vehicle)
        <div class="car-info">
            <h3>お問い合わせをする車両情報</h3>

            <!-- 車両画像 -->
            <div class="car-image-section" style="margin-bottom: 20px; text-align: center; position: relative; display: inline-block; max-width: 400px; margin: 0 auto;">
                @php
                $mainImage = null;
                if (!empty($vehicle['images'])) {
                foreach ($vehicle['images'] as $img) {
                if (strpos($img['image_url'], '_01.') !== false) {
                $mainImage = $img['image_url'];
                break;
                }
                }
                }
                @endphp
                
                {{-- 询价页NEW标志 - 7天内显示NEW+日期，7天外只显示日期 --}}
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
                
                <img src="{{ $mainImage ?? asset('assets/img/car-image/no-image.webp') }}" alt="車両画像" style="width: 100%; max-width: 400px; border-radius: 8px; margin: 0 auto; display: block;">
            </div>

            <!-- 車両情報 -->
            <div class="car-specs-section">
                <h4 class="car-title" style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #333;">{{ $vehicle['manufacturer_name'] ?? '---' }} {{ $vehicle['car_model_name'] ?? '---' }} {{ $vehicle['grade_name'] ?? '' }}</h4>
                <div class="car-specs-grid" style="margin-bottom: 15px;">
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">年式：</span>
                        <span class="spec-value" style="color: #333;">{{ $vehicle['first_registration_at'] ? date('Y年', strtotime($vehicle['first_registration_at'])) : '---' }}</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">走行：</span>
                        <span class="spec-value" style="color: #333;">{{ number_format($vehicle['mileage'] ?? 0) }}km</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">車検：</span>
                        <span class="spec-value" style="color: #333;">{{ $vehicle['inspection_expiry'] ?? '---' }}</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">排気量：</span>
                        <span class="spec-value" style="color: #333;">{{ $vehicle['engine_displacement'] ?? '---' }}cc</span>
                    </div>
                </div>
                <div class="price-section" style="margin-top: 15px;">
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                        <span class="spec-label" style="font-weight: 500; color: #666;">車両本体価格（税込）：</span>
                        <span class="spec-value highlight" style="color: #ff6b35;">{{ number_format(($vehicle['price_incl_tax'] ?? 0) / 10000, 1) }}万円</span>
                    </div>
                    <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                        <span class="spec-label" style="font-weight: bold; color: #333;">支払総額（税込）：</span>
                        <span class="spec-value highlight" style="color: #ff6b35; font-size: 18px; font-weight: bold;">{{ number_format(($vehicle['total_payment'] ?? 0) / 10000, 1) }}万円</span>
                    </div>
                </div>
            </div>

            

            @if ($shopName)
            <div class="retailer-info" style="margin-top: 15px;">
                <div class="spec-item" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                    <span class="spec-label" style="font-weight: 500; color: #666;">販売店：</span>
                    <span class="spec-value" style="color: #333; font-weight: bold;">{{ $shopName }}</span>
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

        @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('inquiry.confirm') }}" class="form-section">
            @csrf
            {{-- 車両データを常に送信（空の場合も含む） --}}
            @php
            $mainImage = '';
            if (!empty($vehicle['images'])) {
            foreach ($vehicle['images'] as $img) {
            if (strpos($img['image_url'], '_01.') !== false) {
            $mainImage = $img['image_url'];
            break;
            }
            }
            }

            $vehicleYear = '';
            if (!empty($vehicle['first_registration_at'])) {
            $vehicleYear = date('Y年', strtotime($vehicle['first_registration_at']));
            }
            @endphp

            <input type="hidden" name="vehicle_id" value="{{ $vehicle['id'] ?? '' }}">
            <input type="hidden" name="vehicle_manufacturer" value="{{ $vehicle['manufacturer_name'] ?? '' }}">
            <input type="hidden" name="vehicle_model" value="{{ $vehicle['car_model_name'] ?? '' }}">
            <input type="hidden" name="vehicle_grade" value="{{ $vehicle['grade_name'] ?? '' }}">
            <input type="hidden" name="vehicle_year" value="{{ $vehicleYear }}">
            <input type="hidden" name="vehicle_mileage" value="{{ $vehicle['mileage'] ?? '' }}">
            <input type="hidden" name="vehicle_price" value="{{ $vehicle['total_payment'] ?? '' }}">
            <input type="hidden" name="vehicle_body_price" value="{{ $vehicle['price_incl_tax'] ?? '' }}">
            <input type="hidden" name="vehicle_inspection" value="{{ $vehicle['inspection_expiry'] ?? '' }}">
            <input type="hidden" name="vehicle_displacement" value="{{ $vehicle['engine_displacement'] ?? '' }}">
            <input type="hidden" name="vehicle_retailer" value="{{ $shopName ?? '' }}">
            <input type="hidden" name="shop_name" value="{{ $shopName ?? '' }}">
            <input type="hidden" name="vehicle_image" value="{{ $mainImage }}">

            {{-- 以下、通常のフォームフィールド --}}
            <div class="form-grid">
                <div class="label required">お問い合わせ種別<span>必須</span></div>
                <div>
                    <label><input type="checkbox" name="inquiry_type[]" value="見積依頼"> 見積依頼</label>
                    <label><input type="checkbox" name="inquiry_type[]" value="在庫確認"> 在庫確認</label>
                    <label><input type="checkbox" name="inquiry_type[]" value="来店予約" id="visit-check"> 来店予約</label>
                    <label><input type="checkbox" name="inquiry_type[]" value="オンライン商談予約" id="online-check"> オンライン商談予約</label>
                </div>

                <div class="label">来店予約希望日</div>
                <div><input type="text" name="visit_date" class="conditional" id="visit-date" disabled></div>

                <div class="label">来店予約希望時間</div>
                <div>
                    <select name="visit_time" class="conditional" id="visit-time" disabled>
                        <option value="">選択してください</option>
                        @foreach (range(10, 19) as $hour)
                        <option value="{{ sprintf('%02d:00', $hour) }}">{{ $hour }}時～</option>
                        @endforeach
                    </select>
                </div>

                <div class="label">オンライン商談希望日</div>
                <div><input type="text" name="online_date" class="conditional" id="online-date" disabled></div>

                <div class="label required">オンライン商談希望時間帯</div>
                <div>
                    <select name="online_time" class="conditional" id="online-time" disabled>
                        <option value="">選択してください</option>
                        @foreach (range(10, 19) as $hour)
                        <option value="{{ sprintf('%02d:00', $hour) }}">{{ $hour }}時～</option>
                        @endforeach
                    </select>
                </div>

                <div class="label required">お名前<span>必須</span></div>
                <div><input type="text" name="name" id="name" required></div>

                <div class="label hidden" id="furigana-label">フリガナ</div>
                <div class="hidden" id="furigana-container">
                    <input type="text" name="furigana" id="furigana" readonly>
                </div>

                <div class="label required">メールアドレス<span>必須</span></div>
                <div><input type="email" name="email" required></div>

                <div class="label">郵便番号</div>
                <div><input type="text" name="zip"></div>

                <div class="label required">電話番号<span>必須</span></div>
                <div><input type="text" name="phone" required></div>

                <div class="label">下取り車</div>
                <div>
                    <label><input type="radio" name="trade_in" value="あり"> あり</label>
                    <label><input type="radio" name="trade_in" value="なし"> なし</label>
                </div>

                <div class="label">ローン審査希望</div>
                <div><label><input type="checkbox" name="loan" value="希望する"> 希望する</label></div>

                <div class="label">ご質問・ご要望</div>
                <div><textarea name="message" rows="4"></textarea></div>

                <div class="label">【「当社が取り扱う個人情報について」】はこちら</div>
                <div>
                    <label><input type="checkbox" name="privacy_agree" value="1" required> 「当社が取り扱う個人情報について」の内容に同意する</label>
                </div>
            </div>

            <div class="submit-area">
                <button type="submit">送信内容を確認する</button>
            </div>
        </form>


    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const visitCheck = document.getElementById('visit-check');
    const onlineCheck = document.getElementById('online-check');

    const visitDate = document.getElementById('visit-date');
    const visitTime = document.getElementById('visit-time');
    const onlineDate = document.getElementById('online-date');
    const onlineTime = document.getElementById('online-time');

    function toggleFields(checkbox, fields) {
        if (checkbox.checked) {
            fields.forEach(field => {
                field.disabled = false;
                field.required = true;
            });
        } else {
            fields.forEach(field => {
                field.disabled = true;
                field.required = false;
                field.value = ''; // 値もリセット（任意）
            });
        }
    }

    visitCheck.addEventListener('change', () => {
        toggleFields(visitCheck, [visitDate, visitTime]);
    });

    onlineCheck.addEventListener('change', () => {
        toggleFields(onlineCheck, [onlineDate, onlineTime]);
    });

    // ページ読み込み時も初期化（戻るボタン対応）
    toggleFields(visitCheck, [visitDate, visitTime]);
    toggleFields(onlineCheck, [onlineDate, onlineTime]);
});
</script>



<script>
    document.addEventListener('DOMContentLoaded', () => {
        const visitCheck = document.getElementById('visit-check');
        const onlineCheck = document.getElementById('online-check');

        function toggleFields(checkbox, ids) {
            ids.forEach(id => {
                const el = document.getElementById(id);
                el.disabled = !checkbox.checked;
            });
        }

        visitCheck.addEventListener('change', () => {
            toggleFields(visitCheck, ['visit-date', 'visit-time']);
        });

        onlineCheck.addEventListener('change', () => {
            toggleFields(onlineCheck, ['online-date', 'online-time']);
        });
    });
</script>

<!-- kuromojiのCDN（またはローカル設置） -->
<script src="https://unpkg.com/kuromoji/build/kuromoji.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const nameInput = document.querySelector('input[name="name"]');
        const furiganaInput = document.querySelector('input[name="furigana"]');
        const furiganaLabel = document.getElementById('furigana-label');
        const furiganaContainer = document.getElementById('furigana-container');

        let tokenizer = null;
        kuromoji.builder({
            dicPath: "../../stock/kuromoji/dict"
        }).build(function(err, builtTokenizer) {
            if (err) {
                console.error("kuromoji initialization failed", err);
                return;
            }
            tokenizer = builtTokenizer;
            console.log("kuromoji initialized!");
        });

        function convertToKatakanaFromKanji(input, callback) {
            if (!tokenizer) return;
            const tokens = tokenizer.tokenize(input);
            const reading = tokens.map(token => token.reading || token.surface_form).join('');
            callback(reading);
        }

        nameInput.addEventListener('blur', () => {
            const name = nameInput.value.trim();
            if (!name || !tokenizer) return;

            convertToKatakanaFromKanji(name, kana => {
                furiganaInput.value = kana;
                furiganaLabel.classList.remove('hidden');
                furiganaContainer.classList.remove('hidden');
            });
        });
    });
</script>




<script>
    $.datepicker.regional['ja'] = {
        closeText: '閉じる',
        prevText: '&#x3C;前',
        nextText: '次&#x3E;',
        currentText: '今日',
        monthNames: ['1月', '2月', '3月', '4月', '5月', '6月',
            '7月', '8月', '9月', '10月', '11月', '12月'
        ],
        monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月',
            '7月', '8月', '9月', '10月', '11月', '12月'
        ],
        dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
        dayNamesShort: ['日', '月', '火', '水', '木', '金', '土'],
        dayNamesMin: ['日', '月', '火', '水', '木', '金', '土'],
        weekHeader: '週',
        dateFormat: 'yy-mm-dd',
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: '年'
    };
    $.datepicker.setDefaults($.datepicker.regional['ja']);
</script>

<script>
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["ja"]); // ★日本語設定を最初に！

        $("#visit-date, #online-date").datepicker({
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            onSelect: function(dateText, inst) {
                const date = $(this).datepicker("getDate");
                const y = date.getFullYear();
                const m = ('0' + (date.getMonth() + 1)).slice(-2);
                const d = ('0' + date.getDate()).slice(-2);
                $(this).val(`${y}年${m}月${d}日`);
            }
        });
    });
</script>






@endsection