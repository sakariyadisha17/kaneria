<?php

namespace App\Http\Controllers\Medicalofficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Patient;
use Yajra\DataTables\Facades\DataTables;
use App\Models\IpdLetterhead;
use App\Models\AdvanceBooking;
use App\Models\IpdPatientMedicine;
use DB;
use App\Models\Diagnosis;
use App\Models\PatientFile;
use PDF;

class DoseController extends Controller
{
    public function index()
    {
        try {
			return view( 'medical_officer.patient_dose.list');
		} catch ( \Exception $ex ) {
			Log::critical( "patient list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'medical_officer' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

    public function getData(Request $request)
    {
        $query = Patient::with(['room', 'bed']) 
            ->where('admit_status', 'Admitted')
            ->orderBy('id', 'desc');
    
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $letterheadUrl = URL('medical_officer/patient_dose/letterhead/' . $row->id); 
                $showUrl = URL('medical_officer/patient_dose/show/' . $row->id);  // Fixed URL
        
                $showButton = '<a href="' . $showUrl . '" title="Indoor case sheet" class="btn-sm" style="background-color: #ff69b4;  color: #fff;">Indoor Details</a>';
        
                $letterButton = '<a href="' . $letterheadUrl . '" title="Indoor case sheet" class="btn-sm" style="background-color: #7bc5f4; color: #fff;">Indoor sheet</a>';

                $openUrl = URL('medical_officer/patient_dose/' . $row->id . '/open');  

                $openButton = '<a href="' . $openUrl . '" title="View Treatment Sheet" class="btn-sm" style="background-color: #B069DB; color: #fff;">view report</a>';

                return '<div style="display: flex; gap: 10px; align-items: center;">' . $letterButton  . $showButton .  $openButton .'</div>';
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
    public function dose_page($id)
    {
        $patient = Patient::find($id);
        $diagnosis = Diagnosis::all();
        if (!$patient) {
            return redirect()->route('medical_officer.patient_dose.data')->with('error', 'Patient not found.');
        }

        return view('medical_officer.patient_dose.dose' , compact('patient','diagnosis'));
    }
    public function storeLetterhead(Request $request, $id)
    {
        
        try {
            $letterhead = new IpdLetterhead();
            $letterhead->patient_id = $request->patient_id;
            $letterhead->appointment_id = $request->appointment_id;
            $letterhead->bp = $this->appendBpUnit($request->bp);
            $letterhead->pulse = $this->appendPulseUnit($request->pulse);
            $letterhead->spo2 = $this->appendSpo2Unit($request->spo2);
            $letterhead->temp = $this->appendTempUnit($request->temp);
            $letterhead->ecg = $request->ecg;
            $letterhead->rbs = $request->rbs;
            $letterhead->report = $request->report;
            $letterhead->patient_note = $request->patient_note;
            $letterhead->diagnosis_id = $request->diagnosis_id;
            $letterhead->complaint = $request->complaint;
            $letterhead->datetime = $request->datetime;
            $letterhead->save();
    
            $medicines = $request->input('medicines', []); 
            foreach ($medicines as $medicine) {
                if (!isset($medicine['medicineName'], $medicine['quantity'], $medicine['frequency'])) {
                    \Log::error('Incomplete medicine data:', $medicine);
                    continue; 
                }
    
                $medicineRecord = new IpdPatientMedicine();
                $medicineRecord->ipd_letter_id = $letterhead->id;
                $medicineRecord->patient_id = $letterhead->patient_id;
                $medicineRecord->patient_note = $letterhead->patient_note;
                $medicineRecord->diagnosis_id = $letterhead->diagnosis_id;
                $medicineRecord->name = $medicine['medicineName'];
                $medicineRecord->quantity = $medicine['quantity']; // Save updated quantity
                $medicineRecord->frequency = $medicine['frequency'];
                $medicineRecord->note = $medicine['note'];

                $medicineRecord->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Details saved successfully!',
                ]);
            }
            
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    private function appendBpUnit($bp)
    {
        return $bp ? $bp . ' mmHg' : null;
    }

    private function appendPulseUnit($pulse)
    {
        return $pulse ? $pulse . ' min' : null;
    }

    private function appendSpo2Unit($spo2)
    {
        return $spo2 ? $spo2 . ' RA' : null;
    }

    private function appendTempUnit($temp)
    {
        return $temp ? $temp . ' °F' : null;
    }

    public function showdata($id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        $vital = IpdLetterhead::where('patient_id', $patient->id)
                    ->where('appointment_id', $patient->appointment_id)
                    ->orderBy('datetime', 'desc')
                    ->get();

        $medicines = IpdPatientMedicine::join('ipd_letterhead', 'ipd_patient_medicine.ipd_letter_id', '=', 'ipd_letterhead.id')
                        ->where('ipd_letterhead.patient_id', $patient->id)
                        ->orderBy('ipd_letterhead.datetime', 'desc')
                        ->select('ipd_patient_medicine.*', 'ipd_letterhead.datetime') 
                        ->get();

        return view('medical_officer.patient_dose.show', compact('vital', 'medicines', 'patient'));
    }

    public function showFile($id)
    {
        $patientFiles = PatientFile::where('patient_id', $id)->get();
        return view('medical_officer.patient_dose.showfile', compact('patientFiles', 'id'));
    }

    public function generatePDF($id)
    {
        $patientFiles = PatientFile::where('patient_id', $id)->get();

        $patient = Patient::findOrFail($id);
        $patientName = $patient->fullname; 
        $patientAge = $patient->age;
        $patientPhone = $patient->phone;
        $patientAddress = $patient->address;

        $pdf = PDF::loadView('medical_officer.patient_dose.generatepdf', compact('patientFiles', 'patientName', 'patientAge', 'patientPhone' ,'patientAddress'));
        return $pdf->download('PatientFiles.pdf'); // Download the PDF
    }
    
}
