<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VehicleApiService;
use Illuminate\Support\Facades\File;

class UpdateMakerCountsCache extends Command
{
    protected $signature = 'cache:update-maker-counts';
    protected $description = 'メーカー車両数量キャッシュファイルを更新';

    public function handle()
    {
        // メモリ制限を増加
        ini_set('memory_limit', '512M');
        $this->info('メーカー車両数量キャッシュの更新を開始します...');

        try {
            // メーカーリスト
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

            $allMakers = collect($makersByCountry)->flatten()->toArray();
            $this->info(count($allMakers) . ' 個のメーカーの車両数量を更新します');

            // 車両APIサービスを取得
            $vehicleApiService = new VehicleApiService();
            
            // キャッシュディレクトリの存在確認
            $cacheDir = storage_path('app/cache');
            if (!File::exists($cacheDir)) {
                File::makeDirectory($cacheDir, 0755, true);
                $this->info('キャッシュディレクトリを作成しました: ' . $cacheDir);
            }

            // ストリーミングJSON書き込みの開始
            $cacheFilePath = storage_path('app/cache/maker_counts.json');
            $file = fopen($cacheFilePath, 'w');
            if ($file === false) {
                throw new \Exception('キャッシュファイルを開けません: ' . $cacheFilePath);
            }
            
            // JSON開始
            fwrite($file, "{\n");
            
            $processedCount = 0;
            $totalMakers = count($allMakers);
            $totalVehicles = 0;
            $successCount = 0;
            $errorCount = 0;
            $isFirst = true;
            
            foreach ($allMakers as $maker) {
                $processedCount++;
                $this->info("処理中: {$maker} ({$processedCount}/{$totalMakers})");
                
                try {
                    // このメーカーの車両数量を取得
                    $count = $this->fetchMakerVehicleCount($vehicleApiService, $maker);
                    
                    // JSON片段を書き込み
                    if (!$isFirst) {
                        fwrite($file, ",\n");
                    }
                    
                    $makerJson = json_encode($maker, JSON_UNESCAPED_UNICODE);
                    fwrite($file, "    {$makerJson}: {$count}");
                    
                    $this->line("  → {$maker}: {$count} 台の車両");
                    
                    // 統計情報を更新
                    $totalVehicles += $count;
                    $successCount++;
                    
                    // メモリ使用量を表示
                    $memoryUsage = round(memory_get_usage() / 1024 / 1024, 2);
                    $this->line("  Memory usage: {$memoryUsage}MB");
                    
                    // 即座にメモリを解放
                    unset($makerJson);
                    gc_collect_cycles();
                    
                    // レート制限回避のために短時間待つ
                    usleep(500000); // 0.5秒
                    
                    $isFirst = false;
                    
                } catch (\Exception $e) {
                    $this->error("  → {$maker} の処理でエラー: " . $e->getMessage());
                    $errorCount++;
                    
                    // エラーの場合は0で書き込み
                    if (!$isFirst) {
                        fwrite($file, ",\n");
                    }
                    
                    $makerJson = json_encode($maker, JSON_UNESCAPED_UNICODE);
                    fwrite($file, "    {$makerJson}: 0");
                    
                    unset($makerJson);
                    gc_collect_cycles();
                    
                    $isFirst = false;
                }
            }
            
            // JSON終了
            fwrite($file, "\n}");
            fclose($file);

            $this->info('車両数量キャッシュファイルの更新が完了しました: ' . $cacheFilePath);
            $this->info('総メーカー数: ' . $processedCount);
            $this->info('成功: ' . $successCount . ' / エラー: ' . $errorCount);
            $this->info('総車両数: ' . number_format($totalVehicles) . ' 台');
            
            // ファイルサイズを確認
            $fileSize = filesize($cacheFilePath);
            $fileSizeKB = round($fileSize / 1024, 2);
            $this->info("ファイルサイズ: {$fileSizeKB}KB");
            
            // ログを記録
            \Log::info('Maker counts cache updated successfully', [
                'total_makers' => $processedCount,
                'successful' => $successCount,
                'errors' => $errorCount,
                'total_vehicles' => $totalVehicles,
                'file_size' => $fileSize,
                'updated_at' => now()->toDateTimeString()
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error('キャッシュ更新に失敗しました: ' . $e->getMessage());
            \Log::error('Failed to update maker counts cache', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * 指定されたメーカーの車両数量をAPIから取得
     */
    private function fetchMakerVehicleCount(VehicleApiService $vehicleApiService, string $maker): int
    {
        try {
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
                'キャデラック' => ['キャデラック', 'cadillac', 'CADILLAC', 'Cadillac'],
                'アルファロメオ' => ['アルファロメオ', 'alfa romeo', 'ALFA ROMEO', 'Alfa Romeo', 'alfaromeo'],
                'マセラティ' => ['マセラティ', 'maserati', 'MASERATI', 'Maserati'],
                'プジョー' => ['プジョー', 'peugeot', 'PEUGEOT', 'Peugeot'],
                'ルノー' => ['ルノー', 'renault', 'RENAULT', 'Renault'],
                'シトロエン' => ['シトロエン', 'citroen', 'CITROEN', 'Citroen'],
                'ボルボ' => ['ボルボ', 'volvo', 'VOLVO', 'Volvo'],
                'サーブ' => ['サーブ', 'saab', 'SAAB', 'Saab'],
                'ヒョンデ' => ['ヒョンデ', 'ヒュンダイ', 'hyundai', 'HYUNDAI', 'Hyundai'],
            ];

            // 获取该品牌的搜索关键词
            $searchKeywords = $brandKeywords[$maker] ?? [$maker];
            
            $maxCount = 0;
            $bestKeyword = '';
            
            // 尝试每个关键词，取最大值
            foreach ($searchKeywords as $keyword) {
                $response = $vehicleApiService->getVehicleList([
                    'search[name]' => $keyword,
                    'rowsPerPage' => 1,
                ]);

                $vehicles = $response['data']['data'] ?? [];
                $meta = $response['data']['meta'] ?? [];
                $count = $meta['total'] ?? count($vehicles);
                
                if ($count > $maxCount) {
                    $maxCount = $count;
                    $bestKeyword = $keyword;
                }
                
                // API频率限制
                usleep(100000); // 0.1秒
            }
            
            if ($bestKeyword && $bestKeyword !== $maker) {
                $this->line("    最適なキーワード: {$bestKeyword} ({$maxCount}台)");
            }
            
            return (int) $maxCount;
            
        } catch (\Exception $e) {
            \Log::error("Failed to fetch vehicle count for maker: {$maker}", [
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }
}