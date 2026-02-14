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

class PatientController extends Controller
{
    public function index()
    {
        try {
			return view( 'nursing.patient.list');
		} catch ( \Exception $ex ) {
			Log::critical( "patient list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'nursing' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function create()
    {
        try {
            $rooms = Room::all();
            return view('nursing.patient.form',compact('rooms'));
		} catch ( \Exception $ex ) {
			Log::critical( "patient form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'nursing' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function getData(Request $request)
    {
        $query = Patient::with(['room', 'bed']) 
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
            return optional($query->bed)->bed_no ?: 'No Bed Assigned';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function searchPatient(Request $request)
    {
        $patient = Patient::withTrashed()->where('patient_id', $request->patient_id)->first();

        if ($patient) {
            return response()->json([
                'success' => true,
                'data' => $patient
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ]);
        }
    }

  
    public function getBeds(Request $request)
    {
        $room_id = $request->input('room_id');
        $beds = Bed::where('room_id', $room_id)->get(); 
        
        return response()->json($beds);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'     => 'required|exists:patients,patient_id',
            'room_type'      => 'required',
            'bed'            => 'required',
            'admit_datetime' => 'required',
            'type'         => 'required',
        ]);
    
    
        try {
            $patient = Patient::where('patient_id', $request->patient_id)
                ->latest('created_at')
                ->first();
    
            if ($patient) {
                if ($patient->bed) {
                    $previousBed = Bed::find($patient->bed);
                    if ($previousBed) {
                        $previousBed->is_occupied = 0;
                        $previousBed->save();
                    }
                }
    
                $patient->update([
                    'room_type'      => $request->room_type,
                    'type' => $request->type,
                    'bed'            => $request->bed,
                    'admit_datetime' => $request->admit_datetime,
                    'admit_status'   => 'Admitted',
                    'mlc'            => $request->mlc, // Will be "1" if checked, "0" if not
                ]);
    
                $roombed = RoomBed::where('patient_id', $patient->id)->first();
    
                if ($roombed) {
                    $roombed->update([
                        'room_id'     => $request->room_type,
                        'bed_id'      => $request->bed,
                        'admit_status'=> 'Admitted',
                    ]);
                } else {
                    RoomBed::create([
                        'room_id'     => $request->room_type,
                        'bed_id'      => $request->bed,
                        'patient_id'  => $patient->id,
                        'admit_status'=> 'Admitted',
                    ]);
                }
    
                $newBed = Bed::find($request->bed);
                if ($newBed) {
                    $newBed->update(['is_occupied' => 1]);
                } else {
                    if (isset($previousBed)) {
                        $previousBed->update(['is_occupied' => 0]);
                    }
                }
    
                return redirect('nursing/patients')->with('success', 'Patient details saved successfully!');
            } else {
                return redirect('nursing/patients')->with('error', 'Patient not found!');
            }
        } catch (\Exception $ex) {
            \Log::error('Error saving patient details: ' . $ex->getMessage());
            return redirect('nursing/patients')->with('error', 'An error occurred while saving the patient details.');
        }
    }
    

    public function destroy($id)
    {
        try {
            $patient = Patient::findOrFail($id);
            // echo json_encode($patient);exit;
    
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
                'type'         => null,

            ]);
    
    
            return redirect('nursing/patients')->with('success', 'Patient deleted successfully!');
        } catch (\Exception $ex) {
            Log::error('Error deleting patient: ' . $ex->getMessage());
            return redirect('nursing/patients')->with('error', 'An error occurred while deleting the patient. ' . $ex->getMessage());
        }
    }
    
    
}
