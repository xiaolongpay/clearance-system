<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Registrar\ClearanceStatusController;
use App\Http\Controllers\Staff\ClearanceController;
use App\Http\Controllers\Student\ClearancePdfController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Techlab\AuditLogController;
use App\Http\Controllers\Techlab\DepartmentController;
use App\Http\Controllers\Techlab\SchoolYearController;
use App\Http\Controllers\Techlab\StudentController;
use App\Http\Controllers\Techlab\StudentImportController;
use Illuminate\Support\Facades\Route;

// Home: send everyone straight to the login page
Route::redirect('/', '/login');

// Login (shared page for students and staff)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Privacy notice (public, linked from the login page)
Route::view('/privacy', 'privacy')->name('privacy');

// Staff dashboard
Route::middleware('auth:web')->get('/staff/dashboard', function () {
    $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();

    return view('staff.dashboard-placeholder', [
        'activeSchoolYear' => $activeSchoolYear,
    ]);
})->name('staff.dashboard');

// Student dashboard
Route::middleware('auth:student')->group(function () {
    Route::get('/student/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/clearance-pdf', [ClearancePdfController::class, 'download'])->name('student.clearance-pdf');
});

// Account settings (shared by both students and staff)
Route::middleware('auth:web,student')->group(function () {
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account', [AccountController::class, 'update'])->name('account.update');
});

// Staff clearance management (all departments, including Techlab)
Route::middleware('auth:web')->prefix('staff')->name('staff.')->group(function () {
    Route::get('clearance', [ClearanceController::class, 'index'])->name('clearance.index');
    Route::post('clearance/{clearanceItem}/clear', [ClearanceController::class, 'markCleared'])->name('clearance.clear');
    Route::post('clearance/{clearanceItem}/reject', [ClearanceController::class, 'reject'])->name('clearance.reject');
    Route::post('clearance/{clearanceItem}/revert', [ClearanceController::class, 'revert'])->name('clearance.revert');
});

// Registrar-only view
Route::middleware(['auth:web', 'registrar'])->prefix('registrar')->name('registrar.')->group(function () {
    Route::get('clearance-status', [ClearanceStatusController::class, 'index'])->name('clearance-status');
});

// Techlab-only management
Route::middleware(['auth:web', 'techlab'])->prefix('techlab')->name('techlab.')->group(function () {
    Route::resource('departments', DepartmentController::class);
    Route::post('departments/{department}/reset-password', [DepartmentController::class, 'resetPassword'])->name('departments.reset-password');

    Route::get('students/import', [StudentImportController::class, 'create'])->name('students.import.create');
    Route::post('students/import', [StudentImportController::class, 'store'])->name('students.import.store');

    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::post('students/{student}/reset-password', [StudentController::class, 'resetPassword'])->name('students.reset-password');
    Route::delete('students/bulk-delete', [StudentController::class, 'bulkDelete'])->name('students.bulk-delete');

    Route::get('school-years', [SchoolYearController::class, 'index'])->name('school-years.index');
    Route::post('school-years', [SchoolYearController::class, 'store'])->name('school-years.store');
    Route::post('school-years/{schoolYear}/activate', [SchoolYearController::class, 'activate'])->name('school-years.activate');
    Route::delete('school-years/{schoolYear}', [SchoolYearController::class, 'destroy'])->name('school-years.destroy');

    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});

// Forced password change reuses the same account settings page
Route::get('/password/force-change', function () {
    return redirect()->route('account.edit');
})->name('password.force-change');
