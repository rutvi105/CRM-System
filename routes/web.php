<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/**
 * Web Routes - All application routes
 * File Location: routes/web.php
 */

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    
    // Dashboard - Role-based
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ticket Management Routes
    Route::resource('tickets', TicketController::class);
    
    // Additional ticket actions
    Route::post('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])
        ->name('tickets.update-status');
    
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])
        ->name('tickets.assign')
        ->middleware('role:admin,agent');
    
    Route::post('/tickets/{ticket}/update-priority', [TicketController::class, 'updatePriority'])
        ->name('tickets.update-priority')
        ->middleware('role:admin,agent');

    // Admin-only routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // Reports & Analytics
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sla-compliance', [ReportController::class, 'slaCompliance'])->name('reports.sla');
        Route::get('/reports/agent-performance', [ReportController::class, 'agentPerformance'])->name('reports.agent');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        
        // Activity Logs
        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
    });
});

require __DIR__.'/auth.php';