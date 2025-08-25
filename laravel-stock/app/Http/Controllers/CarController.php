<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use App\Models\Vehicle;



use App\Services\VehicleApiService;
use App\Models\Car;



class CarController extends Controller
{

    private function getVehicleData(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 40);
        // $ids = $request->input('ids', []);

        $params = [
            'rowsPerPage' => $perPage,
            'sortBy' => 'created_at',
            'page' => $page,
            'descending' => 1,
            // 'ids' => implode(',', $ids),
        ];

        // 筛选参数传递给API
        $filterParams = [
            'body_price_min' => 'search[price_incl_tax_min]',
            'body_price_max' => 'search[price_incl_tax_max]',
            'price_min' => 'search[price_min]',
            'price_max' => 'search[price_max]',
            'year_min' => 'search[year_min]',
            'year_max' => 'search[year_max]',
            'mileage_min' => 'search[mileage_min]',
            'mileage_max' => 'search[mileage_max]',
            'maker' => 'search[manufacturer_name]',
            'vehicle' => 'search[car_model_name]',
            'bodyType' => 'search[body_shape_type]',
            'color' => 'search[color]',
        ];

        foreach ($filterParams as $requestKey => $apiKey) {
            $value = null;
            
            $value = $request->input($requestKey);
            
            if (!empty($value)) {
                if (is_array($value)) {
                    // 暂时只取第一个值，确保API能正常工作
                    $params[$apiKey] = $value[0];
                } else {
                    $params[$apiKey] = $value;
                }
            }
        }

        $api = new VehicleApiService();
        $response = $api->getVehicleList($params);

        $vehicles = $response['data']['data'] ?? [];

        // $vehiclesById = collect($vehicles)->keyBy('id');

        // $orderedVehicles = collect($ids)->map(function ($id) use ($vehiclesById) {
        //     return $vehiclesById->get($id);
        // })->filter();

