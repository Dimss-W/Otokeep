<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerView'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [VehicleController::class, 'dashboard'])->name('dashboard');
    Route::get('/vehicle/register', [VehicleController::class, 'registerView'])->name('vehicle.register');
    Route::post('/vehicle/register', [VehicleController::class, 'register']);
    Route::post('/odometer/update', [VehicleController::class, 'updateOdometer'])->name('odometer.update');
    Route::post('/odometer/scan', [VehicleController::class, 'scanOdometerAi'])->name('odometer.scan');
    Route::post('/service/done/{id}', [ServiceController::class, 'markDone'])->name('service.done');
    Route::post('/service/add', [ServiceController::class, 'add'])->name('service.add');
    
    // New Routes for Information Articles
    Route::get('/recommendations', [VehicleController::class, 'recommendations'])->name('user.recommendations');
    Route::get('/recommendations/{id}', [VehicleController::class, 'recommendationDetail'])->name('user.recommendations.show');
    
    // History Routes
    Route::get('/history', [VehicleController::class, 'history'])->name('user.history');
    Route::get('/history/service-book', [VehicleController::class, 'exportServiceBookPdf'])->name('history.service-book');
    Route::post('/history/record', [VehicleController::class, 'recordHistory'])->name('history.record');
    Route::delete('/history/{id}', [VehicleController::class, 'deleteHistory'])->name('history.delete');

    // Vehicle Switching & Tax Dates
    Route::post('/vehicle/switch/{id}', [VehicleController::class, 'switchVehicle'])->name('vehicle.switch');
    Route::post('/vehicle/tax', [VehicleController::class, 'updateTaxDates'])->name('vehicle.tax.update');

    // AI Chat Routes
    Route::get('/chat', [ChatController::class, 'index'])->name('user.chat');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('user.chat.send');
    Route::post('/chat/clear', [ChatController::class, 'clearHistory'])->name('user.chat.clear');

    // Settings & Realtime Silent Sync
    Route::get('/dashboard/sync', [VehicleController::class, 'syncRealtime'])->name('dashboard.sync');
    Route::post('/settings/notification', [VehicleController::class, 'updateNotificationTime'])->name('notification.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/dashboard/realtime', [AdminController::class, 'realtimeData'])->name('admin.dashboard.realtime');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/admin/categories', [AdminController::class, 'addCategory']);
    Route::put('/admin/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{id}', [AdminController::class, 'deleteCategory']);
    
    Route::get('/admin/recommendations', [AdminController::class, 'recommendations'])->name('admin.recommendations');
    Route::post('/admin/recommendations', [AdminController::class, 'addRecommendation']);
    Route::delete('/admin/recommendations/{id}', [AdminController::class, 'deleteRecommendation']);
});
