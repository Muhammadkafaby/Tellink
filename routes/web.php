<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPostController;
use App\Http\Middleware\TellinkAuth;

Route::get('/', function () { return view('welcome'); });

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

// Protected routes
Route::middleware([TellinkAuth::class])->group(function () {
    // User routes
    Route::get('/listuser', [UserController::class, 'index']);
    Route::get('/profile', [UserController::class, 'show']);
    Route::post('/profile/update', [UserController::class, 'update']);
    Route::post('/profile/upload-avatar', [UserController::class, 'uploadAvatar']);
    Route::delete('/profile/delete', [UserController::class, 'destroy']);
    
    // UserPost/Messages routes
    Route::get('/userpost', [UserPostController::class, 'index']);
    Route::post('/userpost', [UserPostController::class, 'store']);
    Route::put('/userpost/{id}', [UserPostController::class, 'update']);
    Route::delete('/userpost/{id}', [UserPostController::class, 'destroy']);
    
    // Report routes (coming soon)
    Route::get('/report', function () { return view('Report'); });
});
  

