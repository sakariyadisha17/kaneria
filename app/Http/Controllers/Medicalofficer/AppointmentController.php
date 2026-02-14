<?php

namespace App\Http\Controllers\Medicalofficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Patient;
use App\Models\PatientFile;
use Carbon\Carbon;
use PDF;
use App\Models\AdvanceBooking;
use App\Models\Diagnosis;
use DB;
use App\Models\OpdLetterhead;
use App\Models\Medicine;
use App\Models\OpdPatientMedicine;
use App\Http\Requests\StoreOpdLetterheadRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class AppointmentController extends Controller
{
    public function index()
    {
        try {
			return view('medical_officer.appointment.list');
		} catch ( \Exception $ex ) {
			Log::critical( "appointment list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'medical_officer' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

    public function getdata(Request $request)
    {
        $date = $request->date;

        // Base query
        $patients = Patient::query()
            ->select([
                'patients.*',
                'time_sheet.time_sheet as booking_time',
            ])
            ->join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
            ->join('time_sheet', 'advance_booking.time', '=', 'time_sheet.id');
    
        // Apply date filter if provided
        if (!empty($date)) {
            $patients->whereDate('advance_booking.date', $date);
        }
    
        $patients = $patients->orderByDesc('patients.created_at')->get();
        return DataTables::of($patients)
            ->addColumn('time', function ($patient) {
                return $patient->booking_time 
                    ? \Carbon\Carbon::parse($patient->booking_time)->format('h:i A') 
                    : 'N/A';
            })
            ->addColumn('status', function ($patient) {
                $statuses = [
                    'Pending' => 'Pending',
                    // 'Self Book' => 'Self Book',
                    'Arrived' => 'Arrived',
                    'Report' => 'Report',
                    'Completed' => 'Completed',
                ];
                $options = '';
                foreach ($statuses as $value => $label) {
                    $selected = $patient->status === $value ? 'selected' : '';
                    $options .= "<option value='{$value}' {$selected}>{$label}</option>";
                }
                return "
                    <select class='form-control change-status custom-select' data-id='{$patient->id}'>
                        {$options}
                    </select>
                ";
            })
            ->addColumn('background_color', function ($patient) {
                if ($patient->is_relative === 'yes') {
                    return '#DCDBD8';
                }
    
                switch ($patient->status) {
                    case 'Pending':
                        return '#f7e4ee'; 
                    case 'Arrived':
                        return '#D4F1F4';
                    case 'Completed':
                        return '#ccffcc'; 
                    case 'Report':
                        return '#EBF0FF'; 
                    // case 'Self Book':
                    //     return '#D6F5D6'; 
                    default:
                        return ''; 
                }
            })
            ->addColumn('action', function ($patient) {

                $letterAction = '';
                if ($patient->status === 'Arrived' ) {

                    if (auth()->user()->hasRole('Receptionist')) {
                        $letterAction = '<a href="' . URL('receptionist/appointments/' . $patient->id . '/letterhead') . '" class="btn text-primary letter-btn" title="View Letterhead"><i class="fa fa-hospital-o"></i></a>';
                    } elseif (auth()->user()->hasRole('Doctor')) {
                        $letterAction = '<a href="' . URL('doctor/appointments/' . $patient->id . '/letterhead') . '" class="btn text-primary letter-btn" title="View Letterhead"><i class="fa fa-hospital-o"></i></a>';
                    } elseif (auth()->user()->hasRole('Medical Officer')) {
                        $letterAction = '<a href="' . URL('medical_officer/appointments/' . $patient->id . '/letterhead') . '" class="btn text-primary letter-btn" title="View Letterhead"><i class="fa fa-hospital-o"></i></a>';
                    }
                }

                $EditletterAction = '';
                if ($patient->status === 'Report' || $patient->status === 'Completed') {

                    if (auth()->user()->hasRole('Receptionist')) {
                        $EditletterAction = '<a href="' . URL('receptionist/appointments/' . $patient->id . '/edit-letterhead') . '" class="btn text-success letter-btn" title="View Letterhead"><i class="fa fa-hospital-o"></i></a>';
                    } 
                    // elseif (auth()->user()->hasRole('Doctor')) {
                    //     $EditletterAction = '<a href="' . URL('doctor/appointments/' . $patient->id . '/edit-letterhead') . '" class="btn text-success letter-btn" title="View Letterhead"><i class="fa fa-hospital-o"></i></a>';
                    // } 
                    elseif (auth()->user()->hasRole('Medical Officer')) {
                        $EditletterAction = '<a href="' . URL('medical_officer/appointments/' . $patient->id . '/edit-letterhead') . '" class="btn text-success letter-btn" title="View Letterhead"><i class="fa fa-hospital-o"></i></a>';
                    }
                }

                
                $uploadFileAction = '';
            
                if ($patient->status === 'Completed' || $patient->status === 'Report' && $patient->file_uploaded) {
                    $uploadFileAction = '<i class="fa fa-file-upload text-success" title="File Uploaded"></i>';
                  
                } 
                elseif ($patient->status === 'Completed' || $patient->status === 'Report') {
                    $uploadFileAction = '<button class="btn btn-default upload-file-btn" data-id="' . $patient->id . '" data-toggle="modal" data-target="#uploadFileModal"><i class="fa fa-upload"></i></button>';
                    
                }
              
                $openButton = '';
                if ($patient->status === 'Completed' || $patient->status === 'Report') {
                    if (auth()->user()->hasRole('Receptionist')) {
                        $openButton = '<a href="' . URL('receptionist/appointments/' . $patient->id . '/open') . '" ><i class="fa fa-eye"></i></a>';
                    } elseif (auth()->user()->hasRole('Doctor')) {
                        $openButton = '<a href="' . URL('doctor/appointments/' . $patient->id . '/open') . '" ><i class="fa fa-eye"></i></a>';
                    } elseif (auth()->user()->hasRole('Medical Officer')) {
                        $openButton = '<a href="' . URL('medical_officer/appointments/' . $patient->id . '/open') . '" ><i class="fa fa-eye"></i></a>';
                    }
                }
               
                $deleteAction = '';
                if (auth()->user()->hasRole('Receptionist')) {
                    $deleteAction = '<a href="" data-href="' . URL('receptionist/appointments/' . $patient->id . '/delete') . '" 
                    data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg">
                    <i class="fa fa-trash"></i>
                    </a>';                
                } elseif (auth()->user()->hasRole('Doctor')) {
                    $deleteAction = '<a href="" data-href="' . URL('doctor/appointments/' . $patient->id . '/delete') . '" 
                    data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg">
                    <i class="fa fa-trash"></i>
                    </a>';                
                } elseif (auth()->user()->hasRole('Medical Officer')) {
                    $deleteAction = '<a href="" data-href="' . URL('medical_officer/appointments/' . $patient->id . '/delete') . '" 
                    data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg">
                    <i class="fa fa-trash"></i>
                    </a>';                
                }
               
                   
                $actionString = '<div style="display: flex; gap: 10px; align-items: center;">'.$letterAction . ' ' . $EditletterAction . ' ' . $uploadFileAction . ' ' . $openButton . ' ' . $deleteAction .'</div>';
 
                return $actionString;
            })
            
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    
    public function updateStatus(Request $request)
    {
        try {
            $patient = Patient::findOrFail($request->id);
            $appointment = AdvanceBooking::find($patient->appointment_id); 
            
            if (is_null($request->status)) {
                return response()->json(['success' => false, 'message' => 'Status cannot be null']);
            }

            $patient->status = $request->status;
            $patient->save();
            
            // Update the appointment status as well
            if ($appointment) {
                $appointment->status = $patient->status;
                $appointment->save();
            }

            if ($patient->status == 'Completed') {
                // $message = "કનેરિયા હોસ્પિટલ એન્ડ આઇ.સી.યુ ની મુલાકાત કરવા બદલ આભાર.\n\n    અમારી સેવા વધુ સારી બનાવવા માટે તમારો અભિપ્રાય આપવા વિનંતી. તેના માટે નીચેની લિંક પર ક્લિક કરો.\n\nગૂગલ રેવ્યુ: https://search.google.com/local/writereview?placeid=ChIJCZmWG20BWDkRp5VXevQ64xQ\n\nપ્રતિસાદ સર્વે લિંક : https://surveyheart.com/form/6759236d7b7e587b95d832f3\n\n    કનેરિયા હોસ્પિટલ એન્ડ આઇ.સી.યુ દર્દીની તબિયત બાબત હોસ્પિટલ ના મેડિકલ ઓફિસર સાથે વાત કરવા માટે: 9904240805 ના ફોન કે પછી મેસેજ થી સંપર્ક કરી શકાશે. \n\nફરી બતાવવા માટે , 9913340805 તથા (0285)2634951 / (0285)2635951 માં સંપર્ક કરવા વિનંતી. \n\nઓનલાઇન એપાઈન્ટમેન્ટ બુક કરવા માટે નીચેની લિંક પર ક્લિક કરો: kaneriahospital.com";
                $message = config("messages.appointment_completed")[mt_rand(0,5)];
                
                $whatsappResponse = $this->sendWhatsAppMessage("91" . $patient->phone, $message);
            }
            

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);

        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
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


    public function delete($id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $appointment = AdvanceBooking::find($patient->appointment_id);
            
            if ($appointment) {
                $appointment->delete();
            }
    
            if ($patient) {
                $patient->delete(); 
            }
            
            
            return redirect()->back()->with('success', 'Patient and Appointment permanently deleted successfully!');
        } catch (\Exception $ex) {
            Log::error('Error permanently deleting Patient: ' . $ex->getMessage());
            return redirect()->back()->with('error', 'An error occurred while permanently deleting the Patient.');
        }
    }

    public function uploadFiles(Request $request)
    {
        try {
            $request->validate([
                'files.*' => 'required|file',
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
        return view('medical_officer.appointment.showfile', compact('patientFiles', 'id'));
    }

    public function destroy($id)
    {
        $patientFile = PatientFile::findOrFail($id);

        // Delete the file from storage
        if (Storage::exists($patientFile->file_path)) {
            Storage::delete($patientFile->file_path);
        }

        // Delete the database record
        $patientFile->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }


    public function generatePDF($id)
    {
        $patientFiles = PatientFile::where('patient_id', $id)->get();

        $patient = Patient::findOrFail($id);
        $patientName = $patient->fullname; 
        $patientAge = $patient->age;
        $patientPhone = $patient->phone;
        $patientAddress = $patient->address;

        $pdf = PDF::loadView('medical_officer.appointment.generatepdf', compact('patientFiles', 'patientName', 'patientAge', 'patientPhone' ,'patientAddress'));
        return $pdf->download('PatientFiles.pdf'); // Download the PDF
    }

    public function exportCSV(Request $request)
    {
        $date = $request->input('date');
        $selectedDate = $date ? Carbon::parse($date)->toDateString() : null;
    
        $query = Patient::query()
            ->select(['patients.*', 'time_sheet.time_sheet as booking_time'])
            ->join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
            ->join('time_sheet', 'advance_booking.time', '=', 'time_sheet.id')
            ->orderBy('patients.created_at', 'desc');;
    
        if ($selectedDate) {
            $query->whereDate('patients.created_at', $selectedDate);
        }
    
        $patients = $query->get();
    
        $cashTotal = $patients->where('payment_type', 'Cash')->sum('amount');
        $onlineTotal = $patients->where('payment_type', 'Online')->sum('amount');
        $debitTotal = $patients->where('payment_type', 'Debit')->sum('amount');
        $totalAmount = $cashTotal + $onlineTotal;
    
        $filename = 'patients_' . now()->format('d-m-Y') . '.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];
    
        $columns = ['Patient ID', 'Time', 'Full Name', 'Age', 'Phone', 'Payment Type', 'Amount', 'Booking Date', 'Status'];
    
        $callback = function () use ($patients, $columns, $cashTotal, $onlineTotal, $debitTotal, $totalAmount) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
    
            fputcsv($file, $columns);
            foreach ($patients as $patient) {
                $formattedTime = $patient->booking_time 
                    ? Carbon::parse($patient->booking_time)->format('h:i A') 
                    : 'N/A';
                $bookingDate = $patient->created_at 
                    ? Carbon::parse($patient->created_at)->format('d-m-Y') 
                    : 'N/A';
                fputcsv($file, [
                    $patient->patient_id,
                    $formattedTime,
                    $patient->fullname,
                    $patient->age,
                    $patient->phone,
                    $patient->payment_type,
                    $patient->amount,
                    $bookingDate,
                    $patient->status,
                ]);
            }
    
            fputcsv($file, []);
            fputcsv($file, ['', '', '', '', '', '', 'Cash Payment Total', $cashTotal]);
            fputcsv($file, ['', '', '', '', '', '', 'Online Payment Total', $onlineTotal]);
            fputcsv($file, ['', '', '', '', '', '', 'Debit Payment Total', $debitTotal]);
            fputcsv($file, ['', '', '', '', '', '', 'Total Amount', $totalAmount]);
    
            fclose($file);
        };
    
        ob_clean(); // Clear any previous output
        return response()->stream($callback, 200, $headers);
    }
    
    public function exportPDF(Request $request)
    {
        $date = $request->input('date');
        $selectedDate = $date ? Carbon::parse($date)->toDateString() : null;

        $query = Patient::query()
            ->select(['patients.*', 'time_sheet.time_sheet as booking_time'])
            ->join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
            ->join('time_sheet', 'advance_booking.time', '=', 'time_sheet.id')
            ->orderBy('patients.created_at', 'desc');

        if ($selectedDate) {
            $query->whereDate('patients.created_at', $selectedDate);
        }

        $patients = $query->get();

        // Transform and calculate totals
        $patients->transform(function ($patient) {
            $patient->full_name = trim("{$patient->first_name} {$patient->middle_name} {$patient->last_name}");
            $patient->booking_time_formatted = $patient->booking_time 
                ? Carbon::parse($patient->booking_time)->format('h:i A') 
                : 'N/A';
            return $patient;
        });

        $cashTotal = $patients->where('payment_type', 'Cash')->sum('amount');
        $onlineTotal = $patients->where('payment_type', 'Online')->sum('amount');
        $debitTotal = $patients->where('payment_type', 'Debit')->sum('amount');
        $totalAmount = $cashTotal + $onlineTotal;

        $pdf = PDF::loadView('medical_officer.appointment.pdf', compact('patients', 'cashTotal', 'onlineTotal', 'debitTotal', 'totalAmount', 'selectedDate'))
        ->setPaper('A4', 'landscape');
        $filename = 'patients_' . Carbon::now()->format('d-m-Y') . '.pdf';

        return $pdf->download($filename);
    }

    

    //letterhead section
  
    public function getDiagnosisDetails(Request $request)
    {
        $diagnosisIds = $request->diagnosis_id; // Accept multiple diagnosis IDs
    
        if (!$diagnosisIds || !is_array($diagnosisIds)) {
            return response()->json(['success' => false, 'message' => 'No diagnosis selected.']);
        }
    
        // Fetch related medicines for all selected diagnoses
        $medicines = DB::table('diagnosis_medicine')
            ->join('medicine', 'diagnosis_medicine.medicine_id', '=', 'medicine.id')
            ->select('medicine.id', 'medicine.name', 'medicine.quantity', 'medicine.frequency')
            ->whereIn('diagnosis_medicine.diagnosis_id', $diagnosisIds)
            ->groupBy('medicine.id', 'medicine.name', 'medicine.quantity', 'medicine.frequency')
            ->get();
    
        // Fetch diet chart and note for selected diagnoses
        $diagnoses = DB::table('diagnosis')
            ->select('diat as diet', 'note')
            ->whereIn('', $diagnosisIds)
            ->get();
    
        if ($diagnoses->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Diagnosis not found.']);
        }
    
        // Combine diet and notes for multiple diagnoses
        $combinedDiet = $diagnoses->pluck('diet')->filter()->implode("\n");
        $combinedNote = $diagnoses->pluck('note')->filter()->implode("\n");
    
        return response()->json([
            'success' => true,
            'medicines' => $medicines,
            'diet' => $combinedDiet,
            'note' => $combinedNote,
        ]);
    }


    public function getMedicineList(Request $request)
    {
        $medicines = Medicine::all();
        
        $response = $medicines->map(function ($medicine) {
            return [
                'id' => $medicine->id,
                'name' => $medicine->name, // Use 'text' for the select2 display
                'quantity' => $medicine->quantity,
                'frequency' => $medicine->frequency,
                'note' => $medicine->note,

            ];
        });
        
        return response()->json($response);
    }
   
    public function printLetterhead($id)
    {
        $patient = Patient::findOrFail($id); 
    
        if (!$patient) {
            return redirect()->route('appointments.data')->with('error', 'Patient not found.');
        }
    
        $letterhead = OpdLetterhead::where('patient_id', $patient->id)
            ->where('appointment_id', $patient->appointment_id)
            ->latest()
            ->first();
        // Fetch medicines based on opd_letter_id and patient_id
        $medicines = [];
        if ($letterhead) {
            $medicines = OpdPatientMedicine::where('patient_id', $patient->id)->get();
        }
    
        // Return the view with all the necessary data for the print version
        return view('medical_officer.appointment.print_letterhead', compact('patient', 'letterhead', 'medicines'));
    }
    

    public function showletter($id)
    {
        $patient = Patient::findOrFail($id); 
        
        if (!$patient) {
            return redirect()->route('appointments.data')->with('error', 'Patient not found.');
        }

        $diagnosis = Diagnosis::get();
        $letterhead = OpdLetterhead::where('patient_id', $patient->id)
        ->where('appointment_id', $patient->appointment_id)
        ->latest() // Orders by created_at in descending order
        ->first();
    

        // Fetch medicines based on opd_letter_id and patient_id
        $medicines = [];
        // dd($letterhead);
        if ($letterhead) {
            $medicines = OpdPatientMedicine::where('patient_id', $patient->id)
                                        ->get();
        }
        // dd($medicines);
        return view('medical_officer.appointment.letterhead', compact('patient','diagnosis', 'letterhead', 'medicines'));
    }

    public function storeLetterhead(Request $request, $id)
    {
        // dd($request->next_date);
        DB::beginTransaction();  // Start a DB transaction
        
        try {
            // Save OPD letterhead
            $letterhead = new OpdLetterhead();
            $letterhead->patient_id = $request->patient_id;
            $letterhead->appointment_id = $request->appointment_id;
            $letterhead->bp = $this->appendBpUnit($request->bp);
            $letterhead->pulse = $this->appendPulseUnit($request->pulse);
            $letterhead->spo2 = $this->appendSpo2Unit($request->spo2);
            $letterhead->temp = $this->appendTempUnit($request->temp);
            $letterhead->rs = $request->rs;
            $letterhead->cvs = $request->cvs;
            $letterhead->ecg = $request->ecg;
            $letterhead->rbs = $request->rbs;
            if ($request->filled('diagnosis') && is_array($request->diagnosis) && count($request->diagnosis)) {
                $letterhead->diagnosis = json_encode($request->diagnosis);
            }            
            $letterhead->report = $request->report;
            $letterhead->next_report = $request->next_report;
            $letterhead->addition = $request->addition;
            $letterhead->complaint = $request->complaint;
            $letterhead->past_history = $request->past_history;
            $letterhead->note = $request->note;
            $letterhead->diat = $request->diat;
    
            if (!empty($request->next_date)) {
                try {
                    $letterhead->next_date = Carbon::createFromFormat('d-m-Y', $request->next_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    \Log::error('Date Format Error:', ['next_date' => $request->next_date, 'error' => $e->getMessage()]);
                }
            }
            
    
            // Update patient status before saving letterhead
            if ($patient = Patient::find($request->patient_id)) {
                $patient->status = 'Report';
                $patient->save();
            }
    
            // Update appointment status before saving letterhead
            if ($appointment = AdvanceBooking::find($request->appointment_id)) {
                $appointment->status = 'Report';
                $appointment->save();
            }
    
            // Now save the letterhead after updating statuses
            $letterhead->save();
    
            // Save new medicines only
            $medicines = $request->input('medicines', []); 
    
            foreach ($medicines as $medicine) {
                if (!isset($medicine['medicineName'], $medicine['quantity'], $medicine['frequency'])) {
                    \Log::error('Incomplete medicine data:', $medicine);
                    continue; 
                }
    
                // Check if the medicine already exists for the patient in the same letterhead
                $existingMedicine = OpdPatientMedicine::where('patient_id', $request->patient_id)
                    ->where('name', $medicine['medicineName'])
                    ->exists();
    
                if (!$existingMedicine) {
                    $medicineRecord = new OpdPatientMedicine();
                    $medicineRecord->opd_letter_id = $letterhead->id;
                    $medicineRecord->patient_id = $letterhead->patient_id;
                    $medicineRecord->name = $medicine['medicineName'];
                    $medicineRecord->quantity = $medicine['quantity']; 
                    $medicineRecord->frequency = $medicine['frequency'];
                    $medicineRecord->note = $medicine['note'];
                    $medicineRecord->extra_note = $medicine['extra_note'];
                    $medicineRecord->save();
                }
            }
    
            DB::commit();  // Commit transaction
    
            return response()->json([
                'success' => true,
                'message' => 'Details saved successfully!',
            ]);
    
        } catch (\Exception $e) {
            DB::rollback();  // Rollback transaction in case of an error
    
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    private function appendBpUnit($bp)
    {
        return $bp && !str_contains($bp, 'mmHg') ? $bp . ' mmHg' : $bp;
    }
    
    private function appendPulseUnit($pulse)
    {
        return $pulse && !str_contains($pulse, 'min') ? $pulse . ' min' : $pulse;
    }
    
    private function appendSpo2Unit($spo2)
    {
        return $spo2 && !str_contains($spo2, 'RA') ? $spo2 . ' RA' : $spo2;
    }
    
    private function appendTempUnit($temp)
    {
        return $temp && !str_contains($temp, '°F') ? $temp . ' °F' : $temp;
    }
    


    public function editLetter($id)
    {
        $patient = Patient::findOrFail($id); 
        $diagnosis = Diagnosis::get(); 
        $letterhead = OpdLetterhead::where('patient_id', $patient->id)
        ->where('appointment_id', $patient->appointment_id)
        ->latest() // Orders by created_at in descending order
        ->first();
    

        // Fetch medicines based on opd_letter_id and patient_id
        $medicines = [];
        // dd($letterhead);
        if ($letterhead) {
            $medicines = OpdPatientMedicine::where('patient_id', $patient->id)
                                        ->get();
        }

        // Pass patient, diagnosis, letterhead, and medicines to the view
        return view('medical_officer.appointment.edit-letterhead', compact('patient', 'diagnosis', 'letterhead', 'medicines'));
    }

   
    public function updateLetterhead(Request $request, $id)
    {
        
        try {
            $patient = Patient::findOrFail($id);
        
            $letterhead = OpdLetterhead::where('patient_id', $patient->id)
                                       ->where('appointment_id', $patient->appointment_id)
                                       ->first();
        
            if ($letterhead) {
                // Update letterhead fields
                $letterhead->bp =  $request->bp;
                $letterhead->pulse = $request->pulse;
                $letterhead->spo2 = $request->spo2;
                $letterhead->temp = $request->temp;
        
                $letterhead->rs = $request->rs;
                $letterhead->cvs = $request->cvs;
                $letterhead->ecg = $request->ecg;
                $letterhead->rbs = $request->rbs;
                if ($request->filled('diagnosis') && is_array($request->diagnosis) && count($request->diagnosis)) {
                    $letterhead->diagnosis = json_encode($request->diagnosis);
                }  
                $letterhead->report = $request->report;
                $letterhead->next_report = $request->next_report;
                $letterhead->addition = $request->addition;
                $letterhead->complaint = $request->complaint;
                $letterhead->past_history = $request->past_history;
                $letterhead->note = $request->note;
                $letterhead->diat = $request->diat;
        
                if ($request->has('next_date') && !empty($request->next_date)) {
                    try {
                        $letterhead->next_date = Carbon::createFromFormat('d-m-Y', $request->next_date)->format('Y-m-d');
                    } catch (\Exception $e) {
                        \Log::error('Invalid next_date format: ' . $request->next_date);
                        $letterhead->next_date = null;
                    }
                }
        
                $letterhead->save();
        
                $existingMedicines = OpdPatientMedicine::where('opd_letter_id', $letterhead->id)
                                                        ->pluck('name')
                                                        ->toArray(); 
        
                $medicines = $request->input('medicines', []);
                foreach ($medicines as $medicine) {
                    if (!isset($medicine['medicineName'], $medicine['quantity'], $medicine['frequency'], $medicine['note'])) {
                        \Log::error('Incomplete medicine data:', $medicine);
                        continue;
                    }
        
                    if (in_array($medicine['medicineName'], $existingMedicines)) {
                        continue;
                    }
        
                    $medicineRecord = new OpdPatientMedicine();
                    $medicineRecord->opd_letter_id = $letterhead->id;
                    $medicineRecord->patient_id = $letterhead->patient_id;
                    $medicineRecord->name = $medicine['medicineName'];
                    $medicineRecord->quantity = $medicine['quantity'];
                    $medicineRecord->frequency = $medicine['frequency'];
                    $medicineRecord->note = $medicine['note'];
                    $medicineRecord->extra_note = $medicine['extra_note'];
                    $medicineRecord->save();
                }
        
                $updatedMedicines = OpdPatientMedicine::where('opd_letter_id', $letterhead->id)->get();
        
        
                return response()->json([
                    'success' => true,
                    'message' => 'Letterhead updated successfully.',
                    'medicines' => $updatedMedicines
                ]);
            } else {
                throw new \Exception("Letterhead not found for the given patient and appointment.");
            }
        
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteptmedicine($id)
    {
        try {
            $medicine = OpdPatientMedicine::findOrFail($id);
            $medicine->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete medicine.']);
        }
    }

    public function getLetterhead(Request $request)
    {
        $data = OpdLetterhead::with('medicines')->where('patient_id', $request->patient_id)->first();
        return response()->json(['data' => $data]);
    }

    
}
