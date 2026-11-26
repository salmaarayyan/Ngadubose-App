<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Admin\DashboardIndex;
use App\Livewire\Admin\ManageUser;
use App\Livewire\Admin\ManageLaporan;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::addComponent(
            name: 'admin.dashboard-index',
            class: DashboardIndex::class
        );

        Livewire::addComponent(
            name: 'admin.manage-user',
            class: ManageUser::class
        );

        Livewire::addComponent(
            name: 'admin.manage-laporan',
            class: ManageLaporan::class
        );
    }
}
