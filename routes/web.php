<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuditLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//
// The middleware applies only to 1 route
//
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//
// The middleware applies to every route inside the group.
//
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        //
        // Users
        //
        Route::resource('users', UserController::class)
            ->except(['show', 'create', 'store']);

        Route::post('users/{id}/restore', [UserController::class, 'restore'])
            ->name('users.restore');
        //

        //
        // Audit logs
        //
        Route::get('audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');

        Route::get('/audit-logs/export', [AuditLogController::class, 'export'])
            ->name('audit-logs.export');

        Route::get('/audit-logs/export-excel', function (\Illuminate\Http\Request $request) {
                return new \App\Exports\AuditLogsExport($request);
            })
            ->name('audit-logs.export-excel');
        //
    });

require __DIR__.'/auth.php';


























//
// The middleware applies to every route inside the group.
//
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin', [AdminDashboardController::class, 'index'])
//         ->name('admin.dashboard');
// });
