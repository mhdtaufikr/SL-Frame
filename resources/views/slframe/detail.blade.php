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
                            <button class="btn btn-info btn-md">Waiting List</button>
                        @elseif($Commoninformation->Status == 1)
                            <button class="btn btn-warning btn-md">Pending</button>
                        @elseif($Commoninformation->Status == 2)
                            <button class="btn btn-success btn-md">Done</button>
                        @else
                            <span class="text-danger">Unknown Status</span>
                        @endif
                        <button id="submitButton" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#additionalInfoModal">Info</button>
                    </span>
                </div>
            </div>
                        
            <div class="card-body text-center d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/img/SL-Frame.PNG') }}" alt="" class="img-fluid">
            </div>
            <div class="row p-2">
                @foreach ($itemCheckGroups as $checkGroup => $itemCheckGroup)
                    @if ($checkGroup < 6 || \Auth::user()->role !== 'QG')
                    <div class="col-md-2 col-sm-4 p-2 mb-2">
                        <div class="form-check text-center d-flex align-items-center justify-content-center mb-2">
                            <div class="form-check text-center d-flex align-items-center justify-content-center mb-2">
                                @php
                                    $isCheckedGroup = $checkSheet->where('checkGroup', $checkGroup)->isNotEmpty();
                                @endphp
                                <input class="form-check-input check-checkbox custom-checkbox" type="checkbox" id="check{{ $checkGroup }}" {{ $isCheckedGroup ? 'checked' : '' }} onchange="updateSubmitButtonState('{{ $checkGroup }}')">
                                <p class="mb-0 ml-2">Finding & Repair</p>
                            </div>
                        </div>
                        
                        <div class="card h-100" data-bs-toggle="modal" data-bs-target="#modal{{ $checkGroup }}">
                            <div class="card-header text-dark text-center">
                                @if ($checkGroup == "5")
                                5 & 6
                           @elseif($checkGroup == "6")
                                Painting
                            @else
                                {{ $checkGroup }}
                            @endif
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
                                    <h5 class="modal-title">            
                                        @if ($checkGroup == "5")
                                            5 & 6
                                       @elseif($checkGroup == "6")
                                            Painting
                                        @else
                                            {{ $checkGroup }}
                                        @endif
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                @if (\Auth::user()->role === 'PDI')
                                    <form action="{{ route('submitPDI') }}" method="POST">
                                @else
                                    <form action="{{ url('/submit') }}" method="POST">
                                @endif
                                    @csrf
                                    <input value="{{$noframe}}" hidden name="noframe" type="">
                                    <input value="{{$checkGroup}}" hidden name="checkGroup" type="">
                                    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ItemCheck</th>
                                                    <th>FindingQG</th>
                                                    <th>RepairQG</th>
                                                    <th {{ \Auth::user()->role === 'QG' ? 'hidden' : '' }} >FindingPDI</th>
                                                    <th {{ \Auth::user()->role === 'QG' ? 'hidden' : '' }} >RepairPDI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($itemCheckGroup as $item)
                                                <tr>
                                                     <!-- Add hidden input fields for unchecked checkboxes -->
                                                     <input type="hidden" name="findingQC[{{ $item->ItemCheck }}]" value="0">
                                                     <input type="hidden" name="repairQC[{{ $item->ItemCheck }}]" value="0">
                                                    <td>{{ $item->ItemCheck }}</td>
                                                    @if (\Auth::user()->role === 'QG')
                                                    <td>
                                                        <div class="form-check d-flex justify-content-center">
                                                            @php
                                                                $isCheckedFinding = $checkSheet->contains('ItemCheck', $item->ItemCheck) && $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->FindingQG == 1;
                                                                $remarksFinding = $isCheckedFinding ? $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RemarksQG : null;
                                                            @endphp
                                                            <input class="form-check-input bigger-checkbox finding-qc-checkbox" type="checkbox" name="findingQC[{{ $item->ItemCheck }}]" value="1" {{ $isCheckedFinding ? 'checked' : '' }} onchange="handleFindingQCChange(this)">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check d-flex justify-content-center">
                                                            @php
                                                                $isCheckedRepair = $checkSheet->contains('ItemCheck', $item->ItemCheck) && $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RepairQG == 1;
                                                                $remarksRepair = $isCheckedRepair ? $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RemarksQG : null;
                                                            @endphp
                                                            <input class="form-check-input bigger-checkbox repair-qc-checkbox" type="checkbox" name="repairQC[{{ $item->ItemCheck }}]" value="1" {{ $isCheckedRepair ? 'checked' : '' }} {{ \Auth::user()->role !== 'QG' ? 'disabled' : '' }}>
                                                        </div>
                                                    </td>
                                                @else
                                                    <td>
                                                        <div class="form-check d-flex justify-content-center">
                                                            @php
                                                                $isCheckedFinding = $checkSheet->contains('ItemCheck', $item->ItemCheck) && $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->FindingQG == 1;
                                                                $remarksFinding = $isCheckedFinding ? $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RemarksQG : null;
                                                            @endphp
                                                            <input disabled class="form-check-input bigger-checkbox finding-qc-checkbox" type="checkbox" name="findingQC[{{ $item->ItemCheck }}]" value="1" {{ $isCheckedFinding ? 'checked' : '' }} >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check d-flex justify-content-center">
                                                            @php
                                                                $isCheckedRepair = $checkSheet->contains('ItemCheck', $item->ItemCheck) && $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RepairQG == 1;
                                                                $remarksRepair = $isCheckedRepair ? $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RemarksQG : null;
                                                            @endphp
                                                            <input disabled class="form-check-input bigger-checkbox " type="checkbox" name="repairQC[{{ $item->ItemCheck }}]" value="1" {{ $isCheckedRepair ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                @endif
                                                        <td>
                                                            <div class="form-check d-flex justify-content-center">
                                                                @php
                                                                    $isCheckedFindingPDI = $checkSheet->contains('ItemCheck', $item->ItemCheck) && $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->FindingPDI == 1;
                                                                    $remarksFindingPDI = $isCheckedFindingPDI ? $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RemarksPDI : null;
                                                                @endphp
                                                                <!-- Add hidden input field for unchecked checkbox -->
                                                                <input type="hidden" name="findingPDI[{{ $item->ItemCheck }}]" value="0">
                                                                <input class="form-check-input bigger-checkbox finding-pdi-checkbox" type="checkbox" name="findingPDI[{{ $item->ItemCheck }}]" value="1" {{ $isCheckedFindingPDI ? 'checked' : '' }} onchange="handleFindingPDIChange(this)" disabled>
                                                            </div>                                                        
                                                        </td>
                                                        <td>
                                                            <div class="form-check d-flex justify-content-center">
                                                                @php
                                                                    $isCheckedRepairPDI = $checkSheet->contains('ItemCheck', $item->ItemCheck) && $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RepairPDI == 1;
                                                                    $remarksRepairPDI = $isCheckedRepairPDI ? $checkSheet->where('ItemCheck', $item->ItemCheck)->first()->RemarksPDI : null;
                                                                @endphp
                                                                <!-- Add hidden input field for unchecked checkbox -->
                                                                <input type="hidden" name="repairPDI[{{ $item->ItemCheck }}]" value="0">
                                                                <input class="form-check-input bigger-checkbox repair-pdi-checkbox" type="checkbox" name="repairPDI[{{ $item->ItemCheck }}]" value="1" {{ $isCheckedRepairPDI ? 'checked' : 'disabled' }} disabled>
                                                            </div>                                                                                                                
                                                        </td>
                                                </tr>
                                            @endforeach
                                            
                                            </tbody>
                                        </table>
                                        <hr>
                                        <label for="remarks" style="font-size: 1em;">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" rows="5" disabled>{{ $remarksFinding ?: $remarksRepair ?: optional($checkSheet->where('checkGroup', $checkGroup)->first())->Remarks}}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                       
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
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
                                    <div class="col-sm-5">
                                        <label for="noFrame" class="form-label">No Frame</label>
                                        <input disabled  value="{{$Commoninformation->NoFrame}}" type="text" class="form-control" id="noFrame" name="noFrame">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="tglProd" class="form-label">Tgl. Production</label>
                                        <input disabled  type="date" class="form-control" id="tglProd" name="tglProd" value="{{ $Commoninformation->TglProd }}">
                                    </div>                        
                                    <div class="col-sm-3">
                                        <label for="shift" class="form-label">Shift</label>
                                        <input disabled type="number" class="form-control" id="shift" name="shift" value="{{ $Commoninformation->Shift }}" >
                                    </div>
                                    <div class="col-sm-6">
                                    <label for="name" class="form-label">Name of QG</label>
                                        <input disabled value="{{ $Commoninformation->NamaQG}}" type="text" class="form-control" id="nameOfQG" name="name">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="name" class="form-label">Name of PDI</label>
                                            <input disabled value="{{ $Commoninformation->PDI }}" type="text" class="form-control" id="nameOfQG" name="name">
                                        </div>
                                    <div class="col-sm-12">
                                        <label for="remarks" style="font-size: 1em;">Remarks</label>
                                        <textarea disabled class="form-control" name="remarks" id="remarks" rows="5">{{$Commoninformation->Remarks}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                // Ensure that this code runs after the DOM is fully loaded
    document.addEventListener("DOMContentLoaded", function() {
        // Find all FindingQC checkboxes on the page
        var findingQCCheckboxes = document.querySelectorAll('.finding-qc-checkbox');

        // Iterate through each FindingQC checkbox
        findingQCCheckboxes.forEach(function(findingQCCheckbox) {
            // Get the corresponding RepairQC checkbox
            var repairQCCheckbox = findingQCCheckbox.closest('tr').querySelector('.repair-qc-checkbox');

            // Enable/disable RepairQC based on the state of FindingQC
            repairQCCheckbox.disabled = !findingQCCheckbox.checked;

            // If FindingQC is unchecked, also uncheck RepairQC
            if (!findingQCCheckbox.checked) {
                repairQCCheckbox.checked = false;
            }
        });
    });

    // Function to handle FindingQC checkbox changes
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



               

                

            </script>

        </div>
    </div>
</main>
@endsection
