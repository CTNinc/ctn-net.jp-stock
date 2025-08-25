<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex">
  <base href="{{ asset('') }}">

  {{-- 動的 title --}}
  <title>
    @hasSection('title')
    @yield('title')
    @else
    中古車の最安値検索はCTN中古車販売｜買っても、売っても好条件でお得！
    @endif
  </title>

  {{-- 動的 description --}}
  @hasSection('meta_description')
  <meta name="description" content="@yield('meta_description')">
  @else
  <meta name="description" content="CTN中古車販売では国内の中古車販売店の在庫を多数掲載しております。売っても、買ってもお得！中古車のことはCTN中古車販売でお得に購入しましょう！">
  @endif

  {{-- OGP --}}
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="CTN中古車販売">

  @hasSection('og_title')
  <meta property="og:title" content="@yield('og_title')">
  @else
  <meta property="og:title" content="中古車の最安値検索はCTN中古車販売｜買っても、売っても好条件でお得！">
  @endif

  @hasSection('og_description')
  <meta property="og:description" content="@yield('og_description')">
  @else
  <meta property="og:description" content="全国の中古車を最安値で検索・購入！高額買取にも対応するCTN中古車販売で、安心・お得な車選びを。">
  @endif

  @hasSection('og_image')
  <meta property="og:image" content="@yield('og_image')">
  @else
  <meta property="og:image" content="https://ctn-net.jp/stock/assets/img/test.png">
  @endif

  {{-- Canonical --}}
  @hasSection('canonical')
  <link rel="canonical" href="@yield('canonical')">
  @else
  <link rel="canonical" href="https://ctn-net.jp/stock/">
  @endif
  <meta name="robots" content="noindex">


  <link rel="icon" type="image/x-icon" href="https://ctn-net.jp/wp-content/themes/ctncoporation/common/images/favicon.ico?20220226">
  <link rel="apple-touch-icon" href="https://ctn-net.jp/wp-content/themes/ctncoporation/common/images/apple-touch-icon.png">
  <link rel="icon" type="image/png" href="https://ctn-net.jp/wp-content/themes/ctncoporation/common/images/android-chrome-192x192.png">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/reset.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/common.css?ver=0808') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/header.css?ver=0808') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/css/simplebar.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">


  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
  <!-- ヘッダー -->
  <header class="header">
    <div class="header-inner">
      <h1 class="header-logo">
        <a href="{{ url('/') }}">
          <img src="{{ asset('assets/img/logo_ctn_black.png') }}" alt="サービスロゴ">
          @hasSection('header_text')
          <span class="visually-hidden">@yield('header_text')</span>
          @endif
        </a>
      </h1>
      <a href="/stock/cars/" class="sp-block search_old_car">中古車検索</a>

      <button class="hamburger" id="hamburger" aria-label="メニュー">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <!-- PCメニュー -->
      <nav class="header-nav">
        <ul class="header-menu">
          <li class="menu-item" id="menu-stock">
            <a href="/stock/cars/">中古車検索</a>
          </li>
          <li class="menu-item">
            <a href="https://ctn-net.jp/kaitori/car/" target="_blank">車買取</a>
          </li>
          <li class="menu-item">
            <a href="https://ctn-net.jp/kaitori/bike/" target="_blank">バイク買取</a>
          </li>
          <li class="menu-separator"></li>
          <li class="menu-item no-underline">
            <a href="https://ctn-net.jp/kaitori/car/column/" target="_blank">お役立ちコラム</a>
          </li>
          <!-- <li class="menu-item no-underline">
            <a href="https://ctn-net.jp/" target="_blank">総合トップ</a>
          </li> -->
        </ul>
      </nav>

      <nav class="sp-nav" id="sp-nav">
        <div class="sp-nav-inner">
          <p class="sp-nav-title">メインメニュー</p>
          <ul class="sp-nav-menu">
            <li><a href="/stock/cars/">中古車検索</a></li>
            <li><a href="https://ctn-net.jp/kaitori/car/" target="_blank">車買取</a></li>
            <li><a href="https://ctn-net.jp/kaitori/bike/" target="_blank">バイク買取</a></li>
            <!-- <li><a href="https://www.ctn-net.jp/useful/" target="_blank">お役立ちコラム</a></li> -->
            <li class=""><a href="https://www.ctn-net.jp/kaitori/car/column/" target="_blank">クルマのあれこれ</a></li>
            <li class=""><a href="https://www.ctn-net.jp/useful/" target="_blank">カーライン</a></li>
            <li><a href="https://mangosteen-japan.com/" target="_blank">MANGOSTEEN</a></li>
            <!-- <li><a href="#">お問い合わせ</a></li> -->
          </ul>
          <button class="menu-close" id="menu-close">メニューを閉じる</button>
        </div>
      </nav>
    </div>
  </header>




  @yield('content')


  <!-- フッター -->
  <footer class="footer">
    <div class="inner_pt1">
      <div class="footer__section">
        <h4>SERVICE</h4>
        <p class="sp-none">
          <a href="/stock/cars/">中古車検索</a>
          <!-- <span>｜</span> -->
          <a href="https://ctn-net.jp/kaitori/car/" target="_blank">中古車買取</a>
          <!-- <span>｜</span> -->
          <a href="https://ctn-net.jp/kaitori/bike/" target="_blank">バイク買取</a>
          <!-- <span>｜</span> -->
          <a href="https://ctn-net.jp/kaitori/car/column/" target="_blank">クルマのあれこれ</a>
          <!-- <span>｜</span> -->
          <a href="https://www.ctn-net.jp/useful/" target="_blank">CarLine</a>
          <!-- <span>｜</span> -->
          <a href="https://mangosteen-japan.com/" target="_blank">MANGOSTEEN</a>
        </p>
        <ul class="sp-block sp-footer_menu">
          <li><a href="/stock/cars/">中古車検索</a></li>
          <li><a href="https://ctn-net.jp/kaitori/car/" target="_blank">中古車買取</a></li>
          <li><a href="https://ctn-net.jp/kaitori/bike/" target="_blank">バイク買取</a></li>
          <li><a href="https://ctn-net.jp/kaitori/car/column/" target="_blank">クルマのあれこれ</a></li>
          <li><a href="https://www.ctn-net.jp/useful/" target="_blank">CarLine</a></li>
          <li><a href="https://mangosteen-japan.com/" target="_blank">MANGOSTEEN</a></li>
        </ul>

      </div>
    </div>

    <div class="footer__divider"></div>
    <div class="inner_pt1">
      <div class="footer__bottom">
        <div class="footer__nav">
          <a href="https://www.ctn-net.jp/" class="arrow-link" target="_blank"><span class="circle-arrow"></span>運営会社</a>
          <a href="https://www.ctn-net.jp/news/" class="arrow-link" target="_blank"><span class="circle-arrow"></span>ニュース</a>
          <a href="https://www.ctn-net.jp/recruit/" class="arrow-link" target="_blank"><span class="circle-arrow"></span>採用情報</a>
          <a href="https://www.ctn-net.jp/stock/privacypolicy/" class="arrow-link" target="_blank"><span class="circle-arrow"></span>プライバシーポリシー</a>
          <a href="https://www.ctn-net.jp/contact/" class="arrow-link" target="_blank"><span class="circle-arrow"></span>お問い合わせ</a>
          <a href="https://ctn-net.jp/stock/terms/" class="arrow-link"><span class="circle-arrow" target="_blank"></span>利用規約</a>
        </div>

        <div class="footer__copy">
          <div class="footer__img">
            <img src="{{ asset('assets/img/logo_ctn_white.png') }}" alt="CTN Logo" class="footer__logo">
          </div>
          <p>© <span id="copyright-year"></span> CTN Co., Ltd. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>

</body>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;

    // ヘッダーメニューの中古車検索li要素を取得
    const stockMenu = document.getElementById('menu-stock');

    // パスに/stockが含まれていたら、オレンジ下線を適用。含まれていなかったら（総合トップなら）、オレンジ除外。
    if (currentPath.includes('/cars')) {
      stockMenu.classList.add('active');
    } else {
      stockMenu.classList.remove('active');
    }
  });



  // コピーライト年取得表示
  document.addEventListener('DOMContentLoaded', function() {
    const year = new Date().getFullYear();
    document.getElementById("copyright-year").textContent = year;
  });
</script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('/assets/js/search.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/swiper.js') }}"></script>



</html>