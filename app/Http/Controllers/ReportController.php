<?php

namespace App\Http\Controllers;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class ReportController extends Controller
{
   public function index(){
    $report = Report::orderBy('created_at', 'desc')->get();

    return view("report.index",compact("report"));
   }

   public function upload(Request $request)
{
    $fileRules = [
        'required',
        'file',
        'max:15360', // Max 15MB file size (15 * 1024 = 15360 KB)
        'mimes:pdf,csv,xls,xlsx,doc,docx', // Allowed document extensions
    ];

    $request->validate([
        'title' => 'required|string',
        'file' => $fileRules,
    ]);


    $file = $request->file('file');
    $fileName = time() . '_' . $file->getClientOriginalName();
    $filePath = $file->storeAs('reports', $fileName);

    $report = new Report();
    $report->title = $request->title;
    $report->file_path = $filePath;
    $report->save();

    return redirect()->route('reports.index')->with('success', 'Report uploaded successfully.');
}

public function download($id)
{
    $report = Report::findOrFail($id); // Assuming Report is your model

    $filePath = $report->file_path;
    $fileName = basename($filePath);

    return Storage::download($filePath, $fileName);
}

public function destroy($id)
{
    $report = Report::findOrFail($id);

    // Delete the file from storage if needed
    Storage::delete($report->file_path);

    $report->delete();

    return redirect()->route('reports.index')->with('success', 'Report deleted successfully.');
}


}
