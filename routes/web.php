<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\TicketController;
use App\Livewire\Home;
use App\Livewire\Admin\DashboardIndex;
use App\Livewire\Admin\ManageUser;
use App\Livewire\Admin\ManageLaporan;
use App\Http\Controllers\GoogleDriveController;

Route::get('/google-drive/connect', [
    GoogleDriveController::class,
    'redirectToGoogle',
])->name('google-drive.connect');

Route::get('/google-drive/callback', [
    GoogleDriveController::class,
    'callback',
])->name('google-drive.callback');

Route::get('/', Home::class)->name('home');
Route::get('/form-lapor', [TicketController::class, 'create'])->name('laporan.form'); 
Route::post('/store-laporan', [TicketController::class, 'storeReport'])->name('laporan.store');
Route::get('/laporan-sukses', [TicketController::class, 'sukses'])->name('laporan.sukses');
Route::get('/lacak-status', [TicketController::class, 'lacakStatus'])->name('lacak.status');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('auth.login.submit');
});

Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::livewire('/admin/dashboard', 'admin.dashboard-index')
        ->name('admin.dashboard');

    Route::livewire('/admin/manage-user', 'admin.manage-user')
        ->name('manage-user');

    Route::livewire('/admin/manage-laporan', 'admin.manage-laporan')
        ->name('manage-laporan');
});