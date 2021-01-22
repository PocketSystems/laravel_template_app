<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\View;

abstract class ReportModuleController extends ModuleController
{
    public function __construct()
    {
        parent::__construct();
        $cls = explode("\\",get_called_class());
        View::share('controllerName', $cls[count($cls)-1]);
    }
}
