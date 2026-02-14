<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OldPatient;
use Yajra\DataTables\Facades\DataTables;
use Log;

class ImportController extends Controller
{
    public function index()
    {
        try {
            return view('admin.oldpatient.list');
        } catch (\Exception $ex) {
            Log::critical("medicine list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString());
            return redirect('admin')
                ->with('error', 'Woops! Something went wrong. Please try again later.');
        }
    }

    public function getdata(Request $request)
    {
        $query = OldPatient::query()->orderBy('created_at', 'desc');
    
        return DataTables::of($query)
            ->addColumn('action', function($row) {
                return '<a data-href="' . route('admin.patients.delete', $row->id) . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg"  ><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['action']) 
            ->make(true);
    }

    public function processImport(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt',
            ]);

            $file = $request->file('file');
            $handle = fopen($file, 'r');

            if ($handle === false) {
                return redirect()->back()->with('error', 'Failed to open the file. Please try again.');
            }
            fgetcsv($handle); 

            while (($row = fgetcsv($handle)) !== false) {
                $name = trim($row[0] ?? '');
                $phone = trim($row[1] ?? '');

                if (!empty($name) && !empty($phone)) {
                    $existingPatient = OldPatient::where('phone', $phone)->first();

                    if (!$existingPatient) {
                        OldPatient::create([
                            'name' => $name,
                            'phone' => $phone,
                        ]);
                    }
                }
            }

            fclose($handle);

            return redirect()->back()->with('success', 'Patients imported successfully!');
        } catch (\Exception $ex) {
            Log::critical("Import Error:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to import data. Please try again later.');
        }
    }

    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample.csv"',
        ];

        $content = "name,phone";

        return response($content, 200, $headers);
    }
    public function delete(string $id)
    {
        try {
            $patient = OldPatient::findOrFail($id);
            
            if ( isset($patient) ) {
                $patient->delete();

                return redirect( 'admin/old_patient' )
                    ->with( 'success', 'Patient deleted successfully' );
            } else {
                return redirect( 'admin/old_patient' )
                    ->with( 'error', 'Invalid Patient' );
            }
        } catch ( \Exception $ex ) {
            Log::critical( "Patient Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

            return redirect()->back()
                            ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
        }
    }

    
    
}
