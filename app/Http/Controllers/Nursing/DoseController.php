<?php

namespace App\Http\Controllers\Nursing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Patient;
use Yajra\DataTables\Facades\DataTables;
use App\Models\IpdLetterhead;
use App\Models\AdvanceBooking;
use App\Models\IpdPatientMedicine;
use DB;
use App\Models\Service;
use App\Models\PatientService;
use Carbon\Carbon;
use App\Models\Bed;
use App\Models\Room;



class DoseController extends Controller
{
    public function index()
    {
        try {
			return view( 'nursing.patient_dose.list');
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
                $letterheadUrl = URL('nursing/patient_dose/letterhead/' . $row->id); 
                $showUrl = URL('nursing/patient_dose/show/' . $row->id);  
                $serviceUrl = URL('nursing/patient_dose/service/' . $row->id); 

               
                $showButton = '<a href="' . $showUrl . '" title="View patient dose" class="btn-sm" style="background-color: #ff69b4; color: #fff;">Dose</a>';
        
                // $letterButton = '<a href="' . $letterheadUrl . '" 
                //                     class="btn text-primary letter-btn"
                //                     title="View Letterhead">
                //                     <i class="fa fa-hospital-o"></i>
                //                 </a>';
        
                $serviceButton = '<a href="' . $serviceUrl . '" 
                                class="btn text-success"
                                title="Add Services">
                                <i class="fa fa-medkit"></i>
                            </a>';

            return '<div style="display: flex; gap: 10px; align-items: center;">' 
                         . $showButton . $serviceButton . 
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
    // public function dose_page($id)
    // {
    //     $patient = Patient::find($id);

    //     if (!$patient) {
    //         return redirect()->route('nursing.patient_dose.data')->with('error', 'Patient not found.');
    //     }

    //     return view('nursing.patient_dose.dose' , compact('patient'));
    // }
    // public function storeLetterhead(Request $request, $id)
    // {
    //     try {
            
    //         $letterhead = new IpdLetterhead();
    //         $letterhead->patient_id = $request->patient_id;
    //         $letterhead->appointment_id = $request->appointment_id;
    //         $letterhead->bp = $this->appendBpUnit($request->bp);
    //         $letterhead->pulse = $this->appendPulseUnit($request->pulse);
    //         $letterhead->spo2 = $this->appendSpo2Unit($request->spo2);
    //         $letterhead->temp = $this->appendTempUnit($request->temp);
    //         $letterhead->rs = $request->rs;
    //         $letterhead->cvs = $request->cvs;
    //         $letterhead->ecg = $request->ecg;
    //         $letterhead->rbs = $request->rbs;
    //         $letterhead->report = $request->report;
    //         $letterhead->complaint = $request->complaint;
    //         $letterhead->datetime = $request->datetime;
    //         $letterhead->save();
    
    //         $medicines = $request->input('medicines', []); 
    //         foreach ($medicines as $medicine) {
    //             if (!isset($medicine['medicineName'], $medicine['quantity'], $medicine['frequency'])) {
    //                 \Log::error('Incomplete medicine data:', $medicine);
    //                 continue; 
    //             }
    
    //             $medicineRecord = new IpdPatientMedicine();
    //             $medicineRecord->ipd_letter_id = $letterhead->id;
    //             $medicineRecord->patient_id = $letterhead->patient_id;
    //             $medicineRecord->name = $medicine['medicineName'];
    //             $medicineRecord->quantity = $medicine['quantity']; 
    //             $medicineRecord->frequency = $medicine['frequency'];
    //             $medicineRecord->note = $medicine['note'];

    //             $medicineRecord->save();
    //         }
    
    
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Details saved successfully!',
    //         ]);
    
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred. Please try again.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    // private function appendBpUnit($bp)
    // {
    //     return $bp ? $bp . ' mmHg' : null;
    // }

    // private function appendPulseUnit($pulse)
    // {
    //     return $pulse ? $pulse . ' min' : null;
    // }

    // private function appendSpo2Unit($spo2)
    // {
    //     return $spo2 ? $spo2 . ' RA' : null;
    // }

    // private function appendTempUnit($temp)
    // {
    //     return $temp ? $temp . ' °F' : null;
    // }

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

        return view('nursing.patient_dose.show', compact('vital', 'medicines', 'patient'));
    }

    public function service($id)
    {
        $patient = Patient::findOrFail($id);
        $services = Service::all();
        $activeServices = PatientService::where('patient_id', $id)->get();
        $inactiveServices = PatientService::where('patient_id', $id)->get();

    
        return view('nursing.patient_dose.service', compact('patient', 'services', 'activeServices','inactiveServices'));
    }
    
    public function startservice(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $service = Service::findOrFail($request->service_id);
        $startTime = Carbon::now();

        $patientService = PatientService::create([
            'patient_id' => $request->patient_id,
            'service_id' => $request->service_id,
            'start_datetime' => $startTime,
            'end_datetime' => null,
            'calculate_amount' => 0,
        ]);

        return response()->json([
            'message' => 'Service started successfully',
            'data' => $patientService
        ]);
    }

    public function stopService(Request $request)
    {
        $service = PatientService::find($request->id);
    
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
    
        $startTime = Carbon::parse($service->start_datetime);
        $endTime = now();
        $hoursUsed = max(ceil($startTime->diffInMinutes($endTime) / 60), 12); // Minimum 12 hours
    
        $serviceAmountPerHour = $service->service->amount / 24; // Assuming amount is for 24 hours
        $serviceAmount = round($serviceAmountPerHour * $hoursUsed);
    
        $service->update([
            'end_datetime' => $endTime,
            'calculate_amount' => $serviceAmount
        ]);
    
        return response()->json([
            'message' => 'Service stopped and amount updated.',
            'end_datetime' => $endTime->format('Y-m-d h:i A'),
            'amount' => $serviceAmount
        ]);
    }
    
    public function updateAmount(Request $request)
    {
        $service = PatientService::find($request->id);
    
        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Service not found.']);
        }
    
        $service->update(['calculate_amount' => $request->calculate_amount]);
    
        return response()->json(['success' => true, 'message' => 'Amount updated successfully.']);
    }
    
    public function deleteService(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:patient_service,id'
        ]);

        $service = PatientService::find($request->id);
        if (!$service->end_datetime) {
            return response()->json(['message' => 'Active services cannot be deleted!'], 400);
        }
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully!']);
    }
  
    public function getAllPatient()
    {
        $rooms = Room::with([
            'beds.roomBeds.patient' 
        ])->orderBy('id', 'asc')->get();
    
        return view('nursing.view_patients', compact('rooms'));
    }
   
}
