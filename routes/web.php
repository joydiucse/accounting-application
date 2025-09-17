<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DollarIncomeController;
use App\Http\Controllers\DollarExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard route - accessible by all authenticated users
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Routes that require authentication
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Income routes - accessible by admin and accountant
    Route::middleware('role:admin,accountant')->group(function () {
        Route::resource('incomes', IncomeController::class);
        Route::resource('expenses', ExpenseController::class);
        Route::resource('dollar-incomes', DollarIncomeController::class);
        Route::resource('dollar-expenses', DollarExpenseController::class);
    });
    
    // Category routes - admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        
        // Company Profile routes
        Route::get('/company-profile', [CompanyProfileController::class, 'index'])->name('company-profile.index');
        Route::get('/company-profile/create', [CompanyProfileController::class, 'create'])->name('company-profile.create');
        Route::post('/company-profile', [CompanyProfileController::class, 'store'])->name('company-profile.store');
        Route::get('/company-profile/edit', [CompanyProfileController::class, 'edit'])->name('company-profile.edit');
        Route::patch('/company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');
    });
    
    // Reporting routes - accessible by all authenticated users
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
    Route::get('/reports/export-pdf', [DashboardController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/export-excel', [DashboardController::class, 'exportExcel'])->name('reports.export-excel');
});

require __DIR__.'/auth.php';
