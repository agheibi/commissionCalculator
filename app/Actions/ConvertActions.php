<?php

namespace App\Actions;

use Carbon\Carbon;

class ConvertActions
{
    private $columns;
    private $operation_date_format;
    private $operation_date_column_name;

    public function __construct(){
        $this->columns = explode(',', env('CSV_COLUMNS', 'date,identification_id,user_type,operation_type,operation_amount,operation_currency'));
        $this->operation_date_format = env('OPERATION_DATE_FORMAT', 'Y-m-d');
        $this->operation_date_column_name = env('OPERATION_DATE_COLUMN_NAME', 'date');
    }

    public function convertFileToArray($csv){
        try{
            $path = $csv->getRealPath();
            $records = file($path);
            $output = [];
            foreach ($records as $line_index => $line) {
                $line = preg_replace("/\r|\n/", "", $line);
                $line = str_replace('"', "", $line);

                $new_line = [];
                $values = explode(',', $line);
                foreach ($values as $col_index => $value) {
                    $new_line[$this->columns[$col_index]] = $value;
                }
                $output[] = $new_line;
            }

            return $output;


        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }

    public function roundUp($number, $currency)
    {
        if(gettype($number) == 'integer' || gettype($number) == 'double'){
            if($currency == 'JPY'){
                $result = ceil($number);
            }else{
                $pow = pow ( 10, 2 );
                $result =  ( ceil ( $pow * $number ) + ceil ( $pow * $number - ceil ( $pow * $number ) ) ) / $pow;
            }

            return $result;
        }else{
            throw new \Exception('Number must be integer or double');
        }
    }

    public function dateConverter($value, $index)
    {
        try{
            $transaction_day = Carbon::createFromFormat($this->operation_date_format, $value[$this->operation_date_column_name]);
            $value[$index] = $transaction_day;
            return $value;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }



}
