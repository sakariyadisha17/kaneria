<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }  
      
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
    
        // Attempt to log the user in
        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            // Authentication passed
            $user = Auth::user();
    
            if ($user->hasRole('Admin')) {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->hasRole('Doctor')) {
                return redirect('doctor/dashboard');
            } elseif ($user->hasRole('Medical Officer')) {
                return redirect('medical_officer/dashboard');
            } elseif ($user->hasRole('Nursing')) {
                return redirect('nursing/dashboard');
            } elseif ($user->hasRole('Receptionist')) {
                return redirect('receptionist/dashboard');
            } else {
                return redirect('/')
                    ->with('error', "Invalid credentials.")
                    ->withInput();
            }
        } else {
            // Authentication failed
            return redirect()->back()
                ->with('error', 'Invalid credentials.')
                ->withInput();
        }
    }
    
   
    public function logout() {
        Auth::logout();
		return redirect('/')->with("success", "You are logged out successfully.");
    }


    public function sendemail(){
        return view('auth.recover-password');
    }

    public function verifyemail(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'User invalid.'])->withInput();
        }
        return redirect()->back()->with('success', 'Send Otp Success.');
    }

}
