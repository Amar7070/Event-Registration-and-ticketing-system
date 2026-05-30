<x-app-layout>
    <div x-data="{ activeFilter: 'all' }" class="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 relative overflow-hidden">
        
        <!-- Ambient Decorative Vector Blobs -->
        <div class="absolute top-0 right-0 w-1/3 h-96 opacity-10 pointer-events-none blur-[100px] bg-primary/10 rounded-full"></div>
        <div class="absolute top-1/3 left-0 w-1/4 h-96 opacity-5 pointer-events-none blur-[120px] bg-accent-amber/10 rounded-full"></div>

        <!-- Header Section -->
        <section class="pt-40 pb-16 px-8 relative z-10">
            <div class="max-w-[1440px] mx-auto">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 border-b border-slate-100 dark:border-slate-800/60 pb-12">
                    <div>
                        <span class="bg-primary/10 text-primary border border-primary/20 px-3.5 py-1.5 rounded-full text-[10px] font-bold tracking-widest uppercase mb-4 inline-block">COLLECTION</span>
                        <h1 class="text-4xl md:text-6xl font-serif text-slate-900 dark:text-white leading-tight font-medium">Your digital <span class="italic text-primary">passports</span>.</h1>
                        <p class="text-base md:text-lg text-slate-500 dark:text-slate-400 mt-4 max-w-xl font-serif italic">
                            A secure, elegant repository of all your upcoming and past gatherings within the ecosystem.
                        </p>
                    </div>
                    
                    <!-- Dynamic Alpine.js Filter Tabs -->
                    <div class="flex items-center gap-2.5 bg-slate-50 dark:bg-slate-900/50 p-1.5 rounded-full border border-slate-150 dark:border-slate-800/80 w-fit">
                        <button @click="activeFilter = 'all'" 
                                :class="activeFilter === 'all' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'" 
                                class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300">
                            All
                        </button>
                        <button @click="activeFilter = 'upcoming'" 
                                :class="activeFilter === 'upcoming' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'" 
                                class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300">
                            Upcoming
                        </button>
                        <button @click="activeFilter = 'past'" 
                                :class="activeFilter === 'past' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'" 
                                class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest transition-all duration-300">
                            Past Records
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="pb-32 px-8 relative z-10">
            <div class="max-w-[1440px] mx-auto">
                
                @if(session('success'))
                    <div class="mb-12 bg-primary/10 border border-primary/20 text-primary px-6 py-4 rounded-2xl flex items-center gap-4 animate-fade-in backdrop-blur-md">
                        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                        <p class="text-sm font-bold tracking-tight">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @forelse($tickets as $ticket)
                        <article x-show="activeFilter === 'all' || (activeFilter === 'upcoming' && {{ $ticket->event->start_date->isFuture() ? 'true' : 'false' }}) || (activeFilter === 'past' && {{ $ticket->event->start_date->isPast() ? 'true' : 'false' }})" 
                                 class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800/80 shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_45px_-10px_rgba(0,0,0,0.08)] dark:shadow-[0_10px_30px_rgba(0,0,0,0.2)] dark:hover:shadow-[0_20px_45px_-10px_rgba(0,0,0,0.4)] transition-all duration-500 hover:-translate-y-2 group overflow-visible flex flex-col h-full relative">
                            
                            <!-- Card Image Banner -->
                            <div class="relative aspect-[16/10] rounded-t-3xl overflow-hidden">
                                @if($ticket->event->getFirstMediaUrl('banners'))
                                    <img src="{{ $ticket->event->getFirstMediaUrl('banners') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-slate-900 flex items-center justify-center relative">
                                        <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                                        <i data-lucide="image" class="w-10 h-10 text-slate-700 relative z-10"></i>
                                    </div>
                                @endif

                                <!-- Status Badge Overlay -->
                                <div class="absolute top-4 right-4 z-10">
                                    @if($ticket->status === 'confirmed')
                                        <span class="bg-primary text-white text-[9px] font-black uppercase tracking-[0.2em] px-3.5 py-1.5 rounded-full shadow-md backdrop-blur-md">Confirmed</span>
                                    @elseif($ticket->status === 'pending')
                                        <span class="bg-amber-500 text-white text-[9px] font-black uppercase tracking-[0.2em] px-3.5 py-1.5 rounded-full shadow-md backdrop-blur-md">Pending</span>
                                    @else
                                        <span class="bg-slate-500 text-white text-[9px] font-black uppercase tracking-[0.2em] px-3.5 py-1.5 rounded-full shadow-md backdrop-blur-md capitalize">{{ $ticket->status }}</span>
                                    @endif
                                </div>

                                <!-- Ticket Icon Overlay -->
                                <div class="absolute bottom-4 left-4 w-9 h-9 rounded-xl bg-white/90 dark:bg-slate-900/90 backdrop-blur flex items-center justify-center text-primary shadow-sm">
                                    <i data-lucide="ticket" class="w-4.5 h-4.5"></i>
                                </div>
                            </div>

                            <!-- Perforated Division Notch Cutouts -->
                            <div class="relative h-6 bg-white dark:bg-slate-900 overflow-visible flex items-center">
                                <!-- Dashed Line -->
                                <div class="w-full border-t-2 border-dashed border-slate-100 dark:border-slate-800 mx-4"></div>
                                <!-- Notches -->
                                <div class="absolute -left-3.5 w-7 h-7 rounded-full bg-[#FDFBF7] dark:bg-slate-950 border border-slate-100 dark:border-slate-800/80 shadow-[inset_-2px_0_3px_rgba(0,0,0,0.01)]"></div>
                                <div class="absolute -right-3.5 w-7 h-7 rounded-full bg-[#FDFBF7] dark:bg-slate-950 border border-slate-100 dark:border-slate-800/80 shadow-[inset_2px_0_3px_rgba(0,0,0,0.01)]"></div>
                            </div>

                            <!-- Card Body Content -->
                            <div class="px-8 pb-8 pt-2 flex flex-col flex-1">
                                <!-- Date & Location info -->
                                <div class="flex items-center gap-2.5 mb-3.5">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-primary leading-none">{{ $ticket->event->start_date->format('M d, Y') }}</span>
                                    <div class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-800"></div>
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none">{{ $ticket->event->venue ? $ticket->event->venue->city : 'Online' }}</span>
                                </div>

                                <!-- Event Title -->
                                <h3 class="text-xl font-serif text-slate-900 dark:text-white mb-2 group-hover:text-primary transition-colors leading-snug font-medium">
                                    {{ $ticket->event->title }}
                                </h3>
                                
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-8">
                                    {{ $ticket->ticketType->name }} &bull; Pass x{{ $ticket->quantity }}
                                </p>

                                <!-- Footer Info / Pricing & Link -->
                                <div class="mt-auto pt-6 border-t border-slate-50 dark:border-slate-800/50 flex justify-between items-center">
                                    <div>
                                        <span class="block text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none mb-1">Energy Exchanged</span>
                                        <span class="text-base font-serif text-slate-900 dark:text-slate-100 font-medium">₹{{ number_format($ticket->total_amount) }}</span>
                                    </div>
                                    <a href="{{ route('user.tickets.show', $ticket->booking_reference) }}" class="flex items-center gap-1.5 text-[9px] font-black uppercase tracking-[0.2em] text-primary group/link">
                                        OPEN PASS
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover/link:translate-x-1.5 transition-transform duration-300"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full py-40 text-center bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-150 dark:border-slate-800 border-dashed">
                            <div class="w-20 h-20 bg-cream dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-8 text-primary/30">
                                <i data-lucide="ticket" class="w-10 h-10"></i>
                            </div>
                            <h3 class="text-2xl font-serif text-slate-900 dark:text-white mb-3 font-semibold">Your collection is empty.</h3>
                            <p class="text-slate-500 dark:text-slate-400 mb-10 max-w-sm mx-auto font-serif italic">
                                "The world is waiting for you to explore." Find your first seed of connection on the map.
                            </p>
                            <a href="{{ route('events.index') }}" class="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest">Explore Experiences</a>
                        </div>
                    @endforelse
                </div>
                
                @if($tickets->hasPages())
                <div class="mt-20 flex justify-center">
                    {{ $tickets->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
