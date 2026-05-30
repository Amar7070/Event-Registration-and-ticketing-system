<x-app-layout>
    <!-- Cinematic Ambient Glow Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[10%] right-[5%] w-[600px] h-[600px] bg-[#4E7D5B]/5 dark:bg-[#4E7D5B]/2 rounded-full blur-[140px]"></div>
        <div class="absolute top-[40%] left-[2%] w-[500px] h-[500px] bg-amber-500/3 dark:bg-[#4E7D5B]/3 rounded-full blur-[120px]"></div>
    </div>

    @php
        $activeEventsCount = \App\Models\Event::published()->upcoming()->count();
        $totalVenuesCount = \App\Models\Venue::count();
        $spotsReservedCount = \App\Models\Ticket::where('status', 'confirmed')->count() + 1420;

        // Fetch actively promoted events (approved/paid promotions) OR manually featured events by the admin
        $promotedEvents = \App\Models\Event::published()
            ->upcoming()
            ->where('is_restricted', false)
            ->where(function($query) {
                $query->where('is_featured', true)
                      ->orWhereHas('promotions', function($q) {
                          $q->where('status', 'approved')
                            ->where('payment_status', 'paid')
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                      });
            })
            ->with(['category', 'venue'])
            ->latest()
            ->get();
    @endphp

    <!-- Top Promoted Carousel (BookMyShow Style) -->
    @if($promotedEvents->count() > 0)
    <section class="pt-28 pb-10 px-6 md:px-12 max-w-7xl mx-auto relative z-10">
        <div x-data="{ 
            activeSlide: 0, 
            slidesCount: {{ $promotedEvents->count() }},
            next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount },
            prev() { this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount }
        }" 
        x-init="setInterval(() => next(), 6000)"
        class="relative rounded-[2rem] overflow-hidden aspect-[21/10] md:aspect-[21/8] lg:aspect-[21/7] shadow-2xl border border-slate-100 dark:border-slate-800 bg-slate-900 group">
            
            <!-- Slides Container -->
            <div class="w-full h-full relative">
                @foreach($promotedEvents as $index => $pe)
                <div x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 w-full h-full bg-cover bg-center"
                     style="background-image: url('{{ $pe->getFirstMediaUrl('banners') ?: 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=1600' }}');">
                    
                    <!-- Dark Gradient Overlay for Typography legibility -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/20 to-transparent"></div>

                    <!-- Slide Contents -->
                    <div class="absolute bottom-0 left-0 w-full p-8 md:p-12 lg:p-16 flex flex-col justify-end h-full text-left space-y-4 max-w-3xl">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="px-3.5 py-1 rounded-full bg-[#4E7D5B] text-white text-[9px] font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/30">
                                Featured Gathering
                            </span>
                            <span class="px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-[0.2em] border border-white/10">
                                {{ $pe->category->name }}
                            </span>
                        </div>

                        <h2 class="text-2xl md:text-4xl lg:text-5xl font-serif text-white font-bold leading-tight tracking-tight line-clamp-2">
                            {{ $pe->title }}
                        </h2>

                        <div class="flex flex-wrap items-center gap-6 text-slate-300 text-xs md:text-sm">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-4 h-4 text-[#4E7D5B]"></i>
                                {{ $pe->start_date->format('M d, Y') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-4 h-4 text-[#4E7D5B]"></i>
                                {{ $pe->venue ? $pe->venue->name : 'Global Portal' }}
                            </span>
                        </div>

                        <div class="pt-3">
                            <a href="{{ route('events.show', $pe->slug) }}" class="inline-flex items-center gap-2 bg-[#4E7D5B] hover:bg-[#3D6449] text-white px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest transition shadow-lg shadow-[#4E7D5B]/20">
                                Book Pass &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Left Arrow -->
            <button @click="prev()" class="absolute left-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-slate-950/40 hover:bg-[#4E7D5B] text-white backdrop-blur-md flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 z-20 border border-white/10">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </button>

            <!-- Right Arrow -->
            <button @click="next()" class="absolute right-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-slate-950/40 hover:bg-[#4E7D5B] text-white backdrop-blur-md flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 z-20 border border-white/10">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </button>

            <!-- Slide Indicators matching the photo style -->
            <div class="absolute bottom-8 right-8 flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/40 backdrop-blur-sm z-20 border border-white/5">
                @foreach($promotedEvents as $index => $pe)
                <button @click="activeSlide = {{ $index }}"
                        class="transition-all duration-500 rounded-full"
                        :class="activeSlide === {{ $index }} ? 'w-5 h-1.5 bg-[#4E7D5B]' : 'w-1.5 h-1.5 bg-white/40 hover:bg-white/85'"></button>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Main Workspace Split Grid Layout (BookMyShow Inspired) -->
    <section class="py-12 px-6 md:px-12 max-w-7xl mx-auto relative z-10" x-data="locationRadar()">
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 items-start">
            
            <!-- LEFT COLUMN: Filters Sidebar -->
            <aside class="space-y-8 lg:sticky lg:top-28">
                
                <!-- Search Input Panel -->
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Search Ecosystem</h3>
                    <form action="{{ route('events.index') }}" method="GET" class="relative">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('type'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Keywords, hosts, cities..." 
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 focus:border-[#4E7D5B] focus:ring-0 text-xs font-bold text-slate-800 dark:text-slate-200 py-3.5 pl-4 pr-10 rounded-xl">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#4E7D5B]">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>

                <!-- Domains & Categories -->
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Domain Spheres</h3>
                    <div class="flex flex-col gap-1.5">
                        <a href="{{ route('events.index', request()->except('category')) }}" 
                           class="flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors {{ !request('category') ? 'bg-[#4E7D5B]/10 text-[#4E7D5B]' : 'text-slate-550 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <span class="flex items-center gap-2">
                                <i data-lucide="compass" class="w-4 h-4"></i>
                                All Spheres
                            </span>
                            <span class="text-[9px] font-black font-mono opacity-60 bg-slate-100 dark:bg-slate-850 px-2 py-0.5 rounded-md">{{ $activeEventsCount }}</span>
                        </a>

                        @foreach($categories as $category)
                        @php 
                            $count = \App\Models\Event::published()->upcoming()->where('category_id', $category->id)->where('is_restricted', false)->count();
                        @endphp
                        <a href="?{{ http_build_query(array_merge(request()->query(), ['category' => $category->slug])) }}" 
                           class="flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors {{ request('category') === $category->slug ? 'bg-[#4E7D5B]/10 text-[#4E7D5B]' : 'text-slate-550 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <span class="flex items-center gap-2">
                                <i data-lucide="{{ $category->icon ?: 'hash' }}" class="w-4 h-4 text-[#4E7D5B]"></i>
                                {{ $category->name }}
                            </span>
                            <span class="text-[9px] font-black font-mono opacity-60 bg-slate-100 dark:bg-slate-850 px-2 py-0.5 rounded-md">{{ $count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Event Formats (Physical vs Online) -->
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Experience Format</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('events.index', request()->except('type')) }}" 
                           class="px-3 py-2.5 rounded-xl border text-[9px] font-black uppercase tracking-widest text-center transition {{ !request('type') ? 'bg-[#4E7D5B] text-white border-transparent' : 'bg-slate-50 dark:bg-slate-950 border-slate-100 dark:border-slate-850 text-slate-500 dark:text-slate-400 hover:border-[#4E7D5B]/20' }}">
                            All
                        </a>
                        <a href="?{{ http_build_query(array_merge(request()->query(), ['type' => 'physical'])) }}" 
                           class="px-3 py-2.5 rounded-xl border text-[9px] font-black uppercase tracking-widest text-center transition {{ request('type') === 'physical' ? 'bg-[#4E7D5B] text-white border-transparent' : 'bg-slate-50 dark:bg-slate-950 border-slate-100 dark:border-slate-850 text-slate-500 dark:text-slate-400 hover:border-[#4E7D5B]/20' }}">
                            Physical
                        </a>
                        <a href="?{{ http_build_query(array_merge(request()->query(), ['type' => 'online'])) }}" 
                           class="px-3 py-2.5 rounded-xl border text-[9px] font-black uppercase tracking-widest text-center transition {{ request('type') === 'online' ? 'bg-[#4E7D5B] text-white border-transparent' : 'bg-slate-50 dark:bg-slate-950 border-slate-100 dark:border-slate-850 text-slate-500 dark:text-slate-400 hover:border-[#4E7D5B]/20' }}">
                            Online
                        </a>
                    </div>
                </div>

                <!-- Location Radar Integration inside Sidebar -->
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Nearby Radar</h3>
                        <span class="w-2 h-2 rounded-full bg-emerald-500" x-show="locationLocked" style="display: none;"></span>
                    </div>

                    <form action="{{ route('events.index') }}" method="GET" id="radar-form" class="space-y-4">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('type'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif

                        <input type="hidden" name="latitude" x-model="lat" :disabled="!locationLocked">
                        <input type="hidden" name="longitude" x-model="lng" :disabled="!locationLocked">

                        <!-- Locked status -->
                        <div x-show="locationLocked" style="display: none;" class="p-3 bg-[#4E7D5B]/5 border border-[#4E7D5B]/10 rounded-xl space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">SCAN RANGE</span>
                                <span class="text-xs font-black text-[#4E7D5B]" x-text="radius + ' KM'">50 KM</span>
                            </div>
                            <input type="range" name="radius" min="5" max="1000" step="5" x-model="radius" @change="document.getElementById('radar-form').submit()"
                                   class="w-full h-1 bg-slate-100 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-[#4E7D5B]">
                        </div>

                        <div class="flex gap-2">
                            <button type="button" @click="requestLocation()" 
                                    class="flex-1 px-4 py-3 bg-slate-900 dark:bg-slate-800 hover:bg-slate-850 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition flex items-center justify-center gap-2">
                                <template x-if="loading">
                                    <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <i data-lucide="crosshair" class="w-3.5 h-3.5" x-show="!loading && !locationLocked"></i>
                                <i data-lucide="check" class="w-3.5 h-3.5" x-show="!loading && locationLocked" style="display: none;"></i>
                                <span x-text="loading ? 'LOCKING...' : (locationLocked ? 'COORDS LOCKED' : 'LOCK LOCATION')">LOCK LOCATION</span>
                            </button>

                            <button type="button" @click="resetRadar()" x-show="locationLocked" style="display: none;"
                                    class="w-10 h-10 bg-rose-50 dark:bg-rose-950/20 text-rose-600 rounded-xl flex items-center justify-center transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </aside>

            <!-- RIGHT COLUMN: Events Grid list -->
            <main class="lg:col-span-3 space-y-8">
                
                <!-- Query details/Header info -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h2 class="text-lg font-serif font-bold text-slate-900 dark:text-white leading-tight">
                            @if(request('category'))
                                Domain: <span class="text-[#4E7D5B] capitalize">{{ request('category') }}</span>
                            @elseif(request('search'))
                                Search Results for: <span class="text-[#4E7D5B] italic font-serif">"{{ request('search') }}"</span>
                            @else
                                All Experience Spheres
                            @endif
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Showing {{ $events->firstItem() ?? 0 }}-{{ $events->lastItem() ?? 0 }} of {{ $events->total() }} intentional gatherings</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">SORT: NEWEST</span>
                        <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                        <i data-lucide="grid" class="w-4 h-4 text-[#4E7D5B]"></i>
                    </div>
                </div>

                <!-- Event list Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($events as $event)
                        @php
                            $spotsLeft = $event->total_capacity - $event->registered_count;
                            $statusText = 'EXCLUSIVE PASS';
                            $statusColor = 'bg-slate-950/80 dark:bg-slate-900/80 text-white';
                            if ($spotsLeft <= 0) {
                                $statusText = 'FULLY RESERVED';
                                $statusColor = 'bg-rose-500 text-white';
                            } elseif ($spotsLeft < 15) {
                                $statusText = 'LIMITED NODES';
                                $statusColor = 'bg-amber-500 text-white';
                            }
                        @endphp
                        <article class="premium-card group bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-500 flex flex-col justify-between h-full">
                            <a href="{{ route('events.show', $event->slug) }}" class="flex flex-col justify-between h-full">
                                
                                <div>
                                    <!-- Banner Frame -->
                                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-50 dark:bg-slate-950">
                                        <img src="{{ $event->getFirstMediaUrl('banners') ?: 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&q=80&w=600' }}" 
                                             class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" 
                                             alt="{{ $event->title }}">
                                        
                                        <!-- Vignette overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/45 via-transparent to-transparent opacity-100"></div>

                                        <!-- Date Badge -->
                                        <div class="absolute top-4 left-4 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-xl p-2.5 text-center min-w-[50px] shadow-sm border border-white/20">
                                            <span class="block text-[8px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5 leading-none">{{ $event->start_date->format('M') }}</span>
                                            <span class="block text-lg font-serif text-slate-900 dark:text-white leading-none font-bold">{{ $event->start_date->format('d') }}</span>
                                        </div>

                                        <!-- Formats status badge -->
                                        <div class="absolute top-4 right-4">
                                            <span class="status-pill {{ $statusColor }} text-[8px] font-black tracking-widest px-3 py-1.5 rounded-lg border-none shadow-md">{{ $statusText }}</span>
                                        </div>

                                        <!-- Price overlay -->
                                        <div class="absolute bottom-4 right-4 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md text-slate-950 dark:text-white px-3.5 py-1.5 rounded-full text-[9px] font-black tracking-widest shadow-sm border border-white/20">
                                            @if($event->ticketTypes->min('price') == 0) FREE @else FROM ₹{{ number_format($event->ticketTypes->min('price')) }} @endif
                                        </div>
                                    </div>

                                    <!-- Content Description -->
                                    <div class="p-5 space-y-2.5">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-[#4E7D5B]">
                                            {{ $event->category->name }}
                                        </span>

                                        <h4 class="text-base font-serif text-slate-900 dark:text-white font-bold leading-snug group-hover:text-[#4E7D5B] transition-colors line-clamp-2">
                                            {{ $event->title }}
                                        </h4>

                                        <p class="text-slate-450 dark:text-slate-400 text-xs font-serif italic line-clamp-2 leading-relaxed">
                                            {{ $event->short_description }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Card Footer details -->
                                <div class="p-5 pt-3 border-t border-slate-50 dark:border-slate-850 flex items-center justify-between mt-auto">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#4E7D5B]"></i>
                                        {{ $event->venue ? $event->venue->city : 'Global Domain' }}
                                    </span>

                                    <span class="text-[9px] font-black text-[#4E7D5B] uppercase tracking-widest flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                        Book <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                    </span>
                                </div>

                            </a>
                        </article>
                    @empty
                        <div class="col-span-full py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl">
                            <div class="w-14 h-14 bg-slate-50 dark:bg-slate-950 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-350">
                                <i data-lucide="compass" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-xl font-serif text-slate-900 dark:text-white mb-1.5 font-bold">The map is empty</h3>
                            <p class="text-xs text-slate-450 dark:text-slate-400 max-w-xs mx-auto mb-6">No gatherings match the selected filter conditions.</p>
                            <a href="{{ route('events.index') }}" class="inline-flex px-6 py-3 bg-[#4E7D5B] text-white rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-[#3D6449] transition shadow-md">
                                Clear Filters
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination links -->
                @if($events->hasPages())
                    <div class="pt-10 flex justify-center">
                        {{ $events->links() }}
                    </div>
                @endif

            </main>

        </div>

    </section>

    <!-- Host CTA Section -->
    <section class="px-6 md:px-12 pb-24 relative z-10">
        <div class="max-w-7xl mx-auto bg-slate-900 dark:bg-slate-950 rounded-[3rem] p-12 md:p-24 relative overflow-hidden text-center shadow-xl border border-slate-800">
            <div class="absolute inset-0 opacity-[0.03]">
                <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 30px 30px;"></div>
            </div>
            
            <div class="relative z-10 max-w-3xl mx-auto space-y-6">
                <span class="text-[#4E7D5B] text-[10px] font-black uppercase tracking-[0.4em] block">FOR ARCHITECTS</span>
                <h2 class="text-4xl md:text-6xl font-serif text-white leading-tight font-bold">Have a vision for a <span class="text-[#4E7D5B] italic relative font-normal">gathering?<span class="absolute bottom-2 left-0 w-full h-2 bg-[#4E7D5B]/20"></span></span></h2>
                <p class="text-base text-slate-400 font-serif italic max-w-lg mx-auto">
                    Join our ecosystem of intentional hosts and bring your community together in a premium environment.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-4">
                    <a href="{{ route('organizer.events.index') }}" class="bg-[#4E7D5B] hover:bg-[#3D6449] text-white px-10 py-4.5 rounded-full text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/20 transition">
                        START HOSTING
                    </a>
                    <a href="{{ route('about') }}" class="text-[9px] font-black text-white uppercase tracking-[0.3em] flex items-center gap-2 group">
                        VIEW SUCCESS RITUALS
                        <div class="w-8 h-8 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-slate-900 transition-all duration-300">
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        lucide.createIcons();

        function locationRadar() {
            return {
                locationLocked: {{ request()->has('latitude') ? 'true' : 'false' }},
                loading: false,
                lat: '{{ request('latitude', '') }}',
                lng: '{{ request('longitude', '') }}',
                radius: '{{ request('radius', '50') }}',
                
                requestLocation() {
                    if (this.locationLocked) return;
                    
                    this.loading = true;
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.lat = position.coords.latitude;
                                this.lng = position.coords.longitude;
                                this.locationLocked = true;
                                this.loading = false;
                                
                                setTimeout(() => {
                                    document.getElementById('radar-form').submit();
                                }, 500);
                            },
                            (error) => {
                                alert("Coordinates request failed: " + error.message);
                                this.loading = false;
                             }
                        );
                    } else {
                        alert("Geolocation protocols not supported by this browser.");
                        this.loading = false;
                    }
                },
                
                resetRadar() {
                    this.lat = '';
                    this.lng = '';
                    this.locationLocked = false;
                    
                    // Re-route to base filters
                    let url = new URL(window.location.href);
                    url.searchParams.delete('latitude');
                    url.searchParams.delete('longitude');
                    url.searchParams.delete('radius');
                    window.location.href = url.pathname + url.search;
                }
            }
        }
    </script>
</x-app-layout>
