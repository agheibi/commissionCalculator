<?php


namespace App\Actions;


use Illuminate\Support\Facades\Http;

class FetchActions
{
    private $exchange_rate_url;

    public function __construct()
    {
        $this->exchange_rate_url = env('EXCHANGE_RATE_URL', 'https://developers.paysera.com/tasks/api/currency-exchange-rates');
    }

    public function fetchRate($currency)
    {
        try{
            $response = Http::get($this->exchange_rate_url);
            $response_json = $response->json();
            $rates = (array) $response_json['rates'];
            return $rates[$currency];
        }catch(\Exception $exception){
            throw new \Exception('no response from api');
        }

    }
}
