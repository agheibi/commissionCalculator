<?php

namespace Tests\Unit;

use App\Actions\ConvertActions;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\TestCase;

class ConvertActionsTest extends TestCase
{

    public function test_round_up_numbers()
    {
        $convert_actions = new ConvertActions();

        $numbers = [3.341, 8743.2342, 3.20];
        $currencies = ['USD', 'JPY', 'EUR'];
        $results = [3.35, 8744, 3.20];

        foreach ($numbers as $key => $number){
            $result = $convert_actions->roundUp($number, $currencies[$key]);
            $this->assertTrue($result == $results[$key]);
        }
    }

    public function test_date_convert()
    {
        $convert_actions = new ConvertActions();

        $data = ([
            "date" => "2016-02-19",
            "identification_id" => "5",
            "user_type" => "private",
            "operation_type" => "withdraw",
            "operation_amount" => "3000000",
            "operation_currency" => "JPY",
            "index" => 12
        ]);

        $date = Carbon::createFromFormat('Y-m-d', '2016-02-19');

        $response = $convert_actions->dateConverter($data, 'result');

        $this->assertTrue($date->toDate() == $response['result']->toDate());

    }
}
