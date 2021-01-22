<?php


namespace App\Http\Controllers\Modules\Inventory;


use App\Helpers\Helper;
use App\Http\Controllers\ModuleController;
use App\Models\Inventory;
use App\Models\Items;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class InventoryController extends ModuleController
{

    public function __construct()
    {
        parent::__construct();
        $this->setModuleName('inventory');
    }
    public function index()
    {
        $this->injectDatatable();
        return $this->view('index');
    }


    protected function getModuleTable(): string
    {
        return (new Inventory())->getTable();
    }
    protected function getDataTableRows(): array
    {

        return Items::where('is_archive', 0)
            ->where('user_id',Auth::user()->id)
            ->where('company_id',Auth::user()->company_id)
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
    }
    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "name"],
            ["data" => "stock"],
            ["data" => "total","onAction"=>function($row){
            return Helper::price($row['total']);
            }],
        ];
    }
}
