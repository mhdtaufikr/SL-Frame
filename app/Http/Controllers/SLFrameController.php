<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commoninformation;
use App\Models\Itemcheckgroup;
use App\Models\Checksheet;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SLFrameController extends Controller
{
    public function index(Request $request)
    {
        // Check if record with the given NoFrame already exists
        $Commoninformation = Commoninformation::where('NoFrame', $request->no_frame)->first();
    
        if ($Commoninformation) {
            if(Auth::user()->role == 'PDI'){
                if ($Commoninformation->Status == '2') {
                    return redirect()->route('home')->with('failed', 'SL-Frame already checked.');
                }
                return redirect()->route('show', ['noframe' => $request->no_frame]);
            }
            if ($Commoninformation->InspectionLevel == 2) {   
                // If InspectionLevel is already 2, store the error message in the session
                return redirect()->route('home')->with('failed', 'SL-Frame already checked.');
            }
            // If the record exists, redirect to the view with existing data
            return redirect()->route('show', ['noframe' => $request->no_frame]);
        } else {
            // If the record doesn't exist, create a new record and redirect
            $Commoninformation = Commoninformation::create([
                'NoFrame' => $request->no_frame,
                'Status' => 0,
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
            return redirect()->route('home')->with('failed', 'SL-Frame on QG Check.');
           }
        }
        if (Auth::user()->role == 'QG') {
            if ($Commoninformation->InspectionLevel == 2) {
                return redirect()->route('home')->with('failed', 'SL-Frame Already Check.');
               }
        }
        // Define an array of CheckGroup values
        $checkGroups = [1, 2, 3, 4, 5, 6];
        // Fetch all Itemcheckgroups for the specified CheckGroup values
        $itemCheckGroups = Itemcheckgroup::whereIn('CheckGroup', $checkGroups)->get()->groupBy('CheckGroup');
        $checkSheet = Checksheet::where('CommonInfoID',$Commoninformation->CommonInfoID)->get();
        return view('slframe.index', compact('Commoninformation', 'itemCheckGroups','noframe','checkSheet'));
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

    // Find the Commoninformation based on noFrame
    $commoninformation = Commoninformation::where('NoFrame', $noFrame)->first();

    if (!$commoninformation) {
        // Handle the case where Commoninformation is not found
        return redirect()->route('home')->with('error', 'Commoninformation not found');
    }

    // Update Commoninformation based on user role
    if (Auth::user()->role == 'QG') {
        $commoninformation->update([
            'Status' => 0,
            'TglProd' => $tglProd,
            'Shift' => $shift,
            'NamaQG' => $name,
            'Remarks' => $remarks,
            'InspectionLevel' => 2,
            'QualityStatus' => $commoninformation->checksheet->isEmpty() ? 'Good' : 'Bad',
        ]);
    } elseif (Auth::user()->role == 'PDI') {
        $commoninformation->update([
            'Status' => 2,
            'PDI' => $name,
            'PDI_Date' => $tglProd,
            'Remarks' => $remarks,
            'QualityStatus' => $commoninformation->checksheet->isEmpty() ? 'Good' : 'Bad',
        ]);
    }

    // Redirect to the '/home' route
    return redirect()->route('home')->with('status', "Data SL-Frame No. {$noFrame} has been submitted successfully!");

}

    public function delete($id){
        $idFrame = Commoninformation::where('NoFrame',$id)->first()->CommonInfoID;
        $deleteslFrame=Commoninformation::where('CommonInfoID',$idFrame)->delete();
        $deleteChecksheet = Checksheet::where('CommonInfoID',$idFrame)->delete();
        if ($deleteslFrame && $deleteChecksheet) {
                return redirect('/home')->with('status',"Success Delete {$id}");
            }else{
                return redirect('/rule')->with('status','Failed Delete ');
            }
        
    }

    public function slFrameRecords(){
        $Commoninformation = Commoninformation::where('Status', 2)
        ->where('InspectionLevel',2)
        ->get();
        return view('slframe.main', compact('Commoninformation'));
    }

 

    

    public function chartSlFrame()
    {
        // Fetch data from the database
        $data = DB::table('commoninformations')
        ->leftJoin('checksheets', 'commoninformations.CommonInfoID', '=', 'checksheets.CommonInfoID')
        ->select(
            DB::raw('DATE(checksheets.created_at) as date'),
            DB::raw('SUM(IF(checksheets.FindingQG = 1, 1, 0)) as findingQGCount'),
            DB::raw('SUM(IF(checksheets.FindingPDI = 1, 1, 0)) as findingPDICount'),
            DB::raw('SUM(IF(commoninformations.Status != 2 OR commoninformations.InspectionLevel != 2, 1, 0)) as pendingCount')
        )
        ->where('checksheets.created_at', '>=', now()->firstOfMonth())
        ->groupBy('date')
        ->get();
    
        // Prepare the data for the chart
        $dates = $findingQGCount = $findingPDICount = $pendingCount = [];

        foreach ($data as $row) {
            $dates[] = $row->date;
            $findingQGCount[] = $row->findingQGCount;
            $findingPDICount[] = $row->findingPDICount;
            $pendingCount[] = $row->pendingCount;
        }

        return view('slframe.chart', compact('dates', 'findingQGCount', 'findingPDICount', 'pendingCount'));
    }
    
    
    

    

}

