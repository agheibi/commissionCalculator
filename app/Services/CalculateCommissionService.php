<?php

namespace App\Services;

use App\Actions\CalculateActions;
use App\Actions\CommissionActions;
use App\Actions\ConvertActions;
use App\Actions\GroupingActions;


class CalculateCommissionService
{
    private $deposit_interest;
    private $operation_type_column_name;
    private $user_type_column_name;
    private $currency_column_name;
    private $business_type_in_file;
    private $private_type_in_file;
    private $deposit_type_in_file;
    private $withdraw_type_in_file;
    private $amount_column_name;
    private $deposit_commission_fee_percent;
    private $withdraw_business_commission_fee_percent;
    private $withdraw_private_commission_fee_percent;
    private $grouping_actions;
    private $convert_actions;
    private $calculate_actions;
    private $private_withdraw_amount_limit;
    private $private_withdraw_number_limit;
    private $commission_actions;

    public function __construct()
    {
        $this->deposit_interest = env('DEPOSIT_INTEREST', 0.3);
        $this->operation_type_column_name = env('OPERATION_TYPE_COLUMN_NAME', 'operation_type');
        $this->user_type_column_name = env('USER_TYPE_COLUMN_NAME', 'user_type');
        $this->currency_column_name = env('CURRENCY_COLUMN_NAME', 'operation_currency');
        $this->business_type_in_file = env('BUSINESS_TYPE_IN_FILE', 'business');
        $this->private_type_in_file = env('PRIVATE_TYPE_IN_FILE', 'private');
        $this->deposit_type_in_file = env('DEPOSIT_IN_FILE', 'deposit');
        $this->withdraw_type_in_file = env('WITHDRAW_IN_FILE', 'withdraw');
        $this->amount_column_name = env('AMOUNT_COLUMN_NAME', 'operation_amount');
        $this->deposit_commission_fee_percent = env('DEPOSIT_COMMISSION_FEE_PERCENT', 0.03);
        $this->private_withdraw_amount_limit = env('PRIVATE_WITHDRAW_AMOUNT_LIMIT', 1000);
        $this->private_withdraw_number_limit = env('PRIVATE_WITHDRAW_NUMBER_LIMIT', 3);
        $this->withdraw_business_commission_fee_percent = env('WITHDRAW_BUSINESS_COMMISSION_FEE_PERCENT', 0.5);
        $this->withdraw_private_commission_fee_percent = env('WITHDRAW_PRIVATE_COMMISSION_FEE_PERCENT', 0.3);
        $this->grouping_actions = new GroupingActions();
        $this->convert_actions = new ConvertActions();
        $this->calculate_actions = new CalculateActions();
        $this->commission_actions = new CommissionActions();

    }


    public function start($csv){
        try{
            $data = $this->convert_actions->convertFileToArray($csv);

            $grouped_data = $this->grouping_actions->groupData($data);

            //private withdraw calculation
            $private_withdraw_result = $this->commission_actions->calculateWithdrawPrivate($grouped_data[$this->private_type_in_file][$this->withdraw_type_in_file]);

            //private deposit calculation
            $private_deposit_result = $this->commission_actions->calculateDepositPrivate($grouped_data[$this->private_type_in_file][$this->deposit_type_in_file]);

            //business withdraw calculation
            $business_withdraw_result = $this->commission_actions->calculateWithdrawBusiness($grouped_data[$this->business_type_in_file][$this->withdraw_type_in_file]);

            //business deposit calculation
            $business_deposit_result = $this->commission_actions->calculateDepositBusiness($grouped_data[$this->business_type_in_file][$this->deposit_type_in_file]);

            $merged_data = array_merge($private_withdraw_result, $private_deposit_result, $business_withdraw_result, $business_deposit_result);
            $final_data = [];
            foreach ($merged_data as $single_data){
                $index = $single_data['index'];

                $final_data[$index] = $single_data;
            }

            ksort($final_data);

        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

        return $final_data;

    }

}
