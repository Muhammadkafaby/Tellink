<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPostController;
use App\Http\Controllers\TellinkProxyController;
use App\Http\Middleware\TellinkAuth;

Route::redirect('/', '/login');

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

// Protected routes
Route::middleware([TellinkAuth::class])->group(function () {
    // User routes
    Route::get('/listuser', [UserController::class, 'index']);
    Route::get('/profile', [UserController::class, 'profile']); // Current user profile
    Route::get('/user/{id}', [UserController::class, 'show']); // View other user profile
    Route::post('/profile/update', [UserController::class, 'updateProfile']);
    Route::post('/profile/change-password', [UserController::class, 'changePassword']);
    Route::post('/profile/upload-avatar', [UserController::class, 'uploadAvatar']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
    
    // UserPost/Messages routes
    Route::get('/userpost', [UserPostController::class, 'index']);
    Route::post('/userpost', [UserPostController::class, 'store']);
    Route::put('/userpost/{id}', [UserPostController::class, 'update']);
    Route::delete('/userpost/{id}', [UserPostController::class, 'destroy']);
    
    // Report routes (coming soon)
    Route::get('/report', function () { return view('Report'); });
    
    // Tellink API Proxy routes
    Route::prefix('api/tellink')->group(function () {
        Route::get('/users', [TellinkProxyController::class, 'getUsers']);
        Route::get('/users/{nim}', [TellinkProxyController::class, 'getUserDetail']);
        Route::post('/register', [TellinkProxyController::class, 'registerUser']);
        Route::post('/delete-user', [TellinkProxyController::class, 'deleteUser']);
        Route::get('/messages', [TellinkProxyController::class, 'getMessages']);
        Route::post('/messages', [TellinkProxyController::class, 'createMessage']);
        Route::put('/messages/{id}', [TellinkProxyController::class, 'updateMessage']);
        Route::delete('/messages/{id}', [TellinkProxyController::class, 'deleteMessage']);
        Route::get('/reports', [TellinkProxyController::class, 'getReports']);
    });
});
  

