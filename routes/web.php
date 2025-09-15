<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [AppController::class, 'index']);
Route::post('/register', [AppController::class, 'register']);
Route::post('/verify-email', [AppController::class, 'verifyEmail']);
Route::post('/verify-pin', [AppController::class, 'verifyPin']);


Route::get('/login',function(){
    return Inertia::render('LoginPage');
} )->name('login');

Route::get('/apply-job',function(){
    return Inertia::render('ApplyPage');
})->middleware('auth');


Route::get('/register',function(){
    return Inertia::render('RegisterPage');
} );


Route::get('/verify',function(){
    return Inertia::render('VerifyPinPage');
})->name('verify');


// Route::get('/verify-pin',function(){
//     return Inertia::render('LoginPage');
// } );



// Route::get('/', function () {
//     return view('welcome');
// });


