<x-guest-layout>
    <div class="mb-8 text-center text-left">
        <div class="w-20 h-20 bg-[#4E7D5B]/10 rounded-3xl flex items-center justify-center mx-auto mb-6 text-[#4E7D5B]">
            <i data-lucide="shield-check" class="w-10 h-10 text-[#4E7D5B]"></i>
        </div>
        <h2 class="text-3xl font-serif text-slate-900 leading-tight">Two-Step Verification</h2>
        <p class="mt-3 text-xs font-bold text-slate-400 uppercase tracking-widest px-4 leading-relaxed">
            A 6-digit verification code has been sent to your registered email.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('verify-2fa.verify') }}" class="space-y-6 text-center">
        @csrf

        <!-- OTP Input -->
        <div class="mb-8">
            <input id="otp" class="block w-full text-center text-3xl font-black tracking-[0.5em] py-6 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm" 
                          type="text" name="otp" :value="old('otp')" required autofocus placeholder="000000" maxlength="6" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2 text-center" />
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full bg-[#4E7D5B] text-white py-5 rounded-full text-[11px] font-black uppercase tracking-[0.3em] shadow-xl shadow-[#4E7D5B]/20 hover:scale-[1.02] active:scale-95 transition-all">
                Verify Account
            </button>
        </div>
    </form>

    <div class="mt-10 text-center">
        <form method="POST" action="{{ route('verify-2fa.resend') }}">
            @csrf
            <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">
                Didn't receive the code?
                <button type="submit" class="ml-1 text-[#4E7D5B] hover:underline underline-offset-2 transition-colors">
                    Resend OTP
                </button>
            </p>
        </form>
    </div>

    <script>
        lucide.createIcons();
        // Auto-submit when 6 digits are entered
        document.getElementById('otp').addEventListener('input', function(e) {
            if (this.value.length === 6) {
                this.form.submit();
            }
        });
    </script>
</x-guest-layout>
