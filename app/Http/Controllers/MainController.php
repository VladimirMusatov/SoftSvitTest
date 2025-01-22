<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Jobs\FetchFinancialData;

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

        $params = [
            'name' => $request->name,
            'api' => $request->api,
        ];

        Redis::set('api_switcher', $request->api);

        FetchFinancialData::dispatch($params);

        return redirect()->back()->with(['message' => 'Data is being processed in the background']);
    }
}
