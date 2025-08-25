<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class TestListController extends Controller
{
    public function index()
    {
        $jsonPath = public_path('data/vehicles.json'); // publicディレクトリ直下にファイルを配置しておく

        if (!File::exists($jsonPath)) {
            abort(404, 'JSONファイルが見つかりません');
        }

        $vehicles = json_decode(File::get($jsonPath));

        return view('test-list', ['vehicles' => $vehicles]);
    }
}
