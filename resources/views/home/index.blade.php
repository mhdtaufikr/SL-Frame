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
    <section class="content">
        <div class="container-fluid">
     <div class="container-xl px-4 mt-n10">
        @if ( \Auth::user()->role == 'QG' ||  \Auth::user()->role == 'PDI')
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
                        <input type="text" class="form-control mb-4 mt-4" name="no_frame" id="" required>
                        @endif
                        @if(\Auth::user()->role === 'PDI')
                        <select name="no_frame" id="noFrame" class="form-control chosen-select" required>
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
        @endif

   
          <div class="mt-4 row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Pending List</h3>
                </div>
                
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="row">
                  <div class=" mt-4 table-responsive"> 
                    <table id="tableUser" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>No. Frame</th>
                        @if(\Auth::user()->role === 'PDI')
                        <th>Inspection Level</th>
                        @endif
                        <th>PIC</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @php
                          $no=1;
                        @endphp 
                        @foreach (\Auth::user()->role === 'QG' ? $CommoninformationQG : $CommoninformationPDI as $data)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $data->NoFrame }}</td>
                        
                            @if (\Auth::user()->role === 'PDI')
                                <td>
                                    <div class="d-flex align-items-center">
                                        <p class="btn {{ $data->InspectionLevel == 1 ? 'btn-success' : 'btn-danger' }} btn-sm me-2">
                                            {{ $data->InspectionLevel == 1 ? 'QG' : 'PDI' }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <p>{{ $data->InspectionLevel == 1 ? $data->NamaQG : $data->PDI }}</p>
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div class="d-flex align-items-center">
                                        <p>{{ $data->NamaQG }}</p>
                                    </div>
                                </td>
                            @endif
                        
                            <td>
                                @if ($data->Status == 0)
                                    <button class="btn btn-sm btn-info btn-md">Waiting List</button>
                                @elseif ($data->Status == 1)
                                    <button class="btn btn-sm btn-warning btn-md">Pending</button>
                                @elseif ($data->Status == 2)
                                    <button class="btn btn-sm btn-success btn-md">Done</button>
                                @else
                                    <span class="text-danger">Unknown Status</span>
                                @endif
                            </td>
                        
                            <td>
                                
                        
                                @if(\Auth::user()->role === 'QG' || (\Auth::user()->role === 'PDI' && $data->InspectionLevel == 2))
                                <button title="Edit User" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>    
                                <a class="btn btn-primary btn-sm" href="{{ url('/slframe/'.$data->NoFrame) }}">
                                        <i class="fas fa-step-forward"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        
                    
                        {{-- Modal Delete --}}
                        <div class="modal fade" id="modal-delete{{ $data->id }}" tabindex="-1" aria-labelledby="modal-delete{{ $data->id }}-label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modal-delete{{ $data->id }}-label">Delete SL-Frame</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('/slframe/delete/'.$data->NoFrame) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <div class="modal-body">
                                            <div class="form-group">
                                                Are you sure you want to delete <label for="Dropdown">{{ $data->NoFrame }}</label>?
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- Modal Delete --}}
                    @endforeach                    
                      </tbody>
                    </table>
                  </div>
                </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
      

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
    <!-- For Datatables -->
<script>
    $(document).ready(function() {
      var table = $("#tableUser").DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      });
    });
  </script>
</main>
@endsection