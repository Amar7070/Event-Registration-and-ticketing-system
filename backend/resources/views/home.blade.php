<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SmartEvent | Premium Event Registration & AI-Powered Ticketing Ecosystem</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CSS Custom Style Polish -->
    <style>
        .font-serif {
            font-family: 'Fraunces', Georgia, serif;
        }

        .font-sans {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        .bg-cream {
            background-color: #FDFBF7;
        }

        .text-sage {
            color: #4E7D5B;
        }

        .bg-sage {
            background-color: #4E7D5B;
        }

        .border-sage {
            border-color: #4E7D5B;
        }

        /* Smooth Custom Transitions */
        .premium-transition {
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Subtle floating animations */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(0.5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float-reverse {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(10px) rotate(-0.5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .animate-float-reverse {
            animation: float-reverse 7s ease-in-out infinite;
        }

        @keyframes spin-slow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .animate-spin-slow {
            animation: spin-slow 40s linear infinite;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #FDFBF7;
        }
        ::-webkit-scrollbar-thumb {
            background: #E5E2D9;
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4E7D5B;
        }
    </style>
</head>

<body class="antialiased bg-cream dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans selection:bg-[#4E7D5B] selection:text-white overflow-x-hidden"
    x-data="{ scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 50">

    <!-- [Step 1: Universal Navigation Header] -->
    <nav class="fixed top-0 w-full z-[100] premium-transition"
        :class="scrolled ? 'bg-[#FDFBF7]/90 dark:bg-slate-950/90 backdrop-blur-xl border-b border-slate-100 dark:border-slate-800 py-4 shadow-sm' : 'py-8'">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <div class="flex items-center justify-between">
                
                <!-- Left Side: Branding -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-[#4E7D5B] rounded-xl flex items-center justify-center text-white premium-transition group-hover:scale-105 shadow-md shadow-[#4E7D5B]/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</span>
                </a>

                <!-- Center Links -->
                <div class="hidden md:flex items-center gap-10">
                    <a href="{{ route('events.index') }}" class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] hover:text-[#4E7D5B] premium-transition">Browse Events</a>
                    <a href="#features" class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] hover:text-[#4E7D5B] premium-transition">Features</a>
                    <a href="#metrics" class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] hover:text-[#4E7D5B] premium-transition">Impact</a>
                    <a href="{{ route('about') }}" class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] hover:text-[#4E7D5B] premium-transition">About Us</a>
                </div>

                <!-- Right Side: Actions -->
                <div class="flex items-center gap-4">
                    <x-theme-toggle />
                    @guest
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex px-6 py-3 border border-slate-200 text-[#4E7D5B] bg-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-50 premium-transition">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-[#4E7D5B] text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/20 hover:scale-[1.03] active:scale-95 premium-transition">
                            Get Started
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex px-6 py-3 border border-slate-200 text-[#4E7D5B] bg-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-50 premium-transition">
                            Dashboard
                        </a>
                        
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
    </nav>

    <!-- [Step 2: Hero Layout] -->
    <section class="relative min-h-screen pt-36 md:pt-48 pb-20 flex items-center overflow-hidden">
        <!-- Ambient elements -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#4E7D5B]/5 rounded-full blur-[120px] -translate-y-1/3 translate-x-1/3 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#4E7D5B]/3 rounded-full blur-[100px] translate-y-1/3 -translate-x-1/3 pointer-events-none"></div>

        <div class="max-w-[1440px] mx-auto px-6 md:px-12 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                
                <!-- Left Panel (The Live UI Container - Page inside a Page) -->
                <div class="relative w-full order-2 lg:order-1 animate-float">
                    <div class="relative w-full max-w-lg mx-auto bg-[#4E7D5B] rounded-[3rem] p-6 shadow-2xl shadow-[#4E7D5B]/15 overflow-hidden flex flex-col min-h-[580px]" x-data="{ ticketStep: 1, selectedTier: 'regular', guestName: '', guestEmail: '', bookingSuccess: false }">
                        <!-- Serene Monochromatic image inside upper 60% of green card -->
                        <div class="relative h-[280px] w-full rounded-[2.2rem] overflow-hidden group shadow-lg">
                            <img src="{{ asset('tech_summit_lobby.png') }}"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-[4000ms] group-hover:scale-105" 
                                alt="Global Tech Summit Lobby">
                            <div class="absolute inset-0 bg-slate-900/20 mix-blend-multiply"></div>
                            <!-- Live status indicator overlay -->
                            <div class="absolute top-6 left-6 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/90 backdrop-blur-md shadow-md border border-white/20">
                                <span class="flex h-2 w-2 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4E7D5B] opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4E7D5B]"></span>
                                </span>
                                <span class="text-[9px] font-black uppercase tracking-widest text-[#4E7D5B]">LIVE SUMMIT SUMMONS</span>
                            </div>
                        </div>

                        <!-- Integrated live secure registration UI directly beneath -->
                        <div class="flex-1 flex flex-col justify-between pt-6 px-4">
                            <!-- Live interactive multi-step progress line -->
                            <div class="w-full flex items-center justify-between pb-6 border-b border-white/10">
                                <button type="button" @click="if(!bookingSuccess) ticketStep = 1" class="flex items-center gap-2 outline-none">
                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-[9px] font-black transition-colors"
                                        :class="ticketStep >= 1 ? 'bg-white text-[#4E7D5B]' : 'border border-white/40 text-white'">1</div>
                                    <span class="text-[9px] font-black text-white uppercase tracking-widest">Tickets</span>
                                </button>
                                <div class="flex-1 h-px bg-white/10 mx-3"></div>
                                <button type="button" @click="if(!bookingSuccess && guestName !== '') ticketStep = 2" class="flex items-center gap-2 outline-none" :class="guestName === '' ? 'cursor-not-allowed opacity-40' : ''">
                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-[9px] font-black transition-colors"
                                        :class="ticketStep >= 2 ? 'bg-white text-[#4E7D5B]' : 'border border-white/40 text-white'">2</div>
                                    <span class="text-[9px] font-black text-white uppercase tracking-widest">Details</span>
                                </button>
                                <div class="flex-1 h-px bg-white/10 mx-3"></div>
                                <div class="flex items-center gap-2" :class="ticketStep < 3 ? 'opacity-40' : ''">
                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-[9px] font-black transition-colors"
                                        :class="ticketStep >= 3 ? 'bg-white text-[#4E7D5B]' : 'border border-white/40 text-white'">3</div>
                                    <span class="text-[9px] font-black text-white uppercase tracking-widest">Pass</span>
                                </div>
                            </div>

                            <!-- Phase Booking Content -->
                            <div class="pt-6 flex-1 flex flex-col justify-center">
                                <!-- Step 1: Select Ticket Tier -->
                                <div x-show="ticketStep === 1" class="space-y-4">
                                    <h3 class="text-2xl font-serif text-white mb-2 leading-tight tracking-tight">Select Experience Tier</h3>
                                    <p class="text-[11px] text-white/60 mb-4">Book your seat at the Global Tech Summit 2026.</p>
                                    
                                    <div class="space-y-3">
                                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-white/10 bg-white/5 cursor-pointer hover:bg-white/10 transition-colors" :class="selectedTier === 'regular' ? 'border-white bg-white/15' : ''">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="booking_tier" value="regular" x-model="selectedTier" class="hidden">
                                                <span class="w-3.5 h-3.5 rounded-full border border-white flex items-center justify-center">
                                                    <span class="w-2 h-2 rounded-full bg-white" x-show="selectedTier === 'regular'"></span>
                                                </span>
                                                <div class="text-left">
                                                    <span class="block text-xs font-bold text-white uppercase tracking-wider">Regular Pass</span>
                                                    <span class="text-[9px] text-white/50">Full access to tracks & lobbies</span>
                                                </div>
                                            </div>
                                            <span class="text-xs font-black text-white">₹1,499</span>
                                        </label>

                                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-white/10 bg-white/5 cursor-pointer hover:bg-white/10 transition-colors" :class="selectedTier === 'vip' ? 'border-white bg-white/15' : ''">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="booking_tier" value="vip" x-model="selectedTier" class="hidden">
                                                <span class="w-3.5 h-3.5 rounded-full border border-white flex items-center justify-center">
                                                    <span class="w-2 h-2 rounded-full bg-white" x-show="selectedTier === 'vip'"></span>
                                                </span>
                                                <div class="text-left">
                                                    <span class="block text-xs font-bold text-white uppercase tracking-wider">VIP Key Access</span>
                                                    <span class="text-[9px] text-[#A7F3D0] font-black uppercase tracking-widest">24 Tickets Left</span>
                                                </div>
                                            </div>
                                            <span class="text-xs font-black text-white">₹4,999</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Step 2: Add Guest Details -->
                                <div x-show="ticketStep === 2" class="space-y-4">
                                    <h3 class="text-2xl font-serif text-white mb-2 leading-tight tracking-tight">Attendee Information</h3>
                                    <p class="text-[11px] text-white/60 mb-4">Provide details for your secure QR access key.</p>
                                    
                                    <div class="space-y-3">
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/30"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg></span>
                                            <input type="text" placeholder="Attendee Name" x-model="guestName"
                                                class="w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-full text-xs text-white placeholder:text-white/30 focus:border-white focus:bg-white/10 outline-none transition-colors">
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/30"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg></span>
                                            <input type="email" placeholder="Your Email Address" x-model="guestEmail"
                                                class="w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-full text-xs text-white placeholder:text-white/30 focus:border-white focus:bg-white/10 outline-none transition-colors">
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Complete & Show QR Pass -->
                                <div x-show="ticketStep === 3" class="space-y-4 text-center">
                                    <div class="w-20 h-20 bg-white rounded-2xl mx-auto flex items-center justify-center p-3 border-2 border-emerald-400 shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-slate-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="7" height="7" rx="1" />
                                            <rect x="14" y="3" width="7" height="7" rx="1" />
                                            <rect x="3" y="14" width="7" height="7" rx="1" />
                                            <rect x="14" y="14" width="3" height="3" rx="0.5" />
                                            <rect x="17" y="17" width="4" height="4" rx="0.5" />
                                            <line x1="21" y1="14" x2="21.01" y2="14" />
                                            <line x1="14" y1="21" x2="14.01" y2="21" />
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-serif text-white leading-tight tracking-tight">Booking Secured!</h3>
                                    <div class="text-[10px] uppercase font-black tracking-widest text-[#A7F3D0]">REF: SE-2026-T402</div>
                                    <p class="text-[11px] text-white/70 max-w-xs mx-auto leading-relaxed">
                                        Welcome, <span class="font-bold text-white" x-text="guestName"></span>! Your QR code has been generated and sent to <span class="font-bold text-white" x-text="guestEmail"></span>.
                                    </p>
                                </div>
                            </div>

                            <!-- Step actions button -->
                            <div class="w-full pb-2 pt-6">
                                <button type="button" 
                                    @click="
                                        if(ticketStep === 1) { ticketStep = 2 }
                                        else if(ticketStep === 2) { 
                                            if(guestName !== '' && guestEmail !== '') { 
                                                ticketStep = 3; 
                                                bookingSuccess = true;
                                            } 
                                        }
                                        else if(ticketStep === 3) {
                                            ticketStep = 1;
                                            bookingSuccess = false;
                                            guestName = '';
                                            guestEmail = '';
                                        }
                                    "
                                    class="w-full bg-white text-[#4E7D5B] py-4 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-[#4E7D5B]/20 flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-95 transition-all outline-none"
                                    :disabled="ticketStep === 2 && (guestName === '' || guestEmail === '')"
                                    :class="ticketStep === 2 && (guestName === '' || guestEmail === '') ? 'opacity-50 cursor-not-allowed' : ''">
                                    <span x-text="ticketStep === 1 ? 'Continue to Details' : (ticketStep === 2 ? 'Generate Secure Pass' : 'Book Another Seat')"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Ambient Floating Badges -->
                    <div class="absolute -right-6 bottom-12 bg-white px-6 py-4 rounded-[2rem] shadow-xl shadow-slate-900/5 border border-slate-100 flex items-center gap-3 animate-float-reverse">
                        <div class="w-9 h-9 rounded-full bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        </div>
                        <div>
                            <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest">SECURED ACCESS</span>
                            <span class="block text-[11px] font-bold text-slate-800">100% Cryptographic QR</span>
                        </div>
                    </div>
                </div>

                <!-- Right Panel (Main Hero Typography & Context) -->
                <div class="flex flex-col justify-center order-1 lg:order-2">
                    <div class="inline-flex items-center gap-2.5 px-4.5 py-2 rounded-full bg-[#4E7D5B]/5 border border-[#4E7D5B]/10 w-fit mb-8">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4E7D5B]"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-[#4E7D5B]">AI-Powered Ticketing & Registrations</span>
                    </div>

                    <h1 class="text-6xl md:text-7xl lg:text-8xl font-serif text-slate-900 leading-[1.08] tracking-tighter mb-8">
                        Host <br>
                        <span class="text-[#4E7D5B] italic relative inline-block">Beautiful, Seamless<span class="absolute bottom-2 left-0 w-full h-[3px] bg-[#4E7D5B]/10"></span></span> <br>
                        Experiences.
                    </h1>

                    <p class="text-lg md:text-xl text-slate-500 font-medium leading-relaxed max-w-xl mb-12">
                        SmartEvent coordinates tickets, waitlists, secure QR access check-ins, and payments globally. Empowering premium organizers with automated registration workflows.
                    </p>

                    <!-- Direct Call-to-Action Utility -->
                    <form action="{{ route('events.index') }}" method="GET" class="relative max-w-md w-full group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-[#4E7D5B]/10 via-[#4E7D5B]/30 to-[#4E7D5B]/10 rounded-full blur opacity-25 group-focus-within:opacity-45 premium-transition"></div>
                        <div class="relative flex items-center bg-white rounded-full border border-slate-100 shadow-xl shadow-slate-900/5 p-2 overflow-hidden">
                            <div class="pl-5 text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input type="text" name="search" placeholder="Search experiences, cities, or categories..." required
                                class="flex-1 bg-transparent border-none focus:ring-0 text-sm py-4 px-4 placeholder:text-slate-300 text-slate-800 font-medium outline-none">
                            <button type="submit" class="bg-[#4E7D5B] text-white px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-md hover:scale-[1.02] premium-transition">
                                Scan Ecosystem
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <!-- [Step 3: Tabbed Feature Sub-System] -->
    <section id="features" class="py-32 md:py-48 bg-white border-y border-slate-100" x-data="{ activeTab: 'waitlists' }">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            
            <div class="text-center max-w-2xl mx-auto mb-24">
                <span class="text-[#4E7D5B] text-[9px] font-black uppercase tracking-[0.4em] mb-4 block">PLATFORM ECOSYSTEM</span>
                <h2 class="text-5xl md:text-6xl font-serif text-slate-900 leading-tight">Automate workflows, <br>curate event experiences.</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-center">
                
                <!-- Left Panel: Tabs List -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Tab Button 1: Waitlists -->
                    <button @click="activeTab = 'waitlists'"
                        class="w-full text-left p-8 rounded-[2rem] border premium-transition flex flex-col gap-3 group"
                        :class="activeTab === 'waitlists' ? 'bg-[#FDFBF7] border-[#4E7D5B]/20 shadow-lg shadow-slate-900/5' : 'bg-white border-slate-100 hover:border-slate-200'">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center premium-transition"
                                :class="activeTab === 'waitlists' ? 'bg-[#4E7D5B] text-white' : 'bg-slate-50 text-slate-400 group-hover:bg-slate-100'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest"
                                :class="activeTab === 'waitlists' ? 'text-slate-800' : 'text-slate-400 group-hover:text-slate-600'">Queue Automation</span>
                        </div>
                        <h4 class="text-xl font-serif text-slate-800">Dynamic Waitlist Engine</h4>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed">Let queues auto-process! Once a reservation expires or capacity is cleared, the system auto-notifies the next attendee instantly.</p>
                    </button>

                    <!-- Tab Button 2: Floorplans -->
                    <button @click="activeTab = 'floorplans'"
                        class="w-full text-left p-8 rounded-[2rem] border premium-transition flex flex-col gap-3 group"
                        :class="activeTab === 'floorplans' ? 'bg-[#FDFBF7] border-[#4E7D5B]/20 shadow-lg shadow-slate-900/5' : 'bg-white border-slate-100 hover:border-slate-200'">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center premium-transition"
                                :class="activeTab === 'floorplans' ? 'bg-[#4E7D5B] text-white' : 'bg-slate-50 text-slate-400 group-hover:bg-slate-100'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest"
                                :class="activeTab === 'floorplans' ? 'text-slate-800' : 'text-slate-400 group-hover:text-slate-600'">Spatial Design</span>
                        </div>
                        <h4 class="text-xl font-serif text-slate-800">Smart Floorplans & Sessions</h4>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed">Map specific session rooms, speaker slots, and sponsor tiers to layout coordinates, giving guests visual density profiles.</p>
                    </button>

                    <!-- Tab Button 3: Analytics -->
                    <button @click="activeTab = 'analytics'"
                        class="w-full text-left p-8 rounded-[2rem] border premium-transition flex flex-col gap-3 group"
                        :class="activeTab === 'analytics' ? 'bg-[#FDFBF7] border-[#4E7D5B]/20 shadow-lg shadow-slate-900/5' : 'bg-white border-slate-100 hover:border-slate-200'">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center premium-transition"
                                :class="activeTab === 'analytics' ? 'bg-[#4E7D5B] text-white' : 'bg-slate-50 text-slate-400 group-hover:bg-slate-100'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest"
                                :class="activeTab === 'analytics' ? 'text-slate-800' : 'text-slate-400 group-hover:text-slate-600'">Revenues</span>
                        </div>
                        <h4 class="text-xl font-serif text-slate-800">Commission & Revenue Ledger</h4>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed">Admin panel calculates platform commissions, tracks Stripe/Razorpay checkouts, and coordinates instant refunds.</p>
                    </button>
                </div>

                <!-- Right Panel: Viewport -->
                <div class="lg:col-span-8 bg-[#FDFBF7] rounded-[3.5rem] border border-slate-100 shadow-2xl p-8 min-h-[500px] flex flex-col justify-between overflow-hidden">
                    
                    <!-- TAB 1: WAITLISTS -->
                    <div x-show="activeTab === 'waitlists'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" class="flex-1 flex flex-col justify-between space-y-8" x-data="{ queue: [ {name: 'Piyush Kumar', status: 'Waitlisted', email: 'piyush@example.com'}, {name: 'Sarah Connor', status: 'Waitlisted', email: 'sarah@skynet.com'} ], released: false }">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-6">
                            <div>
                                <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest block">WAITLIST SIMULATOR</span>
                                <h3 class="text-2xl font-serif text-slate-800">Dynamic Capacity Release</h3>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-4 py-1.5 bg-amber-50 border border-amber-200 rounded-full text-[9px] font-black uppercase tracking-widest text-amber-600" x-show="!released">CAPACITY FULL</span>
                                <span class="px-4 py-1.5 bg-emerald-50 border border-emerald-200 rounded-full text-[9px] font-black uppercase tracking-widest text-emerald-600" x-show="released">SLOT RELEASED</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 flex-1 items-stretch">
                            <!-- Left: Waitlist Queue -->
                            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                                <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400">Queue Index</h4>
                                <div class="space-y-3">
                                    <template x-for="(user, idx) in queue" :key="idx">
                                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-2xl border border-slate-100 transition-all">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-[#4E7D5B] text-white flex items-center justify-center text-[10px] font-black" x-text="user.name.split(' ').map(n => n[0]).join('')"></div>
                                                <div class="text-left">
                                                    <h5 class="text-xs font-bold text-slate-800" x-text="user.name"></h5>
                                                    <span class="text-[8px] font-bold text-slate-400" x-text="user.email"></span>
                                                </div>
                                            </div>
                                            <span class="px-2.5 py-1 text-[8px] font-black uppercase tracking-widest rounded-full transition-colors"
                                                :class="user.status === 'Booked' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                                                x-text="user.status"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Right: Admin Action Controller -->
                            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between text-left">
                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Ecosystem Automation</h4>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-6">
                                        Simulate ticket cancellations. Click below to trigger waitlist notifications and automatically re-book the first attendee in queue.
                                    </p>
                                </div>
                                <button type="button" 
                                    @click="
                                        if(!released) { 
                                            queue[0].status = 'Booked'; 
                                            released = true; 
                                        } else { 
                                            queue[0].status = 'Waitlisted'; 
                                            released = false; 
                                        }
                                    "
                                    class="w-full bg-slate-900 text-white py-4 rounded-full text-[9px] font-black uppercase tracking-[0.2em] hover:bg-slate-800 transition-colors shadow-lg">
                                    <span x-text="!released ? 'Release Slot (Auto-Book)' : 'Reset Simulation'"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: FLOORPLANS -->
                    <div x-show="activeTab === 'floorplans'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" class="flex-1 flex flex-col justify-between space-y-8">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-6">
                            <div>
                                <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest block">SPATIAL TRACKER</span>
                                <h3 class="text-2xl font-serif text-slate-800">Dynamic Venue Layout & Sessions</h3>
                            </div>
                            <span class="px-4 py-1.5 bg-[#4E7D5B]/10 rounded-full text-[9px] font-black uppercase tracking-widest text-[#4E7D5B]">PHYSICAL NODE</span>
                        </div>

                        <!-- Graphical Floorplan Blueprint -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex-1 flex flex-col justify-between min-h-[250px] relative overflow-hidden">
                            <div class="absolute inset-0 bg-[#4E7D5B]/5 pointer-events-none" style="background-image: radial-gradient(circle, #4E7D5B 0.5px, transparent 0.5px); background-size: 16px 16px;"></div>
                            
                            <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 h-full items-stretch">
                                <div class="border border-dashed border-[#4E7D5B]/30 rounded-2xl p-4 bg-white/70 flex flex-col justify-between text-left">
                                    <span class="text-[8px] font-black text-[#4E7D5B] uppercase tracking-widest">Main Track</span>
                                    <h4 class="text-sm font-bold text-slate-800 leading-tight">Keynotes & Tech Panel</h4>
                                    <span class="text-[9px] font-bold text-slate-400">Capacity: 1,200 seats</span>
                                </div>
                                <div class="border-2 border-[#4E7D5B] rounded-2xl p-4 bg-white shadow-lg flex flex-col justify-between text-left">
                                    <span class="text-[8px] font-black text-white bg-[#4E7D5B] px-2 py-0.5 rounded w-fit uppercase tracking-widest">AI Track</span>
                                    <h4 class="text-sm font-bold text-slate-800 leading-tight">LLM Fine-Tuning Labs</h4>
                                    <span class="text-[9px] font-bold text-[#4E7D5B]">Active Session Now</span>
                                </div>
                                <div class="border border-dashed border-[#4E7D5B]/30 rounded-2xl p-4 bg-white/70 flex flex-col justify-between text-left">
                                    <span class="text-[8px] font-black text-[#4E7D5B] uppercase tracking-widest">Lobby Zone</span>
                                    <h4 class="text-sm font-bold text-slate-800 leading-tight">Sponsor Lounges & Displays</h4>
                                    <span class="text-[9px] font-bold text-slate-400">Platinum Booths</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: ANALYTICS -->
                    <div x-show="activeTab === 'analytics'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" class="flex-1 flex flex-col justify-between space-y-8">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-6">
                            <div>
                                <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest block">ADMIN LEDGER</span>
                                <h3 class="text-2xl font-serif text-slate-800">Secure Commision & Revenue Tracking</h3>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ECOSYSTEM TOTALS</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch flex-1">
                            <!-- Left: Revenue statistics -->
                            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6 text-left">
                                <div>
                                    <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest block">GROSS TICKET SALES</span>
                                    <span class="text-3xl font-serif text-[#4E7D5B] font-bold">₹1,248,500</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                                    <div>
                                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest block">COMMISSIONS (5%)</span>
                                        <span class="text-sm font-bold text-slate-800">₹62,425</span>
                                    </div>
                                    <div>
                                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest block">SECURED REFUNDS</span>
                                        <span class="text-sm font-bold text-slate-800">100% Automated</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Visual ledger statuses -->
                            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between text-left space-y-4">
                                <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400">Payment Gateways</h4>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                        <span class="text-xs font-bold text-slate-700">Stripe SDK Connector</span>
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                        <span class="text-xs font-bold text-slate-700">Razorpay API Tunnel</span>
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- [Step 4: Ticket Pass & Guest Management Block] -->
    <section class="py-32 md:py-48 bg-cream">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                
                <!-- Left Column: Content -->
                <div class="text-left">
                    <span class="text-[#4E7D5B] text-[9px] font-black uppercase tracking-[0.4em] mb-4 block">ZERO-QUEUE ACCESS</span>
                    <h2 class="text-5xl md:text-6xl font-serif text-slate-900 leading-tight mb-8">Scan instantly. <br>Secure attendance.</h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed mb-12 max-w-lg">
                        SmartEvent generates unique high-fidelity PDF passes containing secure, dynamic cryptographic QR codes. Hosts scan passes with zero-queue delays and live attendance updates.
                    </p>

                    <!-- Checklist -->
                    <ul class="space-y-6 mb-12">
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-[#4E7D5B]/10 text-[#4E7D5B] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-1">Secure QR Cryptography</h4>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed">Dynamic tokens mapped to attendees prevent fraud and guarantee secure admission.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-[#4E7D5B]/10 text-[#4E7D5B] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-1">Instant QR Scanners</h4>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed">Organizers scan dynamic passes directly via camera nodes in under 500ms.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-6 h-6 rounded-full bg-[#4E7D5B]/10 text-[#4E7D5B] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-1">Downloadable PDF Passes</h4>
                                <p class="text-xs text-slate-400 font-medium leading-relaxed">Offline-ready high-fidelity PDFs generated via dompdf, ready for mobile wallets.</p>
                            </div>
                        </li>
                    </ul>

                    <a href="{{ route('events.index') }}" class="inline-flex px-10 py-4.5 bg-[#4E7D5B] text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-[#4E7D5B]/20 hover:scale-105 premium-transition">
                        Explore Event Catalog
                    </a>
                </div>

                <!-- Right Column: Visual Pass Card Graphic Asset -->
                <div class="relative w-full max-w-md mx-auto animate-float-reverse">
                    <!-- Premium digital ticket container -->
                    <div class="relative bg-white rounded-[3rem] p-8 border border-slate-100 shadow-2xl overflow-hidden flex flex-col justify-between min-h-[460px]">
                        
                        <div class="flex items-center justify-between border-b border-slate-100 pb-6 mb-6">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-[#4E7D5B] rounded-lg flex items-center justify-center text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                </div>
                                <span class="font-serif text-lg tracking-tight text-slate-800">SmartEvent</span>
                            </div>
                            <span class="px-4 py-1.5 bg-[#4E7D5B]/10 rounded-full text-[9px] font-black uppercase tracking-widest text-[#4E7D5B]">VIP ACCESS PASS</span>
                        </div>

                        <!-- Ticket details -->
                        <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-8 text-left">
                            <div>
                                <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">ATTENDEE</span>
                                <span class="block text-sm font-bold text-slate-800 text-left">Jane Doe</span>
                            </div>
                            <div>
                                <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">BOOKING REF</span>
                                <span class="block text-sm font-bold text-slate-800 text-left">SE-2026-X812</span>
                            </div>
                            <div>
                                <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">EXPERIENCE</span>
                                <span class="block text-sm font-bold text-slate-800 text-left">Global Tech Summit 2026</span>
                            </div>
                            <div>
                                <span class="block text-[8px] font-black text-slate-300 uppercase tracking-widest mb-1">SCHEDULE DATE</span>
                                <span class="block text-sm font-bold text-slate-800 text-left">Oct 12, 2026 at 10:00 AM</span>
                            </div>
                        </div>

                        <!-- Vector QR Code matrix -->
                        <div class="flex-1 flex flex-col justify-center items-center py-6 border-t border-dashed border-slate-100">
                            <div class="relative w-36 h-36 bg-cream border border-[#4E7D5B]/20 rounded-2xl p-4 flex items-center justify-center shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-slate-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7" rx="1" />
                                    <rect x="14" y="3" width="7" height="7" rx="1" />
                                    <rect x="3" y="14" width="7" height="7" rx="1" />
                                    <rect x="14" y="14" width="3" height="3" rx="0.5" />
                                    <rect x="17" y="17" width="4" height="4" rx="0.5" />
                                    <line x1="21" y1="14" x2="21.01" y2="14" />
                                    <line x1="14" y1="21" x2="14.01" y2="21" />
                                    <line x1="14" y1="17" x2="14" y2="17.01" />
                                    <line x1="17" y1="14" x2="17" y2="14.01" />
                                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>
                                    <circle cx="6.5" cy="17.5" r="1" fill="currentColor"/>
                                    <circle cx="6.5" cy="6.5" r="1" fill="currentColor"/>
                                </svg>
                                <div class="absolute inset-0 border-2 border-[#4E7D5B] rounded-2xl pointer-events-none"></div>
                            </div>
                            <span class="block text-[8px] font-black text-slate-300 uppercase tracking-[0.3em] mt-4">SECURE ENTRY CRYPTOGRAPHIC QR</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- [Step 5: Narrative & Social Proof Section] -->
    <section class="relative min-h-[500px] py-32 flex items-center overflow-hidden">
        <!-- Full-bleed background media container -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('networking_gathering.png') }}" class="w-full h-full object-cover scale-105 animate-[pulse_12s_infinite]" alt="Conferences & Networking">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>
        </div>

        <div class="relative z-10 max-w-[1440px] mx-auto px-6 md:px-12 w-full text-center">
            <div class="max-w-4xl mx-auto flex flex-col items-center">
                
                <span class="text-white/40 text-[9px] font-black uppercase tracking-[0.5em] mb-6 animate-pulse">ELITE NETWORKING SPACES</span>
                
                <h2 class="text-5xl md:text-7xl font-serif text-white leading-tight mb-12 tracking-tight max-w-3xl">
                    Connect Intentionally. <br>Network <span class="italic text-[#4E7D5B] bg-white px-5 py-1 rounded-full relative inline-block">Seamlessly</span>.
                </h2>

                <!-- Minimalist Request Invite panel -->
                <div class="w-full max-w-md bg-[#FDFBF7] p-8 rounded-[2.5rem] border border-white/20 shadow-2xl relative overflow-hidden text-left animate-float">
                    <div class="absolute inset-0 bg-[#4E7D5B]/3 pointer-events-none"></div>
                    <div class="relative z-10 space-y-4">
                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest block">ORGANIZER ACCESS KEY</span>
                        <h4 class="text-xl font-serif text-slate-800 leading-tight">Request Platform Credentials</h4>
                        <p class="text-xs text-slate-400 leading-relaxed font-medium">Unlock full platform privileges: publish physical/online events, configure ticketing tiers, and monitor Stripe/Razorpay ledgers.</p>
                        
                        <form action="{{ route('register') }}" method="GET" class="relative group mt-6">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-[#4E7D5B]/10 to-[#4E7D5B]/10 rounded-full blur-sm opacity-50 group-focus-within:opacity-100 premium-transition"></div>
                            <div class="relative flex items-center bg-white border border-slate-100 rounded-full p-1 overflow-hidden">
                                <input type="email" name="email" placeholder="Your work email..." required
                                    class="flex-1 bg-transparent border-none focus:ring-0 text-xs py-3 px-4 placeholder:text-slate-300 text-slate-800 outline-none">
                                <button type="submit" class="bg-[#4E7D5B] text-white w-10 h-10 rounded-full flex items-center justify-center shrink-0 hover:scale-105 active:scale-95 premium-transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- [Step 6: Metrics Grid System] -->
    <section class="py-32 md:py-48 bg-white overflow-hidden" id="metrics">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-center">
                
                <!-- Left Side: Metric list rows -->
                <div class="lg:col-span-7 flex flex-col justify-center text-left">
                    <span class="text-[#4E7D5B] text-[9px] font-black uppercase tracking-[0.4em] mb-4 block">ECOSYSTEM STATISTICS</span>
                    <h2 class="text-5xl md:text-6xl font-serif text-slate-900 leading-tight mb-16">Ecosystem numbers <br>in real-time.</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="bg-[#FDFBF7] p-8 rounded-[2rem] border border-slate-100 flex flex-col gap-3 group hover:border-[#4E7D5B]/20 premium-transition">
                            <span class="text-5xl font-serif text-[#4E7D5B] tracking-tight group-hover:scale-105 premium-transition block">500k+</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block leading-snug">Tickets Secured</span>
                        </div>

                        <div class="bg-[#FDFBF7] p-8 rounded-[2rem] border border-slate-100 flex flex-col gap-3 group hover:border-[#4E7D5B]/20 premium-transition">
                            <span class="text-5xl font-serif text-[#4E7D5B] tracking-tight group-hover:scale-105 premium-transition block">120+</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block leading-snug">Active Cities</span>
                        </div>

                        <div class="bg-[#FDFBF7] p-8 rounded-[2rem] border border-slate-100 flex flex-col gap-3 group hover:border-[#4E7D5B]/20 premium-transition">
                            <span class="text-5xl font-serif text-[#4E7D5B] tracking-tight group-hover:scale-105 premium-transition block">99.9%</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block leading-snug">Check-in Reliability</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Slow Spinning Globe Map Graphic -->
                <div class="lg:col-span-5 flex justify-center items-center">
                    <div class="relative w-80 h-80 md:w-96 md:h-96 border-2 border-[#4E7D5B]/10 rounded-full flex items-center justify-center p-8 md:p-12 animate-spin-slow bg-[#FDFBF7]/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-[#4E7D5B]/10 stroke-[0.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M2 12h20" />
                            <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" />
                        </svg>
                        
                        <!-- Floating map nodes -->
                        <div class="absolute w-3.5 h-3.5 bg-[#4E7D5B] border-4 border-white rounded-full shadow-lg top-16 left-1/3"></div>
                        <div class="absolute w-2.5 h-2.5 bg-[#4E7D5B]/60 border-2 border-white rounded-full shadow-md bottom-24 right-1/4"></div>
                        <div class="absolute w-3 h-3 bg-[#4E7D5B]/80 border-2 border-white rounded-full shadow-md top-1/2 right-16"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- [Step 6.5: Ecosystem Showcase Advertising Slider] -->
    <section class="py-24 bg-white border-t border-slate-100 overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div>
                    <span class="text-[#4E7D5B] text-[9px] font-black uppercase tracking-[0.4em] mb-4 block">Ecosystem Spotlights</span>
                    <h2 class="text-4xl md:text-5xl font-serif text-slate-900 tracking-tight leading-tight">Featured Gatherings.</h2>
                    <p class="text-sm text-slate-400 font-serif italic mt-2">"Intentional spaces actively showcasing in the SmartEvent network."</p>
                </div>
            </div>

            @if($promotions->count() > 0)
                <!-- Active Slideshow Carousel -->
                <div class="relative bg-[#FDFBF7] rounded-[3.5rem] p-8 md:p-16 border border-slate-100 shadow-2xl shadow-slate-100/50 overflow-hidden"
                     x-data="{ activeSlide: 0, totalSlides: {{ $promotions->count() }}, timer: null, resetTimer() { clearInterval(this.timer); this.timer = setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.totalSlides }, 6000) } }"
                     x-init="resetTimer()">
                    
                    <div class="absolute inset-0 bg-[#FDFBF7]/5 pointer-events-none" style="background-image: radial-gradient(circle, #fff 0.8px, transparent 0.8px); background-size: 32px 32px;"></div>

                    <div class="relative z-10 min-h-[350px] flex flex-col justify-between">
                        @foreach($promotions as $index => $promo)
                            <div x-show="activeSlide === {{ $index }}" 
                                 x-transition:enter="transition ease-out duration-700"
                                 x-transition:enter-start="opacity-0 translate-x-8"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                                
                                <!-- Slide Content -->
                                <div class="lg:col-span-6 space-y-6 text-left">
                                    <div class="flex items-center gap-3">
                                        <span class="px-4 py-1.5 bg-[#4E7D5B]/10 text-[#4E7D5B] rounded-full text-[9px] font-black uppercase tracking-widest border border-[#4E7D5B]/20">
                                            {{ $promo->event->category->name }}
                                        </span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#4E7D5B] animate-pulse"></span> FEATURED EXPERIENCE
                                        </span>
                                    </div>

                                    <h3 class="text-3xl md:text-5xl font-serif text-slate-900 leading-tight tracking-tight">{{ $promo->event->title }}</h3>
                                    
                                    <p class="text-slate-500 text-sm leading-relaxed font-serif italic">
                                        "{{ Str::limit($promo->event->short_description, 180) }}"
                                    </p>

                                    <div class="grid grid-cols-2 gap-6 pt-4 border-t border-slate-200/40">
                                        <div>
                                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block leading-none mb-1">Temporal Node</span>
                                            <span class="text-xs font-bold text-slate-700">{{ $promo->event->start_date->format('M d, Y @ h:i A') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block leading-none mb-1">Sanctuary Realm</span>
                                            <span class="text-xs font-bold text-slate-700 truncate block">
                                                {{ $promo->event->venue ? $promo->event->venue->name : 'Digital Realm' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="pt-6">
                                        <a href="{{ route('events.show', $promo->event->slug) }}" class="inline-flex px-10 py-4.5 bg-[#4E7D5B] hover:bg-[#3C6347] active:scale-95 text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-[#4E7D5B]/20 transition-all gap-2 items-center">
                                            SECURE PASS SEATS <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Slide Banner Image -->
                                <div class="lg:col-span-6">
                                    <div class="relative w-full aspect-[16/10] rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-2xl group">
                                        @if($promo->event->hasMedia('banners'))
                                            <img src="{{ $promo->event->getFirstMediaUrl('banners') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[2s]">
                                        @else
                                            <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                                                <i data-lucide="image" class="text-slate-800 w-12 h-12"></i>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                        <!-- Dots Indicator Row -->
                        <div class="flex items-center justify-center gap-3 mt-12 pt-6 border-t border-slate-200/20">
                            @foreach($promotions as $index => $promo)
                                <button @click="activeSlide = {{ $index }}; resetTimer()" 
                                        :class="activeSlide === {{ $index }} ? 'bg-[#4E7D5B] w-8' : 'bg-slate-200 w-2.5'"
                                        class="h-2.5 rounded-full transition-all duration-500 ease-out outline-none"></button>
                            @endforeach
                        </div>

                    </div>
                </div>
            @else
                <!-- Dynamic High-Fidelity Sandbox Promotion Callout for Organizers -->
                <div class="relative bg-slate-900 text-white rounded-[3.5rem] p-12 md:p-24 overflow-hidden shadow-2xl group animate-float-reverse">
                    <div class="absolute inset-0 bg-[#FDFBF7]/5 pointer-events-none" style="background-image: radial-gradient(circle, #fff 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
                        <div class="lg:col-span-7 space-y-6 text-left">
                            <span class="text-[#98C2A7] text-[9px] font-black uppercase tracking-[0.4em] block">MONETIZABLE ENGAGEMENT VECTORS</span>
                            <h3 class="text-3xl md:text-5xl font-serif tracking-tight leading-tight text-[#E8F0EA]">Showcase Your <br>Experience Here.</h3>
                            <p class="text-sm text-white/60 leading-relaxed font-serif max-w-xl">
                                Promote your intentional gathering directly on the SmartEvent Homepage. Reach up to <span class="text-white font-bold">45,000 active nodes per week</span> and secure ticketing waitlists instantly with custom administrative advertising.
                            </p>
                            <div class="pt-4 flex flex-wrap gap-6 items-center">
                                <a href="{{ route('organizer.events.index') }}" class="inline-flex px-10 py-4 bg-[#4E7D5B] hover:bg-[#3C6347] active:scale-95 text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-[#4E7D5B]/20 transition-all gap-2 items-center">
                                    EXPLORE ADVERTISING PLANS <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Floating Showcase Mockup Layout preview graphic -->
                        <div class="lg:col-span-5 flex justify-center items-center">
                            <div class="relative w-72 h-72 border border-white/10 rounded-full flex items-center justify-center p-8 bg-white/5 backdrop-blur-md">
                                <div class="w-full h-full border border-white/20 rounded-full flex items-center justify-center animate-spin-slow">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#98C2A7]/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
                                </div>
                                <div class="absolute w-3.5 h-3.5 bg-[#4E7D5B] border-4 border-slate-900 rounded-full top-10 left-12 animate-pulse"></div>
                                <div class="absolute w-2 h-2 bg-[#98C2A7] border-2 border-slate-900 rounded-full bottom-16 right-16"></div>
                                <div class="absolute p-4 bg-white/10 border border-white/20 rounded-2xl text-[9px] font-black uppercase tracking-widest text-[#98C2A7] backdrop-blur-lg shadow-xl shadow-black/20">
                                    45k VIEWS / WK
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- [Step 7: Final Action Portal & Footer Framework] -->
    <section class="py-32 md:py-48 bg-[#FDFBF7]">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            
            <!-- Wide High-Contrast CTA block -->
            <div class="bg-[#4E7D5B] rounded-[4rem] p-12 md:p-24 text-center text-white relative overflow-hidden shadow-2xl shadow-[#4E7D5B]/10 animate-float-reverse">
                <div class="absolute inset-0 bg-gradient-to-br from-black/10 via-transparent to-black/10 pointer-events-none"></div>
                <div class="absolute inset-0 bg-[#FDFBF7]/5 pointer-events-none" style="background-image: radial-gradient(circle, #fff 0.8px, transparent 0.8px); background-size: 32px 32px;"></div>
                
                <div class="relative z-10 max-w-3xl mx-auto flex flex-col items-center">
                    <span class="text-white/40 text-[9px] font-black uppercase tracking-[0.4em] mb-6 block">LAUNCH AN EXPERIENCE</span>
                    <h2 class="text-5xl md:text-7xl lg:text-8xl font-serif mb-8 leading-tight tracking-tight">Create your own <br>Event Legacy.</h2>
                    <p class="text-lg md:text-xl text-white/70 max-w-xl mx-auto mb-12 font-serif italic">
                        Take full platform control as an approved SmartEvent Organizer. Configure ticketing tiers, coordinate dynamic waitlists, and check-in guests cleanly.
                    </p>
                    <a href="{{ route('register') }}" class="inline-flex px-12 py-5 bg-white text-[#4E7D5B] rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-black/10 hover:scale-105 active:scale-95 premium-transition">
                        Get Started as Organizer
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer Framework -->
    <footer class="bg-white border-t border-slate-100 py-24">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-16 mb-20 text-left">
                
                <div class="lg:col-span-2">
                    <a href="/" class="flex items-center gap-3 mb-8 w-fit group">
                        <div class="w-10 h-10 bg-[#4E7D5B] rounded-xl flex items-center justify-center text-white premium-transition shadow-md shadow-[#4E7D5B]/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</span>
                    </a>
                    <p class="text-sm text-slate-400 max-w-sm leading-relaxed mb-10 text-left">
                        A centralized full-stack ecosystem where users discover elite experiences, organizers manage ticketing and QR entries cleanly, and admins secure the network with real-time analytics.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#4E7D5B] hover:text-white hover:border-[#4E7D5B] premium-transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" /></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#4E7D5B] hover:text-white hover:border-[#4E7D5B] premium-transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-[#4E7D5B] hover:text-white hover:border-[#4E7D5B] premium-transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h5 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-800 mb-8">Ecosystem</h5>
                    <ul class="space-y-4 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <li><a href="{{ route('events.index') }}" class="hover:text-[#4E7D5B] premium-transition">Discover Experiences</a></li>
                        <li><a href="#features" class="hover:text-[#4E7D5B] premium-transition">Waitlist Automator</a></li>
                        <li><a href="#features" class="hover:text-[#4E7D5B] premium-transition">Floorplan Blueprint</a></li>
                        <li><a href="#features" class="hover:text-[#4E7D5B] premium-transition">Access Passes</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-800 mb-8">Platform</h5>
                    <ul class="space-y-4 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <li><a href="{{ route('about') }}" class="hover:text-[#4E7D5B] premium-transition">Company Bio</a></li>
                        <li><a href="{{ route('blog') }}" class="hover:text-[#4E7D5B] premium-transition">Ecosystem Blog</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-[#4E7D5B] premium-transition">Contact Node</a></li>
                        <li><a href="{{ route('help') }}" class="hover:text-[#4E7D5B] premium-transition">Help Center</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-[10px] font-black uppercase tracking-[0.3em] text-[#4E7D5B] mb-8">Grounded Security</h5>
                    <ul class="space-y-4 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <li><a href="#" class="hover:text-[#4E7D5B] premium-transition">Privacy Protocols</a></li>
                        <li><a href="#" class="hover:text-[#4E7D5B] premium-transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-[#4E7D5B] premium-transition">Fraud Shields</a></li>
                        <li><a href="#" class="hover:text-[#4E7D5B] premium-transition">Commissions Guide</a></li>
                    </ul>
                </div>

            </div>

            <div class="pt-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] text-left">
                    &copy; 2026 SmartEvent. centralized premium event ticketing & registration nodes.
                </p>
                <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4E7D5B] animate-pulse"></span>
                    Ecosystem Nominal
                </div>
            </div>

        </div>
    </footer>

</body>

</html>
