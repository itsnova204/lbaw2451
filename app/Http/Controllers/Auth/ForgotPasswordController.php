<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No user found with this email address.']);
        }

        $token = Str::random(60);
        $passwordReset = new PasswordReset();
        $passwordReset->email = $request->email;
        $passwordReset->token = $token;
        $passwordReset->save();

        $resetLink = url('/password/reset/' . $token);

        Mail::send('auth.emails.password', ['resetLink' => $resetLink], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Password Reset Link');
        });

        Log::debug('Sent');

        return redirect()->route('login')->withSuccess('We have emailed your password reset link!');
    }
}