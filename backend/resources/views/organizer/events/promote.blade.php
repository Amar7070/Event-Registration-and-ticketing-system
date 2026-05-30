<x-organizer-layout>
    <x-slot name="header">
        Ecosystem Showcase Advertising
    </x-slot>

    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-serif text-slate-900 mb-2">Showcase Event Promotion</h2>
            <p class="text-slate-500 font-serif italic text-sm">"Elevate your experience directly onto the premium SmartEvent Home page slideshow."</p>
        </div>
        <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-slate-800 uppercase tracking-widest transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Blueprint Ledger
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-6 bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 rounded-[2rem] flex items-center gap-4 text-[#4E7D5B] animate-float">
            <div class="w-8 h-8 rounded-full bg-[#4E7D5B] text-white flex items-center justify-center shadow-lg shadow-[#4E7D5B]/20">
                <i data-lucide="check" class="w-4 h-4"></i>
            </div>
            <div class="text-xs font-bold uppercase tracking-wider leading-relaxed">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 pb-32" x-data="{
        selectedPlan: null,
        selectedPrice: 0,
        selectedDuration: 0,
        cardNumber: '',
        cardExpiry: '',
        cardCvv: '',
        cardName: '{{ Auth::user()->name }}',
        simulateCard(e) {
            let val = e.target.value.replace(/\D/g, '');
            let matches = val.match(/\d{4,16}/g);
            let match = matches && matches[0] || '';
            let parts = [];
            for (let i=0, len=match.length; i<len; i+=4) {
                parts.push(match.substring(i, i+4));
            }
            if (parts.length > 0) {
                this.cardNumber = parts.join(' ');
            } else {
                this.cardNumber = val;
            }
        },
        selectPlan(id, price, duration) {
            this.selectedPlan = id;
            this.selectedPrice = price;
            this.selectedDuration = duration;
            this.$nextTick(() => {
                document.getElementById('checkout-simulator-form').scrollIntoView({ behavior: 'smooth' });
            });
        }
    }">
        <!-- Left Side: Plan Selector and Purchase Form -->
        <div class="lg:col-span-8 space-y-12">
            
            <!-- Plan Choices -->
            <section class="premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-serif text-slate-900">1. Select Showcase Plan</h2>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Admin curated premium landing page exposure tiers</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($plans as $plan)
                        <div @click="selectPlan({{ $plan->id }}, {{ $plan->price }}, {{ $plan->duration_days }})"
                             :class="selectedPlan == {{ $plan->id }} ? 'border-[#4E7D5B] bg-[#4E7D5B]/5 shadow-lg scale-[1.02]' : 'border-slate-100 hover:border-[#4E7D5B]/40 hover:scale-[1.01]'"
                             class="border rounded-[2.5rem] p-8 flex flex-col justify-between gap-6 cursor-pointer premium-transition bg-white relative overflow-hidden group">
                            
                            <!-- Highlight badge if active -->
                            <div class="absolute top-0 right-0 bg-[#4E7D5B] text-white text-[8px] font-black uppercase tracking-widest px-4 py-2 rounded-bl-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                                CHOOSE PACKAGE
                            </div>

                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block leading-none mb-3">DURATION: {{ $plan->duration_days }} DAYS</span>
                                <h3 class="text-lg font-serif text-slate-900 leading-snug group-hover:text-[#4E7D5B] transition-colors">{{ $plan->name }}</h3>
                                <p class="text-xs text-slate-400 mt-4 leading-relaxed line-clamp-3">{{ $plan->description }}</p>
                            </div>

                            <div class="pt-6 border-t border-slate-50 flex items-baseline gap-1">
                                <span class="text-3xl font-serif text-[#4E7D5B] tracking-tight">₹{{ number_format($plan->price, 0) }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">INR</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
                            <p class="text-xs font-serif italic text-slate-500">No showcase pricing plans registered by the platform yet.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Checkout Panel (Awaiting plan selection) -->
            <section id="checkout-simulator-form" class="premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5 relative"
                     x-show="selectedPlan !== null" x-transition x-cloak>
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-serif text-slate-900">2. Secure Sandbox Checkout</h2>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Simulate premium checkout mapping with zero-friction processing</p>
                    </div>
                </div>

                <form action="{{ route('organizer.events.promote.submit', $event) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @csrf
                    <input type="hidden" name="plan_id" :value="selectedPlan">

                    <!-- Simulated Credit Card preview -->
                    <div class="flex flex-col justify-center">
                        <div class="w-full aspect-[1.586/1] bg-gradient-to-br from-slate-900 via-slate-800 to-[#4E7D5B] text-white rounded-[2rem] p-8 flex flex-col justify-between shadow-2xl shadow-slate-900/10 relative overflow-hidden group select-none">
                            <div class="absolute inset-0 bg-[#FDFBF7]/5 pointer-events-none" style="background-image: radial-gradient(circle, #fff 0.8px, transparent 0.8px); background-size: 24px 24px;"></div>
                            
                            <div class="relative z-10 flex justify-between items-start">
                                <div>
                                    <span class="text-[9px] font-black text-white/40 uppercase tracking-[0.25em]">SMARTEVENT MERCHANT</span>
                                    <h4 class="text-sm font-serif italic text-white/90 mt-1">Sandbox Gateway</h4>
                                </div>
                                <div class="w-10 h-8 bg-white/10 rounded-lg flex items-center justify-center backdrop-blur-md">
                                    <i data-lucide="wifi" class="w-4 h-4 text-white/60"></i>
                                </div>
                            </div>

                            <div class="relative z-10">
                                <span class="text-lg md:text-xl font-mono tracking-[0.18em] text-white/90" x-text="cardNumber ? cardNumber : '••••  ••••  ••••  ••••'"></span>
                            </div>

                            <div class="relative z-10 flex justify-between items-end">
                                <div>
                                    <span class="text-[8px] font-black text-white/40 uppercase tracking-widest block leading-none mb-1">CARDHOLDER</span>
                                    <span class="text-xs font-mono uppercase tracking-wider text-white/90 truncate max-w-[150px]" x-text="cardName"></span>
                                </div>
                                <div class="flex gap-6">
                                    <div>
                                        <span class="text-[8px] font-black text-white/40 uppercase tracking-widest block leading-none mb-1">EXPIRY</span>
                                        <span class="text-xs font-mono text-white/90" x-text="cardExpiry ? cardExpiry : 'MM/YY'"></span>
                                    </div>
                                    <div>
                                        <span class="text-[8px] font-black text-white/40 uppercase tracking-widest block leading-none mb-1">CVV</span>
                                        <span class="text-xs font-mono text-white/90" x-text="cardCvv ? cardCvv : '•••'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Inputs -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">Cardholder Name</label>
                            <input type="text" x-model="cardName" required class="form-input" placeholder="e.g. {{ Auth::user()->name }}">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">Card Number</label>
                            <div class="relative">
                                <input type="text" required maxlength="19" @input="simulateCard" class="form-input pr-12 font-mono" placeholder="4111 2222 3333 4444">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-[#4E7D5B]"></i>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">Expiry Date</label>
                                <input type="text" x-model="cardExpiry" required maxlength="5" class="form-input font-mono" placeholder="MM/YY">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">CVV Code</label>
                                <input type="password" x-model="cardCvv" required maxlength="4" class="form-input font-mono" placeholder="•••">
                            </div>
                        </div>
                    </div>

                    <!-- Order Total Receipt -->
                    <div class="col-span-2 p-6 bg-slate-50 rounded-[2rem] border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="text-left space-y-1">
                            <span class="text-[9px] font-black text-[#4E7D5B] uppercase tracking-[0.2em] block">Simulated billing breakdown</span>
                            <h4 class="text-sm font-bold text-slate-900">Total Purchase: <span class="text-[#4E7D5B] font-serif font-black" x-text="'₹' + Number(selectedPrice).toLocaleString()"></span></h4>
                            <p class="text-[11px] text-slate-400">Valid for exactly <span class="font-bold text-slate-700" x-text="selectedDuration"></span> days of cinematic slider showcase.</p>
                        </div>
                        <button type="submit" class="inline-flex px-8 py-4 bg-[#4E7D5B] hover:bg-[#3C6347] active:scale-95 text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-[#4E7D5B]/20 premium-transition">
                            PROCESS SIMULATED PAYMENT
                        </button>
                    </div>
                </form>
            </section>

        </div>

        <!-- Right Side: Sidebar Event Detail & Ledger Stats -->
        <div class="lg:col-span-4 space-y-8">
            <section class="premium-card p-8 bg-white border-slate-100 shadow-2xl shadow-primary/5">
                <div class="w-full aspect-[2/1] rounded-[1.5rem] bg-slate-50 border border-slate-100 overflow-hidden mb-6 relative">
                    @if($event->hasMedia('banners'))
                        <img src="{{ $event->getFirstMediaUrl('banners') }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                            <i data-lucide="image" class="w-8 h-8"></i>
                        </div>
                    @endif
                    <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-slate-100">
                        <span class="text-[8px] font-black uppercase tracking-widest text-[#4E7D5B]">{{ $event->category->name }}</span>
                    </div>
                </div>

                <h3 class="text-xl font-serif text-slate-900 leading-snug mb-3">{{ $event->title }}</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-6">{{ Str::limit($event->short_description, 140) }}</p>

                <div class="space-y-4 pt-6 border-t border-slate-50">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400">Status:</span>
                        <span class="font-bold uppercase tracking-wider text-slate-600">{{ $event->status }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400">Launch Date:</span>
                        <span class="font-bold text-slate-800">{{ $event->start_date->format('M d, Y') }}</span>
                    </div>
                </div>
            </section>

            <section class="premium-card p-8 bg-slate-900 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-[#FDFBF7]/5 pointer-events-none" style="background-image: radial-gradient(circle, #fff 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
                
                <div class="relative z-10">
                    <span class="text-[9px] font-black text-white/40 uppercase tracking-[0.3em] block mb-2">METRIC FORECAST</span>
                    <h3 class="text-2xl font-serif text-[#98C2A7] tracking-tight mb-4">Promotional Impact</h3>
                    <p class="text-xs text-white/60 leading-relaxed mb-6">
                        Experience listings featured directly on the Home page slider enjoy up to <span class="text-white font-bold">12x higher click rates</span> and capture more waitlist sign-ups than standard search grids.
                    </p>
                    <div class="p-4 bg-white/5 rounded-2xl border border-white/10 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#4E7D5B] text-white flex items-center justify-center">
                            <i data-lucide="trending-up" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[8px] font-black text-white/40 uppercase tracking-widest block leading-none">ESTIMATED VIEWS</span>
                            <span class="text-lg font-serif tracking-tight">+45,000 / week</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Full Width Ledger of Showcase Requests -->
        <div class="lg:col-span-12">
            <section class="premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                            <i data-lucide="history" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-serif text-slate-900">Showcase Advertisement Request Ledger</h2>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Full historical audit logs of your featured event promotions</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="pb-4 text-[9px] font-black uppercase tracking-widest text-slate-400">PLAN PACKAGE</th>
                                <th class="pb-4 text-[9px] font-black uppercase tracking-widest text-slate-400">COST PAID</th>
                                <th class="pb-4 text-[9px] font-black uppercase tracking-widest text-slate-400">PAYMENT STATUS</th>
                                <th class="pb-4 text-[9px] font-black uppercase tracking-widest text-slate-400">SHOWCASE STATUS</th>
                                <th class="pb-4 text-[9px] font-black uppercase tracking-widest text-slate-400">START DATE</th>
                                <th class="pb-4 text-[9px] font-black uppercase tracking-widest text-slate-400">END DATE</th>
                                <th class="pb-4 text-[9px] font-black uppercase tracking-widest text-slate-400">SUBMITTED ON</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promotions as $promo)
                                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 premium-transition">
                                    <td class="py-6">
                                        <span class="text-xs font-bold text-slate-900 block">{{ $promo->plan->name }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Duration: {{ $promo->plan->duration_days }} Days</span>
                                    </td>
                                    <td class="py-6">
                                        <span class="text-xs font-serif font-black text-[#4E7D5B]">₹{{ number_format($promo->amount_paid, 2) }}</span>
                                    </td>
                                    <td class="py-6">
                                        <span class="inline-flex px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-100">
                                            {{ $promo->payment_status }}
                                        </span>
                                    </td>
                                    <td class="py-6">
                                        @if($promo->status == 'pending')
                                            <span class="inline-flex px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-[9px] font-black uppercase tracking-widest border border-amber-100 animate-pulse">
                                                Awaiting Assignment
                                            </span>
                                        @elseif($promo->status == 'approved')
                                            <span class="inline-flex px-3 py-1 bg-[#4E7D5B]/10 text-[#4E7D5B] rounded-full text-[9px] font-black uppercase tracking-widest border border-[#4E7D5B]/20">
                                                Showcasing Active
                                            </span>
                                        @elseif($promo->status == 'rejected')
                                            <span class="inline-flex px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-[9px] font-black uppercase tracking-widest border border-rose-100">
                                                Rejected / Refunded
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[9px] font-black uppercase tracking-widest border border-slate-200">
                                                Expired
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-6 text-xs text-slate-600">
                                        {{ $promo->start_date ? $promo->start_date->format('M d, Y H:i') : 'Pending approval' }}
                                    </td>
                                    <td class="py-6 text-xs text-slate-600">
                                        {{ $promo->end_date ? $promo->end_date->format('M d, Y H:i') : 'Pending approval' }}
                                    </td>
                                    <td class="py-6 text-xs text-slate-400">
                                        {{ $promo->created_at->format('M d, Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-xs font-serif italic text-slate-400">
                                        No promotional requests registered for this event yet. Use the plans above to initiate showcase campaigns.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</x-organizer-layout>
