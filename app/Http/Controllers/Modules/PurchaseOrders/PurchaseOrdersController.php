<?php


namespace App\Http\Controllers\Modules\PurchaseOrders;


use App\Helpers\Helper;
use App\Http\Controllers\DatatableTrait;
use App\Http\Controllers\ModuleController;
use App\Models\Inventory;
use App\Models\Items;
use App\Models\Ledger;
use App\Models\PurchaseOrderItems;
use App\Models\PurchaseOrders;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseOrdersController extends ModuleController
{

    use DatatableTrait;

    public function index()
    {
        $this->injectDatatable();
        return $this->view('index');
    }

    public function getSuppliers(): array
    {
        return Suppliers::where('is_archive', '=', '0')->where('status', '=', '1')->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->get(['name', 'id'])->toArray();

    }

    public function getItems(): array
    {
        return Items::where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->get()->toArray();
    }

    public function getStatus(): array
    {
        $status = ['1' => 'Confirm', '2' => 'Pending'];
        return $status;
    }

    public function add()
    {
        $suppliers = $this->getSuppliers();
        $items = $this->getItems();
        $status = $this->getStatus();
        return $this->view('add', ['suppliers' => $suppliers, 'items' => $items, 'status' => $status]);
    }

    public function viewOrder($id)
    {

        $data = PurchaseOrders::with('supplier')->where('id', $id)->first();
        $orders = PurchaseOrderItems::with('item')->where('purchase_order_id', $data['id']);
        return $this->view('viewOrder', ['data' => $data, 'orders' => $orders->get()->toArray()]);
    }
    public function invoice($id)
    {

        $data = PurchaseOrders::with('supplier')->where('id', $id)->first();
        $orders = PurchaseOrderItems::with('item')->where('purchase_order_id', $data['id']);
        \PDF::saveFromView($this->view('invoice', ['data' => $data, 'orders' => $orders->get()->toArray()]), 'filename.pdf');
        return $this->view('invoice', ['data' => $data, 'orders' => $orders->get()->toArray()]);
    }

    public function status(Request $request, $id, $field = "status"): array
    {
        $data = DB::table($this->getModuleTable())->where('id', $id)->get()->first();
        if (!empty($data)) {
            $data = (array)$data;
            DB::table($this->getModuleTable())->where('id', $id)->update([$field => ($data[$field] == 1 ? 2 : 1)]);
            if ($data[$field] == 1) {
                $current_balance =  Helper::getBalance($request->input('supplier_id'),'supplier');
                $ledger = new Ledger();
                $ledger->nature_id = $data['supplier_id'];
                $ledger->type_id = $id;
                $ledger->mode = 'invoice';
                $ledger->amount = $data['grand_cost_total'];
                $ledger->balance = $current_balance+$data['grand_cost_total'];
                $ledger->description = $data['description'];
                $ledger->date = date('Y-m-d');
                $ledger->type = 'purchase';
                $ledger->nature = 'supplier';
                $ledger->account = 'debit';
                $ledger->user_id = Auth::user()->id;
                $ledger->company_id = Auth::user()->company_id;
                $ledger->save();
            }
        }
        return ['success' => 1];
    }


    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'supplier_id' => 'required',
            'order_date' => 'required',
            'status' => 'required',
        ], [], [
            'supplier_id' => 'Supplier',
            'order_date' => 'Order Date',
            'status' => 'Status',

        ])->validate();

        $purchaseOrder = new PurchaseOrders();
        $purchaseOrder->supplier_id = $request->input('supplier_id');
        $purchaseOrder->order_date = date('Y-m-d', strtotime($request->input('order_date')));
        $purchaseOrder->description = $request->input('description');
        $purchaseOrder->status = $request->input('status');
        $purchaseOrder->grand_total = $request->input('grandTotal');
        $purchaseOrder->grand_cost_total = $request->input('grandCostTotal');
        $purchaseOrder->count = $request->input('count');
        $purchaseOrder->user_id = Auth::user()->id;
        $purchaseOrder->company_id = Auth::user()->company_id;
        $purchaseOrder->save();
        $pId = $purchaseOrder->id;
        $error = 0;
        if (!empty($request->input('po'))) {
            $items = json_decode($request->input('po'), true);
            if (empty($items)) {
                return redirect()->back()->withInput()->with('error', 'Please Select Item!');
            }
            foreach ($items as $item) {
                if (!empty($item['item'])) {
                    $itemId = $item['item']["code"];
                    $cost = $item['cost'];
                    $price = $item['price'];
                    $qty = $item['qty'];
                    $total = $item['total'];
                    $cost_total = $item['cost_total'];
                    $purchaseOrderItems = new PurchaseOrderItems();
                    $purchaseOrderItems->purchase_order_id = $pId;
                    $purchaseOrderItems->item_id = $itemId;
                    $purchaseOrderItems->quantity = $qty;
                    $purchaseOrderItems->unit_cost = $cost;
                    $purchaseOrderItems->unit_price = $price;
                    $purchaseOrderItems->total = $total;
                    $purchaseOrderItems->cost_total = $cost_total;
                    $purchaseOrderItems->save();
                    $poiId = $purchaseOrderItems->id;

                    $inventory = new Inventory();
                    $inventory->purchase_order_items_id = $poiId;
                    $inventory->item_id = $itemId;
                    $inventory->quantity = $qty;
                    $inventory->unit_cost = $cost;
                    $inventory->unit_price = $price;
                    $inventory->price_total = $total;
                    $inventory->cost_total = $cost_total;
                    $inventory->user_id = Auth::user()->id;
                    $inventory->company_id = Auth::user()->company_id;
                    $inventory->save();

                    /*if ($purchaseOrder->status == 1) {
                        $itemsTable = Items::where('id', $itemId)->first();
                        $itemsTable->last_updated_stock += $qty;
                        $itemsTable->last_updated_cost = $cost;
                        $itemsTable->last_updated_price = $price;
                        $itemsTable->save();
                    }*/

                } else {
                    $error++;
                }
            }
            if ($purchaseOrder->status == 1) {

                $current_balance =  Helper::getBalance($request->input('supplier_id'),'supplier');
                $ledger = new Ledger();
                $ledger->nature_id = $request->input('supplier_id');
                $ledger->type_id = $pId;
                $ledger->date = date('Y-m-d', strtotime($request->input('order_date')));
                $ledger->mode = 'invoice';
                $ledger->amount = $purchaseOrder->grand_cost_total;
                $ledger->balance = $current_balance+$purchaseOrder->grand_cost_total;
                $ledger->description = $request->input('description');
                $ledger->type = 'purchase';
                $ledger->nature = 'supplier';
                $ledger->account = 'debit';
                $ledger->user_id = Auth::user()->id;
                $ledger->company_id = Auth::user()->company_id;
                $ledger->save();
            }
            if ($error == sizeof($items)) {
                $purchaseOrder->delete();
                return redirect()->back()->withInput()->with('error', 'Please Select Item!');
            }
            if (!empty($request->input('saveClose'))) {
                return redirect()->route($this->mRoute('home'))->with('success', 'Purchase Order Added Successfully!');
            } else {
                return redirect()->route($this->mRoute('add'))->with('success', 'Purchase Order Added Successfully!');

            }

        } else {
            return redirect()->back()->withInput()->with('error', 'Please Select Item!');

        }


    }

    protected function getDataTableRows(): array
    {

        return PurchaseOrders::with('supplier')->where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->get()->toArray();
    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "order_date", "onAction" => function ($row) {
                return date('m/d/Y', strtotime($row['order_date']));
            }],
            ["data" => "supplier.name"],
            ["data" => "grand_total","onAction"=>function($row){
                return Helper::price($row['grand_total']);
            }],
            ["data" => "count"],

            ["data" => "action", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                $html = '';
                if ($row['status'] == 1) {
                    $html = "<i style='color: #0d9448' class='fas fa-check'></i> Confirmed";
                } elseif ($row['status'] == 2) {
                    $html = "<i style='color: #edb100' class='fas fa-bell'></i> Pending";

                }
                return $html;

            }],
            ["data" => "action1", "orderable" => false, "searchable" => false, "onAction" => function ($row) {
                $deleteFun = "delete_row(" . $row["id"] . ",'" . route($this->mRoute('delete'), [$row["id"]]) . "','" . csrf_token() . "',this)";
                $statusFun = "orderStatus(" . $row["id"] . ",'" . route($this->mRoute('status'), [$row["id"], 'status']) . "','" . csrf_token() . "',this)";
                $checkStatus = "" . ($row['status'] == 1 ? 'd-none' : '') . "";

                $html = '
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                        <a href="#" class="dropdown-item ' . $checkStatus . ' href="#" onclick="' . $statusFun . '"><i class="fas fa-check"></i>&nbsp;&nbsp;Confirm</a>
                        <a class="dropdown-item" href="' . route($this->mRoute('viewOrder'), [$row['id']]) . '"><i class="fas fa-eye"></i>&nbsp;&nbsp;View</a>
                        <a class="dropdown-item" href="' . route($this->mRoute('invoice'), [$row['id']]) . '"><i class="fas fa-print"></i>&nbsp;&nbsp;Invoice</a>
                        <a class="dropdown-item ' . $checkStatus . '" href="#" onclick="' . $deleteFun . '"><i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</a>
                      </div>
                    </div>
                ';
                return $html;
            }],
        ];
    }

    protected function getModuleTable(): string
    {
        return (new PurchaseOrders())->getTable();
    }


}
