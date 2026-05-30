<x-admin-layout>
    <x-slot name="header">
        Showcase Advertising Indexes
    </x-slot>

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 mb-12">
        <div>
            <h2 class="text-3xl font-serif text-slate-900 mb-2">Ecosystem Showcase Advertising</h2>
            <p class="text-slate-500 font-serif italic text-sm">"Governing premium homepage showcase campaigns and ads billing frameworks across the ecosystem."</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-12 p-6 bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 rounded-[2rem] flex items-center gap-4 text-[#4E7D5B] animate-float">
            <div class="w-8 h-8 rounded-full bg-[#4E7D5B] text-white flex items-center justify-center shadow-lg shadow-[#4E7D5B]/20">
                <i data-lucide="check" class="w-4 h-4"></i>
            </div>
            <div class="text-xs font-bold uppercase tracking-wider leading-relaxed">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Metrics Ledger Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <div class="premium-card p-10 bg-white border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">TOTAL EARNED REVENUE</span>
            <div class="text-4xl font-serif text-slate-900 mb-2">₹{{ number_format($totalEarned, 2) }}</div>
            <div class="text-[10px] font-bold text-[#4E7D5B] uppercase tracking-widest flex items-center gap-1">
                <i data-lucide="trending-up" class="w-3 h-3"></i> ADS EXCHANGE NOMINAL
            </div>
        </div>

        <div class="premium-card p-10 bg-white border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">PENDING REQUEST QUEUE</span>
            <div class="text-4xl font-serif text-slate-900 mb-2">{{ $pendingCount }}</div>
            <div class="text-[10px] font-bold text-amber-500 uppercase tracking-widest flex items-center gap-1">
                <i data-lucide="activity" class="w-3 h-3"></i> AWAITING ASSIGNMENT
            </div>
        </div>

        <div class="premium-card p-10 bg-white border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">ACTIVE SLIDESHOW NODES</span>
            <div class="text-4xl font-serif text-[#4E7D5B] mb-2">{{ $activeCount }}</div>
            <div class="text-[10px] font-bold text-[#4E7D5B] uppercase tracking-widest flex items-center gap-1">
                <i data-lucide="sparkles" class="w-3 h-3 animate-pulse"></i> SHOWCASING LIVE
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12" x-data="{
        editMode: false,
        planAction: '{{ route('admin.promotion-plans.store') }}',
        planName: '',
        planDescription: '',
        planPrice: '',
        planDuration: '',
        planActive: true,
        openCreate() {
            this.editMode = false;
            this.planAction = '{{ route('admin.promotion-plans.store') }}';
            this.planName = '';
            this.planDescription = '';
            this.planPrice = '';
            this.planDuration = '';
            this.planActive = true;
            document.getElementById('planModal').classList.remove('hidden');
        },
        openEdit(plan) {
            this.editMode = true;
            this.planAction = `/admin/promotion-plans/${plan.id}`;
            this.planName = plan.name;
            this.planDescription = plan.description;
            this.planPrice = plan.price;
            this.planDuration = plan.duration_days;
            this.planActive = plan.is_active;
            document.getElementById('planModal').classList.remove('hidden');
            document.getElementById('method-field').value = 'PUT';
        },
        closeModal() {
            document.getElementById('planModal').classList.add('hidden');
            document.getElementById('method-field').value = 'POST';
        }
    }">
        <!-- Left Side: Advertising Queue Requests -->
        <div class="lg:col-span-8 space-y-12">
            <section class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-serif text-slate-900">Showcase Requests Queue</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Review organizer paid campaigns and assign them to the slider</p>
                    </div>
                </div>

                <div class="overflow-x-auto no-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-cream/30 border-b border-slate-50">
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Node</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Selected Plan</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Amount Paid</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Showcase Window</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($promotions as $promo)
                                <tr class="group hover:bg-cream/50 transition-colors duration-300">
                                    <!-- Event and Host Info -->
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="relative w-10 h-10 shrink-0 rounded-xl overflow-hidden shadow-sm bg-slate-50 border border-slate-100">
                                                @if($promo->event && $promo->event->hasMedia('banners'))
                                                    <img src="{{ $promo->event->getFirstMediaUrl('banners', 'thumb') }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                                                        <i data-lucide="image" class="text-slate-700 w-4 h-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-900 text-sm block leading-snug group-hover:text-primary transition-colors">{{ $promo->event->title ?? 'Deleted Event' }}</span>
                                                <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Host: {{ $promo->event->organizer->name ?? 'Unknown' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Plan Info -->
                                    <td class="px-8 py-6 text-xs text-slate-700">
                                        <span class="font-bold block">{{ $promo->plan->name }}</span>
                                        <span class="text-[9px] text-[#4E7D5B] font-bold uppercase tracking-widest">Duration: {{ $promo->plan->duration_days }} Days</span>
                                    </td>

                                    <!-- Amount Paid -->
                                    <td class="px-8 py-6">
                                        <span class="font-serif text-[#4E7D5B] font-black text-sm">₹{{ number_format($promo->amount_paid, 2) }}</span>
                                    </td>

                                    <!-- Showcase Window Dates -->
                                    <td class="px-8 py-6 text-xs text-slate-500 font-mono">
                                        @if($promo->status == 'approved')
                                            <span class="block text-[9px] text-slate-400 uppercase tracking-widest leading-none mb-1">ENDS ON</span>
                                            <span class="font-bold text-slate-700">{{ $promo->end_date->format('M d, Y') }}</span>
                                        @elseif($promo->status == 'pending')
                                            <span class="italic text-slate-400">Queue pending</span>
                                        @else
                                            <span class="line-through">Not active</span>
                                        @endif
                                    </td>

                                    <!-- Status Badges -->
                                    <td class="px-8 py-6">
                                        @if($promo->status == 'pending')
                                            <span class="status-pill bg-amber-50 text-amber-700 border-amber-100 animate-pulse tracking-wider">PENDING APPROVAL</span>
                                        @elseif($promo->status == 'approved')
                                            @if($promo->end_date->isFuture())
                                                <span class="status-pill bg-[#4E7D5B]/10 text-[#4E7D5B] border-[#4E7D5B]/20 tracking-wider">SHOWCASE LIVE</span>
                                            @else
                                                <span class="status-pill bg-slate-100 text-slate-400 border-slate-200 tracking-wider">EXPIRED</span>
                                            @endif
                                        @else
                                            <span class="status-pill bg-rose-50 text-rose-500 border-rose-100 tracking-wider">REJECTED</span>
                                        @endif
                                    </td>

                                    <!-- Operations -->
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-4">
                                            @if($promo->status == 'pending')
                                                <form action="{{ route('admin.promotions.approve', $promo->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex px-4 py-2 bg-[#4E7D5B] hover:bg-[#3C6347] text-white rounded-full text-[9px] font-black uppercase tracking-widest shadow-md shadow-[#4E7D5B]/10 transition-colors">
                                                        APPROVE & ACTIVATE
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.promotions.reject', $promo->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex px-4 py-2 border border-rose-200 text-rose-500 hover:bg-rose-50 rounded-full text-[9px] font-black uppercase tracking-widest transition-colors">
                                                        Reject
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">ARCHIVED RECORD</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-32 text-center text-slate-400 font-serif italic">
                                        <div class="w-20 h-20 bg-cream rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                            <i data-lucide="history" class="w-10 h-10"></i>
                                        </div>
                                        <h3 class="text-xl font-serif text-slate-900 mb-2">No showcase requests in queue.</h3>
                                        <p class="text-slate-500 font-serif italic">All campaign records are successfully assigned and nominal.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($promotions->hasPages())
                    <div class="p-8 border-t border-slate-50 bg-cream/10">
                        {{ $promotions->appends(request()->except('promotions_page'))->links() }}
                    </div>
                @endif
            </section>

            <!-- Manual Slideshow Display Override Section -->
            <section class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5 mt-12">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-serif text-slate-900">Slideshow Display Control (Manual Override)</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Directly promote or remove any upcoming event in the main slideshow</p>
                    </div>
                </div>

                <div class="overflow-x-auto no-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-cream/30 border-b border-slate-50">
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Event</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Category</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Start Date</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Slideshow Status</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($upcomingEvents as $event)
                                @php
                                    $hasActivePromo = $event->promotions->where('status', 'approved')
                                        ->where('payment_status', 'paid')
                                        ->where('start_date', '<=', now())
                                        ->where('end_date', '>=', now())
                                        ->count() > 0;
                                    $isInSlideshow = $event->is_featured || $hasActivePromo;
                                @endphp
                                <tr class="group hover:bg-cream/50 transition-colors duration-300">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="relative w-10 h-10 shrink-0 rounded-xl overflow-hidden shadow-sm bg-slate-50 border border-slate-100">
                                                @if($event->hasMedia('banners'))
                                                    <img src="{{ $event->getFirstMediaUrl('banners', 'thumb') }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                                                        <i data-lucide="image" class="text-slate-700 w-4 h-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-900 text-sm block leading-snug group-hover:text-primary transition-colors">{{ $event->title }}</span>
                                                <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Host: {{ $event->organizer->name ?? 'Unknown' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-8 py-6 text-xs text-slate-700">
                                        <span class="font-bold block">{{ $event->category->name }}</span>
                                    </td>

                                    <td class="px-8 py-6 text-xs text-slate-500 font-mono">
                                        <span class="font-bold text-slate-700">{{ $event->start_date->format('M d, Y') }}</span>
                                    </td>

                                    <td class="px-8 py-6">
                                        @if($isInSlideshow)
                                            @if($event->is_featured && $hasActivePromo)
                                                <span class="status-pill bg-[#4E7D5B]/10 text-[#4E7D5B] border-[#4E7D5B]/20 tracking-wider">FEATURED & PAID PROMO</span>
                                            @elseif($event->is_featured)
                                                <span class="status-pill bg-[#4E7D5B]/10 text-[#4E7D5B] border-[#4E7D5B]/20 tracking-wider">MANUALLY FEATURED</span>
                                            @else
                                                <span class="status-pill bg-emerald-50 text-emerald-700 border-emerald-100 tracking-wider">PAID PROMO ACTIVE</span>
                                            @endif
                                        @else
                                            <span class="status-pill bg-slate-100 text-slate-400 border-slate-200 tracking-wider">STANDARD LISTING</span>
                                        @endif
                                    </td>

                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-4">
                                            @if($isInSlideshow)
                                                <form action="{{ route('admin.promotions.remove', $event->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex px-4 py-2 border border-rose-200 text-rose-500 hover:bg-rose-50 rounded-full text-[9px] font-black uppercase tracking-widest transition-colors">
                                                        REMOVE FROM SLIDESHOW
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.promotions.add', $event->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex px-4 py-2 bg-[#4E7D5B] hover:bg-[#3C6347] text-white rounded-full text-[9px] font-black uppercase tracking-widest shadow-md shadow-[#4E7D5B]/10 transition-colors">
                                                        ADD TO SLIDESHOW
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-slate-400 font-serif italic">
                                        <h3 class="text-lg font-serif text-slate-900 mb-2">No upcoming events found.</h3>
                                        <p class="text-slate-550 italic text-xs">Create or seed new events to see them in this control board.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($upcomingEvents->hasPages())
                    <div class="p-8 border-t border-slate-50 bg-cream/10">
                        {{ $upcomingEvents->appends(request()->except('events_page'))->links() }}
                    </div>
                @endif
            </section>
        </div>

        <!-- Right Side: Showcase Packages Pricing Manager -->
        <div class="lg:col-span-4 space-y-8">
            <section class="premium-card p-8 bg-white border-slate-100 shadow-2xl shadow-primary/5">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                    <div>
                        <h3 class="text-lg font-serif text-slate-900">Showcase Packages</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Configure pricing packages & display limits</p>
                    </div>
                    <button type="button" @click="openCreate()" class="w-8 h-8 rounded-full bg-[#4E7D5B]/10 hover:bg-[#4E7D5B]/20 text-[#4E7D5B] transition-all flex items-center justify-center">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    @forelse($plans as $plan)
                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 flex flex-col gap-4 relative overflow-hidden group">
                            <!-- Toggle switch active state -->
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-[#4E7D5B] transition-colors leading-snug">{{ $plan->name }}</h4>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1 block">DISPLAY: {{ $plan->duration_days }} DAYS</span>
                                </div>
                                <span class="font-serif text-[#4E7D5B] font-black text-base">₹{{ number_format($plan->price) }}</span>
                            </div>

                            <p class="text-xs text-slate-400 leading-relaxed">{{ $plan->description }}</p>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-200/50">
                                <span class="inline-flex px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border {{ $plan->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                                    {{ $plan->is_active ? 'ACTIVE' : 'SUSPENDED' }}
                                </span>
                                
                                <div class="flex gap-4">
                                    <button type="button" @click="openEdit({{ json_encode($plan) }})" class="text-[9px] font-black text-primary hover:text-primary-hover uppercase tracking-widest">
                                        MODIFY
                                    </button>
                                    <form action="{{ route('admin.promotion-plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Purge this showcase pricing tier from ecosystem?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[9px] font-black text-rose-500 hover:text-rose-600 uppercase tracking-widest">
                                            DELETE
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs font-serif italic text-slate-400 text-center py-8">No pricing plans defined yet.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Add/Edit Showcase Plan Modal -->
        <div id="planModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                    <form id="planForm" method="POST" :action="planAction">
                        @csrf
                        <input type="hidden" name="_method" id="method-field" value="POST">
                        
                        <div class="bg-white p-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                                    <i data-lucide="sparkles" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-serif text-slate-900" x-text="editMode ? 'Modify Showcase Plan' : 'Construct Showcase Plan'"></h3>
                                    <p class="text-[9px] text-slate-400 font-mono tracking-widest uppercase mt-0.5">Ecosystem Billing Strategy</p>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Plan Name</label>
                                    <input type="text" name="name" x-model="planName" required class="form-input" placeholder="e.g. Spotlight Banner">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Description</label>
                                    <textarea name="description" x-model="planDescription" rows="3" class="form-input" placeholder="Brief outline of placement details..."></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Price (₹)</label>
                                        <input type="number" name="price" x-model="planPrice" step="0.01" min="0" required class="form-input" placeholder="₹">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Duration (Days)</label>
                                        <input type="number" name="duration_days" x-model="planDuration" min="1" required class="form-input" placeholder="Days">
                                    </div>
                                </div>

                                <div class="pt-2" x-show="editMode">
                                    <label class="flex items-center gap-4 cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="is_active" value="1" :checked="planActive" class="sr-only peer">
                                            <div class="w-12 h-6 bg-slate-100 rounded-full peer-checked:bg-primary transition-colors border border-slate-200"></div>
                                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6 shadow-sm"></div>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">Enabled in Ecosystem</span>
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Allow organizers to purchase this package plan</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-cream p-10 flex items-center justify-end gap-6">
                            <button type="button" @click="closeModal()" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="btn-primary px-10 py-4 text-xs shadow-xl shadow-primary/20">
                                Apply Modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</x-admin-layout>
