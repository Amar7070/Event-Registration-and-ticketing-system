<x-app-layout>
    <div class="py-24 bg-[#FDFBF7] dark:bg-slate-950 min-h-screen relative overflow-hidden">
        <!-- Subtle Ambient Glow Orbs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[10%] left-[5%] w-[600px] h-[600px] bg-[#4E7D5B]/5 rounded-full blur-[140px]"></div>
            <div class="absolute top-[40%] right-[2%] w-[500px] h-[500px] bg-amber-500/2 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <!-- Header -->
            <div class="mb-16">
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-[#4E7D5B]/5 border border-[#4E7D5B]/10 mb-6 shadow-sm shadow-[#4E7D5B]/2">
                    <span class="w-2 h-2 rounded-full bg-[#4E7D5B] animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-[#4E7D5B]">SECURE RESERVATION NODE</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-serif tracking-tight text-slate-900 dark:text-white leading-[1.1] mb-6">
                    Complete your <span class="italic text-[#4E7D5B] relative font-normal">booking.<span class="absolute bottom-2 left-0 w-full h-2 bg-[#4E7D5B]/5"></span></span>
                </h1>
                <p class="text-lg text-slate-500 font-serif italic max-w-2xl">
                    "Every reservation creates a new connection point within the ecosystem."
                </p>
            </div>

            @if(session('error'))
                <div class="mb-10 bg-rose-50 border border-rose-100 p-6 rounded-3xl text-sm text-rose-800 flex items-start gap-4">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-500 shrink-0"></i>
                    <div>
                        <span class="font-bold block mb-1">Reservation Exception</span>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('events.book.store', $event) }}" method="POST" id="booking-form">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-16 items-start">
                    
                    <!-- Left Column: Options (Ticket types, Quantity, Voucher) -->
                    <div class="lg:col-span-2 space-y-12">
                        
                        <!-- Ticket Selection -->
                        <div class="space-y-6">
                            <h3 class="text-2xl font-serif text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800">
                                Select Ticket Category
                            </h3>
                            
                            <div class="space-y-4">
                                @foreach($event->ticketTypes as $ticket)
                                    @php 
                                        $isAvailable = $ticket->quantity_sold < $ticket->quantity_total;
                                        $isSaleStarted = !$ticket->sale_starts_at || now()->gte($ticket->sale_starts_at);
                                        $isSaleEnded = $ticket->sale_ends_at && now()->gt($ticket->sale_ends_at);
                                        $canBuy = $isAvailable && $isSaleStarted && !$isSaleEnded;
                                        
                                        $isRequested = request('ticket_type_id') == $ticket->id;
                                        $isFirstBuyable = !request()->has('ticket_type_id') && $canBuy && !isset($firstSelected);
                                        $shouldSelect = ($isRequested || $isFirstBuyable) && $canBuy;
                                        if ($shouldSelect) {
                                            $firstSelected = true;
                                        }
                                    @endphp
                                    <label class="relative block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 cursor-pointer hover:border-[#4E7D5B] dark:hover:border-[#4E7D5B] focus-within:ring-2 focus-within:ring-[#4E7D5B] transition-all duration-300 shadow-sm {{ !$canBuy ? 'opacity-50 bg-slate-50' : '' }}">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                            <div class="flex items-start gap-4">
                                                <div class="flex items-center h-6 shrink-0">
                                                    <input type="radio" 
                                                           name="ticket_type_id" 
                                                           value="{{ $ticket->id }}" 
                                                           data-name="{{ $ticket->name }}" 
                                                           data-price="{{ $ticket->price }}" 
                                                           class="h-5 w-5 text-[#4E7D5B] border-slate-300 focus:ring-[#4E7D5B] dark:bg-slate-800 dark:border-slate-700" 
                                                           {{ !$canBuy ? 'disabled' : '' }} 
                                                           {{ $shouldSelect ? 'checked' : '' }} 
                                                           required 
                                                           onchange="updateTotal({{ $ticket->price }})">
                                                </div>
                                                <div>
                                                    <span class="block text-lg font-bold text-slate-900 dark:text-white">{{ $ticket->name }}</span>
                                                    <span class="block text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed font-serif italic max-w-lg">{{ $ticket->description ?? ucfirst($ticket->type) }}</span>
                                                </div>
                                            </div>
                                            <div class="text-left sm:text-right shrink-0">
                                                @if($ticket->price > 0)
                                                    <span class="block text-2xl font-serif text-[#4E7D5B] font-bold">₹{{ number_format($ticket->price, 2) }}</span>
                                                    @if($ticket->original_price)
                                                        <span class="block text-xs text-slate-400 line-through mt-1">₹{{ number_format($ticket->original_price, 2) }}</span>
                                                    @endif
                                                @else
                                                    <span class="block text-2xl font-serif text-emerald-600 font-bold">Free</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if(!$canBuy)
                                            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-[10px] font-black uppercase tracking-wider text-rose-500">
                                                @if(!$isAvailable)
                                                    SOLD OUT
                                                @elseif(!$isSaleStarted)
                                                    SALE STARTS {{ $ticket->sale_starts_at->format('M d, Y') }}
                                                @elseif($isSaleEnded)
                                                    SALES ENDED
                                                @endif
                                            </div>
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Quantity and Coupon Code -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                            <div>
                                <label for="quantity" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Nodes / Quantity</label>
                                <div class="relative">
                                    <select name="quantity" id="quantity" required class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-[#4E7D5B] dark:focus:border-[#4E7D5B] focus:ring-0 text-sm font-bold text-slate-800 dark:text-slate-200 py-4 pl-6 pr-12 rounded-2xl cursor-pointer appearance-none transition" onchange="updateTotal(null)">
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ request('quantity') == $i ? 'selected' : '' }}>{{ $i }} {{ $i > 1 ? 'NODES' : 'NODE' }}</option>
                                        @endfor
                                    </select>
                                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="coupon_code" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Promo / Coupon Code</label>
                                <div class="flex rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden focus-within:border-[#4E7D5B] transition">
                                    <input type="text" 
                                           name="coupon_code" 
                                           id="coupon_code" 
                                           class="flex-1 min-w-0 block w-full px-5 py-4 bg-transparent border-0 focus:ring-0 text-sm font-bold text-slate-800 dark:text-slate-200 uppercase font-mono placeholder:text-slate-300 placeholder:normal-case" 
                                           placeholder="Enter coupon code">
                                    <button type="button" onclick="applyCoupon()" class="px-6 py-4 bg-[#4E7D5B]/5 hover:bg-[#4E7D5B]/10 text-[#4E7D5B] text-xs font-black uppercase tracking-wider transition border-l border-slate-100 dark:border-slate-800">
                                        Apply
                                    </button>
                                </div>
                                
                                <!-- Coupon Feedback Alert -->
                                <div id="coupon-feedback" class="mt-3 hidden text-xs font-semibold px-4 py-3 rounded-2xl border transition-all"></div>

                                <!-- Available Vouchers Grid -->
                                @if(isset($availableCoupons) && $availableCoupons->count() > 0)
                                <div class="mt-5 space-y-2">
                                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Available Vouchers:</span>
                                    <div class="flex flex-wrap gap-2.5">
                                        @foreach($availableCoupons as $avCoupon)
                                        <button type="button" onclick="selectCoupon('{{ $avCoupon->code }}')" class="inline-flex flex-col items-start px-4 py-2.5 bg-white dark:bg-slate-900 hover:bg-[#4E7D5B]/5 border border-slate-200 dark:border-slate-800 hover:border-[#4E7D5B]/30 rounded-2xl transition text-left group shrink-0 shadow-sm">
                                            <span class="font-mono font-black text-xs text-slate-800 dark:text-slate-200 tracking-wider group-hover:text-[#4E7D5B]">{{ $avCoupon->code }}</span>
                                            <span class="text-[9px] text-[#4E7D5B] font-bold mt-1 flex items-center gap-1.5">
                                                @if($avCoupon->type === 'percentage')
                                                    {{ number_format($avCoupon->value, 0) }}% OFF
                                                @else
                                                    ₹{{ number_format($avCoupon->value, 0) }} OFF
                                                @endif
                                            </span>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Buyer Information -->
                        <div class="premium-card bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2rem] shadow-sm">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Buyer Verification</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                                <div>
                                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Name</span>
                                    <span class="block font-bold text-slate-850 dark:text-slate-100">{{ Auth::user()->name }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Email Coordinate</span>
                                    <span class="block font-bold text-slate-850 dark:text-slate-100">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                            <p class="mt-6 text-xs text-slate-400 font-serif italic">Permits and ticket codes will be dispatched to this email coordinate.</p>
                        </div>

                    </div>

                    <!-- Right Column: Sticky Summary -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-32 space-y-8">
                            <div class="premium-card bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] shadow-xl relative overflow-hidden">
                                <!-- Banner Header -->
                                <div class="h-40 w-full overflow-hidden relative">
                                    <img src="{{ $event->getFirstMediaUrl('banners') ?: 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=2000' }}" 
                                         class="w-full h-full object-cover" 
                                         alt="{{ $event->title }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-slate-900 via-white/20 dark:via-slate-900/20 to-transparent"></div>
                                    <div class="absolute bottom-4 left-6">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#4E7D5B] text-white text-[9px] font-black uppercase tracking-widest shadow-lg">
                                            <i data-lucide="tag" class="w-3 h-3"></i>
                                            {{ $event->category->name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-8 relative z-10 space-y-6">
                                    <div>
                                        <h3 class="text-xl font-serif text-slate-900 dark:text-white mb-2 leading-tight">{{ $event->title }}</h3>
                                        <p class="text-xs text-slate-450 font-medium flex items-center gap-1.5 dark:text-slate-400">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#4E7D5B]"></i>
                                            {{ $event->venue ? $event->venue->name : 'Global Ecosystem (Online)' }}
                                        </p>
                                    </div>

                                    <div class="border-t border-slate-100 dark:border-slate-800 pt-6 space-y-4">
                                        <!-- Selected Ticket Display -->
                                        <div class="flex justify-between items-start text-sm gap-4">
                                            <div>
                                                <span class="block text-sm font-bold text-slate-800 dark:text-slate-200" id="summary-ticket-name">-</span>
                                                <span class="block text-[10px] text-slate-400 uppercase tracking-widest mt-1" id="summary-ticket-qty">0 Nodes</span>
                                            </div>
                                            <span class="font-serif text-slate-950 dark:text-white font-bold" id="summary-ticket-subtotal">₹0.00</span>
                                        </div>

                                        <!-- Applied Discount Display -->
                                        <div class="flex justify-between items-center text-sm hidden" id="summary-discount-row">
                                            <span class="text-xs text-[#4E7D5B] font-bold uppercase tracking-wider">Discount Code</span>
                                            <span class="text-[#4E7D5B] font-bold font-serif" id="summary-discount-val">-₹0.00</span>
                                        </div>
                                        
                                        <!-- Total Display -->
                                        <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-between items-end">
                                            <div>
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">TOTAL COST</span>
                                                <span class="text-xs font-serif italic text-slate-500">Service inclusive</span>
                                            </div>
                                            <div class="text-right" id="total-display">
                                                <span class="block text-3xl font-serif font-bold text-slate-900 dark:text-white">₹0.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full bg-[#4E7D5B] hover:bg-[#3D6449] text-white py-5 px-6 rounded-full text-xs font-black uppercase tracking-[0.25em] transition-all duration-300 shadow-lg shadow-[#4E7D5B]/20 active:scale-95">
                                        PROCEED TO PAYMENT &rarr;
                                    </button>

                                    <div class="flex items-center justify-center gap-2 text-[9px] font-black text-slate-300 dark:text-slate-700 uppercase tracking-widest pt-2">
                                        <i data-lucide="lock" class="w-3.5 h-3.5 text-[#4E7D5B]"></i>
                                        SECURED BY GROUNDED ENTERPRISE
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @php
        $selectedTicketType = null;
        if (request()->has('ticket_type_id')) {
            $selectedTicketType = $event->ticketTypes->first(fn($t) => $t->id == request('ticket_type_id'));
        }
        if (!$selectedTicketType) {
            foreach ($event->ticketTypes as $ticket) {
                $isAvailable = $ticket->quantity_sold < $ticket->quantity_total;
                $isSaleStarted = !$ticket->sale_starts_at || now()->gte($ticket->sale_starts_at);
                $isSaleEnded = $ticket->sale_ends_at && now()->gt($ticket->sale_ends_at);
                if ($isAvailable && $isSaleStarted && !$isSaleEnded) {
                    $selectedTicketType = $ticket;
                    break;
                }
            }
        }
        if (!$selectedTicketType) {
            $selectedTicketType = $event->ticketTypes->first();
        }
        $initialPrice = $selectedTicketType ? $selectedTicketType->price : 0;
    @endphp

    <script>
        let selectedPrice = {{ $initialPrice }};
        let discountAmount = 0;
        let appliedCoupon = null;

        document.addEventListener('DOMContentLoaded', function() {
            updateTotalDisplay();
            lucide.createIcons();
        });

        function selectCoupon(code) {
            document.getElementById('coupon_code').value = code;
            applyCoupon();
        }

        async function applyCoupon() {
            const codeInput = document.getElementById('coupon_code');
            const code = codeInput.value.trim();
            const feedbackEl = document.getElementById('coupon-feedback');
            
            if (!code) {
                // Reset coupon if empty
                discountAmount = 0;
                appliedCoupon = null;
                feedbackEl.classList.add('hidden');
                updateTotalDisplay();
                return;
            }

            // Must select a ticket first
            const selectedTicketRadio = document.querySelector('input[name="ticket_type_id"]:checked');
            if (!selectedTicketRadio) {
                feedbackEl.className = "mt-3 text-xs font-semibold px-4 py-3 rounded-2xl border bg-amber-50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-900/50 text-amber-800 dark:text-amber-300";
                feedbackEl.innerText = "Please select a ticket type before applying the coupon.";
                feedbackEl.classList.remove('hidden');
                codeInput.value = '';
                return;
            }

            const ticketTypeId = selectedTicketRadio.value;
            const quantity = parseInt(document.getElementById('quantity').value) || 1;

            try {
                feedbackEl.className = "mt-3 text-xs font-semibold px-4 py-3 rounded-2xl border bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-500 animate-pulse";
                feedbackEl.innerText = "Validating incentive node...";
                feedbackEl.classList.remove('hidden');

                const response = await fetch("{{ route('events.validate-coupon', $event) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        coupon_code: code,
                        ticket_type_id: ticketTypeId,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (response.ok && data.valid) {
                    discountAmount = parseFloat(data.discount);
                    appliedCoupon = data;
                    feedbackEl.className = "mt-3 text-xs font-semibold px-4 py-3 rounded-2xl border bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50 text-emerald-800 dark:text-emerald-300 flex items-center justify-between";
                    feedbackEl.innerHTML = `
                        <span>${data.message}</span>
                        <button type="button" onclick="clearCoupon()" class="text-emerald-700 dark:text-emerald-400 hover:underline font-black uppercase tracking-wider ml-2">Remove</button>
                    `;
                } else {
                    discountAmount = 0;
                    appliedCoupon = null;
                    feedbackEl.className = "mt-3 text-xs font-semibold px-4 py-3 rounded-2xl border bg-rose-50 dark:bg-rose-950/20 border-rose-200 dark:border-rose-900/50 text-rose-800 dark:text-rose-300";
                    feedbackEl.innerText = data.message || "Invalid coupon code.";
                }
            } catch (error) {
                discountAmount = 0;
                appliedCoupon = null;
                feedbackEl.className = "mt-3 text-xs font-semibold px-4 py-3 rounded-2xl border bg-rose-50 dark:bg-rose-950/20 border-rose-200 dark:border-rose-900/50 text-rose-800 dark:text-rose-300";
                feedbackEl.innerText = "Could not validate coupon. Please try again.";
            }

            updateTotalDisplay();
        }

        function clearCoupon() {
            document.getElementById('coupon_code').value = '';
            discountAmount = 0;
            appliedCoupon = null;
            document.getElementById('coupon-feedback').classList.add('hidden');
            updateTotalDisplay();
        }

        function updateTotal(price) {
            if (price !== null) {
                selectedPrice = parseFloat(price);
            }
            
            // If coupon is already applied, re-apply it to update discount based on quantity / new ticket type
            if (document.getElementById('coupon_code').value.trim()) {
                applyCoupon();
            } else {
                updateTotalDisplay();
            }
        }

        function updateTotalDisplay() {
            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            const subtotal = selectedPrice * quantity;
            const total = Math.max(0, subtotal - discountAmount);

            // Read metadata from selected ticket radio
            const checkedRadio = document.querySelector('input[name="ticket_type_id"]:checked');
            let ticketName = "-";
            if (checkedRadio) {
                ticketName = checkedRadio.getAttribute('data-name') || "-";
            }

            // Update Left/Right Summary details
            document.getElementById('summary-ticket-name').innerText = ticketName;
            document.getElementById('summary-ticket-qty').innerText = quantity + (quantity > 1 ? ' Nodes' : ' Node');
            document.getElementById('summary-ticket-subtotal').innerText = '₹' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            const discountRowEl = document.getElementById('summary-discount-row');
            if (discountAmount > 0) {
                discountRowEl.classList.remove('hidden');
                document.getElementById('summary-discount-val').innerText = '-₹' + discountAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            } else {
                discountRowEl.classList.add('hidden');
            }

            const displayEl = document.getElementById('total-display');
            
            if (subtotal === 0) {
                displayEl.innerHTML = '<span class="text-emerald-600 font-bold text-3xl font-serif">Free</span>';
            } else if (discountAmount > 0) {
                displayEl.innerHTML = `
                    <div class="text-right">
                        <span class="block text-xs text-slate-400 line-through">₹${subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                        <span class="block text-xs text-emerald-600 font-semibold">- ₹${discountAmount.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                        <span class="block text-3xl font-serif font-bold text-slate-900 dark:text-white mt-1">₹${total.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                    </div>
                `;
            } else {
                displayEl.innerHTML = `<span class="block text-3xl font-serif font-bold text-slate-900 dark:text-white">₹${total.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>`;
            }
        }
    </script>
</x-app-layout>
