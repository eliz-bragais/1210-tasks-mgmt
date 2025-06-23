<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskMgmtController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::get('/sign-up', [LoginController::class, 'signupPage'])->name('sign-up');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/task-mgmt', [TaskMgmtController::class, 'index'])->name('task-mgmt');
    Route::post('/task-mgmt-create', [TaskMgmtController::class, 'store'])->name('task.mgmt.create');
    Route::post('/task-mgmt-create-subtask', [TaskMgmtController::class, 'storeSubTask'])->name('task.mgmt.create.subtask');
    Route::post('/task-mgmt-status-update', [TaskMgmtController::class, 'updateStatusTask'])->name('task.mgmt.status.update');
    Route::post('/task-mgmt-saveType-update', [TaskMgmtController::class, 'updateSaveTypeTask'])->name('task.mgmt.saveType.update');
    Route::get('/task-mgmt/delete-task', [TaskMgmtController::class, 'deleteTask'])->name('delete.task');
    Route::get('/task-mgmt/retrieve-task', [TaskMgmtController::class, 'retrieveTask'])->name('retrieve.task');
});