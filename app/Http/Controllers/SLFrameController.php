<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commoninformation;
use App\Models\Itemcheckgroup;
use App\Models\Checksheet;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SLFrameExport;
use App\Exports\SLFrameExportQG;
use App\Exports\SLFrameExportPDI;


class SLFrameController extends Controller
{
    public function index(Request $request)
    {
        // Check if record with the given NoFrame already exists
        $Commoninformation = Commoninformation::where('NoFrame', $request->no_frame)->first();

        if ($Commoninformation) {
            if(Auth::user()->role == 'PDI'){
                if ($Commoninformation->Status == '2') {
                    return redirect()->route('checksheet')->with('failed', 'SL-Frame already checked.');
                }
                return redirect()->route('show', ['noframe' => $request->no_frame]);
            }
            if ($Commoninformation->InspectionLevel == 2) {
                // If InspectionLevel is already 2, store the error message in the session
                return redirect()->route('checksheet')->with('failed', 'SL-Frame already checked.');
            }
            // If the record exists, redirect to the view with existing data
            return redirect()->route('show', ['noframe' => $request->no_frame]);
        } else {
            // If the record doesn't exist, create a new record and redirect
            $Commoninformation = Commoninformation::create([
                'NoFrame' => $request->no_frame,
                'Status' => 0,
                'InspectionLevel' => 1,
                'NamaQG'=> Auth::user()->name,
            ]);
            return redirect()->route('show', ['noframe' => $request->no_frame]);
        }
    }
    public function show($noframe)
    {
        $Commoninformation = Commoninformation::where('NoFrame', $noframe)->first();
        if (Auth::user()->role == 'PDI') {
           if ($Commoninformation->InspectionLevel == 1) {
            return redirect()->route('checksheet')->with('failed', 'SL-Frame on QG Check.');
           }
        }
        if (Auth::user()->role == 'QG') {
            if ($Commoninformation->InspectionLevel == 2) {
                return redirect()->route('checksheet')->with('failed', 'SL-Frame Already Check.');
               }
        }
        // Define an array of CheckGroup values
        $checkGroups = [1, 2, 3, 4, 5, 6];
        // Fetch all Itemcheckgroups for the specified CheckGroup values
        $itemCheckGroups = Itemcheckgroup::whereIn('CheckGroup', $checkGroups)->get()->groupBy('CheckGroup');
        $checkSheet = Checksheet::where('CommonInfoID',$Commoninformation->CommonInfoID)->get();
        $commonInfoID = $Commoninformation->CommonInfoID;
        return view('slframe.index', compact('Commoninformation', 'itemCheckGroups','noframe','checkSheet','commonInfoID'));
    }
    public function submit(Request $request)
    {
        // Validate the request data if necessary
        $request->validate([
            'noframe' => 'required',
            // Add validation rules for other fields if needed
        ]);

        // Find the Commoninformation record
        $commonInformation = Commoninformation::where('NoFrame', $request->noframe)->first();

        // Update the status field to 1
        $commonInformation->update(['Status' => 1]);

        foreach ($request->findingQC as $index => $findingQC) {
            // Check if the data already exists in checksheets
            $existingChecksheet = Checksheet::where([
                'CommonInfoID' => $commonInformation->CommonInfoID,
                'ItemCheck' => $index,
            ])->first();

            // Initialize variables for findingQG and repairQG
            $findingQGvalue = $findingQC;
            $repairQGvalue = isset($request->repairQC[$index]) && $request->repairQC[$index] == 1 ? 1 : 0;

            // If the data exists, update it; otherwise, store it
            if ($existingChecksheet) {
                if ($existingChecksheet->FindingQG != $findingQGvalue || $existingChecksheet->RepairQG != $repairQGvalue) {

                    $updateChecksheet = Checksheet::where('id',$existingChecksheet->id)->update([
                        'Remarks' => $request->remarks,
                        'FindingQG' => $findingQGvalue,
                        'RepairQG' => $repairQGvalue,
                    ]);
                }
            } else {
                // If the data doesn't exist and the request value is 1, store it
                if ($findingQGvalue == 1) {
                    $checksheet = new Checksheet([
                        'CommonInfoID' => $commonInformation->CommonInfoID,
                        'ItemCheck' => $index,
                        'checkGroup' => $request->checkGroup,
                        'FindingQG' => $findingQGvalue,
                        'RepairQG' => $repairQGvalue,
                        'Remarks' => $request->remarks,
                        // Add other fields if needed
                    ]);

                    $checksheet->save();
                }
            }
        }

        // Redirect back to the show route
        return redirect()->route('show', ['noframe' => $request->noframe])->with('success', 'Data has been submitted successfully!');
    }
    public function submitPDI(Request $request)
    {
        // Validate the request data if necessary
        $request->validate([
            'noframe' => 'required',
            // Add validation rules for other fields if needed
        ]);

        // Find the Commoninformation record
        $commonInformation = Commoninformation::where('NoFrame', $request->noframe)->first();

        // Update the status field to 1
        $commonInformation->update(['Status' => 1]);
        $commonInformation->update(['PDI' => Auth::user()->name,]);
        foreach ($request->findingPDI as $index => $findingPDI) {
            // Check if the data already exists in checksheets
            $existingChecksheet = Checksheet::where([
                'CommonInfoID' => $commonInformation->CommonInfoID,
                'ItemCheck' => $index,
            ])->first();

            // Initialize variables for findingPDI and repairPDI
            $findingPDIValue = $findingPDI;
            $repairPDIValue = isset($request->repairPDI[$index]) && $request->repairPDI[$index] == 1 ? 1 : 0;

            // If the data exists, update it; otherwise, store it
            if ($existingChecksheet) {
                if ($existingChecksheet->FindingPDI != $findingPDIValue || $existingChecksheet->RepairPDI != $repairPDIValue) {
                    $updateChecksheet = Checksheet::where('id', $existingChecksheet->id)->update([
                        'Remarks' => $request->remarks,
                        'FindingPDI' => $findingPDIValue,
                        'RepairPDI' => $repairPDIValue,
                    ]);
                }
            } else {
                // If the data doesn't exist and the request value is 1, store it
                if ($findingPDIValue == 1) {
                    $checksheet = new Checksheet([
                        'CommonInfoID' => $commonInformation->CommonInfoID,
                        'ItemCheck' => $index,
                        'checkGroup' => $request->checkGroup,
                        'FindingPDI' => $findingPDIValue,
                        'RepairPDI' => $repairPDIValue,
                        'Remarks' => $request->remarks,
                        // Add other fields if needed
                    ]);

                    $checksheet->save();
                }
            }
        }

        // Redirect back to the show route
        return redirect()->route('show', ['noframe' => $request->noframe])->with('success', 'Data has been submitted successfully!');
    }
    public function submitMain(Request $request)
    {
        // Retrieve data from the form submission
        $noFrame = $request->input('noFrame');
        $tglProd = $request->input('tglProd');
        $shift = $request->input('shift');
        $name = $request->input('name');
        $remarks = $request->input('remarks');

        // Check if reject is 'on' in the request
        $rejectStatus = $request->input('reject') == 'on' ? 3 : null;

        // Find the Commoninformation based on noFrame
        $commoninformation = Commoninformation::where('NoFrame', $noFrame)->first();

        if (!$commoninformation) {
            // Handle the case where Commoninformation is not found
            return redirect()->route('checksheet')->with('error', 'Commoninformation not found');
        }

        // Update Commoninformation based on user role and reject status
        if (Auth::user()->role == 'QG') {
            $updateData = [
                'Status' => $rejectStatus ?? 0,
                'TglProd' => $tglProd,
                'Shift' => $shift,
                'NamaQG' => $name,
                'Remarks' => $remarks,
                'InspectionLevel' => 2,
                'QualityStatus' => $rejectStatus ? 'Reject' : ($commoninformation->checksheet->isEmpty() ? 'Good' : 'Finding'),
            ];

            if ($rejectStatus) {
                $updateData['PDI'] = '-';
            }

            $commoninformation->update($updateData);
        } elseif (Auth::user()->role == 'PDI') {
            $commoninformation->update([
                'Status' => $rejectStatus ?? 2,
                'PDI' => $name,
                'PDI_Date' => $tglProd,
                'Remarks' => $remarks,
                'QualityStatus' => $rejectStatus ? 'Reject' : ($commoninformation->checksheet->isEmpty() ? 'Good' : 'Finding'),
            ]);
        }

        // Redirect to the '/home' route
        return redirect()->route('checksheet')->with('status', "Data SL-Frame No. {$noFrame} has been submitted successfully!");
    }


