<x-app-layout>
    <div x-data="{ 
        showReportModal: false,
        activeTab: 'about',
        selectedType: {{ $event->ticketTypes->first()->id ?? 'null' }}, 
        quantity: 1,
        ticketTypes: {
            @foreach($event->ticketTypes as $type)
                '{{ $type->id }}': { price: {{ $type->price }}, name: '{{ addslashes($type->name) }}' },
            @endforeach
        },
        get currentTicket() {
            return this.ticketTypes[this.selectedType] || { price: 0, name: '' };
        },
        get totalCost() {
            return this.currentTicket.price * this.quantity;
        },
        timeLeft: '',
        init() {
            const target = new Date('{{ $event->start_date->toIso8601String() }}').getTime();
            const updateTime = () => {
                const now = new Date().getTime();
                const diff = target - now;
                if (diff < 0) {
                    this.timeLeft = 'Started';
                    return;
                }
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                this.timeLeft = `${days}d ${hours}h ${minutes}m`;
            };
            updateTime();
            setInterval(updateTime, 60000);
        }
    }" class="min-h-screen bg-slate-50/50 dark:bg-slate-950">

        <!-- Cinematic Hero Header -->
        <div class="relative min-h-[50vh] md:min-h-[60vh] lg:min-h-[65vh] w-full flex items-center bg-slate-950 overflow-hidden">
            <!-- Background Image Blurry Backing -->
            <div class="absolute inset-0 bg-cover bg-center scale-110 blur-xl opacity-30 select-none"
                 style="background-image: url('{{ $event->getFirstMediaUrl('banners') ?: 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=1600' }}');">
            </div>
            
            <!-- Dark Vignette Overlays -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-transparent to-transparent"></div>

            <div class="max-w-7xl mx-auto w-full px-6 md:px-12 relative z-10 py-16">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Left: Large Cinematic Cover Frame -->
                    <div class="lg:col-span-4 hidden lg:block">
                        <div class="aspect-[3/4] w-full bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 group relative">
                            <img src="{{ $event->getFirstMediaUrl('banners') ?: 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=1000' }}" 
                                 class="w-full h-full object-cover transition-transform duration-[2000ms] group-hover:scale-110" 
                                 alt="{{ $event->title }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        </div>
                    </div>

                    <!-- Right: Core Metadata & Title -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-[#4E7D5B] text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/20">
                                <i data-lucide="sprout" class="w-3.5 h-3.5"></i>
                                {{ $event->category->name }}
                            </span>
                            
                            <template x-if="timeLeft && timeLeft !== 'Started'">
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-[0.2em] border border-white/15">
                                    <i data-lucide="timer" class="w-3.5 h-3.5 text-[#4E7D5B]"></i>
                                    Countdown: <span x-text="timeLeft" class="ml-1 text-white font-mono"></span>
                                </span>
                            </template>
                        </div>

                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif text-white leading-[1.1] tracking-tight max-w-4xl">
                            {{ $event->title }}
                        </h1>

                        <!-- Organizer and Basic Quick Specs -->
                        <div class="flex flex-wrap items-center gap-8 pt-4">
                            <!-- Organizer Details -->
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($event->organizer->name) }}&background=4E7D5B&color=fff" 
                                         class="w-11 h-11 rounded-xl border border-white/20 shadow-md">
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-[#4E7D5B] rounded-full border border-slate-950 flex items-center justify-center">
                                        <i data-lucide="verified" class="w-2 h-2 text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-450 uppercase tracking-widest leading-none mb-1">Architect</span>
                                    <span class="text-sm font-bold text-white leading-none">{{ $event->organizer->name }}</span>
                                </div>
                            </div>

                            <div class="h-8 w-[1px] bg-white/10 hidden sm:block"></div>

                            <!-- Basic Specifications -->
                            <div class="flex items-center gap-3 text-slate-300">
                                <i data-lucide="map-pin" class="w-5 h-5 text-[#4E7D5B]"></i>
                                <span class="text-sm font-medium">{{ $event->venue ? $event->venue->city : 'Global (Online)' }}</span>
                            </div>

                            <div class="h-8 w-[1px] bg-white/10 hidden sm:block"></div>

                            <div class="flex items-center gap-3 text-slate-300">
                                <i data-lucide="calendar" class="w-5 h-5 text-[#4E7D5B]"></i>
                                <span class="text-sm font-medium">{{ $event->start_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Main Workspace Grid -->
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                
                <!-- Left: Interactive Content Area -->
                <div class="lg:col-span-8 space-y-12">
                    
                    <!-- Modern Navigation Tabs -->
                    <div class="border-b border-slate-200 dark:border-slate-800 flex gap-8 overflow-x-auto no-scrollbar">
                        <button @click="activeTab = 'about'" 
                                :class="activeTab === 'about' ? 'border-[#4E7D5B] text-[#4E7D5B] dark:text-white' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300'"
                                class="pb-4 border-b-2 font-bold text-xs uppercase tracking-widest transition-all shrink-0">
                            Overview
                        </button>
                        @if($event->sessions->count() > 0)
                        <button @click="activeTab = 'sessions'" 
                                :class="activeTab === 'sessions' ? 'border-[#4E7D5B] text-[#4E7D5B] dark:text-white' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300'"
                                class="pb-4 border-b-2 font-bold text-xs uppercase tracking-widest transition-all shrink-0">
                            Timeline ({{ $event->sessions->count() }})
                        </button>
                        @endif
                        @if($event->venue)
                        <button @click="activeTab = 'venue'" 
                                :class="activeTab === 'venue' ? 'border-[#4E7D5B] text-[#4E7D5B] dark:text-white' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300'"
                                class="pb-4 border-b-2 font-bold text-xs uppercase tracking-widest transition-all shrink-0">
                            Coordinates
                        </button>
                        @endif
                        <button @click="activeTab = 'reviews'" 
                                :class="activeTab === 'reviews' ? 'border-[#4E7D5B] text-[#4E7D5B] dark:text-white' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300'"
                                class="pb-4 border-b-2 font-bold text-xs uppercase tracking-widest transition-all shrink-0">
                            Resonance ({{ $event->reviews()->approved()->count() }})
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="space-y-8">
                        
                        <!-- TAB: Overview -->
                        <div x-show="activeTab === 'about'" x-transition class="space-y-8">
                            <div class="prose prose-slate dark:prose-invert max-w-none text-slate-650 dark:text-slate-350 leading-[1.8] text-base md:text-lg font-sans">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        </div>

                        <!-- TAB: Timeline / Sessions -->
                        @if($event->sessions->count() > 0)
                        <div x-show="activeTab === 'sessions'" x-transition class="space-y-6">
                            <div class="relative border-l-2 border-slate-100 dark:border-slate-800 ml-4 pl-8 space-y-12">
                                @foreach($event->sessions as $session)
                                    <div class="relative group">
                                        <!-- Timeline Node Bullet -->
                                        <div class="absolute -left-[41px] top-1.5 w-5 h-5 rounded-full bg-slate-50 dark:bg-slate-950 border-4 border-[#4E7D5B] group-hover:scale-125 transition-transform duration-300 z-10"></div>
                                        
                                        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                                            <div class="flex flex-wrap items-center justify-between gap-4 mb-3 pb-3 border-b border-slate-50 dark:border-slate-800">
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black bg-[#4E7D5B]/10 text-[#4E7D5B] uppercase tracking-wider">
                                                        {{ $session->start_time->format('H:i A') }}
                                                    </span>
                                                    <span class="text-xs text-slate-400 font-medium">Session Segment</span>
                                                </div>
                                            </div>
                                            <h4 class="text-lg font-serif text-slate-900 dark:text-white group-hover:text-[#4E7D5B] transition-colors mb-2 font-bold">{{ $session->title }}</h4>
                                            <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-4">{{ $session->description }}</p>
                                            
                                            @if($session->speaker)
                                                <div class="inline-flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($session->speaker->name) }}&background=4E7D5B&color=fff" class="w-8 h-8 rounded-lg">
                                                    <div>
                                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block leading-none mb-0.5">Speaker</span>
                                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-widest">{{ $session->speaker->name }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- TAB: Coordinates / Venue -->
                        @if($event->venue)
                        <div x-show="activeTab === 'venue'" x-transition class="space-y-6">
                            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850 p-8 rounded-3xl shadow-sm space-y-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-serif font-bold text-slate-900 dark:text-white leading-tight">{{ $event->venue->name }}</h4>
                                        <p class="text-xs text-slate-450 dark:text-slate-400 font-sans mt-0.5">{{ $event->venue->address }}, {{ $event->venue->city }}</p>
                                    </div>
                                </div>

                                <div class="aspect-[16/6] w-full rounded-2xl overflow-hidden relative group border border-slate-100 dark:border-slate-800">
                                    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&q=80&w=1200')] bg-cover bg-center grayscale opacity-60 group-hover:grayscale-0 transition-all duration-700"></div>
                                    <div class="absolute inset-0 bg-[#4E7D5B]/5 mix-blend-multiply"></div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <a href="https://maps.google.com/?q={{ urlencode($event->venue->address) }}" target="_blank" class="px-6 py-3 rounded-full bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-[10px] font-black text-[#4E7D5B] uppercase tracking-widest hover:scale-105 shadow-xl transition-all duration-300">
                                            Open in Google Maps &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- TAB: Resonance / Reviews -->
                        <div x-show="activeTab === 'reviews'" x-transition class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850 p-8 rounded-3xl shadow-sm">
                                <div class="text-center md:border-r border-slate-100 dark:border-slate-800 md:pr-6 py-4">
                                    <div class="text-5xl font-serif font-bold text-slate-900 dark:text-white mb-2">{{ number_format($event->reviews()->approved()->avg('rating'), 1) ?: '0.0' }}</div>
                                    <div class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Aggregate Energy</div>
                                </div>
                                <div class="col-span-2 flex flex-col items-center justify-center gap-3 py-4">
                                    <div class="flex text-amber-450 gap-1.5">
                                        @for($i=1; $i<=5; $i++)
                                            <i data-lucide="star" class="w-6 h-6 {{ $i <= $event->reviews()->approved()->avg('rating') ? 'fill-current text-amber-450' : 'opacity-20 text-slate-350' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-slate-450 dark:text-slate-400 font-medium">Based on {{ $event->reviews()->approved()->count() }} verified ecosystem resonances</span>
                                </div>
                            </div>

                            <!-- Review form and notices -->
                            @auth
                                @php
                                    $hasTicket = $event->tickets()->where('user_id', Auth::id())->where('status', 'confirmed')->exists();
                                    $hasEnded = $event->end_date <= now();
                                    $pendingReview = $event->reviews()->where('user_id', Auth::id())->where('is_approved', false)->first();
                                @endphp

                                @if($pendingReview)
                                    <!-- Pending Review Notice -->
                                    <div class="bg-amber-500/5 border border-amber-500/10 p-6 rounded-3xl mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="space-y-2">
                                            <span class="inline-flex items-center gap-1.5 bg-amber-500/15 text-amber-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest leading-none">Pending Moderation</span>
                                            <h4 class="text-base font-bold text-slate-800 dark:text-slate-200">Your Resonance Review is Awaiting Approval</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 italic">"{{ $pendingReview->comment }}"</p>
                                        </div>
                                        <div class="flex text-amber-400 gap-0.5 shrink-0">
                                            @for($i=1; $i<=5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ $i <= $pendingReview->rating ? 'fill-current text-amber-400' : 'opacity-20 text-slate-350' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                @elseif($hasTicket && $hasEnded)
                                    <!-- Review Submission Form -->
                                    <div x-data="{ userRating: 5, hoverRating: 0 }" class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850 p-8 rounded-3xl shadow-sm mb-8">
                                        <h4 class="text-lg font-serif font-bold text-slate-900 dark:text-white mb-2">Leave a Resonance Review</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Describe the atmosphere and your experience to the community.</p>
                                        
                                        <form action="{{ route('reviews.store', $event) }}" method="POST" class="space-y-6">
                                            @csrf
                                            <!-- Star selection -->
                                            <div class="space-y-2">
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Your Rating</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="hidden" name="rating" :value="userRating">
                                                    <template x-for="star in [1, 2, 3, 4, 5]">
                                                        <button type="button" 
                                                                @click="userRating = star" 
                                                                @mouseenter="hoverRating = star" 
                                                                @mouseleave="hoverRating = 0"
                                                                class="text-amber-400 focus:outline-none transition-transform active:scale-90 duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" 
                                                                 :class="(hoverRating ? star <= hoverRating : star <= userRating) ? 'text-amber-400 fill-amber-400' : 'text-slate-200 dark:text-slate-800'"
                                                                 class="w-8 h-8 transition-all" 
                                                                 viewBox="0 0 24 24" 
                                                                 fill="none" 
                                                                 stroke="currentColor" 
                                                                 stroke-width="2" 
                                                                 stroke-linecap="round" 
                                                                 stroke-linejoin="round">
                                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                            </svg>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <!-- Review text -->
                                            <div class="space-y-2">
                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Your Comment</label>
                                                <textarea name="comment" rows="4" required placeholder="Share your experience with the ecosystem..." class="form-input"></textarea>
                                            </div>

                                            <!-- Submit button -->
                                            <button type="submit" class="btn-primary px-8 py-3 text-xs font-black uppercase tracking-widest">
                                                Submit Resonance Review
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <!-- Information Banner for Unavailable Reviews -->
                                    <div class="bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl mb-8 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                                            <i data-lucide="info" class="w-5 h-5 text-primary"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Resonance Rating Guidelines</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                                                @if(!$hasEnded)
                                                    Reviews will open once this experience has concluded (Event end date: {{ $event->end_date->format('M d, Y g:i A') }}).
                                                @elseif(!$hasTicket)
                                                    Only verified participants who reserved a ticket for this experience are eligible to leave reviews.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <!-- Guest Info Banner -->
                                <div class="bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl mb-8 flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                                        <i data-lucide="log-in" class="w-5 h-5 text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Submit a Resonance Review</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                                            Please <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">login</a> to participate. Review submissions are restricted to verified ticket holders after the experience has concluded.
                                        </p>
                                    </div>
                                </div>
                            @endauth

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @forelse($event->reviews()->approved()->with('user')->latest()->get() as $review)
                                    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850 p-6 rounded-2xl relative shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex items-center gap-3 mb-4">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=4E7D5B&color=fff" class="w-10 h-10 rounded-xl">
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-xs">{{ $review->user->name }}</div>
                                                <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <div class="flex text-amber-400 mb-3 gap-0.5">
                                            @for($i=1; $i<=5; $i++)
                                                <i data-lucide="star" class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current text-amber-400' : 'opacity-20 text-slate-350' }}" />
                                            @endfor
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-350 text-xs md:text-sm leading-relaxed italic font-serif">"{{ $review->comment }}"</p>
                                    </div>
                                @empty
                                    <div class="col-span-full py-16 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 border-dashed">
                                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-950 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-350">
                                            <i data-lucide="message-square" class="w-6 h-6"></i>
                                        </div>
                                        <p class="text-slate-450 font-serif italic text-sm max-w-xs mx-auto">Be the first node to describe the atmosphere of this experience.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right: High-Fidelity Interactive Ticket Stub -->
                <div class="lg:col-span-4 shrink-0">
                    <div class="sticky top-32 space-y-8">
                        
                        <!-- Reservation Ticket Card -->
                        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850 rounded-[2rem] shadow-xl relative overflow-hidden">
                            <!-- Top Decorative Color bar -->
                            <div class="h-3.5 w-full bg-[#4E7D5B] shadow-inner"></div>

                            <div class="p-8 space-y-6">
                                <div>
                                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Secure Entry</span>
                                    <h3 class="text-2xl font-serif text-slate-900 dark:text-white leading-tight font-bold">Resonator Ticket</h3>
                                </div>

                                <form action="{{ route('events.book', $event) }}" method="GET" class="space-y-6">
                                    
                                    <!-- Ticket Type Options -->
                                    <div class="space-y-3.5">
                                        @foreach($event->ticketTypes as $type)
                                            <label class="block relative p-5 rounded-2xl border-2 cursor-pointer transition-all duration-300 group overflow-hidden"
                                                   :class="selectedType == {{ $type->id }} ? 'border-[#4E7D5B] bg-[#4E7D5B]/5 shadow-sm' : 'border-slate-100 dark:border-slate-800 hover:border-[#4E7D5B]/20 hover:bg-slate-50/50'">
                                                <input type="radio" name="ticket_type_id" value="{{ $type->id }}" class="sr-only" @change="selectedType = {{ $type->id }}" {{ $loop->first ? 'checked' : '' }}>
                                                
                                                <div class="flex justify-between items-center relative z-10 gap-3">
                                                    <div class="flex-1">
                                                        <div class="font-bold text-xs uppercase tracking-wider mb-0.5" :class="selectedType == {{ $type->id }} ? 'text-[#4E7D5B]' : 'text-slate-500'">
                                                            {{ $type->name }}
                                                        </div>
                                                        <div class="text-[11px] text-slate-450 dark:text-slate-400 leading-normal font-sans">{{ $type->description ?? ucfirst($type->type) }}</div>
                                                    </div>
                                                    <div class="text-lg font-serif font-bold text-right" :class="selectedType == {{ $type->id }} ? 'text-[#4E7D5B]' : 'text-slate-900 dark:text-white'">
                                                        {{ $type->price == 0 ? 'Free' : '₹' . number_format($type->price) }}
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <!-- Quantity Input -->
                                    <div class="flex items-center justify-between py-3.5 border-t border-b border-dashed border-slate-200 dark:border-slate-800">
                                        <div>
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Quantity</span>
                                            <span class="text-[11px] font-serif italic text-slate-450 dark:text-slate-500">Select ticket nodes</span>
                                        </div>
                                        <div class="relative group">
                                            <select name="quantity" x-model.number="quantity" class="appearance-none bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-[#4E7D5B] focus:ring-0 text-xs font-bold text-slate-850 dark:text-slate-200 py-2.5 pl-5 pr-9 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                                @for($i=1; $i<=10; $i++)
                                                    <option value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'NODES' : 'NODE' }}</option>
                                                @endfor
                                            </select>
                                            <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-450 transition-colors group-hover:text-[#4E7D5B]">
                                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Realtime Calculation Subtotal Display -->
                                    <div class="space-y-2 pt-2 text-xs">
                                        <div class="flex justify-between items-center text-slate-500">
                                            <span>Subtotal (<span x-text="quantity"></span> <span x-text="quantity > 1 ? 'nodes' : 'node'"></span>)</span>
                                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="'₹' + totalCost.toLocaleString('en-IN')">₹0.00</span>
                                        </div>
                                        <div class="flex justify-between items-center text-slate-500">
                                            <span>Booking Fee</span>
                                            <span class="font-semibold text-emerald-600">Free</span>
                                        </div>
                                        <div class="flex justify-between items-end pt-3 border-t border-slate-100 dark:border-slate-800">
                                            <span class="font-bold uppercase tracking-wider text-slate-900 dark:text-white">Estimated total</span>
                                            <span class="text-2xl font-serif font-bold text-[#4E7D5B]" x-text="'₹' + totalCost.toLocaleString('en-IN')">₹0.00</span>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full bg-[#4E7D5B] hover:bg-[#3D6449] text-white py-4.5 px-6 rounded-full text-xs font-black uppercase tracking-[0.25em] transition-all duration-300 shadow-lg shadow-[#4E7D5B]/20 active:scale-95">
                                        CONFIRM RESERVATION &rarr;
                                    </button>
                                </form>

                                <div class="flex items-center justify-center gap-2 text-[9px] font-black text-slate-350 dark:text-slate-600 uppercase tracking-widest pt-2">
                                    <i data-lucide="lock" class="w-3.5 h-3.5 text-[#4E7D5B]"></i>
                                    SECURED BY GROUNDED ENTERPRISE
                                </div>
                            </div>
                        </div>

                        <!-- Organizer Info Card -->
                        <div class="bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-850 p-6 flex items-center justify-between group cursor-pointer hover:bg-white dark:hover:bg-slate-900 hover:shadow-lg transition-all duration-500 rounded-3xl">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($event->organizer->name) }}&background=4E7D5B&color=fff" class="w-12 h-12 rounded-xl shadow-sm group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-[#4E7D5B] rounded-full border border-white flex items-center justify-center">
                                        <i data-lucide="verified" class="w-2 h-2 text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest leading-none mb-1.5">PRINCIPAL ARCHITECT</div>
                                    <div class="text-base font-serif text-slate-900 dark:text-white group-hover:text-[#4E7D5B] transition-colors leading-none font-bold">{{ $event->organizer->name }}</div>
                                </div>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 flex items-center justify-center text-slate-400 group-hover:text-[#4E7D5B] group-hover:border-[#4E7D5B] group-hover:rotate-12 transition-all duration-500 shadow-sm">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                        </div>

                        <!-- Report Copyright / Legal Violation -->
                        <div class="flex justify-center">
                            <button @click="showReportModal = true" class="text-[9px] font-black text-rose-500/80 hover:text-rose-600 uppercase tracking-widest flex items-center gap-2 transition-all p-2.5 bg-rose-500/5 hover:bg-rose-500/10 rounded-full px-5 border border-rose-500/10">
                                <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-rose-500"></i>
                                Report Violation
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Recommendations Section -->
        @if($recommendedEvents->count() > 0)
        <section class="py-20 px-6 md:px-12 bg-slate-50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-850 overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <span class="text-[#4E7D5B] text-[10px] font-black uppercase tracking-[0.4em] mb-3 block">RESONANCE MAP</span>
                        <h2 class="text-3xl md:text-4xl font-serif text-slate-900 dark:text-white">Related Architectures</h2>
                    </div>
                    <a href="{{ route('events.index') }}" class="text-[10px] font-black text-slate-400 hover:text-[#4E7D5B] uppercase tracking-widest transition-colors">
                        VIEW ALL NODES &rarr;
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($recommendedEvents as $rec)
                        <a href="{{ route('events.show', $rec->slug) }}" class="premium-card bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850 group overflow-hidden rounded-3xl hover:shadow-lg transition-all duration-500">
                            <div class="aspect-[16/10] relative overflow-hidden m-2 rounded-2xl">
                                <img src="{{ $rec->getFirstMediaUrl('banners') ?: 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=500' }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="absolute bottom-4 left-4 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-3.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest text-[#4E7D5B] border border-white/20">
                                    {{ $rec->category->name }}
                                </div>
                            </div>
                            <div class="p-6 pt-2">
                                <h4 class="text-lg font-serif text-slate-900 dark:text-white group-hover:text-[#4E7D5B] transition-colors truncate mb-3 font-bold">{{ $rec->title }}</h4>
                                <div class="flex items-center gap-5 text-[9px] font-black uppercase tracking-widest text-slate-400">
                                    <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5 text-[#4E7D5B]"></i> {{ $rec->start_date->format('M d') }}</span>
                                    <span class="flex items-center gap-1.5"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#4E7D5B]"></i> {{ $rec->venue->city ?? 'Global' }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
        
        <!-- Copyright & Legal Violation Report Modal -->
        <div x-show="showReportModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
             
            <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-3xl p-10 overflow-hidden"
                 @click.away="showReportModal = false">
                <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500/5 rounded-full -translate-y-12 translate-x-12 blur-[40px]"></div>
                
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/20 flex items-center justify-center text-rose-500">
                            <i data-lucide="shield-alert" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-serif text-slate-900 dark:text-white leading-tight">Report Violation</h3>
                            <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest block mt-0.5">Ecosystem Security Audit</span>
                        </div>
                    </div>
                    <button @click="showReportModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                @auth
                    <form action="{{ route('events.report.store', $event) }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Subject / Classification</label>
                            <select name="subject" required class="w-full bg-slate-50 dark:bg-slate-900 border-slate-100 dark:border-slate-800 focus:border-rose-400 focus:ring-0 text-xs font-bold text-slate-800 dark:text-slate-200 py-3.5 px-5 rounded-xl cursor-pointer">
                                <option value="Copyright / Intellectual Property Violation">Copyright / Intellectual Property Violation</option>
                                <option value="Illegal / Fraudulent Content">Illegal / Fraudulent Content</option>
                                <option value="Community Violations & Harassment">Community Violations & Harassment</option>
                                <option value="Other Regulatory Restrictions">Other Regulatory Restrictions</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Evidence URL (Optional)</label>
                            <input type="url" name="evidence_url" placeholder="https://..." class="w-full bg-slate-50 dark:bg-slate-900 border-slate-100 dark:border-slate-800 focus:border-rose-400 focus:ring-0 text-xs font-bold text-slate-800 dark:text-slate-200 py-3.5 px-5 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Detailed Narrative & Context</label>
                            <textarea name="description" required rows="4" placeholder="Please describe the copyright infringement or illegal issue in detail..." class="w-full bg-slate-50 dark:bg-slate-900 border-slate-100 dark:border-slate-800 focus:border-rose-400 focus:ring-0 text-xs text-slate-800 dark:text-slate-200 py-3.5 px-5 rounded-xl"></textarea>
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="button" @click="showReportModal = false" class="flex-1 py-3.5 rounded-full border border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-600 hover:border-slate-300 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 py-3.5 rounded-full bg-rose-500 text-white text-[10px] font-black uppercase tracking-wider hover:bg-rose-650 transition-all shadow-lg shadow-rose-500/20">
                                Submit Audit Request
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-6">
                        <div class="w-14 h-14 bg-rose-50 dark:bg-rose-950/20 rounded-full flex items-center justify-center mx-auto mb-4 text-rose-500">
                            <i data-lucide="lock" class="w-6 h-6"></i>
                        </div>
                        <p class="text-slate-650 dark:text-slate-350 font-serif italic text-base mb-6">Authentication is required to initiate a security audit request.</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-[#4E7D5B] px-8 py-3.5 text-[10px] font-black uppercase tracking-widest text-white shadow-lg shadow-[#4E7D5B]/20 hover:bg-[#3D6449] transition">
                            Login Passage
                        </a>
                    </div>
                @endauth
            </div>
        </div>

    </div>
</x-app-layout>
