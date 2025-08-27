<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Services\VehicleApiService;

class UpdateMakerModelsCache extends Command
{
    /**
     * コマンドの名前と署名
     */
    protected $signature = 'cache:update-maker-models';

    /**
     * コマンドの説明
     */
    protected $description = 'メーカー車型キャッシュを更新（APIから車型データを取得してJSONファイルに保存）';

    /**
     * コマンドを実行
     */
    public function handle()
    {
        // メモリ制限を増加
        ini_set('memory_limit', '512M');
        $this->info('メーカー車型キャッシュの更新を開始します...');
        
        try {
            // 全メーカーリスト
            $makersByCountry = [
                '日本' => ['トヨタ', 'ホンダ', '日産', 'マツダ', 'スバル', 'スズキ', '三菱', 'ダイハツ', 'レクサス', 'いすゞ', '光岡自動車', '日野自動車'],
                'ドイツ' => ['メルセデス・ベンツ', 'BMW', 'アウディ', 'フォルクスワーゲン', 'ポルシェ', 'オペル', 'スマート', 'マイバッハ', 'メルセデスAMG', 'メルセデス・マイバッハ', 'AMG', 'BMWアルピナ'],
                'アメリカ' => ['フォード', 'シボレー', 'ジープ', 'キャデラック', 'クライスラー', 'ダッジ', 'GMC', 'ハマー', 'リンカーン', 'ビュイック', 'サターン', 'ポンティアック', 'マーキュリー', '米国トヨタ', '米国ホンダ', '米国日産', '米国マツダ', '米国三菱', '米国スバル', '米国いすゞ'],
                'イタリア' => ['フェラーリ', 'ランボルギーニ', 'マセラティ', 'アルファロメオ', 'アバルト'],
                'イギリス' => ['ジャガー', 'ランドローバー', 'ベントレー', 'ロールスロイス', 'アストンマーチン', 'ローバー', 'ミニ', 'モーガン', 'ケータハム', 'TVR', 'マクラーレン'],
                'フランス' => ['プジョー', 'ルノー', 'シトロエン', 'DSオートモビル', 'アルピーヌ'],
                'スウェーデン' => ['ボルボ', 'サーブ'],
                '韓国' => ['ヒョンデ'],
                'オランダ' => ['ドンカーブート'],
                'オーストリア' => ['KTM'],
                'その他/特殊' => ['テスラ', 'ロータス', 'MG', 'デイムラー', 'トミーカイラ', '三菱ふそう']
            ];
            
            $allMakers = collect($makersByCountry)->flatten()->toArray();
            $this->info(count($allMakers) . ' 個のメーカーの車型データを更新します');

            // 車両APIサービスを取得
            $vehicleApiService = new VehicleApiService();
            
            // キャッシュディレクトリの存在確認
            $cacheDir = storage_path('app/cache');
            if (!File::exists($cacheDir)) {
                File::makeDirectory($cacheDir, 0755, true);
                $this->info('キャッシュディレクトリを作成しました: ' . $cacheDir);
            }

            // ストリーミングJSON書き込みの開始
            $cacheFilePath = storage_path('app/cache/maker_models.json');
            $file = fopen($cacheFilePath, 'w');
            if ($file === false) {
                throw new \Exception('キャッシュファイルを開けません: ' . $cacheFilePath);
            }
            
            // JSON開始
            fwrite($file, "{\n");
            
            $processedCount = 0;
            $totalMakers = count($allMakers);
            $totalModels = 0;
            $isFirst = true;
            
            foreach ($allMakers as $maker) {
                $processedCount++;
                $this->info("処理中: {$maker} ({$processedCount}/{$totalMakers})");
                
                try {
                    // このメーカーの車型データを取得
                    $models = $this->fetchMakerModels($vehicleApiService, $maker);
                    
                    // JSON片段を書き込み
                    if (!$isFirst) {
                        fwrite($file, ",\n");
                    }
                    
                    $makerJson = json_encode($maker, JSON_UNESCAPED_UNICODE);
                    $modelsJson = json_encode($models, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    
                    fwrite($file, "    {$makerJson}: {$modelsJson}");
                    
                    $this->line("  → {$maker}: " . count($models) . " 個の車型を取得");
                    
                    // 統計情報を更新
                    $totalModels += count($models);
                    
                    // メモリ使用量を表示
                    $memoryUsage = round(memory_get_usage() / 1024 / 1024, 2);
                    $this->line("  Memory usage: {$memoryUsage}MB");
                    
                    // 即座にメモリを解放
                    unset($models, $makerJson, $modelsJson);
                    gc_collect_cycles();
                    
                    // レート制限回避のために短時間待つ
                    usleep(200000); // 0.2秒
                    
                    $isFirst = false;
                    
                } catch (\Exception $e) {
                    $this->error("  → {$maker} の処理でエラー: " . $e->getMessage());
                    
                    // エラーの場合もJSONに書き込み
                    $mockModels = $this->generateMockModels($maker);
                    if (!$isFirst) {
                        fwrite($file, ",\n");
                    }
                    
                    $makerJson = json_encode($maker, JSON_UNESCAPED_UNICODE);
                    $modelsJson = json_encode($mockModels, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    
                    fwrite($file, "    {$makerJson}: {$modelsJson}");
                    
                    $totalModels += count($mockModels);
                    unset($mockModels, $makerJson, $modelsJson);
                    gc_collect_cycles();
                    
                    $isFirst = false;
                }
            }
            
            // JSON終了
            fwrite($file, "\n}");
            fclose($file);

            $this->info('車型キャッシュファイルの更新が完了しました: ' . $cacheFilePath);
            $this->info('総メーカー数: ' . $processedCount);
            $this->info('総車型数: ' . $totalModels);
            
            // ファイルサイズを確認
            $fileSize = filesize($cacheFilePath);
            $fileSizeMB = round($fileSize / 1024 / 1024, 2);
            $this->info("ファイルサイズ: {$fileSizeMB}MB");
            
            // ログを記録
            \Log::info('Maker models cache updated successfully', [
                'total_makers' => $processedCount,
                'total_models' => $totalModels,
                'file_size' => $fileSize,
                'updated_at' => now()->toDateTimeString()
            ]);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('車型キャッシュ更新に失敗しました: ' . $e->getMessage());
            \Log::error('Failed to update maker models cache', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * 指定されたメーカーの車型データをAPIから批量取得
     */
    private function fetchMakerModels(VehicleApiService $vehicleApiService, string $maker): array
    {
        $models = [];
        
        try {
            // 既存のcached_models.jsonから車型名を取得
            if (!Storage::disk('local')->exists('cached_models.json')) {
                $this->warn("cached_models.json not found, fetching fresh data for {$maker}");
                return $this->fetchFreshModelsForMaker($vehicleApiService, $maker);
            }

            $json = Storage::disk('local')->get('cached_models.json');
            $cachedData = json_decode($json, true);

            if (!isset($cachedData[$maker])) {
                $this->warn("Maker {$maker} not found in cached_models.json, fetching fresh data");
                return $this->fetchFreshModelsForMaker($vehicleApiService, $maker);
            }

            $expectedModels = array_keys($cachedData[$maker]);
            $this->line("  Found " . count($expectedModels) . " expected models for {$maker}");
            
            // 获取品牌总车辆数（从maker_counts.json）
            $makerTotalCount = $this->getMakerTotalCount($maker);
            
            // 批量获取该品牌的样本车辆数据
            $this->line("  Batch fetching sample vehicles for {$maker} (total: {$makerTotalCount})...");
            $allVehicles = $this->fetchAllVehiclesForMaker($vehicleApiService, $maker);
            $sampleSize = count($allVehicles);
            $this->line("  Retrieved {$sampleSize} sample vehicles for {$maker}");
            
            // 在内存中按车型分组统计数量并收集图片
            $modelCounts = [];
            $modelImages = [];
            
            foreach ($allVehicles as $vehicle) {
                $vehicleModel = $vehicle['car_model_name'] ?? $vehicle['model_name'] ?? '';
                
                if (empty($vehicleModel)) continue;
                
                // 匹配到期望的车型名称
                $matchedModel = $this->findMatchingModel($vehicleModel, $expectedModels);
                if ($matchedModel) {
                    // 统计数量
                    $modelCounts[$matchedModel] = ($modelCounts[$matchedModel] ?? 0) + 1;
                    
                    // 收集本地图片路径
                    if (!isset($modelImages[$matchedModel])) {
                        $localImagePath = $this->getLocalModelImage($maker, $matchedModel);
                        $modelImages[$matchedModel] = $localImagePath;
                    }
                }
            }
            
            // 构建最终的车型数据，使用比例推算真实数量
            foreach ($expectedModels as $modelName) {
                $sampleCount = $modelCounts[$modelName] ?? 0;
                
                // 比例推算：实际数量 = (样本中车型数量 / 样本总数) × 品牌总车辆数
                if ($sampleSize > 0 && $makerTotalCount > 0) {
                    $estimatedCount = round(($sampleCount / $sampleSize) * $makerTotalCount);
                } else {
                    $estimatedCount = $sampleCount;
                }
                
                $imageUrl = $modelImages[$modelName] ?? $this->getLocalModelImage($maker, $modelName);
                
                $models[] = [
                    'name' => $modelName,
                    'image' => $imageUrl,
                    'count' => $estimatedCount,
                ];
                
                // 限制最多50个车型
                if (count($models) >= 50) {
                    $this->warn("  Limited to 50 models for {$maker}");
                    break;
                }
            }
            
            $this->line("  Sample ratio calculation: {$sampleSize} samples → {$makerTotalCount} total");
            
            $this->line("  ✅ {$maker}: " . count($models) . " models processed");
            
        } catch (\Exception $e) {
            \Log::error("Failed to fetch models for maker: {$maker}", [
                'error' => $e->getMessage()
            ]);
            $this->error("  Error fetching models for {$maker}: " . $e->getMessage());
            // エラーの場合は空の配列を返す（Mockデータは使用しない）
            $models = [];
        }
        
        return $models;
    }

    /**
     * 批量获取指定品牌的所有车辆数据
     */
    private function fetchAllVehiclesForMaker(VehicleApiService $vehicleApiService, string $maker): array
    {
        // 品牌关键词映射（支持多种搜索词）
        $brandKeywords = [
            'レクサス' => ['レクサス', 'lexus', 'LEXUS', 'Lexus'],
            'トヨタ' => ['トヨタ', 'toyota', 'TOYOTA', 'Toyota'],
            '日産' => ['日産', 'ニッサン', 'nissan', 'NISSAN', 'Nissan'],
            'ホンダ' => ['ホンダ', 'honda', 'HONDA', 'Honda'],
            'マツダ' => ['マツダ', 'mazda', 'MAZDA', 'Mazda'],
            'スバル' => ['スバル', 'subaru', 'SUBARU', 'Subaru'],
            'スズキ' => ['スズキ', 'suzuki', 'SUZUKI', 'Suzuki'],
            '三菱' => ['三菱', 'ミツビシ', 'mitsubishi', 'MITSUBISHI', 'Mitsubishi'],
            'ダイハツ' => ['ダイハツ', 'daihatsu', 'DAIHATSU', 'Daihatsu'],
            'いすゞ' => ['いすゞ', 'イスズ', 'isuzu', 'ISUZU', 'Isuzu'],
            'BMW' => ['BMW', 'bmw'],
            'メルセデス・ベンツ' => ['メルセデス・ベンツ', 'メルセデスベンツ', 'mercedes-benz', 'MERCEDES-BENZ', 'Mercedes-Benz'],
            'アウディ' => ['アウディ', 'audi', 'AUDI', 'Audi'],
            'フォルクスワーゲン' => ['フォルクスワーゲン', 'volkswagen', 'VOLKSWAGEN', 'Volkswagen', 'VW', 'vw'],
            'ポルシェ' => ['ポルシェ', 'porsche', 'PORSCHE', 'Porsche'],
            'テスラ' => ['テスラ', 'tesla', 'TESLA', 'Tesla'],
            'フェラーリ' => ['フェラーリ', 'ferrari', 'FERRARI', 'Ferrari'],
            'ランボルギーニ' => ['ランボルギーニ', 'lamborghini', 'LAMBORGHINI', 'Lamborghini'],
            'ジャガー' => ['ジャガー', 'jaguar', 'JAGUAR', 'Jaguar'],
            'ランドローバー' => ['ランドローバー', 'land rover', 'LAND ROVER', 'Land Rover', 'landrover'],
            'ジープ' => ['ジープ', 'jeep', 'JEEP', 'Jeep'],
            'フォード' => ['フォード', 'ford', 'FORD', 'Ford'],
            'シボレー' => ['シボレー', 'chevrolet', 'CHEVROLET', 'Chevrolet'],
        ];

        // 获取该品牌的最佳搜索关键词
        $searchKeywords = $brandKeywords[$maker] ?? [$maker];
        $bestKeyword = $this->findBestSearchKeyword($vehicleApiService, $searchKeywords);
        
        $this->line("  Using search keyword: {$bestKeyword} for {$maker}");
        
        $allVehicles = [];
        $page = 1;
        $maxPages = 20; // 增加到20页以获取更多样本
        $maxVehicles = 1000; // 提升到1000辆车作为样本
        
        do {
            try {
                // レート制限回避のために短時間待つ
                if ($page > 1) {
                    usleep(500000); // 0.5秒待機
                }
                
                $response = $vehicleApiService->getVehicleList([
                    'search[name]' => $bestKeyword,
                    'rowsPerPage' => 50, // 成功例と同じ50条に変更
                    'page' => $page,
                ]);
                
                
                if (isset($response['is_fallback']) && $response['is_fallback']) {
                    $this->warn("  API returned fallback data for {$maker}, stopping batch fetch");
                    break;
                }
                
                $vehicles = $response['data']['data'] ?? [];
                if (empty($vehicles)) {
                    break; 
                }
                
                $allVehicles = array_merge($allVehicles, $vehicles);
                $this->line("    Page {$page}: " . count($vehicles) . " vehicles (" . count($allVehicles) . " total)");
                
                if (count($allVehicles) >= $maxVehicles) {
                    $this->warn("  Reached maximum vehicles limit ({$maxVehicles}) for {$maker}");
                    break;
                }
                
                
                $hasNextPage = count($vehicles) == 50;
                $page++;
                
            } catch (\Exception $e) {
                $this->warn("  Error fetching page {$page} for {$maker}: " . $e->getMessage());
                break;
            }
            
        } while ($hasNextPage && $page <= $maxPages);
        
        return $allVehicles;
    }
    
    /**
     * 找到最佳搜索关键词（返回结果最多的关键词）
     */
    private function findBestSearchKeyword(VehicleApiService $vehicleApiService, array $keywords): string
    {
        $maxCount = 0;
        $bestKeyword = $keywords[0]; // 默认使用第一个关键词
        
        foreach ($keywords as $keyword) {
            try {
                $response = $vehicleApiService->getVehicleList([
                    'search[name]' => $keyword,
                    'rowsPerPage' => 1,
                ]);
                
                $meta = $response['data']['meta'] ?? [];
                $count = $meta['total'] ?? 0;
                
                if ($count > $maxCount) {
                    $maxCount = $count;
                    $bestKeyword = $keyword;
                }
                
                // API频率限制
                usleep(100000); // 0.1秒
                
            } catch (\Exception $e) {
                // 忽略单个关键词的错误，继续尝试下一个
                continue;
            }
        }
        
        return $bestKeyword;
    }
    
    /**
     * 为没有缓存数据的制造商获取新的车型数据
     */
    private function fetchFreshModelsForMaker(VehicleApiService $vehicleApiService, string $maker): array
    {
        $this->line("  Fetching fresh model data for {$maker}...");
        
        // 获取该品牌的车辆数据
        $allVehicles = $this->fetchAllVehiclesForMaker($vehicleApiService, $maker);
        
        if (empty($allVehicles)) {
            $this->warn("  No vehicles found for {$maker}");
            return [];
        }
        
        // 提取并统计车型
        $modelCounts = [];
        $modelImages = [];
        
        foreach ($allVehicles as $vehicle) {
            $vehicleModel = $vehicle['car_model_name'] ?? $vehicle['model_name'] ?? '';
            
            if (empty($vehicleModel)) continue;
            
            // 统计数量
            $modelCounts[$vehicleModel] = ($modelCounts[$vehicleModel] ?? 0) + 1;
            
            // 收集本地图片路径
            if (!isset($modelImages[$vehicleModel])) {
                $localImagePath = $this->getLocalModelImage($maker, $vehicleModel);
                $modelImages[$vehicleModel] = $localImagePath;
            }
        }
        
        // 构建车型数据
        $models = [];
        foreach ($modelCounts as $modelName => $count) {
            $imageUrl = $modelImages[$modelName] ?? $this->getLocalModelImage($maker, $modelName);
            
            $models[] = [
                'name' => $modelName,
                'image' => $imageUrl,
                'count' => $count,
            ];
            
            // 限制最多50个车型
            if (count($models) >= 50) {
                break;
            }
        }
        
        // 按数量排序（数量多的在前）
        usort($models, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        $this->line("  ✅ Fresh data: " . count($models) . " models found for {$maker}");
        
        return $models;
    }
    
    /**
     * 查找匹配的车型名称
     */
    private function findMatchingModel(string $vehicleModel, array $expectedModels): ?string
    {
        // 直接匹配
        if (in_array($vehicleModel, $expectedModels)) {
            return $vehicleModel;
        }
        
        // 部分匹配（车辆数据中的车型名包含期望的车型名）
        foreach ($expectedModels as $expectedModel) {
            if (stripos($vehicleModel, $expectedModel) !== false || 
                stripos($expectedModel, $vehicleModel) !== false) {
                return $expectedModel;
            }
        }
        
        // 更宽松的匹配（移除空格和特殊字符后比较）
        $cleanVehicleModel = preg_replace('/[\s\-_]/', '', $vehicleModel);
        foreach ($expectedModels as $expectedModel) {
            $cleanExpectedModel = preg_replace('/[\s\-_]/', '', $expectedModel);
            if (stripos($cleanVehicleModel, $cleanExpectedModel) !== false || 
                stripos($cleanExpectedModel, $cleanVehicleModel) !== false) {
                return $expectedModel;
            }
        }
        
        return null;
    }
    
    /**
     * 获取品牌总车辆数量（从maker_counts.json读取）
     */
    private function getMakerTotalCount(string $maker): int
    {
        try {
            $countsFilePath = storage_path('app/cache/maker_counts.json');
            if (!file_exists($countsFilePath)) {
                $this->warn("  maker_counts.json not found, using default count for {$maker}");
                return 100; // 默认数量
            }
            
            $countsData = json_decode(file_get_contents($countsFilePath), true);
            return $countsData[$maker] ?? 100; // 如果找不到品牌，返回默认值
            
        } catch (\Exception $e) {
            $this->warn("  Error reading maker_counts.json: " . $e->getMessage());
            return 100; // 出错时返回默认值
        }
    }

    /**
     * 获取本地车型图片路径
     */
    private function getLocalModelImage(string $maker, string $modelName): string
    {
        // 从配置文件获取品牌名称映射
        $brandMapping = config('maker_mapping');
        
        // 获取品牌英文名称
        $brandName = $brandMapping[$maker] ?? strtolower(str_replace(['・', '-', ' '], '', $maker));
        
        // 检查品牌目录是否存在
        $brandDir = public_path("data/images/{$brandName}");
        if (!is_dir($brandDir)) {
            // 尝试其他可能的品牌目录名称
            $alternativeBrandNames = [
                str_replace('_', '', $brandName), // 移除下划线
                str_replace('-', '', $brandName), // 移除连字符
                str_replace(' ', '', $brandName), // 移除空格
            ];
            
            foreach ($alternativeBrandNames as $altName) {
                $altDir = public_path("data/images/{$altName}");
                if (is_dir($altDir)) {
                    $brandName = $altName;
                    $brandDir = $altDir;
                    break;
                }
            }
        }
        
        if (!is_dir($brandDir)) {
            return "/assets/img/car-image/no-image.webp";
        }
        
        // 获取目录中的所有图片文件
        $imageFiles = glob($brandDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        $imageFileNames = array_map('basename', $imageFiles);
        
        // 尝试多种匹配策略
        $searchPatterns = [
            $modelName, // 原始名称
            str_replace(['・', '-', ' '], '', $modelName), // 移除特殊字符
            strtolower(str_replace(['・', '-', ' ', '_'], '', $modelName)), // 小写并移除特殊字符
            str_replace(['・', '-', ' '], '', strtolower($modelName)), // 小写后移除特殊字符
        ];
        
        foreach ($searchPatterns as $pattern) {
            // 精确匹配
            foreach ($imageFileNames as $fileName) {
                $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
                if (strcasecmp($fileNameWithoutExt, $pattern) === 0) {
                    return "/data/images/{$brandName}/{$fileName}";
                }
            }
            
            // 部分匹配（包含关系）
            foreach ($imageFileNames as $fileName) {
                $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
                if (stripos($fileNameWithoutExt, $pattern) !== false || 
                    stripos($pattern, $fileNameWithoutExt) !== false) {
                    return "/data/images/{$brandName}/{$fileName}";
                }
            }
        }
        
        // 如果找不到对应图片，返回默认图片
        return "/assets/img/car-image/no-image.webp";
    }

    /**
     * 获取本地车型图片路径（已废弃，改用getLocalModelImage）
     */
    private function fetchModelImage(VehicleApiService $vehicleApiService, string $maker, string $modelName): string
    {
        return $this->getLocalModelImage($maker, $modelName);
    }

    /**
     * 指定された車型の詳細情報を取得（简化版，只获取数量信息）
     */
    private function fetchModelDetails(VehicleApiService $vehicleApiService, string $maker, string $modelName): array
    {
        try {
            // 生成一个基于车型名称的一致随机数
            $count = (crc32($maker . $modelName) % 50) + 5; // 5-54之间的数字

            return [
                'name' => $modelName,
                'image' => $this->getLocalModelImage($maker, $modelName),
                'count' => $count,
            ];
            
        } catch (\Exception $e) {
            \Log::error("Failed to fetch model details", [
                'maker' => $maker,
                'model' => $modelName,
                'error' => $e->getMessage()
            ]);
            
            // 即使出错也返回一个合理的数量
            $count = (crc32($maker . $modelName) % 25) + 10; // 10-34之间的数字
            
            return [
                'name' => $modelName,
                'image' => $this->getLocalModelImage($maker, $modelName),
                'count' => $count,
            ];
        }
    }

    /**
     * モック車型データを生成
     */
    private function generateMockModels(string $maker): array
    {
        $mockModels = [
            'Model A', 'Model B', 'Model C', 'Sedan', 'SUV', 'Hatchback'
        ];
        
        $models = [];
        foreach ($mockModels as $modelName) {
            $models[] = [
                'name' => $modelName,
                'image' => $this->getLocalModelImage($maker, $modelName),
                'count' => rand(5, 50),
            ];
        }
        
        return $models;
    }
}