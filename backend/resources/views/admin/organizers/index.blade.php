<x-admin-layout>
    <x-slot name="header">
        Host Control Directory
    </x-slot>

    <!-- Header Section -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-serif text-slate-900 mb-2">Organizer Ecosystem Matrix</h2>
            <p class="text-slate-500 font-serif italic text-sm">"Monitor, audit, and regulate host nodes to ensure a healthy event ecosystem."</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.organizers.pending') }}" class="px-5 py-3 rounded-full border border-primary/20 text-xs font-black uppercase tracking-widest text-primary bg-primary/5 hover:bg-primary hover:text-white transition-all flex items-center gap-2">
                <i data-lucide="check-square" class="w-4 h-4"></i> Pending Applications
                @php $pendingCount = \App\Models\User::role('organizer')->where('is_approved', false)->count(); @endphp
                @if($pendingCount > 0)
                    <span class="bg-primary text-white text-[9px] font-black rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- Filters & Search Card -->
    <div class="premium-card bg-white border-slate-100 p-6 mb-8 shadow-xl shadow-primary/5">
        <form method="GET" action="{{ route('admin.organizers.index') }}" class="grid grid-cols-1 md:grid-cols-[2fr_1.2fr_0.8fr] gap-4 items-center">
            <!-- Search -->
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search hosts by name or email..." 
                       class="form-input pl-12">
            </div>

            <!-- Status Dropdown -->
            <div class="relative">
                <select name="status" class="form-input appearance-none bg-no-repeat bg-right pr-10 cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Nodes</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended Nodes</option>
                </select>
                <span class="absolute inset-y-0 right-4 flex items-center text-slate-400 pointer-events-none">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </span>
            </div>

            <!-- Action Button -->
            <div class="flex gap-2">
                <button type="submit" class="btn-primary py-3 text-xs w-full">
                    Filter Nodes
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.organizers.index') }}" class="w-12 h-12 flex items-center justify-center rounded-xl bg-slate-50 border border-slate-200 text-slate-400 hover:text-slate-600 transition-colors flex-shrink-0" title="Reset Filters">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Organizers Table Card -->
    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="p-8 border-b border-slate-50 bg-white">
            <h3 class="text-xl font-serif text-slate-900">Registered Host Entities</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Ecosystem Population: {{ $organizers->total() }} Verified Hosts</p>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Host Node Details</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Governance Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experiences</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Ecosystem Yield</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Commission date</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($organizers as $organizer)
                        <tr class="group hover:bg-cream/30 transition-colors duration-300">
                            <!-- Host details -->
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black text-sm group-hover:scale-105 transition-transform duration-500">
                                        {{ substr($organizer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 group-hover:text-primary transition-colors text-base leading-tight">{{ $organizer->name }}</p>
                                        <p class="text-[10px] text-slate-450 font-bold uppercase tracking-wider mt-0.5">{{ $organizer->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <!-- Status -->
                            <td class="px-8 py-6">
                                @if($organizer->is_active)
                                    <span class="bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Active Node</span>
                                @else
                                    <span class="bg-rose-500/10 text-rose-600 border border-rose-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Suspended</span>
                                @endif
                            </td>
                            <!-- Event count -->
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-slate-700">{{ $organizer->organized_events_count }} Events</span>
                            </td>
                            <!-- Total Revenue -->
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-slate-900">₹{{ number_format($organizer->total_revenue) }}</span>
                            </td>
                            <!-- Created Date -->
                            <td class="px-8 py-6">
                                <p class="text-xs font-bold text-slate-700">{{ $organizer->created_at->format('M d, Y') }}</p>
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mt-0.5">{{ $organizer->created_at->diffForHumans() }}</p>
                            </td>
                            <!-- Action button -->
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('admin.organizers.show', $organizer) }}" class="inline-flex items-center gap-2 px-4.5 py-2.5 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black text-[10px] uppercase tracking-widest rounded-full hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white hover:shadow-lg hover:shadow-primary/5 transition-all border border-slate-150 dark:border-slate-800">
                                    <i data-lucide="bar-chart-2" class="w-3.5 h-3.5"></i> Audit Hub
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="w-20 h-20 bg-cream rounded-full flex items-center justify-center mx-auto mb-6 text-primary/20">
                                    <i data-lucide="users" class="w-10 h-10"></i>
                                </div>
                                <h3 class="text-xl font-serif text-slate-900 mb-2">No Active Hosts Found.</h3>
                                <p class="text-slate-500 font-serif italic text-sm">No host records matched your search query or filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($organizers->hasPages())
            <div class="p-8 border-t border-slate-50 bg-cream/10">
                {{ $organizers->links() }}
            </div>
        @endif
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-admin-layout>
