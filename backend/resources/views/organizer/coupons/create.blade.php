<x-organizer-layout>
    <x-slot name="header">
        Create Coupon for {{ $event->title }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <form action="{{ route('organizer.events.coupons.store', $event) }}" method="POST">
                @csrf
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center">
                            <i data-lucide="ticket" class="w-5 h-5 mr-2 text-indigo-500"></i> Coupon Details
                        </h2>
                    </div>

                    <div class="p-8 space-y-8">
                        <!-- Code -->
                        <div>
                            <label for="code" class="block text-sm font-semibold text-slate-700 mb-1">Coupon Code</label>
                            <input type="text" name="code" id="code" required value="{{ old('code') }}" placeholder="e.g. EARLYBIRD20" style="text-transform:uppercase"
                                class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3 font-mono">
                            <p class="text-xs text-slate-500 mt-2">Customers will enter this code at checkout.</p>
                            @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Discount Type and Value -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type" class="block text-sm font-semibold text-slate-700 mb-1">Discount Type</label>
                                <select name="type" id="type" required
                                    class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="flat" {{ old('type') == 'flat' ? 'selected' : '' }}>Flat Amount (₹)</option>
                                </select>
                                @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="value" class="block text-sm font-semibold text-slate-700 mb-1">Discount Value</label>
                                <input type="number" step="0.01" name="value" id="value" required value="{{ old('value') }}"
                                    class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                @error('value') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Limits -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                            <div>
                                <label for="max_discount" class="block text-sm font-semibold text-slate-700 mb-1">Maximum Discount (₹)</label>
                                <input type="number" step="0.01" name="max_discount" id="max_discount" value="{{ old('max_discount') }}" placeholder="Optional"
                                    class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                <p class="text-[11px] text-slate-500 mt-1">Useful for percentage discounts (e.g. 20% off up to ₹500)</p>
                            </div>

                            <div>
                                <label for="min_order_amount" class="block text-sm font-semibold text-slate-700 mb-1">Minimum Order Amount (₹)</label>
                                <input type="number" step="0.01" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount') }}" placeholder="Optional"
                                    class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                            </div>

                            <div>
                                <label for="usage_limit" class="block text-sm font-semibold text-slate-700 mb-1">Total Usage Limit</label>
                                <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}" placeholder="Unlimited if empty"
                                    class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                <p class="text-[11px] text-slate-500 mt-1">Total number of times this coupon can be used across all users</p>
                            </div>

                            <div>
                                <label for="usage_per_user" class="block text-sm font-semibold text-slate-700 mb-1">Limit Per User</label>
                                <input type="number" name="usage_per_user" id="usage_per_user" required value="{{ old('usage_per_user', 1) }}"
                                    class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                            </div>
                        </div>

                        <!-- Validity Period -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                            <div>
                                <label for="valid_from" class="block text-sm font-semibold text-slate-700 mb-1">Valid From</label>
                                <input type="datetime-local" name="valid_from" id="valid_from" required value="{{ old('valid_from', now()->format('Y-m-d\TH:i')) }}"
                                    class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                @error('valid_from') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="valid_until" class="block text-sm font-semibold text-slate-700 mb-1">Valid Until</label>
                                <input type="datetime-local" name="valid_until" id="valid_until" required value="{{ old('valid_until', now()->addDays(7)->format('Y-m-d\TH:i')) }}"
                                    class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                @error('valid_until') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="pt-6 border-t border-slate-100 space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Coupon is Active</span>
                                    <span class="block text-xs text-slate-500 mt-0.5">Toggle to disable this coupon temporarily</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                <input type="checkbox" name="apply_to_all_events" value="1" {{ old('apply_to_all_events') ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800">Apply to Overall (All Events)</span>
                                    <span class="block text-xs text-slate-500 mt-0.5">If checked, this coupon can be used for any event you organize. If unchecked, it will only apply to this event.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100 flex justify-end gap-3">
                        <a href="{{ route('organizer.events.coupons.index', $event) }}" class="px-6 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition-colors">
                            Create Coupon
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-organizer-layout>
