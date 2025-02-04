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

// Route::get('/portfolios/search', [PortfolioController::class, 'search'])->name('portfolios.search');
// Route::get('/', [PortfolioController::class, 'search']);
Route::get('/portfolios/search', [PortfolioController::class, 'search']);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     $portfolios = App\Models\Portfolio::all(); // Fetch all portfolios from the database

//     // Instantiate the controller to call the method
//     $portfolioController = new \App\Http\Controllers\PortfolioController();

//     // Check if URLs are active or inactive
//     foreach ($portfolios as $portfolio) {
//         $portfolio->status = $portfolioController->isLinkActive($portfolio->url) ? 'Active' : 'Inactive';
//     }

//     return view('dashboard', compact('portfolios')); // Pass portfolios to the view
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    $portfolios = Portfolio::all(); // Fetch all portfolios from the database
    $urls = $portfolios->pluck('url')->toArray(); // Collect URLs from the portfolios

    // Batch check URLs using UrlHelper
    $statuses = UrlHelper::checkUrls($urls);

    // Map statuses back to portfolios
    foreach ($portfolios as $portfolio) {
        $portfolio->status = $statuses[$portfolio->url] ? 'Active' : 'Inactive';
    }

    return view('dashboard', compact('portfolios')); // Pass portfolios to the view
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/portfolio/edit/{id}', [PortfolioController::class, 'edit'])->name('admin.portfolio.edit');
    Route::post('/admin/portfolio/update/{id}', [PortfolioController::class, 'update'])->name('admin.portfolio.update');
    Route::delete('/admin/portfolio/delete/{id}', [PortfolioController::class, 'destroy'])->name('admin.portfolio.delete');
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