    public function delete($id){
        $idFrame = Commoninformation::where('NoFrame',$id)->first()->CommonInfoID;
        $deleteslFrame=Commoninformation::where('CommonInfoID',$idFrame)->delete();
        $deleteChecksheet = Checksheet::where('CommonInfoID',$idFrame)->delete();
        if ($deleteslFrame || $deleteChecksheet) {
                return redirect()->route('record')->with('status',"Success Delete {$id}");
            }else{
                return redirect()->route('record')->with('failed','Failed Delete ');
            }

    }


    public function deletePending($id){
        $idFrame = Commoninformation::where('NoFrame',$id)->first()->CommonInfoID;
        $deleteslFrame=Commoninformation::where('CommonInfoID',$idFrame)->delete();
        $deleteChecksheet = Checksheet::where('CommonInfoID',$idFrame)->delete();
        if ($deleteslFrame || $deleteChecksheet) {
                return redirect()->route('checksheet')->with('status',"Success Delete {$id}");
            }else{
                return redirect()->route('checksheet')->with('failed','Failed Delete ');
            }

    }

    public function slFrameRecords(Request $request)
{
    $searchBy = $request->input('searchBy');
    $Commoninformation = [];

    $getCommoninformation = Commoninformation::where('Status', 2)
        ->orwhere('Status', 3)
        ->where('InspectionLevel', 2);

    if ($searchBy === 'production_date_range') {
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');
        $getCommoninformation->whereBetween('TglProd', [$dateFrom, $dateTo]);
    } elseif ($searchBy === 'created_at_date_range') {
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');
        $getCommoninformation->whereBetween('created_at', [$dateFrom, $dateTo]);
    } elseif ($searchBy === 'no_frame') {
        $frameNo = $request->input('frameNo');
        $getCommoninformation->where('NoFrame', $frameNo);
    }

    $Commoninformation = $getCommoninformation->orderByDesc('created_at')->get(); // Sort by newest created_at date

    return view('slframe.main', compact('Commoninformation'));
}


