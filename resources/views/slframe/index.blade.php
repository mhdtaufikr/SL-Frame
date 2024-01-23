@extends('layouts.master')

<style>
    /* styles.css */

    .card-body p {
        font-size: 10px; /* Adjust as needed */
    }

    .modal-dialog {
        max-width: 80%; /* Adjust as needed */
    }
</style>

@section('content')
<main>
    <header class="p-2 page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <!-- Header content -->
    </header>
    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="card">
            <div class="card-header text-dark" style="font-size: 0.8rem">{{$Commoninformation->NoFrame}}</div>
            <div class="card-body text-center d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/img/SL-Frame.PNG') }}" alt="" class="img-fluid">
            </div>
            <div class="row mt-4 mb-4 p-2">
                @foreach ($itemCheckGroups as $checkGroup => $itemCheckGroup)
                    <div class="col-md-2 col-sm-4 p-2">
                        <div class="card h-100" data-bs-toggle="modal" data-bs-target="#modal{{ $checkGroup }}">
                            <div class="card-header text-dark text-center">
                                <strong>{{ $checkGroup }}</strong>
                            </div>
                            <div class="card-body">
                                @foreach ($itemCheckGroup as $item)
                                    <p>{{ $item->ItemCheck }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="modal{{ $checkGroup }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ $checkGroup }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="">
                                    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ItemCheck</th>
                                                    <th>FindingQC</th>
                                                    <th>RepairQC</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($itemCheckGroup as $item)
                                                    <tr>
                                                        <td>{{ $item->ItemCheck }}</td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="findingQC{{ $item->id }}">
                                                                <label class="form-check-label" for="findingQC{{ $item->id }}"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="repairQC{{ $item->id }}">
                                                                <label class="form-check-label" for="repairQC{{ $item->id }}"></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <hr>
                                        <label for="remarks" style="font-size: 1em;">Remarks</label>
                                        <textarea class="form-control" id="remarks" rows="5"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection
