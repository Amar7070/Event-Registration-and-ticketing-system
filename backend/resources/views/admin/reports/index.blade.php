<x-admin-layout>
    <x-slot name="header">
        Security & Copyright Audits
    </x-slot>

    <div class="mb-12">
        <h2 class="text-3xl font-serif text-slate-900 mb-2">Ecosystem Compliance & Legal Monitor</h2>
        <p class="text-slate-500 font-serif italic text-sm">"Reviewing intellectual property infringement and illegal content complaints."</p>
    </div>

    @if(session('success'))
        <div class="mb-8 p-6 bg-primary/10 border border-primary/20 text-primary rounded-[2rem] font-serif italic text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="p-10 border-b border-slate-50 flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white">
            <div>
                <h3 class="text-xl font-serif text-slate-900">Complaints Register</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Total Filed Audits: {{ $reports->total() }}</p>
            </div>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Target Experience</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Subject</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Complainant</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Evidence / Context</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Audit Status</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Oversight Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reports as $report)
                        <tr class="group hover:bg-cream/50 transition-colors duration-300">
                            <td class="px-10 py-8">
                                @if($report->event)
                                    <div class="flex flex-col">
                                        <a href="{{ route('events.show', $report->event->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-primary transition-colors text-base leading-tight">
                                            {{ $report->event->title }}
                                        </a>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">HOST: {{ $report->event->organizer?->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Deleted Event</span>
                                @endif
                            </td>
                            <td class="px-10 py-8">
                                <span class="status-pill bg-rose-50 text-rose-600 border-rose-100 tracking-widest text-[9px] uppercase">
                                    {{ $report->subject }}
                                </span>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-sm font-bold text-slate-700 leading-tight">{{ $report->user->name }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $report->user->email }}</p>
                            </td>
                            <td class="px-10 py-8 max-w-xs">
                                <p class="text-xs text-slate-600 leading-relaxed font-serif italic mb-2">"{{ $report->description }}"</p>
                                @if($report->evidence_url)
                                    <a href="{{ $report->evidence_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-[9px] font-black text-primary hover:text-primary-dark uppercase tracking-widest">
                                        <i data-lucide="link" class="w-3 h-3"></i> View Proof
                                    </a>
                                @endif
                            </td>
                            <td class="px-10 py-8">
                                @if($report->status === 'pending')
                                    <span class="status-pill bg-amber-50 text-amber-600 border-amber-200 tracking-widest">PENDING AUDIT</span>
                                @elseif($report->status === 'resolved')
                                    <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">RESOLVED</span>
                                @else
                                    <span class="status-pill bg-slate-100 text-slate-400 border-slate-200 tracking-widest">DISMISSED</span>
                                @endif
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex items-center justify-end gap-3" x-data="{ openRestrict: false }">
                                    @if($report->event)
                                        <!-- Check Event -->
                                        <a href="{{ route('events.show', $report->event->slug) }}" target="_blank" 
                                           class="w-10 h-10 flex items-center justify-center rounded-xl bg-cream text-slate-400 hover:text-primary hover:bg-white hover:border-primary/20 border border-transparent transition-all" 
                                           title="Audit Experience Blueprint">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>

                                        <!-- Restrict Toggle -->
                                        <form action="{{ route('admin.events.restrict', $report->event) }}" method="POST" class="inline">
                                            @csrf
                                            @if($report->event->is_restricted)
                                                <button type="submit" class="px-4 py-2 text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full hover:bg-emerald-100 transition-colors">
                                                    Lift Restriction
                                                </button>
                                            @else
                                                <button type="button" @click="openRestrict = true" class="px-4 py-2 text-[9px] font-black uppercase tracking-widest bg-rose-50 text-rose-600 border border-rose-100 rounded-full hover:bg-rose-100 transition-colors">
                                                    Restrict Node
                                                </button>

                                                <!-- Restrict Reason Modal Overlay -->
                                                <div x-show="openRestrict" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all text-left" style="display: none;">
                                                    <div class="relative w-full max-w-md bg-white border border-slate-100 shadow-2xl rounded-[3rem] p-12 overflow-hidden" @click.away="openRestrict = false">
                                                        <h3 class="text-xl font-serif text-slate-900 mb-6">Restrict Architecture</h3>
                                                        <div class="space-y-6">
                                                            <div>
                                                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Restriction Reason</label>
                                                                <textarea name="restriction_reason" placeholder="Copyright Infringement..." required class="w-full bg-cream border-slate-100 focus:border-rose-400 focus:ring-0 text-sm text-slate-800 py-4 px-6 rounded-3xl">Suspended due to Copyright Infringement</textarea>
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
                                    @endif

                                    <!-- Resolve / Dismiss Report -->
                                    @if($report->status === 'pending')
                                        <form action="{{ route('admin.copyright-reports.resolve', $report) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action_type" value="resolved">
                                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all" title="Mark Resolved">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.copyright-reports.resolve', $report) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action_type" value="dismissed">
                                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition-all" title="Dismiss Complaint">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-10 py-32 text-center text-slate-400 font-serif italic">No copyright infringement or illegal content reports filed.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="p-10 border-t border-slate-50 bg-cream/10">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-admin-layout>
