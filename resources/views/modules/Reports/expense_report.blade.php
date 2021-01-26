@extends('layouts.app')

@section('content')


    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Expense Report</h4>
            </div>

        </div>
        <form action="{{route('module.'.$controllerName.'.home.search')}}" method="post">
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
                    <label for="inputEmail4">Categories</label>
                    <select class="form-control select2  @error('category_id') is-invalid @enderror" name="category_id">
                        <option label="Select Category"></option>
                        @foreach ($categories as $value)
                            <option
                                value="{{$value['id']}}" {{\App\Helpers\Helper::reqValue('category_id') == $value['id'] ? 'selected' : ''}}>{{$value['name']}}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror

                </div>
                <div class="col-md-1">
                    <button style="margin-top: 28px;" type="submit" class="btn btn-primary btn-icon">
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
                                <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">@price($grand_total)</h3>
                                <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-primary mg-b-5">Total
                                    Amount</h6>
                                <p class="tx-11 tx-color-03 mg-b-0">No. of clicks to ad that consist of a single
                                    impression.</p>
                            </div>
                            <div class="col-5">
                                <div class="chart-ten">
                                    <div id="sumTotal" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col -->
        @foreach($category_amount as $category)
            <div class="col-md-2 mg-t-10">
                <div class="card">
                    <div class="card-header pd-b-4 pd-t-20 bd-b-0">
                        <h6 class="mg-b-0">{{$category['category']}}</h6>
                    </div><!-- card-header -->
                    <div class="card-body pd-y-10">

                        <div class="d-flex align-items-baseline tx-rubik">
                            <h1 class="tx-40 lh-1 tx-normal tx-spacing--2 mg-b-5 mg-r-5">@price($category['amount'])</h1>

                        </div>
                        <p class="tx-11 tx-color-03 mg-b-7">Total amount of {{$category['category']}}</p>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div>
            @endforeach

        </div>
        <br>
    @endif
    <div class="card card-body">

        <div style="display: flex" class="mb-3">

        </div>
        <table data-table="mainGrid" data-url="{{route('module.'.$controllerName.'.datatable',request()->toArray())}}"
               data-exportable="true" data-cols='{!! base64_encode((!empty($dataTableColumns) ? $dataTableColumns : '')) !!}'
               class="table table-hover">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="5%">Date</th>
                <th width="30%">Category Name</th>
                <th width="10%">Amount</th>
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
                return Math.floor(Math.random() * (max - min) ) + min;
            }

            let dataFraction = 30;
            let chartEnd = dataFraction/2;


            var sumTotal = $.plot('#sumTotal', [{
                data: {!!$sumTotalGraph!!}.map((data, index) => [index, getRndInteger(data.amount/dataFraction,data.amount/chartEnd)]),
                color: '#e5e9f2'
            },{
                data: {!!$sumTotalGraph!!}.map((data, index) => [index, data.amount/dataFraction]),
                color: '#66a4fb'
            }], flotChartOption1);


        </script>
    @endif
@endpush
