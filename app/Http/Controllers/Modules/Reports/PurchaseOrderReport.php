<?php


namespace App\Http\Controllers\Modules\Reports;


use App\Http\Controllers\ModuleController;
use App\Models\PurchaseOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PurchaseOrderReport extends ModuleController
{
    public $record = [];

    public function __construct()
    {
        parent::__construct();
        View::share('controllerName', \request()->segment(2));
    }

    public function index()
    {
        return view('modules.reports.purchase_order_report');

    }

    public function search(Request $request)
    {
        $this->record = PurchaseOrders::where('is_archive', 0)->orderBy('id', 'DESC')->get()->toArray();
        $this->injectDatatable();
        return view('modules.reports.purchase_order_report');


        /*  Validator::make($request->all(), [
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
                      $purchaseOrderItems = new PurchaseOrderItems();
                      $purchaseOrderItems->purchase_order_id = $pId;
                      $purchaseOrderItems->item_id = $itemId;
                      $purchaseOrderItems->quantity = $qty;
                      $purchaseOrderItems->unit_cost = $cost;
                      $purchaseOrderItems->unit_price = $price;
                      $purchaseOrderItems->total = $total;
                      $purchaseOrderItems->save();

                      if ($purchaseOrder->status == 1) {
                          $itemsTable = Items::where('id', $itemId)->first();
                          $itemsTable->last_updated_stock += $qty;
                          $itemsTable->last_updated_cost = $cost;
                          $itemsTable->last_updated_price = $price;
                          $itemsTable->save();
                      }

                  } else {
                      $error++;
                  }
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

          }*/


    }

    protected function getDataTableRows(): array
    {
        return $this->record;
    }

    protected function getDataTableColumns(): array
    {
        return [
            ["data" => "id"],
            ["data" => "customer.name"],
            ["data" => "grand_total"],
            ["data" => "order_date", "onAction" => function ($row) {
                return date('m/d/Y', strtotime($row['order_date']));
            }],
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
                        <a class="dropdown-item" href="' . route($this->mRoute('edit'), [$row['id']]) . '"><i class="fas fa-eye"></i>&nbsp;&nbsp;View</a>
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
