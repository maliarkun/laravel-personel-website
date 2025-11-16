<?php

use App\Http\Controllers\Account\PasswordController as AccountPasswordController;
use App\Http\Controllers\Account\ProfileController as AccountProfileController;
use App\Http\Controllers\Account\SubscriptionController as AccountSubscriptionController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NoteController as AdminNoteController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\RecycleBinController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectTimelineController;
use App\Http\Controllers\PublicSite\HomeController;
use App\Http\Controllers\PublicSite\ProjectController as PublicProjectController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categories/{category:slug}', [HomeController::class, 'show'])->name('categories.show');
Route::get('/projects/{project:slug}', [PublicProjectController::class, 'show'])->name('projects.show');
Route::get('/timeline', [ProjectTimelineController::class, 'index'])->name('projects.timeline');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/email/verify', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () { // Üyelerin kendi hesaplarını yöneteceği yeni bölüm.
    Route::get('/', [AccountProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [AccountProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [AccountPasswordController::class, 'update'])->name('password.update');
    Route::get('/subscription', [AccountSubscriptionController::class, 'show'])->name('subscription.show');
});

Route::middleware(['auth', 'editorOrAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class)->middleware('can:manage-content');
    Route::resource('projects', AdminProjectController::class)->middleware('can:edit-content');
    Route::resource('notes', AdminNoteController::class)->middleware('can:edit-content');

    Route::get('/recycle-bin', [RecycleBinController::class, 'index'])->middleware('can:manage-content')->name('recycle.index');
    Route::post('/recycle/{type}/{id}/restore', [RecycleBinController::class, 'restore'])->middleware('can:manage-content')->name('recycle.restore');
    Route::delete('/recycle/{type}/{id}', [RecycleBinController::class, 'destroy'])->middleware('can:manage-content')->name('recycle.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class)->only(['index', 'show']); // Yöneticiye gelişmiş kullanıcı yönetimi sunuyoruz.
    Route::put('users/{user}/roles', [AdminUserController::class, 'updateRoles'])->name('users.roles');
    Route::put('users/{user}/plan', [AdminUserController::class, 'updatePlan'])->name('users.plan');
    Route::put('users/{user}/lock', [AdminUserController::class, 'toggleLock'])->name('users.lock');

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::resource('plans', SubscriptionPlanController::class)->except(['show']);
    });
});
