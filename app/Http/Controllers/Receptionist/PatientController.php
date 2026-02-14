<?php
namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Charge;
use App\Models\Time;
use App\Models\ReferDoctor;
use App\Models\AdvanceBooking;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\PatientRequest;
use Log;
use App\Models\Timesheet;
use Carbon\Carbon;
use App\Models\Doctor;
use Illuminate\Support\Facades\Http;


class PatientController extends Controller
{
   

    public function index()
    {
        try {
            $doctors = Doctor::all();
            $charges = Charge::all();

            return view('receptionist.patient.booking', compact('doctors', 'charges'));
		} catch ( \Exception $ex ) {
			Log::critical( "patient form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}  
    }

    public function getdata(Request $request)
    {
        if ($request->ajax()) {
            $date = $request->date ?? date('Y-m-d'); 
    
            $timeSlots = TimeSheet::with('time')
                ->get();
    
            $data = $timeSlots->map(function ($timeSlot) use ($date) {
                $booking = AdvanceBooking::where('time', $timeSlot->id)
                    ->whereDate('date', $date)
                    ->first();
    
                $timeFormatted = Carbon::createFromFormat('H:i:s', $timeSlot->time->time)
                    ->format('h:i A');
    
                $statusClass = '';
                if ($timeSlot->status == 1) {
                    $statusClass = 'background-color:rgb(229, 239, 245) !important';
                } 
                
                $patient = $booking ? Patient::where('appointment_id', $booking->id)->first() : null;

    
                return [
                    'id' => $booking->id ?? null,
                    'time_id' => $timeSlot->id,
                    'time' => $timeFormatted, 
                    'patient_id' => $patient ? $patient->patient_id : '',
                    'fullname' => $booking->fullname ?? '',
                    'phone' => $booking->phone ?? '',
                    'address' => $booking->address ?? '',
                    'status' => $booking->status ?? '',
                    'status_class' => $statusClass, 
                ];
            });
    
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return $row['id']
                        ? '<button class="btn btn-secondary btn-sm" disabled>Saved</button>'
                        : '<button class="btn btn-success btn-sm action" onclick="saveBooking('.$row['time_id'].')">Save</button>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'time_id' => 'required|exists:time_sheet,id', 
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'date' => 'required|date',
        ]);
    
        $timeSlot = TimeSheet::with('time')
            ->where('id', $request->time_id)
            ->first();
    
        if (!$timeSlot) {
            return response()->json([
                'message' => 'Invalid time selected.',
            ], 400);
        }
    
        $booking = AdvanceBooking::create([
            'time' => $timeSlot->id,
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'Pending',
            'date' => $request->date,
        ]);
        
        $time = date('H:i', strtotime($timeSlot->time_sheet)); 
        if ($time >= '04:00' && $time < '12:00') {
            $timePeriod = 'સવારે'; 
        } elseif ($time >= '12:00' && $time < '16:00') {
            $timePeriod = 'બપોરે'; 
        } elseif ($time >= '16:00' && $time < '20:00') {
            $timePeriod = 'સાંજે'; 
        } else {
            $timePeriod = 'રાત્રે'; 
        }
        
        // $message = "નમસ્કાર,\nકનેરિયા હોસ્પિટલ એન્ડ આઇ.સી.યુ \n\n" .
        // "તારીખ : " . date('d/m/Y', strtotime($request->date)) . " " . 
        // $timePeriod . " " . date('h:i', strtotime($timeSlot->time_sheet)) . " વાગ્યાની તમારી એપાઈન્ટમેન્ટ નોંધાયેલી છે.\n\n" .
        // "    તમને આપેલ સમય એ અંદાજિત સમય છે, ઈમરજન્સી દર્દીના લીધે સમયમાં ફેરફાર થઈ શકે છે \n\n".
        // " *ખાસ નોંધ :* \n" .
        // "    *જો દર્દી બેસી ના શકતા હોય અથવા તો ચાલી ના શકતા હોય તો આ નંબર પર અગાઉ થી જાણ કરવી :* 9904240805 \n\n" .
        // "અમને પસંદ કરવા બદલ આભાર. \n\n" .
        // " *કનેરિયા હોસ્પિટલ - ડૉક્ટર ટીમ :* \n" .
        // " ડૉ. મૌલિક કનેરિયા (M.D. Medicine) \n" .
        // " ડૉ. સૌરીન પટેલ (M.D. Medicine) \n" .
        // " ડૉ. રૂષિતા પરમાર (Medical Officer) \n" .
        // " ડૉ. જયદીપ મહેતા (Medical Officer) \n";

        $message = config("messages.appointment_booked")[mt_rand(0,4)];
        $message = str_replace("[date]", date('d/m/Y', strtotime($request->date)), $message);
        $message = str_replace("[time]", $timePeriod . " " . date('h:i', strtotime($timeSlot->time_sheet)), $message);

        $whatsappResponse = $this->sendWhatsAppMessage("91" . $request->phone, $message);
        
    
        if ($whatsappResponse['status'] == 'success') {
            return response()->json([
                'message' => 'Booking saved successfully and WhatsApp message sent!',
                'booking' => $booking,
            ]);
        } else {
            return response()->json([
                'message' => 'Booking saved, but failed to send WhatsApp message.',
                'booking' => $booking,
            ]);
        }
    }
    
    private function sendWhatsAppMessage($phone, $message)
    {
        $apiUrl = env('WHATSAPP_API_URL'); 
        $appKey = env('WHATSAPP_API_KEY');      
        $authKey = env('WHATSAPP_AUTH_KEY');   
    
        try {
            $curl = curl_init();
    
            $data = [
                'to' => $phone,
                'message' => $message,
                'appkey' => $appKey,
                'authkey' => $authKey
            ];
            // echo json_encode($data);exit;
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiUrl,               
                CURLOPT_RETURNTRANSFER => true,       
                CURLOPT_ENCODING => '',                
                CURLOPT_MAXREDIRS => 10,               
                CURLOPT_TIMEOUT => 0,                  
                CURLOPT_FOLLOWLOCATION => true,       
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,  
                CURLOPT_CUSTOMREQUEST => 'POST',       
                CURLOPT_POSTFIELDS => http_build_query($data), 
            ));
    
            $response = curl_exec($curl);
            // echo json_encode($response);exit;
    
            // Log the raw response from the WhatsApp API for debugging
            Log::info('WhatsApp API Response: ' . $response);
    
            if (curl_errno($curl)) {
                Log::error('cURL Error: ' . curl_error($curl)); // Log cURL error
                return [
                    'status' => 'error',
                    'message' => curl_error($curl),
                ];
            }
    
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl); 
    
