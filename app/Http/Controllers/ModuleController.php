<?php


namespace App\Http\Controllers;

use App\Providers\CustomDatatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Services\DataTable;

abstract class ModuleController extends AuthenticatedController
{
    protected abstract function getDataTableColumns() : array;
    protected abstract function getDataTableRows() : array;
    protected abstract function getModuleTable() : string;
    private $moduleName = null;

    public function __construct()
    {
        parent::__construct();
        $this->moduleName = \request()->segment(2);
        View::share('moduleName', $this->moduleName);
    }

    protected function setModuleName($moduleName){
        $this->moduleName = $moduleName;
        View::share('moduleName', $this->moduleName);
    }

    protected function view($viewName,$data = []){
        return view('modules.'.ucfirst($this->moduleName).'.'.$viewName,$data);
    }

    protected function mRoute($routeName){
        return 'module.'.$this->moduleName.'.'.$routeName;
    }

    protected function injectDatatable(){
        View::share('dataTableColumns', json_encode($this->getDataTableColumns()));
    }

    private function exportDatable(){

    }
    public final function datatable(){
        $dt = DataTables::of($this->getDataTableRows())
            ->addIndexColumn();
//        $dt->html();
        $actions = [];
        foreach ($this->getDataTableColumns() as $column){
            if(!empty($column['onAction'])){
                $dt->addColumn($column['data'],$column['onAction']);
                $actions[] = $column['data'];
            }
        }
        $dt->rawColumns($actions);
        return $dt->make(true);
    }

    public function deleteFile(Request $request,$id,$field): array
    {
        $file = str_ireplace("storage/app/",'',$request->all()[$field]);
        if(Storage::exists($file)){
            Storage::delete($file);
        }
        DB::table($this->getModuleTable())->where('id', $id)->update([$field=>'']);
        return ['success' => 1];
    }

    public function status(Request $request,$id,$field = "status"): array
    {
        $data =  DB::table($this->getModuleTable())->where('id', $id)->get()->first();
        if(!empty($data)){
            $data = (array)$data;
            DB::table($this->getModuleTable())->where('id', $id)->update([$field => ($data[$field] == 1 ? 0 : 1)]);
        }
        return ['success' => 1];
    }

    public function delete($id)
    {
        DB::table($this->getModuleTable())->where('id', $id)->update(['is_archive' => 1]);
        return \response()->json([
            "status" => "SUCCESS"
        ]);
    }

}