    public function chartSlFrame()
    {
        // Your existing query to get data from the database for findingQG
        $findingQGData = DB::table('commoninformations')
            ->leftJoin('checksheets', function ($join) {
                $join->on('commoninformations.CommonInfoID', '=', 'checksheets.CommonInfoID')
                    ->where('commoninformations.Status', '=', 2)
                    ->where('commoninformations.InspectionLevel', '=', 2)
                    ->where('checksheets.FindingQG', '=', 1); // Filter by findingQG
            })
            ->select(
                DB::raw('DATE(checksheets.created_at) as date'),
                DB::raw('COUNT(DISTINCT CASE WHEN checksheets.FindingQG = 1 THEN checksheets.CommonInfoID END) as findingQGCount')
            )
            ->where('checksheets.created_at', '>=', now()->firstOfMonth())
            ->groupBy('date')
            ->get();

        // Your existing query to get data from the database for findingPDI
        $findingPDIData = DB::table('commoninformations')
            ->leftJoin('checksheets', function ($join) {
                $join->on('commoninformations.CommonInfoID', '=', 'checksheets.CommonInfoID')
                    ->where('commoninformations.Status', '=', 2)
                    ->where('commoninformations.InspectionLevel', '=', 2)
                    ->where('checksheets.FindingPDI', '=', 1); // Filter by findingPDI
            })
            ->select(
                DB::raw('DATE(checksheets.created_at) as date'),
                DB::raw('COUNT(DISTINCT CASE WHEN checksheets.FindingPDI = 1 THEN checksheets.CommonInfoID END) as findingPDICount')
            )
            ->where('checksheets.created_at', '>=', now()->firstOfMonth())
            ->groupBy('date')
            ->get();

        // Your existing query to get data from the database for pending
        $pendingData = DB::table('commoninformations')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(IF(Status != 2 AND Status != 3 AND InspectionLevel != 2, 1, 0)) as pendingCount')
        )
        ->where('created_at', '>=', now()->firstOfMonth())
        ->groupBy('date')
        ->get();

