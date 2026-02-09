<?php

use App\Http\Controllers\Admin\MeterController;
use App\Http\Controllers\Admin\MeterReadingController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\TenancyController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resources([
        // 'dashboard' => DashboardController::class,
        'properties' => PropertyController::class,
        'units' => UnitController::class,
        'tenants' => TenantController::class,
        'tenancies' => TenancyController::class,
        'meters' => MeterController::class,
        'meter-readings' => MeterReadingController::class,
        'bills' => BillController::class,
        'payments' => PaymentController::class,
        'users' => UsersController::class
    ], ['only' => ['index', 'store', 'edit', 'update', 'destroy']]);
});

Route::get('bills/{bill}/invoice', [BillController::class, 'invoice'])
    ->name('bills.invoice');


Route::get('/admin/bills/generate', [BillController::class, 'showGenerateForm'])->name('bills.generate');
Route::post('/admin/bills/generate', [BillController::class, 'runGenerate'])->name('bills.generate.run');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

require __DIR__ . '/auth.php';
