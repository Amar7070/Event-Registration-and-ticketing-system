<x-app-layout>
    <style>
        @keyframes scan {
            0%, 100% {
                transform: translateY(0);
                opacity: 0.3;
            }
            50% {
                transform: translateY(128px);
                opacity: 1;
                filter: drop-shadow(0 0 6px rgba(78, 125, 91, 0.8));
            }
        }
        .scanner-line {
            animation: scan 3s ease-in-out infinite;
        }
    </style>

    <div class="py-32 bg-[#FDFBF7] dark:bg-slate-950 min-h-screen px-4 md:px-8 relative overflow-hidden flex items-center justify-center">
        <!-- Ambient decorative background glow -->
        <div class="absolute top-1/4 left-1/4 w-[350px] h-[350px] rounded-full bg-primary/5 blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] rounded-full bg-accent-amber/5 blur-[120px] pointer-events-none"></div>

        <div class="max-w-4xl w-full space-y-8 relative z-10">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-4 px-2">
                <a href="{{ route('user.tickets.index') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-primary transition-colors flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    Back to collection
                </a>
            </div>

            @if(session('success'))
                <div class="bg-primary/10 border border-primary/20 text-primary px-6 py-4 rounded-2xl flex items-center gap-4 backdrop-blur-md">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                    <p class="text-sm font-bold tracking-tight">{{ session('success') }}</p>
                </div>
            @endif

            <!-- The Digital Pass -->
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.05)] dark:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.3)] rounded-[2.5rem] overflow-hidden flex flex-col transition-all duration-500 hover:border-primary/10">
                
                <!-- Pass Header -->
                <div class="relative h-72 md:h-80 overflow-hidden">
                    @if($ticket->event->getFirstMediaUrl('banners'))
                        <img src="{{ $ticket->event->getFirstMediaUrl('banners') }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    
                    <div class="absolute bottom-8 left-8 right-8">
                        <span class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-3.5 py-1.5 rounded-full text-[10px] font-bold tracking-widest uppercase mb-4 inline-block">{{ $ticket->ticketType->name }}</span>
                        <h1 class="text-2xl md:text-4xl font-serif text-white leading-tight font-medium">{{ $ticket->event->title }}</h1>
                    </div>
                </div>

                <!-- Pass Content -->
                <div class="p-8 md:p-12">
                    <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-12 lg:gap-16">
                        <!-- Left: Pass Details -->
                        <div class="space-y-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary/5 dark:bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                                        <i data-lucide="calendar" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Arrival</span>
                                        <span class="text-sm font-bold text-slate-900 dark:text-slate-100 block">{{ $ticket->event->start_date->format('l, M d, Y') }}</span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 block mt-0.5">{{ $ticket->event->start_date->format('g:i A') }}</span>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary/5 dark:bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Sanctuary</span>
                                        <span class="text-sm font-bold text-slate-900 dark:text-slate-100 block">{{ $ticket->event->venue ? $ticket->event->venue->name : 'Digital Realm' }}</span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 block mt-0.5">{{ $ticket->event->venue ? $ticket->event->venue->city : 'Online' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-slate-100 dark:border-slate-800/60 grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary/5 dark:bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                                        <i data-lucide="hash" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1.5">Identity Reference</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-primary font-mono select-all">{{ $ticket->booking_reference }}</span>
                                            <button onclick="navigator.clipboard.writeText('{{ $ticket->booking_reference }}'); const icon = this.querySelector('i'); icon.setAttribute('data-lucide', 'check'); lucide.createIcons(); setTimeout(() => { icon.setAttribute('data-lucide', 'copy'); lucide.createIcons(); }, 2000);" class="text-slate-400 hover:text-primary transition-colors focus:outline-none" title="Copy Reference">
                                                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary/5 dark:bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                                        <i data-lucide="users" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Quantity</span>
                                        <span class="text-sm font-bold text-slate-900 dark:text-slate-100 block">{{ $ticket->quantity }} x Individual Passes</span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 block mt-0.5">Admit {{ $ticket->quantity }} Person(s)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-slate-100 dark:border-slate-800/60">
                                <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3.5">Member Identity</span>
                                <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/30 p-4 rounded-2xl border border-slate-100/50 dark:border-slate-800/40">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4E7D5B&color=fff" class="w-11 h-11 rounded-xl shadow-sm">
                                    <div>
                                        <span class="text-sm font-bold text-slate-900 dark:text-slate-100 block leading-tight">{{ Auth::user()->name }}</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider block mt-1">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: QR, Pricing, & Actions -->
                        <div class="bg-cream/40 dark:bg-slate-800/20 rounded-[2rem] p-8 border border-slate-100 dark:border-slate-850 flex flex-col items-center text-center">
                            <!-- QR Scanner Display -->
                            <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-800 mb-6 relative group overflow-hidden">
                                @if($ticket->status === 'confirmed')
                                    <div class="relative w-36 h-36 bg-white p-2 rounded-2xl border border-slate-100/50 shadow-inner flex items-center justify-center overflow-hidden">
                                        <!-- QR Image -->
                                        <img src="{{ asset('storage/' . $ticket->qr_code_path) }}" alt="QR Code" class="w-full h-full object-contain relative z-10 transition-transform group-hover:scale-105 duration-500">
                                        
                                        <!-- Scan Line Overlay -->
                                        <div class="absolute left-0 right-0 h-0.5 bg-primary scanner-line opacity-80 z-20"></div>
                                        
                                        <!-- Ambient Scanner Glow -->
                                        <div class="absolute inset-0 bg-gradient-to-b from-primary/5 via-primary/0 to-primary/5 pointer-events-none z-15"></div>
                                    </div>
                                @else
                                    <div class="w-36 h-36 flex flex-col items-center justify-center text-slate-300 dark:text-slate-700 gap-3">
                                        <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center">
                                            <i data-lucide="lock" class="w-6 h-6 text-slate-400"></i>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-widest leading-normal text-slate-400 dark:text-slate-500">Locked until<br>payment</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mb-8">
                                <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1.5">Energy Exchanged</span>
                                <span class="text-3xl font-serif text-slate-900 dark:text-slate-100">₹{{ number_format($ticket->total_amount) }}</span>
                            </div>

                            <div class="w-full pt-6 border-t border-slate-100 dark:border-slate-800/80 flex flex-col gap-3">
                                @if($ticket->status === 'confirmed')
                                    <a href="{{ route('user.tickets.download', $ticket) }}" class="btn-primary w-full py-3.5 text-xs tracking-widest font-black uppercase flex items-center justify-center gap-2">
                                        <i data-lucide="download" class="w-4 h-4"></i> Download PDF Pass
                                    </a>
                                    
                                    @php
                                        $gCalStart = $ticket->event->start_date->copy()->setTimezone('UTC')->format('Ymd\THis\Z');
                                        $gCalEnd = $ticket->event->end_date 
                                            ? $ticket->event->end_date->copy()->setTimezone('UTC')->format('Ymd\THis\Z')
                                            : $ticket->event->start_date->copy()->addHours(2)->setTimezone('UTC')->format('Ymd\THis\Z');
                                        
                                        $gCalLocation = $ticket->event->venue 
                                            ? $ticket->event->venue->name . ', ' . $ticket->event->venue->city 
                                            : 'Digital Realm';
                                            
                                        $gCalDetails = "Pass Reference: " . $ticket->booking_reference . "\n" . "Thank you for reserving a passage. Present this digital pass at the entrance.";
                                        
                                        $googleCalendarUrl = "https://calendar.google.com/calendar/render?" . http_build_query([
                                            'action' => 'TEMPLATE',
                                            'text' => $ticket->event->title,
                                            'dates' => $gCalStart . '/' . $gCalEnd,
                                            'details' => $gCalDetails,
                                            'location' => $gCalLocation,
                                        ]);
                                    @endphp
                                    
                                    <a href="{{ $googleCalendarUrl }}" target="_blank" class="w-full py-3.5 rounded-full border border-primary/20 text-[10px] font-black uppercase tracking-widest text-primary bg-primary/5 hover:bg-primary hover:text-white transition-all duration-300 flex items-center justify-center gap-2">
                                        <i data-lucide="calendar-plus" class="w-4 h-4"></i> Add to Google Calendar
                                    </a>
                                @elseif($ticket->status === 'pending')
                                    <a href="{{ route('payments.checkout', $ticket->payment_id) }}" class="btn-primary w-full py-3.5 text-xs bg-amber-500 hover:bg-amber-600 border-amber-600 shadow-amber-500/20 flex items-center justify-center gap-2">
                                        <i data-lucide="credit-card" class="w-4 h-4"></i> Complete Exchange
                                    </a>
                                @endif

                                @if($ticket->ticketType->is_transferable && $ticket->status === 'confirmed')
                                    <button @click="document.getElementById('transfer-modal').classList.remove('hidden')" 
                                            class="w-full py-3.5 rounded-full border border-slate-200 dark:border-slate-800 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:border-primary hover:text-primary hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-300 flex items-center justify-center gap-2">
                                        <i data-lucide="share-2" class="w-4 h-4"></i> Transfer Ownership
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Perforated Edge Divider with side notches -->
                <div class="h-8 bg-cream dark:bg-slate-950 relative overflow-visible">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-[94%] border-t-2 border-dashed border-slate-200 dark:border-slate-800"></div>
                    </div>
                    <!-- Cutout circles on the left and right edges -->
                    <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-[#FDFBF7] dark:bg-[#020617] border border-slate-100 dark:border-slate-800/80 shadow-[inset_-3px_0_5px_rgba(0,0,0,0.02)]"></div>
                    <div class="absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-[#FDFBF7] dark:bg-[#020617] border border-slate-100 dark:border-slate-800/80 shadow-[inset_3px_0_5px_rgba(0,0,0,0.02)]"></div>
                </div>
                
                <!-- Barcode & Stub Footer -->
                <div class="p-10 bg-white dark:bg-slate-900 text-center flex flex-col items-center justify-center">
                    <!-- SVG Barcode Representation -->
                    <div class="flex items-center justify-center gap-[2px] opacity-70 dark:opacity-80 mb-4 h-12">
                        <div class="w-[3px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[1px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[4px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[2px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[1px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[3px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[1px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[2px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[4px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[1px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[3px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[2px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[4px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[1px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <!-- spacing -->
                        <div class="w-3 h-full bg-transparent"></div>
                        <!-- more bars -->
                        <div class="w-[2px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[4px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[1px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[3px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[2px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[1px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[4px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[2px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[3px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[1px] h-full bg-slate-900 dark:bg-slate-100"></div>
                        <div class="w-[4px] h-full bg-slate-900 dark:bg-slate-100"></div>
                    </div>
                    
                    <span class="text-[9px] font-mono tracking-[0.45em] uppercase text-slate-400 dark:text-slate-500 mb-4 block">
                        {{ $ticket->booking_reference }}
                    </span>

                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.25em]">
                        Present this pass at the sanctuary entrance for seamless entry.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer Modal -->
    <div id="transfer-modal" class="hidden fixed z-[100] inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('transfer-modal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-slate-800">
                <form action="{{ route('user.tickets.transfer', $ticket->booking_reference) }}" method="POST">
                    @csrf
                    <div class="p-10">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-cream dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-6 text-primary">
                                <i data-lucide="share-2" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-2xl font-serif text-slate-900 dark:text-slate-100 mb-4 font-semibold" id="modal-title">
                                Transfer Ownership
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 font-serif italic">
                                "Connection is better when shared." Enter the email of the person you want to transfer this pass to.
                            </p>
                            <div class="text-left space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Recipient Identity (Email)</label>
                                <input type="email" name="email" required placeholder="recipient@example.com" 
                                       class="form-input">
                            </div>
                        </div>
                    </div>
                    <div class="bg-cream/40 dark:bg-slate-800/20 p-8 border-t border-slate-100 dark:border-slate-800/60 flex flex-col gap-3">
                        <button type="submit" class="btn-primary w-full py-4 text-xs font-black uppercase tracking-widest">
                            Confirm Transfer
                        </button>
                        <button type="button" onclick="document.getElementById('transfer-modal').classList.add('hidden')" 
                                class="text-[10px] font-black text-slate-400 hover:text-slate-900 dark:hover:text-white uppercase tracking-widest py-2 transition-colors text-center w-full">
                            Cancel Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
