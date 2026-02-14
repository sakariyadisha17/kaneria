<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\OpdLetterhead;
class HomeController extends Controller
{

    public function profile()
    {
        return view('profile');
    }

    public function update_profile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
            ]);

            if ($validator->passes())
            {
                $user = Auth::user();
                $user->name = $request->input('name');
                $user->email = $request->input('email');

                if($user->save())
                {
                    return redirect('profile')->with('success', 'Profile Updated Successfully');
                } else {
                    return redirect('profile')->with('error', 'Unable to Update! Please try again later');
                }
            }
            else
            {
                return redirect('profile')->withErrors($validator);
            }
        }
        catch(\Exception $ex)
        {
            return redirect('profile')
                ->with('error','Whoops! Something went wrong');
        }
    }

    public function change_password(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed'
            ]);

            if($validator->passes())
            {
                $user = Auth::user();
                $oldpassword = $request->input('current_password' );
                $newpassword = $request->input('password');
                $hash= Auth::user()->password;
                if(Hash::check($oldpassword, $hash))
                {
                    if($oldpassword !== $newpassword) {
                        $user->password = Hash::make($request->input('password'));
                        $user->save();
                        return redirect('profile')->with('success', 'Password has been updated successfully')->with("tab", "2");
                    }else{
                        return redirect('profile')->withInput()->with('error', 'Password should be different from old password')->with("tab", "2");
                    }
                }
                else{
                    return redirect('profile')->withInput()->with('error', 'Input your correct current password')->with("tab", "2");
                }
            }
            else
            {
                return redirect('profile')->withErrors($validator)->with("tab", "2");
            }
        }
        catch(\Exception $ex)
        {
            return redirect('profile')->with('error','Whoops! Something went wrong')->with("tab", "2");
        }
    }

    public function statusCounts(Request $request)
{
    try {
        $date = $request->date ?? date('Y-m-d'); // Default to today's date if none is provided

        $statusCounts = Patient::query()
            ->join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
            ->whereDate('advance_booking.date', $date)
            ->selectRaw("
                SUM(CASE WHEN patients.status = 'Pending' THEN 1 ELSE 0 END) as Pending,
                SUM(CASE WHEN patients.status = 'Arrived' THEN 1 ELSE 0 END) as Arrived,
                SUM(CASE WHEN patients.status = 'Report' THEN 1 ELSE 0 END) as Report,
                SUM(CASE WHEN patients.status = 'Completed' THEN 1 ELSE 0 END) as Completed,
                SUM(CASE WHEN patients.is_relative = 'yes' THEN 1 ELSE 0 END) as Special
            ")
            ->first();

        return response()->json([
            'success' => true,
            'counts' => $statusCounts,
        ]);
    } catch (\Exception $ex) {
        Log::error("Error fetching status counts: " . $ex->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch status counts.',
        ], 500);
    }
}

    public function sendReminders()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $twoDaysAgo = Carbon::now()->subDays(2)->toDateString();
    
        // Patients with appointment tomorrow
        $upcomingPatients = OpdLetterhead::with('patient')
            ->whereDate('next_date', $tomorrow)
            ->get();
    
        // Patients who had appointment 2 days ago
        $followUpPatients = OpdLetterhead::with('patient')
            ->whereDate('next_date', $twoDaysAgo)
            ->get();
    
        if ($upcomingPatients->isEmpty() && $followUpPatients->isEmpty()) {
            Log::info("No patients for reminder or follow-up.");
            return response()->json(['message' => 'No messages to send.']);
        }
    
        // Reminder message (for tomorrow)
        foreach ($upcomingPatients as $patient) {
            $message = "નમસ્કાર!🙏🏻 \n\nકાલના દિવસમાં તમારે કનેરિયા હોસ્પિટલમાં ફરી બતાવવાની અપોઇન્ટમેન્ટ માટે નીચે આપેલ નંબર પર સંપર્ક કરવો \n\n📞 કૉલ કરો: 9913340805 , 0285-2634951/0285-2635951\n\n📲 WhatsApp બૂક કરવા: wa.me/919913340805\n\n🌐 ઓનલાઈન બૂક કરવા:\n https://app.kaneriahospital.com/appointment\n\nઆપનો દિવસ શુભ રહે!";
            $this->sendWhatsAppMessage($patient->patient->phone, $message);
        }
    
        // Follow-up message (2 days after appointment)
        foreach ($followUpPatients as $patient) {
            $message = "આપનો સમય કાઢી કનેરિયા હોસ્પિટલમાં મુલાકાત લેવા બદલ ખૂબ ખૂબ આભાર! 🙏🏻\n\nઅમે તમારી વધુ સારી સેવાની અપેક્ષા રાખીએ છીએ. તમારું સ્વાસ્થ્ય અમારું પ્રાથમિક લક્ષ્ય છે.\n\nતબિયત ને લગતી મુંજવણ અથવા પ્રશ્ન માટે કનેરિયા હોસ્પિટલ ના મેડિકલ ઓફિસર સાથે સંપર્ક કરી શકો છો : 9904240805\n\nઆપનો દિવસ શુભ રહે!";
            $this->sendWhatsAppMessage($patient->patient->phone, $message);
        }
    
        Log::info("Reminders sent to {$upcomingPatients->count()} for tomorrow, and follow-ups sent to {$followUpPatients->count()}.");
        return response()->json(['message' => 'Reminder and follow-up messages sent.']);
    }


    private function sendWhatsAppMessage($phone, $message)
    {
        $apiUrl = env('WHATSAPP_API_URL'); 
        $appKey = env('WHATSAPP_API_KEY');      
        $authKey = env('WHATSAPP_AUTH_KEY');   

        try {
            $curl = curl_init();
    
            $data = [
                'to' => '91'.$phone,
                'message' => $message,
                'appkey' => $appKey,
                'authkey' => $authKey,
                'sandbox' => 'false' 
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

}




