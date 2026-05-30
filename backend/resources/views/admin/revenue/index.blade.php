<x-admin-layout>
    <x-slot name="header">
        Financial Integrity
    </x-slot>

    <div class="mb-12">
        <h2 class="text-3xl font-serif text-slate-900 mb-2">Energy Yield Transactions</h2>
        <p class="text-slate-500 font-serif italic text-sm">"Monitoring the platform's vital energy exchange and distribution."</p>
    </div>

    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="p-10 border-b border-slate-50 bg-white sticky left-0 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-serif text-slate-900">Yield Ledger</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Platform-wide Exchange History</p>
            </div>
            <button class="btn-primary px-6 py-2.5 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="download" class="w-3 h-3"></i>
                Audit Export
            </button>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Reference Node / Temporal Point</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Contributor Identity</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Destination</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Gross Exchange</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-primary">Protocol Yield</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($payments as $payment)
                        <tr class="group hover:bg-cream/50 transition-colors duration-300">
                            <td class="px-10 py-8">
                                <div class="font-mono text-[10px] font-black text-slate-900 tracking-tighter group-hover:text-primary transition-colors">{{ $payment->payment_reference }}</div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $payment->created_at->format('M d, Y • H:i') }}</div>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-sm font-bold text-slate-900 leading-tight">{{ $payment->user->name }}</p>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-xs font-bold text-slate-500 truncate max-w-[150px]">{{ $payment->event->title }}</p>
                            </td>
                            <td class="px-10 py-8">
                                <span class="text-sm font-serif text-slate-900">₹{{ number_format($payment->amount) }}</span>
                            </td>
                            <td class="px-10 py-8">
                                <span class="text-sm font-serif text-primary font-bold">₹{{ number_format($payment->platform_fee) }}</span>
                            </td>
                            <td class="px-10 py-8">
                                @if($payment->status === 'completed')
                                    <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">SETTLED</span>
                                @else
                                    <span class="status-pill bg-amber-50 text-amber-500 border-amber-100 tracking-widest uppercase">{{ $payment->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-10 py-32 text-center text-slate-400 font-serif italic">No energy yield records detected.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
            <div class="p-10 border-t border-slate-50 bg-cream/10">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-admin-layout>