        $totalRecordsChecked = DB::table('commoninformations')
        ->where('Status', '=', 2)
        ->orwhere('Status', '=', 3)
        ->where('InspectionLevel', '=', 2)
        ->whereMonth('created_at', now()->month) // Filter by current month
        ->count();

        // Initialize arrays for counts, starting from index 1
        $findingQGCount = array_fill(1, Carbon::now()->daysInMonth, 0);
        $findingPDICount = array_fill(1, Carbon::now()->daysInMonth, 0);
        $pendingCount = array_fill(1, Carbon::now()->daysInMonth, 0);

        // Loop through the findingQG data and increment counts for each day of the month
        foreach ($findingQGData as $row) {
            $day = Carbon::parse($row->date)->format('j');
            $findingQGCount[$day] = $row->findingQGCount;
        }

        // Loop through the findingPDI data and increment counts for each day of the month
        foreach ($findingPDIData as $row) {
            $day = Carbon::parse($row->date)->format('j');
            $findingPDICount[$day] = $row->findingPDICount;
        }

        // Loop through the pending data and increment counts for each day of the month
        foreach ($pendingData as $row) {
            $day = Carbon::parse($row->date)->format('j');
            $pendingCount[$day] = $row->pendingCount;
        }

        // Prepend a dummy value at the beginning of the arrays
        array_unshift($findingQGCount, 0);
        array_unshift($findingPDICount, 0);
        array_unshift($pendingCount, 0);

        // Remove the dummy value from the end of the arrays
        array_pop($findingQGCount);
        array_pop($findingPDICount);
        array_pop($pendingCount);

        // Calculate the sums
        $sumFindingQG = array_sum($findingQGCount);
        $sumFindingPDI = array_sum($findingPDICount);
        $sumPending = array_sum($pendingCount);

        // Create an associative array to store the sums
        $sums = [
            'sumFindingQG' => $sumFindingQG,
            'sumFindingPDI' => $sumFindingPDI,
            'sumPending' => $sumPending,
        ];

     // Get the current month's start and end date
    $startDate = Carbon::now()->startOfMonth();
    $endDate = Carbon::now()->endOfMonth();

    // Get all itemcheckgroups
    $itemCheckGroups = DB::table('itemcheckgroups')->orderBy('GroupID')->get();

    // Create a new array to store the final data
    $data = [];

