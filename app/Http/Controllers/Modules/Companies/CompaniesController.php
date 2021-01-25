<?php


namespace App\Http\Controllers\Modules\Companies;


use App\Helpers\Helper;
use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends ModuleController
{

    use DatatableTrait;

    public function __construct()
    {
        parent::__construct();
        $this->setModuleName('companies');
    }

    public function index()
    {
        $this->injectDatatable();
        return $this->view('index');
    }

    public function add()
    {
        return $this->view('add');
    }
    public function edit($id)
    {

        $data = Company::where('id', $id)->first();
        return $this->view('edit', ['data' => $data]);
    }

    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required',
            'email' => 'required|string',
            'address' => 'required|string',
        ])->validate();

        $company = new Company();
        //On left field name in DB and on right field name in Form/view
        $company->name = $request->input('name');
        $company->email = $request->input('email');
        $company->phone = $request->input('phone');
        $company->address = $request->input('address');
        $company->start_date = date('Y-m-d',strtotime($request->input('start_date')));
        $company->end_date = date('Y-m-d',strtotime($request->input('end_date')));



        if ($request->hasFile('logo')) {
            $company->logo = Helper::file_upload($request,'logo','company');
        }
        $company->save();
        if (!empty($request->input('saveClose'))) {
            return redirect()->route($this->mRoute('home'))->with('success', 'Company Created Successfully!');
        } else {
            return redirect()->route($this->mRoute('add'))->with('success', 'Company Created Successfully!');

        }

    }
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required',
            'email' => 'required|string',
            'address' => 'required|string',
        ])->validate();

        $cdata = $request->except('_token', '_method');
        $cdata['start_date'] = date('Y-m-d',strtotime($request->input('start_date')));
        $cdata['end_date'] = date('Y-m-d',strtotime($request->input('end_date')));
        $old = Company::where('id', $cdata['id'])->first();
        if ($request->hasFile('logo')) {
            if(!empty($old['logo'])){
                $file = str_ireplace("storage/app/",'',$old['logo']);
                if(Storage::exists($file)){
                    Storage::delete($file);
                }
            }

            $cdata['logo'] = Helper::file_upload($request,'logo','company');
        }
        Company::where('id', $cdata['id'])->update($cdata);

        return redirect()->route($this->mRoute('home'))->with('success', 'Company Updated Successfully!');

    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "name"],
            ["data" => "phone"],
            ["data" => "email"],
            ["data" => "action", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                //delete_row('.$row["id"].','.route('module.suppliers.delete',[$row["id"]]).')
                $statusFun = "change_status(" . $row["id"] . ",'" . route($this->mRoute('status'), [$row["id"],'status']) . "','" . csrf_token() . "',this)";
                $checkStatus = "" . ($row['status'] == 1 ? 'checked' : '') . "";
                $btn = '<input switch-button onchange="' . $statusFun . '" ' . $checkStatus . ' type="checkbox" >';
                return $btn;
            }],
            ["data" => "action1", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                //delete_row('.$row["id"].','.route('module.suppliers.delete',[$row["id"]]).')
                $deleteFun = "delete_row(" . $row["id"] . ",'" . route($this->mRoute('delete'), [$row["id"]]) . "','" . csrf_token() . "',this)";
                $btn = '<a href=' . route($this->mRoute('edit'), [$row['id']]) . '><i class="fas fa-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:" onclick="' . $deleteFun . '" style="color: red!important;"><i class="fas fa-trash"></i></a>';
                return $btn;
            }],
        ];
    }

    protected function getModuleTable() : string
    {
        return (new Company())->getTable();
    }

    protected function getDataTableRows(): array
    {
        return Company::where('is_archive', 0)->orderBy('id', 'DESC')->get()->toArray();
    }

}
