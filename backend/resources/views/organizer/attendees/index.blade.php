<x-organizer-layout>
    <x-slot name="header">
        Resident Node Ledger — {{ $event->title }}
    </x-slot>

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-12 gap-8">
        <div>
            <h2 class="text-3xl font-serif text-slate-900 mb-2">Resident Nodes</h2>
            <p class="text-slate-500 font-serif italic text-sm">"Monitoring the identity and verification flow of arriving ecosystem nodes."</p>
        </div>
        <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
            <a href="{{ route('organizer.events.attendees.export', $event) }}" class="flex-1 md:flex-none btn-outline px-6 py-3.5 text-[10px] tracking-widest border-slate-100 bg-white">
                <i data-lucide="download" class="w-4 h-4"></i> EXPORT CSV
            </a>
            <a href="{{ route('organizer.events.scanner', $event) }}" target="_blank" class="flex-1 md:flex-none btn-primary px-8 py-3.5 text-[10px] tracking-widest shadow-xl shadow-primary/20">
                <i data-lucide="scan-line" class="w-4 h-4"></i> OPEN SCANNER HUB
            </a>
        </div>
    </div>

    <!-- Architectural Filters -->
    <div class="premium-card bg-white border-slate-100 shadow-2xl shadow-primary/5 p-6 mb-12 rounded-[2.5rem]">
        <form action="{{ route('organizer.events.attendees', $event) }}" method="GET" class="flex flex-col lg:flex-row gap-6">
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-6 flex items-center text-slate-400 group-focus-within:text-primary transition-colors">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Scan identity references, emails, or order protocols..." 
                       class="form-input pl-16 py-4 rounded-2xl bg-cream/50 border-transparent focus:bg-white focus:border-primary/20">
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex items-center gap-6">
                <div class="relative group">
                    <select name="ticket_type_id" class="form-input py-4 pl-6 pr-12 rounded-2xl bg-cream/50 border-transparent focus:bg-white focus:border-primary/20 appearance-none min-w-[220px]">
                        <option value="">All Archetypes</option>
                        @foreach($ticketTypes as $type)
                            <option value="{{ $type->id }}" {{ request('ticket_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-primary transition-colors">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
                
                <div class="relative group">
                    <select name="status" class="form-input py-4 pl-6 pr-12 rounded-2xl bg-cream/50 border-transparent focus:bg-white focus:border-primary/20 appearance-none min-w-[180px]">
                        <option value="">All Statuses</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-hover:text-primary transition-colors">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary px-10 py-4 text-[10px] tracking-[0.2em] rounded-2xl shadow-lg shadow-primary/10">
                    SCAN LEDGER
                </button>
            </div>
        </form>
    </div>

    <!-- Ledger Table -->
    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-3xl shadow-primary/5 rounded-[3rem]">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Resident Identity</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Order Protocol</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Archetype Detail</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Verification Status</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Pulse Arrival</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($attendees as $ticket)
                        <tr class="group hover:bg-cream/50 transition-colors duration-500">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-2xl bg-cream flex items-center justify-center text-primary font-black text-lg border border-slate-100 group-hover:scale-110 transition-transform duration-500 shadow-sm">
                                        {{ substr($ticket->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $ticket->user->name }}</p>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $ticket->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <span class="font-mono text-[11px] font-black text-slate-600 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 tracking-[0.1em] group-hover:bg-white group-hover:border-primary/20 group-hover:text-primary transition-all">{{ $ticket->booking_reference }}</span>
                                <div class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mt-2">{{ $ticket->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-[11px] font-black text-slate-900 uppercase tracking-widest leading-none mb-2">{{ $ticket->ticketType->name }}</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Quantity: {{ $ticket->quantity }} Nodes</p>
                            </td>
                            <td class="px-10 py-8">
                                @if($ticket->status === 'confirmed')
                                    <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">VERIFIED</span>
                                @elseif($ticket->status === 'pending')
                                    <span class="status-pill bg-amber-50 text-amber-500 border-amber-100 tracking-widest">AWAITING PULSE</span>
                                @else
                                    <span class="status-pill bg-rose-50 text-rose-500 border-rose-100 tracking-widest">TERMINATED</span>
                                @endif
                            </td>
                            <td class="px-10 py-8">
                                @if($ticket->checked_in_at)
                                    <div class="flex items-center gap-3 text-primary">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-black uppercase tracking-widest">ARRIVED</div>
                                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ $ticket->checked_in_at->format('h:i A') }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3 text-slate-300">
                                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center">
                                            <i data-lucide="circle-dashed" class="w-4 h-4 animate-spin-slow"></i>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-widest">ABSENT NODE</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center bg-cream/20">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-8 text-slate-200 shadow-sm border border-slate-50">
                                    <i data-lucide="users" class="w-10 h-10"></i>
                                </div>
                                <h3 class="text-2xl font-serif text-slate-900 mb-2">No nodes registered.</h3>
                                <p class="text-slate-500 font-serif italic max-w-sm mx-auto text-base">"A silent architecture waiting for its first resident node." Explore the ecosystem to attract attendees.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($attendees->hasPages())
            <div class="p-10 border-t border-slate-50 bg-white">
                {{ $attendees->links() }}
            </div>
        @endif
    </div>
    
    <script>
        lucide.createIcons();
    </script>
</x-organizer-layout>
