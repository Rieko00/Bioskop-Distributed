<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\KasirDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
});

// Legacy dashboard route - redirects to role-specific dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('kasir.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Role-specific dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/kasir/dashboard', [KasirDashboardController::class, 'index'])
        ->middleware('role:kasir')
        ->name('kasir.dashboard');
});

Route::middleware(['auth', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/branches', [AdminDashboardController::class, 'branches'])->name('branches');
    Route::get('/films', [AdminDashboardController::class, 'films'])->name('films');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
});

Route::middleware(['auth', 'check.role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tickets', [KasirDashboardController::class, 'tickets'])->name('tickets');
    Route::post('/tickets/create', [KasirDashboardController::class, 'createTicket'])->name('tickets.create');
    Route::get('/schedules', [KasirDashboardController::class, 'schedules'])->name('schedules');
    Route::get('/transactions', [KasirDashboardController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/{id}', [KasirDashboardController::class, 'getTransactionDetail'])->name('transactions.detail');
    Route::get('/transactions/{id}/print', [KasirDashboardController::class, 'printTransaction'])->name('transactions.print');
    Route::get('/seats/booked/{jadwalId}', [KasirDashboardController::class, 'getBookedSeats'])->name('seats.booked');
    Route::get('/schedules/branch/{branchId}', [KasirDashboardController::class, 'getSchedulesByBranch'])->name('schedules.branch');
    Route::get('/seatmap/{id_jadwal}', [KasirDashboardController::class, 'getSeatMap'])->name('seatmap');
    Route::get('/customers', [KasirDashboardController::class, 'customers'])->name('customers');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
