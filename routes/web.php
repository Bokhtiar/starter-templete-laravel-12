<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\HouseOwner\HouseOwnerController;
use App\Http\Controllers\HouseOwner\OwnerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserDashboardController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
});



Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminController::class, 'loginForm'])->name('login');
    Route::get('register', [AdminController::class, 'registerForm'])->name('register');
    Route::post('register', [AdminController::class, 'register']);
    Route::post('login', [AdminController::class, 'login']);
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
    });
});

Route::prefix('owner')->name('owner.')->group(function () {
    Route::get('login', [HouseOwnerController::class, 'showLoginForm'])->name('login');
    Route::get('register', [HouseOwnerController::class, 'registerForm'])->name('register');
    Route::post('register', [HouseOwnerController::class, 'register']);
    Route::post('login', [HouseOwnerController::class, 'login']);
    Route::post('logout', [HouseOwnerController::class, 'logout'])->name('logout');

    Route::middleware('auth:house_owner')->group(function () {
        Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    });
});

require __DIR__.'/auth.php';
