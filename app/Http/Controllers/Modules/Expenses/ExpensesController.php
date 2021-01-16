<?php


namespace App\Http\Controllers\Modules\Expenses;


use App\Http\Controllers\ModuleController;
use App\Models\ExpenseCategories;
use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ExpensesController extends ModuleController
{
    public function __construct()
    {
        parent::__construct();
        View::share('moduleName', \request()->segment(2));
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
        return$this->view('add',['categories'=>$categories]);
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
            ["data" => "expense_category.name"],
            ["data" => "amount"],
            ["data" => "expense_date","onAction" => function ($row) {
            return date('m/d/Y',strtotime($row['expense_date']));
            }],
            ["data" => "action1", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                $deleteFun = "delete_row(" . $row["id"] . ",'" . route($this->mRoute('delete'), [$row["id"]]) . "','" . csrf_token() . "',this)";
                $btn = '<a href=' . route($this->mRoute('edit'), [$row['id']]) . '><i class="fas fa-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:" onclick="' . $deleteFun . '" style="color: red!important;"><i class="fas fa-trash"></i></a>';
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
