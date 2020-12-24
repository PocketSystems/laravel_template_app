<?php


namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

abstract class ModuleController extends AuthenticatedController
{
    protected abstract function getDataTableColumns() : array;
    protected abstract function getDataTableRows() : array;
    protected abstract function getModuleTable() : string;

    protected function injectDatatable(){
        View::share('dataTableColumns', json_encode($this->getDataTableColumns()));
    }

    public final function datatable(){
        $dt = Datatables::of($this->getDataTableRows())
            ->addIndexColumn();
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
