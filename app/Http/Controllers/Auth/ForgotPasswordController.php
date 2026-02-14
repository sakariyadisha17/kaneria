<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon; 
use App\Models\User; 
use Mail; 
use Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgetPasswordForm()
    {
       return view('auth.forgetpsw');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
    
        $token = Str::random(64);
    
        User::updateOrInsert(
            ['email' => $request->email],
            ['remember_token' => $token, 'created_at' => Carbon::now()]
        );
    
        Mail::send('email.forgetpsw', ['remember_token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        return back()->with('success', 'We have e-mailed your password reset link!');
    }

     public function showResetPasswordForm($token) { 
        return view('auth.forgetpswLink', ['remember_token' => $token]);
     }

     public function submitResetPasswordForm(Request $request)
     {
         $request->validate([
             'password' => 'required|string|min:6|confirmed',
             'password_confirmation' => 'required'
         ]);
     
         $user = User::where('remember_token', $request->token)->first();
     
         if (!$user) {
             return back()->withInput()->with('error', 'Invalid token!');
         }
     
         $user->update([
             'password' => Hash::make($request->password),
             'remember_token' => null 
         ]);
     
         return redirect('/')->with('success', 'Your password has been changed!');
     }
}