    // Iterate over each itemcheckgroup
    foreach ($itemCheckGroups as $itemCheckGroup) {
        // Get the count of checksheets for this itemcheckgroup within the current month
        $countChecksheet = DB::table('checksheets')
            ->where('ItemCheck', $itemCheckGroup->ItemCheck)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Add the itemcheckgroup data and the count of checksheets to the final data array
        $data[] = [
            'id'    => $itemCheckGroup->GroupID,
            'ItemCheck' => $itemCheckGroup->ItemCheck,
            'CountChecksheet' => $countChecksheet,
        ];
    }
    $data = collect($data);
        // Pass the data to the view
        return view('slframe.chart', compact('sums', 'findingQGCount', 'findingPDICount', 'pendingCount','data','totalRecordsChecked'));
    }
    public function detailSLFrame($id){
        $noframe = $id;
        $Commoninformation = Commoninformation::where('NoFrame', $noframe)->first();
        // Define an array of CheckGroup values
        $checkGroups = [1, 2, 3, 4, 5, 6];
        // Fetch all Itemcheckgroups for the specified CheckGroup values
        $itemCheckGroups = Itemcheckgroup::whereIn('CheckGroup', $checkGroups)->get()->groupBy('CheckGroup');
        $checkSheet = Checksheet::where('CommonInfoID',$Commoninformation->CommonInfoID)->get();
        return view('slframe.detail', compact('Commoninformation', 'itemCheckGroups','noframe','checkSheet'));
    }

    public function export(Request $request) {
        // dd($request->all());
         // Get the start and end dates from the request
         $startDate = $request->input('startDate');
         $endDate = $request->input('endDate');
         $searchBy = $request->input('searchBy'); // Add this line to fetch the searchBy parameter
        // Get the current date
        $currentDate = Carbon::now()->toDateString();

        // Combine the remarks, current date, and file extension
        // Change this to your desired remarks
        $fileName = "sl_frame_export_{$currentDate}.xlsx";

        if ($request->inspectionLevel == 'qg') {
            return Excel::download(new SLFrameExportQG($startDate, $endDate, $searchBy), $fileName);
        }elseif($request->inspectionLevel == 'pdi'){
            return Excel::download(new SLFrameExportPDI($startDate, $endDate, $searchBy), $fileName);
        }




        // Pass the start and end dates, and searchBy to the export class
        return Excel::download(new SLFrameExport($startDate, $endDate, $searchBy), $fileName);
    }


    public function detailPDI($role, $day)
    {

        $year = now()->year;
        $month = now()->month;

        // Extract the start and end date from the provided $date range
        // $dates = explode('-', $date);
        // $startDate = Carbon::create(now()->year, now()->month, $dates[0], 0, 0, 0);
        // $endDate = Carbon::create(now()->year, now()->month, $dates[1], 23, 59, 59);

        $startDate = Carbon::create($year, $month, $day, 0, 0, 0);
        $endDate = Carbon::create($year, $month, $day, 23, 59, 59);
        if ($role == 'pdi') {
            // Fetch Commoninformation records where FindingPDI or RepairPDI is 1 and there is a related record in checksheet
            $Commoninformation = Commoninformation::where('Status', 2)
                ->where('InspectionLevel', 2)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('checksheet', function ($query) {
                    $query->where('FindingPDI', 1)->orWhere('RepairPDI', 1);
                })
                ->get();

            // Join to checksheet table where FindingPDI or RepairPDI is 1
            $Commoninformation->load(['checksheet' => function ($query) {
                $query->where('FindingPDI', 1)->orWhere('RepairPDI', 1);
            }]);
        } else if ($role == 'qg') {
            // Fetch Commoninformation records where FindingQG or RepairQG is 1 and there is a related record in checksheet
            $Commoninformation = Commoninformation::where('Status', 2)
                ->where('InspectionLevel', 2)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('checksheet', function ($query) {
                    $query->where('FindingQG', 1)->orWhere('RepairQG', 1);
                })
                ->get();
            // Join to checksheet table where FindingQG or RepairQG is 1
            $Commoninformation->load(['checksheet' => function ($query) {
                $query->where('FindingQG', 1)->orWhere('RepairQG', 1);
            }]);
        } else {
            // Fetch Commoninformation records where Status is not 2 and InspectionLevel is not 2 and there is a related record in checksheet
            $Commoninformation = Commoninformation::where('Status', '!=', 2)
                ->where('InspectionLevel', '!=', 2)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('checksheet')
                ->get();
        }
        return view('slframe.main', compact('Commoninformation'));
    }

    public function test(){
        // Your existing query to get data from the database for findingQG
        $findingQGData = DB::table('commoninformations')
            ->leftJoin('checksheets', function ($join) {
                $join->on('commoninformations.CommonInfoID', '=', 'checksheets.CommonInfoID')
                    ->where('commoninformations.Status', '=', 2)
                    ->where('commoninformations.InspectionLevel', '=', 2)
                    ->where('checksheets.FindingQG', '=', 1); // Filter by findingQG
            })
            ->select(
                DB::raw('DATE(checksheets.created_at) as date'),
                DB::raw('COUNT(DISTINCT CASE WHEN checksheets.FindingQG = 1 THEN checksheets.CommonInfoID END) as findingQGCount')
            )
            ->where('checksheets.created_at', '>=', now()->firstOfMonth())
            ->groupBy('date')
            ->get();
        // Your existing query to get data from the database for findingPDI
        $findingPDIData = DB::table('commoninformations')
            ->leftJoin('checksheets', function ($join) {
                $join->on('commoninformations.CommonInfoID', '=', 'checksheets.CommonInfoID')
                    ->where('commoninformations.Status', '=', 2)
                    ->where('commoninformations.InspectionLevel', '=', 2)
                    ->where('checksheets.FindingPDI', '=', 1); // Filter by findingPDI
            })
            ->select(
                DB::raw('DATE(checksheets.created_at) as date'),
                DB::raw('COUNT(DISTINCT CASE WHEN checksheets.FindingPDI = 1 THEN checksheets.CommonInfoID END) as findingPDICount')
            )
            ->where('checksheets.created_at', '>=', now()->firstOfMonth())
            ->groupBy('date')
            ->get();

        // Your existing query to get data from the database for pending
        $pendingData = DB::table('commoninformations')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(IF(Status != 2 OR InspectionLevel != 2, 1, 0)) as pendingCount')
            )
            ->where('created_at', '>=', now()->firstOfMonth())
            ->groupBy('date')
            ->get();

        // Initialize associative arrays for counts, starting from index 1
