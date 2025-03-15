<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Models\Portfolio;
use App\Helpers\UrlHelper;

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
});

Route::get('/portfolios/search', [PortfolioController::class, 'search'])
    ->name('portfolios.search');

Route::get('/dashboard', [PortfolioController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/portfolio/edit/{id}', [PortfolioController::class, 'edit'])->name('admin.portfolio.edit');
    Route::post('/admin/portfolio/update/{id}', [PortfolioController::class, 'update'])->name('admin.portfolio.update');
    Route::delete('/admin/portfolio/delete/{id}', [PortfolioController::class, 'destroy'])->name('admin.portfolio.delete');
    Route::post('/admin/portfolio/bulk-delete', [PortfolioController::class, 'bulkDelete'])->name('admin.portfolio.bulk.delete');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
//     Route::post('/admin/upload', [AdminController::class, 'bulkUpload'])->name('admin.upload');
//     // Route::match(['get', 'post'], '/admin/upload', [AdminController::class, 'bulkUpload'])->name('admin.upload');
// });
Route::get('/admin/dashboard', [AdminController::class, 'showDashboard'])->name('admin.dashboard');
Route::post('/admin/upload', [AdminController::class, 'uploadCSV'])->name('admin.upload');

Route::middleware('auth')->group(function () {
    Route::get('/admin/portfolio/add', [PortfolioController::class, 'create'])->name('admin.portfolio.add');
    Route::post('/admin/portfolio/add', [PortfolioController::class, 'store'])->name('admin.portfolio.store');
});


require __DIR__.'/auth.php';
