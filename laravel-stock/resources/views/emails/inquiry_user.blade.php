@component('mail::message')
{{ $data['name'] }} 様<br>

この度はお問い合わせいただき、誠にありがとうございます。<br>
以下の内容でお問い合わせを承りました。<br><br>

---

### ■ お問い合わせ内容

お問い合わせ種別：{{ implode('・', $data['inquiry_type'] ?? []) }}<br>
来店予約希望日：{{ $data['visit_date'] ?? '（未入力）' }}<br>
来店予約希望時間：{{ $data['visit_time'] ?? '（未入力）' }}<br>
オンライン商談希望日：{{ $data['online_date'] ?? '（未入力）' }}<br>
オンライン商談希望時間：{{ $data['online_time'] ?? '（未入力）' }}<br>
お名前：{{ $data['name'] ?? '（未入力）' }}<br>
フリガナ：{{ $data['furigana'] ?? '未入力' }}<br>
メールアドレス：{{ $data['email'] ?? '（未入力）' }}<br>
郵便番号：{{ $data['zip'] ?? '（未入力）' }}<br>
電話番号：{{ $data['phone'] ?? '（未入力）' }}<br>
下取り車：{{ $data['trade_in'] ?? '（未入力）' }}<br>
ローン審査希望：{{ $data['loan'] ?? '（未入力）' }}<br>
ご質問・ご要望：<br>
{!! nl2br(e($data['message'] ?? '（なし）')) !!}<br><br>

---

### ■ 車両情報

@if (isset($data['vehicle_manufacturer']) && $data['vehicle_manufacturer'])
車種名：{{ $data['vehicle_manufacturer'] ?? '' }} {{ $data['vehicle_model'] ?? '' }} {{ $data['vehicle_grade'] ?? '' }}<br>
年式：{{ $data['vehicle_year'] ?? '---' }}年<br>
走行距離：{{ number_format($data['vehicle_mileage'] ?? 0) }}km<br>
車検：{{ $data['vehicle_inspection'] ?? '---' }}<br>
排気量：{{ ($data['vehicle_displacement'] ?? false) ? intval($data['vehicle_displacement']) . 'cc' : '---' }}<br>
支払総額（税込）：{{ number_format(($data['vehicle_price'] ?? 0) / 10000, 1) }}万円<br>
車両本体価格（税込）：{{ number_format(($data['vehicle_body_price'] ?? 0) / 10000, 1) }}万円<br>
@if (isset($data['vehicle_retailer']) && $data['vehicle_retailer'])
販売店：{{ $data['vehicle_retailer'] }}<br>
@endif
@else
車種名：---<br>
年式：---<br>
走行距離：---<br>
車検：---<br>
排気量：---<br>
支払総額（税込）：---<br>
車両本体価格（税込）：---<br>
@endif

<br>
内容を確認のうえ、担当よりご連絡差し上げます。<br>

@component('mail::button', ['url' => url('/')])
サイトに戻る
@endcomponent

今後とも CTN をよろしくお願いいたします。

@endcomponent
