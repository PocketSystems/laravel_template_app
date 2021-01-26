<?php


namespace App\Http\Controllers\Modules\ExpenseCategories;


use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Models\ExpenseCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoriesController extends ModuleController
{
    use DatatableTrait;

    public function __construct()
    {
        parent::__construct();
        $this->setModuleName("expenseCategories");
    }

    public function index()
    {
        $this->injectDatatable();
        return $this->view('index');
    }

    public function add()
    {
        return$this->view('add');
    }

    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
        ])->validate();

        $expenseCat = new ExpenseCategories();
        //On left field name in DB and on right field name in Form/view
        $expenseCat->name = $request->input('name');
        $expenseCat->user_id = Auth::user()->id;
        $expenseCat->company_id = Auth::user()->company_id;
        $expenseCat->save();

        if (!empty($request->input('saveClose'))) {
            return redirect()->route($this->mRoute('home'))->with('success', 'Expense Category Created Successfully!');
        } else {
            return redirect()->route($this->mRoute('add'))->with('success', 'Expense Category Created Successfully!');

        }

    }
    public function edit($id)
    {
        $data = ExpenseCategories::where('id', $id)->first();
        return $this->view('edit', ['data' => $data]);
    }

    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
        ])->validate();

        $cdata = $request->except('_token', '_method');
        $cdata['user_id'] = Auth::user()->id;
        $cdata['company_id'] = Auth::user()->company_id;

        ExpenseCategories::where('id', $cdata['id'])->update($cdata);
        return redirect()->route($this->mRoute('home'))->with('success', 'Expense Category Updated Successfully!');
    }


    protected function getDataTableRows(): array
    {
        return ExpenseCategories::where('is_archive', 0)->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->get()->toArray();
    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "name"],
            ["data" => "action", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                //delete_row('.$row["id"].','.route('module.categories.delete',[$row["id"]]).')
                $statusFun = "change_status(" . $row["id"] . ",'" . route($this->mRoute('status'), [$row["id"], 'status']) . "','" . csrf_token() . "',this)";
                $checkStatus = "" . ($row['status'] == 1 ? 'checked' : '') . "";
                $btn = '<input switch-button onchange="' . $statusFun . '" ' . $checkStatus . ' type="checkbox" >';
                return $btn;
            }],
            ["data" => "action1", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                //delete_row('.$row["id"].','.route('module.categories.delete',[$row["id"]]).')
                $deleteFun = "delete_row(" . $row["id"] . ",'" . route($this->mRoute('delete'), [$row["id"]]) . "','" . csrf_token() . "',this)";
                $btn = '<a href=' . route($this->mRoute('edit'), [$row['id']]) . '><i class="fas fa-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:" onclick="' . $deleteFun . '" style="color: red!important;"><i class="fas fa-trash"></i></a>';
                return $btn;
            }],
        ];
    }

    protected function getModuleTable(): string
    {
        return (new ExpenseCategories())->getTable();
    }
}
