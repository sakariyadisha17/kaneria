<?php

namespace App\Http\Controllers\Nursing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Room;
use App\Models\Bed;
use App\Models\RoomBed;
use App\Models\RoomShifting;

class ShiftingController extends Controller
{
    public function index()
    {
        try {
            $patients = Patient::where('admit_status', 'Admitted')->get();
            $rooms = Room::all();
			return view( 'nursing.room_shift.list',compact('rooms','patients'));
		} catch ( \Exception $ex ) {
			Log::critical( "room_shift list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'nursing' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function create()
    {
        try {
            return view('nursing.room_shift.form');
		} catch ( \Exception $ex ) {
			Log::critical( "patient form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'nursing' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
  
    public function getdata(Request $request)
{
    $query = RoomShifting::with(['patient','oldBed','newBed','oldRoom','newRoom']);

    return DataTables::of($query)
        ->addColumn('action', function ($query) {
            return '<form action="' . URL('nursing/room_shifting/' . $query->id . '/delete') . '" method="POST" style="display:inline;">
                    ' . method_field('DELETE') . csrf_field() . '
                        <button type="submit" title="Delete" class="text-danger btn-lg" style="border: none; background: none;" data-toggle="modal" data-target="#confirm-delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>';
        })
        ->addColumn('patient_name', function ($query) {
            return $query->patient ? $query->patient->fullname : 'Unknown Patient';
        })
        ->addColumn('old_room', function ($query) {
            return $query->oldRoom ? $query->oldRoom->type : 'No Room Assigned'; 
        })
        ->addColumn('old_bed', function ($query) {
            return $query->oldBed ? $query->oldBed->bed_no : 'No Bed Assigned';
        })
        ->addColumn('new_room', function ($query) {
            return $query->newRoom ? $query->newRoom->type : 'No Room Assigned';
        })
        ->addColumn('new_bed', function ($query) {
            return $query->newBed ? $query->newBed->bed_no : 'No Bed Assigned';
        })
        ->editColumn('shifted_at', function ($query) {
            return $query->shifted_at ? \Carbon\Carbon::parse($query->shifted_at)->format('d-m-Y H:i') : 'Not Shifted';
        })
        ->rawColumns(['action'])
        ->make(true);
}

    
    public function getBeds(Request $request)
    {
        $room_id = $request->input('room_id');
        $beds = Bed::where('room_id', $room_id)->get(); 
        
        return response()->json($beds);
    }
   
    public function getPatientDetails(Request $request)
    {
        $patient = Patient::where('id', $request->patient_id)->first();
        
        if ($patient) {
            $roomType = Room::where('id', $patient->room_type)->first();
            $bed = Bed::where('id', $patient->bed)->first();
            return response()->json([
                'room_type' => $roomType ? $roomType->type : '',
                'bed' => $bed ? $bed->bed_no : '',
            ]);
        }

        return response()->json(['room_type' => '', 'bed' => '']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'new_room_id'  => 'required|exists:rooms,id',
            'new_bed_id'   => 'required|exists:beds,id',
            'datetime'     => 'required|date',
        ]);
    
        try {
            $patient = Patient::where('id', $request->patient_id)->firstOrFail();
    
            if (!empty($patient->bed)) {
                Bed::where('id', $patient->bed)->update(['is_occupied' => 0]);
            }
    
            RoomShifting::create([
                'patient_id'  => $patient->id,
                'old_room_id' => $patient->room_type, 
                'old_bed_id'  => $patient->bed,        
                'new_room_id' => $request->new_room_id, 
                'new_bed_id'  => $request->new_bed_id,
                'shifted_at'  => $request->datetime, 
            ]);
    
            Bed::where('id', $request->new_bed_id)->update(['is_occupied' => 1]);
    
            $patient->update([
                'room_type' => $request->new_room_id,
                'bed'       => $request->new_bed_id,
            ]);
    
            $roomBed = RoomBed::where('patient_id', $patient->id)->first();
    
            if ($roomBed) {
                $roomBed->update([
                    'room_id'      => $request->new_room_id,
                    'bed_id'       => $request->new_bed_id,
                    'admit_status' => 'Admitted',
                ]);
            } else {
                RoomBed::create([
                    'patient_id'   => $patient->id,
                    'room_id'      => $request->new_room_id,
                    'bed_id'       => $request->new_bed_id,
                    'admit_status' => 'Admitted',
                ]);
            }
    
            return redirect()->back()->with('success', 'Room shifting successful!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    
}
