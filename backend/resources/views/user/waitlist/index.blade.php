<x-app-layout>
    <!-- Header Section -->
    <section class="pt-48 pb-16 px-8 bg-cream border-b border-slate-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full opacity-5 pointer-events-none">
            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                <path d="M100 0C155.228 0 200 44.7715 200 100C200 155.228 155.228 200 100 200C44.7715 200 0 155.228 0 100C0 44.7715 44.7715 0 100 0Z" fill="currentColor" class="text-primary"/>
            </svg>
        </div>

        <div class="max-w-[1440px] mx-auto relative z-10 text-center md:text-left">
            <span class="status-pill bg-primary/10 text-primary border-primary/20 mb-4 inline-block tracking-widest uppercase">RESONANCE QUEUE</span>
            <h1 class="heading-display text-slate-900">Your <span class="italic text-primary">anticipation</span> nodes.</h1>
            <p class="text-lg text-slate-500 mt-4 max-w-xl mx-auto md:mx-0 font-serif italic">
                "Tracing the frequency of gatherings you are waiting to experience."
            </p>
        </div>
    </section>

    <div class="py-24 bg-cream min-h-screen px-8">
        <div class="max-w-[1440px] mx-auto">
            @if($entries->count() > 0)
                <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-3xl shadow-primary/5 rounded-[3rem]">
                    <div class="overflow-x-auto no-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-cream/30">
                                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Hub</th>
                                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Archetype Pass</th>
                                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Queue Position</th>
                                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Node Status</th>
                                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($entries as $entry)
                                    <tr class="group hover:bg-cream/50 transition-colors duration-500">
                                        <td class="px-10 py-8">
                                            <div class="font-bold text-slate-900 group-hover:text-primary transition-colors text-lg leading-tight">{{ $entry->event->title }}</div>
                                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $entry->event->start_date->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-10 py-8">
                                            <span class="text-[11px] font-black text-slate-600 uppercase tracking-widest">{{ $entry->ticketType->name }}</span>
                                        </td>
                                        <td class="px-10 py-8 text-center">
                                            <div class="inline-flex flex-col items-center">
                                                <span class="text-3xl font-serif text-primary leading-none">#{{ $entry->position }}</span>
                                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest mt-2">PRIORITY NODE</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-8">
                                            @if($entry->status === 'waiting')
                                                <span class="status-pill bg-amber-50 text-amber-500 border-amber-100 tracking-widest animate-pulse">AWAITING SPACE</span>
                                            @elseif($entry->status === 'notified')
                                                <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">SPOT OPENED</span>
                                            @elseif($entry->status === 'expired')
                                                <span class="status-pill bg-rose-50 text-rose-500 border-rose-100 tracking-widest">EXPIRED</span>
                                            @elseif($entry->status === 'converted')
                                                <span class="status-pill bg-cream text-slate-400 border-slate-100 tracking-widest">CONVERTED</span>
                                            @endif
                                        </td>
                                        <td class="px-10 py-8 text-right">
                                            @if($entry->status === 'notified')
                                                <div class="flex flex-col items-end gap-3">
                                                    <a href="{{ route('events.book', $entry->event) }}" class="btn-primary px-8 py-3 text-[10px] tracking-widest shadow-xl shadow-primary/20">
                                                        CLAIM SPOT
                                                    </a>
                                                    <span class="text-[9px] font-black text-rose-400 uppercase tracking-[0.2em] flex items-center gap-2 animate-pulse">
                                                        <i data-lucide="clock" class="w-3 h-3"></i> EXPIRES {{ strtoupper($entry->expires_at->diffForHumans()) }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">MONITORING PULSE</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-12">
                    {{ $entries->links() }}
                </div>
            @else
                <div class="py-40 text-center bg-white rounded-[4rem] border border-slate-100 border-dashed shadow-3xl shadow-primary/5 max-w-4xl mx-auto">
                    <div class="w-24 h-24 bg-cream rounded-full flex items-center justify-center mx-auto mb-10 text-primary/30">
                        <i data-lucide="clock" class="w-12 h-12"></i>
                    </div>
                    <h3 class="text-3xl font-serif text-slate-900 mb-4">No anticipation nodes active.</h3>
                    <p class="text-slate-500 mb-12 max-w-sm mx-auto font-serif italic text-lg leading-relaxed">
                        "Your frequency is currently clear. Find high-resonance gatherings to join the queue."
                    </p>
                    <a href="{{ route('events.index') }}" class="btn-primary px-12 py-5 text-sm font-bold tracking-tight shadow-2xl shadow-primary/20">
                        EXPLORE THE ECOSYSTEM
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
