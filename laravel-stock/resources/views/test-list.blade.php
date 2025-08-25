@extends('layouts.app')

@section('title', 'CTN車販売')

@section('content')
<main>
    <h1>車両データ一覧</h1>

    @if (!empty($vehicles))
        <table>
            <thead>
                <tr>
                    <th>メーカー</th>
                    <th>車種</th>
                    <th>グレード</th>
                    <th>年式</th>
                    <th>走行距離</th>
                    <th>価格</th>
                    <th>外装色</th>
                    <th>装備</th>
                    <th>保証内容</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->manufacturer_name ?? '' }}</td>
                        <td>{{ $vehicle->car_model_name ?? '' }}</td>
                        <td>{{ $vehicle->grade_name ?? '' }}</td>
                        <td>{{ $vehicle->first_registration_at ?? '' }}</td>
                        <td>{{ isset($vehicle->mileage) ? number_format($vehicle->mileage) . 'km' : '' }}</td>
                        <td>{{ isset($vehicle->price_incl_tax) ? number_format($vehicle->price_incl_tax) . '円' : '' }}</td>
                        <td>{{ $vehicle->exterior_color ?? '' }}</td>
                        <td>
                            @if (!empty($vehicle->equipments))
                                {{ implode(', ', get_object_vars($vehicle->equipments)) }}
                            @endif
                        </td>
                        <td>{{ $vehicle->warranty_details ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>車両データがありません。</p>
    @endif


</main>


<style>
    table {
        font-size: 12px;
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f4f4f4;
        position: sticky;
        top: 0;
        white-space: nowrap
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>