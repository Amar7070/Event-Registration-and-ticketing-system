<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\CopyrightReport;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\EventPromotion;
use App\Models\EventPromotionPlan;
use App\Services\AnalyticsService;
use App\Mail\ContactReplyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function dashboard()
    {
        $metrics = $this->analyticsService->getPlatformMetrics();
        return response()->json($metrics);
    }

    public function users()
    {
        $users = User::with('roles')->latest()->paginate(15)->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'is_active' => $u->is_active ?? true,
                'is_approved' => $u->is_approved,
                'role' => $u->roles->pluck('name')->first() ?? 'user',
                'created_at' => $u->created_at->toIso8601String(),
            ];
        });
        return response()->json(['data' => $users]);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'is_active' => 'sometimes|required|boolean',
            'role' => 'sometimes|required|string|in:admin,organizer,attendee',
        ]);

        if ($request->has('is_active')) {
            $user->update(['is_active' => $request->is_active]);
            activity()->causedBy(auth()->user())->performedOn($user)->log('updated user active state to: ' . ($request->is_active ? 'active' : 'inactive'));
        }

        if ($request->has('role')) {
            if ($user->id === auth()->id() && $request->role !== 'admin') {
                return response()->json(['message' => 'You cannot revoke your own Administrator privileges.'], 400);
            }

            $oldRole = $user->roles->pluck('name')->first() ?? 'user';
            $user->syncRoles([$request->role]);

            if ($request->role === 'organizer') {
                $user->is_approved = true;
            } else {
                $user->is_approved = false;
            }
            $user->save();

            activity()->causedBy(auth()->user())->performedOn($user)->log("changed role from {$oldRole} to {$request->role}");
        }

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active ?? true,
                'is_approved' => $user->is_approved,
                'role' => $user->roles->pluck('name')->first() ?? 'user',
                'created_at' => $user->created_at->toIso8601String(),
            ]
        ]);
    }

    public function categoriesPublic()
    {
        $categories = EventCategory::where('is_active', true)->get();
        return response()->json(['categories' => $categories]);
    }

    public function categories()
    {
        $categories = EventCategory::latest()->get();
        return response()->json(['categories' => $categories]);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = EventCategory::create($validated);

        return response()->json([
            'message' => 'Category created successfully.',
            'category' => $category
        ], 201);
    }

    public function updateCategory(Request $request, EventCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return response()->json([
            'message' => 'Category updated successfully.',
            'category' => $category
        ]);
    }

    public function destroyCategory(EventCategory $category)
    {
        $eventCount = Event::where('category_id', $category->id)->count();
        if ($eventCount > 0) {
            return response()->json(['message' => 'Cannot delete category containing active events. Re-assign them first.'], 400);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.'
        ]);
    }

    public function events()
    {
        $events = Event::with(['organizer', 'category'])->latest()->paginate(15);
        return response()->json(['events' => $events]);
    }

    public function restrictEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'restriction_reason' => 'nullable|string|max:1000',
        ]);

        $isRestricted = !$event->is_restricted;

        $event->update([
            'is_restricted' => $isRestricted,
            'restriction_reason' => $isRestricted ? ($validated['restriction_reason'] ?? 'Violation of Copyright / Illegal Content Terms') : null,
        ]);

        activity()->causedBy(auth()->user())->performedOn($event)->log($isRestricted ? 'restricted event' : 'lifted event restriction');

        return response()->json([
            'message' => $isRestricted 
                ? 'Event has been restricted and hidden from public platforms.' 
                : 'Event restriction has been lifted successfully.',
            'is_restricted' => $isRestricted
        ]);
    }

    public function pendingOrganizers()
    {
        $organizers = User::role('organizer')
            ->where('is_approved', false)
            ->latest()
            ->paginate(15);

        return response()->json(['organizers' => $organizers]);
    }

    public function approveOrganizer(User $organizer)
    {
        if (!$organizer->hasRole('organizer')) {
            return response()->json(['message' => 'User does not have organizer role.'], 400);
        }

        $organizer->is_approved = true;
        $organizer->save();

        try {
            \Mail::to($organizer->email)->send(new \App\Mail\OrganizerApprovalMail($organizer));
        } catch (\Throwable $e) {
            // ignore
        }

        activity()->causedBy(auth()->user())->performedOn($organizer)->log('approved organizer account');

        return response()->json([
            'message' => 'Organizer approved successfully.'
        ]);
    }

    public function rejectOrganizer(Request $request, User $organizer)
    {
        $request->validate(['reason' => 'required|string']);

        if (!$organizer->hasRole('organizer')) {
            return response()->json(['message' => 'User does not have organizer role.'], 400);
        }

        $organizer->syncRoles(['attendee']); // strip host role
        $organizer->is_approved = false;
        $organizer->save();

        try {
            \Mail::to($organizer->email)->send(new \App\Mail\OrganizerRejectionMail($organizer, $request->reason));
        } catch (\Throwable $e) {
            // ignore
        }

        activity()->causedBy(auth()->user())->performedOn($organizer)->log('rejected organizer application');

        return response()->json([
            'message' => 'Organizer application rejected and reverted to attendee.'
        ]);
    }

    public function revenue()
    {
        $payments = Payment::with(['user', 'event'])->latest()->paginate(15);
        $totalCommission = Payment::where('status', 'completed')->sum('platform_fee') ?? 0;
        $totalGross = Payment::where('status', 'completed')->sum('amount') ?? 0;

        return response()->json([
            'payments' => $payments,
            'stats' => [
                'totalCommission' => (float) $totalCommission,
                'totalGross' => (float) $totalGross,
            ]
        ]);
    }

    public function reviews()
    {
        $reviews = Review::with(['user', 'event'])->latest()->paginate(20);
        return response()->json(['reviews' => $reviews]);
    }

    public function approveReview(Review $review)
    {
        $review->update(['is_approved' => true]);
        return response()->json(['message' => 'Review approved successfully.']);
    }

    public function rejectReview(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Review rejected/deleted successfully.']);
    }

    public function destroyReview(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully.']);
    }

    public function allOrganizers(Request $request)
    {
        $query = User::role('organizer')->withCount('organizedEvents');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'suspended') {
                $query->where('is_active', false);
            }
        }

        $organizers = $query->latest()->paginate(15);

        // Fetch revenue metadata per organizer
        $organizers->getCollection()->transform(function($org) {
            $totalRevenue = Payment::whereHas('event', function($q) use ($org) {
                $q->where('organizer_id', $org->id);
            })->where('status', 'completed')->sum('amount') ?? 0;

            return [
                'id' => $org->id,
                'name' => $org->name,
                'email' => $org->email,
                'is_active' => $org->is_active ?? true,
                'is_approved' => $org->is_approved,
                'organized_events_count' => $org->organized_events_count,
                'total_revenue' => (float)$totalRevenue,
                'created_at' => $org->created_at->toIso8601String(),
            ];
        });

        return response()->json(['organizers' => $organizers]);
    }

    public function showOrganizer(User $organizer)
    {
        $organizer->loadCount('organizedEvents');
        $events = Event::where('organizer_id', $organizer->id)->latest()->get();
        $totalRevenue = Payment::whereHas('event', function($q) use ($organizer) {
            $q->where('organizer_id', $organizer->id);
        })->where('status', 'completed')->sum('amount') ?? 0;

        return response()->json([
            'organizer' => [
                'id' => $organizer->id,
                'name' => $organizer->name,
                'email' => $organizer->email,
                'phone' => $organizer->phone,
                'is_active' => $organizer->is_active ?? true,
                'is_approved' => $organizer->is_approved,
                'events_count' => $organizer->organized_events_count,
                'total_revenue' => (float)$totalRevenue,
                'created_at' => $organizer->created_at->toIso8601String(),
            ],
            'events' => $events
        ]);
    }

    public function toggleOrganizerStatus(User $organizer)
    {
        $newStatus = !($organizer->is_active ?? true);
        $organizer->update(['is_active' => $newStatus]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($organizer)
            ->log('toggled organizer active state to: ' . ($newStatus ? 'active' : 'suspended'));

        return response()->json([
            'message' => 'Organizer status toggled successfully.',
            'is_active' => $newStatus
        ]);
    }

    public function allCoupons()
    {
        $coupons = Coupon::with(['organizer', 'event'])->latest()->get();
        return response()->json(['coupons' => $coupons]);
    }

    public function updateCoupon(Request $request, Coupon $coupon)
    {
        if ($request->has('toggle_active')) {
            $newActive = !$coupon->is_active;
            $coupon->update(['is_active' => $newActive]);
            return response()->json([
                'message' => 'Coupon active state toggled successfully.',
                'coupon' => $coupon
            ]);
        }

        $validated = $request->validate([
            'type' => 'required|in:percentage,flat',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean'
        ]);

        $coupon->update($validated);
        return response()->json([
            'message' => 'Coupon updated successfully.',
            'coupon' => $coupon
        ]);
    }

    public function destroyCoupon(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(['message' => 'Coupon purged successfully.']);
    }

    public function promotionsIndex()
    {
        $promotions = EventPromotion::with(['event.organizer', 'plan'])->latest()->get();
        $plans = EventPromotionPlan::latest()->get();
        
        $upcomingEvents = Event::with(['organizer', 'category'])->where('start_date', '>=', now())->latest()->get();

        $totalEarned = EventPromotion::where('status', 'approved')->sum('amount_paid') ?? 0;
        $pendingCount = EventPromotion::where('status', 'pending')->count();
        $activeCount = EventPromotion::where('status', 'approved')->where('end_date', '>', now())->count();

        return response()->json([
            'promotions' => $promotions,
            'plans' => $plans,
            'upcomingEvents' => $upcomingEvents,
            'stats' => [
                'totalEarned' => (float)$totalEarned,
                'pendingCount' => $pendingCount,
                'activeCount' => $activeCount,
            ]
        ]);
    }

    public function approvePromotion(EventPromotion $promotion)
    {
        $promotion->update([
            'status' => 'approved',
            'start_date' => now(),
            'end_date' => now()->addDays($promotion->plan->duration_days ?? 7)
        ]);

        if ($promotion->event) {
            $promotion->event->update(['is_featured' => true]);
        }

        return response()->json(['message' => 'Showcase campaign approved and activated in slideshow showcase.']);
    }

    public function rejectPromotion(EventPromotion $promotion)
    {
        $promotion->update(['status' => 'rejected']);
        return response()->json(['message' => 'Showcase request rejected successfully.']);
    }

    public function addToSlideshow(Event $event)
    {
        $event->update(['is_featured' => true]);
        return response()->json(['message' => 'Event manually added to homepage slideshow.']);
    }

    public function removeFromSlideshow(Event $event)
    {
        $event->update(['is_featured' => false]);
        return response()->json(['message' => 'Event manually removed from homepage slideshow.']);
    }

    public function storePromotionPlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        $plan = EventPromotionPlan::create($validated);
        return response()->json(['message' => 'Showcase package plan created successfully.', 'plan' => $plan]);
    }

    public function updatePromotionPlan(Request $request, EventPromotionPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $plan->update($validated);
        return response()->json(['message' => 'Showcase package plan updated successfully.', 'plan' => $plan]);
    }

    public function destroyPromotionPlan(EventPromotionPlan $plan)
    {
        $activePromoCount = EventPromotion::where('plan_id', $plan->id)->where('status', 'approved')->where('end_date', '>', now())->count();
        if ($activePromoCount > 0) {
            return response()->json(['message' => 'Cannot delete plan with active showcasing promotions.'], 400);
        }

        $plan->delete();
        return response()->json(['message' => 'Showcase package plan purged successfully.']);
    }

    public function contacts()
    {
        $contacts = Contact::latest()->paginate(20);
        return response()->json(['contacts' => $contacts]);
    }

    public function updateContactStatus(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied'
        ]);
        $contact->update($validated);
        return response()->json(['message' => 'Status updated.', 'contact' => $contact]);
    }

    public function replyContact(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'reply_message' => 'required|string'
        ]);

        try {
            Mail::to($contact->email)->send(new ContactReplyMail($contact, $validated['reply_message']));
        } catch (\Throwable $e) {
            // Ignore mailer failure if SMTP is not properly configured.
            \Log::error('Failed to send contact reply email: ' . $e->getMessage());
        }

        $contact->update(['status' => 'replied']);

        return response()->json([
            'message' => 'Reply sent successfully and status updated to Replied.',
            'contact' => $contact
        ]);
    }
}
