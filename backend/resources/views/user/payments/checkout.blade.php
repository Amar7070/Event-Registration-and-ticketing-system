<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Order Summary</h2>
                            <p class="text-slate-500 font-medium">Ref: {{ $payment->payment_reference }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-black text-indigo-600">₹{{ number_format($payment->amount, 2) }}</span>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Total Amount</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Event Details -->
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-16 h-16 rounded-lg bg-indigo-500 shrink-0 flex items-center justify-center">
                            <i data-lucide="calendar" class="text-white w-8 h-8"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">{{ $payment->event->title }}</h3>
                            <p class="text-sm text-slate-500">{{ $payment->event->start_date->format('l, d M Y') }}</p>
                            <p class="text-sm text-slate-500">{{ $payment->event->venue->name ?? 'Venue TBD' }}</p>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div>
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4">Select Payment Method</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-all group">
                                <input type="radio" name="gateway" value="stripe" class="hidden peer">
                                <div class="w-5 h-5 border-2 border-slate-300 rounded-full mr-3 peer-checked:border-indigo-600 peer-checked:bg-indigo-600"></div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">Stripe</span>
                                    <span class="text-xs text-slate-500">Cards, Apple Pay, Google Pay</span>
                                </div>
                                <div class="ml-auto opacity-20 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="credit-card" class="w-6 h-6 text-indigo-600"></i>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-all group">
                                <input type="radio" name="gateway" value="razorpay" class="hidden peer">
                                <div class="w-5 h-5 border-2 border-slate-300 rounded-full mr-3 peer-checked:border-indigo-600 peer-checked:bg-indigo-600"></div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">Razorpay</span>
                                    <span class="text-xs text-slate-500">UPI, NetBanking, Wallets</span>
                                </div>
                                <div class="ml-auto opacity-20 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="smartphone" class="w-6 h-6 text-indigo-600"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 border-t border-slate-100">
                    <button id="pay-button" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl shadow-xl shadow-slate-900/20 hover:bg-slate-800 transition-all flex items-center justify-center gap-2">
                        <span>Pay Now</span>
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-4 font-medium flex items-center justify-center gap-1">
                        <i data-lucide="lock" class="w-3 h-3"></i> Secure SSL Encrypted Checkout
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cinematic Success Overlay Modal -->
    <div id="success-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md opacity-0 transition-all duration-700">
        <div class="max-w-md w-full bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-2xl relative overflow-hidden transform scale-90 transition-all duration-700 flex flex-col items-center text-center space-y-6">
            
            <!-- Confetti Container -->
            <div id="confetti-container" class="absolute inset-0 pointer-events-none select-none overflow-hidden"></div>

            <!-- Glowing checkmark container -->
            <div class="relative w-24 h-24 flex items-center justify-center">
                <div class="absolute inset-0 bg-[#4E7D5B]/20 rounded-full blur-xl animate-pulse"></div>
                <svg class="w-20 h-20 text-[#4E7D5B]" viewBox="0 0 100 100">
                    <circle class="checkmark-circle" cx="50" cy="50" r="45" fill="none" stroke="#4E7D5B" stroke-width="6" stroke-linecap="round"/>
                    <path class="checkmark-check" fill="none" d="M30 52 l14 14 l28 -30" stroke="#4E7D5B" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <!-- Custom message -->
            <div class="space-y-2">
                <span class="status-pill bg-[#4E7D5B]/10 text-[#4E7D5B] border-[#4E7D5B]/20 tracking-widest uppercase text-[9px] font-black">
                    TRANSACTION COMPLETED
                </span>
                <h3 class="text-2xl font-serif text-slate-900 leading-tight">Now, the ticket is yours!</h3>
                <p class="text-slate-500 text-xs font-serif italic">"Your digital presence has been verified and registered within the experience node."</p>
            </div>

            <!-- Glowing Ticket Card Component -->
            <div class="w-full bg-[#1E293B] text-white rounded-3xl p-6 relative overflow-hidden shadow-xl border border-white/5 group">
                <div class="absolute -right-16 -top-16 w-32 h-32 bg-[#4E7D5B]/20 rounded-full blur-[40px] pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 w-32 h-32 bg-amber-500/10 rounded-full blur-[40px] pointer-events-none"></div>
                
                <div class="flex flex-col text-left space-y-4">
                    <div class="flex justify-between items-start border-b border-white/10 pb-4">
                        <div class="min-w-0">
                            <span class="text-[8px] font-black tracking-widest uppercase text-slate-400 block mb-0.5">EVENT PASS</span>
                            <h4 class="font-serif text-base text-white truncate max-w-[200px]">{{ $payment->event->title }}</h4>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-[8px] font-black tracking-widest uppercase text-slate-400 block mb-0.5">REFERENCE</span>
                            <span class="font-mono text-xs font-bold text-[#4E7D5B]" id="ticket-ref">#...</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-[8px] font-black tracking-widest uppercase text-slate-500 block mb-0.5">TEMPORAL NODE</span>
                            <span class="font-bold text-slate-200">{{ $payment->event->start_date->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-[8px] font-black tracking-widest uppercase text-slate-500 block mb-0.5">SPATIAL INDEX</span>
                            <span class="font-bold text-slate-200 truncate block max-w-[120px]">{{ $payment->event->venue->name ?? 'Venue TBD' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Abstract punched ticket circles -->
                <div class="absolute top-1/2 -left-3 w-6 h-6 bg-white rounded-full -translate-y-1/2"></div>
                <div class="absolute top-1/2 -right-3 w-6 h-6 bg-white rounded-full -translate-y-1/2"></div>
            </div>

            <!-- Action buttons -->
            <div class="w-full flex flex-col gap-3">
                <a id="view-ticket-btn" href="#" class="w-full btn-primary py-4 text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/20 text-center flex items-center justify-center gap-2">
                    <span>View My Ticket</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
                <a href="{{ route('dashboard') }}" class="w-full text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors py-2 block">
                    Return to Portal
                </a>
            </div>
        </div>
    </div>

    <style>
        .checkmark-circle {
            stroke-dasharray: 283;
            stroke-dashoffset: 283;
        }
        .checkmark-check {
            stroke-dasharray: 60;
            stroke-dashoffset: 60;
        }
        
        .modal-active .checkmark-circle {
            animation: draw-circle 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        .modal-active .checkmark-check {
            animation: draw-check 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.6s forwards;
        }
        
        @keyframes draw-circle {
            to {
                stroke-dashoffset: 0;
            }
        }
        @keyframes draw-check {
            to {
                stroke-dashoffset: 0;
            }
        }
        
        .confetti-piece {
            position: absolute;
            opacity: 0;
            transform-origin: center;
        }
        
        @keyframes confetti-explosion {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(0);
                opacity: 1;
            }
            80% {
                opacity: 0.8;
            }
            100% {
                transform: translate(var(--tx), var(--ty)) rotate(var(--rot)) scale(1);
                opacity: 0;
            }
        }
    </style>

    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            const selected = document.querySelector('input[name="gateway"]:checked');
            if (!selected) {
                alert('Please select a payment method');
                return;
            }
            
            // Mocking the payment process
            this.innerHTML = '<svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Securing Gateway Connection...';
            this.disabled = true;
            
            fetch("{{ route('payments.process') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_id: {{ $payment->id }},
                    gateway: selected.value
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update Ticket Information inside modal
                    document.getElementById('ticket-ref').innerText = '#' + (data.booking_reference || 'REF-TKT');
                    document.getElementById('view-ticket-btn').href = window.location.origin + '/my-tickets/' + (data.booking_reference || '');
                    
                    // Show success modal with beautiful fade-in and scaling
                    const modal = document.getElementById('success-modal');
                    const modalContent = modal.querySelector('div');
                    
                    modal.classList.remove('hidden');
                    
                    setTimeout(() => {
                        modal.classList.remove('opacity-0');
                        modal.classList.add('modal-active');
                        modalContent.classList.remove('scale-90');
                        modalContent.classList.add('scale-100');
                        
                        // Launch confetti particles!
                        launchConfetti();
                        lucide.createIcons();
                    }, 50);
                } else {
                    alert('Payment processing failed. Please try again.');
                    this.innerHTML = '<span>Pay Now</span><i data-lucide="arrow-right" class="w-5 h-5"></i>';
                    this.disabled = false;
                    lucide.createIcons();
                }
            })
            .catch(err => {
                alert('Connection index exception.');
                this.innerHTML = '<span>Pay Now</span><i data-lucide="arrow-right" class="w-5 h-5"></i>';
                this.disabled = false;
                lucide.createIcons();
            });
        });
        
        function launchConfetti() {
            const container = document.getElementById('confetti-container');
            const colors = ['#4E7D5B', '#F59E0B', '#14B8A6', '#10B981', '#3B82F6', '#EC4899'];
            
            for (let i = 0; i < 45; i++) {
                const particle = document.createElement('div');
                particle.className = 'confetti-piece';
                
                // Randomize style
                const color = colors[Math.floor(Math.random() * colors.length)];
                const size = Math.random() * 8 + 4;
                const isRound = Math.random() > 0.5;
                
                particle.style.backgroundColor = color;
                particle.style.width = size + 'px';
                particle.style.height = (isRound ? size : size * 0.4) + 'px';
                particle.style.borderRadius = isRound ? '50%' : '2px';
                
                // Target coordinates for explosion
                const angle = Math.random() * Math.PI * 2;
                const distance = Math.random() * 250 + 100;
                const tx = Math.cos(angle) * distance;
                const ty = Math.sin(angle) * distance - 50;
                const rot = Math.random() * 720;
                
                particle.style.setProperty('--tx', tx + 'px');
                particle.style.setProperty('--ty', ty + 'px');
                particle.style.setProperty('--rot', rot + 'deg');
                
                // Random position starting from the center of the checkmark
                particle.style.left = '50%';
                particle.style.top = '100px';
                
                // Animation settings
                const delay = Math.random() * 0.2;
                const duration = Math.random() * 1.5 + 1.2;
                particle.style.animation = `confetti-explosion ${duration}s cubic-bezier(0.1, 0.8, 0.3, 1) ${delay}s forwards`;
                
                container.appendChild(particle);
            }
        }
    </script>
</x-app-layout>
