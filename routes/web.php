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

use App\Http\Livewire\ExampleLaravel\ContenusFormationController;
use App\Http\Controllers\ExportController;


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



Route::get('/professeurs', [ProfesseurController::class, 'liste_prof'])->name('prof.liste');
Route::post('/professeurs', [ProfesseurController::class, 'store'])->name('prof.store');
Route::post('/professeurs/update/{id}', [ProfesseurController::class, 'update'])->name('prof.update');
Route::delete('/professeurs/delete/{id}', [ProfesseurController::class, 'delete_prof'])->name('prof.delete');
Route::get('/professeurs/search', [ProfesseurController::class, 'search'])->name('prof.search');
Route::get('/professeurs/export', [ProfesseurController::class, 'export'])->name('export.profs'); // Si vous avez une méthode export




Route::get('/etudiants', [EtudiantController::class, 'index'])->name('etudiants.index');
Route::post('/etudiants', [EtudiantController::class, 'store'])->name('etudiants.store');
Route::post('/etudiants/{id}', [EtudiantController::class, 'update'])->name('etudiants.update');
Route::delete('/etudiants/{id}', [EtudiantController::class, 'destroy'])->name('etudiants.destroy');









Route::get('/contenues', [ContenusFormationController::class, 'liste_contenue'])->name('contenue.list');
Route::post('/contenues', [ContenusFormationController::class, 'store'])->name('contenue.store');
Route::put('/contenues/{id}', [ContenusFormationController::class, 'update'])->name('contenue.update');
Route::delete('/contenues/{id}', [ContenusFormationController::class, 'delete_contenue'])->name('contenue.delete');
Route::get('/search', [ContenusFormationController::class, 'search'])->name('contenue.search');
Route::get('/export/contenues', [ContenusFormationController::class, 'export'])->name('export.contenues');
Route::get('contenusformation-management', ContenusFormationController::class)->middleware('auth')->name('contenusformation-management');

Route::post('/prof/store', [ProfesseurController::class, 'store'])->name('prof.store');
Route::delete('/profs/{id}', [ProfesseurController::class, 'delete_prof'])->name('prof.delete');

Route::post('/profs', [ProfesseurController::class, 'store'])->name('prof.store');



Route::get('forgot-password', ForgotPassword::class)->middleware('guest')->name('password.forgot');
Route::get('reset-password/{id}', ResetPassword::class)->middleware('signed')->name('reset-password');



Route::get('sign-up', Register::class)->middleware('guest')->name('register');
Route::get('sign-in', Login::class)->middleware('guest')->name('login');

Route::get('user-profile', UserProfile::class)->middleware('auth')->name('user-profile');
Route::get('user-management', UserManagement::class)->middleware('auth')->name('user-management');

//Etudiant routes
Route::get('/etudiants', [EtudiantController::class, 'liste_etudiant'])->name('etudiant.list');
Route::post('/etudiants', [EtudiantController::class, 'store'])->name('etudiant.store');
Route::put('/etudiants/{id}', [EtudiantController::class, 'update'])->name('etudiant.update');
Route::delete('/etudiants/{id}', [EtudiantController::class, 'delete_etudiant'])->name('etudiant.delete');
Route::get('/search', [EtudiantController::class, 'search'])->name('etudiant.search');
Route::get('/export/etudiants', [EtudiantController::class, 'export'])->name('export.etudiants');
Route::get('etudiant-management', EtudiantController::class)->middleware('auth')->name('etudiant-management');
// Route::put('etudiants/{id}', [EtudiantController::class, 'update'])->middleware('auth')->name('etudiant.update');
// Route::post('/etudiants', [EtudiantController::class, 'store'])->middleware('auth')->name('etudiants.store');
// Route::get('/delete-etudiant/{id}',[EtudiantController::class , 'delete_etudiant'])->middleware('auth')->name('etudiant.delete');
// Route::post('/etudiant/store', [EtudiantController::class,'store'])->name('etudiant.store');
// Route::get('/search',[EtudiantController::class,'search']);
// Route::get('/export-excel',[EtudiantController::class , 'export'])->middleware('auth')->name('etudiants.export');
// Route::get('etudiant-management', EtudiantController::class)->middleware('auth')->name('etudiant-management');
// Route::put('etudiants/{id}', [EtudiantController::class, 'update'])->middleware('auth')->name('etudiant.update');
// Route::post('/etudiants', [EtudiantController::class, 'store'])->middleware('auth')->name('etudiants.store');
// Route::get('/delete-etudiant/{id}',[EtudiantController::class , 'delete_etudiant'])->middleware('auth')->name('etudiant.delete');
// Route::post('/etudiant/store', [EtudiantController::class,'store'])->name('etudiant.store');

// Route::get('/search', [EtudiantController::class, 'search'])->name('search');

// Route::get('/export-excel',[EtudiantController::class , 'export'])->middleware('auth')->name('etudiants.export');
// Route::get('/export-excel',[EtudiantController::class , 'export'])->middleware('auth')->name('etudiants.export');
// Route::get('/export-excel', [EtudiantController::class, 'export'])->middleware('auth')->name('etudiants.export');
// Route::get('/etudiants/search', [EtudiantController::class, 'search'])->name('etudiants.search');

