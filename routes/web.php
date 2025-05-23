<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\IdeaLikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PinController;
use App\Http\Controllers\Admin\PinController as AdminPinController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('ideas/')
    ->as('ideas.')
    ->group(function () {
        // Route::get('/{idea}', [IdeaController::class, 'show'])->name('show');

        Route::middleware('auth')->group(function () {
            // Route::get('/{idea}/edit', [IdeaController::class, 'edit'])->name('edit');
            // Route::post('', [IdeaController::class, 'store'])->name('store');
            // Route::put('/{idea}', [IdeaController::class, 'update'])->name('update');
            // Route::delete('/{idea}', [IdeaController::class, 'destroy'])->name('destroy');

            // Route::post('/{idea}/comments', [CommentController::class, 'store'])->name('comments.store');
        });
    });

Route::resource('ideas', IdeaController::class)->except('index','create','show')->middleware('auth');
Route::resource('ideas', IdeaController::class)->only('show');

Route::resource('ideas.comments', CommentController::class)->only('store')->middleware('auth');

Route::resource('users', UserController::class)->only('edit', 'update')->middleware('auth');
Route::resource('users', UserController::class)->only('show');

Route::get('profile', [UserController::class,'profile'])->middleware('auth')->name('profile');

Route::post('users/{user}/follow', [FollowerController::class, 'follow'])->middleware('auth')->name('users.follow');
Route::post('users/{user}/unfollow', [FollowerController::class, 'unfollow'])->middleware('auth')->name('users.unfollow');

Route::post('ideas/{idea}/like', [IdeaLikeController::class, 'like'])->middleware('auth')->name('ideas.like');
Route::post('ideas/{idea}/unlike', [IdeaLikeController::class, 'unlike'])->middleware('auth')->name('ideas.unlike');

Route::get('/feed', FeedController::class)->name('feed')->middleware('auth');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->middleware(['auth'])->can('admin')->name('admin.dashboard');
    Route::get('/add-pin', [AdminPinController::class, 'create'])->middleware(['auth'])->can('admin')->name('admin.pins.create');
    Route::post('/add-pin', [AdminPinController::class, 'store'])->middleware(['auth'])->can('admin')->name('admin.pins.store');
    Route::get('/pins/{pin}/edit', [AdminPinController::class, 'edit'])->middleware(['auth'])->can('admin')->name('admin.pins.edit');
    Route::put('/pins/{pin}', [AdminPinController::class, 'update'])->middleware(['auth'])->can('admin')->name('admin.pins.update');
    Route::delete('/pins/{pin}', [AdminPinController::class, 'destroy'])->middleware(['auth'])->can('admin')->name('admin.pins.destroy');
});
