<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlayerRecommendationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/forgot-password', [UserController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/send-reset-link', [UserController::class, 'sendResetLink'])->name('sendResetLink');
Route::get('/reset-password/{token}', [UserController::class, 'showResetForm'])->name('resetPassword');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('saveResetPassword');
Route::get('/register', [UserController::class, 'loadRegister']);
Route::post('/register', [UserController::class, 'studentRegister'])->name('studentRegister');
Route::get('/login', [UserController::class, 'loadLogin'])->name('login');
Route::get('/', [UserController::class, 'loadLandigPage']);
Route::post('/login', [UserController::class, 'userLogin'])->name('userLogin');
Route::get('/verification/{id}', [UserController::class, 'verification']);
Route::post('deleteAccount/{user}', [UserController::class, 'deleteAccount'])->name('deleteAccount');
Route::get('/pro',[UserController::class, 'loadProfile']);
Route::get('/profile', [UserController::class, 'showProfile']);
Route::post('/profile', [UserController::class, 'updateProfile']);
Route::post('/verified', [UserController::class, 'verifiedOtp'])->name('verifiedOtp');
Route::get('/dashboard', [UserController::class, 'loadDashboard'])->name('dashboard');
Route::get('/resend-otp', [UserController::class, 'resendOtp'])->name('resendOtp');
Route::post('/recommendations', [PlayerRecommendationController::class, 'index']);
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

/* Route::get('/nav', function () {
    return view('frontend.LandingPage');
});
 */
