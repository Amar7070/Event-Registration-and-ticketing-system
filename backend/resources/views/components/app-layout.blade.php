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

    <title>{{ config('app.name', 'SmartEvent') }} - @yield('title', 'Event platform built for organizers and attendees')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-screen flex flex-col">
        <header x-data="{ open: false }" class="bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20 gap-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Evenzo</p>
                            <p class="text-lg font-extrabold text-slate-900">Event marketplace</p>
                        </div>
                    </a>

                    <nav class="hidden lg:flex items-center gap-1 xl:gap-4">
                        <a href="{{ route('events.index') }}" class="px-4 py-3 rounded-full text-sm font-semibold transition {{ request()->routeIs('events.index') ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Browse Events</a>
                        <a href="{{ route('about') }}" class="px-4 py-3 rounded-full text-sm font-semibold transition {{ request()->routeIs('about') ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">About</a>
                        <a href="{{ route('blog') }}" class="px-4 py-3 rounded-full text-sm font-semibold transition {{ request()->routeIs('blog') ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Blog</a>
                        <a href="{{ route('help') }}" class="px-4 py-3 rounded-full text-sm font-semibold transition {{ request()->routeIs('help') ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Help</a>
                        <a href="{{ route('contact') }}" class="px-4 py-3 rounded-full text-sm font-semibold transition {{ request()->routeIs('contact') ? 'bg-indigo-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">Contact</a>
                    </nav>

                    <div class="flex items-center gap-3">
                        <x-theme-toggle />
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-white">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 dark:text-slate-300 dark:hover:text-white">Log in</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-200/30 hover:bg-indigo-700 transition">Get Started</a>
                        @endauth
                        <button @click="open = !open" class="lg:hidden inline-flex items-center justify-center rounded-full border border-slate-200 p-3 text-slate-600 hover:bg-slate-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" @click.away="open = false" x-transition class="lg:hidden bg-white border-t border-slate-200 shadow-sm">
                <div class="px-4 py-3 space-y-2">
                    <a href="{{ route('events.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Browse Events</a>
                    <a href="{{ route('about') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">About</a>
                    <a href="{{ route('blog') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Blog</a>
                    <a href="{{ route('help') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Help</a>
                    <a href="{{ route('contact') }}" class="block rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Contact</a>
                </div>
            </div>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="bg-slate-900 text-slate-200 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-11 h-11 rounded-2xl bg-indigo-500 flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Evenzo</p>
                                <p class="text-xl font-bold text-white">Modern event experiences</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-400 max-w-xl leading-relaxed">Evenzo helps organizers host successful events and makes it easy for attendees to discover curated experiences anywhere.</p>
                    </div>

                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-400 mb-5">Explore</p>
                        <ul class="space-y-3 text-sm text-slate-300">
                            <li><a href="{{ route('events.index') }}" class="hover:text-white transition">Browse Events</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                            <li><a href="{{ route('blog') }}" class="hover:text-white transition">Blog</a></li>
                            <li><a href="{{ route('help') }}" class="hover:text-white transition">Help Center</a></li>
                        </ul>
                    </div>

                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-400 mb-5">Get in touch</p>
                        <ul class="space-y-3 text-sm text-slate-300">
                            <li class="hover:text-white transition">support@smartevent.com</li>
                            <li class="hover:text-white transition">+91 (800) 123-4567</li>
                            <li class="hover:text-white transition">Mumbai, India</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 border-t border-slate-800 pt-8 text-sm text-slate-500 flex flex-col md:flex-row md:justify-between gap-4">
                    <p>&copy; {{ date('Y') }} Evenzo. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a>
                        <a href="{{ route('help') }}" class="hover:text-white transition">Support</a>
                        <a href="{{ route('about') }}" class="hover:text-white transition">Privacy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script>window.addEventListener('DOMContentLoaded', function(){ if(window.lucide) lucide.createIcons(); });</script>
    @stack('scripts')
</body>
</html>
