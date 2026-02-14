<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Patient;
use Carbon\Carbon;
use PDF;
use DB;

class PatientsController extends Controller
{

        
    public function exportPatientListPDF(Request $request)
    {
        $selectedDate = $request->query('date', Carbon::today()->toDateString());
    
        $patients = Patient::query()
            ->select([
                'patients.fullname',
                'patients.phone',
                'patients.address',
                'patients.created_at', // Include created_at date
                'doctors.name as referred_by_name' // Fetching the referred doctor's name
            ])
            ->leftJoin('doctors', 'patients.referred_by', '=', 'doctors.id') // Assuming `referred_by` references `doctors.id`
            ->whereDate('patients.created_at', $selectedDate)
            ->get();
    
        $patients->transform(function ($patient) {
            $patient->formatted_created_at = Carbon::parse($patient->created_at)->format('d-m-Y H:i:s'); // Format the created_at date
            return $patient;
        });
    
        $pdf = PDF::loadView('admin.patients.refered_by', [
            'patients' => $patients,
            'selectedDate' => Carbon::parse($selectedDate)->format('d-m-Y'),
        ]);
    
        $filename = 'patients_list_' . $selectedDate . '.pdf';
    
        return $pdf->download($filename);
    }
    
    


    public function index()
    {
        try {
			return view( 'admin.patients.list');
		} catch ( \Exception $ex ) {
			Log::critical( "patients list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function getData(Request $request)
    {
        $query = Patient::withTrashed();
        
        return DataTables::of($query)
            ->addColumn('deleted_at', function ($query) {
                return $query->deleted_at ? $query->deleted_at->format('Y-m-d H:i:s') : 'Active';
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at ? $query->created_at->format('d-m-Y') : 'N/A';
            })
            ->addColumn('action', function ($query) {
                if ($query->trashed()) {
                    return '<span class="text-danger">Deleted</span>';
                } else {
                    return '<button class="btn btn-sm btn-danger btn-trash" data-id="' . $query->id . '"><i class="fa fa-trash"></i></button>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
   
    public function softDelete($id)
    {
        $patient = Patient::findOrFail($id);

        // echo $id;exit;

        if ($patient) {
            $patient->delete(); // Perform soft delete
            return response()->json(['success' => 'Record successfully trashed.']);
        }

        return response()->json(['error' => 'Record not found.'], 404);
    }

   

    

}
