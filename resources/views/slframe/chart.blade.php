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
                    
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header text-dark">Finding By QG ({{ now()->format('Y') }})</div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="myBarChart1" width="100%" height="50"></canvas>
                                </div>
                            </div>
                            <div class="card-footer small text-muted">
                                <p> Updated {{ now()->format('F d, Y \a\t h:i A') }}</p>
                                <div class="additional-info text-dark">
                                   
                                    <div class="row">
                                        <div class="col-md-6">
                                            @foreach ($dates as $item)
                                            <p>Date Range: 
                                                    {{$item}}
                                            </p>
                                            @endforeach
                                        </div>
                                        <div class="col-md-6">
                                            @foreach($findingQGCount as $index => $count)
                                            <p>Finding : {{ $count }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header text-dark">Finding By PDI ({{ now()->format('Y') }})</div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="myBarChart2" width="100%" height="50"></canvas>
                                </div>
                            </div>
                            <div class="card-footer small text-muted">
                                <p> Updated {{ now()->format('F d, Y \a\t h:i A') }}</p>
                                <div class="additional-info text-dark">
                                   
                                    <div class="row">
                                        <div class="col-md-6">
                                            @foreach ($dates as $item)
                                            <p>Date Range: 
                                                    {{$item}}
                                            </p>
                                            @endforeach
                                        </div>
                                        <div class="col-md-6">
                                            @foreach($findingPDICount as $index => $count)
                                            <p>Finding : {{ $count }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header text-dark">Pending SL-Frame ({{ now()->format('Y') }})</div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="myBarChart3" width="100%" height="50"></canvas>
                                </div>
                            </div>
                            <div class="card-footer small text-muted">
                                <p>Updated {{ now()->format('F d, Y \a\t h:i A') }}</p>
                                <div class="additional-info text-dark">
                                   
                                    <div class="row">
                                        <div class="col-md-6">
                                            @foreach ($dates as $item)
                                            <p>Date Range: 
                                                    {{$item}}
                                            </p>
                                            @endforeach
                                        </div>
                                        <div class="col-md-6">
                                            @foreach($pendingCount as $index => $count)
                                            <p>Pending : {{ $count }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                 
                                </div>
                            </div>
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

            // Bar Chart Example
            var ctx = document.getElementById("myBarChart1");
            var myBarChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["1-5", "6-10", "11-15", "16-20", "21-25", "26-31"],
                    datasets: [{
                        label: "Finding : ",
                        backgroundColor: "#ff0008",
                        hoverBackgroundColor: "#5c0205",
                        borderColor: "#4e73df",
                        data: {!! json_encode($findingQGCount) !!},
                        maxBarThickness: 25
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
                                unit: "month"
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 6
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                max: 30,
                                maxTicksLimit: 15,
                                padding: 10,
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return number_format(value);
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
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
                        titleMarginBottom: 10,
                        titleFontColor: "#6e707e",
                        titleFontSize: 14,
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: "#dddfeb",
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                var datasetLabel =
                                    chart.datasets[tooltipItem.datasetIndex].label || "";
                                return datasetLabel + number_format(tooltipItem.yLabel);
                            }
                        }
                    }
                }
            });

            // Bar Chart Example
            var ctx = document.getElementById("myBarChart2");
            var myBarChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["1-5", "6-10", "11-15", "16-20", "21-25", "26-31"],
                    datasets: [{
                        label: "Finding : ",
                      backgroundColor: "#ff0008",
                        hoverBackgroundColor: "#5c0205",
                        borderColor: "#4e73df",
                        data: {!! json_encode($findingPDICount) !!}, 
                        maxBarThickness: 25
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
                                unit: "month"
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 6
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                max: 30,
                                maxTicksLimit: 15,
                                padding: 10,
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return number_format(value);
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
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
                        titleMarginBottom: 10,
                        titleFontColor: "#6e707e",
                        titleFontSize: 14,
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: "#dddfeb",
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                var datasetLabel =
                                    chart.datasets[tooltipItem.datasetIndex].label || "";
                                return datasetLabel + number_format(tooltipItem.yLabel);
                            }
                        }
                    }
                }
            });

            // Bar Chart Example
            var ctx = document.getElementById("myBarChart3");
            var myBarChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["1-5", "6-10", "11-15", "16-20", "21-25", "26-31"],
                    datasets: [{
                        label: "Pending : ",
                      backgroundColor: "#ff0008",
                        hoverBackgroundColor: "#5c0205",
                        borderColor: "#4e73df",
                        data: {!! json_encode($pendingCount) !!}, 
                        maxBarThickness: 25
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
                                unit: "month"
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 6
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                max: 30,
                                maxTicksLimit: 15,
                                padding: 10,
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return number_format(value);
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
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
                        titleMarginBottom: 10,
                        titleFontColor: "#6e707e",
                        titleFontSize: 14,
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: "#dddfeb",
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                var datasetLabel =
                                    chart.datasets[tooltipItem.datasetIndex].label || "";
                                return datasetLabel + number_format(tooltipItem.yLabel);
                            }
                        }
                    }
                }
            });
    </script>
</main>
@endsection
