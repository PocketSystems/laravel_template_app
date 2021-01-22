<?php


namespace App\Http\Controllers\Modules\Expenses;


use App\Helpers\Helper;
use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Models\ExpenseCategories;
use App\Models\Expenses;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpensesController extends ModuleController
{


    use DatatableTrait;

    public function __construct()
    {
        parent::__construct();
        $this->setModuleName("expenses");
    }

    public function getMode(): array
    {
        return ['Cash','Card','Online'];

    }
    public function index()
    {
        $this->injectDatatable();
        return $this->view('index');
    }
    public function getCategories():array{
        return ExpenseCategories::select('name','id')->where('status','=',1)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->get()->toArray();
    }
    public function add()
    {
        $categories = $this->getCategories();
        $mode = $this->getMode();
        return$this->view('add',['categories'=>$categories,'mode'=>$mode]);
    }
    public function edit($id)
    {
        $data = Expenses::where('id', $id)->first();
        $categories = $this->getCategories();
        return $this->view('edit', ['data' => $data,'categories'=>$categories]);
    }
    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'expense_category_id' => 'required',
            'amount' => 'required',
            'expense_date' => 'required',
            'mode' => 'required',
        ])->validate();

        $expense = new Expenses();
        //On left field name in DB and on right field name in Form/view
        $expense->expense_category_id = $request->input('expense_category_id');
        $expense->amount = $request->input('amount');
        $expense->description = $request->input('description');
        $expense->expense_date = date('Y-m-d',strtotime($request->input('expense_date')));
        $expense->user_id = Auth::user()->id;
        $expense->company_id = Auth::user()->company_id;
        $expense->save();
        $id = $expense->id;

        $ledger = new Ledger();
        $ledger->nature_id = $id;
        $ledger->type_id = 0;
        $ledger->date = date('Y-m-d', strtotime($request->input('expense_date')));
        $ledger->mode = $request->input('mode');
        $ledger->amount = $expense->amount;
        $ledger->balance = 0;
        $ledger->description = $request->input('description');
        $ledger->type = 'payment';
        $ledger->nature = 'expense';
        $ledger->account = 'debit';
        $ledger->user_id = Auth::user()->id;
        $ledger->company_id = Auth::user()->company_id;
        $ledger->save();
        if (!empty($request->input('saveClose'))) {
            return redirect()->route($this->mRoute('home'))->with('success', 'Expense Added Successfully!');
        } else {
            return redirect()->route($this->mRoute('add'))->with('success', 'Expense Added Successfully!');

        }

    }
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'expense_category_id' => 'required',
            'amount' => 'required',
            'expense_date' => 'required',
        ])->validate();

        $cdata = $request->except('_token', '_method');
        $cdata['expense_date'] = date('Y-m-d',strtotime($request->input('expense_date')));
        $cdata['user_id'] = Auth::user()->id;
        $cdata['company_id'] = Auth::user()->company_id;
        Expenses::where('id', $cdata['id'])->update($cdata);
        return redirect()->route($this->mRoute('home'))->with('success', 'Expense Updated Successfully!');
    }


    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "expense_date","onAction" => function ($row) {
                return date('m/d/Y',strtotime($row['expense_date']));
            }],
            ["data" => "expense_category.name"],
            ["data" => "amount","onAction"=>function($row){
                return Helper::price($row['amount']);
            }],
            ["data" => "action1", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                $deleteFun = "delete_row(" . $row["id"] . ",'" . route($this->mRoute('delete'), [$row["id"]]) . "','" . csrf_token() . "',this)";
                $btn = '<a href="javascript:" onclick="' . $deleteFun . '" style="color: red!important;"><i class="fas fa-trash"></i></a>';
                return $btn;
            }],
        ];
    }

    protected function getModuleTable() : string
    {
        return (new Expenses())->getTable();
    }
    protected function getDataTableRows(): array
    {
        return Expenses::with('expense_category')->where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->get()->toArray();
    }


}
