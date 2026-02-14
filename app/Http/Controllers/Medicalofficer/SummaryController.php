<?php

namespace App\Http\Controllers\Medicalofficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Carbon\Carbon;
use App\Models\RoomBed;
use App\Models\Bed;
use Yajra\DataTables\Facades\DataTables;
use App\Models\OpdLetterhead;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\IpdPatientMedicine;
use App\Models\IpdLetterhead;

class SummaryController extends Controller
{
    public function index()
    {
        return view('medical_officer.discharge_summary.list');
    }

    public function getData(Request $request)
    {
        $query = Patient::with(['room', 'bed']) 
        ->where('admit_status', 'Discharged')
        ->orderBy('id', 'desc');

        return DataTables::of($query)
        ->addColumn('action', function ($row) {
            $showUrl = URL('medical_officer/discharge_summary/show/' . $row->id);  
    
            $showButton = '<a href="' . $showUrl . '" title="Discharge summary" class="btn-sm" style="background-color: #ff69b4;  color: #fff;">Discharge summary</a>';
    
            return '<div style="display: flex; gap: 10px; align-items: center;">'  . $showButton . '</div>';
        })
       
   
        ->rawColumns(['action'])
        ->make(true);
    }
    public function show(Request $request, $id)
    {
        try {
            $patient = Patient::find($id);
            if($patient){
                return view('medical_officer.discharge_summary.details', compact('patient'));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function saveDischargeSummary(Request $request, $patientId)
    {
        $request->validate([
            'complaints' => 'required|string',
            'discharge_medication' => 'required',
            'relative_name' => 'required|string',
            'relative_num' => 'required|string',
        ]);
    
        $patient = Patient::findOrFail($patientId);
        $patient->update([
            'complaints' => $request->complaints,
            'discharge_medication' => $request->discharge_medication,
            'relative_name' => $request->relative_name,
            'relative_num' => $request->relative_num,
            'admit_status' => 'Discharged',
        ]);
    
        return response()->json(['success' => true, 'message' => 'Discharge summary saved successfully!']);
    }


    
    public function printDischargeSummary($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        // dd($patient);
        // Retrieve first and last OPD Letterhead entry for the patient
        $firstOpd = OpdLetterhead::where('patient_id', $patientId)->orderBy('created_at', 'asc')->first();
        $lastOpd = OpdLetterhead::where('patient_id', $patientId)->orderBy('created_at', 'desc')->first();
        $indoorMedicine = IpdPatientMedicine::where('patient_id', $patient->id)->get();
        $dischargeMedicine = json_decode($patient->discharge_medication, true) ?? [];
        $diagnosis = IpdLetterhead::where('patient_id', $patient->id)
        ->with('diagnosis') // Load related diagnosis
        ->orderBy('created_at', 'desc')
        ->first();
    
        // Access the diagnosis details
        $diagnosisDetails = $diagnosis->name ?? null;
        $todayDateTime = Carbon::now()->format('Y-m-d H:i:s');
        // Load the HTML view with patient and OPD data
        $pdf = PDF::loadView('medical_officer.discharge_summary.discharge_summary_print', compact('patient', 'firstOpd', 'lastOpd', 'indoorMedicine','dischargeMedicine','diagnosisDetails', 'todayDateTime'))
            ->setPaper('A4')
            ->setOptions([
                'defaultFont' => 'Noto Sans Gujarati', // Supports Gujarati text
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
    
        return $pdf->download('discharge_summary_' . $patient->id . '.pdf');
    }
    

    

}
