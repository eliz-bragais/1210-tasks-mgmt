<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskMgmtController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [LoginController::class, 'login'])->name('login-post');
Route::post('/sign-up', [LoginController::class, 'signUp'])->name('sign-up-post');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authenticated API end point
Route::middleware('auth:sanctum')->group(function () {
    // Authentication related routes
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout-post');
    Route::post('/task-mgmt-create-subtask', [TaskMgmtController::class, 'storeSubTask'])->name('task.mgmt.create.subtask');
});
