<?php

namespace App\Http\Controllers\Nursing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\IpdPatientMedicine;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PatientFile;
use PDF;
class TreatmentController extends Controller
{
   
    public function index()
    {
        try {
			return view( 'nursing.treatment.list');
		} catch ( \Exception $ex ) {
			Log::critical( "patient list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
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
            ->addColumn('action', function ($row) {
                $showUrl = URL('nursing/treatment_sheet/show/' . $row->id);  

                $showButton = '<a href="' . $showUrl . '" title="View Treatment Sheet" class="btn-sm" style="background-color: #ff69b4; color: #fff;">Treatmentsheet</a>';
            

            
                $uploadFileAction = '<button class="btn btn-default upload-file-btn" data-id="' . $row->id . '" data-toggle="modal" data-target="#uploadFileModal"><i class="fa fa-upload"></i></button>';


                $openButton = '<a href="' . URL('nursing/treatment_sheet/' . $row->id . '/open') . '" ><i class="fa fa-eye"></i></a>';


                return '<div style="display: flex; gap: 10px; align-items: center;">' 
                            . $showButton . $uploadFileAction . $openButton .
                    '</div>';
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

    

    public function showdata($id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }
        return view('nursing.treatment.treatment', compact( 'patient'));
    }

 
    public function getPatientMedicines($id)
    {

        $medicines = IpdPatientMedicine::join('ipd_letterhead', 'ipd_patient_medicine.ipd_letter_id', '=', 'ipd_letterhead.id')
        ->where('ipd_letterhead.patient_id', $id)
        ->orderBy('ipd_letterhead.datetime', 'desc')
        ->select('ipd_patient_medicine.*', 'ipd_letterhead.datetime') 
        ->get();
        return response()->json(['medicines' => $medicines]);
    }
   
  
    public function updateMedicineStatus(Request $request)
    {
        try {
            $checkedDoses = $request->input('checkedDoses');
    
            if (!$checkedDoses || !is_array($checkedDoses)) {
                return response()->json(['success' => false, 'message' => 'Invalid form data received.']);
            }
    
            $medicineIds = array_unique(array_column($checkedDoses, 'medicine_id'));
            $medicines = IpdPatientMedicine::whereIn('id', $medicineIds)->get()->keyBy('id');
    
            foreach ($checkedDoses as $dose) {
                $medicineId = $dose['medicine_id'];
                $index = $dose['index'];
                $checked = $dose['checked'];
                $time = !empty($dose['time']) ? $dose['time'] : null;
    
                if (!isset($medicines[$medicineId])) {
                    continue;
                }
                $medicine = $medicines[$medicineId];
    
                $timeColumn = 'time' . ($index + 1);
    
                $existingStatus = explode(',', $medicine->status ?? '0,0,0,0');
    
                while (count($existingStatus) < 4) {
                    $existingStatus[] = '0';
                }
    
                if ($checked == 1) {
                    $existingStatus[$index] = '1'; 
                    $medicine->$timeColumn = $time ?? now()->format('H:i:s'); 
                } else {
                    $existingStatus[$index] = '0'; 
                    $medicine->$timeColumn = null; 
                }
    
                $medicine->status = implode(',', $existingStatus);
                $medicine->save();
            }
    
            return response()->json(['success' => true, 'message' => 'Medicine status and time updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function uploadFiles(Request $request)
    {
        try {
            $request->validate([
                'files.*' => 'required|file|max:2048',
                'patient_id' => 'required|exists:patients,id',
            ]);

            $patient_id = $request->patient_id;

            $appointments = Patient::query()
                ->select(['patients.id as patient_id', 'advance_booking.id as appointment_id'])
                ->join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
                ->where('patients.id', $patient_id)
                ->get();

            if ($appointments->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No appointment found for the given patient and date.']);
            }

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('PatientFiles', 'public');
                    foreach ($appointments as $appointment) {
                        $patientfile = new PatientFile();
                        $patientfile->patient_id = $patient_id;
                        $patientfile->appointment_id = $appointment->appointment_id;
                        $patientfile->file_name = $file->getClientOriginalName();
                        $patientfile->file_path = $path;
                        $patientfile->save();
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Patient file uploaded successfully!']);
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }
    public function showFile($id)
    {
        $patientFiles = PatientFile::where('patient_id', $id)->get();
        return view('nursing.treatment.showfile', compact('patientFiles', 'id'));
    }

    public function generatePDF($id)
    {
        $patientFiles = PatientFile::where('patient_id', $id)->get();

        $patient = Patient::findOrFail($id);
        $patientName = $patient->fullname; 
        $patientAge = $patient->age;
        $patientPhone = $patient->phone;
        $patientAddress = $patient->address;

        $pdf = PDF::loadView('nursing.treatment.generatepdf', compact('patientFiles', 'patientName', 'patientAge', 'patientPhone' ,'patientAddress'));
        return $pdf->download('PatientFiles.pdf'); // Download the PDF
    }

}
