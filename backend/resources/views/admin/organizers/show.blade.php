<x-admin-layout>
    <x-slot name="header">
        Host Audit & Control
    </x-slot>

    <!-- Back to directory breadcrumb -->
    <div class="mb-8">
        <a href="{{ route('admin.organizers.index') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-primary transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Back to Host Directory
        </a>
    </div>

    <!-- Host Overview Section -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] p-10 mb-10 shadow-2xl shadow-primary/5 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-[2rem] bg-primary/10 flex items-center justify-center text-primary font-black text-2xl shadow-inner">
                {{ substr($organizer->name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-3xl font-serif text-slate-900 dark:text-white font-medium">{{ $organizer->name }}</h2>
                    @if($organizer->is_active)
                        <span class="bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-3.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest leading-none">Active</span>
                    @else
                        <span class="bg-rose-500/10 text-rose-600 border border-rose-500/20 px-3.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest leading-none font-bold">Suspended</span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-mono">{{ $organizer->email }}</p>
                <div class="flex items-center gap-4 text-xs text-slate-400 mt-3 font-semibold">
                    <span>Joined: {{ $organizer->created_at->format('M d, Y') }} ({{ $organizer->created_at->diffForHumans() }})</span>
                    <span class="text-slate-200 dark:text-slate-700">|</span>
                    <span>Timezone: {{ $organizer->timezone ?? 'Not Set' }}</span>
                </div>
            </div>
        </div>

        <!-- Governance Control Toggles -->
        <div class="flex items-center gap-4 border-t lg:border-t-0 pt-6 lg:pt-0 border-slate-100 dark:border-slate-850">
            <form action="{{ route('admin.organizers.toggle-status', $organizer) }}" method="POST" onsubmit="return confirm('Are you sure you want to change this host\'s governance status?')">
                @csrf
                @if($organizer->is_active)
                    <button type="submit" class="px-8 py-3.5 bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-600 hover:text-white transition-all font-black text-[10px] uppercase tracking-widest rounded-full flex items-center gap-2">
                        <i data-lucide="shield-alert" class="w-4 h-4"></i> Suspend Host
                    </button>
                @else
                    <button type="submit" class="px-8 py-3.5 bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all font-black text-[10px] uppercase tracking-widest rounded-full flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4"></i> Restore Host
                    </button>
                @endif
            </form>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Revenue -->
        <div class="premium-card bg-white border-slate-100 p-8 shadow-xl shadow-primary/5 flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <i data-lucide="wallet" class="w-7 h-7"></i>
            </div>
            <div>
                <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Total Revenue</span>
                <span class="text-2xl font-serif text-slate-900 dark:text-white font-medium">₹{{ number_format($totalRevenue) }}</span>
            </div>
        </div>

        <!-- Tickets Sold -->
        <div class="premium-card bg-white border-slate-100 p-8 shadow-xl shadow-primary/5 flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                <i data-lucide="ticket" class="w-7 h-7"></i>
            </div>
            <div>
                <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Tickets Sold</span>
                <span class="text-2xl font-serif text-slate-900 dark:text-white font-medium">{{ number_format($totalTicketsSold) }}</span>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="premium-card bg-white border-slate-100 p-8 shadow-xl shadow-primary/5 flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center flex-shrink-0">
                <i data-lucide="star" class="w-7 h-7"></i>
            </div>
            <div>
                <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Average Rating</span>
                <span class="text-2xl font-serif text-slate-900 dark:text-white font-medium">{{ number_format($averageRating, 1) }} / 5.0</span>
            </div>
        </div>

        <!-- Events Hosted -->
        <div class="premium-card bg-white border-slate-100 p-8 shadow-xl shadow-primary/5 flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center flex-shrink-0">
                <i data-lucide="calendar" class="w-7 h-7"></i>
            </div>
            <div>
                <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Total Events</span>
                <span class="text-2xl font-serif text-slate-900 dark:text-white font-medium">{{ $totalEvents }}</span>
            </div>
        </div>
    </div>

    <!-- Tabbed Area -->
    <div x-data="{ tab: 'events' }" class="space-y-8">
        <!-- Tab Selectors -->
        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900/50 p-1.5 rounded-full border border-slate-150 dark:border-slate-800/80 w-fit">
            <button @click="tab = 'events'" 
                    :class="tab === 'events' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'" 
                    class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300">
                Experiences ({{ $events->count() }})
            </button>
            <button @click="tab = 'promotions'" 
                    :class="tab === 'promotions' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'" 
                    class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300">
                Promotions ({{ $promotions->count() }})
            </button>
            <button @click="tab = 'reviews'" 
                    :class="tab === 'reviews' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'" 
                    class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300">
                Feedback ({{ $reviews->count() }})
            </button>
            <button @click="tab = 'reports'" 
                    :class="tab === 'reports' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'" 
                    class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300">
                Copyright Audit ({{ $copyrightReports->count() }})
            </button>
        </div>

        <!-- Tab 1: Events -->
        <div x-show="tab === 'events'" class="premium-card bg-white border-slate-100 overflow-hidden shadow-xl shadow-primary/5">
            <div class="p-8 border-b border-slate-50">
                <h3 class="text-xl font-serif text-slate-900">Experiences Catalogue</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Platform index of all events designed by this organizer.</p>
            </div>
            
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-cream/30">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Event Title</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Date & Category</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Event Status</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Capacity/Sales</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Revenue</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Rating</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Moderation Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($events as $event)
                            <tr class="hover:bg-cream/20 transition-colors duration-300">
                                <td class="px-8 py-5">
                                    <span class="font-bold text-slate-900 block leading-tight">{{ $event->title }}</span>
                                    <span class="text-[10px] font-mono text-slate-400 mt-1 block select-all">{{ $event->slug }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-slate-700 block">{{ $event->start_date->format('M d, Y') }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-black block mt-0.5">{{ $event->category->name }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    @if($event->is_restricted)
                                        <span class="bg-rose-500/10 text-rose-600 border border-rose-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest font-bold">Restricted</span>
                                    @else
                                        @if($event->status === 'published')
                                            <span class="bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest font-bold">Published</span>
                                        @elseif($event->status === 'draft')
                                            <span class="bg-slate-400/10 text-slate-500 border border-slate-400/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest font-bold">Draft</span>
                                        @else
                                            <span class="bg-amber-400/10 text-amber-500 border border-amber-400/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest font-bold capitalize">{{ $event->status }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-slate-750 block">{{ $event->tickets_count }} / {{ $event->total_capacity }} sold</span>
                                    <!-- Progress bar -->
                                    @php $percent = $event->total_capacity > 0 ? min(100, round(($event->tickets_count / $event->total_capacity) * 100)) : 0; @endphp
                                    <div class="w-24 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full mt-1.5 overflow-hidden">
                                        <div class="h-full bg-primary" style="width: {{ $percent }}%"></div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 font-bold text-slate-900">
                                    ₹{{ number_format($event->revenue) }}
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-slate-750 flex items-center gap-1">
                                        <i data-lucide="star" class="w-3.5 h-3.5 text-amber-400 fill-amber-400"></i>
                                        {{ $event->rating > 0 ? number_format($event->rating, 1) : 'None' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <!-- Toggle Restriction -->
                                    <button onclick="openRestrictModal('{{ $event->slug }}', {{ $event->is_restricted ? 'true' : 'false' }})" 
                                            class="px-4.5 py-2.5 rounded-full text-[9px] font-black uppercase tracking-widest transition-all border {{ $event->is_restricted ? 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-600 hover:text-white' : 'bg-rose-50 text-rose-500 border-rose-100 hover:bg-rose-500 hover:text-white' }}">
                                        {{ $event->is_restricted ? 'Restore access' : 'Restrict Experience' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-8 py-20 text-center text-slate-400">
                                    No events created by this host yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 2: Promotions -->
        <div x-show="tab === 'promotions'" class="premium-card bg-white border-slate-100 overflow-hidden shadow-xl shadow-primary/5">
            <div class="p-8 border-b border-slate-50">
                <h3 class="text-xl font-serif text-slate-900">Marketing & Promotions</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Ecosystem showcase campaigns running for this host's events.</p>
            </div>
            
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-cream/30">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Event</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Time Interval</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Advertising Status</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Showcase Decisions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($promotions as $promotion)
                            <tr class="hover:bg-cream/20 transition-colors duration-300">
                                <td class="px-8 py-5">
                                    <span class="font-bold text-slate-900 block leading-tight">{{ $promotion->event->title }}</span>
                                    <span class="text-[10px] text-slate-400 block mt-1">Reference: ep-{{ $promotion->id }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-slate-700 block">{{ $promotion->start_date->format('M d, Y') }} - {{ $promotion->end_date->format('M d, Y') }}</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 block mt-0.5">End in {{ $promotion->end_date->diffForHumans(null, true) }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    @if($promotion->status === 'approved')
                                        <span class="bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Running</span>
                                    @elseif($promotion->status === 'pending')
                                        <span class="bg-amber-500/10 text-amber-600 border border-amber-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Pending Audit</span>
                                    @else
                                        <span class="bg-rose-500/10 text-rose-600 border border-rose-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest capitalize">{{ $promotion->status }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if($promotion->status === 'pending')
                                        <div class="flex items-center justify-end gap-3">
                                            <form action="{{ route('admin.promotions.approve', $promotion) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-primary text-white text-[9px] font-black uppercase tracking-widest rounded-full hover:bg-primary-650 shadow-md">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.promotions.reject', $promotion) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-rose-50 text-rose-500 border border-rose-100 text-[9px] font-black uppercase tracking-widest rounded-full hover:bg-rose-500 hover:text-white">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Audited</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center text-slate-400">
                                    No promotions requested by this host yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 3: Reviews -->
        <div x-show="tab === 'reviews'" class="premium-card bg-white border-slate-100 overflow-hidden shadow-xl shadow-primary/5">
            <div class="p-8 border-b border-slate-50">
                <h3 class="text-xl font-serif text-slate-900">Resonance & Reviews</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Event validation and community reviews left on this host's events.</p>
            </div>
            
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-cream/30">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experience</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Reviewer</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Rating</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Comment</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Audit Date</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Moderation Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($reviews as $review)
                            <tr class="hover:bg-cream/20 transition-colors duration-300">
                                <td class="px-8 py-5">
                                    <span class="font-bold text-slate-900 block leading-tight">{{ $review->event->title }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-slate-750 block">{{ $review->user->name }}</span>
                                    <span class="text-[9px] text-slate-400 block mt-0.5">{{ $review->user->email }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-0.5">
                                        @for($i=1; $i<=5; $i++)
                                            <i data-lucide="star" class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-xs text-slate-600 max-w-sm italic">"{{ $review->comment }}"</p>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-slate-700 block">{{ $review->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Remove this review from the public registry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4.5 py-2.5 bg-rose-50 text-rose-500 border border-rose-100 text-[9px] font-black uppercase tracking-widest rounded-full hover:bg-rose-500 hover:text-white transition-all">
                                            Purge Review
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center text-slate-400">
                                    No community reviews recorded for this host.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 4: Reports -->
        <div x-show="tab === 'reports'" class="premium-card bg-white border-slate-100 overflow-hidden shadow-xl shadow-primary/5">
            <div class="p-8 border-b border-slate-50">
                <h3 class="text-xl font-serif text-slate-900">Legal & Copyright Audit</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Ecosystem legal compliance alerts and illegal content reports flagged on this host's events.</p>
            </div>
            
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-cream/30">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Infraction Event</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Filing Reporter</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Description</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Governance Status</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Report Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($copyrightReports as $report)
                            <tr class="hover:bg-cream/20 transition-colors duration-300">
                                <td class="px-8 py-5">
                                    <span class="font-bold text-slate-900 block leading-tight">{{ $report->event->title }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-slate-750 block">{{ $report->user->name }}</span>
                                    <span class="text-[9px] text-slate-400 block mt-0.5">{{ $report->user->email }}</span>
                                </td>
                                <td class="px-8 py-5 text-xs text-slate-650 max-w-sm italic">
                                    "{{ $report->details ?? $report->reason }}"
                                </td>
                                <td class="px-8 py-5">
                                    @if($report->status === 'resolved')
                                        <span class="bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-3.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest font-bold">Resolved</span>
                                    @elseif($report->status === 'pending')
                                        <span class="bg-rose-500/10 text-rose-600 border border-rose-500/20 px-3.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest font-bold">Pending Review</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 border border-slate-200 px-3.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest capitalize font-bold">{{ $report->status }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-slate-700 block">{{ $report->created_at->format('M d, Y') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center text-slate-400">
                                    Ecosystem compliant. No legal or copyright reports filed against this host.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Restrict Event Modal -->
    <div id="restrictModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeRestrictModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-800">
                <form id="restrictForm" method="POST" action="">
                    @csrf
                    <div class="p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div id="restrictModalIcon" class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-505">
                                <i data-lucide="alert-octagon" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-2xl font-serif text-slate-900 dark:text-slate-100 font-semibold" id="restrictModalTitle">Restrict Experience Access</h3>
                        </div>
                        
                        <div id="restrictReasonContainer" class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Reason for Public Restriction</label>
                            <textarea name="restriction_reason" rows="4" placeholder="Explain the rationale for content restriction..." 
                                      class="form-input"></textarea>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">This notification will hide the experience from public search indices.</p>
                        </div>
                        <div id="restoreExplanation" class="hidden text-sm text-slate-650 dark:text-slate-400">
                            Confirm that you wish to restore public listing access for this experience. It will reappear on public calendars immediately.
                        </div>
                    </div>
                    <div class="bg-cream/40 dark:bg-slate-800/20 p-8 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-end gap-6">
                        <button type="button" onclick="closeRestrictModal()" class="text-[10px] font-black text-slate-400 hover:text-slate-900 dark:hover:text-white uppercase tracking-widest transition-colors py-2">
                            Cancel
                        </button>
                        <button type="submit" id="restrictConfirmBtn" class="px-8 py-3.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold uppercase tracking-widest rounded-full shadow-lg transition-all">
                            Confirm Restriction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRestrictModal(eventSlug, isRestricted) {
            const form = document.getElementById('restrictForm');
            const title = document.getElementById('restrictModalTitle');
            const reasonContainer = document.getElementById('restrictReasonContainer');
            const restoreExplanation = document.getElementById('restoreExplanation');
            const confirmBtn = document.getElementById('restrictConfirmBtn');
            const iconContainer = document.getElementById('restrictModalIcon');

            form.action = `/admin/events/${eventSlug}/restrict`;

            if (isRestricted) {
                title.innerText = "Restore Public Listing";
                reasonContainer.classList.add('hidden');
                restoreExplanation.classList.remove('hidden');
                confirmBtn.innerText = "Restore Experience";
                confirmBtn.className = "px-8 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold uppercase tracking-widest rounded-full shadow-lg transition-all";
                iconContainer.className = "w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500";
                iconContainer.innerHTML = '<i data-lucide="shield-check" class="w-6 h-6"></i>';
            } else {
                title.innerText = "Restrict Experience Access";
                reasonContainer.classList.remove('hidden');
                restoreExplanation.classList.add('hidden');
                confirmBtn.innerText = "Confirm Restriction";
                confirmBtn.className = "px-8 py-3.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold uppercase tracking-widest rounded-full shadow-lg transition-all";
                iconContainer.className = "w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500";
                iconContainer.innerHTML = '<i data-lucide="alert-octagon" class="w-6 h-6"></i>';
            }

            document.getElementById('restrictModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function closeRestrictModal() {
            document.getElementById('restrictModal').classList.add('hidden');
        }
        
        lucide.createIcons();
    </script>
</x-admin-layout>
