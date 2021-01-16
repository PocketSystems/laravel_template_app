<?php


namespace App\Http\Controllers\Modules\SaleOrders;


use App\Http\Controllers\ModuleController;
use App\Models\Customers;
use App\Models\Inventory;
use App\Models\Items;
use App\Models\SaleOrderItems;
use App\Models\SaleOrders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class SaleOrdersController extends ModuleController
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
    public function getCustomers(): array
    {
        return Customers::where('is_archive', '=', '0')->where('status', '=', '1')->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->get(['name', 'id'])->toArray();

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

        $customers = $this->getCustomers();
        $items = $this->getItems();
        $status = $this->getStatus();
        return $this->view('add', ['customers' => $customers, 'items' => $items, 'status' => $status]);
    }

    public function viewOrder($id)
    {

        $data = SaleOrders::with('customer')->where('id', $id)->first();
        $orders = SaleOrderItems::with('item')->where('sale_order_id', $data['id']);
        return $this->view('viewOrder', ['data' => $data, 'orders' => $orders->get()->toArray()]);
    }
    public function invoice($id)
    {

        $data = SaleOrders::with('customer')->where('id', $id)->first();
        $orders = SaleOrderItems::with('item')->where('sale_order_id', $data['id']);
        return $this->view('invoice', ['data' => $data, 'orders' => $orders->get()->toArray()]);
    }
    public function status(Request $request,$id,$field = "status"): array
    {
        $data =  DB::table($this->getModuleTable())->where('id', $id)->get()->first();
        if(!empty($data)){
            $data = (array)$data;
            DB::table($this->getModuleTable())->where('id', $id)->update([$field => ($data[$field] == 1 ? 2 : 1)]);
        }
        return ['success' => 1];
    }

    public function create(Request $request)
    {
        Validator::make($request->all(), [
            'customer_id' => 'required',
            'order_date' => 'required',
            'status' => 'required',
        ], [], [
            'customer_id' => 'Customer',
            'order_date' => 'Order Date',
            'status' => 'Status',

        ])->validate();

        $saleOrder = new SaleOrders();
        $saleOrder->customer_id = $request->input('customer_id');
        $saleOrder->order_date = date('Y-m-d', strtotime($request->input('order_date')));
        $saleOrder->status = $request->input('status');
        $saleOrder->description = $request->input('description');
        $saleOrder->grand_total = $request->input('grandTotal');
        $saleOrder->discount_total = $request->input('discount_total');
        $saleOrder->count = $request->input('count');
        $saleOrder->user_id = Auth::user()->id;
        $saleOrder->company_id = Auth::user()->company_id;
        $saleOrder->save();
        $pId = $saleOrder->id;
        $error = 0;
        if (!empty($request->input('so'))) {
            $items = json_decode($request->input('so'), true);
            if (empty($items)) {
                return redirect()->back()->withInput()->with('error', 'Please Select Item!');
            }
            foreach ($items as $item) {
                if (!empty($item['item']) && !empty($item['qty'])) {


                        $itemId = $item['item']["code"];
                        $price = $item['price'];
                        $qty = $item['qty'];
                        $discount = $item['discount'];
                        $discount_amount = $item['discount_amount'];
                        $tax = $item['tax'];
                        $total = $item['total'];
                        $saleOrderItems = new SaleOrderItems();
                        $saleOrderItems->sale_order_id = $pId;
                        $saleOrderItems->item_id = $itemId;
                        $saleOrderItems->quantity = $qty;
                        $saleOrderItems->discount = $discount;
                        $saleOrderItems->discount_amount = $discount_amount;
                        $saleOrderItems->tax = $tax;
                        $saleOrderItems->unit_price = $price;
                        $saleOrderItems->total = $total;
                        $saleOrderItems->save();

                        if ($saleOrder->status == 1) {
                            $inventory = Inventory::where('item_id',$itemId)->get();
                            $this->inventory_deduction(intval($qty),$inventory);
                   /*         $itemsTable = Items::where('id', $itemId)->first();
                            $itemsTable->last_updated_stock -= $qty;
                            $itemsTable->last_updated_price = $price;
                            $itemsTable->save();*/
                        }


                } else {
                    $error++;
                }
            }
            if ($error == sizeof($items)) {
                $saleOrder->delete();
                return redirect()->back()->withInput()->with('error', 'Please Select Item!');
            }
            if (!empty($request->input('saveClose'))) {
                return redirect()->route($this->mRoute('home'))->with('success', 'Sale Order Added Successfully!');
            } else {
                return redirect()->route($this->mRoute('add'))->with('success', 'Sale Order Added Successfully!');

            }

        } else {
            return redirect()->back()->withInput()->with('error', 'Please Select Item!');

        }


    }


    private function inventory_deduction($qty,$inventory,$debug = false): int
    {
        if($debug){
            echo "<pre>";
            print_r($inventory);
            echo "</pre>";
        }
        $itemsUpdated = 0;
        foreach ($inventory as $item){
            if($debug) {
                echo "<br/>";
            }
            if($qty === 0){
                break;
            }

            $itemQty = $item->quantity;
            if($itemQty < $qty){
                if($debug) {
                    echo "isLess";
                }
                $qty -= $item->quantity;
                $item->quantity = 0;
            }elseif($itemQty >= $qty){
                if($debug) {
                    echo "isGreater";
                }
                $item->quantity -= $qty;
                $qty = 0;
            }
            $item->save();
            $itemsUpdated++;
            if($debug) {
                echo "<br/>";
                echo $qty;
            }
        }
        if($debug) {
            echo "<pre>";
            print_r($inventory);
            echo "</pre>";
        }
        return $itemsUpdated;
    }

    protected function getDataTableRows(): array
    {

        return SaleOrders::with('customer')->where('is_archive', 0)->where('user_id',Auth::user()->id)->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->get()->toArray();
    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "order_date", "onAction" => function ($row) {
                return date('m/d/Y', strtotime($row['order_date']));
            }],
            ["data" => "customer.name"],
            ["data" => "grand_total"],
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
                $statusFun = "orderStatus(" . $row["id"] . ",'" . route($this->mRoute('status'), [$row["id"],'status']) . "','" . csrf_token() . "',this)";
                $checkStatus = "" . ($row['status'] == 1 ? 'd-none' : '') . "";

                $html = '
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                        <a href="#" class="dropdown-item '.$checkStatus.' href="#" onclick="' . $statusFun . '"><i class="fas fa-check"></i>&nbsp;&nbsp;Confirm</a>
                        <a class="dropdown-item" href="'.route($this->mRoute('viewOrder'), [$row['id']]).'"><i class="fas fa-eye"></i>&nbsp;&nbsp;View</a>
                        <a class="dropdown-item '.$checkStatus.'" href="#" onclick="' . $deleteFun . '"><i class="fas fa-trash"></i>&nbsp;&nbsp;Delete</a>
                      </div>
                    </div>
                ';
                return $html;
            }],
        ];
    }

    protected function getModuleTable(): string
    {
        return (new SaleOrders())->getTable();
    }
}
