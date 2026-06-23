<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/how-it-works', 'pages.how-it-works')->name('how-it-works');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('patients', PatientController::class);

    Route::get('/diagnoses', [DiagnosisController::class, 'index'])->name('diagnoses.index');
    Route::get('/diagnoses/create', [DiagnosisController::class, 'create'])->name('diagnoses.create');
    Route::post('/diagnoses', [DiagnosisController::class, 'store'])->name('diagnoses.store');
    Route::get('/diagnoses/{diagnosis}', [DiagnosisController::class, 'show'])->name('diagnoses.show');
    Route::patch('/diagnoses/{diagnosis}/review', [DiagnosisController::class, 'updateReview'])->name('diagnoses.review');
});

require __DIR__.'/auth.php';
