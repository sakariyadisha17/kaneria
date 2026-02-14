<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;

class SurveyController extends Controller
{
    public function index()
    {
        return view('survey');
    }
    public function store(Request $request)
    {
        $survey = new Survey();
        $survey->mobile = $request->mobile;
        $survey->doctor_rating = $request->doctor_rating;
        $survey->staff_rating = $request->staff_rating;
        $survey->recep_rating = $request->recep_rating;
        $survey->medical_store_staff = $request->medical_store_staff;
        $survey->lab_services = $request->lab_services;
        $survey->suggestions = $request->suggestions;
        $survey->save();    
      

        return redirect()->back()->with('success', 'ફોર્મ સફળતાપૂર્વક સબમિટ થયું!');
    }
}
