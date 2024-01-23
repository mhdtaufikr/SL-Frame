<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commoninformation;
use App\Models\Itemcheckgroup;

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
        return view('slframe.index', compact('Commoninformation', 'itemCheckGroups'));
    }
    
}
