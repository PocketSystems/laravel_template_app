<?php


namespace App\Http\Controllers\Modules\Reports;


use App\Helpers\Helper;
use App\Http\Controllers\ReportModuleController;
use App\Models\ExpenseCategories;
use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseReport extends ReportModuleController
{
    public $record = [];

    public function __construct()
    {
        parent::__construct();
    }


    public function getCategories(): array
    {
        return ExpenseCategories::where('is_archive', '=', '0')->where('status', '=', '1')->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->get(['name', 'id'])->toArray();
    }

    public function index()
    {
        $categories = $this->getCategories();
        $this->injectDatatable();
        return view('modules.reports.expense_report', [ 'categories' => $categories]);
    }

    public function search(Request $request)
    {
        $categories = $this->getCategories();
        $this->injectDatatable();
        $params = \request()->all();
        $base = Expenses::with('expense_category')->where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id);
        $query = $this->poQuery($base, $params);

        $sumTotal = $query->sum('amount');
        $sumTotalGraph = json_encode($query->get(['amount'])->toArray());
        $category_amount = [];
        $categories_amount = $query->groupBy('expense_category_id')->get(['expense_category_id'])->toArray();
        foreach ($categories_amount as $key => $category){
            $inner = Expenses::where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id);
            $inner = $this->poQuery($inner, $params);
            $amount = $inner->where('expense_category_id',$category['expense_category_id']);

            $total = $amount->sum('amount');
            $category_amount[$key]['category']=$category['expense_category']['name'];
            $category_amount[$key]['amount']=$total;

        }

//        $sumItems = $query->sum('count');
//        $sumCountGraph = json_encode($query->get(['count'])->toArray());


   /*     $queryConfirmStatus = $this->poQuery($base, $params, 1);
        $cItem = $queryConfirmStatus->count();*/

       /* $queryPendingStatus = $this->poQuery($base, $params, 2);
        $pItem = $queryPendingStatus->count();*/


        $data = [
            'sumTotalGraph' => $sumTotalGraph,
            'category_amount' => $category_amount,
            'categories' => $categories,
            'grand_total' => $sumTotal,
//            'count' => $sumItems,
//            'cItem' => $cItem,
//            'pItem' => $pItem
        ];

        return view('modules.reports.expense_report', $data);

    }

    public function poQuery($query, $params)
    {
        if (!empty($params['from_date']) && !empty($params['to_date'])) {
            $from = date('Y-m-d', strtotime($params['from_date']));
            $to = date('Y-m-d', strtotime($params['to_date']));
            $query = $query->whereBetween('expense_date', [$from, $to]);
        } elseif (!empty($params['to_date'])) {
            $to = date('Y-m-d', strtotime($params['to_date']));
            $query = $query->where('expense_date', '<=', $to);

        }

        if (!empty($params['category_id'])) {
            $query = $query->where('category_id', $params['category_id']);
        }
        return $query;
    }

    protected function getDataTableRows(): array
    {
        $params = \request()->all();
        if (!empty($params['_token'])) {
            $base = Expenses::with('expense_category')->where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC');
            $query = $this->poQuery($base, $params);
            return $query->get()->toArray();
        } else {
            return [];
        }
    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "expense_date", "onAction" => function ($row) {
                return date('m/d/Y', strtotime($row['expense_date']));
            }],
            ["data" => "expense_category.name"],
            ["data" => "amount","onAction"=>function($row){
                return Helper::price($row['amount']);
            }],

        ];
    }

    protected function getModuleTable(): string
    {
        return (new Expenses())->getTable();
    }
}
