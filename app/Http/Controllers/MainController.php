<?php

namespace App\Http\Controllers;

use App\Actions\CalculateActions;
use App\Actions\ConvertActions;
use Illuminate\Http\Request;
use App\Services\CalculateCommissionService;

class MainController extends Controller
{
    private $calculate_actions;
    private $convert_actions;

    public function __construct()
    {
        $this->calculate_actions = new CalculateActions();
        $this->convert_actions = new ConvertActions();
    }

    public function getFile(Request $request)
    {
        $data = $request->all();
        $calculateCommissionService = new CalculateCommissionService();
        try {
            $data = $calculateCommissionService->start($data['csv']);

            foreach ($data as $item) {
                $converted = $this->calculate_actions->convertBaseToMainCurrency($item['commission_amount'],
                    $item['operation_currency']);
                $roundedUp = $this->convert_actions->roundUp($converted, $item['operation_currency']);
                dump($roundedUp);
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }
}
