<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketProgressController;

Route::get('/', [TicketController::class, 'index']);
Route::get('/form-lapor', [TicketController::class, 'create'])->name('laporan.form'); 
Route::post('/store-laporan', [TicketController::class, 'storeReport'])->name('laporan.store');
Route::get('/laporan-sukses', [TicketController::class, 'sukses'])->name('laporan.sukses');
Route::get('/lacak-status', [TicketController::class, 'lacakStatus']);


Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
});

Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [TicketProgressController::class, 'adminDashboard'])->name('dashboard');

    Route::post('/ticket/update/{id}', [TicketProgressController::class, 'updateStatus'])->name('ticket.update');

    Route::get('/manage-user', [UserController::class, 'userIndex'])->name('manage-user');
    Route::post('/manage-user', [UserController::class, 'userStore'])->name('manage-user.store');
    Route::put('/manage-user/{id}', [UserController::class, 'userUpdate'])->name('manage-user.update');
    Route::delete('/manage-user/{id}', [UserController::class, 'userDestroy'])->name('manage-user.destroy');

});