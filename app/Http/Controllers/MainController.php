<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;

use App\Models\FinancicalData;

class MainController extends Controller
{
    public function index()
    {
        return view('main');
    }

    public function table()
    {
        $fin_data = FinancicalData::all();

        return view('table', ['fin_data' => $fin_data]);
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
