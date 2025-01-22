<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;

class FetchFinancialData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $params;

    /**
     * Create a new job instance.
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $api_controller = new ApiController();

        $params = $this->params;

        $api = $params['api'];

        switch($api)
        {
            case "rapid":
                $api_responce = $api_controller->rapid($params);
                break;
            case "financial_data":
                $api_responce = $api_controller->financial_data($params);
                break;
        }

        if ($api_responce['success']) {
            $api_responce = $api_responce['data'];

            $financial_details = [
                'eps' => $api_responce['eps'],
                'price' => $api_responce['price'],
                'volume' => $api_responce['volume'],
            ];

            $financial_details = json_encode($financial_details);

            DB::table('financical_data')->updateOrInsert(
                ['symbol' => $api_responce['symbol']],
                [
                    'symbol' => $api_responce['symbol'],
                    'name' => $api_responce['name'],
                    'financial_details' => $financial_details,
                    'open' => $api_responce['open'],
                    'previousClose' => $api_responce['previousClose'],
                ]
            );
        }
    }
}
