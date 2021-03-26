@extends('layouts.app')

@section('content')


    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Customer Ledger</h4>
            </div>
            <div>

                @isSubscribed
                <a href="{{route('module.'.$moduleName.'.add')}}" class="btn btn-success btn-icon">
                    <i data-feather="plus"></i> Add Payment
                </a>
                @endisSubscribed
                @isNotSubscribed

                <a href="#" data-toggle="modal" data-target="#subscriptionExpiredPopup" class="btn btn-success btn-icon">
                    <i data-feather="plus"></i> Add Payment
                </a>
                @endisNotSubscribed
            </div>
        </div>
        <form action="{{route('module.'.$moduleName.'.home.search')}}" method="post">
            @csrf
            <div class="form-row">

                <div class="form-group col-md-3">
                    <label for="inputEmail4">From Date</label>

                    <input type="text" name="from_date"
                           class="form-control datepicker @error('from_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{\App\Helpers\Helper::reqValue('from_date')}}"
                           autocomplete="off">
                    @error('from_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="inputEmail4">To Date</label>
                    <input type="text" name="to_date"
                           class="form-control datepicker @error('to_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{\App\Helpers\Helper::reqValue('to_date')}}"
                           autocomplete="off">
                    @error('to_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-5">
                    <label for="inputEmail4">Customers <span class="tx-danger">*</span></label>
                    <select class="form-control select2  @error('category_id') is-invalid @enderror" name="customer_id">
                        <option label="Select Customer"></option>
                        @foreach ($customers as $value)
                            <option
                                value="{{$value['id']}}" {{\App\Helpers\Helper::reqValue('customer_id') == $value['id'] ? 'selected' : ''}}>{{$value['name']}}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="col-md-1">
                    <button style="width:100%;margin-top: 28px;" type="submit" class="btn btn-primary btn-icon">
                        <i data-feather="search"></i> Search
                    </button>
                </div>

            </div>
        </form>
    </div>
    <br>
    @if(!empty(\request()->toArray()))
        <div class="row row-xs">
            <div class="col-lg-4 col-md-6 mg-t-10">
                <div class="card">
                    <div class="card-body pd-y-20 pd-x-25">
                        <div class="row row-sm">
                            <div class="col-7">
                                <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">@price($sold)</h3>
                                <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-primary mg-b-5">Total
                                    Sold</h6>
                                <p class="tx-11 tx-color-03 mg-b-0">No. of clicks to ad that consist of a single
                                    impression.</p>
                            </div>
                            <div class="col-5">
                                <div class="chart-ten">
                                    <div id="soldTotal" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col -->

            <div class="col-lg-4 col-md-6 mg-t-10">
                <div class="card">
                    <div class="card-body pd-y-20 pd-x-25">
                        <div class="row row-sm">
                            <div class="col-7">
                                <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">@price($receive)</h3>
                                <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-pink mg-b-5">Total
                                    Receive</h6>
                                <p class="tx-11 tx-color-03 mg-b-0">No. of clicks to ad that consist of a single
                                    impression.</p>
                            </div>
                            <div class="col-5">
                                <div class="chart-ten">
                                    <div id="reciveTotal" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col -->

            <div class="col-lg-4 col-md-6 mg-t-10">
                <div class="card">
                    <div class="card-body pd-y-20 pd-x-25">
                        <div class="row row-sm">
                            <div class="col-7">
                                <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">@price($balance)</h3>
                                <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-teal mg-b-5">Ending
                                    Balance</h6>
                                <p class="tx-11 tx-color-03 mg-b-0">No. of clicks to ad that consist of a single
                                    impression.</p>
                            </div>
                            <div class="col-5">
                                <div class="chart-ten">
                                    <div id="balanceTotal" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col -->


        </div>
        <br>
    @endif
    <div class="card card-body">

        <div style="display: flex" class="mb-3">

        </div>
        <table data-table="mainGrid" data-url="{{route('module.'.$moduleName.'.datatable',request()->toArray())}}"
               data-exportable="true" data-cols='{!! base64_encode($dataTableColumns) !!}'
               class="table table-hover">
            <thead>
            <tr>
                <th width="5%">Date</th>
                <th width="10%">Trans.Type</th>
                <th width="10%">Mode</th>
                <th width="30%">Description</th>
                <th width="10%">Amount</th>
                <th width="10%">Balance</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection
@push('scripts')
    @if(!empty(\request()->toArray()))

        <script>
            var flotChartOption1 = {
                series: {
                    shadowSize: 0,
                    bars: {
                        show: true,
                        lineWidth: 0,
                        barWidth: .5,
                        fill: 1
                    }
                },
                grid: {
                    aboveData: true,
                    color: '#e5e9f2',
                    borderWidth: 0,
                    labelMargin: 0
                },
                yaxis: {
                    show: false,
                    min: 0,
                    max: 25
                },
                xaxis: {
                    show: false
                }
            };


            function getRndInteger(min, max) {
                return Math.floor(Math.random() * (max - min)) + min;
            }

            let dataFraction = 30;
            let chartEnd = dataFraction / 2;


            var soldTotal = $.plot('#soldTotal', [{
                data:
                {!!$soldTotalGraph!!}.map((data, index) => [index, getRndInteger(data.amount / dataFraction, data.amount / chartEnd)]),
                color: '#e5e9f2'
            }, {
                data: {!!$soldTotalGraph!!}.map((data, index) => [index, data.amount / dataFraction]),
                color: '#66a4fb'
            }], flotChartOption1);

            var reciveTotal = $.plot('#reciveTotal', [{
                data:
                {!!$receiveTotalGraph!!}.map((data, index) => [index, getRndInteger(data.amount / dataFraction, data.amount / chartEnd)]),
                color: '#e5e9f2'
            }, {
                data: {!!$receiveTotalGraph!!}.map((data, index) => [index, data.amount / dataFraction]),
                color: '#f10075'
            }], flotChartOption1);


            var balanceTotal = $.plot('#balanceTotal', [{
                data:
                {!!$balanceTotalGraph!!}.map((data, index) => [index, getRndInteger(data.balance / dataFraction, data.balance / chartEnd)]),
                color: '#e5e9f2'
            }, {
                data: {!!$balanceTotalGraph!!}.map((data, index) => [index, data.balance / dataFraction]),
                color: '#00cccc'
            }], flotChartOption1);


        </script>
    @endif
@endpush
@push('style')
    <style>
        .badge {
            font-size: 0.875rem;
            padding: 8px;
            font-weight: 500;
        }
        td:nth-child(2),td:nth-child(3){
            text-transform: capitalize;
        }
    </style>
@endpush
