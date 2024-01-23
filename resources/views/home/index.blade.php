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
     <div class="container-xl px-4 mt-n10">
        <div class="card">
            {{-- <div class="card-header">Example Card</div> --}}
            <div class="card-body">
                <div class="text-center">
                    <form action="{{ url('/slframe') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="no_frame">Input No. Frame</label>
                        <input type="text" class="form-control mb-4 mt-4" name="no_frame" id="">
                        <button class="btn btn-success" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
</main>
@endsection