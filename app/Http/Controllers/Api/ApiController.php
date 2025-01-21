<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function financial_data($params)
    {
        $financialmodelingprep_api_key = env('FINANCIALMODELINGPREP_API_KEY');

        $curl = curl_init();

        $name = $params['name']; 

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://financialmodelingprep.com/api/v3/quote/'.$name.'?apikey='.$financialmodelingprep_api_key ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
          ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);


        if(empty($response))
        {
            return [
                'success' => false,
                'message' => 'response is empty'
            ];
        }

        $response = collect($response);

        $api_responce = $response->first();

        $data_to_responce = [ 
            'symbol' => $api_responce->symbol,
            'name' => $api_responce->name,
            'price' => $api_responce->price,
            'volume' => $api_responce->volume,
            'eps' => $api_responce->eps,
            'open' => $api_responce->open,
            'previousClose' => $api_responce->previousClose
        ];

        return [
            'success' => true,
            'data' => $data_to_responce,
        ];
    }

    public function rapid($params)
    {
        $name = $params['name'];
        $api_key = env('RAPID_API_KEY');


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://yahoo-finance166.p.rapidapi.com/api/market/get-quote?symbols=".$name,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: yahoo-finance166.p.rapidapi.com",
                "x-rapidapi-key: " . $api_key
            ],
        ]);

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'success' => false,
                'message' => "cURL Error #:" . $err
            ];
        }

        $response = json_decode($response);

        $response = collect($response);

        if(empty($response['quoteResponse']->result)){
            return [
                'success' => false,
                'message' => 'response is empty',
            ];
        }
        else{

            $response = $response['quoteResponse']->result[0];

            $name = $response->longName;
            $name = preg_replace('/[^a-zA-Z\s]/u', '', $name);

            $data_to_responce = [
                'symbol' => $response->symbol,
                'name' => $name,
                'price' => $response->regularMarketPrice,
                'volume' => $response->regularMarketVolume,
                'eps' => $response->epsTrailingTwelveMonths,
                'open' => $response->regularMarketOpen,
                'previousClose' => $response->regularMarketPreviousClose,
            ];

            return [
                'success' => true,
                'data' => $data_to_responce,
            ];
        }
    }
}
