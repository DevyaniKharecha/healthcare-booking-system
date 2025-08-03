<?php

use Illuminate\Support\Facades\Route;
// routes/api.php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\HealthcareProfessionalController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/professionals', [HealthcareProfessionalController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'cancel']);
    Route::patch('/appointments/{id}/complete', [AppointmentController::class, 'markAsCompleted']);
});

