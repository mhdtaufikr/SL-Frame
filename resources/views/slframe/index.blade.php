@extends('layouts.master')

<style>
    /* styles.css */

    .card-body p {
        font-size: 10px; /* Adjust as needed */
    }

    .modal-dialog {
        max-width: 80%; /* Adjust as needed */
    }

    .bigger-checkbox {
        width: 25px; /* Adjust the width as needed */
        height: 25px; /* Adjust the height as needed */
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
            <div class="card-header text-dark">
                <div class="d-flex justify-content-between">
                    <h1> <strong>{{ $Commoninformation->NoFrame }}</strong></h1>
                    
                    <span class="ml-auto">
                        @if($Commoninformation->Status == 0)
                            <button class="btn btn-info btn-md">On Going</button>
                        @elseif($Commoninformation->Status == 1)
                            <button class="btn btn-warning btn-md">Pending</button>
                        @elseif($Commoninformation->Status == 2)
                            <button class="btn btn-success btn-md">Done</button>
                        @else
                            <span class="text-danger">Unknown Status</span>
                        @endif
                        <button class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#additionalInfoModal">Submit</button>
                    </span>
                </div>
                
                
            </div>
            
            
            
            <div class="card-body text-center d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/img/SL-Frame.PNG') }}" alt="" class="img-fluid">
            </div>
            <div class="row p-2">
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
                                <form action="{{ route('submit') }}" method="POST">
                                    @csrf
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
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input bigger-checkbox" type="checkbox" name="findingQC[]" value="{{ $item->ItemCheck }}">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input bigger-checkbox" type="checkbox" name="repairQC[]" value="{{ $item->ItemCheck }}">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <hr>
                                        <label for="remarks" style="font-size: 1em;">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" rows="5"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
<!-- ... Your existing code ... -->



<!-- Modal for Additional Information -->
<div class="modal fade" id="additionalInfoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Additional Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('submit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="noFrame" class="form-label">No Frame</label>
                            <input  value="{{$Commoninformation->NoFrame}}" type="text" class="form-control" id="noFrame" name="noFrame">
                        </div>
                        <div class="col-sm-6">
                            <label for="tglProd" class="form-label">Tanggal Production</label>
                            <input  type="date" class="form-control" id="tglProd" name="tglProd" value="{{ now()->toDateString() }}">
                        </div>                        
                        <div class="col-sm-6">
                            <label for="shift" class="form-label">Shift</label>
                            <input type="number" class="form-control" id="shift" name="shift" value="{{ getShiftValue() }}" >
                        </div>
                        <div class="col-sm-6">
                            <label for="nameOfQG" class="form-label">Name of QG</label>
                            <input  value="{{ auth()->user()->name }}" type="text" class="form-control" id="nameOfQG" name="nameOfQG">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@php
    function getShiftValue() {
        $currentHour = date('H');

        if ($currentHour >= 7 && $currentHour < 15) {
            return 1;
        } elseif ($currentHour >= 15 && $currentHour < 23) {
            return 2;
        } else {
            // You may want to handle other cases or set a default value
            return 1;
        }
    }
@endphp
<!-- ... Your existing code ... -->


        </div>
    </div>
</main>
@endsection
