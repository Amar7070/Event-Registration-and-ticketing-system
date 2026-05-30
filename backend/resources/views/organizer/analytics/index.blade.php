<x-organizer-layout>
    <x-slot name="header">
        Ecosystem Insights
    </x-slot>

    <div class="mb-12">
        <h2 class="text-3xl font-serif text-slate-900 mb-2">Global Resonance</h2>
        <p class="text-slate-500 font-serif italic text-sm">"Analyzing the frequency and connection density of your gatherings."</p>
    </div>

    <!-- High-Level Insights -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-16">
        <div class="premium-card p-10 bg-[#1E293B] text-white relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full -translate-y-16 translate-x-16 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">TOTAL ENERGY YIELD</span>
            <h3 class="text-5xl font-serif text-white mb-2">₹{{ number_format($totalRevenue / 1000, 1) }}k</h3>
            <div class="flex items-center gap-2 text-[10px] font-bold text-primary uppercase tracking-widest">
                <i data-lucide="trending-up" class="w-3 h-3"></i> 12% VELOCITY INCREASE
            </div>
        </div>

        <div class="premium-card p-10 bg-white border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -translate-y-16 translate-x-16 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">COLLECTIVE ATTENDEES</span>
            <h3 class="text-5xl font-serif text-slate-900 mb-2">{{ number_format($totalTickets) }}</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Across {{ $events->count() }} Managed Nodes</p>
        </div>

        <div class="premium-card p-10 bg-white border-slate-100 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -translate-y-16 translate-x-16 group-hover:scale-110 transition-transform duration-700"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">OCCUPANCY DENSITY</span>
            <h3 class="text-5xl font-serif text-slate-900 mb-2">84<span class="text-2xl ml-1">%</span></h3>
            <div class="mt-4 w-full bg-slate-50 rounded-full h-1.5 overflow-hidden">
                <div class="bg-primary h-full" style="width: 84%"></div>
            </div>
        </div>
    </div>

    <!-- Node Performance Table -->
    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-white sticky left-0">
            <div>
                <h2 class="text-xl font-serif text-slate-900">Node Performance Map</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Detailed resonance metrics per experience</p>
            </div>
            <button class="flex items-center gap-2 px-6 py-2.5 rounded-full bg-cream border border-slate-100 text-[10px] font-bold text-slate-600 uppercase tracking-widest hover:border-primary hover:text-primary transition-all">
                <i data-lucide="download" class="w-3 h-3"></i>
                Export Report
            </button>
        </div>
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Node</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Node Status</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Attendance</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Yield (Revenue)</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($events as $event)
                        <tr class="group hover:bg-cream/50 transition-colors duration-300">
                            <td class="px-8 py-8">
                                <div class="font-bold text-slate-900 group-hover:text-primary transition-colors text-lg leading-tight">{{ $event->title }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $event->start_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-8 py-8">
                                @if($event->status === 'published')
                                    <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">ACTIVE NODE</span>
                                @else
                                    <span class="status-pill bg-slate-50 text-slate-400 border-slate-100 tracking-widest">{{ strtoupper($event->status) }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-8">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-bold text-slate-900">{{ $event->registered_count }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">/ {{ $event->total_capacity }} Nodes</span>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <span class="text-sm font-bold text-slate-900">₹{{ number_format($event->payments_sum_amount ?? 0) }}</span>
                            </td>
                            <td class="px-8 py-8 text-right">
                                <a href="{{ route('organizer.events.analytics', $event) }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-primary group-hover:translate-x-1 transition-transform inline-flex items-center gap-2">
                                    View Details
                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <p class="text-slate-400 font-serif italic">No ecosystem data found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-organizer-layout>
