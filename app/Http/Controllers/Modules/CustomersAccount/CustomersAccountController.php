<?php


namespace App\Http\Controllers\Modules\CustomersAccount;


use App\Helpers\Helper;
use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Models\Customers;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomersAccountController extends ModuleController
{
    use DatatableTrait;

    public function __construct()
    {
        parent::__construct();
        $this->setModuleName("customersAccount");

    }
    public function getCustomers(): array
    {
        return Customers::where('is_archive', '=', '0')->where('status', '=', '1')->where('company_id',Auth::user()->company_id)->get()->toArray();

    }
    public function getMode(): array
    {
        return ['Cash','Card','Online'];

    }
    public function index()
    {
        $this->injectDatatable();
        $customers = $this->getCustomers();
        return $this->view('index',['customers'=>$customers]);
    }
    public function add()
    {
        $customers = $this->getCustomers();
        $mode = $this->getMode();
        return $this->view('add',['customers'=>$customers,'mode'=>$mode]);
    }
    public function create(Request $request)
    {

        Validator::make($request->all(), [
            'customer_id' => 'required',
            'mode' => 'required',
            'amount' => 'required|numeric|min:0|not_in:0',
        ])->validate();

        $ledger = new Ledger();
        //On left field name in DB and on right field name in Form/view
        $ledger->nature_id = $request->input('customer_id');
        $ledger->type_id = 0;
        $ledger->mode = $request->input('mode');
        $ledger->amount = $request->input('amount');
        $ledger->balance = $request->input('balance');
        $ledger->description = $request->input('description');
        $ledger->date = date('Y-m-d',strtotime($request->input('date')));
        $ledger->type = 'payment';
        $ledger->nature = 'customer';
        $ledger->account = 'credit';
        $ledger->user_id = Auth::user()->id;
        $ledger->company_id = Auth::user()->company_id;
        $ledger->save();
        if (!empty($request->input('saveClose'))) {
            return redirect()->route('module.customersAccount.home')->with('success', 'Payments Added Successfully!');
        } else {
            return redirect()->route('module.customersAccount.home')->with('success', 'Payments Added Successfully!');
        }

    }



    public function search(Request $request)
    {
        Validator::make($request->all(), [
            'customer_id' => 'required',

        ])->validate();

        $this->injectDatatable();
        $customers = $this->getCustomers();

        $params = \request()->all();
        if(!empty($params)) {
            $soldBase = Ledger::where('type', 'sale')->where('nature', 'customer')->where('is_archive', 0)->where('company_id', Auth::user()->company_id);
            $soldQuery = $this->poQuery($soldBase, $params);
            $sold = $soldQuery->sum('amount');
            $soldTotalGraph = json_encode($soldQuery->get(['amount'])->toArray());

            $receiveBase = Ledger::where('type', 'payment')->where('nature', 'customer')->where('is_archive', 0)->where('company_id', Auth::user()->company_id);
            $receiveQuery = $this->poQuery($receiveBase, $params);
            $receive = $receiveQuery->sum('amount');
            $receiveTotalGraph = json_encode($receiveQuery->get(['amount'])->toArray());

            $balance = Ledger::where("nature_id",  $params['customer_id'])->where('nature', 'customer')->orderBy('id', 'desc')->get('balance')->first()['balance'];
            $balanceTotalGraph = Ledger::where("nature_id",  $params['customer_id'])->where('nature', 'customer')->orderBy('id', 'desc')->get('balance');
        }
        $data = [
              'sold' => $sold,
              'soldTotalGraph' => $soldTotalGraph,
              'receive' => $receive,
              'receiveTotalGraph' => $receiveTotalGraph,
              'balance' => $balance,
              'balanceTotalGraph' => $balanceTotalGraph,
              'customers' => $customers,

          ];
        return $this->view('index',(!empty($data)  ? $data :[] ));


    }
    public function poQuery($query, $params)
    {
        if (!empty($params['from_date']) && !empty($params['to_date'])) {
            $from = date('Y-m-d', strtotime($params['from_date']));
            $to = date('Y-m-d', strtotime($params['to_date']));
            $query = $query->whereBetween('date', [$from, $to]);
        } elseif (!empty($params['to_date'])) {
            $to = date('Y-m-d', strtotime($params['to_date']));
            $query = $query->where('date', '<=', $to);
        }

        if (!empty($params['customer_id'])) {
            $query = $query->where('nature_id', $params['customer_id']);
        }
        return $query;
    }

    protected function getDataTableRows(): array
    {
        $params = \request()->all();
        if (!empty($params['_token'])) {
            $base = Ledger::where('nature','customer')->where('is_archive', 0)->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC');
            $query = $this->poQuery($base, $params);
            return $query->get()->toArray();
        } else {
            return [];
        }
    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "date", "onAction" => function ($row) {
                return date('m/d/Y', strtotime($row['date']));
            }],
            ["data" => "type"],
            ["data" => "mode"],
            ["data" => "description"],
            ["data" => "amount","onAction"=>function($row){
                if($row['type'] == 'payment'){
                    return '<span class="badge badge-danger">- '.Helper::price($row['amount']).'</span>';
                }elseif ($row['type'] == 'sale'){
                    return '<span class="badge badge-success">'.Helper::price($row['amount']).'</span>';
                }else{
                    return  "";
                }
            }],
            ["data" => "balance","onAction"=>function($row){

                    return '<span class="badge badge-info">'.Helper::price($row['balance']).'</span>';

            }],

        ];
    }

    protected function getModuleTable(): string
    {
        return (new Ledger())->getTable();
    }

}
