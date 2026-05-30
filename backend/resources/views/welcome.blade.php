<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartEvent | Rooted in Connection</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-cream selection:bg-primary selection:text-white overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-[100] transition-all duration-500 py-6 px-6 md:px-12 flex justify-between items-center" id="navbar">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/20">
                <i data-lucide="sprout" class="w-6 h-6"></i>
            </div>
            <span class="font-serif text-2xl tracking-tight text-slate-900">SmartEvent</span>
        </div>

        <div class="hidden lg:flex items-center gap-10">
            <a href="{{ route('events.index') }}" class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-primary transition-colors">Discover</a>
            <a href="{{ route('about') }}" class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-primary transition-colors">Philosophy</a>
            <a href="{{ route('pricing') }}" class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-primary transition-colors">Ecosystem</a>
        </div>

        <div class="flex items-center gap-6">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary text-[10px] py-3 tracking-widest">GO TO DASHBOARD</a>
            @else
                <a href="{{ route('login') }}" class="hidden sm:block text-[11px] font-black uppercase tracking-[0.2em] text-slate-900 hover:text-primary">Log in</a>
                <a href="{{ route('register') }}" class="btn-primary text-[10px] py-3 tracking-widest px-8">JOIN THE ECOSYSTEM</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden px-6 md:px-12 pt-20">
        <!-- Hero Background -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('smartevent_hero_forest_gathering_1778941027621.png') }}" class="w-full h-full object-cover scale-110 animate-[pulse_10s_infinite]" alt="Forest Gathering">
            <div class="absolute inset-0 bg-gradient-to-b from-cream/10 via-cream/30 to-cream"></div>
            <!-- Organic Overlay -->
            <div class="absolute inset-0 opacity-20 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/natural-paper.png')]"></div>
        </div>

        <div class="relative z-10 max-w-5xl text-center">
            <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-white/80 backdrop-blur-xl border border-white shadow-xl shadow-primary/5 mb-10 animate-fade-in">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-600">The Intentional Gathering Network</span>
            </div>
            <h1 class="text-6xl md:text-8xl font-serif tracking-tight text-slate-900 leading-[1.05] mb-10 opacity-0 translate-y-12 transition-all duration-1000 ease-out animate-slide-up">
                Events rooted in <span class="text-primary italic relative">genuine<span class="absolute bottom-1 left-0 w-full h-2 bg-primary/10 -rotate-1"></span></span> connection.
            </h1>
            <p class="text-xl md:text-2xl text-slate-600 max-w-3xl mx-auto mb-14 animate-fade-in delay-500 font-serif italic">
                A premium ecosystem designed for immersive experiences, organic networking, and intentional gathering. Build your forest, one seed at a time.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 animate-fade-in delay-700">
                <a href="{{ route('events.index') }}" class="btn-primary px-12 py-5 text-xs tracking-widest shadow-2xl shadow-primary/20">
                    EXPLORE THE MAP
                </a>
                <a href="{{ route('organizer.events.create') }}" class="btn-outline px-12 py-5 text-xs tracking-widest bg-white/40 backdrop-blur-xl border-slate-200">
                    HOST AN EXPERIENCE
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-3 opacity-30 animate-bounce">
            <span class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-400 rotate-90 translate-y-4">SCROLL</span>
            <div class="w-[1px] h-12 bg-gradient-to-b from-slate-400 to-transparent"></div>
        </div>
    </section>

    <!-- Trust Banner -->
    <section class="py-12 bg-white border-y border-slate-50 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-24 opacity-30 grayscale hover:grayscale-0 transition-all duration-700">
                <div class="flex items-center gap-2 font-serif text-xl">ECO<span class="font-bold">SYSTEM</span></div>
                <div class="flex items-center gap-2 font-serif text-xl">NATURE<span class="font-bold">QUEST</span></div>
                <div class="flex items-center gap-2 font-serif text-xl">RESONANCE</div>
                <div class="flex items-center gap-2 font-serif text-xl">GATHER<span class="font-bold">WELL</span></div>
                <div class="flex items-center gap-2 font-serif text-xl">BLUEPRINT</div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-32 px-6 md:px-12 max-w-7xl mx-auto overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
            <div class="max-w-2xl">
                <span class="text-primary text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">THE ARCHETYPES</span>
                <h2 class="text-4xl md:text-6xl font-serif text-slate-900 leading-tight">Start with inspiration.</h2>
                <p class="text-lg text-slate-500 italic font-serif mt-4">"Every intentional connection begins with a shared frequency."</p>
            </div>
            <a href="{{ route('events.index') }}" class="group flex items-center gap-3 text-[10px] font-black text-primary uppercase tracking-[0.3em]">
                VIEW ALL ARCHITECTURES
                <div class="w-10 h-10 rounded-full border border-primary/20 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all duration-500">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Category Card 1 -->
            <div class="premium-card group cursor-pointer border-none rounded-[2.5rem] shadow-none">
                <div class="relative h-[500px] overflow-hidden rounded-[2.5rem]">
                    <img src="https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Nature">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/20 to-transparent opacity-80"></div>
                    <div class="absolute bottom-10 left-10 text-white z-10">
                        <span class="status-pill bg-primary text-white border-none mb-4 tracking-[0.3em]">NATURE</span>
                        <h3 class="text-3xl font-serif mb-2">Sustainability Summits</h3>
                        <p class="text-sm text-slate-300 font-serif italic opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0">Aligning with the earth's rhythm.</p>
                    </div>
                </div>
            </div>

            <!-- Category Card 2 -->
            <div class="premium-card group cursor-pointer border-none rounded-[2.5rem] shadow-none">
                <div class="relative h-[500px] overflow-hidden rounded-[2.5rem]">
                    <img src="https://images.unsplash.com/photo-1514525253361-bee8718a300a?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Music">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/20 to-transparent opacity-80"></div>
                    <div class="absolute bottom-10 left-10 text-white z-10">
                        <span class="status-pill bg-primary text-white border-none mb-4 tracking-[0.3em]">MUSIC</span>
                        <h3 class="text-3xl font-serif mb-2">Acoustic Sunset Series</h3>
                        <p class="text-sm text-slate-300 font-serif italic opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0">Harmonizing in open spaces.</p>
                    </div>
                </div>
            </div>

            <!-- Category Card 3 -->
            <div class="premium-card group cursor-pointer border-none rounded-[2.5rem] shadow-none">
                <div class="relative h-[500px] overflow-hidden rounded-[2.5rem]">
                    <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Wellness">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/20 to-transparent opacity-80"></div>
                    <div class="absolute bottom-10 left-10 text-white z-10">
                        <span class="status-pill bg-primary text-white border-none mb-4 tracking-[0.3em]">WELLNESS</span>
                        <h3 class="text-3xl font-serif mb-2">Restorative Retreats</h3>
                        <p class="text-sm text-slate-300 font-serif italic opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0">Reconnecting with the inner node.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Prop Section -->
    <section class="bg-slate-900 py-32 px-6 md:px-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/10 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-24 items-center relative z-10">
            <div>
                <span class="text-primary text-[10px] font-black uppercase tracking-[0.4em] mb-6 block">INTELLIGENT ECOSYSTEM</span>
                <h2 class="text-5xl md:text-7xl font-serif mb-10 leading-tight">Modern tools for <span class="italic text-primary">unhurried</span> planning.</h2>
                <p class="text-xl text-slate-400 mb-14 leading-relaxed font-serif italic">
                    The SmartEvent platform provides a single source of truth for your UI language—ensuring every touchpoint, from the first invite to the final analytic report, feels unified and premium.
                </p>
                <div class="space-y-10">
                    <div class="flex items-start gap-6 group">
                        <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:border-primary transition-all duration-500">
                            <i data-lucide="shield-check" class="w-6 h-6 text-primary group-hover:text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-serif mb-2 text-white">Natural Design System</h4>
                            <p class="text-slate-500 text-sm">Consistent atomic units (4px) and organic spacing rhythm that breathes.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-6 group">
                        <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:border-primary transition-all duration-500">
                            <i data-lucide="type" class="w-6 h-6 text-primary group-hover:text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-serif mb-2 text-white">High-Contrast Typography</h4>
                            <p class="text-slate-500 text-sm">Merging functional Inter for data with emotional serif headers for connection.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="premium-card bg-slate-800 border-white/5 shadow-3xl p-4 rotate-6 transform translate-x-12 translate-y-12 z-10 hidden lg:block opacity-40">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=800" class="rounded-[2rem]" alt="Analytics Overview">
                </div>
                <div class="premium-card bg-white p-5 -rotate-3 relative z-20 shadow-2xl rounded-[3rem]">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800" class="rounded-[2.5rem] shadow-lg" alt="Dashboard">
                    <div class="absolute -bottom-10 -right-10 bg-primary p-8 rounded-[2rem] shadow-2xl hidden md:block animate-bounce">
                        <i data-lucide="zap" class="w-8 h-8 text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-32 px-6 md:px-12 text-center bg-cream relative overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="max-w-4xl mx-auto relative z-10">
            <h2 class="text-5xl md:text-7xl font-serif text-slate-900 mb-10 leading-tight">Ready to plant your seed?</h2>
            <p class="text-xl text-slate-500 mb-14 font-serif italic">Join the network of intentional gathering architects.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('register') }}" class="btn-primary px-16 py-6 text-xs tracking-widest shadow-2xl shadow-primary/20">
                    START THE JOURNEY
                </a>
                <a href="{{ route('about') }}" class="btn-outline px-16 py-6 text-xs tracking-widest border-primary/10">
                    LEARN THE PHILOSOPHY
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-24 px-6 md:px-12 border-t border-slate-100">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/20">
                            <i data-lucide="sprout" class="w-6 h-6"></i>
                        </div>
                        <span class="font-serif text-2xl tracking-tight text-slate-900">SmartEvent</span>
                    </div>
                    <p class="text-slate-500 font-serif italic text-lg max-w-sm mb-10">
                        "Architecting experiences that resonate across the organic network of human connection."
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all">
                            <i data-lucide="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all">
                            <i data-lucide="twitter" class="w-4 h-4"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all">
                            <i data-lucide="linkedin" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h5 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-900 mb-8">EXPLORE</h5>
                    <ul class="space-y-4 text-sm font-medium text-slate-500">
                        <li><a href="{{ route('events.index') }}" class="hover:text-primary transition-colors">Discover Map</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-primary transition-colors">Philosophy</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-primary transition-colors">Ecosystem</a></li>
                        <li><a href="{{ route('static.blog') }}" class="hover:text-primary transition-colors">Field Notes</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-900 mb-8">SUPPORT</h5>
                    <ul class="space-y-4 text-sm font-medium text-slate-500">
                        <li><a href="{{ route('static.help') }}" class="hover:text-primary transition-colors">Help Center</a></li>
                        <li><a href="{{ route('static.contact') }}" class="hover:text-primary transition-colors">Contact Node</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Privacy Ritual</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Terms of Gaia</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-10 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">
                    © 2024 SmartEvent Ecosystem. Rooted in Connection.
                </p>
                <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    SYSTEMS NOMINAL
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar blur on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('bg-white/80', 'backdrop-blur-xl', 'border-b', 'border-slate-100', 'py-4');
                nav.classList.remove('py-6');
            } else {
                nav.classList.remove('bg-white/80', 'backdrop-blur-xl', 'border-b', 'border-slate-100', 'py-4');
                nav.classList.add('py-6');
            }
        });

        // Initialize Lucide
        lucide.createIcons();

        // Reveal on scroll logic
        const observerOptions = { threshold: 0.1 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-slide-up').forEach(el => observer.observe(el));
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(3rem); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 1.2s ease-out forwards; }
        .animate-slide-up { animation: slide-up 1.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-700 { animation-delay: 0.7s; }
        
        /* Custom shadows */
        .shadow-3xl {
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.15);
        }
    </style>
</body>
</html>
