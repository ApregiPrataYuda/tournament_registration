<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegistratianTeam\RegistrationTeamController;
use App\Http\Controllers\Api\Auth\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register-akun', [AuthController::class, 'register'])->name('api.register.akun');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware('auth:sanctum')->group(function () {
Route::get('list-team-register', [RegistrationTeamController::class, 'index'])->name('api.list.team.register');
Route::get('list-team-register-detail/{id}', [RegistrationTeamController::class, 'show'])->name('api.list.team.register.detail');
Route::post('add-team-register', [RegistrationTeamController::class, 'store'])->name('api.add.team.register');  
Route::delete('delete-team-register/{id}', [RegistrationTeamController::class, 'destroy'])->name('api.delete.team.register');
});

