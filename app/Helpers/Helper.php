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

}

?>
