<?php
namespace App\Http\Controllers\Nursing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;
use App\Models\Medicine;

class HomeController extends Controller
{
    public function index()
    {
            $total_patient = Patient::count();

            $today = now()->format('Y-m-d');
            $today_appointment = Patient::query()->whereDate('created_at', $today)->count();
    
        return view('nursing.home', compact('total_patient','today_appointment'));
    }

    public function getMedicineList(Request $request)
    {
        // echo "hi";exit;
        $medicines = Medicine::all();
        
        $response = $medicines->map(function ($medicine) {
            return [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'quantity' => $medicine->quantity,
                'frequency' => $medicine->frequency,
                'note' => $medicine->note,

            ];
        });
        return response()->json($response);
    }
}




