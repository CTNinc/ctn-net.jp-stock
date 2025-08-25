<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryToAdmin;
use App\Mail\InquiryToUser;
use App\Services\VehicleApiService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;


class InquiryController extends Controller
{
    public function create(Request $request)
    {
        $vehicleId = $request->query('vehicle_id');
        $shopName = $request->query('shop_name'); // URLパラメータから店舗名を取得
        $vehicle = null;

        if ($vehicleId) {
            try {
                // APIから車両情報を取得（店舗情報は前ページから受け取るため、APIリクエストを削減）
                $api = new VehicleApiService();
                $response = $api->getVehicleById($vehicleId);
                $vehicle = $response['data'] ?? null;

                \Log::debug('vehicle param and API data', [
                    'vehicle_id_from_request' => $vehicleId,
                    'external_id_from_api' => $vehicle['external_id'] ?? null,
                ]);
            } catch (\Exception $e) {
                // API呼び出しが失敗した場合、エラーをログに記録するが、ページ表示は阻止しない
                \Log::error('Failed to get vehicle info for inquiry: ' . $e->getMessage());
            }
        }

        return view('inquiry.inquiry', compact('vehicle', 'shopName'));
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'inquiry_type' => 'required|array',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'privacy_agree' => 'required',
        ]);

        // デバッグログ：受信したリクエストデータを記録
        \Log::info('Inquiry confirm request data', [
            'all_request_data' => $request->all(),
            'validated_data' => $validated,
            'vehicle_data' => [
                'vehicle_id' => $request->input('vehicle_id'),
                'vehicle_manufacturer' => $request->input('vehicle_manufacturer'),
                'vehicle_model' => $request->input('vehicle_model'),
                'vehicle_grade' => $request->input('vehicle_grade'),
                'vehicle_year' => $request->input('vehicle_year'),
                'vehicle_mileage' => $request->input('vehicle_mileage'),
                'vehicle_price' => $request->input('vehicle_price'),
                'vehicle_retailer' => $request->input('vehicle_retailer'),
            ]
        ]);

        $formData = $validated + $request->except(array_keys($validated));

        // デバッグログ：confirmページに渡すformDataを記録
        \Log::info('Data passed to confirm view', [
            'formData' => $formData
        ]);

        return view('inquiry.confirm', ['formData' => $formData]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        try {
            /** ▼▼▼ API送信 ▼▼▼ **/
            $apiKey = config('services.inquiry_api.key');
            $method = 'POST';
            $fullPath = '/api/v1/vehicle_inquiries/contact';
            $path = ltrim($fullPath, '/');
            $timestamp = time();
            $signatureBase = strtoupper($method) . $path . $apiKey . $timestamp;
            $signature = hash_hmac('sha256', $signatureBase, $apiKey);

            \Log::debug('署名データ生成', [
                'method' => strtoupper($method),
                'path' => $path,
                'timestamp' => $timestamp,
                'data' => $signatureBase,
                'signature' => $signature,
            ]);

            $contentMapping = [
                '見積依頼'         => 1,
                '在庫確認'         => 2,
                '来店予約'         => 3,
                'オンライン商談予約' => 3, // ← 忘れず追加！
                '車両状態確認'     => 4,
                'その他'           => 5,
            ];
            $contentTypes = array_map(function ($type) use ($contentMapping) {
                return $contentMapping[$type] ?? null;
            }, $data['inquiry_type'] ?? []);

            $contentTypes = array_filter($contentTypes);

            // content: お問い合わせ種別を API形式に
            // $contentTypes = array_map('intval', $data['inquiry_type'] ?? []);

            \Log::debug('問い合わせ種別の変換結果', [
                'original' => $data['inquiry_type'] ?? [],
                'converted' => $contentTypes,
            ]);

            // visit_datetime: 来店希望（3）が含まれる場合のみ送信
            $visitDatetime = null;
            if (in_array(3, $contentTypes)) {
                if (!empty($data['visit_date']) && !empty($data['visit_time'])) {
                    $visitDatetime = \Carbon\Carbon::parse(
                        str_replace(['年', '月', '日'], ['-', '-', ''], $data['visit_date']) . ' ' . $data['visit_time']
                    )->format('Y-m-d H:i:s');
                }
            }

            $apiPayload = [
                'vehicle_id'   => $data['vehicle_id'],
                'content'      => $contentTypes,
                'name'         => $data['name'],
                'furigana'     => $data['furigana'],
                'email'        => $data['email'],
                'postal_code'  => preg_replace('/\D/', '', $data['zip']),
                'phone_number' => preg_replace('/\D/', '', $data['phone']),
                'message'      => $data['message'] ?? '',
            ];

            if ($visitDatetime) {
                $apiPayload['visit_datetime'] = $visitDatetime;
            }

            \Log::info('Sending inquiry data to external API', ['payload' => $apiPayload]);

            $baseUrl = rtrim(config('services.inquiry_api.endpoint'), '/');
            $url = $baseUrl . '/' . $path;

            $apiResponse = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'X-Timestamp' => $timestamp,
                'X-Signature' => $signature,
            ])->post($url, $apiPayload);

            if (!$apiResponse->successful()) {
                \Log::error('API送信エラー: ', [
                    'status' => $apiResponse->status(),
                    'response' => $apiResponse->body()
                ]);
                return redirect()->route('inquiry.thanks')->with('warning', 'API送信に失敗しました。');
            }

            /** ▲▲▲ ここまでAPI送信 ▲▲▲ **/

            // メール送信処理
            \Log::info('Starting email sending process');

            // 管理者メール送信
            \Log::info('Attempting to send admin email to: info@ctn-net.co.jp');
            Mail::to('info@ctn-net.co.jp')->send(new InquiryToAdmin($data));
            \Log::info('Admin email sent successfully');

            // ユーザーメール送信（メールアドレスが有効な場合のみ）
            if (!empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                \Log::info('Attempting to send user email to: ' . $data['email']);
                Mail::to($data['email'])->send(new InquiryToUser($data));
                \Log::info('User email sent successfully');
            } else {
                \Log::warning('Invalid or empty email address: ' . ($data['email'] ?? 'null'));
            }

            // 記録諮詢數據到日誌
            \Log::info('Inquiry submitted successfully', [
                'user_email' => $data['email'] ?? 'N/A',
                'user_name' => $data['name'] ?? 'N/A',
                'inquiry_type' => $data['inquiry_type'] ?? [],
                'vehicle_id' => $data['vehicle_id'] ?? 'N/A',
                'vehicle_info' => [
                    'manufacturer' => $data['vehicle_manufacturer'] ?? 'N/A',
                    'model' => $data['vehicle_model'] ?? 'N/A',
                    'grade' => $data['vehicle_grade'] ?? 'N/A',
                    'year' => $data['vehicle_year'] ?? 'N/A',
                    'mileage' => $data['vehicle_mileage'] ?? 'N/A',
                    'price' => $data['vehicle_price'] ?? 'N/A',
                    'retailer' => $data['vehicle_retailer'] ?? 'N/A'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to process inquiry: ' . $e->getMessage());
            return redirect()->route('inquiry.thanks')->with('warning', 'お問い合わせの処理中にエラーが発生しました。');
        }

        // 車両情報をセッションに保存してthanksページで表示
        $vehicleInfo = [
            'manufacturer' => $data['vehicle_manufacturer'] ?? '',
            'model' => $data['vehicle_model'] ?? '',
            'grade' => $data['vehicle_grade'] ?? '',
            'year' => $data['vehicle_year'] ?? '',
            'mileage' => $data['vehicle_mileage'] ?? '',
            'price' => $data['vehicle_price'] ?? '',
            'body_price' => $data['vehicle_body_price'] ?? '',
            'inspection' => $data['vehicle_inspection'] ?? '',
            'displacement' => $data['vehicle_displacement'] ?? ''
        ];

        return redirect()->route('inquiry.thanks')->with('inquiry_vehicle', $vehicleInfo);
    }
}
