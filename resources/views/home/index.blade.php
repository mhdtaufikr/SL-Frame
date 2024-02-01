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
                    <div class="col-sm-12">
                        <!--alert success -->
                        @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                          <strong>{{ session('status') }}</strong>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div> 
                      @endif
  
                      @if (session('failed'))
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session('failed') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div> 
                    @endif
                      
                        <!--alert success -->
                        <!--validasi form-->
                          @if (count($errors)>0)
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <ul>
                                    <li><strong>Data Process Failed !</strong></li>
                                    @foreach ($errors->all() as $error)
                                        <li><strong>{{ $error }}</strong></li>
                                    @endforeach
                                </ul>
                            </div>
                          @endif
                        <!--end validasi form-->
                      </div>
                    <form action="{{ url('/slframe') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label class="mb-4" for="no_frame">Input No. Frame</label>
                        @if(\Auth::user()->role === 'QG')
                        <input type="text" class="form-control mb-4 mt-4" name="no_frame" id="">
                        @endif
                        @if(\Auth::user()->role === 'PDI')
                        <select name="no_frame" id="noFrame" class="form-control chosen-select">
                            <option value="">- Please Select NO. Frame -</option>
                            @foreach ($Commoninformation as $data)
                                <option value="{{ $data->NoFrame }}">{{ $data->NoFrame }}</option>
                            @endforeach
                        </select>
                    
                        <!-- Initialize Chosen -->
                        <script>
                            $(document).ready(function () {
                                $(".chosen-select").chosen({
                                    search_contains: true,
                                    width: "100%" // Adjust the width as needed
                                });
                            });
                        </script>
                    @endif
                    
                        <button class="btn btn-success mt-4" type="submit">Submit</button>
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