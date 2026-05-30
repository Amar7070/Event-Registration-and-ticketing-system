<x-app-layout>
    <!-- Hero Section -->
    <section class="pt-48 pb-32 px-6 md:px-12 bg-cream overflow-hidden relative border-b border-slate-100 text-center">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        </div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-primary/5 border border-primary/10 mb-10">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">OPERATIONAL GUIDANCE</span>
            </div>
            <h1 class="text-6xl md:text-8xl font-serif tracking-tight text-slate-900 leading-[1.05] mb-12 max-w-4xl mx-auto">
                How can we <span class="italic text-primary">support</span> your journey?
            </h1>
            <p class="text-xl md:text-2xl text-slate-500 max-w-3xl mx-auto font-serif italic leading-relaxed mb-16">
                "Find clarity within the ecosystem. We've architected these guides to ensure your experience remains frictionless."
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto relative group">
                <div class="absolute inset-y-0 left-6 flex items-center text-slate-400 group-focus-within:text-primary transition-colors">
                    <i data-lucide="search" class="w-6 h-6"></i>
                </div>
                <input type="text" placeholder="Search guidance nodes, protocols, or support topics..." 
                       class="w-full pl-16 pr-8 py-6 rounded-[2rem] bg-white border border-slate-100 shadow-2xl shadow-primary/5 focus:ring-4 focus:ring-primary/10 focus:border-primary/20 transition-all outline-none text-lg font-serif italic">
            </div>
        </div>
    </section>

    <!-- Support Grid -->
    <section class="py-32 px-6 md:px-12 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Resident Support -->
                <div class="premium-card p-12 bg-white border-slate-100 rounded-[3.5rem] hover:shadow-3xl hover:shadow-primary/5 transition-all duration-700 flex flex-col group">
                    <div class="w-16 h-16 bg-cream rounded-2xl flex items-center justify-center text-primary mb-10 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                        <i data-lucide="ticket" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-serif text-slate-900 mb-8 leading-tight">Resident <span class="italic text-primary">Assistance</span></h3>
                    <ul class="space-y-6 flex-1">
                        <li>
                            <a href="#" class="flex items-center justify-between text-sm font-bold text-slate-500 hover:text-primary transition-colors group/link uppercase tracking-widest">
                                Booking Protocol
                                <i data-lucide="chevron-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-between text-sm font-bold text-slate-500 hover:text-primary transition-colors group/link uppercase tracking-widest">
                                Payment Frequency
                                <i data-lucide="chevron-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-between text-sm font-bold text-slate-500 hover:text-primary transition-colors group/link uppercase tracking-widest">
                                Waitlist Resonance
                                <i data-lucide="chevron-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Architect Support -->
                <div class="premium-card p-12 bg-slate-900 text-white rounded-[3.5rem] shadow-3xl shadow-primary/10 flex flex-col group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex-1">
                        <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center text-primary mb-10 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500 border border-white/5">
                            <i data-lucide="layout" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-2xl font-serif text-white mb-8 leading-tight">Architect <span class="italic text-primary">Guidance</span></h3>
                        <ul class="space-y-6">
                            <li>
                                <a href="#" class="flex items-center justify-between text-[10px] font-black text-slate-400 hover:text-white transition-colors group/link uppercase tracking-widest">
                                    Blueprint Construction
                                    <i data-lucide="chevron-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center justify-between text-[10px] font-black text-slate-400 hover:text-white transition-colors group/link uppercase tracking-widest">
                                    Identity Verification Hub
                                    <i data-lucide="chevron-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center justify-between text-[10px] font-black text-slate-400 hover:text-white transition-colors group/link uppercase tracking-widest">
                                    Financial Yield Payouts
                                    <i data-lucide="chevron-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Safety Support -->
                <div class="premium-card p-12 bg-white border-slate-100 rounded-[3.5rem] hover:shadow-3xl hover:shadow-primary/5 transition-all duration-700 flex flex-col group">
                    <div class="w-16 h-16 bg-cream rounded-2xl flex items-center justify-center text-primary mb-10 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                        <i data-lucide="shield-check" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-serif text-slate-900 mb-8 leading-tight">Security <span class="italic text-primary">Protocols</span></h3>
                    <ul class="space-y-6 flex-1">
                        <li>
                            <a href="#" class="flex items-center justify-between text-sm font-bold text-slate-500 hover:text-primary transition-colors group/link uppercase tracking-widest">
                                Identity Recovery
                                <i data-lucide="chevron-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-between text-sm font-bold text-slate-500 hover:text-primary transition-colors group/link uppercase tracking-widest">
                                Terminating Connections
                                <i data-lucide="chevron-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-between text-sm font-bold text-slate-500 hover:text-primary transition-colors group/link uppercase tracking-widest">
                                Ecosystem Governance
                                <i data-lucide="chevron-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Still need help? -->
    <section class="py-32 px-6 md:px-12 bg-cream text-center border-t border-slate-100">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl md:text-6xl font-serif text-slate-900 mb-10 leading-tight">Can't find the <span class="italic text-primary">resonance</span> you need?</h2>
            <p class="text-xl text-slate-500 mb-14 font-serif italic">Our support nodes are standing by to assist with any operational queries.</p>
            <a href="{{ route('contact') }}" class="btn-primary px-16 py-6 text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/20">
                INITIATE DIRECT SYNC
            </a>
        </div>
    </section>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
