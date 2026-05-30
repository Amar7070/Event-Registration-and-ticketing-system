<x-organizer-layout>
    <x-slot name="header">
        All Attendees
    </x-slot>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Global Attendee Roster</h2>
        <p class="text-sm text-slate-500">Search and manage all participants across all your events.</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <form action="{{ route('organizer.attendees.index') }}" method="GET" class="p-4">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or booking reference..." 
                       class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[11px] text-slate-400 uppercase tracking-wider bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 font-bold">Attendee</th>
                        <th class="px-6 py-4 font-bold">Event</th>
                        <th class="px-6 py-4 font-bold">Ref / Type</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 text-right font-bold">Check-in</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendees as $ticket)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $ticket->user->name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $ticket->user->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-700 truncate max-w-[200px]">{{ $ticket->event->title }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-mono text-[10px] font-bold text-slate-500">{{ $ticket->booking_reference }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $ticket->ticketType->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($ticket->status === 'confirmed')
                                    <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-[10px] font-bold">CONFIRMED</span>
                                @else
                                    <span class="text-slate-400 bg-slate-50 px-2 py-0.5 rounded text-[10px] font-bold">{{ strtoupper($ticket->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($ticket->checked_in_at)
                                    <span class="text-indigo-600 font-bold text-[10px]">{{ $ticket->checked_in_at->format('h:i A') }}</span>
                                @else
                                    <span class="text-slate-300 italic text-[10px]">Not checked in</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">No attendees found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($attendees->hasPages())
            <div class="p-6 border-t border-slate-100">
                {{ $attendees->links() }}
            </div>
        @endif
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-organizer-layout>
