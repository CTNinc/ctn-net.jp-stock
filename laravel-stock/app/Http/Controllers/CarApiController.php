<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


use App\Services\VehicleApiService;


class CarApiController extends Controller
{
    public function carsmekas(Request $request, $maker)
    {
        $page = $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 40);

        $params = [
            'rowsPerPage' => $perPage,
            'page' => $page,
            'descending' => true,
            'search[name]' => $maker,
        ];

        $api = new VehicleApiService();
        $response = $api->getVehicleList($params);

        $vehicles = $response['data']['data'] ?? [];
        $meta = $response['data']['meta'] ?? null;

        return view('cars.maker.models', [
            'vehicles' => $vehicles,
            'maker' => $maker,
            'pagination' => $meta,
        ]);
    }

    public function filter(Request $request)
    {

        // 1. リクエストパラメータを取得する
        $perPage = (int) $request->input('per_page', 40);
        $params = [
            'rowsPerPage' => $perPage,
            'page' => $request->input('page', 1),
            'sortBy' => 'created_at',
            'descending' => true
        ];

        // 2. APIクエリパラメータを構築する   
        $searchParams = [
            'bodyType' => 'search[body_shape_type]',
            'body_price_min' => 'search[price_incl_tax_min]',
            'body_price_max' => 'search[price_incl_tax_max]',
            'price_min' => 'search[price_min]',
            'price_max' => 'search[price_max]',
            'mileage_min' => 'search[mileage_min]',
            'mileage_max' => 'search[mileage_max]',
            'passenger_capacity_min' => 'search[passenger_capacity_min]',
            'passenger_capacity_max' => 'search[passenger_capacity_max]',
            'engine_displacement_min' => 'search[engine_displacement_min]',
            'engine_displacement_max' => 'search[engine_displacement_max]',
            'year_min' => 'search[year_min]',
            'year_max' => 'search[year_max]',
            'color' => 'search[color]',
            'maker' => 'search[manufacturer_name]',
            'vehicle' => 'search[car_model_name]'
        ];

        foreach ($searchParams as $requestKey => $apiKey) {
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

        // メーカー名と車種名の処理
        $maker = $request->input('maker');
        $vehicle = $request->input('vehicle');
        $models = $request->input('models', []);
        
        // models[]パラメータの処理
        if (!empty($models) && is_array($models)) {
            $model = reset($models); // 最初の車型を取得
            if (!empty($model) && $model !== '選択する') {
                $params['search[car_model_name]'] = $model;
            }
        }

        // 並び順を処理
        $sort = $request->input('sort', 'year_new_created');
        $sortMapping = [
            'year_new_created' => ['sortBy' => 'first_registration_at', 'descending' => true], // 年式新しい順＋入庫日期順（デフォルト）
            'newest' => ['sortBy' => 'created_at', 'descending' => true],
            'oldest' => ['sortBy' => 'created_at', 'descending' => false],
            'total_low' => ['sortBy' => 'price_incl_tax', 'descending' => false],
            'total_high' => ['sortBy' => 'price_incl_tax', 'descending' => true],
            'year_new' => ['sortBy' => 'first_registration_at', 'descending' => true],
            'year_old' => ['sortBy' => 'first_registration_at', 'descending' => false],
            'mileage_low' => ['sortBy' => 'mileage', 'descending' => false],
            'mileage_high' => ['sortBy' => 'mileage', 'descending' => true],
            'cc_low' => ['sortBy' => 'engine_displacement', 'descending' => false],
            'cc_high' => ['sortBy' => 'engine_displacement', 'descending' => true],
        ];

        // 排気量排序はAPIでサポートされていないため、ローカルで処理
        // 乗車定員検索もAPIでサポートされていない可能性があるため、ローカルで処理
        $hasPassengerCapacitySearch = false;
        $passengerCapacityMin = $request->input('passenger_capacity_min');
        $passengerCapacityMax = $request->input('passenger_capacity_max');
        
        if (!empty($passengerCapacityMin) || !empty($passengerCapacityMax)) {
            $hasPassengerCapacitySearch = true;
            // API支持passenger_capacity筛选，保留参数让API处理
            // 这样可以在API层面进行筛选，获得正确的分页信息
        }
        
        if (isset($sortMapping[$sort]) && !in_array($sort, ['cc_low', 'cc_high'])) {
            $params = array_merge($params, $sortMapping[$sort]);
        }

        // デバッグ：APIパラメータをログに出力
        \Log::info('CarApiController API Parameters', [
            'request_url' => $request->fullUrl(),
            'request_params' => $request->all(),
            'api_params' => $params
        ]);

        // 3. APIを呼び出す
        $api = new VehicleApiService();
        $response = $api->getVehicleList($params);

        // 4. 結果を取得
        $vehicles = $response['data']['data'] ?? [];
        $meta = $response['data']['meta'] ?? null;
        $vehicleCount = $meta['total'] ?? count($vehicles);

        // デバッグ：API返回的数据结构
        if (!empty($vehicles)) {
            $sampleVehicle = $vehicles[0];
            \Log::info('API Response Sample Vehicle', [
                'vehicle_keys' => array_keys($sampleVehicle),
                'sample_vehicle' => $sampleVehicle
            ]);
        }

        // 5. API已经处理了乘客容量筛选，不需要本地筛选
        // 分页信息由API返回，保持原样

        // 6. ローカル排序処理
        if ($sort === 'year_new_created') {
            // デフォルト排序：年式＋入庫日
            $vehicles = collect($vehicles)->sortBy([
                function ($vehicle) {
                    return strtotime($vehicle['first_registration_at'] ?? '1970-01-01');
                },
                function ($vehicle) {
                    return strtotime($vehicle['created_at'] ?? '1970-01-01');
                }
            ])->reverse()->values()->all();
        } elseif ($sort === 'cc_low') {
            // 排気量：少ない順
            $vehicles = collect($vehicles)->sortBy(function ($vehicle) {
                return $vehicle['engine_displacement'] ?? PHP_INT_MAX;
            })->values()->all();
        } elseif ($sort === 'cc_high') {
            // 排気量：多い順
            $vehicles = collect($vehicles)->sortByDesc(function ($vehicle) {
                return $vehicle['engine_displacement'] ?? 0;
            })->values()->all();
        }

        // 6. メーカー名のリストを準備
        $makerList = [
            'トヨタ', 'ホンダ', '日産', 'マツダ', 'スバル', 'スズキ', '三菱', 'ダイハツ', 'レクサス',
            'メルセデス・ベンツ', 'BMW', 'アウディ', 'フォルクスワーゲン', 'ポルシェ', 'テスラ',
            'フォード', 'シボレー', 'ジープ', 'フェラーリ', 'ランボルギーニ', 'マセラティ',
            'ジャガー', 'ランドローバー', 'ミニ', 'ルノー', 'プジョー', 'シトロエン'
        ];



        // 7. ビューを返す
        return view('cars.index', [
            'latestVehicles' => $vehicles,
            'vehicles' => $vehicles,
            'vehicleCount' => $vehicleCount,
            'pagination' => $meta,
            'makerList' => $makerList,
            // ユーザーが入力したフィルター条件を保持
            'maker' => $maker,
            'vehicle' => $vehicle,
            'selectedModels' => $models, // 選択された車種名を保持
            'bodytype' => $request->input('bodyType'),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'mileage_min' => $request->input('mileage_min'),
            'mileage_max' => $request->input('mileage_max'),
            'passenger_capacity_min' => $request->input('passenger_capacity_min'),
            'passenger_capacity_max' => $request->input('passenger_capacity_max'),
            'engine_displacement_min' => $request->input('engine_displacement_min'),
            'engine_displacement_max' => $request->input('engine_displacement_max'),
            'year_min' => $request->input('year_min'),
            'year_max' => $request->input('year_max'),
            'color' => $request->input('color'),
            'sort' => $sort,
            'availableBodyTypes' => [], // 空の配列を追加
            'areas' => [], // 空の配列を追加
            'retailerMap' => [] // 空の配列を追加
        ]);
    }


    /**
     * メーカー名の変換マッピングを取得
     */
    private function getMakerMapping(): array
    {
        return [
            '日産' => 'ニッサン',  // 日産は片假名のニッサンで検索
            '三菱' => 'ミツビシ',  // 三菱は片假名のミツビシで検索
            'トヨタ' => 'トヨタ',
            'ホンダ' => 'ホンダ',
            'マツダ' => 'マツダ',
            'スバル' => 'スバル',
            'スズキ' => 'スズキ',
            'ダイハツ' => 'ダイハツ',
            'レクサス' => 'レクサス',
            'メルセデス・ベンツ' => 'メルセデス・ベンツ',
            'BMW' => 'BMW',
            'アウディ' => 'アウディ',
            'フォルクスワーゲン' => 'フォルクスワーゲン',
            'ポルシェ' => 'ポルシェ',
            'テスラ' => 'テスラ',
            'フォード' => 'フォード',
            'シボレー' => 'シボレー',
            'ジープ' => 'ジープ',
            'フェラーリ' => 'フェラーリ',
            'ランボルギーニ' => 'ランボルギーニ',
            'マセラティ' => 'マセラティ',
            'ジャガー' => 'ジャガー',
            'ランドローバー' => 'ランドローバー',
            'ミニ' => 'ミニ',
            'ルノー' => 'ルノー',
            'プジョー' => 'プジョー',
            'シトロエン' => 'シトロエン'
        ];
    }

    private function getRegistrationYear(array $car): ?int
    {
        if (empty($car['first_registration_at'])) {
            return null;
        }
        
        try {
            return (int) substr($car['first_registration_at'], 0, 4); 
        } catch (\Exception $e) {
            logger()->error('Registration year parsing error', [
                'car_id' => $car['id'] ?? null,
                'date' => $car['first_registration_at'],
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }



    public function makerModels($makerId)
    {
        // 英文别名を日文制造商名に変換
        $japaneseMakerName = \App\Helpers\MakerHelper::toJapanese($makerId);
        
        // まずキャッシュファイルから読み取りを試行
        $models = $this->getMakerModelsFromCache($japaneseMakerName);
        
        // キャッシュが利用できない場合は従来の方法を使用
        if (empty($models) || (count($models) === 1 && strpos($models[0]['name'], 'データ読み込み中') !== false)) {
            $models = $this->getMakerModelsFromLegacyMethod($japaneseMakerName);
        }

        return view('cars.maker.models', [
            'makerId' => $japaneseMakerName,
            'models' => $models,
        ]);
    }



    /**
     * キャッシュファイルから車型データを読み取り（優先方法）
     */
    private function getMakerModelsFromCache($makerId): array
    {
        $cacheFilePath = storage_path('app/cache/maker_models.json');
        
        try {
            // ファイルの存在確認
            if (!file_exists($cacheFilePath)) {
                \Log::warning('Maker models cache file not found', ['path' => $cacheFilePath]);
                return $this->getDefaultMakerModels($makerId);
            }

            // ファイル内容を読み取り
            $jsonContent = file_get_contents($cacheFilePath);
            if ($jsonContent === false) {
                \Log::error('Failed to read maker models cache file', ['path' => $cacheFilePath]);
                return $this->getDefaultMakerModels($makerId);
            }

            // JSONを解析
            $allModels = json_decode($jsonContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Failed to parse maker models JSON', [
                    'path' => $cacheFilePath,
                    'error' => json_last_error_msg()
                ]);
                return $this->getDefaultMakerModels($makerId);
            }

            // 指定されたメーカーのデータを取得
            if (!isset($allModels[$makerId])) {
                \Log::warning('Maker not found in cache', ['maker' => $makerId]);
                return $this->getDefaultMakerModels($makerId);
            }

            \Log::info('Maker models loaded from cache file successfully', [
                'maker' => $makerId,
                'count' => count($allModels[$makerId]),
                'file_size' => filesize($cacheFilePath)
            ]);

            return $allModels[$makerId];

        } catch (\Exception $e) {
            \Log::error('Exception while reading maker models cache', [
                'error' => $e->getMessage(),
                'maker' => $makerId,
                'path' => $cacheFilePath
            ]);
            return $this->getDefaultMakerModels($makerId);
        }
    }

    /**
     * 従来の方法で車型データを取得（フォールバック）
     */
    private function getMakerModelsFromLegacyMethod($makerId): array
    {
        if (!Storage::disk('local')->exists('cached_models.json')) {
            \Log::error('Legacy cached_models.json not found');
            return $this->getDefaultMakerModels($makerId);
        }

        $json = Storage::disk('local')->get('cached_models.json');
        $data = json_decode($json, true);

        if (!isset($data[$makerId])) {
            \Log::warning('Maker not found in legacy cache', ['maker' => $makerId]);
            return $this->getDefaultMakerModels($makerId);
        }

        $modelNames = array_keys($data[$makerId]);
        $vehicleApiService = app(\App\Services\VehicleApiService::class);
        $models = [];

        foreach ($modelNames as $modelName) {
            try {
                $imageUrl = asset('assets/img/car-image/no-image.webp'); // デフォルト画像
                $count = 0;

                // 検索候補（順に試す）
                $keywords = [
                    $makerId . ' ' . $modelName,   // 半角スペース
                    $makerId . '　' . $modelName,  // 全角スペース
                    $modelName                     // 車種名だけ
                ];

                foreach ($keywords as $keyword) {
                    $response = $vehicleApiService->getVehicleList([
                        'search[name]' => $keyword,
                        'rowsPerPage' => 1,
                    ]);

                    $vehicles = $response['data']['data'] ?? [];
                    $meta = $response['data']['meta'] ?? [];

                    if (!empty($vehicles)) {
                        if (!empty($vehicles[0]['images'][0]['image_url'])) {
                            $imageUrl = $vehicles[0]['images'][0]['image_url'];
                        }
                        $count = $meta['total'] ?? count($vehicles);
                        break; // 最初にヒットした時点で抜ける
                    }
                }

                $models[] = [
                    'name' => $modelName,
                    'image' => $imageUrl,
                    'count' => $count,
                ];
            } catch (\Exception $e) {
                logger()->error("【ERROR: {$makerId} {$modelName}】画像取得失敗: " . $e->getMessage());

                $models[] = [
                    'name' => $modelName,
                    'image' => asset('assets/img/car-image/no-image.webp'),
                    'count' => 0,
                ];
            }
        }

        \Log::info('Maker models loaded from legacy method', [
            'maker' => $makerId,
            'count' => count($models)
        ]);

        return $models;
    }

    /**
     * デフォルトの車型データを取得（キャッシュファイルが利用できない場合）
     */
    private function getDefaultMakerModels($makerId): array
    {
        return [
            [
                'name' => $makerId . ' データ読み込み中...',
                'image' => asset('assets/img/car-image/no-image.webp'),
                'count' => 0,
            ]
        ];
    }
}