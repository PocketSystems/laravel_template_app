@extends('layouts.app')
@section('content')

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                    <h4 class="mg-b-0 tx-spacing--1">Welcome to Dashboard</h4>
                </div>

            </div>

            <div class="row row-xs">
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-body">
                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Today Sale</h6>
                        <div class="d-flex d-lg-block d-xl-flex align-items-end">
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"></h3>
                            <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium"> <i class="icon ion-md-arrow-"></i></span> Yesterday</p>
                        </div>

                    </div>
                </div><!-- col -->
                <div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0">
                    <div class="card card-body">
                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Week Sales</h6>
                        <div class="d-flex d-lg-block d-xl-flex align-items-end">
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">1234</h3>
                            <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium "> <i class="icon ion-md-arrow-"></i></span> last week</p>
                        </div>

                    </div>
                </div><!-- col -->
                <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
                    <div class="card card-body">
                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">{{date('F')}} Sales</h6>
                        <div class="d-flex d-lg-block d-xl-flex align-items-end">
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">1234</h3>
                            <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium "> <i class="icon ion-md-arrow-"></i></span>  last Month</p>
                        </div>

                    </div>
                </div><!-- col -->
                <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
                    <div class="card card-body">
                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">{{date('Y')}} Sales</h6>
                        <div class="d-flex d-lg-block d-xl-flex align-items-end">
                            <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1">1234</h3>
                            <p class="tx-11 tx-color-03 mg-b-0"><span class="tx-medium "> <i class="icon ion-md-arrow-"></i></span>last Year</p>
                        </div>

                    </div>
                </div><!-- col -->


                <div class="col-lg-8 col-xl-7 mg-t-10">
                    <div class="card">
                        <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between">
                            <h6 class="mg-b-0">Sale &amp; Purchase Growth of {{date('Y')}}</h6>
                            <ul class="list-inline d-flex mg-t-20 mg-sm-t-10 mg-md-t-0 mg-b-0">

                                <li class="list-inline-item d-flex align-items-center mg-l-5">
                                    <span class="d-block wd-10 ht-10 bg-df-2 rounded mg-r-5"></span>
                                    <span class="tx-sans tx-uppercase tx-10 tx-medium tx-color-03">Sales</span>
                                </li>
                                <li class="list-inline-item d-flex align-items-center mg-l-5">
                                    <span style="background: #001737" class="d-block wd-10 ht-10 bg-df-3 rounded mg-r-5"></span>
                                    <span class="tx-sans tx-uppercase tx-10 tx-medium tx-color-03">Purchase</span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body pd-20">
                            <div class="ht-250 ht-lg-300"><canvas id="chartBar1"></canvas></div>
                        </div>
                    </div><!-- card -->
                </div>
                <div class="col-lg-4 col-xl-5 mg-t-10">
                    <div class="card">
                        <div class="card-header pd-y-20 d-md-flex align-items-center justify-content-between">
                            <h6 class="mg-b-0">Sale &amp; Purchase &amp; Expense Comparison of {{date('F Y')}}</h6>

                        </div>
                        <div class="card-body pd-10">
                            <div class="card-body pd-lg-25">
                                <div class="chart-seven"><canvas id="chartDonut"></canvas></div>
                            </div><!-- card-body -->
                        </div><!-- card-body -->
                        <div class="card-footer pd-20">
                            <div class="row">
                                <div class="col-4">
                                    <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 tx-nowrap mg-b-5">Sales</p>
                                    <div class="d-flex align-items-center">
                                        <div class="wd-10 ht-10 rounded-circle bg-primary mg-r-5"></div>
                                        <h5 class="tx-normal tx-rubik mg-b-0"></h5>
                                    </div>
                                </div><!-- col -->
                                <div class="col-4">
                                    <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">Purchase</p>
                                    <div class="d-flex align-items-center">
                                        <div class="wd-10 ht-10 rounded-circle bg-pink mg-r-5"></div>
                                        <h5 class="tx-normal tx-rubik mg-b-0">1234</h5>
                                    </div>
                                </div><!-- col -->
                                <div class="col-4">
                                    <p class="tx-10 tx-uppercase tx-medium tx-color-03 tx-spacing-1 mg-b-5">Expense</p>
                                    <div class="d-flex align-items-center">
                                        <div class="wd-10 ht-10 rounded-circle bg-teal mg-r-5"></div>
                                        <h5 class="tx-normal tx-rubik mg-b-0">exp month</h5>
                                    </div>
                                </div><!-- col -->

                            </div><!-- row -->
                        </div><!-- card-footer -->
                    </div><!-- card -->
                </div>


                <div class="col-lg-4 col-md-6 mg-t-10">
                    <div class="card">
                        <div class="card-body pd-y-20 pd-x-25">
                            <div class="row row-sm">
                                <div class="col-7">
                                    <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">134</h3>
                                    <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-primary mg-b-5">Total Sales</h6>
                                    <p class="tx-11 tx-color-03 mg-b-0">Total Sales you generate by systems</p>
                                </div>
                                <div class="col-5">
                                    <div class="chart-ten">
                                        <div id="saleSum" class="flot-chart"></div>
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
                                    <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">purchase sum</h3>
                                    <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-pink mg-b-5">Total Purchase</h6>
                                    <p class="tx-11 tx-color-03 mg-b-0">Total purchases you generate by systems</p>
                                </div>
                                <div class="col-5">
                                    <div class="chart-ten">
                                        <div id="purchaseSum" class="flot-chart"></div>
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
                                    <h3 class="tx-normal tx-rubik tx-spacing--1 mg-b-5">expanse sum</h3>
                                    <h6 class="tx-12 tx-semibold tx-uppercase tx-spacing-1 tx-teal mg-b-5">Total Expense</h6>
                                    <p class="tx-11 tx-color-03 mg-b-0">Total Expense you generate by systems</p>

                                </div>
                                <div class="col-5">
                                    <div class="chart-ten">
                                        <div id="expSum" class="flot-chart"></div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- card-body -->
                    </div><!-- card -->
                </div><!-- col -->

            </div><!-- row -->

