<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VehicleApiService;
use Illuminate\Support\Facades\Storage;

class CacheMakerModels extends Command
{
    protected $signature = 'cache:maker-models';
    protected $description = 'Lấy dữ liệu model theo maker và lưu vào file JSON';
    
    protected $REQUESTS_PER_MINUTE = 120;
    protected $DELAY_BETWEEN_REQUESTS;

    public function __construct()
    {
        parent::__construct();
        $this->DELAY_BETWEEN_REQUESTS = 60 / $this->REQUESTS_PER_MINUTE;
    }

    public function handle()
    {
        $this->info('Bắt đầu lấy dữ liệu model...');

        $api = new VehicleApiService();
        $makers = config('car.makers');
        $data = [];

        foreach ($makers as $makerId => $makerName) {
            $this->info("Processing maker: $makerName ($makerId)");
            
            $page = 1;
            $allModels = [];
            $startTime = microtime(true);
            $requestCount = 0;

            do {
                $elapsedTime = microtime(true) - $startTime;
                $expectedTime = $requestCount * $this->DELAY_BETWEEN_REQUESTS;
                
                if ($expectedTime > $elapsedTime) {
                    $sleepTime = $expectedTime - $elapsedTime;
                    usleep($sleepTime * 1000000);
                }

                try {
                    $params = [
                        'rowsPerPage' => 50,
                        'page' => $page,
                        'search[name]' => $makerId,
                    ];

                    $response = $api->getVehicleList($params);
                    $requestCount++;

                    if (isset($response['status']) && $response['status'] === 429) {
                        $retryAfter = $response['headers']['Retry-After'] ?? 10;
                        $this->warn("Rate limited. Waiting {$retryAfter}s...");
                        sleep($retryAfter);
                        continue;
                    }

                    $vehicles = $response['data']['data'] ?? [];
                    
                    foreach ($vehicles as $vehicle) {
                        if (!empty($vehicle['car_model_name'])) {
                            $allModels[] = $vehicle['car_model_name'];
                        }
                    }

                    foreach ($vehicles as $vehicle) {
                        $model = $vehicle['car_model_name'] ?? null;
                        $grade = $vehicle['grade_name'] ?? null;

                        if (!empty($model)) {
                            if (!isset($allModels[$model])) {
                                $allModels[$model] = [];
                            }
                            if (!empty($grade)) {
                                $allModels[$model][] = $grade;
                            }
                        }
                    }

                    $page++;
                } catch (\Exception $e) {
                    $this->error("Error: ".$e->getMessage());
                    sleep(5);
                    continue;
                }
            } while (!empty($vehicles) && $page <= ($response['data']['meta']['last_page'] ?? 1));
            
            $data[$makerId] = array_values(array_unique($allModels));
            
            usleep($this->DELAY_BETWEEN_REQUESTS * 1000000);
        }

        Storage::disk('local')->put('cached_models.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $this->info('Đã lưu dữ liệu vào storage/app/cached_models.json');
    }
}


// class CacheMakerModels extends Command
// {
//     protected $signature = 'cache:maker-models';
//     protected $description = 'Lấy dữ liệu model theo maker và lưu vào file JSON';

//     protected $REQUESTS_PER_MINUTE = 120;
//     protected $DELAY_BETWEEN_REQUESTS;

//     public function __construct()
//     {
//         parent::__construct();
//         $this->DELAY_BETWEEN_REQUESTS = 60 / $this->REQUESTS_PER_MINUTE;
//     }

//     public function handle()
//     {
//         $this->info('Bắt đầu lấy dữ liệu model...');

//         $api = new VehicleApiService();
//         $makers = config('car.makers'); // [maker_id => maker_name]
//         $data = [];

//         foreach ($makers as $makerId => $makerName) {
//             $this->info("Processing maker: $makerName ($makerId)");

//             $page = 1;
//             $models = [];
//             $startTime = microtime(true);
//             $requestCount = 0;

//             do {
//                 $elapsedTime = microtime(true) - $startTime;
//                 $expectedTime = $requestCount * $this->DELAY_BETWEEN_REQUESTS;

//                 if ($expectedTime > $elapsedTime) {
//                     $sleepTime = $expectedTime - $elapsedTime;
//                     usleep($sleepTime * 1000000);
//                 }

//                 try {
//                     $params = [
//                         'rowsPerPage' => 50,
//                         'page' => $page,
//                         'search[name]' => $makerId,
//                     ];

//                     $response = $api->getVehicleList($params);
//                     $requestCount++;

//                     if (isset($response['status']) && $response['status'] === 429) {
//                         $retryAfter = $response['headers']['Retry-After'] ?? 10;
//                         $this->warn("Rate limited. Waiting {$retryAfter}s...");
//                         sleep($retryAfter);
//                         continue;
//                     }

//                     $vehicles = $response['data']['data'] ?? [];

//                     foreach ($vehicles as $vehicle) {
//                         $model = $vehicle['car_model_name'] ?? null;
//                         $grade = $vehicle['grade_name'] ?? null;

//                         if (!empty($model)) {
//                             if (!isset($models[$model])) {
//                                 $models[$model] = [];
//                             }

//                             if (!empty($grade)) {
//                                 $models[$model][] = $grade;
//                             }
//                         }
//                     }

//                     $page++;
//                 } catch (\Exception $e) {
//                     $this->error("Error: ".$e->getMessage());
//                     sleep(5);
//                     continue;
//                 }
//             } while (!empty($vehicles) && $page <= ($response['data']['meta']['last_page'] ?? 1));

//             // Xoá trùng grade cho từng model
//             foreach ($models as $modelName => $grades) {
//                 $models[$modelName] = array_values(array_unique($grades));
//             }

//             // Gán vào data với tên hãng là key (vd. "トヨタ")
//             $data[$makerName] = $models;

//             usleep($this->DELAY_BETWEEN_REQUESTS * 1000000);
//         }

//         Storage::disk('local')->put('cached_models.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
//         $this->info('Đã lưu dữ liệu vào storage/app/cached_models.json');
//     }
// }
