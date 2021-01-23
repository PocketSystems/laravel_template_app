<?php


namespace App\Http\Controllers\Modules\Reports;

use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SubModuleTrait;
use App\Models\Expenses;
use App\Models\PurchaseOrders;
use App\Models\SaleOrders;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfitLossReport extends ModuleController
{

    use SubModuleTrait{
        SubModuleTrait::__construct as subModuleConstructor;
    }

    public function __construct()
    {
        parent::__construct();
        $this->subModuleConstructor();
        $this->setModuleName("reports");
    }

    public function index()
    {
        return $this->view('profit_loss_report');
    }

    public function search(Request $request)
    {
        Validator::make($request->all(), [
            'from_date' => 'required',
            'to_date' => 'required',
        ], [], [
            'from_date' => 'From Date',
            'to_date' => 'to Date',

        ])->validate();

        $params = \request()->all();
        $data_all = [];
        $total_all = [
            "saleTotal"=>0,
            "discountTotal"=>0,
            "costTotal"=>0,
            "expenseTotal"=>0,
            "grossProfitTotal"=>0,
            "netProfitTotal"=>0,
        ];
        $from = date('Y-m-d',strtotime($params['from_date']));
        $to = date('Y-m-d',strtotime($params['to_date']));
        $months = CarbonPeriod::create($from, '1 month', $to);
        if(sizeof($months) > 5){
            return redirect()->back()->withInput()->with('error', 'you can only select 6 Months!');

        }
        foreach ($months as $key => $dt) {
            /*          echo Carbon::parse($dt)->month.'<br>';*/
            /*     echo $dt.'<br>';
                 echo $dt->format("Y-m").'<br>';*/
            $data_all[$key]['sale'] = SaleOrders::where('is_archive', 0)->where('status', 1)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->whereYear('order_date', Carbon::parse($dt)->year)->whereMonth('order_date', Carbon::parse($dt)->month)->sum('grand_total');
            $data_all[$key]['discount'] = SaleOrders::where('is_archive', 0)->where('status', 1)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->whereYear('order_date', Carbon::parse($dt)->year)->whereMonth('order_date', Carbon::parse($dt)->month)->sum('discount_total');
            $data_all[$key]['cost'] = PurchaseOrders::where('is_archive', 0)->where('status', 1)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->whereYear('order_date', Carbon::parse($dt)->year)->whereMonth('order_date', Carbon::parse($dt)->month)->sum('grand_cost_total');
            $data_all[$key]['expense'] = Expenses::where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->whereYear('expense_date', Carbon::parse($dt)->year)->whereMonth('expense_date', Carbon::parse($dt)->month)->sum('amount');
            $data_all[$key]['gross_profit'] =   ($data_all[$key]['sale']-$data_all[$key]['discount'])-$data_all[$key]['cost'];
            $data_all[$key]['net_profit'] =      $data_all[$key]['gross_profit']-$data_all[$key]['expense'];
            $total_all['grossProfitTotal'] +=$data_all[$key]['gross_profit'];
            $total_all['netProfitTotal'] += $data_all[$key]['net_profit'];
            $total_all['saleTotal'] +=$data_all[$key]['sale'];
            $total_all['discountTotal'] +=$data_all[$key]['discount'];
            $total_all['costTotal'] +=$data_all[$key]['cost'];
            $total_all['expenseTotal'] +=$data_all[$key]['expense'];
        }



        $data = [
            'months' => $months,
            'data_all' => $data_all,
            'total_all' => $total_all,
        ];

        return view('modules.reports.profit_loss_report', $data);

    }

    protected function getModuleTable(): string
    {
        return  "";
    }
}
