<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SmartEvent') }} | Secure Management</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#FDFBF7] selection:bg-[#4E7D5B] selection:text-white" 
      x-data="{ scrolled: false, showAvailability: false }" 
      @scroll.window="scrolled = window.pageYOffset > 20">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-500" 
         :class="scrolled ? 'bg-white/90 backdrop-blur-xl border-b border-slate-100 py-4 shadow-sm' : 'py-8'">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <div class="flex items-center justify-between">
                
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-[#4E7D5B] rounded-xl flex items-center justify-center text-white transition-transform group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</span>
                </a>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center gap-10">
                    <a href="{{ route('events.index') }}" class="text-xs font-black text-slate-500 uppercase tracking-widest hover:text-[#4E7D5B] transition-colors">Browse Events</a>
                    <a href="{{ route('dashboard') }}" class="text-xs font-black text-slate-500 uppercase tracking-widest hover:text-[#4E7D5B] transition-colors">Dashboard</a>
                    <a href="{{ route('user.tickets.index') }}" class="text-xs font-black text-slate-500 uppercase tracking-widest hover:text-[#4E7D5B] transition-colors">My Tickets</a>
                </div>

                <!-- Auth Actions -->
                <div class="flex items-center gap-4">
                    <x-theme-toggle />
                    @guest
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex px-6 py-3 border border-slate-200 text-[#4E7D5B] bg-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-50 transition-all">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-[#4E7D5B] text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/20 hover:scale-105 transition-all">
                            Get Started
                        </a>
                    @else
                        <!-- Alpine.js dropdown for Profile Menu -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                                <div class="w-10 h-10 rounded-full bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 flex items-center justify-center text-[#4E7D5B] font-bold text-xs uppercase shadow-sm">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100" 
                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-3 w-56 bg-white border border-slate-100 rounded-2xl shadow-xl shadow-[#4E7D5B]/5 py-2 z-50 text-left"
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-slate-50">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Signed In As</p>
                                    <p class="text-xs font-bold text-slate-800 truncate mt-1">{{ auth()->user()->name }}</p>
                                </div>
                                
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:text-[#4E7D5B] hover:bg-slate-50 uppercase tracking-wider transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Dashboard
                                </a>
                                
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:text-[#4E7D5B] hover:bg-slate-50 uppercase tracking-wider transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile Settings
                                </a>
                                
                                @if(auth()->user()->hasRole('organizer') || auth()->user()->hasRole('admin'))
                                    <a href="{{ route('organizer.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:text-[#4E7D5B] hover:bg-slate-50 uppercase tracking-wider transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Organizer Console
                                    </a>
                                @endif

                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-slate-600 hover:text-[#4E7D5B] hover:bg-slate-50 uppercase tracking-wider transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Admin Panel
                                    </a>
                                @endif
                                
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-50 mt-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-[11px] font-bold text-red-500 hover:bg-red-50/50 uppercase tracking-wider transition-colors text-left font-sans">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-28">
        {{ $slot }}
    </main>

    <!-- Availability Modal -->
    <div x-show="showAvailability" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-cloak>
        <div class="bg-white rounded-[2rem] w-full max-w-md p-10 shadow-2xl relative overflow-hidden" @click.away="showAvailability = false">
            <button @click="showAvailability = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
            <h3 class="text-3xl font-serif mb-6 text-[#4E7D5B]">Event Availability</h3>
            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Select Date Range</label>
                    <input type="date" class="w-full bg-slate-50 border-none rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-[#4E7D5B]/20">
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Number of Guests</label>
                    <select class="w-full bg-slate-50 border-none rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-[#4E7D5B]/20">
                        <option>10 - 50 Guests</option>
                        <option>50 - 200 Guests</option>
                        <option>200+ Guests</option>
                    </select>
                </div>
                <button class="w-full bg-[#4E7D5B] text-white py-5 rounded-full text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-[#4E7D5B]/20">
                    Check Availability
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#FDFBF7] py-24 border-t border-slate-100">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12">
                <div class="max-w-sm">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 bg-[#4E7D5B] rounded-lg flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="text-xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed font-medium">
                        Elevating event hosting with advanced AI automated registrations and dynamic queues.
                    </p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-16">
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Platform</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-xs text-slate-400 hover:text-[#4E7D5B] transition-colors">Features</a></li>
                            <li><a href="#" class="text-xs text-slate-400 hover:text-[#4E7D5B] transition-colors">Analytics</a></li>
                            <li><a href="#" class="text-xs text-slate-400 hover:text-[#4E7D5B] transition-colors">Security</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Company</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-xs text-slate-400 hover:text-[#4E7D5B] transition-colors">About</a></li>
                            <li><a href="#" class="text-xs text-slate-400 hover:text-[#4E7D5B] transition-colors">Blog</a></li>
                            <li><a href="#" class="text-xs text-slate-400 hover:text-[#4E7D5B] transition-colors">Careers</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Connect</h4>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4E7D5B] transition-colors">
                                <i data-lucide="instagram" class="w-4 h-4"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4E7D5B] transition-colors">
                                <i data-lucide="twitter" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-24 pt-12 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em]">
                    &copy; 2026 SmartEvent. Modern automated ticketing and queuing ecosystems.
                </p>
                <div class="flex gap-8">
                    <a href="#" class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] hover:text-[#4E7D5B]">Privacy</a>
                    <a href="#" class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] hover:text-[#4E7D5B]">Terms</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