// Initialize associative arrays for counts, starting from index 1
$findingQGCount = [];
$findingPDICount = [];
$pendingCount = [];

// Loop through the findingQG data and increment counts for each day of the month
foreach ($findingQGData as $row) {
    $dateFormatted = Carbon::parse($row->date)->format('d');
    $findingQGCount[$dateFormatted] = $row->findingQGCount;
}

// Loop through the findingPDI data and increment counts for each day of the month
foreach ($findingPDIData as $row) {
    $dateFormatted = Carbon::parse($row->date)->format('d');
    $findingPDICount[$dateFormatted] = $row->findingPDICount;
}

// Loop through the pending data and increment counts for each day of the month
foreach ($pendingData as $row) {
    $dateFormatted = Carbon::parse($row->date)->format('d');
    $pendingCount[$dateFormatted] = $row->pendingCount;
}

// Fill in any missing days with zero counts
for ($i = 1; $i <= 31; $i++) {
    if (!isset($findingQGCount[$i])) {
        $findingQGCount[$i] = 0;
    }
    if (!isset($findingPDICount[$i])) {
        $findingPDICount[$i] = 0;
    }
    if (!isset($pendingCount[$i])) {
        $pendingCount[$i] = 0;
    }
}

// Sort arrays by keys to ensure they are ordered correctly
ksort($findingQGCount);
ksort($findingPDICount);
ksort($pendingCount);

// Convert associative arrays to numerically indexed arrays
$findingQGCount = array_values($findingQGCount);
$findingPDICount = array_values($findingPDICount);
$pendingCount = array_values($pendingCount);

