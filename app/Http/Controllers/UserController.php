<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

class UserController extends Controller
{
    public function loadRegister()
    {
        return view('register');
    }

    public function showResetForm($token)
    {
        return view('reset_password_form', ['token' => $token]);
    }


    public function resetPassword(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $resetPassword = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetPassword) {
            return back()->with('error', 'Invalid token or email.');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the password reset record
        DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->delete();

        return redirect()->route('login')->with('success', 'Password reset successful. You can now log in with your new password.');
    }



    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }


    public function studentRegister(Request $request)
    {
        $request->validate([
            'name' => 'string|required|min:2',
            'email' => 'string|email|required|max:100|unique:users',
            'password' => 'string|required|confirmed|min:6',
            'country_code' => 'required',
            'phone' => 'required',
        ]);

        // Country code mapping
        $countryCodeMapping = [
            '+1' => 'US',
            '+30' => 'GR',
            '+254' => 'KE',
            '+44' => 'GB',
            '+27' => 'ZA',
            '+966' => 'SA',
        ];

        $phoneNumber = $request->country_code . $request->phone;

        // Retrieve the ISO country code
        $isoCountryCode = $countryCodeMapping[$request->country_code] ?? null;

        $phoneExists = User::where('phone', $phoneNumber)->exists();

        if ($phoneExists) {
            return back()->withErrors(['phone' => 'The phone number already exists.']);
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($phoneNumber, $isoCountryCode);
            if (!$phoneUtil->isValidNumber($numberProto)) {
                // If the number is not valid, return with an error message
                return back()->withErrors(['phone' => 'Phone number is not valid.']);
            }
        } catch (NumberParseException $e) {
            // If the number could not be parsed, return with an error message
            return back()->withErrors(['phone' => 'Phone number could not be parsed.']);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;

        // Append the country code to the phone number
        $user->phone = $request->country_code . $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect("/verification/" . $user->id);
    }

    public function loadLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function sendOtp($user, $email)
    {
        $otp = rand(100000, 999999);
        $time = time();

        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'email' => $email,
                'otp' => $otp,
                'created_at' => $time
            ]
        );

        $data['email'] = $email;
        $data['title'] = 'Mail Verification';
        $data['body'] = 'Your OTP is: ' . $otp;

        Mail::send('mailVerification', ['data' => $data], function ($message) use ($data) {
            $message->from('sender@example.com', 'Sender Name');
            $message->to($data['email'])->subject($data['title']);
        });
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'string|required|email',
            'password' => 'string|required'
        ]);

        $userCredential = $request->only('email', 'password');
        $userData = User::where('email', $request->email)->first();
        if ($userData && $userData->is_verified == 0) {
            //$this->sendOtp($userData, $request->email);
            return redirect("/verification/" . $userData->id);
        } elseif (Auth::attempt($userCredential)) {
            return redirect('/dashboard');
        } else {
            return back()->with('error', 'Username & Password is incorrect');
        }
    }

    public function loadDashboard()
    {
        if (Auth::check()) {
            return view('dashboard');
        }
        return redirect('/');
    }
    public function forgotPassword()
    {
        return view('forgot_password');
    }



    public function verification($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user || $user->is_verified == 1) {
            return redirect('/');
        }
        $email = $user->email;

        $this->sendOtp($user, $email); //OTP SEND

        return view('verification', compact('email'));
    }

    public function verifiedOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $otpData = EmailVerification::where('otp', $request->otp)->first();
        if (!$otpData) {
            return response()->json(['success' => false, 'msg' => 'You entered the wrong OTP']);
        } else {
            $currentTime = time();
            $time = $otpData->created_at;

            if ($currentTime >= $time && $time >= $currentTime - (90 + 5)) { // 90 seconds
                User::where('id', $user->id)->update([
                    'is_verified' => 1
                ]);
                return response()->json(['success' => true, 'msg' => 'Mail has been verified']);
            } else {
                return response()->json(['success' => false, 'msg' => 'Your OTP has expired']);
            }
        }
    }

    public function resendOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $otpData = EmailVerification::where('email', $request->email)->first();

        $currentTime = time();
        $time = $otpData->created_at;

        if ($currentTime >= $time && $time >= $currentTime - (90 + 5)) { // 90 seconds
            return response()->json(['success' => false, 'msg' => 'Please try again after some time']);
        } else {
            $this->sendOtp($user, $request->email); //OTP SEND
            return response()->json(['success' => true, 'msg' => 'OTP has been sent']);
        }
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found.');
        }

        // Generate a unique token and save it in the `password_resets` table
        $token = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send the password reset link to the user's email
        Mail::to($user->email)->send(new ResetPasswordMail($token));

        return back()->with('success', 'Password reset link has been sent to your email.');
    }
}
