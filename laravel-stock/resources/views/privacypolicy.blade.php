@extends('layouts.app')

@section('title', 'プライバシーポリシー｜中古車の最安値検索は【CTN中古車販売】')

@section('content')
@section('header_text', 'プライバシーポリシー')

{{ Breadcrumbs::render('privacypolicy') }}
  <link rel="stylesheet" href="{{ asset('assets/css/policy-terms.css?ver=0808') }}">

<main>
  <!-- 利用規約セクション --> 
  <section class="policy-terms__section">
    <div class="inner_pt1">
      <h2 class="policy-terms-title">プライバシーポリシー</h2>
      <div class="policy-terms-content">
        <p>株式会社CTN（以下「当社」）は、当社が運営するウェブサイト（以下「本サイト」）において取得する個人情報の保護に関して、以下のとおりプライバシーポリシーを定めます。</p>
        <h3>1. 取得する情報</h3>
        <p>当社は、以下の情報を取得することがあります：</p>
        <ul>
          <li>氏名、住所、電話番号、メールアドレス等の連絡先情報</li>
          <li>車両情報（車種、年式、走行距離、登録番号 等）</li>
          <li>査定申込情報、買取希望条件等の取引情報</li>
          <li>Cookieやアクセスログなどの閲覧履歴</li>
        </ul>
        <h3>2. 利用目的</h3>
        <p>取得した情報は以下の目的で使用します：</p>
        <ul>
          <li>査定業者への情報提供・見積依頼代行</li>
          <li>ユーザーへのサービス案内・サポート対応</li>
          <li>サイトの利用分析・改善</li>
          <li>法令に基づく対応</li>
        </ul>

        <h3>3. 情報の提供・委託</h3>
        <p>当社は、以下の範囲で個人情報を第三者に提供または外部業者に委託することがあります：</p>
        <ul>
          <li>一括査定サービス提供のための提携業者（査定会社、車両買取業者等）</li>
          <li>サーバー管理・マーケティング支援などの外部業者</li>
        </ul>

        <h3>4. Cookie等の利用について</h3>
        <p>
          当社は、利便性向上や広告配信のため、Cookie等を利用します。ブラウザの設定により拒否することも可能です。
        </p>

        <h3>5. 安全管理措置</h3>
        <p>
          当社は、個人情報を安全に管理するため、適切なセキュリティ対策を講じます。
        </p>

        <h3>6. 開示・訂正・削除等の対応</h3>
        <p>
          お客様からの請求により、当社が保有する個人情報の開示、訂正、利用停止等に適切に対応いたします。
        </p>

        <h3>第7条（免責事項）</h3>
        <p>
          プライバシーに関するお問い合わせは以下までご連絡ください：<br>
          【メールアドレス】info@ctn-net.co.jp<br>
          【受付時間】平日10:00〜19:00（土日祝除く）
        </p>
        <h3>8. 改定について</h3>
        <p>
          本ポリシーは法令改正等により随時変更される場合があります。
        </p>
      </div>
    </div>
  </section>

  <section class="pagetop-section">
    <!-- ページ最上部へスクロール -->
    <a href="#" class="pagetop">
      PAGETOP
      <img src="{{ asset('assets/img/arrow-up-white.svg') }}" alt="ページトップ">
    </a>
  </section>

</main>

@endsection
