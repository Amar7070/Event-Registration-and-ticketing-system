<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrganizerApprovalController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PromotionPlanController;
use App\Http\Controllers\Admin\PromotionController;

Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('coupons', CouponController::class)->only(['index', 'update', 'destroy']);
    Route::resource('promotion-plans', PromotionPlanController::class)->except(['create', 'edit', 'show']);
    Route::get('promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::post('promotions/{promotion}/approve', [PromotionController::class, 'approve'])->name('promotions.approve');
    Route::post('promotions/{promotion}/reject', [PromotionController::class, 'reject'])->name('promotions.reject');
    Route::post('promotions/events/{event}/add', [PromotionController::class, 'addToSlideshow'])->name('promotions.add');
    Route::post('promotions/events/{event}/remove', [PromotionController::class, 'removeFromSlideshow'])->name('promotions.remove');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/metrics', [DashboardController::class, 'getMetrics'])->name('metrics');
    Route::get('/events', [DashboardController::class, 'events'])->name('events.index');
    Route::post('/events/{event}/restrict', [DashboardController::class, 'restrict'])->name('events.restrict');
    Route::get('/copyright-reports', [DashboardController::class, 'copyrightReports'])->name('copyright-reports.index');
    Route::post('/copyright-reports/{report}/resolve', [DashboardController::class, 'resolveReport'])->name('copyright-reports.resolve');
    Route::get('/revenue', [DashboardController::class, 'revenue'])->name('revenue.index');

    Route::resource('users', UserManagementController::class)->only(['index', 'show', 'update']);
    Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);

    Route::get('organizers/pending', [OrganizerApprovalController::class, 'index'])->name('organizers.pending');
    Route::post('organizers/{organizer}/approve', [OrganizerApprovalController::class, 'approve'])->name('organizers.approve');
    Route::post('organizers/{organizer}/reject', [OrganizerApprovalController::class, 'reject'])->name('organizers.reject');

    Route::get('organizers', [\App\Http\Controllers\Admin\OrganizerManagementController::class, 'index'])->name('organizers.index');
    Route::get('organizers/{organizer}', [\App\Http\Controllers\Admin\OrganizerManagementController::class, 'show'])->name('organizers.show');
    Route::post('organizers/{organizer}/toggle-status', [\App\Http\Controllers\Admin\OrganizerManagementController::class, 'toggleStatus'])->name('organizers.toggle-status');
});
