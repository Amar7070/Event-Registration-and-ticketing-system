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

        <title>{{ config('app.name', 'Evenzo') }} - {{ $title ?? 'Smart Event Platform' }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(18px); }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
        <header class="sticky top-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur border-b border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20 gap-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="w-11 h-11 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Evenzo</p>
                            <p class="text-lg font-extrabold text-slate-900 dark:text-white">Event Marketplace</p>
                        </div>
                    </a>
                    <nav class="hidden md:flex items-center gap-4">
                        <a href="{{ route('events.index') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition dark:text-slate-300 dark:hover:text-white">Explore Events</a>
                        <a href="{{ route('about') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition dark:text-slate-300 dark:hover:text-white">About</a>
                        <a href="{{ route('blog') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition dark:text-slate-300 dark:hover:text-white">Blog</a>
                        <a href="{{ route('help') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition dark:text-slate-300 dark:hover:text-white">Help</a>
                        <a href="{{ route('contact') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition dark:text-slate-300 dark:hover:text-white">Contact</a>
                    </nav>
                    <div class="flex items-center gap-3">
                        <x-theme-toggle />
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition dark:text-slate-300 dark:hover:text-white">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition dark:text-slate-300 dark:hover:text-white">Sign in</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-200/30 hover:bg-indigo-700 transition">Join Now</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="bg-slate-950 text-slate-200 border-t border-slate-800 pt-16 pb-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                    <div>
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <span class="text-xl font-bold tracking-tight">Evenzo</span>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed">The world's leading event management platform. Discover and organize amazing experiences.</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-6 text-sm uppercase tracking-widest text-slate-200">Platform</h4>
                        <ul class="space-y-4 text-sm text-slate-400">
                            <li><a href="{{ route('events.index') }}" class="hover:text-white transition">Browse Events</a></li>
                            <li><a href="{{ route('organizer.events.index') }}" class="hover:text-white transition">Organize Event</a></li>
                            <li><a href="#" class="hover:text-white transition">Mobile App</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-6 text-sm uppercase tracking-widest text-slate-200">Support</h4>
                        <ul class="space-y-4 text-sm text-slate-400">
                            <li><a href="{{ route('help') }}" class="hover:text-white transition">Help Center</a></li>
                            <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                            <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-6 text-sm uppercase tracking-widest text-slate-200">Newsletter</h4>
                        <p class="text-slate-400 text-sm mb-4">Stay updated with the latest event launches and platform news.</p>
                        <form class="flex flex-col gap-3">
                            <input type="email" placeholder="Email address" class="premium-input bg-slate-900/10 border-slate-800 text-white focus:border-indigo-400 focus:ring-indigo-400/10" />
                            <button class="btn-primary w-full">Subscribe</button>
                        </form>
                    </div>
                </div>
                <div class="pt-10 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-6 text-slate-400 text-sm">
                    <p>© {{ date('Y') }} Evenzo. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a>
                        <a href="{{ route('help') }}" class="hover:text-white transition">Support</a>
                        <a href="{{ route('about') }}" class="hover:text-white transition">About</a>
                    </div>
                </div>
            </div>
        </footer>

        <script src="https://unpkg.com/lucide@latest" defer></script>
        @stack('scripts')
    </body>
</html>
