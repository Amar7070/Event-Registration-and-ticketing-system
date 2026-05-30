<x-app-layout>
    <!-- Hero Section -->
    <section class="pt-48 pb-32 px-6 md:px-12 bg-cream overflow-hidden relative border-b border-slate-100 text-center">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[120px]"></div>
        </div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-primary/5 border border-primary/10 mb-10">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">ECOSYSTEM ACCESS</span>
            </div>
            <h1 class="text-6xl md:text-8xl font-serif tracking-tight text-slate-900 leading-[1.05] mb-12 max-w-4xl mx-auto">
                Transparent <span class="italic text-primary">yields</span> for every architect.
            </h1>
            <p class="text-xl md:text-2xl text-slate-500 max-w-3xl mx-auto font-serif italic leading-relaxed">
                "No hidden roots. Just simple architectures to help your community flourish."
            </p>
        </div>
    </section>

    <!-- Pricing Grid -->
    <section class="py-32 px-6 md:px-12 bg-white relative">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                
                <!-- Free Tier -->
                <div class="premium-card p-12 bg-white border-slate-100 rounded-[3rem] hover:shadow-3xl hover:shadow-primary/5 transition-all duration-700 flex flex-col">
                    <div class="mb-10">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 block">THE SEED</span>
                        <h3 class="text-3xl font-serif text-slate-900 mb-2">Essential</h3>
                        <div class="text-5xl font-serif text-primary mb-4">Free</div>
                        <p class="text-slate-500 font-serif italic text-sm">Perfect for small circles and grassroots gatherings.</p>
                    </div>
                    
                    <ul class="space-y-6 mb-12 flex-1">
                        <li class="flex items-center gap-4 text-sm font-medium text-slate-600">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            Up to 100 resident nodes
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-slate-600">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            Basic Experience Blueprint
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-slate-600">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            QR Identity Scanning
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-slate-600">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            72h Financial Yielding
                        </li>
                    </ul>

                    <a href="{{ route('register') }}" class="btn-outline w-full py-5 text-[10px] font-black uppercase tracking-[0.3em] rounded-full border-slate-100">
                        PLANT FOR FREE
                    </a>
                </div>

                <!-- Pro Tier (Highlighted) -->
                <div class="premium-card p-12 bg-slate-900 border-primary/20 rounded-[3rem] shadow-3xl shadow-primary/10 relative overflow-hidden flex flex-col scale-105 z-10">
                    <div class="absolute top-0 right-0 p-8">
                        <div class="status-pill bg-primary text-white border-none tracking-widest text-[9px] animate-pulse">MOST RESONANT</div>
                    </div>
                    <div class="mb-10 relative z-10">
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest mb-4 block">THE FOREST</span>
                        <h3 class="text-3xl font-serif text-white mb-2">Professional</h3>
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl font-serif text-white">₹2,999</span>
                            <span class="text-slate-400 font-serif italic text-lg">/month</span>
                        </div>
                        <p class="text-slate-400 font-serif italic text-sm mt-4">Deep insights and unlimited architectures for scaling communities.</p>
                    </div>
                    
                    <ul class="space-y-6 mb-12 flex-1 relative z-10">
                        <li class="flex items-center gap-4 text-sm font-medium text-white/80">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            Unlimited resident nodes
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-white/80">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            Premium Blueprint Layouts
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-white/80">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            Full Resonance Analytics
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-white/80">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            Priority Financial Yielding
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-white/80">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            White-label Digital Passports
                        </li>
                    </ul>

                    <a href="{{ route('register') }}" class="btn-primary w-full py-5 text-[10px] font-black uppercase tracking-[0.4em] rounded-full shadow-2xl shadow-primary/20">
                        START SCALE RITUAL
                    </a>
                </div>

                <!-- Enterprise Tier -->
                <div class="premium-card p-12 bg-white border-slate-100 rounded-[3rem] hover:shadow-3xl hover:shadow-primary/5 transition-all duration-700 flex flex-col">
                    <div class="mb-10">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 block">THE SYSTEM</span>
                        <h3 class="text-3xl font-serif text-slate-900 mb-2">Ecosystem</h3>
                        <div class="text-5xl font-serif text-slate-900 mb-4 font-bold tracking-tighter">Custom</div>
                        <p class="text-slate-500 font-serif italic text-sm">Bespoke architectures for large-scale enterprise gatherings.</p>
                    </div>
                    
                    <ul class="space-y-6 mb-12 flex-1">
                        <li class="flex items-center gap-4 text-sm font-medium text-slate-600">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            Dedicated Node Management
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-slate-600">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            SLA & Uptime Guarantees
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-slate-600">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            Advanced Security Protocols
                        </li>
                        <li class="flex items-center gap-4 text-sm font-medium text-slate-600">
                            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
                            Custom Integration Nodes
                        </li>
                    </ul>

                    <a href="{{ route('static.contact') }}" class="btn-outline w-full py-5 text-[10px] font-black uppercase tracking-[0.3em] rounded-full border-slate-100">
                        CONTACT THE NODE
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-32 px-6 md:px-12 bg-cream border-t border-slate-100">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-20">
                <span class="text-primary text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">TRANSPARENCY REPORT</span>
                <h2 class="text-4xl font-serif text-slate-900">Common Queries</h2>
            </div>
            
            <div class="space-y-8">
                <div class="premium-card p-8 bg-white border-slate-50 rounded-[2rem]">
                    <h4 class="text-xl font-serif text-slate-900 mb-4">How do transaction fees work?</h4>
                    <p class="text-slate-500 text-base font-serif italic leading-relaxed">
                        Every paid architecture incurs a small ecosystem fee of 2.5% + ₹10 per ticket. This yield is used to maintain the integrity of our digital soil and infrastructure.
                    </p>
                </div>
                <div class="premium-card p-8 bg-white border-slate-50 rounded-[2rem]">
                    <h4 class="text-xl font-serif text-slate-900 mb-4">Can I switch archetypes later?</h4>
                    <p class="text-slate-500 text-base font-serif italic leading-relaxed">
                        Absolutely. You can upgrade your forest's scale at any point during your gathering cycle. Downgrades take effect at the end of the current temporal period.
                    </p>
                </div>
                <div class="premium-card p-8 bg-white border-slate-50 rounded-[2rem]">
                    <h4 class="text-xl font-serif text-slate-900 mb-4">Is there a limit on free gatherings?</h4>
                    <p class="text-slate-500 text-base font-serif italic leading-relaxed">
                        We encourage organic growth. You can host as many free events as you need, provided each remains under the 100-node capacity limit.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