            // Log HTTP code for additional debugging
            Log::info('HTTP Status Code: ' . $httpCode);
    
            if ($httpCode === 200) {
                return [
                    'status' => 'success',
                    'response' => json_decode($response, true),
                ];
            } else {
                Log::error('WhatsApp API Error Response: ' . $response); // Log the error response
                return [
                    'status' => 'error',
                    'response' => json_decode($response, true), 
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage()); // Log exception message
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    
    public function show($id)
    {
        $booking = AdvanceBooking::find($id);
        if ($booking) {
            return response()->json($booking); // Return booking data as JSON
        }
        return response()->json(['message' => 'Booking not found'], 404);
    }
   
    public function patientformdata()
    {
        try {
            // Fetch doctors and charges
            $doctors = Doctor::select('id', 'name')->get();
            $charges = Charge::select('id', 'type', 'amount')->get();
    
            return response()->json([
                'doctors' => $doctors,
                'charges' => $charges,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch data: ' . $e->getMessage(),
            ], 500);
        }
    }
   
    public function storePatient(PatientRequest $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:advance_booking,id',
            'patient_id' => 'nullable|exists:patients,patient_id',
            'amount' => 'required|numeric',
            'payment_type' => 'required|string|max:50',
            'gender' => 'required|string|in:Male,Female,Other',
            'age' => 'required|integer|min:1|max:120',
            'phone' => 'nullable|numeric|min:1000000000|max:9999999999', // For 10-digit numbers
            'charges' => 'required|array|min:1',
            'charges.*' => 'required|exists:charges,id',
            'referred_by' => 'nullable|exists:doctors,id',  
        ]);
    
        $appointment = AdvanceBooking::find($request->appointment_id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }
    
