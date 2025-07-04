<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Customers\CustomerEdit;
use App\Livewire\Employees\EmployeeForm;
use App\Livewire\Customers\CustomerIndex;
use App\Livewire\Employees\EmployeeIndex;
use App\Livewire\Customers\CustomerCreate;
use App\Http\Controllers\RoutingController;
use App\Livewire\Globalsets\GlobalSetManager;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/global-sets', GlobalSetManager::class)->name('global-sets');
    
    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        // Controller-based routes
        Route::get('/customer-employee', \App\Livewire\Reports\CustomerEmployeeReport::class)->name('customer-employee');
        Route::post('/customer-employee/export', [\App\Http\Controllers\Reports\CustomerEmployeeReportController::class, 'export'])->name('customer-employee.export');
        
        // Employee Report Routes
        Route::get('/employee-report', \App\Livewire\Reports\EmployeeReport::class)->name('employee-report');
        
        // Labor Demand Report Route
        Route::get('/labor-demand', \App\Livewire\Reports\LaborDemandReport::class)->name('labor-demand');
        Route::get('/labor-demand/export', [\App\Http\Controllers\Reports\LaborDemandReportController::class, 'export'])->name('labor-demand.export');
    });
});
Route::middleware(['auth'])->group(function () {
    Route::get('/', CustomerIndex::class)->name('index');
    Route::get('/home', CustomerIndex::class)->name('index');
    Route::prefix('customer')
        ->name('customer.')
        ->group(function () {
            Route::get('/', CustomerIndex::class)->name('index');
            Route::get('/{customer}/edit', CustomerEdit::class)->name('edit');
            Route::get('/create', CustomerCreate::class)->name('create');
        });
});

// Employee
Route::middleware(['auth'])->group(function () {
 Route::get('/employees', EmployeeIndex::class)->name('employees.index');
Route::get('/employees/create', EmployeeForm::class)->name('employees.create');
Route::get('/employees/{id}/edit', EmployeeForm::class)->name('employees.edit');

});

require __DIR__ . '/auth.php';

Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    // Route::get('', [RoutingController::class, 'index'])->name('root');
    // Route::get('/home', fn()=>view('index'))->name('home');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
