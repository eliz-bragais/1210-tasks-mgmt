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

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('/sign-up', [LoginController::class, 'signupPage'])->name('sign-up');

Route::get('/task-mgmt', [TaskMgmtController::class, 'index'])->name('task-mgmt');
Route::post('/task-mgmt-create', [TaskMgmtController::class, 'store'])->name('task.mgmt.create');
Route::post('/task-mgmt-create-subtask', [TaskMgmtController::class, 'storeSubTask'])->name('task.mgmt.create.subtask');
