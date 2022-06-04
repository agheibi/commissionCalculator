<?php

namespace Tests\Unit;

use App\Actions\CalculateActions;
use App\Actions\FetchActions;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CalculateActionsTest extends TestCase
{
    public function test_calculate_end_week_formatted_action()
    {

        $calculate_actions = new CalculateActions();
        $format = env('OPERATION_DATE_FORMAT', 'Y-m-d');
        $data = ([
            "date" => "2016-02-19",
            "identification_id" => "5",
            "user_type" => "private",
            "operation_type" => "withdraw",
            "operation_amount" => "3000000",
            "operation_currency" => "JPY",
            "index" => 12
        ]);

        $carbon_date = Carbon::createFromFormat($format, '2016-02-19');
        $carbon_end_week = $carbon_date->endOfWeek();
        $formatted = $carbon_end_week->format($format);

        $response = $calculate_actions->calculateEndWeekFormatted($data);

        $this->assertTrue($response == $formatted);

    }

}
