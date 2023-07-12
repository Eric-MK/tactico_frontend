<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlayerRecommendationController;
use App\Http\Controllers\ShortlistController;
use App\Http\Controllers\AdminController;

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
Route::delete('shortlist/{id}', [ShortlistController::class, 'destroy'])->name('shortlist.destroy');

/* Route::get('/shortlist', [UserController::class, 'loadingshortlist'])->name('shortlist');
 */
Route::post('shortlist', [ShortlistController::class, 'store'])->name('shortlist.store');
Route::get('shortlisted-players/{userId}', [ShortlistController::class, 'index']);

Route::post('deleteAccount/{user}', [UserController::class, 'deleteAccount'])->name('deleteAccount');
Route::get('/pro',[UserController::class, 'loadProfile']);
Route::get('/profile', [UserController::class, 'showProfile']);
Route::post('/profile', [UserController::class, 'updateProfile']);
Route::post('/verified', [UserController::class, 'verifiedOtp'])->name('verifiedOtp');
Route::get('/dashboard', [UserController::class, 'loadDashboard'])->name('dashboard');
Route::get('/resend-otp', [UserController::class, 'resendOtp'])->name('resendOtp');
Route::post('/recommendations', [PlayerRecommendationController::class, 'index']);
Route::get('/logout', [UserController::class, 'logout'])->name('logout');


/* Route::get('/page', [UserController::class, 'loadadmin'])->name('loadadmin')->middleware('admin');
 */
Route::get('verified-accounts', [AdminController::class, 'viewVerifiedAccounts'])->name('admin.verified-accounts')->middleware('admin');

Route::get('nonverified-accounts', [AdminController::class, 'viewNonVerifiedAccounts'])->name('admin.nonverified-accounts')->middleware('admin');
Route::get('deleted-accounts', [AdminController::class, 'viewDeletedAccounts'])->name('admin.deleted-accounts')->middleware('admin');
Route::post('/admin/unverify/{id}', [AdminController::class,'unverify'])->name('admin.unverify')->middleware('admin');
Route::post('/admin/verify/{id}', [AdminController::class,'verify'])->name('admin.verify')->middleware('admin');
Route::post('/admin/delete/{id}', [AdminController::class,'delete'])->name('admin.delete')->middleware('admin');


Route::get('/admin', function () {
})->middleware('admin');

/* Route::get('/nav', function () {
    return view('frontend.LandingPage');
});
 */
