@component('mail::message')
以下のお問い合わせがありました：  
<br><br>

---

- **お問い合わせ種別：** {{ implode('、', $data['inquiry_type'] ?? []) }}  
- **来店予約希望日：** {{ $data['visit_date'] ?? '未入力' }}  
- **来店予約希望時間：** {{ $data['visit_time'] ?? '未入力' }}  
- **オンライン商談希望日：** {{ $data['online_date'] ?? '未入力' }}  
- **オンライン商談希望時間帯：** {{ $data['online_time'] ?? '未入力' }}  
- **氏名：** {{ $data['name'] }}  
- **フリガナ：** {{ $data['furigana'] ?? '未入力' }}  
- **メールアドレス：** {{ $data['email'] }}  
- **郵便番号：** {{ $data['zip'] ?? '未入力' }}  
- **電話番号：** {{ $data['phone'] }}  
- **下取り車：** {{ $data['trade_in'] ?? '未選択' }}  
- **ローン審査希望：** {{ $data['loan'] ?? 'なし' }}  
- **ご質問・ご要望：**  
{!! nl2br(e($data['message'] ?? '（なし）')) !!}  

---

@if (isset($data['vehicle_manufacturer']) && $data['vehicle_manufacturer'])
■ お問い合わせ車両情報  
- 車種名：{{ $data['vehicle_manufacturer'] ?? '' }} {{ $data['vehicle_model'] ?? '' }} {{ $data['vehicle_grade'] ?? '' }}  
- 年式：{{ $data['vehicle_year'] ?? '---' }}年  
- 走行距離：{{ number_format($data['vehicle_mileage'] ?? 0) }}km  
- 車検：{{ $data['vehicle_inspection'] ?? '---' }}  
- 排気量：{{ ($data['vehicle_displacement'] ?? false) ? intval($data['vehicle_displacement']) . 'cc' : '---' }}  
- 支払総額（税込）：{{ number_format(($data['vehicle_price'] ?? 0) / 10000, 1) }}万円  
- 車両本体価格（税込）：{{ number_format(($data['vehicle_body_price'] ?? 0) / 10000, 1) }}万円  
@if (isset($data['vehicle_retailer']) && $data['vehicle_retailer'])
- 販売店：{{ $data['vehicle_retailer'] }}  
@endif
@else
■ お問い合わせ車両情報  
- 車種名：---  
- 年式：---  
- 走行距離：---  
- 車検：---  
- 排気量：---  
- 支払総額（税込）：---  
- 車両本体価格（税込）：---  
@endif

---

@component('mail::button', ['url' => url('/')])
管理画面にログイン
@endcomponent
@endcomponent
