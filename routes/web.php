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

    // User Management Routes
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/role/{role}', [AdminDashboardController::class, 'getUsersByRole'])->name('users.by-role');
    Route::get('/users/{id}', [AdminDashboardController::class, 'getUser'])->name('users.show');
    Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{id}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::put('/users/{id}/password', [AdminDashboardController::class, 'updateUserPassword'])->name('users.update-password');
    // Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('users.destroy');

    // Branch Management Routes
    Route::get('/branches', [AdminDashboardController::class, 'branches'])->name('branches');
    Route::get('/branches/{id}', [AdminDashboardController::class, 'getCabang'])->name('branches.show');
    Route::post('/branches', [AdminDashboardController::class, 'storeCabang'])->name('branches.store');
    Route::put('/branches/{id}', [AdminDashboardController::class, 'updateCabang'])->name('branches.update');
    // Route::delete('/branches/{id}', [AdminDashboardController::class, 'deleteCabang'])->name('branches.destroy');

    // Studio Management Routes
    Route::get('/studios', [AdminDashboardController::class, 'studios'])->name('studios');
    Route::get('/studios/cabang/{cabangId}', [AdminDashboardController::class, 'getStudiosByCabang'])->name('studios.by-cabang');
    Route::get('/studios/{id}/seatmap', [AdminDashboardController::class, 'getSeatMap'])->name('studios.seatmap');
    Route::post('/studios', [AdminDashboardController::class, 'storeStudio'])->name('studios.store');
    Route::put('/studios/{id}', [AdminDashboardController::class, 'updateStudio'])->name('studios.update');
    // Route::delete('/studios/{id}', [AdminDashboardController::class, 'deleteStudio'])->name('studios.destroy');

    // Film Management Routes
    Route::get('/films', [AdminDashboardController::class, 'films'])->name('films');
    Route::get('/films/{id}', [AdminDashboardController::class, 'getFilm'])->name('films.show');
    Route::post('/films', [AdminDashboardController::class, 'storeFilm'])->name('films.store');
    Route::put('/films/{id}', [AdminDashboardController::class, 'updateFilm'])->name('films.update');
    // Route::delete('/films/{id}', [AdminDashboardController::class, 'deleteFilm'])->name('films.destroy');

    // Schedule Management Routes
    Route::get('/schedules', [AdminDashboardController::class, 'schedules'])->name('schedules');
    Route::get('/schedules/cabang/{cabangId}', [AdminDashboardController::class, 'getSchedulesByCabang'])->name('schedules.by-cabang');
    Route::post('/schedules', [AdminDashboardController::class, 'storeSchedule'])->name('schedules.store');
    Route::put('/schedules/{id}', [AdminDashboardController::class, 'updateSchedule'])->name('schedules.update');
    // Route::delete('/schedules/{id}', [AdminDashboardController::class, 'deleteSchedule'])->name('schedules.destroy');

    // Reports and Settings
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
