<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Organizer\QRScannerController;
use App\Http\Controllers\Organizer\TicketController;
use App\Http\Controllers\Organizer\CouponController;
use App\Http\Controllers\Organizer\AnalyticsController;
use App\Http\Controllers\Organizer\AttendeeController;
Route::middleware(['role:organizer|admin', 'organizer.approved'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('organizer.events.index');
    })->name('dashboard');

    Route::resource('events', OrganizerEventController::class);
    Route::post('events/{event}/publish', [OrganizerEventController::class, 'publish'])->name('events.publish');
    Route::post('events/{event}/clone', [OrganizerEventController::class, 'clone'])->name('events.clone');
    Route::post('events/{event}/cancel', [OrganizerEventController::class, 'cancel'])->name('events.cancel');
    Route::get('events/{event}/promote', [OrganizerEventController::class, 'showPromoteForm'])->name('events.promote');
    Route::post('events/{event}/promote', [OrganizerEventController::class, 'submitPromotion'])->name('events.promote.submit');

    Route::resource('events.tickets', TicketController::class)->except(['show']);
    Route::resource('events.coupons', CouponController::class)->except(['show']);

    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('events/{event}/analytics', [AnalyticsController::class, 'show'])->name('events.analytics');
    Route::get('events/{event}/stats', [AnalyticsController::class, 'getStats'])->name('events.stats');

    Route::get('attendees', [AttendeeController::class, 'globalIndex'])->name('attendees.index');
    Route::get('events/{event}/attendees', [AttendeeController::class, 'index'])->name('events.attendees');

    Route::get('events/{event}/scanner', [QRScannerController::class, 'show'])->name('events.scanner');
    Route::post('events/{event}/scan', [QRScannerController::class, 'scan'])->name('events.scan');
});
