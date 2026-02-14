<?php
namespace App\Http\Controllers\Medicalofficer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;

class HomeController extends Controller
{
    public function index()
    {
            $total_patient = Patient::count();

            $today = now()->format('Y-m-d');
            $today_appointment = Patient::query()->whereDate('created_at', $today)->count();
    
        return view('medical_officer.home', compact('total_patient','today_appointment'));
    }
}




