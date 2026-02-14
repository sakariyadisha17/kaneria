<?php

namespace App\Http\Controllers\Medicalofficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Room;
use App\Models\Bed;
use App\Models\RoomBed;
use Log;

class PatientController extends Controller
{
    public function index()
    {
        try {
			return view( 'medical_officer.patient.list');
		} catch ( \Exception $ex ) {
			Log::critical( "patient list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'medical_officer' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function getData(Request $request)
    {
        $query = Patient::with(['room', 'bed']) // Ensure bed relationship is included
        ->where('admit_status', 'Admitted')
        ->orderBy('id', 'desc');

        return DataTables::of($query)
            ->addColumn('action', function ($query) {
                return '<form action="' . URL('nursing/patients/' . $query->id . '/delete') . '" method="POST" style="display:inline;">
                        ' . method_field('DELETE') . csrf_field() . '
                            <button type="submit" title="Delete" class="text-danger btn-lg" style="border: none; background: none;" data-toggle="modal" data-target="#confirm-delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>';
        })
        ->editColumn('room_type', function ($query) {
            return $query->room ? $query->room->type : 'No Room Assigned'; 
        })
        ->addColumn('bed_no', function ($query) {
            return optional($query->bed)->bed_no ?: 'No Bed Assigned'; // Fixing possible null reference
        })
        ->rawColumns(['action'])
        ->make(true);
    }
  

    public function destroy($id)
    {
        try {
            $patient = Patient::findOrFail($id);
    
            if (!$patient) {
                throw new \Exception("Patient not found");
            }
    
            $roombed = RoomBed::where('patient_id', $patient->id)->first();
            if ($roombed) {
                $roombed->delete();
            }
    
            $bed = Bed::find($patient->bed);
            if ($bed) {
                $bed->update(['is_occupied' => 0]);
            }
    
            $patient->update([
                'bed' => null,  
                'room_type' => null,  
                'admit_status' => null,  
                'admit_datetime' => null,  
            ]);
    
            $patient->delete();
    
            return redirect('medical_officer/patients')->with('success', 'Patient deleted successfully!');
        } catch (\Exception $ex) {
            Log::error('Error deleting patient: ' . $ex->getMessage());
            return redirect('medical_officer/patients')->with('error', 'An error occurred while deleting the patient. ' . $ex->getMessage());
        }
    }
    
    
}
