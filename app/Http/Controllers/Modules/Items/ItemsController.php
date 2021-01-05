<?php


namespace App\Http\Controllers\Modules\Items;

use App\Http\Controllers\ModuleController;
use App\Helpers\Helper;
use App\Models\Categories;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class ItemsController extends ModuleController
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
        return Categories::all('name','id')->toArray();
    }
    public function add()
    {
        $categories = $this->getCategories();
        return $this->view('add',['categories'=>$categories]);
    }

    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
            'sku' => 'required',
            'category_id' => 'required',
        ])->validate();

        $items = new Items();
        //On left field name in DB and on right field name in Form/view
        $items->name = $request->input('name');
        $items->sku = $request->input('sku');
        $items->cost = $request->input('cost');
        $items->last_updated_cost = $request->input('cost');
        $items->last_updated_price = $request->input('price');
        $items->last_updated_stock = 0;
        $items->price = $request->input('price');
        $items->description = $request->input('description');
        $items->category_id = $request->input('category_id');
        $items->user_id = Auth::user()->id;
        $items->company_id = Auth::user()->company_id;


        if ($request->hasFile('image')) {
            $items->image = Helper::file_upload($request, 'image', 'items');
        }
        $items->save();
        if (!empty($request->input('saveClose'))) {
            return redirect()->route($this->mRoute('home'))->with('success', 'Item Created Successfully!');
        } else {
            return redirect()->route($this->mRoute('add'))->with('success', 'Item Created Successfully!');

        }

    }

    public function edit($id)
    {

        $data = Items::where('id', $id)->first();
        $categories = $this->getCategories();

        return $this->view('edit', ['data' => $data,'categories'=>$categories]);
    }

    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string',
            'sku' => 'required',
            'category_id' => 'required',
        ])->validate();
        $cdata = $request->except('_token', '_method');
        $cdata['user_id'] = Auth::user()->id;
        $cdata['company_id'] = Auth::user()->company_id;
        $old = Items::where('id', $cdata['id'])->first();
        if ($request->hasFile('image')) {
            if(!empty($old['image'])){
                $file = str_ireplace("storage/app/",'',$old['image']);
                if(Storage::exists($file)){
                    Storage::delete($file);
                }
            }
            $cdata['image'] = Helper::file_upload($request,'image','items');
        }
        Items::where('id', $cdata['id'])->update($cdata);
        return redirect()->route($this->mRoute('home'))->with('success', 'Items Updated Successfully!');
    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "sku"],
            ["data" => "category.name"],
            ["data" => "name"],
            ["data" => "cost"],
            ["data" => "price"],
            ["data" => "action", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                $statusFun = "change_status(" . $row["id"] . ",'" . route($this->mRoute('status'), [$row["id"],'status']) . "','" . csrf_token() . "',this)";
                $checkStatus = "" . ($row['status'] == 1 ? 'checked' : '') . "";
                $btn = '<input switch-button onchange="' . $statusFun . '" ' . $checkStatus . ' type="checkbox" >';
                return $btn;
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
        return (new Items())->getTable();
    }
    protected function getDataTableRows(): array
    {
        return Items::with('category')->where('is_archive', 0)->orderBy('id', 'DESC')->get()->toArray();
    }

}
