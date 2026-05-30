<x-app-layout>
    <!-- Header Section -->
    <section class="pt-48 pb-16 px-8 bg-cream border-b border-slate-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full opacity-5 pointer-events-none text-primary">
            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                <circle cx="100" cy="100" r="100" fill="currentColor"/>
            </svg>
        </div>

        <div class="max-w-[1440px] mx-auto relative z-10 text-center md:text-left">
            <span class="status-pill bg-primary/10 text-primary border-primary/20 mb-4 inline-block tracking-widest uppercase">IDENTITY CORE</span>
            <h1 class="heading-display text-slate-900">Manage your <span class="italic text-primary">resident</span> profile.</h1>
            <p class="text-lg text-slate-500 mt-4 max-w-xl mx-auto md:mx-0 font-serif italic">
                "Calibrating your presence within the ecosystem. Maintain your identity's integrity."
            </p>
        </div>
    </section>

    <div class="py-24 bg-cream min-h-screen px-8">
        <div class="max-w-[1440px] mx-auto space-y-12">
            
            <!-- Resident Identification -->
            <div class="premium-card bg-white border-slate-100 p-10 md:p-16 rounded-[3.5rem] shadow-3xl shadow-primary/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl"></div>
                <div class="max-w-3xl relative z-10">
                    <div class="flex items-center gap-6 mb-12">
                        <div class="w-14 h-14 bg-cream rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-serif text-slate-900">Resident Identification</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Update your primary identity markers</p>
                        </div>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Access Key Rotation -->
            <div class="premium-card bg-white border-slate-100 p-10 md:p-16 rounded-[3.5rem] shadow-3xl shadow-primary/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl"></div>
                <div class="max-w-3xl relative z-10">
                    <div class="flex items-center gap-6 mb-12">
                        <div class="w-14 h-14 bg-cream rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                            <i data-lucide="key" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-serif text-slate-900">Access Key Rotation</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Maintain identity access security</p>
                        </div>
                    </div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Identity Purge -->
            <div class="premium-card bg-rose-50/50 border-rose-100 p-10 md:p-16 rounded-[3.5rem] shadow-3xl shadow-rose-500/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500/5 rounded-full blur-3xl"></div>
                <div class="max-w-3xl relative z-10">
                    <div class="flex items-center gap-6 mb-12">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-rose-500 border border-rose-200">
                            <i data-lucide="trash-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-serif text-slate-900">Identity Purge</h3>
                            <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mt-1">Permanently disconnect from the ecosystem</p>
                        </div>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
