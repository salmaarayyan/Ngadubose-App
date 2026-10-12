<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use \App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\TicketController;
use App\Livewire\Home;
use App\Livewire\Admin\ManageLaporan;
use App\Livewire\Admin\ManageUser;
use App\Livewire\Admin\DashboardIndex;

Route::get('/', Home::class)->name('home');
Route::get('/form-lapor', [TicketController::class, 'create'])->name('laporan.form'); 
Route::post('/store-laporan', [TicketController::class, 'storeReport'])->name('laporan.store');
Route::get('/laporan-sukses', [TicketController::class, 'sukses'])->name('laporan.sukses');
Route::get('/lacak-status', [TicketController::class, 'lacakStatus']);

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', \App\Http\Livewire\Admin\Login::class)->name('admin.login');
});

Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::livewire('/dashboard', DashboardIndex::class)
            ->name('dashboard');

        Route::livewire('/manage-user', ManageUser::class)
            ->name('manage-user');

        Route::livewire('/manage-laporan', ManageLaporan::class)
            ->name('manage-laporan');
    });


