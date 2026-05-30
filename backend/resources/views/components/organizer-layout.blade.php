<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

    <title>{{ config('app.name', 'SmartEvent') }} | Host Console</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="antialiased bg-[#FAF9F5] text-slate-900 selection:bg-primary selection:text-white">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
        
        <!-- Organizer Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-80 bg-[#1E293B] text-white transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] lg:translate-x-0 lg:static lg:inset-0 shadow-2xl"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center px-10 py-12">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/20">
                            <i data-lucide="compass" class="w-5 h-5"></i>
                        </div>
                        <span class="font-serif text-2xl tracking-tight text-white">SmartEvent</span>
                    </a>
                </div>

                <!-- Navigation List -->
                <nav class="flex-1 px-6 space-y-2 overflow-y-auto no-scrollbar">
                    <div class="px-4 pb-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">Host Control Room</div>
                    
                    <a href="{{ route('organizer.events.index') }}" class="{{ request()->routeIs('organizer.events.*') ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group">
                        <i data-lucide="calendar" class="w-5 h-5 mr-4 group-hover:scale-110 transition-transform"></i> My Gatherings
                    </a>

                    <a href="{{ route('organizer.attendees.index') }}" class="{{ request()->routeIs('organizer.attendees.*') ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group">
                        <i data-lucide="users" class="w-5 h-5 mr-4 group-hover:scale-110 transition-transform"></i> Attendee Directory
                    </a>

                    <a href="{{ route('organizer.analytics.index') }}" class="{{ request()->routeIs('organizer.analytics.*') ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group">
                        <i data-lucide="trending-up" class="w-5 h-5 mr-4 group-hover:scale-110 transition-transform"></i> Event Insights
                    </a>

                    <a href="{{ route('organizer.reviews.index') }}" class="{{ request()->routeIs('organizer.reviews.*') ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group">
                        <i data-lucide="message-square" class="w-5 h-5 mr-4 group-hover:scale-110 transition-transform"></i> Guest Feedback
                    </a>

                    <div class="px-4 pt-8 pb-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">Identity Hub</div>
                    <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white hover:bg-white/5 flex items-center px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group">
                        <i data-lucide="user" class="w-5 h-5 mr-4 group-hover:scale-110 transition-transform"></i> Personal Console
                    </a>
                </nav>

                <!-- User Profile Context -->
                <div class="p-6 border-t border-white/5 bg-slate-900/50">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white font-black text-xs shadow-lg shadow-primary/10">
                                {{ substr(auth()->user()->name, 0, 2) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-white truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                                <p class="text-[9px] text-slate-500 font-black uppercase tracking-widest leading-none">Event Host</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-500 hover:text-rose-500 hover:bg-rose-500/10 transition-all">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 flex items-center justify-between px-10 py-6 sticky top-0 z-40 shadow-sm">
                <div class="flex items-center gap-6">
                    <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 flex items-center justify-center rounded-xl border border-slate-200 text-slate-400 hover:text-primary hover:border-primary transition-all lg:hidden">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h1 class="text-xl font-serif text-slate-900 tracking-tight">{{ $header ?? 'Host Workspace' }}</h1>
                </div>
                <div class="flex items-center gap-6">
                    <x-theme-toggle />
                    <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-[#FAF9F5] rounded-full border border-slate-100">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Host Mode Active</span>
                    </div>
                    <a href="{{ route('organizer.events.create') }}" class="btn-primary bg-primary text-white border-primary shadow-lg shadow-primary/10 px-6 py-2.5 rounded-full text-xs font-black uppercase tracking-widest hover:-translate-y-0.5 transition-all">
                        Create Blueprint
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-10 custom-scrollbar bg-[#FAF9F5]">
                @if(session('success'))
                    <div class="mb-10 p-6 bg-primary/10 border border-primary/20 text-primary rounded-[2rem] flex items-center gap-6 animate-slide-up shadow-xl shadow-primary/5">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                            <i data-lucide="check" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] block mb-1 leading-none">Operation Successful</span>
                            <span class="text-sm font-bold">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-10 p-6 bg-rose-50 border border-rose-100 text-rose-600 rounded-[2rem] flex items-center gap-6 animate-slide-up shadow-xl shadow-rose-500/5">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                            <i data-lucide="alert-triangle" class="w-6 h-6 text-rose-500"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] block mb-1 leading-none">Process Halt</span>
                            <span class="text-sm font-bold">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
