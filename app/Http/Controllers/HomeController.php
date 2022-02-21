<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends AuthenticatedController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $secondBox = $this->secondBox();
        $topBox = $this->topBox();
        $thirdBox = $this->thirdBox();
        return view('dashboard', ['topBox' => $topBox,'secondBox'=>$secondBox,'thirdBox'=>$thirdBox]);

    }
    private function secondBox(): array
    {
       $dates_month = Helper::rangeMonth();
        $sale_purchase_comp = [];
       foreach ($dates_month as $key => $value){
           $sale_purchase_comp[date('M',strtotime($value))] = [];
       }

//       dd($sale_purchase_comp);


        $data =
           [
               'sale_purchase_comp'=>json_encode($sale_purchase_comp),
           ];
        return $data;

    }

    private function thirdBox(): array
    {

        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        $start_week = date("Y-m-d",$start_week);
        $end_week = date("Y-m-d",$end_week);

        $data =
            [
            ];
        return $data;
    }

    private function topBox(): array
    {
        $data = [
        ];

        return $data;
    }

}
