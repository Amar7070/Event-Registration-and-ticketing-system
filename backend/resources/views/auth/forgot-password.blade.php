<x-guest-layout>
    <div class="mb-8 text-left">
        <h2 class="text-3xl font-serif text-slate-900 mb-2 leading-tight">Recover access</h2>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] leading-relaxed">
            Forgot your password? Enter your email and we'll send you a recovery link.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6 text-left">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus
                    placeholder="jane@example.com"
                    class="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-6">
            <button type="submit"
                class="w-full bg-[#4E7D5B] text-white py-5 rounded-full text-[11px] font-black uppercase tracking-[0.3em] shadow-xl shadow-[#4E7D5B]/20 hover:scale-[1.02] active:scale-95 transition-all">
                Send Recovery Link
            </button>
        </div>
    </form>
</x-guest-layout>
