@extends('layouts.app')

@section('title', '車種一覧｜中古車の最安値検索は【CTN中古車一括査定】')
@section('header_text', '車種一覧')


@section('content')
{{ Breadcrumbs::render('cars.index') }}
  <link rel="stylesheet" href="{{ asset('assets/css/cars-index.css?ver=0808') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/index.css?ver=0808') }}">

<main>
  <div class="sp-flex sp-fliter ">
    <h2>中古車一覧</h2>
    <button class="filter-toggle-btn" id="filterToggle">
      絞り込み<span>＋</span>
    </button>

    <!-- フィルターウィンドウ（元のfilter-boxをここに移動または複製） -->
    <div class="filter-overlay" id="filterOverlay">
      <div class="filter-panel">
        <button class="filter-close-btn" id="filterClose">×</button>
        <h2>絞り込み</h2>

        <!-- 🔽ここにfilter-boxの .search-form などの中身を入れる -->
        <form method="GET" action="{{ route('cars.index') }}" class="car-search-form">
	        <div class="search-form">
	          <div class="search-basic">
	            <div class="line">
	              <div class="column">
	                <div class="pull-down">
	                  <label class="w4em">メーカー</label>
	                  <div>
	                    <div><select name="maker" id="maker-select" class="maker-select w90">
	                        <option value="" {{ request('color') == '' ? 'selected' : '' }}>選択する</option>
	                        @if(isset($makerList))
	                          @foreach($makerList as $makerName)
	                            <option value="{{ $makerName }}" {{ $maker == $makerName ? 'selected' : '' }}>
	                              {{ $makerName }}
	                            </option>
	                          @endforeach
	                        @endif
	                      </select></div>
	                  </div>
	                </div>
	                <div class="pull-down">
	                  <label class="w4em">車種</label>
	                  <div>
	                    <div><select name="vehicle" id="vehicle-select" class="vehicle-select w90" disabled>
	                        <option>選択する</option>
	                      </select></div>
	                  </div>
	                </div>
	                <div class="pull-down">
	                  <label class="w4em">年式</label>
	                  <div>
	                    <select name="year_min" id="year-min" class="year-min w90">
	                      <option value="" {{ request('year_min') == '' ? 'selected' : '' }}>下限なし</option>
	                      <option value="2026" {{ request('year_min') == '2026' ? 'selected' : '' }}>2026(R08)年</option>
	                      <option value="2025" {{ request('year_min') == '2025' ? 'selected' : '' }}>2025(R07)年</option>
	                      <option value="2024" {{ request('year_min') == '2024' ? 'selected' : '' }}  >2024(R06)年</option>
	                      <option value="2023" {{ request('year_min') == '2023' ? 'selected' : '' }}>2023(R05)年</option>
	                      <option value="2022" {{ request('year_min') == '2022' ? 'selected' : '' }}>2022(R04)年</option>
	                      <option value="2021" {{ request('year_min') == '2021' ? 'selected' : '' }}>2021(R03)年</option>
	                      <option value="2020" {{ request('year_min') == '2020' ? 'selected' : '' }}>2020(R02)年</option>
	                      <option value="2019" {{ request('year_min') == '2019' ? 'selected' : '' }}>2019(H31,R01)年</option>
	                      <option value="2018" {{ request('year_min') == '2018' ? 'selected' : '' }}>2018(H30)年</option>
	                      <option value="2017" {{ request('year_min') == '2017' ? 'selected' : '' }}>2017(H29)年</option>
	                      <option value="2016" {{ request('year_min') == '2016' ? 'selected' : '' }}>2016(H28)年</option>
	                      <option value="2015" {{ request('year_min') == '2015' ? 'selected' : '' }}>2015(H27)年</option>
	                      <option value="2014" {{ request('year_min') == '2014' ? 'selected' : '' }}>2014(H26)年</option>
	                      <option value="2013" {{ request('year_min') == '2013' ? 'selected' : '' }}>2013(H25)年</option>
	                      <option value="2012" {{ request('year_min') == '2012' ? 'selected' : '' }}>2012(H24)年</option>
	                      <option value="2011" {{ request('year_min') == '2011' ? 'selected' : '' }}>2011(H23)年</option>
	                      <option value="2010" {{ request('year_min') == '2010' ? 'selected' : '' }}>2010(H22)年</option>
	                      <option value="2009" {{ request('year_min') == '2009' ? 'selected' : '' }}>2009(H21)年</option>
	                      <option value="2008" {{ request('year_min') == '2008' ? 'selected' : '' }}>2008(H20)年</option>
	                      <option value="2007" {{ request('year_min') == '2007' ? 'selected' : '' }}>2007(H19)年</option>
	                      <option value="2006" {{ request('year_min') == '2006' ? 'selected' : '' }}>2006(H18)年</option>
	                      <option value="2005" {{ request('year_min') == '2005' ? 'selected' : '' }}>2005(H17)年</option>
	                      <option value="2004" {{ request('year_min') == '2004' ? 'selected' : '' }}>2004(H16)年</option>
	                      <option value="2003" {{ request('year_min') == '2003' ? 'selected' : '' }}>2003(H15)年</option>
	                      <option value="2002" {{ request('year_min') == '2002' ? 'selected' : '' }}>2002(H14)年</option>
	                      <option value="2001" {{ request('year_min') == '2001' ? 'selected' : '' }}>2001(H13)年</option>
	                      <option value="2000" {{ request('year_min') == '2000' ? 'selected' : '' }}>2000(H12)年</option>
	                      <option value="1999" {{ request('year_min') == '1999' ? 'selected' : '' }}>1999(H11)年</option>
	                      <option value="1998" {{ request('year_min') == '1998' ? 'selected' : '' }}>1998(H10)年</option>
	                      <option value="1997" {{ request('year_min') == '1997' ? 'selected' : '' }}>1997(H09)年</option>
	                      <option value="1996" {{ request('year_min') == '1996' ? 'selected' : '' }}>1996(H08)年</option>
	                      <option value="1995" {{ request('year_min') == '1995' ? 'selected' : '' }}>1995(H07)年</option>
	                      <option value="1994" {{ request('year_min') == '1994' ? 'selected' : '' }}>1994(H06)年</option>
	                      <option value="1993" {{ request('year_min') == '1993' ? 'selected' : '' }}>1993(H05)年</option>
	                      <option value="1992" {{ request('year_min') == '1992' ? 'selected' : '' }}>1992(H04)年</option>
	                      <option value="1991" {{ request('year_min') == '1991' ? 'selected' : '' }}>1991(H03)年</option>
	                      <option value="1990" {{ request('year_min') == '1990' ? 'selected' : '' }}>1990(H02)年</option>
	                      <option value="1989" {{ request('year_min') == '1989' ? 'selected' : '' }}>1989(H01)年</option>
	                    </select>
	                  </div>
	                  <span>〜</span>
	                  <div>
	                    <select name="year_max" id="year-max" class="year-max w90">
	                      <option value="" {{ request('year_max') == '' ? 'selected' : '' }}>上限なし</option>
	                      <option value="2026" {{ request('year_min') == '2026' ? 'selected' : '' }}>2026(R08)年</option>
	                      <option value="2025" {{ request('year_min') == '2025' ? 'selected' : '' }}>2025(R07)年</option>
	                      <option value="2024" {{ request('year_min') == '2024' ? 'selected' : '' }}    >2024(R06)年</option>
	                      <option value="2023" {{ request('year_min') == '2023' ? 'selected' : '' }}>2023(R05)年</option>
	                      <option value="2022" {{ request('year_min') == '2022' ? 'selected' : '' }}>2022(R04)年</option>
	                      <option value="2021" {{ request('year_min') == '2021' ? 'selected' : '' }}>2021(R03)年</option>
	                      <option value="2020" {{ request('year_min') == '2020' ? 'selected' : '' }}>2020(R02)年</option>
	                      <option value="2019" {{ request('year_min') == '2019' ? 'selected' : '' }}>2019(H31,R01)年</option>
	                      <option value="2018" {{ request('year_min') == '2018' ? 'selected' : '' }}>2018(H30)年</option>
	                      <option value="2017" {{ request('year_min') == '2017' ? 'selected' : '' }}>2017(H29)年</option>
	                      <option value="2016" {{ request('year_min') == '2016' ? 'selected' : '' }}>2016(H28)年</option>
	                      <option value="2015" {{ request('year_min') == '2015' ? 'selected' : '' }}>2015(H27)年</option>
	                      <option value="2014" {{ request('year_min') == '2014' ? 'selected' : '' }}>2014(H26)年</option>
	                      <option value="2013" {{ request('year_min') == '2013' ? 'selected' : '' }}>2013(H25)年</option>
	                      <option value="2012" {{ request('year_min') == '2012' ? 'selected' : '' }}>2012(H24)年</option>
	                      <option value="2011" {{ request('year_min') == '2011' ? 'selected' : '' }}>2011(H23)年</option>
	                      <option value="2010" {{ request('year_min') == '2010' ? 'selected' : '' }}>2010(H22)年</option>
	                      <option value="2009" {{ request('year_min') == '2009' ? 'selected' : '' }}>2009(H21)年</option>
	                      <option value="2008" {{ request('year_min') == '2008' ? 'selected' : '' }}>2008(H20)年</option>
	                      <option value="2007" {{ request('year_min') == '2007' ? 'selected' : '' }}>2007(H19)年</option>
	                      <option value="2006" {{ request('year_min') == '2006' ? 'selected' : '' }}>2006(H18)年</option>
	                      <option value="2005" {{ request('year_min') == '2005' ? 'selected' : '' }}>2005(H17)年</option>
	                      <option value="2004" {{ request('year_min') == '2004' ? 'selected' : '' }}>2004(H16)年</option>
	                      <option value="2003" {{ request('year_min') == '2003' ? 'selected' : '' }}>2003(H15)年</option>
	                      <option value="2002" {{ request('year_min') == '2002' ? 'selected' : '' }}>2002(H14)年</option>
	                      <option value="2001" {{ request('year_min') == '2001' ? 'selected' : '' }}>2001(H13)年</option>
	                      <option value="2000" {{ request('year_min') == '2000' ? 'selected' : '' }}>2000(H12)年</option>
	                      <option value="1999" {{ request('year_min') == '1999' ? 'selected' : '' }}>1999(H11)年</option>
	                      <option value="1998" {{ request('year_min') == '1998' ? 'selected' : '' }}>1998(H10)年</option>
	                      <option value="1997" {{ request('year_min') == '1997' ? 'selected' : '' }}>1997(H09)年</option>
	                      <option value="1996" {{ request('year_min') == '1996' ? 'selected' : '' }}>1996(H08)年</option>
	                      <option value="1995" {{ request('year_min') == '1995' ? 'selected' : '' }}>1995(H07)年</option>
	                      <option value="1994" {{ request('year_min') == '1994' ? 'selected' : '' }}>1994(H06)年</option>
	                      <option value="1993" {{ request('year_min') == '1993' ? 'selected' : '' }}>1993(H05)年</option>
	                      <option value="1992" {{ request('year_min') == '1992' ? 'selected' : '' }}>1992(H04)年</option>
	                      <option value="1991" {{ request('year_min') == '1991' ? 'selected' : '' }}>1991(H03)年</option>
	                      <option value="1990" {{ request('year_min') == '1990' ? 'selected' : '' }}>1990(H02)年</option>
	                      <option value="1989" {{ request('year_min') == '1989' ? 'selected' : '' }}>1989(H01)年</option>

	                    </select>
	                  </div>
	                </div>
	                <div class="pull-down">
	                  <label class="w4em">走行距離</label>
	                  <div>
	                    <select name="mileage_min" id="mileage-min" class="mileage-min w90">
	                      <option value="" {{ request('year_min') == '' ? 'selected' : '' }}>下限なし</option>
	                      <option value="5000" {{ request('mileage_min') == '5000' ? 'selected' : '' }}>5000Km</option>
	                      <option value="10000" {{ request('mileage_min') == '10000' ? 'selected' : '' }}>1万Km</option>
	                      <option value="20000" {{ request('mileage_min') == '20000' ? 'selected' : '' }}>2万Km</option>
	                      <option value="30000" {{ request('mileage_min') == '30000' ? 'selected' : '' }}>3万Km</option>
	                      <option value="40000" {{ request('mileage_min') == '40000' ? 'selected' : '' }}>4万Km</option>
	                      <option value="50000" {{ request('mileage_min') == '50000' ? 'selected' : '' }}>5万Km</option>
	                      <option value="60000" {{ request('mileage_min') == '60000' ? 'selected' : '' }}>6万Km</option>
	                      <option value="70000" {{ request('mileage_min') == '70000' ? 'selected' : '' }}>7万Km</option>
	                      <option value="80000" {{ request('mileage_min') == '80000' ? 'selected' : '' }}>8万Km</option>
	                      <option value="90000" {{ request('mileage_min') == '90000' ? 'selected' : '' }}>9万Km</option>
	                      <option value="100000" {{ request('mileage_min') == '100000' ? 'selected' : '' }}>10万Km</option>
	                      <option value="110000" {{ request('mileage_min') == '110000' ? 'selected' : '' }}>11万Km</option>
	                      <option value="120000" {{ request('mileage_min') == '120000' ? 'selected' : '' }}>12万Km</option>
	                      <option value="130000" {{ request('mileage_min') == '130000' ? 'selected' : '' }}>13万Km</option>
	                      <option value="140000" {{ request('mileage_min') == '140000' ? 'selected' : '' }}>14万Km</option>
	                      <option value="150000" {{ request('mileage_min') == '150000' ? 'selected' : '' }}>15万Km</option>
	                    </select>
	                  </div>
	                  <span>〜</span>
	                  <div>
	                    <select name="mileage_max" id="mileage-max" class="mileage-max w90">
	                      <option value="" {{ request('year_max') == '' ? 'selected' : '' }}>上限なし</option>
	                      <option value="5000" {{ request('mileage_min') == '5000' ? 'selected' : '' }}>5000Km</option>
	                      <option value="10000" {{ request('mileage_min') == '10000' ? 'selected' : '' }}>1万Km</option>
	                      <option value="20000" {{ request('mileage_min') == '20000' ? 'selected' : '' }}>2万Km</option>
	                      <option value="30000" {{ request('mileage_min') == '30000' ? 'selected' : '' }}>3万Km</option>
	                      <option value="40000" {{ request('mileage_min') == '40000' ? 'selected' : '' }}>4万Km</option>
	                      <option value="50000" {{ request('mileage_min') == '50000' ? 'selected' : '' }}>5万Km</option>
	                      <option value="60000" {{ request('mileage_min') == '60000' ? 'selected' : '' }}>6万Km</option>
	                      <option value="70000" {{ request('mileage_min') == '70000' ? 'selected' : '' }}>7万Km</option>
	                      <option value="80000" {{ request('mileage_min') == '80000' ? 'selected' : '' }}>8万Km</option>
	                      <option value="90000" {{ request('mileage_min') == '90000' ? 'selected' : '' }}>9万Km</option>
	                      <option value="100000" {{ request('mileage_min') == '100000' ? 'selected' : '' }}>10万Km</option>
	                      <option value="110000" {{ request('mileage_min') == '110000' ? 'selected' : '' }}>11万Km</option>
	                      <option value="120000" {{ request('mileage_min') == '120000' ? 'selected' : '' }}>12万Km</option>
	                      <option value="130000" {{ request('mileage_min') == '130000' ? 'selected' : '' }}>13万Km</option>
	                      <option value="140000" {{ request('mileage_min') == '140000' ? 'selected' : '' }}>14万Km</option>
	                      <option value="150000" {{ request('mileage_min') == '150000' ? 'selected' : '' }}>15万Km</option>
	                    </select>
	                  </div>
	                </div>
	                

                  <div class="pull-down">
	                  <label class="w4em">本体価格</label>
	                  <div>
	                    <select name="body_price_min" id="body-price-min" class="body-price-min w90">
	                      <option value="" {{ request('body_price_min') == '' ? 'selected' : '' }}>下限なし</option>
	                      <option value="100000" {{ request('body_price_min') == '100000' ? 'selected' : '' }}>10万円</option>
	                      <option value="200000" {{ request('body_price_min') == '200000' ? 'selected' : '' }}>20万円</option>
	                      <option value="300000" {{ request('body_price_min') == '300000' ? 'selected' : '' }}>30万円</option>
	                      <option value="400000" {{ request('body_price_min') == '400000' ? 'selected' : '' }}>40万円</option>
	                      <option value="500000" {{ request('body_price_min') == '500000' ? 'selected' : '' }}>50万円</option>
	                      <option value="600000" {{ request('body_price_min') == '600000' ? 'selected' : '' }}    >60万円</option>
	                      <option value="700000" {{ request('body_price_min') == '700000' ? 'selected' : '' }}>70万円</option>
	                      <option value="800000" {{ request('body_price_min') == '800000' ? 'selected' : '' }}>80万円</option>
	                      <option value="900000" {{ request('body_price_min') == '900000' ? 'selected' : '' }}>90万円</option>
	                      <option value="1000000" {{ request('body_price_min') == '1000000' ? 'selected' : '' }}>100万円</option>
	                      <option value="1100000" {{ request('body_price_min') == '1100000' ? 'selected' : '' }}>110万円</option>
	                      <option value="1200000" {{ request('body_price_min') == '1200000' ? 'selected' : '' }}>120万円</option>
	                      <option value="1300000" {{ request('body_price_min') == '1300000' ? 'selected' : '' }}>130万円</option>
	                      <option value="1400000" {{ request('body_price_min') == '1400000' ? 'selected' : '' }}>140万円</option>
	                      <option value="1500000" {{ request('body_price_min') == '1500000' ? 'selected' : '' }}>150万円</option>
	                      <option value="1600000" {{ request('body_price_min') == '1600000' ? 'selected' : '' }}>160万円</option>
	                      <option value="1700000" {{ request('body_price_min') == '1700000' ? 'selected' : '' }}>170万円</option>
	                      <option value="1800000" {{ request('body_price_min') == '1800000' ? 'selected' : '' }}>180万円</option>
	                      <option value="1900000" {{ request('body_price_min') == '1900000' ? 'selected' : '' }}>190万円</option>
	                      <option value="2000000" {{ request('body_price_min') == '2000000' ? 'selected' : '' }}>200万円</option>
	                      <option value="3000000" {{ request('body_price_min') == '3000000' ? 'selected' : '' }}>300万円</option>
	                      <option value="4000000" {{ request('body_price_min') == '4000000' ? 'selected' : '' }}>400万円</option>
	                      <option value="5000000" {{ request('body_price_min') == '5000000' ? 'selected' : '' }}>500万
	                      <option value="8000000" {{ request('body_price_min') == '8000000' ? 'selected' : '' }}>800万円</option>
	                      <option value="9000000" {{ request('body_price_min') == '9000000' ? 'selected' : '' }}>900万円</option>
	                      <option value="10000000" {{ request('body_price_min') == '10000000' ? 'selected' : '' }}>1000万円</option>
	                    </select>
	                  </div>
	                  <span>〜</span>
	                  <div>
	                    <select name="body_price_max" id="body-price-max" class="body-price-max w90">
	                      <option value="" {{ request('year_max') == '' ? 'selected' : '' }}>上限なし</option>
	                      <option value="100000" {{ request('body_price_max') == '100000' ? 'selected' : '' }}>10万円</option>
	                      <option value="200000" {{ request('body_price_max') == '200000' ? 'selected' : '' }}>20万円</option>
	                      <option value="300000" {{ request('body_price_max') == '300000' ? 'selected' : '' }}>30万円</option>
	                      <option value="400000" {{ request('body_price_max') == '400000' ? 'selected' : '' }}>40万円</option>
	                      <option value="500000" {{ request('body_price_max') == '500000' ? 'selected' : '' }}>50万円</option>
	                      <option value="600000" {{ request('body_price_max') == '600000' ? 'selected' : '' }}>60万円</option>
	                      <option value="700000" {{ request('body_price_max') == '700000' ? 'selected' : '' }}>70万円</option>
	                      <option value="800000" {{ request('body_price_max') == '800000' ? 'selected' : '' }}>80万円</option>
	                      <option value="900000" {{ request('body_price_max') == '900000' ? 'selected' : '' }}>90万円</option>
	                      <option value="1000000" {{ request('body_price_max') == '1000000' ? 'selected' : '' }}>100万円</option>
	                      <option value="1100000" {{ request('body_price_max') == '1100000' ? 'selected' : '' }}>110万円</option>
	                      <option value="1200000" {{ request('body_price_max') == '1200000' ? 'selected' : '' }}>120万円</option>
	                      <option value="1300000" {{ request('body_price_max') == '1300000' ? 'selected' : '' }}>130万円</option>
	                      <option value="1400000" {{ request('body_price_max') == '1400000' ? 'selected' : '' }}>140万円</option>
	                      <option value="1500000" {{ request('body_price_max') == '1500000' ? 'selected' : '' }}>150万円</option>
	                      <option value="1600000" {{ request('body_price_max') == '1600000' ? 'selected' : '' }}>160万円</option>
	                      <option value="1700000" {{ request('body_price_max') == '1700000' ? 'selected' : '' }}>170万円</option>
	                      <option value="1800000" {{ request('body_price_max') == '1800000' ? 'selected' : '' }}>180万円</option>
	                      <option value="1900000" {{ request('body_price_max') == '1900000' ? 'selected' : '' }}>190万円</option>
	                      <option value="2000000" {{ request('body_price_max') == '2000000' ? 'selected' : '' }}>200万円</option>
	                      <option value="3000000" {{ request('body_price_max') == '3000000' ? 'selected' : '' }}>300万円</option>
	                      <option value="4000000" {{ request('body_price_max') == '4000000' ? 'selected' : '' }}>400万円</option>
	                      <option value="5000000" {{ request('body_price_max') == '5000000' ? 'selected' : '' }}>500万円</option>
	                      <option value="6000000" {{ request('body_price_max') == '6000000' ? 'selected' : '' }}>600万円</option>
	                      <option value="7000000" {{ request('body_price_max') == '7000000' ? 'selected' : '' }}>700万円</option>
	                      <option value="8000000" {{ request('body_price_max') == '8000000' ? 'selected' : '' }}>800万円</option>
	                      <option value="9000000" {{ request('body_price_max') == '9000000' ? 'selected' : '' }}>900万円</option>
	                      <option value="10000000" {{ request('body_price_max') == '10000000' ? 'selected' : '' }}>1000万円</option>
	                    </select>
	                  </div>
	                </div>
	              </div>

	                <div class="pull-down">
	                  <label class="w4em">支払総額</label>
	                  <div>
	                    <select name=price_min id="price-min" class="price-min w90">
	                      <option value="" {{ request('year_min') == '' ? 'selected' : '' }}>下限なし</option>
	                      <option value="100000" {{ request('price_min') == '100000' ? 'selected' : '' }}>10万円</option>
	                      <option value="200000" {{ request('price_min') == '200000' ? 'selected' : '' }}>20万円</option>
	                      <option value="300000" {{ request('price_min') == '300000' ? 'selected' : '' }}>30万円</option>
	                      <option value="400000" {{ request('price_min') == '400000' ? 'selected' : '' }}>40万円</option>
	                      <option value="500000" {{ request('price_min') == '500000' ? 'selected' : '' }}>50万円</option>
	                      <option value="600000" {{ request('price_min') == '600000' ? 'selected' : '' }}>60万円</option>
	                      <option value="700000" {{ request('price_min') == '700000' ? 'selected' : '' }}>70万円</option>
	                      <option value="800000" {{ request('price_min') == '800000' ? 'selected' : '' }}>80万円</option>
	                      <option value="900000" {{ request('price_min') == '900000' ? 'selected' : '' }}>90万円</option>
	                      <option value="1000000" {{ request('price_min') == '1000000' ? 'selected' : '' }}>100万円</option>
	                      <option value="1100000" {{ request('price_min') == '1100000' ? 'selected' : '' }}>110万円</option>
	                      <option value="1200000" {{ request('price_min') == '1200000' ? 'selected' : '' }}>120万円</option>
	                      <option value="1300000" {{ request('price_min') == '1300000' ? 'selected' : '' }}>130万円</option>
	                      <option value="1400000" {{ request('price_min') == '1400000' ? 'selected' : '' }}>140万円</option>
	                      <option value="1500000" {{ request('price_min') == '1500000' ? 'selected' : '' }}>150万円</option>
	                      <option value="1600000" {{ request('price_min') == '1600000' ? 'selected' : '' }}>160万円</option>
	                      <option value="1700000" {{ request('price_min') == '1700000' ? 'selected' : '' }}>170万円</option>
	                      <option value="1800000" {{ request('price_min') == '1800000' ? 'selected' : '' }}>180万円</option>
	                      <option value="1900000" {{ request('price_min') == '1900000' ? 'selected' : '' }}>190万円</option>
	                      <option value="2000000" {{ request('price_min') == '2000000' ? 'selected' : '' }}>200万円</option>
	                      <option value="3000000" {{ request('price_min') == '3000000' ? 'selected' : '' }}>300万円</option>
	                      <option value="4000000" {{ request('price_min') == '4000000' ? 'selected' : '' }}>400万円</option>
	                      <option value="5000000" {{ request('price_min') == '5000000' ? 'selected' : '' }}>500万円</option>
	                      <option value="6000000" {{ request('price_min') == '6000000' ? 'selected' : '' }}>600万円</option>
	                      <option value="7000000" {{ request('price_min') == '7000000' ? 'selected' : '' }}>700万円</option>
	                      <option value="8000000" {{ request('price_min') == '8000000' ? 'selected' : '' }}>800万円</option>
	                      <option value="9000000" {{ request('price_min') == '9000000' ? 'selected' : '' }}>900万円</option>
	                      <option value="10000000" {{ request('price_min') == '10000000' ? 'selected' : '' }}>1000万円</option>
	                    </select>
	                  </div>
	                  <span>〜</span>
	                  <div>
	                    <select name=price_max id="price-max" class="price-max w90">
	                      <option value="" {{ request('year_max') == '' ? 'selected' : '' }}>上限なし</option>
	                      <option value="100000" {{ request('price_max') == '100000' ? 'selected' : '' }}>10万円</option>
	                      <option value="200000" {{ request('price_max') == '200000' ? 'selected' : '' }}>20万円</option>
	                      <option value="300000" {{ request('price_max') == '300000' ? 'selected' : '' }}>30万円</option>
	                      <option value="400000" {{ request('price_max') == '400000' ? 'selected' : '' }}>40万円</option>
	                      <option value="500000" {{ request('price_max') == '500000' ? 'selected' : '' }}>50万円</option>
	                      <option value="600000" {{ request('price_max') == '600000' ? 'selected' : '' }}>60万円</option>
	                      <option value="700000" {{ request('price_max') == '700000' ? 'selected' : '' }}>70万円</option>
	                      <option value="800000" {{ request('price_max') == '800000' ? 'selected' : '' }}>80万円</option>
	                      <option value="900000" {{ request('price_max') == '900000' ? 'selected' : '' }}>90万円</option>
	                      <option value="1000000" {{ request('price_max') == '1000000' ? 'selected' : '' }}>100万円</option>
	                      <option value="1100000" {{ request('price_max') == '1100000' ? 'selected' : '' }}>110万円</option>
	                      <option value="1200000" {{ request('price_max') == '1200000' ? 'selected' : '' }}>120万円</option>
	                      <option value="1300000" {{ request('price_max') == '1300000' ? 'selected' : '' }}>130万円</option>
	                      <option value="1400000" {{ request('price_min') == '1400000' ? 'selected' : '' }}>140万円</option>
	                      <option value="1500000" {{ request('price_max') == '1500000' ? 'selected' : '' }}>150万円</option>
	                      <option value="1600000" {{ request('price_max') == '1600000' ? 'selected' : '' }}>160万円</option>
	                      <option value="1700000" {{ request('price_max') == '1700000' ? 'selected' : '' }}>170万円</option>
	                      <option value="1800000" {{ request('price_min') == '1800000' ? 'selected' : '' }}>180万円</option>
	                      <option value="1900000" {{ request('price_max') == '1900000' ? 'selected' : '' }}>190万円</option>
	                      <option value="2000000" {{ request('price_max') == '2000000' ? 'selected' : '' }}>200万円</option>
	                      <option value="3000000" {{ request('price_max') == '3000000' ? 'selected' : '' }}>300万円</option>
	                      <option value="4000000" {{ request('price_max') == '4000000' ? 'selected' : '' }}>400万円</option>
	                      <option value="5000000" {{ request('price_max') == '5000000' ? 'selected' : '' }}>500万円</option>
	                      <option value="6000000" {{ request('price_max') == '6000000' ? 'selected' : '' }}>600万円</option>
	                      <option value="7000000" {{ request('price_max') == '7000000' ? 'selected' : '' }}>700万円</option>
	                      <option value="8000000" {{ request('price_max') == '8000000' ? 'selected' : '' }}>800万円</option>
	                      <option value="9000000" {{ request('price_max') == '9000000' ? 'selected' : '' }}>900万円</option>
	                      <option value="10000000" {{ request('price_max') == '10000000' ? 'selected' : '' }}>1000万円</option>
	                    </select>
	                  </div>
	                </div>
	              </div>


	              <div class="column">
	                <div class="checkbox flex-column">
	                  <label>ボディタイプ</label>
	                  <div class="checkbox-group column-2">
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="セダン" type="checkbox" {{ in_array('セダン', request('bodyType', [])) ? 'checked' : '' }}>セダン</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="軽カー" type="checkbox" {{ in_array('軽カー', request('bodyType', [])) ? 'checked' : '' }}>軽カー</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="クーペ" type="checkbox" {{ in_array('クーペ', request('bodyType', [])) ? 'checked' : '' }}>クーペ</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="トラック・バス" type="checkbox" {{ in_array('トラック・バス', request('bodyType', [])) ? 'checked' : '' }}>トラック&バス</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="ステーションワゴン" type="checkbox" {{ in_array('ステーションワゴン', request('bodyType', [])) ? 'checked' : '' }}>ステーションワゴン</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="輸入車" type="checkbox" {{ in_array('輸入車', request('bodyType', [])) ? 'checked' : '' }}>輸入車</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="ミニバン＆1BOX" type="checkbox" {{ in_array('ミニバン＆1BOX', request('bodyType', [])) ? 'checked' : '' }}>ミニバン&1BOX</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="ハイブリッド" type="checkbox" {{ in_array('ハイブリッド', request('bodyType', [])) ? 'checked' : '' }}>ハイブリッド</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="クロカン4WD＆SUV" type="checkbox" {{ in_array('クロカン4WD＆SUV', request('bodyType', [])) ? 'checked' : '' }}>クロカン4WD&SUV</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="福祉車両" type="checkbox" {{ in_array('福祉車両', request('bodyType', [])) ? 'checked' : '' }}>福祉車両</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="コンパクト" type="checkbox" {{ in_array('コンパクト', request('bodyType', [])) ? 'checked' : '' }}>コンパクト</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="その他" type="checkbox" {{ in_array('その他', request('bodyType', [])) ? 'checked' : '' }}>その他</label>
	                  </div>
	                </div>
	              </div>

	              <div class="column">
	                <div class="color-palette">
	                  <label class="w3em">本体色</label>
	                  <div class="palette">


	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="ホワイト系" title="ホワイト" {{ in_array('ホワイト系', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#fff;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="ブラック系" title="ブラック" {{ in_array('ブラック系', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#000;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="レッド" title="レッド" {{ in_array('レッド', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#FA3535;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="ブルー" title="ブルー" {{ in_array('ブルー', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#2FA3DB;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="イエロー" title="イエロー" {{ in_array('イエロー', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#FFE735;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="グリーン" title="グリーン" {{ in_array('グリーン', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#709F62;"></span>
	                    </label>
	                    <label class="color-option">
	                                            <input type="checkbox" name="color[]" value="ブラウン" title="ブラウン" {{ in_array('ブラウン', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#894000;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ベージュ" title="ベージュ" {{ in_array('ベージュ', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#E5C875;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="グレー" title="グレー" {{ in_array('グレー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#ccc;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ライトグレー" title="ライトグレー" {{ in_array('ライトグレー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#E6E6E6;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="オレンジ" title="オレンジ" {{ in_array('オレンジ', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#EE7A14;"></span>
                    </label>

                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="パープル" title="パープル" {{ in_array('パープル', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#725BF0;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ピンク" title="ピンク" {{ in_array('ピンク', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#EBA7DA;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="アイボリー" title="アイボリー" {{ in_array('アイボリー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#EBDDAC;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="オフホワイト" title="オフホワイト" {{ in_array('オフホワイト', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#F8F6EB;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="チャコール" title="チャコール" {{ in_array('チャコール', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#6D6D6D;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ネイビー" title="ネイビー" {{ in_array('ネイビー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#002C9A;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ネイビー" title="ネイビー" {{ in_array('ネイビー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background: linear-gradient(to right,#e60000,#f39800,#fff100,#009944,#0068b7,#1d2088,#920783,#e60000);"></span>
                    </label>

	                  </div>
	                </div>

	              </div>
	            </div>
	          </div>
	        </div>

        <!-- ボタン -->
        <div class="filter-buttons fixed-footer">
          <button type="button" class="btn-clear" onclick="clearAllFilters()">条件をクリア</button>
          <button type="submit" class="btn-submit">
            <span>{{ number_format($vehicleCount) }}</span>台検索する
          </button>
        </div>
    	</form>
      </div>
    </div>
  </div>



  <!-- 検索フォームセクション -->
  <section class="cars__index-search-filter pc">
    <div class="filter-box">

      	<div class="search-title">
        	<h2>中古車一覧</h2>
      	</div>

      	<form method="GET" action="{{ route('cars.index') }}" class="car-search-form">
	        <div class="search-form">
	          <div class="search-basic">
	            <div class="line">
	              <div class="column">
	                <div class="pull-down">
	                  <label class="w4em">メーカー</label>
	                  <div>
	                    <div><select name="maker" id="maker-select" class="maker-select w90">
	                        <option value="" {{ request('color') == '' ? 'selected' : '' }}>選択する</option>
	                        @if(isset($makerList))
	                          @foreach($makerList as $makerName)
	                            <option value="{{ $makerName }}" {{ $maker == $makerName ? 'selected' : '' }}>
	                              {{ $makerName }}
	                            </option>
	                          @endforeach
	                        @endif
	                      </select></div>
	                  </div>
	                </div>
	                <div class="pull-down">
	                  <label class="w4em">車種</label>
	                  <div>
	                    <div><select name="vehicle" id="vehicle-select" class="vehicle-select w90" disabled>
	                        <option>選択する</option>
	                      </select></div>
	                  </div>
	                </div>
	                <div class="pull-down">
	                  <label class="w4em">年式</label>
	                  <div>
	                    <select name="year_min" id="year-min" class="year-min w90">
	                      <option value="" {{ request('year_min') == '' ? 'selected' : '' }}>下限なし</option>
	                      <option value="2026" {{ request('year_min') == '2026' ? 'selected' : '' }}>2026(R08)年</option>
	                      <option value="2025" {{ request('year_min') == '2025' ? 'selected' : '' }}>2025(R07)年</option>
	                      <option value="2024" {{ request('year_min') == '2024' ? 'selected' : '' }}>2024(R06)年</option>
	                      <option value="2023" {{ request('year_min') == '2023' ? 'selected' : '' }}>2023(R05)年</option>
	                      <option value="2022" {{ request('year_min') == '2022' ? 'selected' : '' }}>2022(R04)年</option>
	                      <option value="2021" {{ request('year_min') == '2021' ? 'selected' : '' }}>2021(R03)年</option>
	                      <option value="2020" {{ request('year_min') == '2020' ? 'selected' : '' }}>2020(R02)年</option>
	                      <option value="2019" {{ request('year_min') == '2019' ? 'selected' : '' }}>2019(H31,R01)年</option>
	                      <option value="2018" {{ request('year_min') == '2018' ? 'selected' : '' }}>2018(H30)年</option>
	                      <option value="2017" {{ request('year_min') == '2017' ? 'selected' : '' }}>2017(H29)年</option>
	                      <option value="2016" {{ request('year_min') == '2016' ? 'selected' : '' }}>2016(H28)年</option>
	                      <option value="2015" {{ request('year_min') == '2015' ? 'selected' : '' }}>2015(H27)年</option>
	                      <option value="2014" {{ request('year_min') == '2014' ? 'selected' : '' }}>2014(H26)年</option>
	                      <option value="2013" {{ request('year_min') == '2013' ? 'selected' : '' }}>2013(H25)年</option>
	                      <option value="2012" {{ request('year_min') == '2012' ? 'selected' : '' }}>2012(H24)年</option>
	                      <option value="2011" {{ request('year_min') == '2011' ? 'selected' : '' }}>2011(H23)年</option>
	                      <option value="2010" {{ request('year_min') == '2010' ? 'selected' : '' }}>2010(H22)年</option>
	                      <option value="2009" {{ request('year_min') == '2009' ? 'selected' : '' }}>2009(H21)年</option>
	                      <option value="2008" {{ request('year_min') == '2008' ? 'selected' : '' }}>2008(H20)年</option>
	                      <option value="2007" {{ request('year_min') == '2007' ? 'selected' : '' }}>2007(H19)年</option>
	                      <option value="2006" {{ request('year_min') == '2006' ? 'selected' : '' }}>2006(H18)年</option>
	                      <option value="2005" {{ request('year_min') == '2005' ? 'selected' : '' }}>2005(H17)年</option>
	                      <option value="2004" {{ request('year_min') == '2004' ? 'selected' : '' }}>2004(H16)年</option>
	                      <option value="2003" {{ request('year_min') == '2003' ? 'selected' : '' }}>2003(H15)年</option>
	                      <option value="2002" {{ request('year_min') == '2002' ? 'selected' : '' }}>2002(H14)年</option>
	                      <option value="2001" {{ request('year_min') == '2001' ? 'selected' : '' }}>2001(H13)年</option>
	                      <option value="2000" {{ request('year_min') == '2000' ? 'selected' : '' }}>2000(H12)年</option>
	                      <option value="1999" {{ request('year_min') == '1999' ? 'selected' : '' }}>1999(H11)年</option>
	                      <option value="1998" {{ request('year_min') == '1998' ? 'selected' : '' }}>1998(H10)年</option>
	                      <option value="1997" {{ request('year_min') == '1997' ? 'selected' : '' }}>1997(H09)年</option>
	                      <option value="1996" {{ request('year_min') == '1996' ? 'selected' : '' }}>1996(H08)年</option>
	                      <option value="1995" {{ request('year_min') == '1995' ? 'selected' : '' }}>1995(H07)年</option>
	                      <option value="1994" {{ request('year_min') == '1994' ? 'selected' : '' }}>1994(H06)年</option>
	                      <option value="1993" {{ request('year_min') == '1993' ? 'selected' : '' }}>1993(H05)年</option>
	                      <option value="1992" {{ request('year_min') == '1992' ? 'selected' : '' }}>1992(H04)年</option>
	                      <option value="1991" {{ request('year_min') == '1991' ? 'selected' : '' }}>1991(H03)年</option>
	                      <option value="1990" {{ request('year_min') == '1990' ? 'selected' : '' }}>1990(H02)年</option>
	                      <option value="1989" {{ request('year_min') == '1989' ? 'selected' : '' }}>1989(H01)年</option>
	                    </select>
	                  </div>
	                  <span>〜</span>
	                  <div>
	                    <select name="year_max" id="year-max" class="year-max w90">
	                      <option value="" {{ request('year_max') == '' ? 'selected' : '' }}>上限なし</option>
	                      <option value="2026" {{ request('year_max') == '2026' ? 'selected' : '' }}>2026(R08)年</option>
	                      <option value="2025" {{ request('year_max') == '2025' ? 'selected' : '' }}>2025(R07)年</option>
	                      <option value="2024" {{ request('year_max') == '2024' ? 'selected' : '' }}>2024(R06)年</option>
	                      <option value="2023" {{ request('year_max') == '2023' ? 'selected' : '' }}>2023(R05)年</option>
	                      <option value="2022" {{ request('year_max') == '2022' ? 'selected' : '' }}>2022(R04)年</option>
	                      <option value="2021" {{ request('year_max') == '2021' ? 'selected' : '' }}>2021(R03)年</option>
	                      <option value="2020" {{ request('year_max') == '2020' ? 'selected' : '' }}>2020(R02)年</option>
	                      <option value="2019" {{ request('year_max') == '2019' ? 'selected' : '' }}>2019(H31,R01)年</option>
	                      <option value="2018" {{ request('year_max') == '2018' ? 'selected' : '' }}>2018(H30)年</option>
	                      <option value="2017" {{ request('year_max') == '2017' ? 'selected' : '' }}>2017(H29)年</option>
	                      <option value="2016" {{ request('year_max') == '2016' ? 'selected' : '' }}>2016(H28)年</option>
	                      <option value="2015" {{ request('year_max') == '2015' ? 'selected' : '' }}>2015(H27)年</option>
	                      <option value="2014" {{ request('year_max') == '2014' ? 'selected' : '' }}>2014(H26)年</option>
	                      <option value="2013" {{ request('year_max') == '2013' ? 'selected' : '' }}>2013(H25)年</option>
	                      <option value="2012" {{ request('year_max') == '2012' ? 'selected' : '' }}>2012(H24)年</option>
	                      <option value="2011" {{ request('year_max') == '2011' ? 'selected' : '' }}>2011(H23)年</option>
	                      <option value="2010" {{ request('year_max') == '2010' ? 'selected' : '' }}>2010(H22)年</option>
	                      <option value="2009" {{ request('year_max') == '2009' ? 'selected' : '' }}>2009(H21)年</option>
	                      <option value="2008" {{ request('year_max') == '2008' ? 'selected' : '' }}>2008(H20)年</option>
	                      <option value="2007" {{ request('year_max') == '2007' ? 'selected' : '' }}>2007(H19)年</option>
	                      <option value="2006" {{ request('year_max') == '2006' ? 'selected' : '' }}>2006(H18)年</option>
	                      <option value="2005" {{ request('year_max') == '2005' ? 'selected' : '' }}>2005(H17)年</option>
	                      <option value="2004" {{ request('year_max') == '2004' ? 'selected' : '' }}>2004(H16)年</option>
	                      <option value="2003" {{ request('year_max') == '2003' ? 'selected' : '' }}>2003(H15)年</option>
	                      <option value="2002" {{ request('year_max') == '2002' ? 'selected' : '' }}>2002(H14)年</option>
	                      <option value="2001" {{ request('year_max') == '2001' ? 'selected' : '' }}>2001(H13)年</option>
	                      <option value="2000" {{ request('year_max') == '2000' ? 'selected' : '' }}>2000(H12)年</option>
	                      <option value="1999" {{ request('year_max') == '1999' ? 'selected' : '' }}>1999(H11)年</option>
	                      <option value="1998" {{ request('year_max') == '1998' ? 'selected' : '' }}>1998(H10)年</option>
	                      <option value="1997" {{ request('year_max') == '1997' ? 'selected' : '' }}>1997(H09)年</option>
	                      <option value="1996" {{ request('year_max') == '1996' ? 'selected' : '' }}>1996(H08)年</option>
	                      <option value="1995" {{ request('year_max') == '1995' ? 'selected' : '' }}>1995(H07)年</option>
	                      <option value="1994" {{ request('year_max') == '1994' ? 'selected' : '' }}>1994(H06)年</option>
	                      <option value="1993" {{ request('year_max') == '1993' ? 'selected' : '' }}>1993(H05)年</option>
	                      <option value="1992" {{ request('year_max') == '1992' ? 'selected' : '' }}>1992(H04)年</option>
	                      <option value="1991" {{ request('year_max') == '1991' ? 'selected' : '' }}>1991(H03)年</option>
	                      <option value="1990" {{ request('year_max') == '1990' ? 'selected' : '' }}>1990(H02)年</option>
	                      <option value="1989" {{ request('year_max') == '1989' ? 'selected' : '' }}>1989(H01)年</option>

	                    </select>
	                  </div>
	                </div>
	                <div class="pull-down">
	                  <label class="w4em">走行距離</label>
	                  <div>
	                    <select name="mileage_min" id="mileage-min" class="mileage-min w90">
	                      <option value="" {{ request('year_min') == '' ? 'selected' : '' }}>下限なし</option>
	                      <option value="5000" {{ request('mileage_min') == '5000' ? 'selected' : '' }}>5000Km</option>
	                      <option value="10000" {{ request('mileage_min') == '10000' ? 'selected' : '' }}>1万Km</option>
	                      <option value="20000" {{ request('mileage_min') == '20000' ? 'selected' : '' }}>2万Km</option>
	                      <option value="30000" {{ request('mileage_min') == '30000' ? 'selected' : '' }}>3万Km</option>
	                      <option value="40000" {{ request('mileage_min') == '40000' ? 'selected' : '' }}>4万Km</option>
	                      <option value="50000" {{ request('mileage_min') == '50000' ? 'selected' : '' }}>5万Km</option>
	                      <option value="60000" {{ request('mileage_min') == '60000' ? 'selected' : '' }}>6万Km</option>
	                      <option value="70000" {{ request('mileage_min') == '70000' ? 'selected' : '' }}>7万Km</option>
	                      <option value="80000" {{ request('mileage_min') == '80000' ? 'selected' : '' }}>8万Km</option>
	                      <option value="90000" {{ request('mileage_min') == '90000' ? 'selected' : '' }}>9万Km</option>
	                      <option value="100000" {{ request('mileage_min') == '100000' ? 'selected' : '' }}>10万Km</option>
	                      <option value="110000" {{ request('mileage_min') == '110000' ? 'selected' : '' }}>11万Km</option>
	                      <option value="120000" {{ request('mileage_min') == '120000' ? 'selected' : '' }}>12万Km</option>
	                      <option value="130000" {{ request('mileage_min') == '130000' ? 'selected' : '' }}>13万Km</option>
	                      <option value="140000" {{ request('mileage_min') == '140000' ? 'selected' : '' }}>14万Km</option>
	                      <option value="150000" {{ request('mileage_min') == '150000' ? 'selected' : '' }}>15万Km</option>
	                    </select>
	                  </div>
	                  <span>〜</span>
	                  <div>
	                    <select name="mileage_max" id="mileage-max" class="mileage-max w90">
	                      <option value="" {{ request('year_max') == '' ? 'selected' : '' }}>上限なし</option>
	                      <option value="5000" {{ request('mileage_max') == '5000' ? 'selected' : '' }}>5000Km</option>
	                      <option value="10000" {{ request('mileage_max') == '10000' ? 'selected' : '' }}>1万Km</option>
	                      <option value="20000" {{ request('mileage_max') == '20000' ? 'selected' : '' }}>2万Km</option>
	                      <option value="30000" {{ request('mileage_max') == '30000' ? 'selected' : '' }}>3万Km</option>
	                      <option value="40000" {{ request('mileage_max') == '40000' ? 'selected' : '' }}>4万Km</option>
	                      <option value="50000" {{ request('mileage_max') == '50000' ? 'selected' : '' }}>5万Km</option>
	                      <option value="60000" {{ request('mileage_max') == '60000' ? 'selected' : '' }}>6万Km</option>
	                      <option value="70000" {{ request('mileage_max') == '70000' ? 'selected' : '' }}>7万Km</option>
	                      <option value="80000" {{ request('mileage_max') == '80000' ? 'selected' : '' }}>8万Km</option>
	                      <option value="90000" {{ request('mileage_max') == '90000' ? 'selected' : '' }}>9万Km</option>
	                      <option value="100000" {{ request('mileage_max') == '100000' ? 'selected' : '' }}>10万Km</option>
	                      <option value="110000" {{ request('mileage_max') == '110000' ? 'selected' : '' }}>11万Km</option>
	                      <option value="120000" {{ request('mileage_max') == '120000' ? 'selected' : '' }}>12万Km</option>
	                      <option value="130000" {{ request('mileage_max') == '130000' ? 'selected' : '' }}>13万Km</option>
	                      <option value="140000" {{ request('mileage_max') == '140000' ? 'selected' : '' }}>14万Km</option>
	                      <option value="150000" {{ request('mileage_max') == '150000' ? 'selected' : '' }}>15万Km</option>
	                    </select>
	                  </div>
	                </div>
	                
	                <div class="pull-down">
	                  <label class="w4em">本体価格</label>
	                  <div>
	                    <select name="body_price_min" id="body-price-min-pc" class="body-price-min w90">
	                      <option value="" {{ request('year_min') == '' ? 'selected' : '' }}>下限なし</option>
	                      <option value="100000" {{ request('body_price_min') == '100000' ? 'selected' : '' }}>10万円</option>
	                      <option value="200000" {{ request('body_price_min') == '200000' ? 'selected' : '' }}>20万円</option>
	                      <option value="300000" {{ request('body_price_min') == '300000' ? 'selected' : '' }}>30万円</option>
	                      <option value="400000" {{ request('body_price_min') == '400000' ? 'selected' : '' }}>40万円</option>
	                      <option value="500000" {{ request('body_price_min') == '500000' ? 'selected' : '' }}>50万円</option>
	                      <option value="600000" {{ request('body_price_min') == '600000' ? 'selected' : '' }}>60万円</option>
	                      <option value="700000" {{ request('body_price_min') == '700000' ? 'selected' : '' }}>70万円</option>
	                      <option value="800000" {{ request('body_price_min') == '800000' ? 'selected' : '' }}>80万円</option>
	                      <option value="900000" {{ request('body_price_min') == '900000' ? 'selected' : '' }}>90万円</option>
	                      <option value="1000000" {{ request('body_price_min') == '1000000' ? 'selected' : '' }}>100万円</option>
	                      <option value="1100000" {{ request('body_price_min') == '1100000' ? 'selected' : '' }}>110万円</option>
	                      <option value="1200000" {{ request('body_price_min') == '1200000' ? 'selected' : '' }}>120万円</option>
	                      <option value="1300000" {{ request('body_price_min') == '1300000' ? 'selected' : '' }}>130万円</option>
	                      <option value="1400000" {{ request('body_price_min') == '1400000' ? 'selected' : '' }}>140万円</option>
	                      <option value="1500000" {{ request('body_price_min') == '1500000' ? 'selected' : '' }}>150万円</option>
	                      <option value="1600000" {{ request('body_price_min') == '1600000' ? 'selected' : '' }}>160万円</option>
	                      <option value="1700000" {{ request('body_price_min') == '1700000' ? 'selected' : '' }}>170万円</option>
	                      <option value="1800000" {{ request('body_price_min') == '1800000' ? 'selected' : '' }}>180万円</option>
	                      <option value="1900000" {{ request('body_price_min') == '1900000' ? 'selected' : '' }}>190万円</option>
	                      <option value="2000000" {{ request('body_price_min') == '2000000' ? 'selected' : '' }}>200万円</option>
	                      <option value="3000000" {{ request('body_price_min') == '3000000' ? 'selected' : '' }}>300万円</option>
	                      <option value="4000000" {{ request('body_price_min') == '4000000' ? 'selected' : '' }}>400万円</option>
	                      <option value="5000000" {{ request('body_price_min') == '5000000' ? 'selected' : '' }}>500万円</option>
	                      <option value="6000000" {{ request('body_price_min') == '6000000' ? 'selected' : '' }}>600万円</option>
	                      <option value="7000000" {{ request('body_price_min') == '7000000' ? 'selected' : '' }}>700万円</option>
	                      <option value="8000000" {{ request('body_price_min') == '8000000' ? 'selected' : '' }}>800万円</option>
	                      <option value="9000000" {{ request('body_price_min') == '9000000' ? 'selected' : '' }}>900万円</option>
	                      <option value="10000000" {{ request('body_price_min') == '10000000' ? 'selected' : '' }}>1000万円</option>
	                    </select>
	                  </div>
	                  <span>〜</span>
	                  <div>
	                    <select name="body_price_max" id="body-price-max-pc" class="body-price-max w90">
	                      <option value="" {{ request('body_price_max') == '' ? 'selected' : '' }}>上限なし</option>
	                      <option value="100000" {{ request('body_price_max') == '100000' ? 'selected' : '' }}>10万円</option>
	                      <option value="200000" {{ request('body_price_max') == '200000' ? 'selected' : '' }}>20万円</option>
	                      <option value="300000" {{ request('body_price_max') == '300000' ? 'selected' : '' }}>30万円</option>
	                      <option value="400000" {{ request('body_price_max') == '400000' ? 'selected' : '' }}>40万円</option>
	                      <option value="500000" {{ request('body_price_max') == '500000' ? 'selected' : '' }}>50万円</option>
	                      <option value="600000" {{ request('body_price_max') == '600000' ? 'selected' : '' }}>60万円</option>
	                      <option value="700000" {{ request('body_price_max') == '700000' ? 'selected' : '' }}>70万円</option>
	                      <option value="800000" {{ request('body_price_max') == '800000' ? 'selected' : '' }}>80万円</option>
	                      <option value="900000" {{ request('body_price_max') == '900000' ? 'selected' : '' }}>90万円</option>
	                      <option value="1000000" {{ request('body_price_max') == '1000000' ? 'selected' : '' }}>100万円</option>
	                      <option value="1100000" {{ request('body_price_max') == '1100000' ? 'selected' : '' }}>110万円</option>
	                      <option value="1200000" {{ request('body_price_max') == '1200000' ? 'selected' : '' }}>120万円</option>
	                      <option value="1300000" {{ request('body_price_max') == '1300000' ? 'selected' : '' }}>130万円</option>
	                      <option value="1400000" {{ request('body_price_max') == '1400000' ? 'selected' : '' }}>140万円</option>
	                      <option value="1500000" {{ request('body_price_max') == '1500000' ? 'selected' : '' }}>150万円</option>
	                      <option value="1600000" {{ request('body_price_max') == '1600000' ? 'selected' : '' }}>160万円</option>
	                      <option value="1700000" {{ request('body_price_max') == '1700000' ? 'selected' : '' }}>170万円</option>
	                      <option value="1800000" {{ request('body_price_max') == '1800000' ? 'selected' : '' }}>180万円</option>
	                      <option value="1900000" {{ request('body_price_max') == '1900000' ? 'selected' : '' }}>190万円</option>
	                      <option value="2000000" {{ request('body_price_max') == '2000000' ? 'selected' : '' }}>200万円</option>
	                      <option value="3000000" {{ request('body_price_max') == '3000000' ? 'selected' : '' }}>300万円</option>
	                      <option value="4000000" {{ request('body_price_max') == '4000000' ? 'selected' : '' }}>400万円</option>
	                      <option value="5000000" {{ request('body_price_max') == '5000000' ? 'selected' : '' }}>500万円</option>
	                      <option value="6000000" {{ request('body_price_max') == '6000000' ? 'selected' : '' }}>600万円</option>
	                      <option value="7000000" {{ request('body_price_max') == '7000000' ? 'selected' : '' }}>700万円</option>
	                      <option value="8000000" {{ request('body_price_max') == '8000000' ? 'selected' : '' }}>800万円</option>
	                      <option value="9000000" {{ request('body_price_max') == '9000000' ? 'selected' : '' }}>900万円</option>
	                      <option value="10000000" {{ request('body_price_max') == '10000000' ? 'selected' : '' }}>1000万円</option>
	                    </select>
	                  </div>
	                </div>
	                
	                <div class="pull-down">
	                  <label class="w4em">支払総額</label>
	                  <div>
	                    <select name="price_min" id="price-min" class="price-min w90">
	                      <option value="" {{ request('year_min') == '' ? 'selected' : '' }}>下限なし</option>
	                      <option value="100000" {{ request('price_min') == '100000' ? 'selected' : '' }}>10万円</option>
	                      <option value="200000" {{ request('price_min') == '200000' ? 'selected' : '' }}>20万円</option>
	                      <option value="300000" {{ request('price_min') == '300000' ? 'selected' : '' }}>30万円</option>
	                      <option value="400000" {{ request('price_min') == '400000' ? 'selected' : '' }}>40万円</option>
	                      <option value="500000" {{ request('price_min') == '500000' ? 'selected' : '' }}>50万円</option>
	                      <option value="600000" {{ request('price_min') == '600000' ? 'selected' : '' }}>60万円</option>
	                      <option value="700000" {{ request('price_min') == '700000' ? 'selected' : '' }}>70万円</option>
	                      <option value="800000" {{ request('price_min') == '800000' ? 'selected' : '' }}>80万円</option>
	                      <option value="900000" {{ request('price_min') == '900000' ? 'selected' : '' }}>90万円</option>
	                      <option value="1000000" {{ request('price_min') == '1000000' ? 'selected' : '' }}>100万円</option>
	                      <option value="1100000" {{ request('price_min') == '1100000' ? 'selected' : '' }}>110万円</option>
	                      <option value="1200000" {{ request('price_min') == '1200000' ? 'selected' : '' }}>120万円</option>
	                      <option value="1300000" {{ request('price_min') == '1300000' ? 'selected' : '' }}>130万円</option>
	                      <option value="1400000" {{ request('price_min') == '1400000' ? 'selected' : '' }}>140万円</option>
	                      <option value="1500000" {{ request('price_min') == '1500000' ? 'selected' : '' }}>150万円</option>
	                      <option value="1600000" {{ request('price_min') == '1600000' ? 'selected' : '' }}>160万円</option>
	                      <option value="1700000" {{ request('price_min') == '1700000' ? 'selected' : '' }}>170万円</option>
	                      <option value="1800000" {{ request('price_min') == '1800000' ? 'selected' : '' }}>180万円</option>
	                      <option value="1900000" {{ request('price_min') == '1900000' ? 'selected' : '' }}>190万円</option>
	                      <option value="2000000" {{ request('price_min') == '2000000' ? 'selected' : '' }}>200万円</option>
	                      <option value="3000000" {{ request('price_min') == '3000000' ? 'selected' : '' }}>300万円</option>
	                      <option value="4000000" {{ request('price_min') == '4000000' ? 'selected' : '' }}>400万円</option>
	                      <option value="5000000" {{ request('price_min') == '5000000' ? 'selected' : '' }}>500万円</option>
	                      <option value="6000000" {{ request('price_min') == '6000000' ? 'selected' : '' }}>600万円</option>
	                      <option value="7000000" {{ request('price_min') == '7000000' ? 'selected' : '' }}>700万円</option>
	                      <option value="8000000" {{ request('price_min') == '8000000' ? 'selected' : '' }}>800万円</option>
	                      <option value="9000000" {{ request('price_min') == '9000000' ? 'selected' : '' }}>900万円</option>
	                      <option value="10000000" {{ request('price_min') == '10000000' ? 'selected' : '' }}>1000万円</option>
	                    </select>
	                  </div>
	                  <span>〜</span>
	                  <div>
	                    <select name=price_max id="price-max" class="price-max w90">
	                      <option value="" {{ request('price_max') == '' ? 'selected' : '' }}>上限なし</option>
	                      <option value="100000" {{ request('price_max') == '100000' ? 'selected' : '' }}>10万円</option>
	                      <option value="200000" {{ request('price_max') == '200000' ? 'selected' : '' }}>20万円</option>
	                      <option value="300000" {{ request('price_max') == '300000' ? 'selected' : '' }}>30万円</option>
	                      <option value="400000" {{ request('price_max') == '400000' ? 'selected' : '' }}>40万円</option>
	                      <option value="500000" {{ request('price_max') == '500000' ? 'selected' : '' }}>50万円</option>
	                      <option value="600000" {{ request('price_max') == '600000' ? 'selected' : '' }}>60万円</option>
	                      <option value="700000" {{ request('price_max') == '700000' ? 'selected' : '' }}>70万円</option>
	                      <option value="800000" {{ request('price_max') == '800000' ? 'selected' : '' }}>80万円</option>
	                      <option value="900000" {{ request('price_max') == '900000' ? 'selected' : '' }}>90万円</option>
	                      <option value="1000000" {{ request('price_max') == '1000000' ? 'selected' : '' }}>100万円</option>
	                      <option value="1100000" {{ request('price_max') == '1100000' ? 'selected' : '' }}>110万円</option>
	                      <option value="1200000" {{ request('price_max') == '1200000' ? 'selected' : '' }}>120万円</option>
	                      <option value="1300000" {{ request('price_max') == '1300000' ? 'selected' : '' }}>130万円</option>
	                      <option value="1400000" {{ request('price_max') == '1400000' ? 'selected' : '' }}>140万円</option>
	                      <option value="1500000" {{ request('price_max') == '1500000' ? 'selected' : '' }}>150万円</option>
	                      <option value="1600000" {{ request('price_max') == '1600000' ? 'selected' : '' }}>160万円</option>
	                      <option value="1700000" {{ request('price_max') == '1700000' ? 'selected' : '' }}>170万円</option>
	                      <option value="1800000" {{ request('price_max') == '1800000' ? 'selected' : '' }}>180万円</option>
	                      <option value="1900000" {{ request('price_max') == '1900000' ? 'selected' : '' }}>190万円</option>
	                      <option value="2000000" {{ request('price_max') == '2000000' ? 'selected' : '' }}>200万円</option>
	                      <option value="3000000" {{ request('price_max') == '3000000' ? 'selected' : '' }}>300万円</option>
	                      <option value="4000000" {{ request('price_max') == '4000000' ? 'selected' : '' }}>400万円</option>
	                      <option value="5000000" {{ request('price_max') == '5000000' ? 'selected' : '' }}>500万円</option>
	                      <option value="6000000"{{ request('price_max') == '6000000' ? 'selected' : '' }}>600万円</option>
	                      <option value="7000000"{{ request('price_max') == '7000000' ? 'selected' : '' }}>700万円</option>
	                      <option value="8000000"{{ request('price_max') == '8000000' ? 'selected' : '' }}>800万円</option>
	                      <option value="9000000"{{ request('price_max') == '9000000' ? 'selected' : '' }}>900万円</option>
	                      <option value="10000000"{{ request('price_max') == '10000000' ? 'selected' : '' }}>1000万円</option>
	                    </select>
	                  </div>
	                </div>
	              </div>


	              <div class="column">
	                <div class="checkbox flex-column">
	                  <label>ボディタイプ</label>
	                  <div class="checkbox-group column-2">
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="セダン" type="checkbox" {{ in_array('セダン', request('bodyType', [])) ? 'checked' : '' }}>セダン</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="軽カー" type="checkbox" {{ in_array('軽カー', request('bodyType', [])) ? 'checked' : '' }}>軽カー</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="クーペ" type="checkbox" {{ in_array('クーペ', request('bodyType', [])) ? 'checked' : '' }}>クーペ</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="トラック・バス" type="checkbox" {{ in_array('トラック・バス', request('bodyType', [])) ? 'checked' : '' }}>トラック&バス</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="ステーションワゴン" type="checkbox" {{ in_array('ステーションワゴン', request('bodyType', [])) ? 'checked' : '' }}>ステーションワゴン</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="輸入車" type="checkbox" {{ in_array('輸入車', request('bodyType', [])) ? 'checked' : '' }}>輸入車</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="ミニバン＆1BOX" type="checkbox" {{ in_array('ミニバン＆1BOX', request('bodyType', [])) ? 'checked' : '' }}>ミニバン&1BOX</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="ハイブリッド" type="checkbox" {{ in_array('ハイブリッド', request('bodyType', [])) ? 'checked' : '' }}>ハイブリッド</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="クロカン4WD＆SUV" type="checkbox" {{ in_array('クロカン4WD＆SUV', request('bodyType', [])) ? 'checked' : '' }}>クロカン4WD&SUV</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="福祉車両" type="checkbox" {{ in_array('福祉車両', request('bodyType', [])) ? 'checked' : '' }}>福祉車両</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="コンパクト" type="checkbox" {{ in_array('コンパクト', request('bodyType', [])) ? 'checked' : '' }}>コンパクト</label>
	                    <label class="w9em21" id="checkbox-bodytype"><input name="bodyType[]" value="その他" type="checkbox" {{ in_array('その他', request('bodyType', [])) ? 'checked' : '' }}>その他</label>
	                  </div>
	                </div>
	              </div>

	              <div class="column">
	                <div class="color-palette">
	                  <label class="w3em">本体色</label>
	                  <div class="palette">


	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="ホワイト系" title="ホワイト" {{ in_array('ホワイト系', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#fff;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="ブラック系" title="ブラック" {{ in_array('ブラック系', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#000;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="レッド" title="レッド" {{ in_array('レッド', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#FA3535;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="ブルー" title="ブルー" {{ in_array('ブルー', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#2FA3DB;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="イエロー" title="イエロー" {{ in_array('イエロー', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#FFE735;"></span>
	                    </label>
	                    <label class="color-option">
	                      <input type="checkbox" name="color[]" value="グリーン" title="グリーン" {{ in_array('グリーン', request('color', [])) ? 'checked' : '' }}>
	                      <span class="color-box" style="background:#709F62;"></span>
	                    </label>
	                    <label class="color-option">
	                                            <input type="checkbox" name="color[]" value="ブラウン" title="ブラウン" {{ in_array('ブラウン', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#894000;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ベージュ" title="ベージュ" {{ in_array('ベージュ', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#E5C875;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="グレー" title="グレー" {{ in_array('グレー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#ccc;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ライトグレー" title="ライトグレー" {{ in_array('ライトグレー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#E6E6E6;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="オレンジ" title="オレンジ" {{ in_array('オレンジ', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#EE7A14;"></span>
                    </label>

                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="パープル" title="パープル" {{ in_array('パープル', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#725BF0;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ピンク" title="ピンク" {{ in_array('ピンク', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#EBA7DA;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="アイボリー" title="アイボリー" {{ in_array('アイボリー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#EBDDAC;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="オフホワイト" title="オフホワイト" {{ in_array('オフホワイト', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#F8F6EB;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="チャコール" title="チャコール" {{ in_array('チャコール', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#6D6D6D;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ネイビー" title="ネイビー" {{ in_array('ネイビー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background:#002C9A;"></span>
                    </label>
                    <label class="color-option">
                      <input type="checkbox" name="color[]" value="ネイビー" title="ネイビー" {{ in_array('ネイビー', request('color', [])) ? 'checked' : '' }}>
                      <span class="color-box" style="background: linear-gradient(to right,#e60000,#f39800,#fff100,#009944,#0068b7,#1d2088,#920783,#e60000);"></span>
                    </label>

	                  </div>
	                </div>

	              </div>



	            </div>


	          </div>
	        </div>


	        <!-- ボタン -->
	        <div class="search-button-section">
	          <div class="close-button">

	          </div>
	          <div class="filter-buttons">
	            <button type="button" class="btn-clear" onclick="clearAllFilters()">条件をクリア</button>
	            <button type="submit" class="btn-submit">
	              @if(isset($pagination) && $pagination['total'] > 0)
	              <span>{{ number_format($pagination['total']) }}</span>台検索する
	              @else
	              <span>0</span>台検索する
	              @endif

	            </button>
	          </div>
	        </div>
      	</form>

    </div>
    </div>

    </div>
  </section>

  <!-- 検索フォームセクション -->



  <!-- 一覧セクション： -->
  <section class="cars__index-list-heading">
    <div class="inner_pt1">
      <div class="heading-box">
        <!-- 左側 -->
        <div class="hb-left">

          <span style="font-weight: bold;font-size:1.5rem">{{ number_format($pagination['total'] ?? count($vehicles)) }}台</span>
          @php
            $perPage = request('per_page', 40);
            $currentPage = $pagination['current_page'] ?? 1;
            $total = $pagination['total'] ?? count($vehicles);
            $start = ($currentPage - 1) * $perPage + 1;
            $end = min($currentPage * $perPage, $total);
          @endphp
          <span class="sp-none">{{ $start }}〜{{ $end }}台</span>

          @if(session('fallback_message'))
          <div style="margin-top: 8px; padding: 8px; background-color: #fff3cd; border: 1px solid #ffeeba; border-radius: 4px; font-size: 0.9rem; color: #856404;">
            {{ session('fallback_message') }}
          </div>
          @endif



          <select class="sp-none" id="per-page-select" onchange="changePerPage()">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10台ずつ表示</option>
            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20台ずつ表示</option>
            <option value="40" {{ request('per_page', 40) == 40 ? 'selected' : '' }}>40台ずつ表示</option>
          </select>
        </div>

        <!-- 右側 -->
        <div class="hb-right">
          <x-sort-select />

          <div class="view-switch">
            <button class="view-btn active">
              <div class="grid-icon">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
              </div>
            </button>
            <button class="view-btn"></button>
          </div>

          <!-- ページネーション -->

        </div>
      </div>

    </div>

  </section>

  <!-- カード並び -->
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
  <section class="cars__index-list-cards">
    <div class="inner_pt1">

      <div class="list-cards-cards">
        <div class="list-cards-list">
          @forelse ($vehicles as $vehicle)
          <!-- 遷移先はすべて異なる予定 -->
          <a href="{{ route('cars.detail', ['id' => $vehicle['id']]) }}" class="list-cards-card">
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
            <div class="list-cards-image">
              <!-- NEW标志定位到图片左上角 -->
              {{-- 7天内显示NEW+日期，7天外只显示日期 --}}
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
              <img src="{{ $mainImage ?? asset('assets/img/test.png') }}" alt="">
            </div>
            <div class="list-cards-info">
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
              
              <!-- 录入时间显示 
              @if (!empty($vehicle['created_at']))
              <p class="info-line created-date">
                <span class="label">作成日</span>
                <span class="value">{{ \Carbon\Carbon::parse($vehicle['created_at'])->format('Y/n/j') }}</span>
                <span class="unit"></span>
              </p>
              @endif-->
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
          @endforeach
        </div>
      </div>
      <div class="pagination-all">
        @if ($pagination)
        <div class="pagination">
          @php
          $currentPage = $pagination['current_page'];
          $lastPage = $pagination['last_page'];
          $queryParams = request()->except('page');

          $start = max(1, $currentPage - 3);
          $end = min($lastPage, $currentPage + 3);
          @endphp

          {{-- << Prev --}}
          @if ($currentPage > 1)
          <a href="{{ url()->current() }}?{{ http_build_query(array_merge($queryParams, ['page' => $currentPage - 1])) }}">« prev</a>
          @endif

          {{-- Page numbers --}}
          @if ($start > 1)
          <a href="{{ url()->current() }}?{{ http_build_query(array_merge($queryParams, ['page' => 1])) }}">1</a>
          @if ($start > 2)
          <span>...</span>
          @endif
          @endif

          @for ($i = $start; $i <= $end; $i++)
            @if ($i==$currentPage)
            <span class="current">{{ $i }}</span>
            @else
            <a href="{{ url()->current() }}?{{ http_build_query(array_merge($queryParams, ['page' => $i])) }}">{{ $i }}</a>
            @endif
            @endfor

            @if ($end < $lastPage)
              @if ($end < $lastPage - 1)
              <span>...</span>
              @endif
              <a href="{{ url()->current() }}?{{ http_build_query(array_merge($queryParams, ['page' => $lastPage])) }}">{{ $lastPage }}</a>
              @endif

              {{-- Next >> --}}
              @if ($currentPage < $lastPage)
                <a href="{{ url()->current() }}?{{ http_build_query(array_merge($queryParams, ['page' => $currentPage + 1])) }}">next »</a>
                @endif

        </div>
        @endif
      </div>

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
              <a href="{{ route('cars.maker.models', ['maker' => $makerName]) }}">
                {{ $makerName }}
              </a>
            </li>
            @endforeach
            
          </ul>
        </div>
      </div>
    </div>
  </section>



  <!-- ★CTN車販売について -->
  <section class="about-section">
    <div class="inner_pt2">
      <div class="about__content">
        <div class="about__image">
          <img src="{{ asset('assets/img/test.png') }}" alt="CTN車販売サービスロゴ">
        </div>
        <div class="about__text">
          <h3>CTN中古車検索について</h3>
          <p>CTN中古車検索は、「できるだけ安く、でもちゃんと選びたい」そんな方にぴったりの中古車検索サイトです。トヨタ・日産・ホンダなどの人気国産車から、輸入車まで幅広く掲載中。価格にこだわりながら、安心して選べるクルマをご紹介しています。 SUV、軽自動車、ミニバン、セダンなどボディタイプからの絞り込みも簡単。さらに、自社ローンや、修理・メンテナンスに関する情報も掲載しており、購入後も安心のサポート体制を整えています。 初めてのクルマ選びにも、買い替えにも、お得に探せるCTN中古車検索をぜひご活用ください！
            車の品質にこだわりたい方はプロの鑑定師により鑑定されたグー鑑定車がおすすめです♪
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
  // ページトップボタン
  document.querySelector('.pagetop').addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  // 表示件数変更機能
  function changePerPage() {
    const select = document.getElementById('per-page-select');
    const perPage = select.value;
    
    // 現在のURLパラメータを取得
    const urlParams = new URLSearchParams(window.location.search);
    
    // per_pageパラメータを設定
    urlParams.set('per_page', perPage);
    
    // ページを1に戻す（新しい表示件数での1ページ目を表示）
    urlParams.set('page', '1');
    
    // 新しいURLでリロード
    window.location.search = urlParams.toString();
  }

  // 全ての検索条件をクリアする機能
  function clearAllFilters() {
    // 基本のcars/indexページにリダイレクト（全パラメータをクリア）
    window.location.href = '{{ route("cars.index") }}';
  }
</script>

@endsection