// Add a dummy value at the beginning of the arrays
array_unshift($findingQGCount, 0);
array_unshift($findingPDICount, 0);
array_unshift($pendingCount, 0);


        // Calculate the sums
        $sumFindingQG = array_sum($findingQGCount);
        $sumFindingPDI = array_sum($findingPDICount);
        $sumPending = array_sum($pendingCount);

        // Create an associative array to store the sums
        $sums = [
            'sumFindingQG' => $sumFindingQG,
            'sumFindingPDI' => $sumFindingPDI,
            'sumPending' => $sumPending,
        ];

     // Get the current month's start and end date
    $startDate = Carbon::now()->startOfMonth();
    $endDate = Carbon::now()->endOfMonth();

    // Get all itemcheckgroups
    $itemCheckGroups = DB::table('itemcheckgroups')->orderBy('GroupID')->get();

    // Create a new array to store the final data
    $data = [];

    // Iterate over each itemcheckgroup
    foreach ($itemCheckGroups as $itemCheckGroup) {
        // Get the count of checksheets for this itemcheckgroup within the current month
        $countChecksheet = DB::table('checksheets')
            ->where('ItemCheck', $itemCheckGroup->ItemCheck)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Add the itemcheckgroup data and the count of checksheets to the final data array
        $data[] = [
            'id'    => $itemCheckGroup->GroupID,
            'ItemCheck' => $itemCheckGroup->ItemCheck,
            'CountChecksheet' => $countChecksheet,
        ];
    }
    $data = collect($data);
        // Pass the data to the view
        return view('slframe.test', compact('sums', 'findingQGCount', 'findingPDICount', 'pendingCount','data'));
    }

    public function detailRole($role){

        $year = now()->year;
        $month = now()->month;

        // Extract the start and end date from the provided $date range
        // $dates = explode('-', $date);
        // $startDate = Carbon::create(now()->year, now()->month, $dates[0], 0, 0, 0);
        // $endDate = Carbon::create(now()->year, now()->month, $dates[1], 23, 59, 59);

       // Get the first day of the current month
        $startDate = Carbon::create($year, $month, 1, 0, 0, 0);

        // Get the last day of the current month
        $endDate = Carbon::create($year, $month, $startDate->daysInMonth, 23, 59, 59);

        if ($role == 'pdi') {
            // Fetch Commoninformation records where FindingPDI or RepairPDI is 1 and there is a related record in checksheet
            $Commoninformation = Commoninformation::where('Status', 2)
                ->where('InspectionLevel', 2)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('checksheet', function ($query) {
                    $query->where('FindingPDI', 1)->orWhere('RepairPDI', 1);
                })
                ->get();

            // Join to checksheet table where FindingPDI or RepairPDI is 1
            $Commoninformation->load(['checksheet' => function ($query) {
                $query->where('FindingPDI', 1)->orWhere('RepairPDI', 1);
            }]);
        } else if ($role == 'qg') {
            // Fetch Commoninformation records where FindingQG or RepairQG is 1 and there is a related record in checksheet
            $Commoninformation = Commoninformation::where('Status', 2)
                ->where('InspectionLevel', 2)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('checksheet', function ($query) {
                    $query->where('FindingQG', 1)->orWhere('RepairQG', 1);
                })
                ->get();

            // Join to checksheet table where FindingQG or RepairQG is 1
            $Commoninformation->load(['checksheet' => function ($query) {
                $query->where('FindingQG', 1)->orWhere('RepairQG', 1);
            }]);
        } else {

            $itemCheck = Itemcheckgroup::where('GroupID', $role)->value('ItemCheck');

            $commonInfoIDs = Checksheet::where('ItemCheck', $itemCheck)->pluck('CommonInfoID');

            $Commoninformation = collect(); // Initialize an empty collection to store the results

            foreach ($commonInfoIDs as $commonInfoID) {
                // Query the commoninformations table for each CommonInfoID
                $commonInfo = Commoninformation::where('Status', 2)
                    ->where('InspectionLevel', 2)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('CommonInfoID', $commonInfoID)
                    ->get();

                // Merge the results into the collection
                $Commoninformation = $Commoninformation->merge($commonInfo);
            }

            // Now $Commoninformation contains all the results in a single collection

        }
        return view('slframe.main', compact('Commoninformation'));


    }

}

