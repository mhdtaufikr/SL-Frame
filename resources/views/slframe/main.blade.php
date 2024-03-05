@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
                {{-- <div class="row align-items-center justify-content-between">
                    <div class="col-auto mt-4">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="tool"></i></div>
                            Dropdown App Menu
                        </h1>
                        <div class="page-header-subtitle">Use this blank page as a starting point for creating new pages inside your project!</div>
                    </div>
                    <div class="col-12 col-xl-auto mt-4">Optional page header content</div>
                </div> --}}
            </div>
        </div>
    </header>
<!-- Main page content-->
<div class="container-xl px-4 mt-n10">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      {{-- <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>    </h1>
          </div>
        </div>
      </div><!-- /.container-fluid --> --}}
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List of SL-Frame</h3>
              </div>

              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">

                    <div class="col-sm-12">
                      <!--alert success -->
                      @if (session('status'))
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('status') }}</strong>
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

                    <div class="mb-3 col-sm-6">
                        <form action="{{ url('/frame/search') }}" method="POST" id="searchForm">
                            @csrf
                            <div class="input-group input-group-sm">
                                <select class="form-control" name="searchBy" id="searchBy">
                                    <option value="">Search By</option>
                                    <option value="production_date_range">Production Date Range</option>
                                    <option value="created_at_date_range">Created at Date Range</option>
                                    <option value="no_frame">No Frame</option>
                                </select>
                                <input name="frameNo" type="text" class="form-control" id="searchNoFrame" placeholder="Enter search term" style="display: none;">
                                <input name="dateFrom" type="date" class="form-control" id="startDate" placeholder="Start Date" style="display: none;">
                                <input name="dateTo" type="date" class="form-control" id="endDate" placeholder="End Date" style="display: none;">
                                <button class="btn btn-dark btn-sm" type="submit">Search</button>
                            </div>
                        </form>
                    </div>

                    <script>
                       $(document).ready(function() {
                        $('#searchBy').change(function() {
                            var selectedOption = $(this).val();
                            $('#searchNoFrame, #startDate, #endDate').hide();
                            if (selectedOption === 'production_date_range') {
                                $('#startDate, #endDate').show();
                            } else if (selectedOption === 'created_at_date_range') {
                                // Show the inputs for created at date range
                                $('#startDate, #endDate').show();
                            } else if (selectedOption === 'no_frame') {
                                $('#searchNoFrame').show();
                            }
                        });
                    });

                    </script>




                    <div class="mb-3 col-sm-12">
                        <button title="Export to Excel" type="button" class="btn btn-success btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-export-excel">
                            Export to Excel
                        </button>

                        <!-- Export to Excel Modal -->
                        <div class="modal fade" id="modal-export-excel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Export to Excel</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Add any content related to exporting to Excel here -->
                                    <p>Choose export options and click export.</p>
                                    <!-- You can add form elements, checkboxes, or any other export-related options here -->

                                    <div class="col-sm-12 mb-2">
                                        <form action="{{ url('/export') }}" method="GET">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" name="searchBy" id="searchByModal" onchange="toggleSearchInputs()">
                                                    <option value="">Export by</option>
                                                    <option value="dateRangeCreatedAt">Date Range Created At</option>
                                                    <option value="dateRangeProductionDate">Date Range Production Date</option>
                                                    <option value="inspectionLevel">Inspection Level</option>
                                                </select>

                                                <input name="startDate" type="date" class="form-control" id="startDateModal" style="display: none;">
                                                <input name="endDate" type="date" class="form-control" id="endDateModal" style="display: none;">
                                                <select class="form-control" name="inspectionLevel" id="inspectionLevelModal" style="display: none;">
                                                    <option value="">Select Inspection Level</option>
                                                    <option value="qg">QG</option>
                                                    <option value="pdi">PDI</option>
                                                </select>

                                                <button class="btn btn-success btn-sm" type="submit">Export</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                        </div>

                        <script>
                           $(document).ready(function() {
                            $('#searchByModal').on('change', function() {
                                var searchBy = $(this).val();
                                var startDateInput = $('#startDateModal');
                                var endDateInput = $('#endDateModal');
                                var productionDateInput = $('#productionDateModal');
                                var inspectionLevelInput = $('#inspectionLevelModal');

                                if (searchBy === 'dateRangeCreatedAt') {
                                    startDateInput.show();
                                    endDateInput.show();
                                    productionDateInput.hide();
                                    inspectionLevelInput.hide();
                                } else if (searchBy === 'dateRangeProductionDate') {
                                    startDateInput.show();
                                    endDateInput.show();
                                    productionDateInput.show();
                                    inspectionLevelInput.hide();
                                } else if (searchBy === 'inspectionLevel') {
                                    startDateInput.hide();
                                    endDateInput.hide();
                                    productionDateInput.hide();
                                    inspectionLevelInput.show();
                                } else {
                                    startDateInput.hide();
                                    endDateInput.hide();
                                    productionDateInput.hide();
                                    inspectionLevelInput.hide();
                                }
                            });
                        });

                        </script>
                </div>

                <div class="table-responsive">
                <table id="tableUser" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>No. Frame</th>
                    <th>TglProd</th>
                    <th>PIC</th>
                    <th>Remarks</th>
                    <th>status</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                      $no=1;
                    @endphp
                    @foreach ($Commoninformation as $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data->NoFrame }}</td>
                        <td>{{ date('d-m-Y', strtotime($data->TglProd)) }}</td>
                        <td><p>QG : {{$data->NamaQG}}</p>
                            <p>PDI : {{$data->PDI}}</p>
                        </td>
                        <td>{{ $data->Remarks}}</td>

                        <td>@if ($data->QualityStatus == "Bad")
                            <a href="#" class="btn btn-danger btn-sm">Bad</a>
                        @else
                        <a href="#" class="btn btn-success btn-sm">Good</a>
                        @endif</td>
                        <td>{{ date('d-m-Y', strtotime($data->created_at)) }}</td>
                        <td>
                          <a title="Detail" class="btn btn-primary btn-sm" href="{{url("detail/".$data->NoFrame)}}"> <i class="fas fa-info"></i></a>
                          <button title="Delete" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                            <i class="fas fa-trash"></i>
                        </button>

                        </td>
                    </tr>

                    {{-- Modal Delete --}}
                    <div class="modal fade" id="modal-delete{{ $data->id }}" tabindex="-1" aria-labelledby="modal-delete{{ $data->id }}-label" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="modal-delete{{ $data->id }}-label">Delete SL-Frame Record</h4>
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
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>


</main>
<!-- For Datatables -->
<script>
$(document).ready(function () {
    var table = $("#tableUser").DataTable({
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "order": [],
        "dom": 'Bfrtip',
        "buttons": []  // Set buttons to an empty array to disable export button
    });
});

  </script>
@endsection
