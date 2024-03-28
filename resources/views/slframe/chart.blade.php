@extends('layouts.master')

@section('content')
<style>
    #lblGreetings {
        font-size: 1rem; /* Adjust the base font size as needed */
    }

    @media only screen and (max-width: 600px) {
        #lblGreetings {
            font-size: 1rem; /* Adjust the font size for smaller screens */
        }
    }
    .page-header .page-header-content {
  padding-top: 0rem;
  padding-bottom: 1rem;
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
                        <!-- Modal -->
                        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-body">
                                <img id="modalImage" src="{{ asset('assets/img/SL-Frame.PNG') }}" alt="Large Image" class="img-fluid">
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <img src="{{ asset('assets/img/SL-Frame.PNG') }}" alt="" class="img-fluid img-thumbnail" usemap="#image-map">
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var image = document.querySelector('img.img-thumbnail');
                                var modalImage = document.getElementById('modalImage');

                                image.addEventListener('click', function() {
                                    var largeImageUrl = this.src; // Get the URL of the larger image
                                    modalImage.src = largeImageUrl; // Set the src attribute of the modal image
                                    $('#imageModal').modal('show'); // Show the modal
                                });
                            });
                        </script>

                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="row text-center" style="margin-bottom: -20px">
                                        <div class="col-md-3">
                                            <a href="{{url('/slframe/detail/qg')}}">
                                            <button class="btn btn-teal btn-sm btn-icon mr-2">
                                                <i class="fas fa-medal  ml-4"></i>
                                            </button><p style="font-size: 12px">Finding By QG</p></a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{url('/slframe/detail/qg')}}">
                                            <button class="btn btn-purple btn-sm btn-icon mr-2">
                                                <i class="fas fa-truck-loading  ml-4"></i>
                                            </button><p style="font-size: 12px">Finding By PDI</p></a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{url('/checksheet')}}">
                                            <button class="btn btn-green btn-icon btn-sm">
                                                <i class="fas fa-hourglass-end  ml-4"></i>
                                            </button><p style="font-size: 12px">Pending</p></a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{url('/slframe/detail/reject')}}">
                                            <button class="btn btn-pink btn-icon btn-sm">
                                                <i class="fas fa-times"></i>
                                            </button><p style="font-size: 12px">Reject</p></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="chartContainer3" style="height: 290px; max-width: 920px; margin: -18px auto;"></div>
                                </div>
                            </div>
                        </div>          </div>
                    <div class="col-md-8">

                        <div class="col-md-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div id="chartContainer2" style="height: 210px; max-width: 920px; margin: 0px auto;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card mb-4  card-collapsable">
                                <div class="card-body" href="#collapseCardExample" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">

                                    <div id="chartContainer" style="height: 225px; max-width: 920px; margin: 0px auto;"></div>
                                    <div class="card-collapsable-arrow">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                 <div id="collapseCardExample" class="card-footer collapse ">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @foreach ($data->take(12) as $item)
                                                <p style="font-size: 12px; margin-bottom: -2px">{{ $item['id'] }} = {{ $item['ItemCheck'] }}</p>
                                            @endforeach
                                        </div>
                                        <div class="col-md-4">
                                            @foreach ($data->slice(12, 12) as $item)
                                                <p style="font-size: 12px; margin-bottom: -2px">{{ $item['id'] }} = {{ $item['ItemCheck'] }}</p>
                                            @endforeach
                                        </div>
                                        <div class="col-md-4">
                                            @foreach ($data->slice(24) as $item)
                                                <p style="font-size: 12px; margin-bottom: -2px">{{ $item['id'] }} = {{ $item['ItemCheck'] }}</p>
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


        window.onload = function () {

            var monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
        ];

        var now = new Date();
        var month = monthNames[now.getMonth()];
        var totalRecordsChecked = {{ $totalRecordsChecked }};
    var chart = new CanvasJS.Chart("chartContainer3", {
    theme: "light2",
    exportEnabled: true,
    animationEnabled: true,
    title: {
        text:  "Total = " + totalRecordsChecked + "(" + month + ")"

    },
    data: [{
        type: "pie",
        startAngle: 25,
        toolTipContent: "<b>{label}</b>: {y}",
        showInLegend: true,
        legendText: "{label}",
        indexLabelFontSize: 16,
        indexLabel: "{label} - {y}",
        dataPoints: [
            { y: {{ $sums['sumFindingPDI'] }}, label: "FindingPDI", role: "pdi" },
            { y: {{ $sums['sumFindingQG'] }}, label: "FindingQG", role: "qg" },
            { y: {{ $sums['sumReject'] }}, label: "Reject", role: "reject" },
            { y: {{ $sums['sumPending'] }}, label: "Pending", role: "pending" }

        ]
    }]
});

chart.render();

// Add click event handler for the data points
chart.options.data[0].click = function (e) {
    var role = e.dataPoint.role;
    if (role === "qg" || role === "pdi" || role === "reject") {
        var url = "/slframe/detail/" + role;
    } else if (role === "pending") {
        var url = "/checksheet";
    }
    window.open(url, '_blank');
};

        // Parse the data
        var chartData = {!! json_encode($data->toArray()) !!};

// Create the main chart
var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    exportEnabled: true,
    title: {
        text: "Count of finding & Repair Item Check SL - Frame (" + month + ")"
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
            label: item.id,
            y: item.CountChecksheet,
            name: item.ItemCheck, // Add the item check as the name property
            url: '{{ route("detailRole", ["role" => "itemCheck"]) }}'.replace('itemCheck', item.id)
        })),
        toolTipContent: "<b>Item Check:</b> {name}<br/><b>Count:</b> {y}" // Use {name} for the item check
    }]
});

// Add click event handler to open detail page
chart.options.data[0].click = function(e) {
    if (e.dataPoint.url) {
        window.open(e.dataPoint.url, '_blank');
    }
}

// Render the main chart
chart.render();




        var chart = new CanvasJS.Chart("chartContainer2", {
            animationEnabled: true,
            exportEnabled: true,
            title:{
                text: "Checksheet SL Frame"
            },
            axisY :{
                includeZero: false,
                prefix: ""
            },
            toolTip: {
                shared: true
            },
            legend: {
                fontSize: 13
            },
            data: [{
            type: "splineArea",
            showInLegend: true,
            name: "Finding QG",
            yValueFormatString: "#,##0",
            dataPoints: [
                @foreach($findingQGCount as $key => $value)
                    { x: new Date({{ Carbon\Carbon::now()->year }}, {{ Carbon\Carbon::now()->month - 1 }}, {{$key}}), y: {{$value}} },
                @endforeach
            ]
        },
        {
            type: "splineArea",
            showInLegend: true,
            name: "Finding PDI",
            yValueFormatString: "#,##0",
            dataPoints: [
                @foreach($findingPDICount as $key => $value)
                    { x: new Date({{ Carbon\Carbon::now()->year }}, {{ Carbon\Carbon::now()->month - 1 }}, {{$key}}), y: {{$value}} },
                @endforeach
            ]
        },
        {
            type: "splineArea",
            showInLegend: true,
            name: "Pending",
            yValueFormatString: "#,##0",
            dataPoints: [
                @foreach($pendingCount as $key => $value)
                    { x: new Date({{ Carbon\Carbon::now()->year }}, {{ Carbon\Carbon::now()->month - 1 }}, {{$key}}), y: {{$value}} },
                @endforeach
            ]
        }]

                });
                chart.render();

                }
</script>

</main>
@endsection
