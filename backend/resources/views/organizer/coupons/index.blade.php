<x-organizer-layout>
    <x-slot name="header">
        Resonance Incentives — {{ $event->title }}
    </x-slot>

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-12 gap-8">
        <div>
            <h2 class="text-3xl font-serif text-slate-900 mb-2">Resonance Incentives</h2>
            <p class="text-slate-500 font-serif italic text-sm">"Architecting promotional codes to amplify the ecosystem pulse."</p>
        </div>
        <a href="{{ route('organizer.events.coupons.create', $event) }}" class="btn-primary px-8 py-3.5 text-[10px] tracking-widest shadow-xl shadow-primary/20">
            <i data-lucide="plus" class="w-4 h-4"></i> NEW INCENTIVE NODE
        </a>
    </div>

    @if(session('success'))
        <div class="mb-10 bg-primary/10 border border-primary/20 text-primary px-8 py-5 rounded-[2rem] flex items-center gap-4 animate-fade-in shadow-xl shadow-primary/5">
            <div class="w-8 h-8 bg-primary rounded-xl flex items-center justify-center text-white">
                <i data-lucide="check" class="w-4 h-4"></i>
            </div>
            <p class="text-[11px] font-black uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-3xl shadow-primary/5 rounded-[2.5rem]">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Voucher Code</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Yield Reduction</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Resonance Limit</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Temporal Window</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($coupons as $coupon)
                        <tr class="group hover:bg-cream/50 transition-colors duration-500">
                            <td class="px-10 py-8">
                                <span class="font-mono font-black text-slate-900 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 tracking-widest text-sm group-hover:bg-white group-hover:border-primary/20 group-hover:text-primary transition-all">{{ $coupon->code }}</span>
                            </td>
                            <td class="px-10 py-8">
                                <div class="text-lg font-serif text-primary">
                                    @if($coupon->type === 'percentage')
                                        {{ $coupon->value }}% <span class="text-[10px] uppercase font-black tracking-widest text-slate-400 ml-1">OFF</span>
                                    @else
                                        ₹{{ number_format($coupon->value) }} <span class="text-[10px] uppercase font-black tracking-widest text-slate-400 ml-1">OFF</span>
                                    @endif
                                </div>
                                @if($coupon->max_discount)
                                    <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1">Up to ₹{{ $coupon->max_discount }}</div>
                                @endif
                            </td>
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-bold text-slate-900">{{ $coupon->used_count ?? 0 }}</span>
                                    <div class="w-10 h-1 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="bg-primary h-full" style="width: {{ $coupon->usage_limit ? ($coupon->used_count / $coupon->usage_limit) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">/ {{ $coupon->usage_limit ?? '∞' }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <div class="flex flex-col gap-1">
                                    <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest flex items-center gap-2">
                                        <i data-lucide="calendar" class="w-3 h-3"></i> FROM {{ $coupon->valid_from->format('M d') }}
                                    </div>
                                    <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest flex items-center gap-2">
                                        <i data-lucide="clock" class="w-3 h-3"></i> UNTIL {{ $coupon->valid_until->format('M d') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                @if($coupon->is_active && $coupon->valid_until->isFuture())
                                    <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">ACTIVE NODE</span>
                                @else
                                    <span class="status-pill bg-rose-50 text-rose-500 border-rose-100 tracking-widest">EXPIRED</span>
                                @endif
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex items-center justify-end gap-6">
                                    <a href="{{ route('organizer.events.coupons.edit', [$event, $coupon]) }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-primary transition-colors">
                                        RE-CONSTRUCT
                                    </a>
                                    <form action="{{ route('organizer.events.coupons.destroy', [$event, $coupon]) }}" method="POST" onsubmit="return confirm('Are you sure you want to terminate this incentive node?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-300 hover:text-rose-500 transition-colors">
                                            TERMINATE
                                        </a>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-10 py-32 text-center bg-cream/20">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-8 text-slate-200 shadow-sm border border-slate-50">
                                    <i data-lucide="ticket" class="w-10 h-10"></i>
                                </div>
                                <h3 class="text-2xl font-serif text-slate-900 mb-2">No incentives deployed.</h3>
                                <p class="text-slate-500 font-serif italic max-w-sm mx-auto mb-10 text-base">Amplify the gathering's resonance by creating promotional vouchers for arriving nodes.</p>
                                <a href="{{ route('organizer.events.coupons.create', $event) }}" class="btn-primary px-12 py-5 text-xs tracking-widest">
                                    DEPLOY FIRST INCENTIVE
                                </a>
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
