<?php

namespace App\Http\Controllers;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class ReportController extends Controller
{
    public function index()
{
    $folders = Report::select('folder')->distinct()->get();
    $reports = Report::orderBy('created_at', 'desc')->get();

    return view("report.index", compact("reports", "folders"));
}




   public function folderDetail($folder)
{
    $files = Report::where('folder', $folder)->get();

    return view('report.folder-detail', compact('files'));
}


public function upload(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'folder' => 'required|string',
        'file' => 'required|file|mimes:pdf,csv,xls,xlsx,doc,docx|max:15360', // Adjust file validation rules as needed
    ]);

    // Retrieve the uploaded file
    $file = $request->file('file');

    // Generate a unique file name
    $fileName = time() . '_' . $file->getClientOriginalName();

    // Store the file in the specified folder within the storage/app/public directory
    $filePath = $file->storeAs('public/' . $request->folder, $fileName);

    // Save the file path and other details to the database
    $report = new Report();
    $report->title = $fileName; // You can adjust this as needed
    $report->folder = $request->folder;
    $report->file_path = $filePath;
    $report->save();

    return redirect()->route('reports.index')->with('status', 'File uploaded successfully.');
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

    return redirect()->route('reports.index')->with('status', 'Report deleted successfully.');
}

public function addFolder(Request $request)
{
    $fileRules = [
        'required',
        'file',
        'max:15360', // Max 15MB file size (15 * 1024 = 15360 KB)
        'mimes:pdf,csv,xls,xlsx,doc,docx', // Allowed document extensions
    ];

    $request->validate([
        'folder' => 'required|string', // Validate folder name
        'title' => 'required|string',
        'file' => $fileRules,
    ]);

    // Create or find the folder
    $folder = $request->folder;
    $folderPath = 'reports/' . $folder;
    if (!Storage::exists($folderPath)) {
        Storage::makeDirectory($folderPath);
    }

    $file = $request->file('file');
    $fileName = time() . '_' . $file->getClientOriginalName();
    $filePath = $file->storeAs($folderPath, $fileName);

    $report = new Report();
    $report->title = $request->title;
    $report->file_path = $filePath;
    $report->folder = $folder; // Store folder name in the database
    $report->save();

    return redirect()->route('reports.index')->with('status', 'Report uploaded successfully.');
    }

    public function destroyFolder($folder){
    // Use the where clause to filter records by the folder column
    $reportsToDelete = Report::where('folder', $folder)->get();

    // Delete each record in the collection
    foreach ($reportsToDelete as $report) {
        $report->delete();
    }
    return redirect()->route('reports.index')->with('status', 'Reports in folder ' . $folder . ' deleted successfully.');
    }



}
