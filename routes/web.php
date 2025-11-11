<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [AppController::class, 'index'])->name('home');
Route::post('/register', [AppController::class, 'registerOrUpdate']);
Route::post('/verify-email', [AppController::class, 'verifyEmail']);
Route::post('/verify-pin', [AppController::class, 'verifyPin']);
Route::post('/logout', [AppController::class, 'logout'])->middleware('auth');


Route::get('/login', function () {
    return Inertia::render('LoginPage');
})->name('login');

// Route::prefix('manager')->name('manager.')->group(function () {
//     // Page d'accueil du manager
//     Route::get('/{step?}', [AppController::class,'manager'])->name('dashboard');
//     Route::get('/{step?}/{uuid}', [AppController::class,'show'])->name('show');
//     // // Page des produits
//     Route::get('/offre/add', function () {
//         return Inertia::render('AddOffrePage');
//     })->name('offre.add');
//     // // Page des prix
//     // Route::get('/prix', function () {
//     //     return Inertia::render('PrixPage');
//     // })->name('prix');

// })->middleware('auth');

Route::prefix('manager')->name('manager.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/{step?}', [AppController::class, 'manager'])->name('dashboard');
    Route::get('/{step?}/{uuid}', [AppController::class, 'show'])->name('show');
    Route::get('/offre/detail/{uuid}', [AppController::class, 'offreDetail'])->name('offre.detail');
    Route::post('/offre/add', [AppController::class, 'storeOffre'])->name('offre.add');

    Route::post('/offre/update-status/', [AppController::class, 'updateOffreStatus'])->name('offre.update.status');
    Route::post('/offre/delete/{uuid}', [AppController::class, 'deleteOffre'])->name('offre.delete');
    Route::post('/offre/tracking', [AppController::class, 'storeOffreTranking'])->name('offre.tracking');
    Route::post('/parametre', [AppController::class, 'storeEmail'])->name('parametre');
    Route::post('/parametre/email', [AppController::class, 'storeEmail'])->name('email.add');
    Route::post('/parametre/user', [AppController::class, 'storeUser'])->name('user.add');
    Route::post('/application/user/delete', [AppController::class, 'deleteApplicationData'])->name('application.delete');
});


Route::get('/apply-job/{uuid?}', [AppController::class, 'applyJob'])
    ->middleware('auth')
    ->name('apply.job');

Route::get('/completed', [AppController::class, 'completedProfile'])
    ->middleware('auth')
    ->name('completed.job');
Route::post('/completed/save', [AppController::class, 'storeCompletedProfile'])->middleware('auth');

Route::get('/apply-spontaneous', [AppController::class, 'spontanousApplication'])->middleware('auth');
Route::post('/apply-job/save', [AppController::class, 'storeOrUpdate'])->middleware('auth');
Route::get('/apply-job/success/{uuid}', [AppController::class, 'applySuccess'])->middleware('auth');
Route::get('/profile', [AppController::class, 'profile'])->middleware('auth');
Route::post('/upload-cv', [AppController::class, 'uploadCv'])->name('cv.add')->middleware('auth');
Route::delete('/user/document', [AppController::class, 'deleteUserDocument'])->middleware('auth');


Route::get('/register', function () {
    return Inertia::render('RegisterPage');
});


Route::get('/verify', function () {
    return Inertia::render('VerifyPinPage');
})->name('verify');


// Route::get('/verify-pin',function(){
//     return Inertia::render('LoginPage');
// } );



// Route::get('/', function () {
//     return view('welcome');
// });
