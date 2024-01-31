<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commoninformation;
use App\Models\Itemcheckgroup;
use App\Models\Checksheet;

class SLFrameController extends Controller
{
    public function index(Request $request)
    {
        // Check if record with the given NoFrame already exists
        $Commoninformation = Commoninformation::where('NoFrame', $request->no_frame)->first();
    
        if ($Commoninformation) {
            // If the record exists, redirect to the view with existing data
            return redirect()->route('show', ['noframe' => $request->no_frame]);
        } else {
            // If the record doesn't exist, create a new record and redirect
            $Commoninformation = Commoninformation::create([
                'NoFrame' => $request->no_frame,
                'Status' => 0,
            ]);
            return redirect()->route('show', ['noframe' => $request->no_frame]);
        }
    }
    

    public function show($noframe)
    {
        $Commoninformation = Commoninformation::where('NoFrame', $noframe)->first();
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

        // Loop through the submitted data and store it in the checksheets table
        foreach ($request->findingQC as $index => $findingQC) {
            // Check if the data already exists in checksheets
            $existingChecksheet = Checksheet::where([
                'CommonInfoID' => $commonInformation->CommonInfoID,
                'ItemCheck' => $findingQC,
            ])->first();

            // If the data doesn't exist, store it
            if (!$existingChecksheet) {
                $checksheet = new Checksheet([
                    'CommonInfoID' => $commonInformation->CommonInfoID,
                    'ItemCheck' => $findingQC,
                    'checkGroup' => $request->checkGroup,
                    'FindingQC' => '1',
                    'RepairQC' => '1', // Assuming it's always 1 when storing FindingQC
                    'RemarksQG' => $request->remarks,
                    // Add other fields if needed
                ]);

                $checksheet->save();
            }
        }

        // Redirect back to the show route
        return redirect()->route('show', ['noframe' => $request->noframe])->with('success', 'Data has been submitted successfully!');
    }

    
    
    
}
