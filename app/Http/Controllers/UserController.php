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

    public function loadProfile()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        return view('frontend.profile');
    }

    public function loadingshortlist()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        return view('frontend.ShortlistPage');
    }
     public function loadLandigPage()
    {
        return view('frontend.LandingPage');
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

    public function showProfile()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    $user = Auth::user();

    $request->validate([
        'name' => 'string|nullable|min:2',
        'email' => 'string|email|nullable|max:100|unique:users,email,'.$user->id,
        'phone' => 'nullable',
        'password' => 'nullable|confirmed|min:6',
    ], [
        'email.unique' => 'The email entered is already taken by another user.',
    ]);

    if ($request->name) {
        $user->name = $request->name;
    }

    if ($request->email && $request->email != $user->email) {
        $user->email = $request->email;
        $user->is_verified = 0;  // Set is_verified to 0
        $user->save();
        Auth::logout(); // logout the user

        return redirect('/login');
    }

    if ($request->phone) {
        $user->phone = $request->phone;
    }

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->save();



    return redirect('/pro')->with('success', 'Profile updated successfully.');
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
        'email' => 'string|email|required|max:100',
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

    // Check if phone number exists and belongs to a deleted user
    $deletedUser = User::where(function ($query) use ($request, $phoneNumber) {
        $query->where('phone', $phoneNumber)
            ->orWhere('email', $request->email);
    })->where('is_deleted', 1)->first();

    if (!$deletedUser) {
        // Check if the phone number or email is already used by a non-deleted user
        $exists = User::where(function ($query) use ($request, $phoneNumber) {
            $query->where('phone', $phoneNumber)
                ->orWhere('email', $request->email);
        })->where('is_deleted', 0)->exists();

        if ($exists) {
            return back()->withErrors(['phone' => 'The phone number or email already exists.']);
        }
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

    if ($deletedUser) {
        // If the user was deleted, update their information and restore their account
        $deletedUser->name = $request->name;
        $deletedUser->email = $request->email;
        $deletedUser->phone = $phoneNumber;
        $deletedUser->password = Hash::make($request->password);
        $deletedUser->is_deleted = 0; // Unmark the user as deleted
        $deletedUser->save();
        $userId = $deletedUser->id;
    } else {
        // If the user is new, create a new User instance
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $phoneNumber;
        $user->password = Hash::make($request->password);
        $user->save();
        $userId = $user->id;
    }

    return redirect("/verification/" . $userId);
}



    public function loadLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('login');
    }

public function deleteAccount(User $user)
{
    $user->update(['is_deleted' => 1]);
    $user->update(['is_verified' => 0]);

    // Log the user out
    Auth::logout();

    return redirect()->route('login')->with('message', 'Account successfully deleted');
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

        // Check if the user exists and if the account has been deleted
        if ($userData && $userData->is_deleted == 1) {
            return back()->with('error', 'Username & Password is incorrect');
        } elseif ($userData && $userData->is_verified == 0) {
            //$this->sendOtp($userData, $request->email);
            return redirect("/verification/" . $userData->id);
        } elseif (Auth::attempt($userCredential)) {
            if ($userData->role === 'admin') {
                return redirect('verified-accounts'); // redirect to admin page
            }
            return redirect('/dashboard'); // redirect to user page
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

    /* public function loadadmin()
    {
        return view('Admin.admin');
    }
 */
}
