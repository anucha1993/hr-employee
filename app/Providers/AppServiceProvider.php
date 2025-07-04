<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Livewire\Reports\CustomerEmployeeReport;
use App\Livewire\Reports\SimpleTest;

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
        //
        Paginator::useBootstrap();
        
        // Register Livewire components
        $this->registerLivewireComponents();
    }
    
    /**
     * Register Livewire components
     */
    private function registerLivewireComponents(): void
    {
        // Check if we're using Livewire v3 (with new way of registering components)
        if (class_exists('\Livewire\Livewire') && method_exists('\Livewire\Livewire', 'component')) {
            // Try both kebab-case and snake_case for Livewire 3
            \Livewire\Livewire::component('reports.customer-employee-report', \App\Livewire\Reports\CustomerEmployeeReport::class);
            \Livewire\Livewire::component('reports.customer_employee_report', \App\Livewire\Reports\CustomerEmployeeReport::class);
            \Livewire\Livewire::component('reports.simple-test', \App\Livewire\Reports\SimpleTest::class);
        }
    }
}