//Professeur routes
Route::get('prof-management', ProfesseurController::class)->middleware('auth')->name('prof-management');
Route::get('prof-management', ProfesseurController::class)->middleware('auth')->name('prof-management');
Route::put('profs/{id}', [ProfesseurController::class, 'update'])->middleware('auth')->name('profs.update');
Route::post('/profs', [ProfesseurController::class, 'store'])->middleware('auth')->name('profs.store');
Route::get('/delete-prof/{id}',[ProfesseurController::class , 'delete_prof'])->middleware('auth')->name('profs.delete_etudiant');
Route::post('/prof/store', [ProfesseurController::class,'store'])->name('prof.store');
Route::get('/export/profs', [ProfesseurController::class, 'export'])->name('export.profs');
Route::put('/profs/{id}', [ProfesseurController::class, 'update'])->name('prof.update');


Route::put('/professeurs/{id}', [ProfesseurController::class, 'update'])->name('professeurs.update');

// Route::get('/search',[ProfesseurController::class,'search']);



// Route::put('profs/{id}', [ProfesseurController::class, 'update'])->middleware('auth')->name('profs.update');
// Route::post('/profs', [ProfesseurController::class, 'store'])->middleware('auth')->name('profs.store');
// Route::get('/delete-prof/{id}',[ProfesseurController::class , 'delete_prof'])->middleware('auth')->name('profs.delete_etudiant');
// Route::post('/prof/store', [ProfesseurController::class,'store'])->name('prof.store');
// Route::post('/prof/store', [ProfesseurController::class, 'store'])->name('prof.store');


// Route::post('/prof/store', [ProfesseurController::class, 'store'])->name('prof.store');

// Route::post('/profs/{id}/update', [ProfesseurController::class, 'update'])->name('prof.update');
// Route::get('/profs/{id}', [ProfesseurController::class, 'edit']);
// Route::get('/prof/delete/{id}', [ProfesseurController::class, 'delete'])->name('prof.delete');
// Route::get('/profs/export', [ProfesseurController::class, 'export'])->name('profs.export');

// Route::get('/edit-prof/{id}', [ProfesseurController::class, 'edit']);
// Route::post('/update-prof/{id}', [ProfesseurController::class, 'update']);

// Route::post('/prof/store', [ProfesseurController::class,'store'])->name('prof.store');
// Route::post('/prof/store', [ProfesseurController::class, 'store'])->name('prof.store');

Route::get('/export-excel',[ProfesseurController::class , 'export'])->middleware('auth')->name('profs.export');
// Route::get('/profs/search', [ProfesseurController::class, 'search'])->name('profs.search');

// Route::get('/export-excel',[ProfesseurController::class , 'export'])->middleware('auth')->name('profs.export');



Route::get('export/professeurs', [ExportController::class, 'exportProfesseurs'])->name('export.professeurs');
Route::get('export/etudiants', [ExportController::class, 'exportEtudiants'])->name('export.etudiants');
Route::get('export/formationa', [ExportController::class, 'formationsExport'])->name('formations.export');


Route::get('formations-management', FormationsController::class)->middleware('auth')->name('formations-management');
Route::put('formations/{id}', [FormationsController::class, 'update'])->middleware('auth')->name('formations.update');
Route::post('/formations', [FormationsController::class, 'store'])->middleware('auth')->name('formations.store');
Route::get('/delete-formation/{id}',[FormationsController::class , 'delete_formation'])->middleware('auth')->name('formations.delete_etudiant');
Route::post('/formation/store', [FormationsController::class,'store'])->name('formation.store');

Route::get('/contenus', [ContenusFormationController::class, 'liste_contenus'])->name('contennus-management');
Route::get('contennus-management', ContenusFormationController::class)->middleware('auth')->name('contenuus-management');
Route::post('/contenu/store', [ContenusFormationController::class,'store'])->name('contenu.store');
// Afficher la liste des contenus de formation
// Route::get('/contenus', [ContenusFormationController::class, 'index'])->name('contenus.index');

// Afficher le formulaire pour ajouter un nouveau contenu
// Route::get('/contenus/create', [ContenusFormationController::class, 'create'])->name('contenus.create');

// // Enregistrer un nouveau contenu
// Route::post('/contenus', [ContenusFormationController::class, 'store'])->name('contenus.store');

// // Afficher le formulaire pour modifier un contenu existant
// Route::get('/contenus/{id}/edit', [ContenusFormationController::class, 'edit'])->name('contenus.edit');

// // Mettre à jour un contenu existant
// Route::put('/contenus/{id}', [ContenusFormationController::class, 'update'])->name('contenus.update');

// // Supprimer un contenu existant
// Route::delete('/contenus/{id}', [ContenusFormationController::class, 'destroy'])->name('contenus.destroy');
// Route::get('/contenus-management', [ContenusFormationController::class, 'index'])->name('contenus-management');


// Route::get('/professeurs', [ProfesseurController::class, 'liste_prof'])->name('prof.liste');
// Route::post('/prof/store', [ProfesseurController::class, 'store'])->name('prof.store');
// Route::post('/prof/update/{id}', [ProfesseurController::class, 'update'])->name('prof.update');
// Route::delete('/prof/delete/{id}', [ProfesseurController::class, 'delete_prof'])->name('prof.delete');
// Route::get('/prof/search', [ProfesseurController::class, 'search'])->name('prof.search');









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