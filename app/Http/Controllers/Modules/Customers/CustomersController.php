<?php


namespace App\Http\Controllers\Modules\Customers;

use App\Http\Controllers\ModuleController;
use App\Helpers\Helper;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CustomersController extends ModuleController
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

    public function add()
    {
        return $this->view('add');
    }

    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
        ])->validate();

        $Customers = new Customers();
        //On left field name in DB and on right field name in Form/view
        $Customers->name = $request->input('name');
        $Customers->email = $request->input('email');
        $Customers->phone = $request->input('phone');
        $Customers->address = $request->input('address');
        $Customers->user_id = Auth::user()->id;
        $Customers->company_id = Auth::user()->company_id;


        if ($request->hasFile('image')) {
            $Customers->image = Helper::file_upload($request,'image','Customers');
        }
        $Customers->save();
        if (!empty($request->input('saveClose'))) {
            return redirect()->route($this->mRoute('home'))->with('success', 'Customer Created Successfully!');
        } else {
            return redirect()->route($this->mRoute('add'))->with('success', 'Customer Created Successfully!');
        }

    }

    public function edit($id)
    {

        $data = Customers::where('id', $id)->first();
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
        $old = Customers::where('id', $cdata['id'])->first();
        if ($request->hasFile('image')) {
            if(!empty($old['image'])){
                $file = str_ireplace("storage/app/",'',$old['image']);
                if(Storage::exists($file)){
                    Storage::delete($file);
                }
            }

            $cdata['image'] = Helper::file_upload($request,'image','Customers');
        }
        Customers::where('id', $cdata['id'])->update($cdata);

        return redirect()->route($this->mRoute('home'))->with('success', 'Customer Updated Successfully!');

    }

    protected function getDataTableRows(): array
    {
        return Customers::where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->get()->toArray();
    }



    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "name"],
            ["data" => "phone"],
            ["data" => "email"],
            ["data" => "action", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                //delete_row('.$row["id"].','.route('module.Customers.delete',[$row["id"]]).')
                $statusFun = "change_status(" . $row["id"] . ",'" . route($this->mRoute('status'), [$row["id"],'status']) . "','" . csrf_token() . "',this)";
                $checkStatus = "" . ($row['status'] == 1 ? 'checked' : '') . "";
                $btn = '<input switch-button onchange="' . $statusFun . '" ' . $checkStatus . ' type="checkbox" >';
                return $btn;
            }],
            ["data" => "action1", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                //delete_row('.$row["id"].','.route('module.Customers.delete',[$row["id"]]).')
                $deleteFun = "delete_row(" . $row["id"] . ",'" . route($this->mRoute('delete'), [$row["id"]]) . "','" . csrf_token() . "',this)";
                $btn = '<a href=' . route($this->mRoute('edit'), [$row['id']]) . '><i class="fas fa-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:" onclick="' . $deleteFun . '" style="color: red!important;"><i class="fas fa-trash"></i></a>';
                return $btn;
            }],
        ];
    }

    protected function getModuleTable() : string
    {
        return (new Customers())->getTable();
    }

}
