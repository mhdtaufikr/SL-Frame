<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pallet;
use App\Models\Commoninformation;

class HomeController extends Controller
{
    public function index()
    {
        $Commoninformation = Commoninformation::where('InspectionLevel', '2')
        ->where('Status', '<>', '2')
        ->get();
        $CommoninformationQG = Commoninformation::where('InspectionLevel', '1')->get();
        $CommoninformationPDI = Commoninformation::where('Status','<>', '2')->where('PDI', null)->get();

        return view('home.index',compact('Commoninformation','CommoninformationQG','CommoninformationPDI'));

    }
}
