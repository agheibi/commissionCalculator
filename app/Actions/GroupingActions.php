<?php


namespace App\Actions;

class GroupingActions
{
    private $single_indexed;
    private $total_indexed = [];
    private $user_types_trans = [];
    private $user_type_column_name;
    private $operation_and_user_type_trans = [];
    private $operation_type_column_name;
    private $user_id_group = [];
    private $identification_id_column_name;
    private $calculate_actions;
    private $convert_actions;

    public function __construct()
    {
        $this->user_type_column_name = env('USER_TYPE_COLUMN_NAME');
        $this->operation_type_column_name = env('OPERATION_TYPE_COLUMN_NAME');
        $this->identification_id_column_name = env('IDENTIFICATION_ID_COLUMN_NAME');
        $this->calculate_actions = new CalculateActions();
        $this->convert_actions = new ConvertActions();
    }

    public function groupData($data)
    {
        try{
            $indexed_data = $this->indexingInfo($data);

            $group_by_user_type = $this->groupByUserType($indexed_data);

            $group_by_operation_and_user_type = $this->groupByOperationType($group_by_user_type);

            return $group_by_operation_and_user_type;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }

    private function indexingInfo($data){
        try{
            foreach ($data as $key => $value){
                $this->single_indexed = $value;
                $this->single_indexed['index'] = $key;
                $this->single_indexed = $this->convert_actions->dateConverter($this->single_indexed, 'transaction_date');
                $this->single_indexed['base_currency_amount'] = $this->calculate_actions->convertAmountToBase($this->single_indexed);
                array_push($this->total_indexed, $this->single_indexed);
            }
            return $this->total_indexed;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }


    private function groupByUserType($data){
        try{
            foreach ($data as $value){
                $this->user_types_trans[$value[$this->user_type_column_name]][]=$value;
            }

            return $this->user_types_trans;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }

    private function groupByOperationType($data){
        try{
            foreach ($data as $value_key => $value){
                foreach ($value as $item_key => $item){
                    $this->operation_and_user_type_trans[$value_key][$item[$this->operation_type_column_name]][] = $item;
                }
            }
            return $this->operation_and_user_type_trans;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }

    public function groupByIdentificationId($data){
        try{
            foreach ($data as $value){
                $this->user_id_group[$value[$this->identification_id_column_name]][] =$value;
            }

            return $this->user_id_group;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }

    public function groupByWeekTransactions($data)
    {
        try{
            $single_group = [];
            foreach ($data as $value){
                $weekend_date = $this->calculate_actions->calculateEndWeekFormatted($value);
                $single_group[$weekend_date][] = $value;
            }
            return $single_group;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
    }

}
