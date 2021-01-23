@extends('layouts.app_full')

@section('content')
    <div class=" mb-5">
    <div class="content tx-13" id="poOrderPrint">
        <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
            <div class="row">
                <div class="col-sm-6">
                    <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Supplier Information</label>
                    <h6 class="tx-15 mg-b-10">{{$data['supplier']['name']}}</h6>
                    <p class="mg-b-0">Email: {{$data['supplier']['email']}}</p>
                    <p class="mg-b-0">Tel No: {{$data['supplier']['phone']}}</p>
                </div><!-- col -->
                <div class="col-sm-6 tx-right d-none d-md-block">
                    <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Invoice Number</label>
                    <h1 class="tx-normal tx-color-04 mg-b-10 tx-spacing--2">#{{$data['id']}}</h1>
                </div><!-- col -->
                <div class="col-sm-6 col-lg-8 mg-t-40 mg-sm-t-0 mg-md-t-40">
                    <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Supplier Address</label>
                    <p class="mg-b-0">{{$data['supplier']['address']}}</p>

                </div><!-- col -->
                <div class="col-sm-6 col-lg-4 mg-t-40">
                    <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Invoice Information</label>
                    <ul class="list-unstyled lh-7">
                        <li class="d-flex justify-content-between">
                            <span>Purchase Order Number</span>
                            <span>#{{$data['id']}}</span>
                        </li>

                        <li class="d-flex justify-content-between">
                            <span>Issue Date</span>
                            <span>{{date('m/d/Y',strtotime($data['order_date']))}}</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Order Status</span>
                            <span> {{$data['status'] == 1 ? 'Confirmed' :'Pending' }}</span>
                        </li>
                    </ul>
                </div><!-- col -->
            </div><!-- row -->

            <div class="table-responsive mg-t-40">
                <table class="table table-invoice bd-b">
                    <thead>
                    <tr>
                        <th class="wd-40p d-none d-sm-table-cell">Item</th>
                        <th class="tx-right">Unit Price</th>
                        <th class="tx-right">QTY</th>
                        <th class="tx-right">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td  class="d-none d-sm-table-cell tx-color-03">{{$order['item']['name']}}</td>
                            <td class="tx-right">@price($order['unit_cost'])</td>
                            <td class="tx-right">{{$order['quantity']}}</td>
                            <td class="tx-right">@price($order['total'])</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="row justify-content-between">
                <div class="col-sm-6 col-lg-6 order-2 order-sm-0 mg-t-40 mg-sm-t-0">
<!--                    <label class="tx-sans tx-uppercase tx-10 tx-medium tx-spacing-1 tx-color-03">Notes</label>
                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. </p>-->
                </div>
                <div class="col-sm-6 col-lg-4 order-1 order-sm-0">
                    <ul class="list-unstyled lh-7 pd-r-10">

                        <li class="d-flex justify-content-between" style="font-size:20px; ">
                            <strong>Total Amount</strong>
                            <strong>@price($data['grand_total'])</strong>
                        </li>
                    </ul>
<!--
                    <button onclick="PrintElem('poOrderPrint')" class="btn btn-block btn-primary">Pay Now</button>-->
                </div><!-- col -->
            </div><!-- row -->
        </div><!-- container -->
    </div>
    </div>

@endsection