@endsection

@push('scripts')
    <script>

        function getRndInteger(min, max) {
            return Math.floor(Math.random() * (max - min) ) + min;
        }
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
        let dataFraction = 100;
        let chartEnd = dataFraction/2;



         dataFraction = 10;
         chartEnd = dataFraction/2;



         dataFraction = 20;
         chartEnd = dataFraction/2;

        var ctxLabel = Object.keys(soData).map((key, index) => key);
        var ctxData1 =  Object.keys(soData).map((date, index) => soData[date].so*10);
        var ctxData2 =  Object.keys(soData).map((date, index) => soData[date].po*10);
        var ctxColor1 = '#001737';
        var ctxColor2 = '#66a4fb';




    //    second box

        // Bar chart
        var ctx1 = document.getElementById('chartBar1').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ctxLabel,
                datasets: [{
                    data: ctxData1,
                    backgroundColor: ctxColor1
                }, {
                    data: ctxData2,
                    backgroundColor: ctxColor2
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false,
                    labels: {
                        display: false
                    }
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#e5e9f2'
                        },
                        ticks: {
                            beginAtZero:true,
                            fontSize: 10,
                            fontColor: '#182b49',
                            max: 80
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        barPercentage: 0.6,
                        ticks: {
                            beginAtZero:true,
                            fontSize: 11,
                            fontColor: '#182b49'
                        }
                    }]
                }
            }
        });

        // pie chart
        var datapie = {
            labels: ['Purchase','Sales','Expense'],
            datasets: [{

            }]
        };
        var optionpie = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false,
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        };
        var ctx2 = document.getElementById('chartDonut');
        var myDonutChart = new Chart(ctx2, {
            type: 'doughnut',
            data: datapie,
            options: optionpie
        });

    </script>
@endpush
