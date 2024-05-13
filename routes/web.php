<?php

use App\Http\Livewire\Auth\ForgotPassword;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\ResetPassword;
use App\Http\Livewire\Billing;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\ExampleLaravel\UserManagement;
use App\Http\Livewire\ExampleLaravel\UserProfile;
use App\Http\Livewire\ExampleLaravel\EtudiantController;
use App\Http\Livewire\Notifications;
use App\Http\Livewire\Profile;
use App\Http\Livewire\RTL;
use App\Http\Livewire\StaticSignIn;
use App\Http\Livewire\StaticSignUp;
use App\Http\Livewire\Tables;
use App\Http\Livewire\VirtualReality;
use GuzzleHttp\Middleware;

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

Route::get('/', function(){
    return redirect('sign-in');
});

Route::get('forgot-password', ForgotPassword::class)->middleware('guest')->name('password.forgot');
Route::get('reset-password/{id}', ResetPassword::class)->middleware('signed')->name('reset-password');



Route::get('sign-up', Register::class)->middleware('guest')->name('register');
Route::get('sign-in', Login::class)->middleware('guest')->name('login');

Route::get('user-profile', UserProfile::class)->middleware('auth')->name('user-profile');
Route::get('user-management', UserManagement::class)->middleware('auth')->name('user-management');

Route::get('etudiant-management', EtudiantController::class)->middleware('auth')->name('etudiant-management');

// Route::get('add-etudiant', EtudiantController::class)->middleware('auth')->name('add-etudiant');
// Route::get('add-etudiant-traitement/{id}', EtudiantController::class)->middleware('auth')->name('add-etudiant-traitement');
// Route::get('update-etudiant', EtudiantController::class)->middleware('auth')->name('update-etudiant');
// Route::get('update-etudiant-traitement/{id}', EtudiantController::class)->middleware('auth')->name('update-etudiant-traitement');
Route::get('delete-etudiant', EtudiantController::class)->middleware('auth')->name('delete-etudiant');
// Route::get('/etudiant',[EtudiantController::class , 'liste_etudiant']);
// Route::get('/ajouter',[EtudiantController::class , 'ajouter_etudiant']);
// Route::post('/ajouter/traitement',[EtudiantController::class , 'ajouter_etudiant_traitement']);
// Route::get('/update-etudiant/{id}',[EtudiantController::class , 'update_etudiant']);
// Route::post('/update/traitement',[EtudiantController::class , 'update_etudiant_traitement']);
// Route::get('/delete-etudiant/{id}',[EtudiantController::class , 'delete_etudiant']);

// Route::middleware(['auth'])->group(function () {
//     Route::get('etudiant-management', [EtudiantController::class, 'render'])->name('etudiant-management');
//     Route::get('etudiant-management/liste-etudiant', [EtudiantController::class, 'liste_etudiant'])->name('liste-etudiant');
//     Route::get('etudiant-management/ajouter-etudiant', [EtudiantController::class, 'ajouter_etudiant'])->name('ajouter-etudiant');
//     Route::post('etudiant-management/ajouter-etudiant', [EtudiantController::class, 'ajouter_etudiant_traitement'])->name('ajouter-etudiant-traitement');
//     Route::get('etudiant-management/update-etudiant/{id}', [EtudiantController::class, 'update_etudiant'])->name('update-etudiant');
//     Route::post('etudiant-management/update-etudiant/{id}', [EtudiantController::class, 'update_etudiant_traitement'])->name('update-etudiant-traitement');
//     Route::get('etudiant-management/delete-etudiant/{id}', [EtudiantController::class, 'delete_etudiant'])->name('delete-etudiant');
// });


Route::group(['middleware' => 'auth'], function () {
Route::get('dashboard', Dashboard::class)->name('dashboard');
Route::get('billing', Billing::class)->name('billing');
Route::get('profile', Profile::class)->name('profile');
Route::get('tables', Tables::class)->name('tables');
Route::get('notifications', Notifications::class)->name("notifications");
Route::get('virtual-reality', VirtualReality::class)->name('virtual-reality');
Route::get('static-sign-in', StaticSignIn::class)->name('static-sign-in');
Route::get('static-sign-up', StaticSignUp::class)->name('static-sign-up');
Route::get('rtl', RTL::class)->name('rtl');
});