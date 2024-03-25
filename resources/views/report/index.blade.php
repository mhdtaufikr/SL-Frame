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
                <h3 class="card-title">List of Report</h3>
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

                <div class="mb-3 col-sm-12">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#add-folder">
                        Add Folder
                    </button>


                    <div class="modal fade" id="add-folder" tabindex="-1" aria-labelledby="add-folder-label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="add-folder-label">Add Folder</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modals" aria-label="Close"></button>
                                </div>
                                <form action="{{route('reports.folder')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Folder Name</label>
                                            <input  required type="text" class="form-control" id="title" name="folder">
                                        </div>

                                        <div class="mb-3">
                                            <label for="title" class="form-label">File Name</label>
                                            <input  required type="text" class="form-control" id="title" name="title">
                                        </div>

                                        <div class="mb-3">
                                            <label for="">Initiate File</label>
                                            <input required type="file" class="form-control" id="csvFile" name="file" accept=".xlsx">

                                        </div>

                                        @error('excel-file')
                                            <div class="alert alert-danger" role="alert">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Folder</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="table-responsive">
                <table id="tableUser" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>Folder</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                      $no=1;
                    @endphp
                    @foreach ($folders as $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data->folder }}</td>
                        <td>
                            <button title="Add File" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-upload-{{ $data->folder }}">
                                <i class="fas fa-plus"></i>
                            </button>
                         <!-- Button to open the modal -->
                        <button title="Detail" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-detail-{{ $data->folder }}">
                            <i class="fas fa-info"></i>
                        </button>
                          <button title="Delete" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                        </td>
                    </tr>

                     <!-- Modal for uploading file -->
                        <div class="modal fade" id="modal-upload-{{ $data->folder }}" tabindex="-1" aria-labelledby="modal-upload-label-{{ $data->folder }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-upload-label-{{ $data->folder }}">Upload File ({{ $data->folder }})</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('reports.upload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="folder" value="{{ $data->folder }}">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="file" class="form-label">Select File</label>
                                                <input required type="file" class="form-control" id="file" name="file">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    {{-- Modal Delete --}}
                    <div class="modal fade" id="modal-delete{{ $data->id }}" tabindex="-1" aria-labelledby="modal-delete{{ $data->id }}-label" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="modal-delete{{ $data->id }}-label">Delete Folder {{ $data->folder }}?</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/reports/delete/folder/'.$data->folder) }}" method="POST">
                            @csrf
                            @method('delete')
                            <div class="modal-body">
                                <div class="form-group">
                                  Are you sure you want to delete <label for="Dropdown"> {{$data->title}}</label>?
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

                @foreach ($folders as $folder)
    <!-- Modal for folder: {{ $folder->folder }} -->
    <div class="modal fade" id="modal-detail-{{ $folder->folder }}" tabindex="-1" aria-labelledby="modal-detail-title" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-detail-title">Files in Folder "{{ $folder->folder }}"</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- File list for the folder -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                @if ($report->folder === $folder->folder)
                                    <tr>
                                        <td>{{ $report->title }}</td>
                                        <td>{{ date('d-m-Y', strtotime($report->created_at)) }}</td>
                                        <td>
                                            <a title="Download" class="btn btn-success btn-sm" href="{{ url('/reports/download/'.$report->id) }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a title="delete" class="btn btn-danger btn-sm" href="{{ url('/reports/delete/'.$report->id) }}">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach


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
