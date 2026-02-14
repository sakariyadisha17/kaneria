<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\Time;
use App\Models\AdvanceBooking;
use Log;
use Carbon\Carbon;


class OnlineBookController extends Controller
{
    public function index()
    {
        return view('appointment');
    }


    
    public function getAvailableTimes(Request $request)
    {
        $date = $request->input('date');

        if (!$date) {
            return response()->json(['error' => 'Invalid date provided'], 400);
        }

        // Count bookings per time_slot (time_sheet.id)
        // $bookedCounts = AdvanceBooking::whereDate('date', $date)
        //     ->select('time', \DB::raw('COUNT(*) as total'))
        //     ->groupBy('time')
        //     ->pluck('total', 'time')
        //     ->toArray();

        $times = Timesheet::selectRaw('MIN(id) as id, time_sheet')
        ->groupBy('time_sheet')
        ->orderBy('time_sheet')
        ->get()
        ->filter(function ($t) {
            $count = AdvanceBooking::whereDate('date', request('date'))
                ->whereHas('timesheet', function ($q) use ($t) {
                    $q->where('time_sheet', $t->time_sheet);
                })
                ->count();

            return $count < 3; // max 3 bookings
        })
        ->map(function ($t) {
            return [
                'id'   => $t->id, // ✅ now available
                'time' => \Carbon\Carbon::parse($t->time_sheet)->format('g:i A'),
            ];
        })
        ->values();

        return response()->json($times->values());
    }


     public function search(Request $request)
{
    $query = $request->get('query');

    $patients = Patient::where('fullname', 'LIKE', "%{$query}%")
        ->limit(10)
        ->get(['id', 'fullname', 'phone', 'address']);

    return response()->json($patients);
}

      // Store appointment
      public function storeBooking(Request $request)
      {
          $validated = $request->validate([
              'time_id' => 'required|exists:time_sheet,id',
              'fullname' => 'required|string|max:255',
              'phone' => 'required|string|max:15',
              'address' => 'required|string|max:255',
              'date' => 'required|date',
          ]);
      
          $timeSlot = Timesheet::find($validated['time_id']);
      
          if (!$timeSlot) {
              return response()->json(['message' => 'Invalid time slot selected.'], 400);
          }
      
          $existingBookings = AdvanceBooking::where('date', $validated['date'])
              ->where('time', $timeSlot->id)
              ->count();
               
          if ($existingBookings >= 3) {
              return response()->json(['message' => 'Selected time slot is fully booked.'], 400);
          }
      
          $booking = AdvanceBooking::create([
              'time' => $timeSlot->id,
              'fullname' => $validated['fullname'],
              'phone' => $validated['phone'],
              'address' => $validated['address'],
              'status' => 'Self Book',
              'date' => $validated['date'],
          ]);
      
          // Send success response immediately
          $response = [
              'message' => 'Booking successfully.',
              'booking' => $booking,
          ];
      
          // Trigger WhatsApp message asynchronously
        //   $message = "Hello {$validated['fullname']}, \nYour booking has been confirmed for \n" .
        //       date('d M Y', strtotime($validated['date'])) . " at " .
        //       date('h:i A', strtotime($timeSlot->time_sheet)) . ". \nThank you for choosing us!";

        $message = config("messages.appointment_booked")[mt_rand(0,4)];
        $message = str_replace("[date]", date('d/m/Y', strtotime($validated['date'])), $message);
        $message = str_replace("[time]", date('h:i', strtotime($timeSlot->time_sheet)), $message);
      
          dispatch(function () use ($validated, $message) {
              $this->sendWhatsAppMessage("91" . $validated['phone'], $message);
          })->afterResponse();
      
          return response()->json($response);
      }
      
      public function contact_us()
    {
        return view('contact');
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
}

?>
