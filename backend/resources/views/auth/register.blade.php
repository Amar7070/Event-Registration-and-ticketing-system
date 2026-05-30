<x-guest-layout :cinematic="true">
    <div class="min-h-screen bg-[#FDFBF7] flex items-stretch">

         <!-- Left Side: Monochromatic Image Card -->
         <div class="hidden lg:flex flex-1 p-6">
             <div class="relative w-full h-full bg-[#4E7D5B] rounded-[3rem] overflow-hidden shadow-2xl flex flex-col">
                 <div class="flex-1 relative">
                     <img src="{{ asset('networking_gathering.png') }}"
                         class="absolute inset-0 w-full h-full object-cover grayscale opacity-90" alt="SmartEvent Networking">
                     <div class="absolute inset-0 bg-[#4E7D5B]/30 mix-blend-multiply"></div>
                 </div>
                 <div class="p-20 relative z-10 bg-[#4E7D5B]">
                     <h2 class="text-6xl font-serif text-white mb-8 leading-tight tracking-tighter">Host and Discover Elite Experiences</h2>
                     <p class="text-xl text-white/70 font-serif italic leading-relaxed max-w-sm">
                         Sign up to buy tickets, download secure cryptographic passes, and unlock premium organizer dashboards.
                     </p>
                 </div>
             </div>
         </div>

         <!-- Right Side: Registration Form -->
         <div class="flex-1 flex flex-col items-center justify-center relative px-8 md:px-20 py-12">

             <!-- Thin Progress Bar at Top -->
             <div class="absolute top-0 left-0 w-full h-1 bg-slate-50 overflow-hidden">
                 <div class="h-full w-1/3 bg-[#4E7D5B] transition-all duration-1000"></div>
             </div>

             <div class="w-full max-w-md text-left">
                 <!-- Header -->
                 <div class="mb-16">
                     <div class="flex items-center gap-3 mb-8">
                         <div class="w-10 h-10 bg-[#4E7D5B] rounded-xl flex items-center justify-center text-white">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                             </svg>
                         </div>
                         <h1 class="text-2xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</h1>
                     </div>
                     <h2 class="text-4xl font-serif text-slate-900 mb-2 leading-tight">Create your account</h2>
                     <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Step 1 of 3: Personal details</p>
                 </div>

                 <!-- Registration Form -->
                 <form method="POST" action="{{ route('register') }}" class="space-y-6">
                     @csrf

                     <!-- Full Name -->
                     <div class="space-y-2">
                         <label for="name"
                             class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                         <div class="relative group">
                             <div
                                 class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                                 <i data-lucide="user" class="w-4 h-4"></i>
                             </div>
                             <input id="name" type="text" name="name" :value="old('name')" required autofocus
                                 placeholder="Jane Doe"
                                 class="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm">
                         </div>
                         <x-input-error :messages="$errors->get('name')" class="mt-2" />
                     </div>

                     <!-- Email Address -->
                     <div class="space-y-2">
                         <label for="email"
                             class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                         <div class="relative group">
                             <div
                                 class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                                 <i data-lucide="mail" class="w-4 h-4"></i>
                             </div>
                             <input id="email" type="email" name="email" :value="old('email')" required
                                 placeholder="jane@example.com"
                                 class="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm">
                         </div>
                         <x-input-error :messages="$errors->get('email')" class="mt-2" />
                     </div>

                     <!-- Password -->
                     <div class="space-y-2">
                         <label for="password"
                             class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                         <div class="relative group">
                             <div
                                 class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                                 <i data-lucide="lock" class="w-4 h-4"></i>
                             </div>
                             <input id="password" type="password" name="password" required placeholder="••••••••"
                                 class="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm">
                         </div>
                         <x-input-error :messages="$errors->get('password')" class="mt-2" />
                     </div>

                     <!-- Actions -->
                     <div class="pt-10 space-y-12">
                         <button type="submit"
                             class="w-full bg-[#4E7D5B] text-white py-5 rounded-full text-[11px] font-black uppercase tracking-[0.3em] shadow-xl shadow-[#4E7D5B]/20 hover:scale-[1.02] active:scale-95 transition-all">
                             Continue
                         </button>

                         <div class="relative">
                             <div class="absolute inset-0 flex items-center">
                                 <div class="w-full border-t border-slate-100"></div>
                             </div>
                             <div class="relative flex justify-center">
                                 <span
                                     class="px-6 bg-[#FDFBF7] text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Or continue with</span>
                             </div>
                         </div>

                         <div class="flex gap-4">
                             <button type="button"
                                 class="flex-1 bg-white border border-slate-100 py-4 rounded-full flex items-center justify-center gap-3 hover:bg-slate-50 transition-all shadow-sm">
                                 <img src="https://www.google.com/favicon.ico" class="w-4 h-4 grayscale opacity-40">
                                 <span
                                     class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Google</span>
                             </button>
                             <button type="button"
                                 class="flex-1 bg-white border border-slate-100 py-4 rounded-full flex items-center justify-center gap-3 hover:bg-slate-50 transition-all shadow-sm">
                                 <i data-lucide="github" class="w-4 h-4 text-slate-900 opacity-60"></i>
                                 <span
                                     class="text-[10px] font-black text-slate-900 uppercase tracking-widest">GitHub</span>
                             </button>
                         </div>

                         <p class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                             Already have an account? <a href="{{ route('login') }}"
                                 class="text-[#4E7D5B] hover:underline underline-offset-4 decoration-2">Log in</a>
                         </p>
                     </div>
                 </form>
             </div>
         </div>
     </div>
</x-guest-layout>