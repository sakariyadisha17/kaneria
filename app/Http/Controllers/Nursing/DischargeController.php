<?php

namespace App\Http\Controllers\Nursing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Carbon\Carbon;
use App\Models\RoomBed;
use App\Models\Bed;

class DischargeController extends Controller
{
    public function index()
    {
        $patients = Patient::where('admit_status', 'admitted')->get();
        return view('nursing.discharge.index' , compact('patients'));
    }

    public function store(Request $request)
    {
        // Validate form inputs
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'discharge_type' => 'required|in:discharge,refer',
            'discharge_datetime' => 'required|date',
        ]);
    
        // Retrieve patient record
        $patient = Patient::find($request->patient_id);
    
        if (!$patient) {
            return back()->with('error', 'Patient not found.');
        }
    
        // Update patient data
        $patient->bed = null;
        $patient->room_type = null;
        $patient->discharge_datetime = Carbon::parse($request->discharge_datetime);
        $patient->admit_status = 'discharged';
        $patient->save();
    
        // Update room_bed status to 'discharged'
        RoomBed::where('patient_id', $patient->id)->update([
            'admit_status' => 'discharged'
        ]);
    
        // Update bed status to unoccupied (is_occupied = 0)
        Bed::where('id', $patient->bed_id)->update([
            'is_occupied' => 0
        ]);
    
        return back()->with('success', 'Patient discharged successfully.');
    }
}
