<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class Helper {

    public static function file_upload(Request $request, string $field_name, string $dir)
    {
        return "storage/app/".$request->file($field_name)->storePubliclyAs(
            $dir,$request->file($field_name)->hashName()
        );
    }

    public static function reqValue($key): string
    {
        if(empty(\request()->toArray()[$key])){
            return "";
        }
        return \request()->toArray()[$key];
    }
    public static function dates_month($month, $year,$format='d-M-Y') {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $mktime = mktime(0, 0, 0, $month, $i, $year);
            $date = date($format, $mktime);
            $dates_month[$i] = $date;
        }

        return $dates_month;
    }


    public static function rangeMonth (): array
    {
        $months = [];
        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            $months[]= $month;
        }
        return $months;
    }


}

?>
