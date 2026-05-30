<x-organizer-layout>
    <x-slot name="header">
        Event Architecture
    </x-slot>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-12">
        <div>
            <h2 class="text-3xl font-serif text-slate-900 mb-2">Ecosystem Managed Events</h2>
            <p class="text-slate-500 font-serif italic text-sm">"Architecting intentional spaces for connection."</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center px-4 py-2.5 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-400 group focus-within:border-primary focus-within:text-primary transition-all">
                <i data-lucide="search" class="w-4 h-4 mr-3"></i>
                <input type="text" placeholder="Locate event architecture..." class="bg-transparent border-none focus:ring-0 p-0 text-slate-900 placeholder:text-slate-300 w-48">
            </div>
        </div>
    </div>

    <!-- Analytics Pulse -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
        <div class="premium-card p-10 bg-white border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">ACTIVE ARCHITECTURES</span>
            <div class="text-4xl font-serif text-slate-900 mb-2">{{ $activeCount }}</div>
            <div class="text-[10px] font-bold text-primary uppercase tracking-widest flex items-center gap-1">
                <i data-lucide="activity" class="w-3 h-3"></i> LIVE NODES
            </div>
        </div>

        <div class="premium-card p-10 bg-white border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">TOTAL RESONANCE</span>
            <div class="text-4xl font-serif text-slate-900 mb-2">{{ number_format($totalResonance) }}</div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">COLLECTIVE ATTENDEES</div>
        </div>

        <div class="premium-card p-10 bg-white border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">ENERGY EXCHANGE</span>
            <div class="text-4xl font-serif text-slate-900 mb-2">
                @if($totalRevenue >= 1000)
                    ₹{{ number_format($totalRevenue / 1000, 1) }}k
                @else
                    ₹{{ number_format($totalRevenue) }}
                @endif
            </div>
            <div class="text-[10px] font-bold text-primary uppercase tracking-widest flex items-center gap-1">
                <i data-lucide="zap" class="w-3 h-3"></i> SYSTEM REVENUE
            </div>
        </div>

        <div class="premium-card p-10 bg-white border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">GLOBAL RATING</span>
            <div class="text-4xl font-serif text-slate-900 mb-2">{{ number_format($globalRating, 1) }}</div>
            <div class="flex text-amber-400">
                <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                <i data-lucide="star" class="w-3 h-3 fill-current"></i>
            </div>
        </div>
    </div>

    <!-- Management Interface -->
    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Structure Details</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Temporal Node</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Ecosystem Status</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Density Map</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($events as $event)
                        <tr class="group hover:bg-cream/50 transition-colors duration-300">
                            <td class="px-8 py-8">
                                <div class="flex items-center gap-6">
                                    <div class="relative w-16 h-16 shrink-0 rounded-2xl overflow-hidden shadow-sm group-hover:shadow-lg transition-all duration-500">
                                        @if($event->hasMedia('banners'))
                                            <img src="{{ $event->getFirstMediaUrl('banners', 'thumb') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        @else
                                            <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                                                <i data-lucide="image" class="text-slate-700 w-6 h-6"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 group-hover:text-primary transition-colors text-lg leading-tight">{{ $event->title }}</div>
                                        <div class="flex items-center gap-3 mt-1.5">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-primary">{{ $event->category->name }}</span>
                                            <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ ucfirst($event->type) }} Experience</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="text-sm font-bold text-slate-900">{{ $event->start_date->format('M d, Y') }}</div>
                                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mt-1">{{ $event->start_date->format('g:i A') }}</div>
                            </td>
                            <td class="px-8 py-8">
                                @if($event->status === 'published')
                                    <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">LIVE NODE</span>
                                @elseif($event->status === 'draft')
                                    <span class="status-pill bg-slate-100 text-slate-400 border-slate-200 tracking-widest">BLUEPRINT</span>
                                @else
                                    <span class="status-pill bg-rose-50 text-rose-500 border-rose-100 tracking-widest uppercase">{{ $event->status }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-8">
                                <div class="max-w-[140px]">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Resonance</span>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">{{ round(($event->registered_count / max($event->total_capacity, 1)) * 100) }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-50 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-primary h-full transition-all duration-1000 ease-out" style="width: {{ ($event->registered_count / max($event->total_capacity, 1)) * 100 }}%"></div>
                                    </div>
                                    <div class="text-[9px] font-bold text-slate-400 mt-2 tracking-widest uppercase">
                                        {{ $event->registered_count }} / {{ $event->total_capacity }} GATHERED
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('organizer.events.analytics', $event) }}" 
                                       class="w-10 h-10 flex items-center justify-center rounded-xl bg-cream text-slate-400 hover:text-primary hover:bg-white hover:border-primary/20 border border-transparent transition-all" 
                                       title="Ecosystem Analytics">
                                        <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('organizer.events.attendees', $event) }}" 
                                       class="w-10 h-10 flex items-center justify-center rounded-xl bg-cream text-slate-400 hover:text-primary hover:bg-white hover:border-primary/20 border border-transparent transition-all" 
                                       title="Attendee Directory">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('organizer.events.edit', $event) }}" 
                                       class="w-10 h-10 flex items-center justify-center rounded-xl bg-cream text-slate-400 hover:text-primary hover:bg-white hover:border-primary/20 border border-transparent transition-all" 
                                       title="Modify Blueprint">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('organizer.events.scanner', $event) }}" 
                                       class="w-10 h-10 flex items-center justify-center rounded-xl bg-cream text-slate-400 hover:text-[#4E7D5B] hover:bg-white hover:border-[#4E7D5B]/20 border border-transparent transition-all" 
                                       title="Pass Scanner">
                                        <i data-lucide="scan" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('organizer.events.promote', $event) }}" 
                                       class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#4E7D5B]/10 text-[#4E7D5B] hover:bg-[#4E7D5B] hover:text-white transition-all shadow-lg shadow-[#4E7D5B]/5" 
                                       title="Promote Ad Showcase">
                                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-cream rounded-full flex items-center justify-center mb-8 text-primary/20">
                                        <i data-lucide="calendar-off" class="w-12 h-12"></i>
                                    </div>
                                    <h3 class="text-2xl font-serif text-slate-900 mb-2">No event architectures found.</h3>
                                    <p class="text-slate-500 font-serif italic mb-10">Start by constructing your first intentional gathering space.</p>
                                    <a href="{{ route('organizer.events.create') }}" class="btn-primary px-10 py-4 text-lg font-bold tracking-tight">Construct New Event</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($events->hasPages())
        <div class="mt-12">
            {{ $events->links() }}
        </div>
    @endif

    <script>
        lucide.createIcons();
    </script>
</x-organizer-layout>
