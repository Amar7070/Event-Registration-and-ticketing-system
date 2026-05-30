<x-admin-layout>
    <x-slot name="header">
        Incentive Indexes
    </x-slot>

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 mb-12">
        <div>
            <h2 class="text-3xl font-serif text-slate-900 mb-2">Ecosystem Coupons</h2>
            <p class="text-slate-500 font-serif italic text-sm">"Governing promotional vectors and alignment indices across all experience nodes."</p>
        </div>
    </div>

    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Coupon Code</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Campaign Scope</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Discount Logic</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Saturation Limit</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Ecosystem Status</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($coupons as $coupon)
                        <tr class="group hover:bg-cream/50 transition-colors duration-300">
                            <!-- Coupon Code -->
                            <td class="px-8 py-7">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                        <i data-lucide="ticket" class="w-4.5 h-4.5"></i>
                                    </div>
                                    <div>
                                        <span class="font-mono font-black text-slate-900 text-sm tracking-wider uppercase select-all">{{ $coupon->code }}</span>
                                        <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Host: {{ $coupon->organizer->name }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Scope -->
                            <td class="px-8 py-7">
                                @if($coupon->event)
                                    <div>
                                        <span class="status-pill bg-amber-50 text-amber-600 border-amber-100 tracking-wider">EVENT SPECIFIC</span>
                                        <span class="block text-[9px] text-slate-500 font-serif italic truncate max-w-[180px] mt-1">{{ $coupon->event->title }}</span>
                                    </div>
                                @else
                                    <div>
                                        <span class="status-pill bg-emerald-50 text-emerald-600 border-emerald-100 tracking-wider">GLOBAL CAMPAIGN</span>
                                        <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-1">ALL HOST EXPERIENCES</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Value Logic -->
                            <td class="px-8 py-7">
                                <p class="font-serif text-slate-900 font-bold text-base">
                                    @if($coupon->type === 'percentage')
                                        {{ number_format($coupon->value, 0) }}% Off
                                    @else
                                        ₹{{ number_format($coupon->value, 0) }} Off
                                    @endif
                                </p>
                                <p class="text-[8px] text-slate-400 font-black uppercase tracking-widest mt-0.5">
                                    @if($coupon->min_order_amount > 0)
                                        Min ₹{{ number_format($coupon->min_order_amount) }}
                                    @endif
                                    @if($coupon->max_discount > 0)
                                        | Max ₹{{ number_format($coupon->max_discount) }}
                                    @endif
                                </p>
                            </td>

                            <!-- Usage Statistics -->
                            <td class="px-8 py-7">
                                <div class="flex flex-col gap-1">
                                    <div class="flex justify-between text-[10px] font-mono text-slate-500">
                                        <span>{{ $coupon->used_count }} Uses</span>
                                        <span class="text-slate-400">/ {{ $coupon->usage_limit ?? '∞' }} limit</span>
                                    </div>
                                    <div class="w-24 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        @php
                                            $percent = $coupon->usage_limit ? min(100, ($coupon->used_count / $coupon->usage_limit) * 100) : 0;
                                        @endphp
                                        <div class="bg-primary h-full rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Ecosystem Status & Toggle -->
                            <td class="px-8 py-7">
                                <div class="flex items-center gap-4">
                                    @if($coupon->is_active && $coupon->valid_until->isFuture())
                                        <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-wider">ACTIVE</span>
                                    @elseif($coupon->valid_until->isPast())
                                        <span class="status-pill bg-slate-100 text-slate-400 border-slate-200 tracking-wider">EXPIRED</span>
                                    @else
                                        <span class="status-pill bg-rose-50 text-rose-500 border-rose-100 tracking-wider">SUSPENDED</span>
                                    @endif

                                    <!-- Quick 1-click status switch -->
                                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="toggle_active" value="1">
                                        <button type="submit" class="w-8 h-8 rounded-full border border-slate-200 bg-white hover:border-primary hover:text-primary transition-all flex items-center justify-center text-slate-400" title="Toggle active status">
                                            <i data-lucide="power" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <!-- Operations -->
                            <td class="px-8 py-7 text-right">
                                <div class="flex items-center justify-end gap-6">
                                    <button onclick="openEditModal({{ json_encode($coupon) }})" 
                                            class="text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:text-primary-hover transition-colors">
                                        Modify
                                    </button>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Are you sure you want to purge this incentive code from the ecosystem?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-500 hover:text-rose-600 transition-colors">
                                            Purge
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-32 text-center text-slate-400 font-serif italic">
                                <div class="w-20 h-20 bg-cream rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                    <i data-lucide="ticket" class="w-10 h-10"></i>
                                </div>
                                <h3 class="text-xl font-serif text-slate-900 mb-2">No promotional nodes registered.</h3>
                                <p class="text-slate-500 font-serif italic">No coupons exist in the SmartEvent ecosystem yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($coupons->hasPages())
            <div class="p-8 border-t border-slate-50 bg-cream/10">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Coupon Modal -->
    <div id="couponModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCouponModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="couponForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                                <i data-lucide="ticket" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-serif text-slate-900" id="modal-title">Modify Coupon Node</h3>
                                <p class="text-[9px] text-slate-400 font-mono tracking-widest uppercase mt-0.5" id="coupon-node-code">CODE: ---</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Type and Value -->
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Discount Type</label>
                                    <select name="type" id="modal-type" required class="form-input">
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="flat">Flat Price (₹)</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Discount Value</label>
                                    <input type="number" name="value" id="modal-value" step="0.01" min="0" required class="form-input">
                                </div>
                            </div>

                            <!-- Min Order & Max Discount -->
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Min Order Amount (₹)</label>
                                    <input type="number" name="min_order_amount" id="modal-min-order" step="0.01" min="0" class="form-input">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Max Discount Limit (₹)</label>
                                    <input type="number" name="max_discount" id="modal-max-discount" step="0.01" min="0" class="form-input">
                                </div>
                            </div>

                            <!-- Limits -->
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Total Usage Limit</label>
                                    <input type="number" name="usage_limit" id="modal-usage-limit" min="1" class="form-input" placeholder="No Limit (∞)">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Usage Per User</label>
                                    <input type="number" name="usage_per_user" id="modal-usage-per-user" min="1" required class="form-input">
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Valid From</label>
                                    <input type="datetime-local" name="valid_from" id="modal-valid-from" required class="form-input">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Valid Until</label>
                                    <input type="datetime-local" name="valid_until" id="modal-valid-until" required class="form-input">
                                </div>
                            </div>

                            <!-- Active switch -->
                            <div class="pt-2">
                                <label class="flex items-center gap-4 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" id="modal-active" value="1" class="sr-only peer">
                                        <div class="w-12 h-6 bg-slate-100 rounded-full peer-checked:bg-primary transition-colors border border-slate-200"></div>
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6 shadow-sm"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">Enabled in Ecosystem</span>
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Toggle active status of this promo code</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-cream p-10 flex items-center justify-end gap-6">
                        <button type="button" onclick="closeCouponModal()" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-10 py-4 text-xs shadow-xl shadow-primary/20">
                            Apply Modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function formatDateToLocalDatetime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            
            // Format to YYYY-MM-DDTHH:MM local format expected by datetime-local input
            const pad = (num) => String(num).padStart(2, '0');
            const year = date.getFullYear();
            const month = pad(date.getMonth() + 1);
            const day = pad(date.getDate());
            const hours = pad(date.getHours());
            const minutes = pad(date.getMinutes());
            
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        function openEditModal(coupon) {
            document.getElementById('couponForm').action = `/admin/coupons/${coupon.id}`;
            document.getElementById('coupon-node-code').innerText = `CODE: ${coupon.code}`;
            
            document.getElementById('modal-type').value = coupon.type;
            document.getElementById('modal-value').value = coupon.value;
            document.getElementById('modal-min-order').value = coupon.min_order_amount || '';
            document.getElementById('modal-max-discount').value = coupon.max_discount || '';
            document.getElementById('modal-usage-limit').value = coupon.usage_limit || '';
            document.getElementById('modal-usage-per-user').value = coupon.usage_per_user;
            
            // Dates formatting for HTML input
            document.getElementById('modal-valid-from').value = formatDateToLocalDatetime(coupon.valid_from);
            document.getElementById('modal-valid-until').value = formatDateToLocalDatetime(coupon.valid_until);
            
            document.getElementById('modal-active').checked = coupon.is_active;
            
            document.getElementById('couponModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function closeCouponModal() {
            document.getElementById('couponModal').classList.add('hidden');
        }
        
        lucide.createIcons();
    </script>
</x-admin-layout>
