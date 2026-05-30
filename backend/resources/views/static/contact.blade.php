<x-app-layout>
    <!-- Hero Section -->
    <section class="pt-48 pb-32 px-6 md:px-12 bg-cream overflow-hidden relative border-b border-slate-100 text-center">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        </div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-primary/5 border border-primary/10 mb-10">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">NODE CONNECTION</span>
            </div>
            <h1 class="text-6xl md:text-8xl font-serif tracking-tight text-slate-900 leading-[1.05] mb-12 max-w-4xl mx-auto">
                Initiate a <span class="italic text-primary">direct</span> sync.
            </h1>
            <p class="text-xl md:text-2xl text-slate-500 max-w-3xl mx-auto font-serif italic leading-relaxed">
                "Whether you're an architect or a resident, we're here to ensure the connection remains grounded."
            </p>
        </div>
    </section>

    <section class="py-32 px-6 md:px-12 bg-white relative">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-stretch">
                <!-- Info Panel -->
                <div class="premium-card p-12 bg-slate-900 text-white rounded-[3rem] relative overflow-hidden flex flex-col shadow-3xl shadow-primary/10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-[100px] translate-x-1/2 -translate-y-1/2"></div>
                    
                    <div class="relative z-10 flex-1">
                        <span class="text-primary text-[10px] font-black uppercase tracking-[0.4em] mb-12 block">COORDINATION POINTS</span>
                        <h3 class="text-4xl font-serif mb-12 leading-tight">Ecosystem <span class="italic text-primary">Hubs</span>.</h3>
                        
                        <div class="space-y-12">
                            <div class="flex items-start gap-8 group">
                                <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-primary border border-white/5 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                    <i data-lucide="mail" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Digital Identity Sync</span>
                                    <span class="text-xl font-serif italic">support@smartevent.com</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-8 group">
                                <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-primary border border-white/5 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                    <i data-lucide="phone" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Operational Pulse</span>
                                    <span class="text-xl font-serif italic">+91 (800) 123-4567</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-8 group">
                                <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-primary border border-white/5 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Physical Sanctuary</span>
                                    <span class="text-xl font-serif italic leading-relaxed">123 Event St, Mumbai,<br>MH 400001</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-10 pt-12 border-t border-white/5 flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-slate-500">
                        <i data-lucide="activity" class="w-3 h-3 text-primary animate-pulse"></i>
                        Monitoring Connection Quality
                    </div>
                </div>

                <!-- Form Panel -->
                <div class="premium-card p-12 md:p-20 bg-white border-slate-100 rounded-[3rem] shadow-3xl shadow-primary/5">
                    @if(session('success'))
                        <div class="mb-12 bg-primary/10 border border-primary/20 text-primary px-8 py-5 rounded-2xl flex items-center gap-4 animate-fade-in">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-10">
                        @csrf
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Resident Identity (Name)</label>
                            <input name="name" type="text" value="{{ old('name') }}" required 
                                   class="form-input" placeholder="e.g. Julian Architect">
                            @error('name')<p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Identity Endpoint (Email)</label>
                            <input name="email" type="email" value="{{ old('email') }}" required 
                                   class="form-input" placeholder="julian@ecosystem.com">
                            @error('email')<p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">The Reflection (Message)</label>
                            <textarea name="message" rows="5" required 
                                      class="form-input" placeholder="Describe the nature of your connection request...">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>@enderror
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="btn-primary w-full py-5 text-sm font-bold tracking-tight shadow-2xl shadow-primary/20">
                                DISPATCH REQUEST
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
