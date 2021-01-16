<?php


namespace App\Http\Controllers\Modules\Reports;


use App\Http\Controllers\ModuleController;
use App\Models\Customers;
use App\Models\PurchaseOrders;
use App\Models\SaleOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use League\CommonMark\Inline\Element\Emphasis;

class SaleOrderReport extends ModuleController
{
    public $record = [];

    public function __construct()
    {
        parent::__construct();
        View::share('controllerName', \request()->segment(2));
    }

    public function getStatus(): array
    {
        $status = ['1' => 'Confirm', '2' => 'Pending'];
        return $status;
    }

    public function getCustomers(): array
    {
        return Customers::where('is_archive', '=', '0')->where('status', '=', '1')->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->get(['name', 'id'])->toArray();
    }

    public function index()
    {

        $status = $this->getStatus();
        $customers = $this->getCustomers();
        $this->injectDatatable();
        return view('modules.reports.sale_order_report', ['status' => $status, 'customers' => $customers]);
    }

    public function search(Request $request)
    {
        $status = $this->getStatus();
        $customers = $this->getCustomers();
        $this->injectDatatable();
        $params = \request()->all();
        $base = SaleOrders::with('customer')->where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id);
        $query = $this->poQuery($base, $params);

        $sumTotal = $query->sum('grand_total');
        $sumTotalGraph = json_encode($query->get(['grand_total'])->toArray());
        $sumItems = $query->sum('count');
        $sumCountGraph = json_encode($query->get(['count'])->toArray());
        $queryConfirmStatus = $this->poQuery($base, $params, 1);
        $cItem = $queryConfirmStatus->count();

        $queryPendingStatus = $this->poQuery($base, $params, 2);
        $pItem = $queryPendingStatus->count();

        $data = [
            'sumTotalGraph' => $sumTotalGraph,
            'sumCountGraph' => $sumCountGraph,
            'status' => $status,
            'customers' => $customers,
            'grand_total' => $sumTotal,
            'count' => $sumItems,
            'cItem' => $cItem,
            'pItem' => $pItem
        ];
        return view('modules.reports.sale_order_report', $data);
    }

    public function poQuery($query, $params, $status = null)
    {
        if (!empty($params['from_date']) && !empty($params['to_date'])) {
            $from = date('Y-m-d', strtotime($params['from_date']));
            $to = date('Y-m-d', strtotime($params['to_date']));
            $query = $query->whereBetween('order_date', [$from, $to]);
        } elseif (!empty($params['to_date'])) {
            $to = date('Y-m-d', strtotime($params['to_date']));
            $query = $query->where('order_date', '<=', $to);
        }
        if (!empty($status)) {
            $query = $query->where('status', $status);
        }elseif (!empty($params['status'])) {
            $query = $query->where('status', $params['status']);
        }
        if (!empty($params['customer_id'])) {
            $query = $query->where('customer_id', $params['customer_id']);
        }
        return $query;
    }

    protected function getDataTableRows(): array
    {
        $params = \request()->all();
        if (!empty($params['_token'])) {
            $base = SaleOrders::with('customer')->where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->orderBy('order_date', 'ASC');
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
            ["data" => "order_date", "onAction" => function ($row) {
                return date('m/d/Y', strtotime($row['order_date']));
            }],
            ["data" => "customer.name"],
            ["data" => "grand_total"],
            ["data" => "count"],
            ["data" => "action", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                $html = '';
                if ($row['status'] == 1) {
                    $html = "<i style='color: #0d9448' class='fas fa-check'></i> Confirmed";
                } elseif ($row['status'] == 2) {
                    $html = "<i style='color: #edb100' class='fas fa-bell'></i> Pending";
                }
                return $html;
            }]
        ];
    }

    protected function getModuleTable(): string
    {
        return (new SaleOrders())->getTable();
    }
}
