<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\SessionController;
use App\Http\Controllers\PublicEventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $promotions = \App\Models\EventPromotion::with(['event.category', 'event.venue'])
        ->where('status', 'approved')
        ->where('end_date', '>', now())
        ->latest()
        ->take(4)
        ->get();
    return view('home', compact('promotions'));
})->name('home');
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('events.show');

Route::get('/about', [\App\Http\Controllers\StaticPageController::class, 'about'])->name('about');
Route::get('/contact', [\App\Http\Controllers\StaticPageController::class, 'contact'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\StaticPageController::class, 'submitContact'])->name('contact.send');
Route::get('/help', [\App\Http\Controllers\StaticPageController::class, 'help'])->name('help');
Route::get('/blog', [\App\Http\Controllers\StaticPageController::class, 'blog'])->name('blog');

Route::middleware(['auth'])->group(function () {
    Route::get('/verify-2fa', [\App\Http\Controllers\Auth\TwoFactorController::class, 'index'])->name('verify-2fa.index');
    Route::post('/verify-2fa', [\App\Http\Controllers\Auth\TwoFactorController::class, 'verify'])->name('verify-2fa.verify');
    Route::post('/verify-2fa/resend', [\App\Http\Controllers\Auth\TwoFactorController::class, 'resend'])->name('verify-2fa.resend');
});

Route::middleware(['auth', 'two_factor'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return app(\App\Http\Controllers\User\DashboardController::class)->index();
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/apply-organizer', [ProfileController::class, 'applyOrganizer'])->name('profile.apply-organizer');
    
    Route::get('/profile/sessions', [SessionController::class, 'index'])->name('profile.sessions');
    Route::delete('/profile/sessions', [SessionController::class, 'destroy'])->name('profile.sessions.destroy');

    // Waitlist
    Route::get('/waitlist', [\App\Http\Controllers\User\WaitlistController::class, 'index'])->name('waitlist.index');
    Route::post('/events/{event}/waitlist', [\App\Http\Controllers\User\WaitlistController::class, 'store'])->name('waitlist.store');


    // Copyright Reports
    Route::post('/events/{event}/report', [\App\Http\Controllers\User\CopyrightReportController::class, 'store'])->name('events.report.store');

    // Ticketing (User)
    Route::get('/events/{event}/book', [\App\Http\Controllers\User\BookingController::class, 'create'])->name('events.book');
    Route::post('/events/{event}/book', [\App\Http\Controllers\User\BookingController::class, 'store'])->name('events.book.store');
    Route::post('/events/{event}/validate-coupon', [\App\Http\Controllers\User\BookingController::class, 'validateCoupon'])->name('events.validate-coupon');
    Route::get('/my-tickets', [\App\Http\Controllers\User\BookingController::class, 'index'])->name('user.tickets.index');
    Route::get('/my-tickets/{reference}', [\App\Http\Controllers\User\BookingController::class, 'show'])->name('user.tickets.show');
    Route::post('/my-tickets/{reference}/transfer', [\App\Http\Controllers\User\BookingController::class, 'transfer'])->name('user.tickets.transfer');
    Route::get('/my-tickets/{ticket}/download', [\App\Http\Controllers\User\TicketDownloadController::class, 'download'])->name('user.tickets.download');
    
    // Payments
    Route::get('/payments/checkout/{payment}', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/payments/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('payments.process');

    // Language Switch
    Route::get('/lang/{lang}', [\App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');

    require __DIR__.'/organizer.php';
    require __DIR__.'/admin.php';
});

require __DIR__.'/auth.php';
