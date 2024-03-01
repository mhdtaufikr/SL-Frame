@extends('layouts.master')

@section('content')
<style>
    #lblGreetings {
        font-size: 1.5rem; /* Adjust the base font size as needed */
    }

    @media only screen and (max-width: 600px) {
        #lblGreetings {
            font-size: 1rem; /* Adjust the font size for smaller screens */
        }
    }
</style>
<script src="{{ asset('test.js') }}"></script>
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-2">
                        <h1 class="page-header-title">
                            {{-- <div class="page-header-icon"><i data-feather="file"></i></div> --}}
                            <label id="lblGreetings"></label>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <section class="content">
        <div class="container-fluid">
            <div class="container-xl px-4 mt-n10">
                <div class="row">
                   <!-- Finding By QG -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header text-dark">
                                <div class="d-flex justify-content-between">
                                    <span></span>
                                    <span style="padding-right: 10px"><strong>Total : {{$sums['sumFindingQG']}}</strong></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="findingByQG" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                            </div>
                            <div class="card-footer small text-muted"><p>
                                Updated today at {{ now()->format('h:i A') }}
                            </p></div>
                        </div>
                    </div>

                    <!-- Finding By PDI -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header text-dark">
                                <div class="d-flex justify-content-between">
                                    <span></span>
                                    <span style="padding-right: 10px"><strong>Total : {{$sums['sumFindingPDI']}}</strong></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="findingByPDI" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                            </div>
                            <div class="card-footer small text-muted"><p>
                              Updated today at {{ now()->format('h:i A') }}
                            </p></div>
                        </div>
                    </div>

                    <!-- Pending -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header text-dark">
                                <div class="d-flex justify-content-between">
                                    <span></span>
                                    <span style="padding-right: 10px"><strong>Total : {{$sums['sumPending']}}</strong></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="pending" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                            </div>
                            <div class="card-footer small text-muted"><p>
                              Updated today at {{ now()->format('h:i A') }}
                            </p></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                            </div>
                            <div class="card-footer small text-muted"><p>
                                Updated today at {{ now()->format('h:i A') }}
                            </p></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- /.container-fluid -->
    </section>


    <script>
        var myDate = new Date();
        var hrs = myDate.getHours();

        var greet;

        if (hrs < 12)
            greet = 'Good Morning';
        else if (hrs >= 12 && hrs <= 17)
            greet = 'Good Afternoon';
        else if (hrs >= 17 && hrs <= 24)
            greet = 'Good Evening';

        document.getElementById('lblGreetings').innerHTML =
            '<b>' + greet + '</b> and welcome to Checksheet SL-Frame';


    </script>
<script>
    window.onload = function () {
        // Parse the data
        var chartData = {!! json_encode($data->toArray()) !!};

        // Create the main chart
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Count of finding & Repair Item Check SL - Frame"
            },
            axisX: {
                title: "ItemCheck",
                interval: 1
            },
            axisY: {
                title: "Count of Items",
                interval: 1
            },
            data: [{
                type: "area",
                dataPoints: chartData.map(item => ({
                    label: item.ItemCheck,
                    y: item.CountChecksheet
                }))
            }]
        });

        // Render the main chart
        chart.render();

        // Create the findingByQGChart
        var findingByQGChart = new CanvasJS.Chart("findingByQG", {
            animationEnabled: true,
            title: {
                text: "Finding By QG"
            },
            axisX: {
                valueFormatString: "DD MMM",
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true
                }
            },
            axisY: {
                title: "Count of Finding",
                includeZero: false,
                valueFormatString: "##0",
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true,
                    labelFormatter: function (e) {
                        return "" + CanvasJS.formatNumber(e.value, "##0");
                    }
                }
            },
            data: [{
                type: "area",
                xValueFormatString: "DD MMM",
                yValueFormatString: "##0",
                dataPoints: [
                    @foreach($findingQGCount as $key => $value)
                        { x: new Date({{ now()->year }}, {{ now()->month - 1 }}, {{$key}}), y: {{$value}} },
                    @endforeach
                ]
            }]
        });

        // Render the findingByQGChart
        findingByQGChart.render();
        findingByQGChart.options.data[0].click = function (e) {
            var date = new Date(e.dataPoint.x);
            var day = date.getDate();
            var month = date.getMonth() + 1; // Months are zero-based
            var year = date.getFullYear();
            var formattedDate = `${day}`;

            var role = 'qg';
            var url = `/detail/${role}/${formattedDate}`;
            window.open(url, '_blank');
        };

        // Create the findingByPDIChart
        var findingByPDIChart = new CanvasJS.Chart("findingByPDI", {
            animationEnabled: true,
            title: {
                text: "Finding By PDI"
            },
            axisX: {
                valueFormatString: "DD MMM",
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true
                }
            },
            axisY: {
                title: "Count of Finding",
                includeZero: false,
                valueFormatString: "##0",
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true,
                    labelFormatter: function (e) {
                        return "" + CanvasJS.formatNumber(e.value, "##0");
                    }
                }
            },
            data: [{
                type: "area",
                xValueFormatString: "DD MMM",
                yValueFormatString: "##0",
                dataPoints: [
                    @foreach($findingPDICount as $key => $value)
                        { x: new Date({{ now()->year }}, {{ now()->month - 1 }}, {{$key}}), y: {{$value}} },
                    @endforeach
                ]
            }]
        });

        // Render the findingByPDIChart
        findingByPDIChart.render();
        findingByPDIChart.options.data[0].click = function (e) {
            var date = new Date(e.dataPoint.x);
            var day = date.getDate();
            var month = date.getMonth() + 1; // Months are zero-based
            var year = date.getFullYear();
            var formattedDate = `${day}`;

            var role = 'pdi';
            var url = `/detail/${role}/${formattedDate}`;
            window.open(url, '_blank');
        };

        // Create the pendingChart
        var pendingChart = new CanvasJS.Chart("pending", {
            animationEnabled: true,
            title: {
                text: "Pending Checksheet"
            },
            axisX: {
                valueFormatString: "DD MMM",
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true
                }
            },
            axisY: {
                title: "Count of Pending",
                includeZero: false,
                valueFormatString: "##0",
                crosshair: {
                    enabled: true,
                    snapToDataPoint: true,
                    labelFormatter: function (e) {
                        return "" + CanvasJS.formatNumber(e.value, "##0");
                    }
                }
            },
            data: [{
                type: "area",
                xValueFormatString: "DD MMM",
                yValueFormatString: "##0",
                dataPoints: [
                    @foreach($pendingCount as $key => $value)
                        { x: new Date({{ now()->year }}, {{ now()->month - 1 }}, {{$key}}), y: {{$value}} },
                    @endforeach
                ]
            }]
        });

        // Render the pendingChart
        pendingChart.render();
    }
</script>



</main>
@endsection
