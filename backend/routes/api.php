<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\OrganizerController;
use App\Http\Controllers\Api\EventBookingController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\OrganizerEventController;
use App\Http\Controllers\Api\AdminController;

// Health check for Railway deployment monitoring
Route::get('/health', fn() => response()->json([
    'status' => 'ok',
    'app'    => config('app.name'),
    'env'    => config('app.env'),
]));

Route::middleware(['throttle:api'])->group(function () {
    // Public Routes
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{slug}', [EventController::class, 'show']);
    Route::post('/contact', function (Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);
        return response()->json(['message' => 'Thank you for contacting us. We will respond within one business day.']);
    });

    Route::prefix('v1')->name('api.v1.')->group(function () {
        Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
        
        Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
        Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

        Route::get('/home', [EventController::class, 'home']);
Route::post('/contact', [ContactController::class, 'store']);
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
        Route::get('/events/{event}/reviews', [App\Http\Controllers\Api\ReviewController::class, 'index']);
        Route::get('/categories', [AdminController::class, 'categoriesPublic'])->name('categories.public');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user', function (Request $request) {
                $user = $request->user();
                return response()->json([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->pluck('name')->first() ?? 'user',
                    'is_approved' => $user->is_approved,
                    'two_factor_enabled' => $user->two_factor_enabled,
                ]);
            })->name('user');

            Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

            // Attendee Dashboard & Booking (Strictly for standard users)
            Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index'])->name('dashboard');
            
            // Attendee Booking & Interaction
            Route::post('/events/{event}/book', [EventBookingController::class, 'book']);
            Route::post('/events/{event}/validate-coupon', [EventBookingController::class, 'validateCoupon']);
            Route::post('/events/{event}/waitlist', [EventBookingController::class, 'joinWaitlist']);
            
            Route::get('/my-tickets', [TicketController::class, 'index'])->name('tickets.index');
            Route::post('/payments/process', [\App\Http\Controllers\PaymentController::class, 'process']);
            Route::get('/my-tickets/{reference}', [EventBookingController::class, 'showTicket']);
            Route::post('/my-tickets/{reference}/transfer', [EventBookingController::class, 'transferTicket']);
            Route::get('/my-tickets/{ticket}/download', [EventBookingController::class, 'downloadTicket']);
            Route::get('/my-waitlists', [TicketController::class, 'waitlists'])->name('waitlists.index');
            
            Route::post('/events/{event}/reviews', [App\Http\Controllers\Api\ReviewController::class, 'store']);
            Route::post('/events/{event}/claim', [App\Http\Controllers\Api\CopyrightReportController::class, 'store']);

            // Profile Governance
            Route::get('/profile', [UserProfileController::class, 'show']);
            Route::put('/profile', [UserProfileController::class, 'update']);
            Route::delete('/profile', [UserProfileController::class, 'destroy']);
            Route::put('/profile/password', [UserProfileController::class, 'updatePassword']);
            Route::post('/profile/apply-organizer', [UserProfileController::class, 'applyOrganizer']);
            Route::get('/profile/sessions', [UserProfileController::class, 'sessions']);
            Route::delete('/profile/sessions', [UserProfileController::class, 'destroySessions']);

            // Organizer Management
            Route::middleware(['role:organizer|admin', \App\Http\Middleware\EnsureOrganizerIsApproved::class])->prefix('organizer')->group(function () {
                Route::get('/stats', [OrganizerController::class, 'stats'])->name('stats');
                Route::get('/venues', function() { return response()->json(['venues' => \App\Models\Venue::all()]); });
                
                Route::get('/events', [OrganizerEventController::class, 'index']);
                Route::post('/events', [OrganizerEventController::class, 'store']);
                Route::get('/events/{event}', [OrganizerEventController::class, 'show']);
                Route::put('/events/{event}', [OrganizerEventController::class, 'update']);
                Route::delete('/events/{event}', [OrganizerEventController::class, 'destroy']);
                Route::post('/events/{event}/publish', [OrganizerEventController::class, 'publish']);
                Route::post('/events/{event}/clone', [OrganizerEventController::class, 'clone']);
                Route::post('/events/{event}/cancel', [OrganizerEventController::class, 'cancel']);
                Route::get('/events/{event}/promote', [OrganizerEventController::class, 'promoteData']);
                Route::post('/events/{event}/promote', [OrganizerEventController::class, 'promote']);
                
                // Organizer Tickets & Coupons
                Route::get('/events/{event}/tickets', [OrganizerEventController::class, 'ticketsIndex']);
                Route::post('/events/{event}/tickets', [OrganizerEventController::class, 'ticketsStore']);
                Route::put('/events/{event}/tickets/{ticketType}', [OrganizerEventController::class, 'ticketsUpdate']);
                Route::delete('/events/{event}/tickets/{ticketType}', [OrganizerEventController::class, 'ticketsDestroy']);
                
                Route::get('/events/{event}/coupons', [OrganizerEventController::class, 'couponsIndex']);
                Route::post('/events/{event}/coupons', [OrganizerEventController::class, 'couponsStore']);
                Route::put('/events/{event}/coupons/{coupon}', [OrganizerEventController::class, 'couponsUpdate']);
                Route::delete('/events/{event}/coupons/{coupon}', [OrganizerEventController::class, 'couponsDestroy']);

                // Global Organizer Routes
                Route::get('/coupons', [OrganizerController::class, 'couponsIndex']);
                Route::post('/coupons', [OrganizerController::class, 'couponsStore']);
                Route::put('/coupons/{coupon}', [OrganizerController::class, 'couponsUpdate']);
                Route::delete('/coupons/{coupon}', [OrganizerController::class, 'couponsDestroy']);
                Route::get('/copyright', [OrganizerController::class, 'copyrightReports']);
                Route::get('/attendees', [OrganizerController::class, 'globalAttendees']);
                Route::get('/analytics', [OrganizerEventController::class, 'analyticsGlobal']);
                Route::get('/reviews', [OrganizerEventController::class, 'reviewsIndex']);
                Route::post('/reviews/{review}/reply', [OrganizerEventController::class, 'replyToReview']);
                Route::delete('/reviews/{review}', [OrganizerEventController::class, 'reviewsDestroy']);
                Route::get('/events/{event}/analytics', [OrganizerEventController::class, 'analytics']);
                Route::get('/events/{event}/attendees', [OrganizerController::class, 'attendees'])->name('events.attendees');
                Route::post('/events/{event}/scan', [OrganizerEventController::class, 'scan']);
            });

            // Admin Control Panel
            Route::middleware(['role:admin'])->prefix('admin')->group(function () {
                Route::get('/dashboard', [AdminController::class, 'dashboard']);
                Route::get('/users', [AdminController::class, 'users']);
                Route::put('/users/{user}', [AdminController::class, 'updateUser']);
                
                Route::get('/categories', [AdminController::class, 'categories']);
                Route::post('/categories', [AdminController::class, 'storeCategory']);
                Route::put('/categories/{category}', [AdminController::class, 'updateCategory']);
                Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory']);

                Route::get('/events', [AdminController::class, 'events']);
                Route::post('/events/{event}/restrict', [AdminController::class, 'restrictEvent']);
                
                Route::get('/organizers', [AdminController::class, 'allOrganizers']);
                Route::get('/organizers/pending', [AdminController::class, 'pendingOrganizers']);
                Route::get('/organizers/{organizer}', [AdminController::class, 'showOrganizer']);
                Route::post('/organizers/{organizer}/approve', [AdminController::class, 'approveOrganizer']);
                Route::post('/organizers/{organizer}/reject', [AdminController::class, 'rejectOrganizer']);
                Route::post('/organizers/{organizer}/toggle-status', [AdminController::class, 'toggleOrganizerStatus']);
                
                Route::get('/coupons', [AdminController::class, 'allCoupons']);
                Route::put('/coupons/{coupon}', [AdminController::class, 'updateCoupon']);
                Route::delete('/coupons/{coupon}', [AdminController::class, 'destroyCoupon']);

                Route::get('/promotions', [AdminController::class, 'promotionsIndex']);
                Route::post('/promotions/{promotion}/approve', [AdminController::class, 'approvePromotion']);
                Route::post('/promotions/{promotion}/reject', [AdminController::class, 'rejectPromotion']);
                Route::post('/promotions/events/{event}/add', [AdminController::class, 'addToSlideshow']);
                Route::post('/promotions/events/{event}/remove', [AdminController::class, 'removeFromSlideshow']);
                Route::post('/promotion-plans', [AdminController::class, 'storePromotionPlan']);
                Route::put('/promotion-plans/{plan}', [AdminController::class, 'updatePromotionPlan']);
                Route::delete('/promotion-plans/{plan}', [AdminController::class, 'destroyPromotionPlan']);
                Route::get('/revenue', [AdminController::class, 'revenue']);
                
                Route::get('/reviews', [AdminController::class, 'reviews']);
                Route::delete('/reviews/{review}', [AdminController::class, 'destroyReview']);
                Route::get('/contacts', [AdminController::class, 'contacts']);
                Route::put('/contacts/{contact}/status', [AdminController::class, 'updateContactStatus']);
                Route::post('/contacts/{contact}/reply', [AdminController::class, 'replyContact']);
                
                Route::get('/claims', [App\Http\Controllers\Api\CopyrightReportController::class, 'index']);
                Route::put('/claims/{report}', [App\Http\Controllers\Api\CopyrightReportController::class, 'update']);
            });
        });
    });
});

// Webhooks
Route::post('/webhooks/stripe', [PaymentWebhookController::class, 'handleStripe']);
Route::post('/webhooks/razorpay', [PaymentWebhookController::class, 'handleRazorpay']);