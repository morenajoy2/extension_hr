<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    EmployeeController,
    GroupController,
    ProfileController,
    RequirementController,
    RoleController,
    DepartmentController,
    DepartmentTeamController,
    StrandController,
    EmploymentTypeController,
    PositionController
};

// Root redirects to login
Route::get('/', function () {
    return view('auth.login');
});

// Dashboard based on role
Route::middleware(['auth', 'verified'])->get('/dashboard', [DashboardController::class, 'redirectByRole'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // PROFILE
    Route::get('/profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.view');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    // EMPLOYEES (201)
    Route::get('/201', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/201/{user}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/201/{user}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/201/{user}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/201/{user}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::post('/201/{id}/status', [EmployeeController::class, 'updateStatus'])->name('employees.updateStatus');
    Route::get('/201/search/autocomplete', [EmployeeController::class, 'autocomplete'])->name('employees.autocomplete');
    Route::post('/201/{id}/update-requirement-status', [EmployeeController::class, 'updateRequirementStatus'])->name('employees.updateRequirementStatus');

    // REQUIREMENTS
    Route::post('/201/store', [RequirementController::class, 'store'])->name('requirements.store');
    Route::post('/201/upload-modal', [RequirementController::class, 'uploadModal'])->name('requirements.upload.modal');
    Route::delete('/201/{requirement}/delete', [RequirementController::class, 'delete'])->name('requirements.delete');
    Route::post('/201/{requirement}/signed', [RequirementController::class, 'uploadSignedFile'])->name('requirements.signed.upload');
    Route::get('/requirements', [RequirementController::class, 'myRequirements'])->name('my.requirements');
    Route::get('/requirements/{requirement}/exit-clearance/files', [RequirementController::class, 'getExitClearanceFiles']);
    Route::get('/requirements/{requirement}/weekly-report/files', [RequirementController::class, 'getWeeklyReportFiles']);


    // ROLES
    Route::post('/switch-role', [RoleController::class, 'switch'])->name('switch.role');
    Route::get('/roles/manage', [RoleController::class, 'manage'])->name('roles.manage');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // GROUPS
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::delete('/groups/{id}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::post('/switch-group', [GroupController::class, 'switch'])->name('switch.group');

    // DEPARTMENTS
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // EMPLOYMENT TYPES
    Route::get('/employment-types', [EmploymentTypeController::class, 'index'])->name('employment-types.index');
    Route::post('/employment-types', [EmploymentTypeController::class, 'store'])->name('employment-types.store');
    Route::put('/employment-types/{employmentType}', [EmploymentTypeController::class, 'update'])->name('employment-types.update');
    Route::delete('/employment-types/{employmentType}', [EmploymentTypeController::class, 'destroy'])->name('employment-types.destroy');

    // DEPARTMENT TEAMS
    Route::get('/department-teams', [DepartmentTeamController::class, 'index'])->name('department-teams.index');
    Route::post('/department-teams', [DepartmentTeamController::class, 'store'])->name('department-teams.store');
    Route::put('/department-teams/{departmentTeam}', [DepartmentTeamController::class, 'update'])->name('department-teams.update');
    Route::delete('/department-teams/{departmentTeam}', [DepartmentTeamController::class, 'destroy'])->name('department-teams.destroy');

    // STRANDS
    Route::get('/strands', [StrandController::class, 'index'])->name('strands.index');
    Route::post('/strands', [StrandController::class, 'store'])->name('strands.store');
    Route::put('/strands/{strand}', [StrandController::class, 'update'])->name('strands.update');
    Route::delete('/strands/{strand}', [StrandController::class, 'destroy'])->name('strands.destroy');

    //POSITIONS
    Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
    Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
    Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');

    Route::get('/requirements/{id}/details', [RequirementController::class, 'showRequirementDetails']);

});

// Auth routes (login, register, etc.)
require __DIR__.'/auth.php';
