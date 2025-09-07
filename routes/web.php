<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FulltimeTimesheetController;
use App\Http\Controllers\ParttimeTimesheetController;
use App\Http\Controllers\StaffTimesheetController;
use App\Http\Controllers\UtilityTimesheetController;
use App\Http\Controllers\BsitController;
use App\Http\Controllers\BsbaController;
use App\Http\Controllers\BshmController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;


Route::get('/timesheets', [FulltimeTimesheetController::class, 'index'])->name('timesheets.index');
Route::post('/timesheets', [FulltimeTimesheetController::class, 'store'])->name('timesheets.store');



Route::get('/', function () {
    return view('index');
});

Route::post('/index', function () {
    return 'hello';
});

Route::get('/index', function () {
    return view('/index');
})->name('index');

// Handle login (POST)
Route::post('/index', [LoginController::class, 'authenticate']);

// Admin login routes
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login.form');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login')->middleware('throttle:3,1');

// Admin dashboard route
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Salary Adjustment route
Route::get('/salary-adjustment', [AdminController::class, 'salaryAdjustment'])->name('salary.adjustment');

// Attendance login routes
Route::get('/attendance/login', function () {
    return view('attendance.login');
})->name('attendance.login.form');
Route::post('/attendance/login', [AttendanceController::class, 'login'])->name('attendance.login')->middleware('throttle:3,1');
Route::get('/attendance/dashboard', [AttendanceController::class, 'dashboard'])->name('attendance.dashboard');
Route::get('/api/course-counts', [AttendanceController::class, 'getCourseCounts']);
Route::get('/api/attendance-data/{course}', [AttendanceController::class, 'getAttendanceData']);
Route::post('/api/save-attendance', [AttendanceController::class, 'saveAttendance']);
Route::post('/api/bulk-delete-attendance', [AttendanceController::class, 'bulkDeleteAttendance']);

Route::get('/register', function () {
    return view('auth.register');
});

Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1');

Route::get('/logout', function () {
    // Clear all session data
    session()->flush();
    return redirect('/')->with('success', 'You have been logged out successfully.');
});


use App\Http\Controllers\DashboardController;
Route::get('/dashboard', function () {
    if (!session()->has('user_id')) {
        return redirect('/')->with('error', 'Please login first.');
    }
    
    // Check if user is admin and redirect accordingly
    if (session()->get('user_role') === 'admin' || session()->get('is_admin')) {
        return app(AdminController::class)->dashboard(request());
    }
    
    return app(DashboardController::class)->index(request());
})->name('dashboard');

Route::get('/search', [DashboardController::class, 'search'])->name('search');
Route::get('/api/employee-stats', [DashboardController::class, 'getEmployeeStats'])->name('employee.stats');
Route::get('/api/employee-stats/detailed', [DashboardController::class, 'getDetailedEmployeeStats'])->name('employee.stats.detailed');
Route::get('/api/instructors-by-rate', [DashboardController::class, 'getInstructorsByRate'])->name('instructors.by.rate');

// Master List Routes
Route::get('/master-list', [DashboardController::class, 'masterList'])->name('master.list');
Route::get('/master-list/add', [DashboardController::class, 'masterListAddForm'])->name('master.list.add');
Route::post('/master-list/add', [DashboardController::class, 'masterListAddStore'])->name('master.list.add.store');
Route::post('/master-list/delete-selected', [DashboardController::class, 'deleteSelected'])->name('master.list.delete');
Route::post('/master-list/print-data', [DashboardController::class, 'getPrintData'])->name('master.list.print');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');


Route::middleware(['auth'])->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
});



Route::resource('fulltime', FulltimeTimesheetController::class);
Route::get('/fulltime/print/all', [FulltimeTimesheetController::class, 'printAll'])->name('fulltime.print');
Route::post('/fulltime/{id}/update-day', [FulltimeTimesheetController::class, 'updateDay'])->name('fulltime.update.day');
Route::post('/fulltime/{id}/update-field', [FulltimeTimesheetController::class, 'updateField'])->name('fulltime.update.field');

Route::resource('parttime', ParttimeTimesheetController::class);
Route::get('/parttime/print/all', [ParttimeTimesheetController::class, 'printAll'])->name('parttime.print');

Route::resource('staff', StaffTimesheetController::class);
Route::get('/staff/print/all', [StaffTimesheetController::class, 'printAll'])->name('staff.print');

Route::resource('utility', UtilityTimesheetController::class);
Route::get('/utility/print/all', [UtilityTimesheetController::class, 'printAll'])->name('utility.print');

// Department Routes
Route::resource('departments', DepartmentController::class);



// Department Attendance Checker Routes
Route::get('/bsit', [BsitController::class, 'index'])->name('bsit.index');
Route::get('/bsba', [BsbaController::class, 'index'])->name('bsba.index');
Route::get('/bshm', [BshmController::class, 'index'])->name('bshm.index');
Route::get('/education', [EducationController::class, 'index'])->name('education.index');
