<x-admin-layout>
    <x-slot name="header">
        Experience Oversight
    </x-slot>

    <div class="mb-12">
        <h2 class="text-3xl font-serif text-slate-900 mb-2">Global Experience Monitor</h2>
        <p class="text-slate-500 font-serif italic text-sm">"Ensuring the integrity and quality of every node in the ecosystem."</p>
    </div>

    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="p-10 border-b border-slate-50 flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white">
            <div>
                <h3 class="text-xl font-serif text-slate-900">Ecosystem Event Map</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Total Monitored Architectures: {{ $events->total() }}</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="relative group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" placeholder="Scan architectures..." 
                           class="form-input pl-12 pr-6 py-3 w-full sm:w-72">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Architecture</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Principal Architect</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Archetype</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Resonance Date</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Node Status</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Density</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($events as $event)
                        <tr class="group hover:bg-cream/50 transition-colors duration-300">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-5">
                                    <div class="relative w-14 h-14 shrink-0 rounded-2xl overflow-hidden shadow-sm group-hover:shadow-lg transition-all duration-500">
                                        @if($event->hasMedia('banners'))
                                            <img src="{{ $event->getFirstMediaUrl('banners', 'thumb') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        @else
                                            <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                                                <i data-lucide="image" class="text-slate-700 w-5 h-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 group-hover:text-primary transition-colors text-lg leading-tight">{{ $event->title }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">NODE-{{ $event->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-sm font-bold text-slate-700">{{ $event->organizer?->name ?? 'Unknown Organizer' }}</p>
                            </td>
                            <td class="px-10 py-8">
                                <span class="status-pill bg-primary/5 text-primary border-primary/10 tracking-widest uppercase">
                                    {{ $event->category?->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-sm font-bold text-slate-500">{{ $event->start_date->format('M d, Y') }}</p>
                            </td>
                            <td class="px-10 py-8">
                                @if($event->is_restricted)
                                    <span class="status-pill bg-rose-500 text-white border-rose-600 tracking-widest animate-pulse">RESTRICTED</span>
                                @elseif($event->status === 'published')
                                    <span class="status-pill bg-primary text-white border-primary tracking-widest">LIVE NODE</span>
                                @elseif($event->status === 'draft')
                                    <span class="status-pill bg-slate-100 text-slate-400 border-slate-200 tracking-widest">BLUEPRINT</span>
                                @else
                                    <span class="status-pill bg-rose-50 text-rose-500 border-rose-100 tracking-widest uppercase">{{ $event->status }}</span>
                                @endif
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="inline-flex flex-col items-end">
                                    <span class="text-sm font-bold text-slate-900">{{ $event->registered_count }} / {{ $event->total_capacity }}</span>
                                    <div class="w-16 bg-slate-100 rounded-full h-1 overflow-hidden mt-1.5">
                                        <div class="bg-primary h-full" style="width: {{ min(($event->registered_count / max($event->total_capacity, 1)) * 100, 100) }}%"></div>
                                    </div>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mt-1">Gathered</span>
                                </div>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex items-center justify-end gap-3" x-data="{ openRestrict: false }">
                                    <!-- Check Experience -->
                                    <a href="{{ route('events.show', $event->slug) }}" target="_blank" 
                                       class="w-10 h-10 flex items-center justify-center rounded-xl bg-cream text-slate-400 hover:text-primary hover:bg-white hover:border-primary/20 border border-transparent transition-all" 
                                       title="Audit Experience Blueprint">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>

                                    <!-- Restrict Toggle -->
                                    <form action="{{ route('admin.events.restrict', $event) }}" method="POST" class="inline">
                                        @csrf
                                        @if($event->is_restricted)
                                            <button type="submit" class="px-4 py-2 text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full hover:bg-emerald-100 transition-colors">
                                                Lift Restriction
                                            </button>
                                        @else
                                            <button type="button" @click="openRestrict = true" class="px-4 py-2 text-[9px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-100 rounded-full hover:bg-rose-100 transition-colors">
                                                Restrict
                                            </button>

                                            <!-- Restrict Reason Modal Overlay -->
                                            <div x-show="openRestrict" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all text-left" style="display: none;">
                                                <div class="relative w-full max-w-md bg-white border border-slate-100 shadow-2xl rounded-[3rem] p-12 overflow-hidden" @click.away="openRestrict = false">
                                                    <h3 class="text-xl font-serif text-slate-900 mb-6">Restrict Architecture</h3>
                                                    <div class="space-y-6">
                                                        <div>
                                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Restriction Reason</label>
                                                            <textarea name="restriction_reason" placeholder="Copyright Infringement..." required class="w-full bg-cream border-slate-100 focus:border-rose-400 focus:ring-0 text-sm text-slate-800 py-4 px-6 rounded-3xl">Suspended due to Copyright Infringement or Legal Compliance guidelines violation.</textarea>
                                                        </div>
                                                        <div class="flex gap-4">
                                                            <button type="button" @click="openRestrict = false" class="flex-1 py-4 rounded-full border border-slate-200 text-xs font-black uppercase tracking-widest text-slate-600">Cancel</button>
                                                            <button type="submit" class="flex-1 py-4 rounded-full bg-rose-500 text-white text-xs font-black uppercase tracking-widest hover:bg-rose-600">Confirm Restrict</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-10 py-32 text-center text-slate-400 font-serif italic">No experience architectures detected.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($events->hasPages())
            <div class="p-10 border-t border-slate-50 bg-cream/10">
                {{ $events->links() }}
            </div>
        @endif
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-admin-layout>
