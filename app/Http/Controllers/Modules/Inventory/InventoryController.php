<?php


namespace App\Http\Controllers\Modules\Inventory;


use App\Helpers\Helper;
use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Models\Inventory;
use App\Models\Items;
use Illuminate\Support\Facades\Auth;

class InventoryController extends ModuleController
{

    use DatatableTrait;

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

        return Items::with('category')->where('is_archive', 0)

            ->where('company_id',Auth::user()->company_id)
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
    }
    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "category.name"],
            ["data" => "name"],
            ["data" => "stock"]

        ];
    }
}