        return [
            'vehicles' => $vehicles,
            // 'viewedVehicles' => $orderedVehicles,
        ];
    }


    private function getNewsList()
    {
        $jsonPath = '/home/xs557112/ctn-net.jp/public_html/stock/data/vehicles.json';
        if (!File::exists($jsonPath)) {
            abort(404, 'JSONファイルが見つかりません');
        }
        $vehicles = json_decode(File::get($jsonPath), true);

        $since = Carbon::now()->subDays(180);
        $dateToday = Carbon::now()->format('Y.m.d');
        $newCount = 0;
        $updateCount = 0;

        foreach ($vehicles as $v) {
            if (!empty($v['data_created_at']) && Carbon::parse($v['data_created_at'])->greaterThanOrEqualTo($since)) {
                $newCount++;
            }
            if (!empty($v['data_updated_at']) && Carbon::parse($v['data_updated_at'])->greaterThanOrEqualTo($since)) {
                $updateCount++;
            }
        }

        $newsList = [];
        if ($newCount > 0) {
            $newsList[] = [
                'date' => $dateToday,
                'text' => "新規中古車情報を {$newCount} 件追加！"
            ];
        }
        if ($updateCount > 0) {
            $newsList[] = [
                'date' => $dateToday,
                'text' => "車両情報を {$updateCount} 件更新！"
            ];
        }

        return $newsList;
    }

    public function index(Request $request)
    {
        $vehicleData = $this->getVehicleData($request); // ← ここで受け取る
        $newsList = $this->getNewsList();


        return view('index', [
            'vehicles' => $vehicleData['vehicles'],
            'newsList' => $newsList,
        ]);
    }





    // 車両一覧

    public function cars(Request $request)
    {
        $data = $this->getVehicleData($request);
        $vehicles = collect($data['vehicles']);

        $maker = $request->input('maker');

        // 並び替え処理
        switch ($request->input('sort')) {
            case 'newest':
                $vehicles = $vehicles->sortByDesc('data_created_at')->values();
                break;
            case 'oldest':
                $vehicles = $vehicles->sortBy('data_created_at')->values();
                break;
            case 'total_low':
                $vehicles = $vehicles->sortBy(function ($v) {
                    return $v['total_payment'] ?? PHP_INT_MAX;
                })->values();
                break;
            case 'total_high':
                $vehicles = $vehicles->sortByDesc(function ($v) {
                    return $v['total_payment'] ?? 0;
                })->values();
                break;
            case 'price_low':
                $vehicles = $vehicles->sortBy(function ($v) {
                    return $v['price_incl_tax'] ?? PHP_INT_MAX;
                })->values();
                break;
            case 'price_high':
                $vehicles = $vehicles->sortByDesc(function ($v) {
                    return $v['price_incl_tax'] ?? 0;
                })->values();
                break;
            case 'year_new':
                $vehicles = $vehicles->sortByDesc(function ($v) {
                    return strtotime($v['first_registration_at'] ?? '1970-01-01');
                })->values();
                break;
            case 'year_old':
                $vehicles = $vehicles->sortBy(function ($v) {
                    return strtotime($v['first_registration_at'] ?? '2100-01-01');
                })->values();
                break;
            case 'mileage_low':
                $vehicles = $vehicles->sortBy(function ($v) {
                    return $v['mileage'] ?? PHP_INT_MAX;
                })->values();
                break;
            case 'mileage_high':
                $vehicles = $vehicles->sortByDesc(function ($v) {
                    return $v['mileage'] ?? 0;
                })->values();
                break;
            case 'cc_low':
                $vehicles = $vehicles->sortBy(function ($v) {
                    return $v['engine_displacement'] ?? PHP_INT_MAX;
                })->values();
                break;
            case 'cc_high':
                $vehicles = $vehicles->sortByDesc(function ($v) {
                    return $v['engine_displacement'] ?? 0;
                })->values();
                break;
            default:
                // デフォルト：新着順
                $vehicles = $vehicles->sortByDesc('data_created_at')->values();
                break;
        }

        // ページと件数の取得（GETパラメータから）
        $perPage = (int) $request->input('per_page', 40);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage - 1) * $perPage;

        // ページネーション処理
        $paginatedVehicles = new LengthAwarePaginator(
            $vehicles->slice($offset, $perPage)->values(),
            $vehicles->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $availableBodyTypes = [
            1 => 'セダン',
            2 => 'SUV',
            3 => 'ミニバン',
            4 => '軽自動車',
            5 => 'ステーションワゴン',
            6 => 'スポーツカー',
            7 => 'オープンカー',
        ];

        // 地域一覧
        $areas = [
            [
                'region' => '北海道・東北',
                'region_url' => '/cars/search?region=hokkaido-tohoku',
                'prefs' => [
                    ['name' => '北海道', 'url' => '/cars/search?pref=hokkaido'],
                    ['name' => '青森県', 'url' => '/cars/search?pref=aomori'],
                    ['name' => '岩手県', 'url' => '/cars/search?pref=iwate'],
                    ['name' => '宮城県', 'url' => '/cars/search?pref=miyagi'],
                    ['name' => '秋田県', 'url' => '/cars/search?pref=akita'],
                    ['name' => '山形県', 'url' => '/cars/search?pref=yamagata'],
                    ['name' => '福島県', 'url' => '/cars/search?pref=fukushima'],
                ],
            ],
            [
                'region' => '北陸・甲信越',
                'region_url' => '/cars/search?region=hokuriku-koshinetsu',
                'prefs' => [
                    ['name' => '新潟県', 'url' => '/cars/search?pref=niigata'],
                    ['name' => '富山県', 'url' => '/cars/search?pref=toyama'],
                    ['name' => '石川県', 'url' => '/cars/search?pref=ishikawa'],
                    ['name' => '福井県', 'url' => '/cars/search?pref=fukui'],
                    ['name' => '山梨県', 'url' => '/cars/search?pref=yamanashi'],
                    ['name' => '長野県', 'url' => '/cars/search?pref=nagano'],
                ],
            ],
            [
                'region' => '関東',
                'region_url' => '/cars/search?region=kanto',
                'prefs' => [
                    ['name' => '東京都', 'url' => '/cars/search?pref=tokyo'],
                    ['name' => '埼玉県', 'url' => '/cars/search?pref=saitama'],
                    ['name' => '千葉県', 'url' => '/cars/search?pref=chiba'],
                    ['name' => '神奈川県', 'url' => '/cars/search?pref=kanagawa'],
                    ['name' => '茨城県', 'url' => '/cars/search?pref=ibaraki'],
                    ['name' => '栃木県', 'url' => '/cars/search?pref=tochigi'],
                    ['name' => '群馬県', 'url' => '/cars/search?pref=gunma'],
                ],
            ],
            [
                'region' => '関西',
                'region_url' => '/cars/search?region=kansai',
                'prefs' => [
                    ['name' => '大阪府', 'url' => '/cars/search?pref=osaka'],
                    ['name' => '兵庫県', 'url' => '/cars/search?pref=hyogo'],
                    ['name' => '京都府', 'url' => '/cars/search?pref=kyoto'],
                    ['name' => '滋賀県', 'url' => '/cars/search?pref=shiga'],
                    ['name' => '奈良県', 'url' => '/cars/search?pref=nara'],
                    ['name' => '和歌山県', 'url' => '/cars/search?pref=wakayama'],
                ],
            ],
            [
                'region' => '中国',
                'region_url' => '/cars/search?region=chugoku',
                'prefs' => [
                    ['name' => '鳥取県', 'url' => '/cars/search?pref=tottori'],
                    ['name' => '島根県', 'url' => '/cars/search?pref=shimane'],
                    ['name' => '岡山県', 'url' => '/cars/search?pref=okayama'],
                    ['name' => '広島県', 'url' => '/cars/search?pref=hiroshima'],
                    ['name' => '山口県', 'url' => '/cars/search?pref=yamaguchi'],
                ],
            ],
            [
                'region' => '四国',
                'region_url' => '/cars/search?region=shikoku',
                'prefs' => [
                    ['name' => '徳島県', 'url' => '/cars/search?pref=tokushima'],
                    ['name' => '香川県', 'url' => '/cars/search?pref=kagawa'],
                    ['name' => '愛媛県', 'url' => '/cars/search?pref=ehime'],
                    ['name' => '高知県', 'url' => '/cars/search?pref=kochi'],
                ],
            ],
            [
                'region' => '九州・沖縄',
                'region_url' => '/cars/search?region=kyushu-okinawa',
                'prefs' => [
                    ['name' => '福岡県', 'url' => '/cars/search?pref=fukuoka'],
                    ['name' => '佐賀県', 'url' => '/cars/search?pref=saga'],
                    ['name' => '熊本県', 'url' => '/cars/search?pref=kumamoto'],
                    ['name' => '大分県', 'url' => '/cars/search?pref=oita'],
                    ['name' => '長崎県', 'url' => '/cars/search?pref=nagasaki'],
                    ['name' => '宮崎県', 'url' => '/cars/search?pref=miyazaki'],
                    ['name' => '鹿児島県', 'url' => '/cars/search?pref=kagoshima'],
                    ['name' => '沖縄県', 'url' => '/cars/search?pref=okinawa'],
                ],
            ],
        ];

        return view('cars.index', [
            'latestVehicles' => $paginatedVehicles,
            'vehicles' => $paginatedVehicles,
            'pagination' => [
                'current_page' => $paginatedVehicles->currentPage(),
                'last_page' => $paginatedVehicles->lastPage(),
                'total' => $paginatedVehicles->total(),
            ],
            'retailerMap' => $data['retailerMap'] ?? [],
            'vehicleCount' => $vehicles->count(),
            'maker' => $maker,
            'availableBodyTypes' => $availableBodyTypes,
            'areas' => $areas,
        ]);
    }


    public function search(Request $request)
    {
        $maker = $request->input('maker');
        $bodyTypeKey = $request->input('bodyType');
        $priceRange = $request->input('price');
        $mileageRange = $request->input('mileage');
        $engineRange = $request->input('engine');
        $capacityRange = $request->input('capacity');
        $page = $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 40);

        $api = new VehicleApiService();

        $params = [
            'rowsPerPage' => $perPage,
            // 'sortBy' => 'created_at',
            'page' => $page,
            'descending' => true,
        ];

        if (!empty($maker)) {
            $params['search[name]'] = $maker;
        }

        $response = $api->getVehicleList($params);


        if (!$response['success']) {
            return back()->with('error', 'APIからデータを取得できませんでした');
        }

        $vehicles = $response['data']['data'] ?? [];
        $meta = $response['data']['meta'] ?? null;
        $links = $response['data']['links'] ?? null;

        $vehicles = collect($vehicles)
            ->sortByDesc(function ($item) {
                return $item['created_at'] ?? '1970-01-01 00:00:00';
            })
            ->values()
            ->all();

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
            'その他' => 'その他',
            'セダン' => 'セダン',
        ];

        if (!empty($bodyTypeKey)) {
            $vehicles = array_filter($vehicles, function ($vehicle) use ($bodyTypeKey) {
                return isset($vehicle['body_shape_type']) &&
                    str_contains($vehicle['body_shape_type'], $bodyTypeKey);
            });
        }

        if ($priceRange) {
            [$min, $max] = explode('-', $priceRange);
            $vehicles = array_filter($vehicles, function ($item) use ($min, $max) {
                return $item['total_payment'] >= $min && $item['total_payment'] <= $max;
            });
        }

        if ($mileageRange) {
            [$min, $max] = explode('-', $mileageRange);
            $vehicles = array_filter($vehicles, function ($item) use ($min, $max) {
                return $item['mileage'] >= $min && $item['mileage'] <= $max;
            });
        }

        if ($engineRange) {
            [$min, $max] = explode('-', $engineRange);
            $vehicles = array_filter($vehicles, function ($item) use ($min, $max) {
                return $item['engine_displacement'] >= $min && $item['engine_displacement'] <= $max;
            });
        }

        if ($capacityRange) {
            [$min, $max] = explode('-', $capacityRange);
            $vehicles = array_filter($vehicles, function ($item) use ($min, $max) {
                return $item['passenger_capacity'] >= $min && $item['passenger_capacity'] <= $max;
            });
        }

        $bodyTypeDisplay = $bodyTypeDisplayNames[$bodyTypeKey] ?? $bodyTypeKey;

        return view('cars.search-results', [
            'vehicles' => $vehicles,
            'maker' => $maker,
            'bodyType' => $bodyTypeDisplay,
            'price' => $priceRange,
            'mileage' => $mileageRange,
            'engineRange' => $engineRange,
            'capacityRange' => $capacityRange,
            'pagination' => $meta,
            'paginationLinks' => $links,
        ]);
    }

    // public function detail(string $id, Request $request)
    // {
    //     $api = new VehicleApiService();

    //     try {
    //         $response = $api->getVehicleById($id);
    //         // dd($response);
    //         $vehicle = $response['data'] ?? [];

    //         // retailers.json：店舗一覧マスタを読み込む
    //         // $retailerMapPath = public_path('data/retailers.json');
    //         // $retailerMap = File::exists($retailerMapPath)
    //         //     ? json_decode(File::get($retailerMapPath), true)
    //         //     : [];

    //         return view('cars.detail', [
    //             'vehicle' => $vehicle,
    //             // 'retailerMap' => $retailerMap,
    //         ]);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', '車両情報を取得できませんでした');
    //     }
    // }


    public function detail(string $id, Request $request)
    {
        $api = new VehicleApiService();

        try {
            $response = $api->getVehicleById($id);
            // dd($response);
            // dd($response['data']['data'][10]['images'] ?? []);
            $vehicle = $response['data'] ?? [];

            // APIから販売店情報を取得
            $dealerInfo = $vehicle['dealer'] ?? null;
            $shopName = null;

            // dealerデータから店舗名を取得
            if ($dealerInfo) {
                $shopName = $dealerInfo['name'] ?? null;
            }

            return view('cars.detail', [
                'vehicle' => $vehicle,
                'dealerInfo' => $dealerInfo,
                'shopName' => $shopName,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '車両情報を取得できませんでした');
        }
    }



    public function allMakers(VehicleApiService $vehicleApiService)
    {
        $makersByCountry = [
            '日本' => [
                'レクサス',
                'トヨタ',
                '日産',
                'ホンダ',
                'マツダ',
                'スバル',
                'スズキ',
                '三菱',
                'ダイハツ',
                'いすゞ',
                '光岡自動車',
                'トミーカイラ',
                '日野自動車',
                '三菱ふそう',
            ],
            'ドイツ' => [
                'メルセデス・ベンツ',
                'AMG',
                'マイバッハ',
                'スマート',
                'メルセデスAMG',
                'BMW',
                'BMWアルピナ',
                'アウディ',
                'フォルクスワーゲン',
                'オペル',
                'ポルシェ',
                'ミニ',
                'メルセデス・マイバッハ',
            ],
            'イギリス' => [
                'ロールスロイス',
                'ベントレー',
                'ジャガー',
                'デイムラー',
                'ランドローバー',
                'アストンマーチン',
                'ロータス',
                'マクラーレン',
                'TVR',
                'MG',
                'ローバー',
                'ケータハム',
                'モーガン',
            ],
            'アメリカ' => [
                'キャデラック',
                'シボレー',
                'ビュイック',
                'ポンティアック',
                'サターン',
                'ハマー',
                'GMC',
                'リンカーン',
                'マーキュリー',
                'クライスラー',
                'ダッジ',
                'ジープ',
                'テスラ',
                '米国トヨタ',
                '米国日産',
                '米国ホンダ',
                '米国スバル',
                '米国三菱',
                '米国マツダ',
                '米国いすゞ',
            ],
            'イタリア' => ['アルファロメオ', 'フェラーリ', 'ランボルギーニ', 'マセラティ', 'アバルト'],
            'フランス' => ['プジョー', 'ルノー', 'シトロエン', 'DSオートモビル', 'アルピーヌ'],
            'スウェーデン' => ['ボルボ', 'サーブ'],
            'オランダ' => ['ドンカーブート'],
            '韓国' => ['ヒョンデ'],
            'オーストリア' => ['KTM'],
        ];


        $flatMakerList = collect($makersByCountry)->flatten()->toArray();
        $counts = $vehicleApiService->getVehicleCountsByMakers($flatMakerList);

        return view('cars.makerlist', [
            'makersByCountry' => $makersByCountry,
            'counts' => $counts,
        ]);
    }





    // 車両詳細表示
    // public function detail($id, Request $request)
    // {
    //     $trcd = $request->query('TRCD');
    //     $restid = $request->query('RESTID');

    //     // 詳細データ取得（TODO！）

    //     return view('cars.detail', compact('id', 'trcd', 'restid'));
    // }

    // 問い合わせページ
    public function inquiry(Request $request)
    {
        $stid = $request->query('STID');
        $bkkn = $request->query('BKKN');

        return view('cars.inquiry', compact('stid', 'bkkn'));
    }




    public function makerList(VehicleApiService $vehicleApiService)
    {
        $makersByCountry = [
            '日本' => ['レクサス', 'トヨタ', '日産', 'ホンダ', 'マツダ', 'スバル', 'スズキ', '三菱', 'ダイハツ', 'いすゞ', '光岡自動車', 'トミーカイラ', '日野自動車', '三菱ふそう'],
            'ドイツ' => ['メルセデス・ベンツ', 'AMG', 'マイバッハ', 'スマート', 'メルセデスAMG', 'BMW', 'BMWアルピナ', 'アウディ', 'フォルクスワーゲン', 'オペル', 'ポルシェ', 'ミニ', 'メルセデス・マイバッハ'],
            'イギリス' => ['ロールスロイス', 'ベントレー', 'ジャガー', 'デイムラー', 'ランドローバー', 'アストンマーチン', 'ロータス', 'マクラーレン', 'TVR', 'MG', 'ローバー', 'ケータハム', 'モーガン'],
            'アメリカ' => ['キャデラック', 'シボレー', 'ビュイック', 'ポンティアック', 'サターン', 'ハマー', 'GMC', 'リンカーン', 'マーキュリー', 'クライスラー', 'ダッジ', 'ジープ', 'テスラ', '米国トヨタ', '米国日産', '米国ホンダ', '米国スバル', '米国三菱', '米国マツダ', '米国いすゞ'],
            'イタリア' => ['アルファロメオ', 'フェラーリ', 'ランボルギーニ', 'マセラティ', 'アバルト'],
            'フランス' => ['プジョー', 'ルノー', 'シトロエン', 'DSオートモビル', 'アルピーヌ'],
            'スウェーデン' => ['ボルボ', 'サーブ'],
            'オランダ' => ['ドンカーブート'],
            '韓国' => ['ヒョンデ'],
            'オーストリア' => ['KTM'],
        ];

        // キャッシュファイルからメーカー数量を読み取り
        $counts = $this->getMakerCountsFromCache();

        // メーカーごとのURL事前計算（パフォーマンス最適化）
        $makerUrls = [];
        foreach (collect($makersByCountry)->flatten() as $maker) {
            $count = $counts[$maker] ?? 0;
            $makerUrls[$maker] = [
                'url' => $count > 0 ? route('cars.maker.models', ['maker' => urlencode($maker)]) : 'javascript:void(0)',
                'class' => $count > 0 ? '' : 'disabled',
                'count' => $count
            ];
        }

        return view('cars.makerlist', [
            'makersByCountry' => $makersByCountry,
            'counts' => $counts,
            'makerUrls' => $makerUrls,
        ]);
    }

    /**
     * キャッシュファイルからメーカー数量を読み取り
     */
    private function getMakerCountsFromCache(): array
    {
        $cacheFilePath = storage_path('app/cache/maker_counts.json');
        
        try {
            // ファイルの存在確認
            if (!file_exists($cacheFilePath)) {
                \Log::warning('Maker counts cache file not found', ['path' => $cacheFilePath]);
                return $this->getDefaultMakerCounts();
            }

            // ファイル内容を読み取り
            $jsonContent = file_get_contents($cacheFilePath);
            if ($jsonContent === false) {
                \Log::error('Failed to read maker counts cache file', ['path' => $cacheFilePath]);
                return $this->getDefaultMakerCounts();
            }

            // JSONを解析
            $counts = json_decode($jsonContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Failed to parse maker counts JSON', [
                    'path' => $cacheFilePath,
                    'error' => json_last_error_msg()
                ]);
                return $this->getDefaultMakerCounts();
            }

            \Log::info('Maker counts loaded from cache file successfully', [
                'count' => count($counts),
                'file_size' => filesize($cacheFilePath)
            ]);

            return $counts;

        } catch (\Exception $e) {
            \Log::error('Exception while reading maker counts cache', [
                'error' => $e->getMessage(),
                'path' => $cacheFilePath
            ]);
            return $this->getDefaultMakerCounts();
        }
    }

    /**
     * デフォルトのメーカー数量を取得（キャッシュファイルが利用できない場合）
     */
    private function getDefaultMakerCounts(): array
    {
        return [
            'トヨタ' => 200,
            '日産' => 150,
            'ホンダ' => 130,
            'マツダ' => 80,
            'スバル' => 60,
            '三菱' => 70,
            'スズキ' => 110,
            'ダイハツ' => 90,
            'レクサス' => 80,
            'メルセデス・ベンツ' => 120,
            'BMW' => 100,
            'アウディ' => 85,
        ];
    }

    public function makerModels($maker)
    {
        $makerMap = [
            'toyota' => 'トヨタ',
            'nissan' => '日産',
            'honda' => 'ホンダ',
            'mazda' => 'マツダ',
            'subaru' => 'スバル',
            'mitsubishi' => '三菱',
            'suzuki' => 'スズキ',
            'daihatsu' => 'ダイハツ',
            'isuzu' => 'いすゞ',
        ];

        $makerJa = $makerMap[$maker] ?? $maker;


        $models = [
            ['name' => 'ハイエースバン', 'image' => 'car.png', 'count' => 123],
            ['name' => 'N-BOX', 'image' => 'car.png', 'count' => 123],
            ['name' => 'スペーシア', 'image' => 'car.png', 'count' => 123],
            ['name' => 'ステップワゴン', 'image' => 'car.png', 'count' => 123],
            ['name' => 'タント', 'image' => 'car.png', 'count' => 123],
            ['name' => 'ハスラー', 'image' => 'car.png', 'count' => 123],
            ['name' => 'フィット', 'image' => 'car.png', 'count' => 123],
            ['name' => 'セレナ', 'image' => 'car.png', 'count' => 123],
        ];

        $grouped = [
            '人気車種' => $models,
            '英数' => [],
            'ア行' => [],
            'カ行' => [],
            'サ行' => [],
            'タ行' => [],
            'ナ行' => [],
            'ハ行' => [],
            'マ行' => [],
            'ヤ行' => [],
            'ラ行' => [],
            'ワ行' => [],
        ];

        foreach ($models as $model) {
            $first = mb_substr($model['name'], 0, 1);
            $kana = mb_convert_kana($first, 'KVC');

            if (preg_match('/[a-zA-Z0-9]/u', $kana)) {
                $grouped['英数'][] = $model;
            } elseif (in_array($kana, ['ア', 'イ', 'ウ', 'エ', 'オ'])) {
                $grouped['ア行'][] = $model;
            } elseif (in_array($kana, ['カ', 'キ', 'ク', 'ケ', 'コ'])) {
                $grouped['カ行'][] = $model;
            } elseif (in_array($kana, ['サ', 'シ', 'ス', 'セ', 'ソ'])) {
                $grouped['サ行'][] = $model;
            } elseif (in_array($kana, ['タ', 'チ', 'ツ', 'テ', 'ト'])) {
                $grouped['タ行'][] = $model;
            } elseif (in_array($kana, ['ナ', 'ニ', 'ヌ', 'ネ', 'ノ'])) {
                $grouped['ナ行'][] = $model;
            } elseif (in_array($kana, ['ハ', 'ヒ', 'フ', 'ヘ', 'ホ'])) {
                $grouped['ハ行'][] = $model;
            } elseif (in_array($kana, ['マ', 'ミ', 'ム', 'メ', 'モ'])) {
                $grouped['マ行'][] = $model;
            } elseif (in_array($kana, ['ヤ', 'ユ', 'ヨ'])) {
                $grouped['ヤ行'][] = $model;
            } elseif (in_array($kana, ['ラ', 'リ', 'ル', 'レ', 'ロ'])) {
                $grouped['ラ行'][] = $model;
            } elseif (in_array($kana, ['ワ', 'ヲ', 'ン'])) {
                $grouped['ワ行'][] = $model;
            }
        }

        $availableBodyTypes = [
            1 => 'セダン',
            2 => 'SUV',
            3 => 'ミニバン',
            4 => '軽自動車',
            5 => 'ステーションワゴン',
            6 => 'スポーツカー',
            7 => 'オープンカー',
        ];

        // 地域一覧
        $areas = [
            [
                'region' => '北海道・東北',
                'region_url' => '/cars/search?region=hokkaido-tohoku',
                'prefs' => [
                    ['name' => '北海道', 'url' => '/cars/search?pref=hokkaido'],
                    ['name' => '青森県', 'url' => '/cars/search?pref=aomori'],
                    ['name' => '岩手県', 'url' => '/cars/search?pref=iwate'],
                    ['name' => '宮城県', 'url' => '/cars/search?pref=miyagi'],
                    ['name' => '秋田県', 'url' => '/cars/search?pref=akita'],
                    ['name' => '山形県', 'url' => '/cars/search?pref=yamagata'],
                    ['name' => '福島県', 'url' => '/cars/search?pref=fukushima'],
                ],
            ],
            [
                'region' => '北陸・甲信越',
                'region_url' => '/cars/search?region=hokuriku-koshinetsu',
                'prefs' => [
                    ['name' => '新潟県', 'url' => '/cars/search?pref=niigata'],
                    ['name' => '富山県', 'url' => '/cars/search?pref=toyama'],
                    ['name' => '石川県', 'url' => '/cars/search?pref=ishikawa'],
                    ['name' => '福井県', 'url' => '/cars/search?pref=fukui'],
                    ['name' => '山梨県', 'url' => '/cars/search?pref=yamanashi'],
                    ['name' => '長野県', 'url' => '/cars/search?pref=nagano'],
                ],
            ],
            [
                'region' => '関東',
                'region_url' => '/cars/search?region=kanto',
                'prefs' => [
                    ['name' => '東京都', 'url' => '/cars/search?pref=tokyo'],
                    ['name' => '埼玉県', 'url' => '/cars/search?pref=saitama'],
                    ['name' => '千葉県', 'url' => '/cars/search?pref=chiba'],
                    ['name' => '神奈川県', 'url' => '/cars/search?pref=kanagawa'],
                    ['name' => '茨城県', 'url' => '/cars/search?pref=ibaraki'],
                    ['name' => '栃木県', 'url' => '/cars/search?pref=tochigi'],
                    ['name' => '群馬県', 'url' => '/cars/search?pref=gunma'],
                ],
            ],
            [
                'region' => '関西',
                'region_url' => '/cars/search?region=kansai',
                'prefs' => [
                    ['name' => '大阪府', 'url' => '/cars/search?pref=osaka'],
                    ['name' => '兵庫県', 'url' => '/cars/search?pref=hyogo'],
                    ['name' => '京都府', 'url' => '/cars/search?pref=kyoto'],
                    ['name' => '滋賀県', 'url' => '/cars/search?pref=shiga'],
                    ['name' => '奈良県', 'url' => '/cars/search?pref=nara'],
                    ['name' => '和歌山県', 'url' => '/cars/search?pref=wakayama'],
                ],
            ],
            [
                'region' => '中国',
                'region_url' => '/cars/search?region=chugoku',
                'prefs' => [
                    ['name' => '鳥取県', 'url' => '/cars/search?pref=tottori'],
                    ['name' => '島根県', 'url' => '/cars/search?pref=shimane'],
                    ['name' => '岡山県', 'url' => '/cars/search?pref=okayama'],
                    ['name' => '広島県', 'url' => '/cars/search?pref=hiroshima'],
                    ['name' => '山口県', 'url' => '/cars/search?pref=yamaguchi'],
                ],
            ],
            [
                'region' => '四国',
                'region_url' => '/cars/search?region=shikoku',
                'prefs' => [
                    ['name' => '徳島県', 'url' => '/cars/search?pref=tokushima'],
                    ['name' => '香川県', 'url' => '/cars/search?pref=kagawa'],
                    ['name' => '愛媛県', 'url' => '/cars/search?pref=ehime'],
                    ['name' => '高知県', 'url' => '/cars/search?pref=kochi'],
                ],
            ],
            [
                'region' => '九州・沖縄',
                'region_url' => '/cars/search?region=kyushu-okinawa',
                'prefs' => [
                    ['name' => '福岡県', 'url' => '/cars/search?pref=fukuoka'],
                    ['name' => '佐賀県', 'url' => '/cars/search?pref=saga'],
                    ['name' => '熊本県', 'url' => '/cars/search?pref=kumamoto'],
                    ['name' => '大分県', 'url' => '/cars/search?pref=oita'],
                    ['name' => '長崎県', 'url' => '/cars/search?pref=nagasaki'],
                    ['name' => '宮崎県', 'url' => '/cars/search?pref=miyazaki'],
                    ['name' => '鹿児島県', 'url' => '/cars/search?pref=kagoshima'],
                    ['name' => '沖縄県', 'url' => '/cars/search?pref=okinawa'],
                ],
            ],
        ];

        return view('cars.maker.models', [
            'maker' => $makerJa,
            'groupedModels' => $grouped,
            'availableBodyTypes' => $availableBodyTypes,
            'areas' => $areas,
        ]);
    }

    public function makerModelArea($maker, $model)
    {
        return view('cars.maker.area', compact('maker', 'model'));
    }

    public function areaStockList($maker, $model, $prefecture)
    {
        return view('cars.area.stock', compact('maker', 'model', 'prefecture'));
    }
}
