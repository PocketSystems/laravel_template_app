<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Expenses;
use App\Models\PurchaseOrders;
use App\Models\SaleOrders;
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
           $sale_purchase_comp[date('M',strtotime($value))]['so'] = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id',Auth::user()->company_id)->whereMonth('order_date',Carbon::parse($value)->month)->sum('grand_total');
           $sale_purchase_comp[date('M',strtotime($value))]['po'] = PurchaseOrders::where('is_archive', 0)->where('status', 1)->where('company_id',Auth::user()->company_id)->whereMonth('order_date',Carbon::parse($value)->month)->sum('grand_cost_total');
       }

        $sale_month_pie = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id',Auth::user()->company_id)->whereMonth('order_date',Carbon::parse(date('Y-m-d'))->month)->sum('grand_total');
        $purchase_month_pie = PurchaseOrders::where('is_archive', 0)->where('status', 1)->where('company_id',Auth::user()->company_id)->whereMonth('order_date',Carbon::parse(date('Y-m-d'))->month)->sum('grand_cost_total');
        $exp_month_pie = Expenses::where('is_archive', 0)->where('status', 1)->where('company_id',Auth::user()->company_id)->whereMonth('expense_date',Carbon::parse(date('Y-m-d'))->month)->sum('amount');

        $data =
           [
               'sale_month_pie'=>$sale_month_pie,
               'purchase_month_pie'=>$purchase_month_pie,
               'exp_month_pie'=>$exp_month_pie,
               'sale_purchase_comp'=>json_encode($sale_purchase_comp),
           ];
        return $data;

    }

    private function thirdBox(): array
    {
        $saleSumToday = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id)->where('order_date',date('Y-m-d'))->sum('grand_total');
        $saleSumWeek = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id)->whereBetween('order_date',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('grand_total');
        $saleSumMonth = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id)->whereMonth('order_date',Carbon::parse(date('Y-m-d'))->month)->sum('grand_total');
        $saleSumYear = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id)->whereYear('order_date',Carbon::parse(date('Y-m-d'))->year)->sum('grand_total');

        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        $start_week = date("Y-m-d",$start_week);
        $end_week = date("Y-m-d",$end_week);
        $saleSumTodayLast = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id)->where('order_date',date('Y-m-d',strtotime("-1 days")))->sum('grand_total');
        $saleSumWeekLast = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id)->whereBetween('order_date', [$start_week, $end_week])->sum('grand_total');
        $saleSumMonthLast = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id)->whereMonth('order_date',Carbon::now()->subMonth()->month)->sum('grand_total');
        $saleSumYearLast = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id)->whereYear('order_date',date('Y', strtotime('-1 year')))->sum('grand_total');

        $data =
            [
                'saleSumToday'=>$saleSumToday,
                'saleSumWeek'=>$saleSumWeek,
                'saleSumMonth'=>$saleSumMonth,
                'saleSumYear'=>$saleSumYear,
                'saleSumTodayLast'=>$saleSumTodayLast,
                'saleSumWeekLast'=>$saleSumWeekLast,
                'saleSumMonthLast'=>$saleSumMonthLast,
                'saleSumYearLast'=>$saleSumYearLast,
                'todayComp'=>($saleSumToday > $saleSumTodayLast ? 1 : 0),
                'weekComp'=>($saleSumWeek > $saleSumWeekLast ? 1 : 0),
                'monthComp'=>($saleSumMonth > $saleSumMonthLast ? 1 : 0),
                'yearComp'=>($saleSumYear > $saleSumYearLast ? 1 : 0),
            ];
        return $data;
    }

    private function topBox(): array
    {
        $purchaseSum = PurchaseOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id);
        $saleSum = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id);
        $saleItem = SaleOrders::where('is_archive', 0)->where('status', 1)->where('company_id', Auth::user()->company_id);
        $expenseSum = Expenses::where('is_archive', 0)->where('company_id', Auth::user()->company_id);
        $saleSumGraph = json_encode($saleSum->get(['grand_total'])->toArray());
        $purchaseSumGraph = json_encode($purchaseSum->get(['grand_cost_total'])->toArray());
        $expSumGraph = json_encode($expenseSum->get(['amount'])->toArray());
//        dd($saleCountGraph);
        $purchase = $purchaseSum->sum('grand_cost_total');
        $sale = $saleSum->sum('grand_total');
        $saleI = $saleItem->sum('count');
        $exp = $expenseSum->sum('amount');
        $data = [
            'purchaseSum' => $purchase,
            'saleSum' => $sale,
            'saleItem' => $saleI,
            'expenseSum' => $exp,
            'grossProfit' => $sale - $purchase,
            'netProfit' => ($sale - $purchase) - $exp,
            'saleSumGraph' => $saleSumGraph,
            'purchaseSumGraph' => $purchaseSumGraph,
            'expSumGraph' => $expSumGraph
        ];

        return $data;
    }

}
