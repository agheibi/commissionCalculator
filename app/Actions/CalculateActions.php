<?php


namespace App\Actions;

use Carbon\Carbon;

class CalculateActions
{
    private $operation_type_column_name;
    private $withdraw_type_in_file;
    private $private_type_in_file;
    private $user_type_column_name;
    private $currency_column_name;
    private $base_currency;
    private $amount_column_name;
    private $operation_date_format;
    private $operation_date_column_name;
    private $fetch_actions;
    private $private_withdraw_amount_limit;
    private $private_withdraw_number_limit;
    private $withdraw_private_commission_fee_percent;
    private $deposit_commission_fee_percent;
    private $withdraw_business_commission_fee_percent;



    public function __construct()
    {
        $this->operation_type_column_name = env('OPERATION_TYPE_COLUMN_NAME', 'operation_type');
        $this->withdraw_type_in_file = env('WITHDRAW_IN_FILE', 'withdraw');
        $this->private_type_in_file = env('PRIVATE_TYPE_IN_FILE', 'private');
        $this->user_type_column_name = env('USER_TYPE_COLUMN_NAME', 'user_type');
        $this->currency_column_name = env('CURRENCY_COLUMN_NAME', 'operation_currency');
        $this->base_currency = env('BASE_CURRENCY', 'EUR');
        $this->amount_column_name = env('AMOUNT_COLUMN_NAME', 'operation_amount');
        $this->operation_date_format = env('OPERATION_DATE_FORMAT', 'Y-m-d');
        $this->operation_date_column_name = env('OPERATION_DATE_COLUMN_NAME', 'date');
        $this->private_withdraw_amount_limit = env('PRIVATE_WITHDRAW_AMOUNT_LIMIT', 1000);
        $this->private_withdraw_number_limit = env('PRIVATE_WITHDRAW_NUMBER_LIMIT', 3);
        $this->withdraw_private_commission_fee_percent = env('WITHDRAW_PRIVATE_COMMISSION_FEE_PERCENT', 0.3);
        $this->deposit_commission_fee_percent = env('DEPOSIT_COMMISSION_FEE_PERCENT', 0.03);
        $this->withdraw_business_commission_fee_percent = env('WITHDRAW_BUSINESS_COMMISSION_FEE_PERCENT', 0.5);
        $this->fetch_actions = new FetchActions();

    }

    public function calculateEndWeekFormatted($value)
    {
        try{
            $transaction_day = Carbon::createFromFormat($this->operation_date_format, $value[$this->operation_date_column_name]);
            $end_week_date = $transaction_day->endOfWeek();
            $formatted_end_week_date = $end_week_date->format($this->operation_date_format);
            return $formatted_end_week_date;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }

    public function convertAmountToBase($value)
    {
        try{
            $rate = $this->fetch_actions->fetchRate($value[$this->currency_column_name]);
            return ($value[$this->amount_column_name] / $rate);
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }

    public function convertBaseToMainCurrency($amount, $currency)
    {
        try{
            $rate = $this->fetch_actions->fetchRate($currency);
            return ($amount * $rate);
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }
}
