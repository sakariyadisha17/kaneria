<?php

namespace App\Http\Controllers\Nursing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Room;
use App\Models\Bed;
use App\Models\RoomBed;
use Log;
use App\Models\IpdLetterhead;
use App\Models\IpdPatientMedicine;
use App\Models\PatientFile;
use App\Models\PatientService;
use App\Models\VitalChart;
use Auth;

class BillingController extends Controller
{
  
    public function index()
    {
        try {
			return view( 'nursing.patient_bill.list');
		} catch ( \Exception $ex ) {
			Log::critical( "patient list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'nursing' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function getData(Request $request)
    {
        $query = Patient::with(['room', 'bed']) 
            ->where('admit_status', 'Discharged')
            ->orderBy('id', 'desc');

            return DataTables::of($query)
            ->addColumn('action', function ($query) {
                if (Auth::user()->hasRole('Admin')) { 
                    $actionString = '<a data-href="' . URL('nursing/patient_bill/' . $query->id . '/delete') . '" 
                                        data-toggle="modal" 
                                        data-target="#confirm-delete" 
                                        title="Delete" 
                                        class="text-danger btn-lg">
                                        <i class="fa fa-trash"></i>
                                    </a>';
                } else {
                    $actionString = '';
                }
        
                return $actionString;
            })
   
        ->rawColumns(['action'])
        ->make(true);
    }
   
    public function destroy($id)
    {
        try {
            $patient = Patient::withTrashed()->findOrFail($id);
    
            if (!$patient) {
                throw new \Exception("Patient not found");
            }
    
            IpdPatientMedicine::where('patient_id', $patient->id)->delete();
            IpdLetterhead::where('patient_id', $patient->id)->delete(); 
            PatientFile::where('patient_id', $patient->id)->delete();
            PatientService::where('patient_id', $patient->id)->delete();
            VitalChart::where('patient_id', $patient->id)->delete();
            RoomBed::where('patient_id', $patient->id)->delete();
    
            Bed::where('id', $patient->bed)->update(['is_occupied' => 0]);
    
            $patient->forceDelete();
    
            return redirect('nursing/patient_bill')->with('success', 'Patient permanently deleted!');
        } catch (\Exception $ex) {
            Log::error('Error deleting patient: ' . $ex->getMessage());
            return redirect('nursing/patient_bill')->with('error', 'An error occurred while deleting the patient. ' . $ex->getMessage());
        }
    }
    
    
}