        try {
            $patient = new Patient();
    
            if ($request->has('patient_id')) {
                $existingPatient = Patient::withTrashed()
                    ->where('patient_id', $request->patient_id)
                    ->first();
            
                if (!$existingPatient) {
                    return response()->json(['error' => 'Patient not found'], 404);
                }
            
                // Copy existing patient data
                $patient->fullname = $existingPatient->fullname;
                $patient->address = $existingPatient->address;
                $patient->phone = $existingPatient->phone;
                $patient->referred_by = $request->referred_by;
                $patient->patient_id = $request->patient_id;
                // // Generate next patient_id
                // $lastPatientId = Patient::withTrashed()
                //     ->whereRaw('LENGTH(patient_id) = 8 AND patient_id REGEXP "^[0-9]+$"')
                //     ->orderByRaw('CAST(patient_id AS UNSIGNED) DESC')
                //     ->value('patient_id');
            
                // $nextPatientId = $lastPatientId
                //     ? str_pad(((int)$lastPatientId) + 1, 8, '0', STR_PAD_LEFT)
                //     : '00000001';
            
                
            
            } elseif ($request->has('generated_patient_id')) {
                // Generate next patient_id (not using request value)
                $lastPatientId = Patient::withTrashed()
                    ->whereRaw('LENGTH(patient_id) = 8 AND patient_id REGEXP "^[0-9]+$"')
                    ->orderByRaw('CAST(patient_id AS UNSIGNED) DESC')
                    ->value('patient_id');
            
                $nextPatientId = $lastPatientId
                    ? str_pad(((int)$lastPatientId) + 1, 8, '0', STR_PAD_LEFT)
                    : '00000001';
            
                $patient->patient_id = $nextPatientId;
            
            } else {
                // Fallback if neither patient_id nor generated_patient_id is given
                $lastPatientId = Patient::withTrashed()
                    ->whereRaw('LENGTH(patient_id) = 8 AND patient_id REGEXP "^[0-9]+$"')
                    ->orderByRaw('CAST(patient_id AS UNSIGNED) DESC')
                    ->value('patient_id');
            
                $nextPatientId = $lastPatientId
                    ? str_pad(((int)$lastPatientId) + 1, 8, '0', STR_PAD_LEFT)
                    : '00000001';
            
                $patient->patient_id = $nextPatientId;
            }

            
    
            $patient->appointment_id = $appointment->id;
            $patient->fullname = $appointment->fullname;
            $patient->address = $appointment->address;
            $patient->phone = $appointment->phone;
            $patient->gender = $request->gender;
            $patient->age = $request->age;
            $patient->charges = json_encode($request->charges);
            $patient->amount = $request->amount;
            $patient->payment_type = $request->payment_type;
            $patient->status = 'Arrived';
            $patient->is_relative = $request->is_relative;
            $patient->save();
    
            $appointment->status = 'Arrived';
            $appointment->save();
    
            if ($request->has('referred_by')) {
                $doctor = Doctor::find($request->referred_by);
                // echo json_encode($doctor);exit;
                if ($doctor) {
                    $message = "નમસ્કાર,\nકનેરિયા હોસ્પિટલ એન્ડ આઇ.સી.યુ \n\n" .
                    
                    "    તમે રીફર કરેલ દર્દી {$patient->fullname} હોસ્પિટલમાં opd માં વિઝિટ કરેલ છે. \n\n".
                    " *કનેરિયા હોસ્પિટલ - ડૉક્ટર ટીમ :* \n" .
                    " ડૉ. મૌલિક કનેરિયા (M.D. Medicine) \n" .
                    " ડૉ. સૌરીન પટેલ (M.D. Medicine) \n" .
                    " ડૉ. રૂષિતા પરમાર (Medical Officer) \n" .
                    " ડૉ. જયદીપ મહેતા (Medical Officer) \n";
                    $whatsappResponse = $this->sendDoctorWhatsAppMsg("91".$doctor->phone, $message);
                    if ($whatsappResponse['status'] == 'error') {
                        Log::error('WhatsApp message failed: ' . $whatsappResponse['message']);
                    }
                }
            }
            return response()->json(['success' => 'Patient saved successfully!'], 200);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save patient: ' . $e->getMessage()], 500);
        }
    }
    
    private function sendDoctorWhatsAppMsg($phone, $message)
    {
        $apiUrl = env('WHATSAPP_API_URL');
        $appKey = env('WHATSAPP_API_KEY');
        $authKey = env('WHATSAPP_AUTH_KEY');
    
        try {
            $curl = curl_init();
    
            if (!preg_match('/^\d{10,15}$/', $phone)) {
                return [
                    'status' => 'error',
                    'message' => 'Invalid phone number format.',
                ];
            }
    
            $data = [
                'to' => $phone,
                'message' => $message,
                'appkey' => $appKey,
                'authkey' => $authKey
            ];
    
            curl_setopt_array($curl, [
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($data),
            ]);
    
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                return [
                    'status' => 'error',
                    'message' => curl_error($curl),
                ];
            }
    
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
    
            if ($httpCode === 200) {
                return [
                    'status' => 'success',
                    'response' => json_decode($response, true),
                ];
            } else {
                return [
                    'status' => 'error',
                    'response' => json_decode($response, true),
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
    
    
    
    public function edit_patient($appointmentId, Request $request)
    {
        if ($request->isMethod('get')) {
            $appointment = AdvanceBooking::with('patient')->findOrFail($appointmentId);
            return response()->json($appointment);
        }
    
        if ($request->isMethod('post')) {
            $appointment = AdvanceBooking::withTrashed()->findOrFail($appointmentId);
            
            $patient = Patient::where('appointment_id', $appointment->id)->first();
    
            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }
    
            $totalAmount = $request->amount - $request->return_amount;
    
            $appointment->status = 'Arrived';
            $appointment->fullname = $request->fullname;
            $appointment->address = $request->address;
            $appointment->phone = $request->phone;
            $appointment->save();
    
            $patient->fullname = $appointment->fullname;
            $patient->phone = $appointment->phone;
            $patient->address = $appointment->address;
            $patient->age = $request->age;
            $patient->gender = $request->gender;
            $patient->referred_by = $request->referred_by;
            $patient->charges = $request->charges;
            $patient->amount = $request->amount;
            $patient->payment_type = $request->payment_type;
            $patient->return_amount = $request->return_amount;
            $patient->total_amount = $totalAmount;
            $patient->status = 'Arrived';
            $patient->is_relative = $request->is_relative;
            $patient->created_at = now();
            $patient->save();
    
            return response()->json(['success' => 'Patient updated successfully']);
        }
    }
    
    
    public function destroy_patient($appointmentId, Request $request)
    {
        try {

            $appointment = AdvanceBooking::findOrFail($appointmentId);
            
            $appointment->delete();

    
            return response()->json(['success' => 'Appointment & Patient Record is Deleted '], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete patient and related records', 'details' => $e->getMessage()], 500);
        }
    }

    public function softDeletePatient($appointmentId, Request $request)
    {
        try {
            $appointment = AdvanceBooking::findOrFail($appointmentId);
            $patient = Patient::where('appointment_id', $appointment->id)->first();    
            if (!$patient) {
                return response()->json(['error' => 'Patient not found'], 404);
            }
    
            $patient->delete(); 
            $appointment->delete(); 
    
            return response()->json(['success' => 'Patient record soft-deleted'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to soft delete the patient record',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function generateId() {
        $lastPatient = Patient::orderBy('id', 'desc')->first();
        $newId = sprintf('%08d', $lastPatient ? $lastPatient->id + 1 : 1);
        return response()->json(['success' => true, 'patient_id' => $newId]);
    }
    
    public function getDetails(Request $request) {

        $patient = Patient::where('patient_id', $request->patient_id)->withTrashed()->first();
        if ($patient) {
            return response()->json(['success' => true, 'patient' => $patient]);
        }
        return response()->json(['success' => false]);
    }

     public function dayPatients(Request $request)
    {
        $date = Carbon::parse($request->date);
        $type = $request->type; // total | new | old

        $query = Patient::join(
            'advance_booking',
            'patients.appointment_id',
            '=',
            'advance_booking.id'
        )
        ->whereDate('advance_booking.date', $date)
        ->where('advance_booking.status', 'Arrived')
        ->select('patients.*');

        // 🔹 Filter by type
        if ($type === 'new') {
            $query->where(function ($q) {
                $q->whereNull('patients.charges')
                ->orWhere('patients.charges', 'NOT LIKE', '%"2"%');
            });
        }

        if ($type === 'old') {
            $query->where('patients.charges', 'LIKE', '%"2"%');
        }

        $patients = $query->distinct()->get();

        return view('receptionist.patient.day_list', compact('patients', 'date', 'type'));
    }


}
