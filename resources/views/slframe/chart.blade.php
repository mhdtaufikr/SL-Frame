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
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header text-dark">
                                <div class="d-flex justify-content-between">
                                    <span>Finding by QG ({{now()->format('F Y') }})</span>
                                    <span style="padding-right: 10px"><strong>Total : {{$sums['sumFindingqg']}}</strong></span>
                                </div>
                            </div>


                            <div class="card-body">
                                <div class="chart-area"><canvas id="myAreaChart" width="100%" height="30"></canvas></div>
                            </div>
                            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header text-dark">
                                <div class="d-flex justify-content-between">
                                    <span>Finding by QG ({{now()->format('F Y') }})</span>
                                    <span style="padding-right: 10px"><strong>Total : {{$sums['sumFindingPDI']}}</strong></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-area"><canvas id="myAreaChart2" width="100%" height="30"></canvas></div>
                            </div>
                            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header text-dark">
                                <div class="d-flex justify-content-between">
                                    <span>  Pending({{now()->format('F Y') }})</span>
                                    <span style="padding-right: 10px"><strong>Total : {{$sums['sumPending']}}</strong></span>
                                </div>
                                </div>

                            <div class="card-body">
                                <div class="chart-area"><canvas id="myAreaChart3" width="100%" height="30"></canvas></div>
                            </div>
                            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
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
        // Set new default font family and font color to mimic Bootstrap's default styling
        (Chart.defaults.global.defaultFontFamily = "Metropolis"),
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = "#858796";

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + "").replace(",", "").replace(" ", "");
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
                dec = typeof dec_point === "undefined" ? "." : dec_point,
                s = "",
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return "" + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || "").length < prec) {
                s[1] = s[1] || "";
                s[1] += new Array(prec - s[1].length + 1).join("0");
            }
            return s.join(dec);
        }

        var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
    type: "line",
    data: {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
        datasets: [{
            label: "Finding",
            lineTension: 0,
            backgroundColor: "rgba(60, 60, 60, 0.5)",
            borderColor: "#f50505",
            pointRadius: 3,
            pointBackgroundColor: "#000000",
            pointBorderColor: "#000000",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "#f50505",
            pointHoverBorderColor: "#f50505",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: {!! json_encode($findingQGCount) !!} // Pass PHP array to JavaScript here
        }]
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            xAxes: [{
                time: {
                    unit: "date"
                },
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 31
                }
            }],
            yAxes: [{
                ticks: {
                    max: 10, // Set max value here
                    maxTicksLimit: 10,
                    padding: 10,
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return "" + number_format(value);
                    }
                },
                gridLines: {
                    color: "#000000",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }]
        },
        legend: {
            display: false
        },
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: "#6e707e",
            titleFontSize: 14,
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: "index",
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                    var datasetLabel =
                        chart.datasets[tooltipItem.datasetIndex].label || "";
                    return datasetLabel + ": " + number_format(tooltipItem.yLabel);
                }
            }
        },
        onClick: function(evt, elements) {
            if (elements.length > 0) {
                // Get the label for the clicked element
                var label = this.data.labels[elements[0]._index];
                var role = 'qg';
                // Construct the URL using the label (date)
                var url = 'detail/'+ role + '/' + label;

                // Open the URL in a new tab
                window.open(url, '_blank');
            }
        }
    }
});

var ctx = document.getElementById("myAreaChart2");
var myLineChart = new Chart(ctx, {
    type: "line",
    data: {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
        datasets: [{
            label: "Finding",
            lineTension: 0,
            backgroundColor: "rgba(60, 60, 60, 0.5)",
            borderColor: "#f50505",
            pointRadius: 3,
            pointBackgroundColor: "#000000",
            pointBorderColor: "#000000",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "#f50505",
            pointHoverBorderColor: "#f50505",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: {!! json_encode($findingPDICount) !!} // Pass PHP array to JavaScript here
        }]
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            xAxes: [{
                time: {
                    unit: "date"
                },
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 31
                }
            }],
            yAxes: [{
                ticks: {
                    max: 10, // Set max value here
                    maxTicksLimit: 10,
                    padding: 10,
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return "" + number_format(value);
                    }
                },
                gridLines: {
                    color: "#000000",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }]
        },
        legend: {
            display: false
        },
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: "#6e707e",
            titleFontSize: 14,
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: "index",
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                    var datasetLabel =
                        chart.datasets[tooltipItem.datasetIndex].label || "";
                    return datasetLabel + ": " + number_format(tooltipItem.yLabel);
                }
            }
        },
        // New code for adding anchor tags to the points
       // New code for adding anchor tags to the points
       onClick: function(event, chartElement) {
            var point = chartElement[0];
            if (point) {
                var label = this.data.labels[point._index];
                var role = 'pdi'; // set your role here
                // Construct the URL using the label (date) and role
                var url = '/detail/' + role + '/' + label;
                // Open the URL in a new tab
                window.open(url, '_blank');
            }
        }

    }
});


    var ctx = document.getElementById("myAreaChart3");
    var myLineChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
            datasets: [{
                label: "Pending",
                lineTension: 0,
                backgroundColor: "rgba(60, 60, 60, 0.5)",
                borderColor: "#f50505",
                pointRadius: 3,
                pointBackgroundColor: "#000000",
                pointBorderColor: "#000000",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "#f50505",
                pointHoverBorderColor: "#f50505",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: {!! json_encode($pendingCount) !!} // Pass PHP array to JavaScript here
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: "date"
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 31
                    }
                }],
                yAxes: [{
                    ticks: {
                        max: 10, // Set max value here
                        maxTicksLimit: 10,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                            return "" + number_format(value);
                        }
                    },
                    gridLines: {
                        color: "#000000",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }]
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: "#6e707e",
                titleFontSize: 14,
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: "index",
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel =
                            chart.datasets[tooltipItem.datasetIndex].label || "";
                        return datasetLabel + ": " + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });


    </script>
</main>
@endsection
