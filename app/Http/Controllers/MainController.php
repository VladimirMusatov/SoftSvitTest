<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

use App\Models\FinancicalData;

class MainController extends Controller
{
    public function index()
    {

        $status = Redis::get('api_switcher');

        if(!$status)
        {
            Redis::set('api_switcher', 'rapid');
        }

        return view('main', ['status' => $status]);
    }

    public function table(Request $request)
    {
        $query = FinancicalData::query();

        $filters = $request->only(['symbol', 'price_start', 'price_end','name', 'volume_start', 'volume_end']);

        $filter_conditions = [
            ['symbol', '=', $filters['symbol'] ?? null],
            ['financial_details->price', '>=', $filters['price_start'] ?? null],
            ['financial_details->price', '<=', $filters['price_end'] ?? null],
            ['name', 'like', $filters['name'] ?? null],
            ['financial_details->volume', '>=', $filters['volume_start'] ?? null],
            ['financial_details->volume', '<=', $filters['volume_end'] ?? null],
        ];

        foreach ($filter_conditions as $condition) {
            if (!empty($condition[2])) {
                $query->where($condition[0], $condition[1], $condition[2]);
            }
        }
    
        $fin_data = $query->get();

        return view('table', ['fin_data' => $fin_data, 'filters' => $filters]);

    }

    public function post_request(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
    
        $api_controller = new ApiController();

        $params = [
            'name' => $request->name,
        ];

        switch($request->api)
        {
            case "rapid":
                $api_responce = $api_controller->rapid($params);
                break;
            case "financial_data":
                $api_responce = $api_controller->financial_data($params);
                break;
        }

        Redis::set('api_switcher', $request->api);

        if(!$api_responce['success'])
        {
            return back()->withErrors($api_responce['message']); 
        }
        
        $api_responce = $api_responce['data'];

        $financial_details = [
            'eps' => $api_responce['eps'],
            'price' => $api_responce['price'],
            'volume' => $api_responce['volume'],
        ];

        $financial_details = json_encode($financial_details);

        DB::table('financical_data')->updateOrInsert(
            [
                'symbol' => $api_responce['symbol']
            ],
            [
                'symbol' => $api_responce['symbol'],
                'name' => $api_responce['name'],
                'financial_details' => $financial_details,
                'open' => $api_responce['open'],
                'previousClose' => $api_responce['previousClose'],
            ]
        );

        return redirect()->back()->with(['message' => 'data added successfully']);
    }
}
