<?php


namespace App\Actions;

class CommissionActions
{

    private $grouping_actions;
    private $private_withdraw_amount_limit;
    private $private_withdraw_number_limit;
    private $withdraw_private_commission_fee_percent;
    private $deposit_commission_fee_percent;
    private $withdraw_business_commission_fee_percent;


    public function __construct()
    {
        $this->grouping_actions = new GroupingActions();
        $this->private_withdraw_amount_limit = env('PRIVATE_WITHDRAW_AMOUNT_LIMIT', 1000);
        $this->private_withdraw_number_limit = env('PRIVATE_WITHDRAW_NUMBER_LIMIT', 3);
        $this->withdraw_private_commission_fee_percent = env('WITHDRAW_PRIVATE_COMMISSION_FEE_PERCENT', 0.3);
        $this->deposit_commission_fee_percent = env('DEPOSIT_COMMISSION_FEE_PERCENT', 0.03);
        $this->withdraw_business_commission_fee_percent = env('WITHDRAW_BUSINESS_COMMISSION_FEE_PERCENT', 0.5);
    }
    public function calculateWithdrawPrivate($data)
    {
        try{
            $group_by_identification_id = $this->grouping_actions->groupByIdentificationId($data);
            $group_by_identification_and_time = [];
            foreach ($group_by_identification_id as $key => $value){
                $group_by_identification_and_time[$key]=$this->grouping_actions->groupByWeekTransactions($value);
            }
            foreach ($group_by_identification_and_time as $single_person_operations_key => $single_person_operation){
                foreach ($single_person_operation as $single_week_person_operations_key => $single_week_person_operations){
                    $remain = $this->private_withdraw_amount_limit;
                    $count = 1;
                    foreach ($single_week_person_operations as $single_person_operation_key => $single_person_operation){
                        $diff = (float)$remain - (float)$single_person_operation['base_currency_amount'];
                        if($count <= $this->private_withdraw_number_limit && $diff >= 0){
                            $group_by_identification_and_time[$single_person_operations_key][$single_week_person_operations_key][$single_person_operation_key]['include_commission_amount'] = 0;
                            $remain = $diff;
                        }elseif ($count <= $this->private_withdraw_number_limit && $diff < 0){
                            $group_by_identification_and_time[$single_person_operations_key][$single_week_person_operations_key][$single_person_operation_key]['include_commission_amount'] = -$diff;
                            $remain = 0;
                        }else{
                            $group_by_identification_and_time[$single_person_operations_key][$single_week_person_operations_key][$single_person_operation_key]['include_commission_amount'] = (float)$single_person_operation['base_currency_amount'];
                        }

                        $count += 1;
                    }

                }
            }

            //return each single operation
            $no_group_data = [];
            foreach ($group_by_identification_and_time as $single_person_operations){
                foreach($single_person_operations as $single_week_operation){
                    foreach($single_week_operation as $single_operation){
                        $single_operation['commission_amount'] = ((float)$single_operation['include_commission_amount'] * $this->withdraw_private_commission_fee_percent)/ 100;

                        $no_group_data[$single_operation['index']] = $single_operation;
                        array_push($no_group_data, $single_operation);
                    }
                }
            }

            return $no_group_data;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }


    public function calculateDepositPrivate($data){
        try{
            $data_with_commission_amount = [];
            foreach ($data as $value){
                $value['include_commission_amount'] = (float)$value['base_currency_amount'];
                $value['commission_amount'] = ((float)$value['include_commission_amount'] * $this->deposit_commission_fee_percent)/ 100;
                $data_with_commission_amount[$value['index']] = $value;
            }

            return $data_with_commission_amount;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }


    public function calculateWithdrawBusiness($data){
        try{
            $data_with_commission_amount = [];
            foreach ($data as $value){
                $value['include_commission_amount'] = (float)$value['base_currency_amount'];
                $value['commission_amount'] = ((float)$value['include_commission_amount'] * $this->withdraw_business_commission_fee_percent)/ 100;
                $data_with_commission_amount[$value['index']] = $value;
            }

            return $data_with_commission_amount;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }

    public function calculateDepositBusiness($data){
        try{
            $data_with_commission_amount = [];
            foreach ($data as $value){
                $value['include_commission_amount'] = (float)$value['base_currency_amount'];
                $value['commission_amount'] = ((float)$value['include_commission_amount'] * $this->deposit_commission_fee_percent)/ 100;
                $data_with_commission_amount[$value['index']] = $value;
            }

            return $data_with_commission_amount;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }
}
