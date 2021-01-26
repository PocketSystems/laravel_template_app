<?php


namespace App\Http\Controllers\Modules\SuppliersAccount;

use App\Helpers\Helper;
use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Models\Ledger;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SuppliersAccountController extends ModuleController
{
    use DatatableTrait;

    public function __construct()
    {
        parent::__construct();
        $this->setModuleName("suppliersAccount");

    }
    public function getSupplier(): array
    {
        return Suppliers::where('is_archive', '=', '0')->where('status', '=', '1')->where('company_id',Auth::user()->company_id)->get()->toArray();

    }
    public function getMode(): array
    {
        return ['Cash','Card','Online'];

    }
    public function index()
    {
        $this->injectDatatable();

        $suppliers = $this->getSupplier();
        $mode = $this->getMode();
        return $this->view('index',['suppliers'=>$suppliers,'mode'=>$mode]);
    }
    public function add()
    {
        $customers = $this->getSupplier();
        $mode = $this->getMode();
        return $this->view('add',['suppliers'=>$customers,'mode'=>$mode]);
    }
    public function create(Request $request)
    {

        Validator::make($request->all(), [
            'supplier_id' => 'required',
            'mode' => 'required',
            'amount' => 'required|numeric|min:0|not_in:0',
        ])->validate();

        $ledger = new Ledger();
        //On left field name in DB and on right field name in Form/view
        $ledger->nature_id = $request->input('supplier_id');
        $ledger->mode = $request->input('mode');
        $ledger->type_id = 0;
        $ledger->amount = $request->input('amount');
        $ledger->balance = $request->input('balance');
        $ledger->description = $request->input('description');
        $ledger->date = date('Y-m-d',strtotime($request->input('date')));
        $ledger->type = 'payment';
        $ledger->nature = 'supplier';
        $ledger->account = 'debit';
        $ledger->user_id = Auth::user()->id;
        $ledger->company_id = Auth::user()->company_id;
        $ledger->save();
        if (!empty($request->input('saveClose'))) {
            return redirect()->route($this->mRoute('home'))->with('success', 'Payments Added Successfully!');
        } else {
            return redirect()->route('home')->with('success', 'Payments Added Successfully!');
        }

    }

    public function search(Request $request)
    {
        Validator::make($request->all(), [
            'supplier_id' => 'required',

        ])->validate();

        $this->injectDatatable();
        $suppliers = $this->getSupplier();

        $params = \request()->all();
        if(!empty($params)) {
            $purchaseBase = Ledger::where('type', 'purchase')->where('nature', 'supplier')->where('is_archive', 0)->where('company_id', Auth::user()->company_id);
            $purchaseQuery = $this->poQuery($purchaseBase, $params);
            $purchase = $purchaseQuery->sum('amount');
            $purchaseTotalGraph = json_encode($purchaseQuery->get(['amount'])->toArray());

            $paidBase = Ledger::where('type', 'payment')->where('nature', 'supplier')->where('is_archive', 0)->where('company_id', Auth::user()->company_id);
            $paidQuery = $this->poQuery($paidBase, $params);
            $paid = $paidQuery->sum('amount');
            $paidTotalGraph = json_encode($paidQuery->get(['amount'])->toArray());

            $balance = Ledger::where("nature_id",  $params['supplier_id'])->where('nature', 'supplier')->orderBy('id', 'desc')->get('balance')->first()['balance'];
            $balanceTotalGraph = Ledger::where("nature_id",  $params['supplier_id'])->where('nature', 'supplier')->orderBy('id', 'desc')->get('balance');
        }
        $data = [
            'purchase' => $purchase,
            'purchaseTotalGraph' => $purchaseTotalGraph,
            'paid' => $paid,
            'paidTotalGraph' => $paidTotalGraph,
            'balance' => $balance,
            'balanceTotalGraph' => $balanceTotalGraph,
            'suppliers' => $suppliers,

        ];

        return view('modules.suppliersAccount.index',(!empty($data)  ? $data :[] ));

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

        if (!empty($params['supplier_id'])) {
            $query = $query->where('nature_id', $params['supplier_id']);
        }
        return $query;
    }
    protected function getDataTableRows(): array
    {
        $params = \request()->all();
        if (!empty($params['_token'])) {
            $base = Ledger::where('nature','supplier')->where('is_archive', 0)->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC');
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
                }elseif ($row['type'] == 'purchase'){
                    return '<span class="badge badge-success">'.Helper::price($row['amount']).'</span>';
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
