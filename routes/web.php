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
use App\Http\Livewire\ExampleLaravel\ProfesseurController;
use App\Http\Livewire\ExampleLaravel\FormationsController;


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

//Etudiant routes
Route::get('etudiant-management', EtudiantController::class)->middleware('auth')->name('etudiant-management');
Route::put('etudiants/{id}', [EtudiantController::class, 'update'])->middleware('auth')->name('etudiants.update');
Route::post('/etudiants', [EtudiantController::class, 'store'])->middleware('auth')->name('etudiants.store');
Route::get('/delete-etudiant/{id}',[EtudiantController::class , 'delete_etudiant'])->middleware('auth')->name('etudiants.delete_etudiant');
Route::post('/etudiant/store', [EtudiantController::class,'store'])->name('etudiant.store');

//Professeur routes
Route::get('prof-management', ProfesseurController::class)->middleware('auth')->name('prof-management');
Route::put('profs/{id}', [ProfesseurController::class, 'update'])->middleware('auth')->name('profs.update');
Route::post('/profs', [ProfesseurController::class, 'store'])->middleware('auth')->name('profs.store');
Route::get('/delete-prof/{id}',[ProfesseurController::class , 'delete_prof'])->middleware('auth')->name('profs.delete_etudiant');
Route::post('/prof/store', [ProfesseurController::class,'store'])->name('prof.store');


Route::get('formations-management', FormationsController::class)->middleware('auth')->name('formations-management');
Route::put('formations/{id}', [FormationsController::class, 'update'])->middleware('auth')->name('formations.update');
Route::post('/formations', [FormationsController::class, 'store'])->middleware('auth')->name('formations.store');
Route::get('/delete-formation/{id}',[FormationsController::class , 'delete_formation'])->middleware('auth')->name('formations.delete_etudiant');
Route::post('/formation/store', [FormationsController::class,'store'])->name('formation.store');




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