@props(['cinematic' => false])
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

    <title>{{ config('app.name', 'SmartEvent') }} | Secure Access</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-cream text-slate-900 dark:bg-slate-950 dark:text-slate-100 selection:bg-[#4E7D5B] selection:text-white">
    @if ($cinematic)
        {{ $slot }}
    @else
        <div class="min-h-screen flex items-center justify-center px-8 py-20 relative overflow-hidden">
            <!-- Abstract Background -->
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <div
                    class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#4E7D5B]/20 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#4E7D5B]/10 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/2">
                </div>
            </div>

            <div class="relative w-full max-w-lg">
                <div class="mb-12 text-center">
                    <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-4">
                        <div
                            class="w-16 h-16 rounded-[2rem] bg-[#4E7D5B] flex items-center justify-center text-white shadow-2xl shadow-[#4E7D5B]/20 transition-transform hover:scale-105 duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="font-serif text-3xl tracking-tight text-slate-900 dark:text-white">SmartEvent</span>
                    </a>
                </div>

                <div class="premium-card bg-white dark:bg-slate-900 dark:border-slate-800 p-12 shadow-2xl shadow-[#4E7D5B]/5">
                    {{ $slot }}
                </div>

                <p class="mt-12 text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">
                    Protected by Grounded Security Protocols
                </p>
            </div>
        </div>
    @endif
</body>

</html>