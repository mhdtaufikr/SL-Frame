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

    .custom-checkbox {
        width: 1.25em;  /* Adjust the width as needed */
        height: 1.25em; /* Adjust the height as needed */
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
                        <button id="submitButton" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#additionalInfoModal" disabled>Submit</button>
                    </span>
                </div>
            </div>
                        
            <div class="card-body text-center d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/img/SL-Frame.PNG') }}" alt="" class="img-fluid">
            </div>
            <div class="row p-2">
                @foreach ($itemCheckGroups as $checkGroup => $itemCheckGroup)
                    <div class="col-md-2 col-sm-4 p-2 mb-2">
                        <div class="form-check text-center d-flex align-items-center justify-content-center mb-2">
                            <div class="form-check text-center d-flex align-items-center justify-content-center mb-2">
                                @php
                                    $isCheckedGroup = $checkSheet->where('checkGroup', $checkGroup)->isNotEmpty();
                                @endphp
                                <input class="form-check-input check-checkbox custom-checkbox" type="checkbox" id="check{{ $checkGroup }}" {{ $isCheckedGroup ? 'checked' : '' }} onchange="updateSubmitButtonState('{{ $checkGroup }}')">
                                <p class="mb-0 ml-2">Check</p>
                            </div>
                        </div>
                        
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
                                    <input value="{{$noframe}}" hidden name="noframe" type="">
                                    <input value="{{$checkGroup}}" hidden name="checkGroup" type="">
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
                                                            @php
                                                                $isCheckedFinding = $checkSheet->contains('ItemCheck', $item->ItemCheck);
                                                                $remarksFinding = $isCheckedFinding ? $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RemarksQG : null;
                                                            @endphp
                                                            <input class="form-check-input bigger-checkbox finding-qc-checkbox" type="checkbox" name="findingQC[]" value="{{ $item->ItemCheck }}" {{ $isCheckedFinding ? 'checked' : '' }} onchange="handleFindingQCChange(this)">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check d-flex justify-content-center">
                                                            @php
                                                                $isCheckedRepair = $checkSheet->contains('ItemCheck', $item->ItemCheck);
                                                                $remarksRepair = $isCheckedRepair ? $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RemarksQG : null;
                                                            @endphp
                                                            <input class="form-check-input bigger-checkbox repair-qc-checkbox" type="checkbox" name="repairQC[]" value="{{ $item->ItemCheck }}" {{ $isCheckedRepair ? 'checked' : 'disabled' }}>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                            </tbody>
                                        </table>
                                        <hr>
                                        <label for="remarks" style="font-size: 1em;">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" rows="5">{{ $remarksFinding ?: $remarksRepair ?: optional($checkSheet->where('checkGroup', $checkGroup)->first())->RemarksQG }}</textarea>
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
                        <form action="{{ route('submitMain') }}" method="POST">
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
                                    @if(\Auth::user()->role === 'QG')
                                    <label for="name" class="form-label">Name of QG</label>
                                    @endif
                                    @if(\Auth::user()->role === 'PDI')
                                    <label for="name" class="form-label">Name of PDI</label>
                                    @endif
                                      
                                        <input  value="{{ auth()->user()->name }}" type="text" class="form-control" id="nameOfQG" name="name">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="remarks" style="font-size: 1em;">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" rows="5"></textarea>
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
            <script>
                function handleFindingQCChange(findingQCCheckbox) {
                    // Get the corresponding RepairQC checkbox
                    var repairQCCheckbox = findingQCCheckbox.closest('tr').querySelector('.repair-qc-checkbox');
                
                    // Enable/disable RepairQC based on the state of FindingQC
                    repairQCCheckbox.disabled = !findingQCCheckbox.checked;
                
                    // If FindingQC is unchecked, also uncheck RepairQC
                    if (!findingQCCheckbox.checked) {
                        repairQCCheckbox.checked = false;
                    }
                }

                $(document).ready(function () {
                    $('.check-checkbox').on('change', function () {
                        updateSubmitButtonState();
                    });
                });
            
                function updateSubmitButtonState() {
                    var totalCheckboxes = $('.check-checkbox').length;
                    var checkedCheckboxes = $('.check-checkbox:checked').length;
            
                    var allGroupsChecked = totalCheckboxes === checkedCheckboxes;
            
                    $('#submitButton').prop('disabled', !allGroupsChecked);
                }
            
                // This function ensures that the modal is opened only if at least one checkbox is checked
                $('#additionalInfoModal').on('show.bs.modal', function (event) {
                    if ($('.check-checkbox:checked').length === 0) {
                        // Prevent modal from opening if no checkboxes are checked
                        event.preventDefault();
                        alert('Please check at least one checkbox before submitting.');
                    }
                });
            </script>

        </div>
    </div>
</main>
@endsection
