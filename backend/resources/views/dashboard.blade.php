<x-app-layout>
    <div class="pt-32 pb-24 bg-[#FAF9F5] dark:bg-slate-950 min-h-screen px-6 md:px-12 transition-colors duration-500">
        <div class="max-w-[1440px] mx-auto space-y-12">
            
            @if(session('success'))
                <div class="p-6 bg-primary/10 border border-primary/20 text-primary rounded-[2rem] flex items-center gap-6 shadow-xl shadow-primary/5 animate-slide-up">
                    <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm border border-transparent dark:border-slate-800">
                        <i data-lucide="check" class="w-6 h-6 text-primary"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] block mb-1 leading-none">Operation Successful</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-6 bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20 text-rose-600 dark:text-rose-450 rounded-[2rem] flex items-center gap-6 shadow-xl shadow-rose-500/5 animate-slide-up">
                    <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm border border-transparent dark:border-slate-800">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-rose-500"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] block mb-1 leading-none">Process Halt</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            
            <!-- Welcome Hero Grid -->
            <section class="grid gap-8 lg:grid-cols-[1.6fr_0.9fr] items-stretch">
                <!-- Welcome Banner -->
                <div class="premium-card p-12 bg-[#1E293B] text-white relative overflow-hidden flex flex-col justify-center rounded-[3rem] border border-slate-800 shadow-2xl">
                    <div class="absolute top-0 right-0 w-80 h-80 bg-primary/10 rounded-full blur-[100px] translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-500/5 rounded-full blur-[100px] -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary bg-primary/10 px-4 py-2 border border-primary/20 rounded-full">Ecosystem Dashboard</span>
                            <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif text-white tracking-tight leading-tight">
                            Welcome back,<br>
                            <span class="text-primary italic font-normal">{{ auth()->user()->name }}</span>
                        </h1>
                        <p class="text-lg text-slate-400 max-w-xl leading-relaxed">
                            Your personalized explorer console is synchronized. Track your reservations, audit legal notifications, or manage your organizer parameters seamlessly.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="{{ route('events.index') }}" class="btn-primary bg-primary text-white border-primary shadow-xl shadow-primary/20 hover:bg-primary/95 hover:-translate-y-0.5 transition-all px-10 py-4 text-xs font-black uppercase tracking-widest">
                                Discover Experiences
                            </a>
                            <a href="{{ route('user.tickets.index') }}" class="px-10 py-4 rounded-full bg-white/5 border border-white/10 text-xs font-black uppercase tracking-widest text-slate-300 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all">
                                Ticket Vault
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Navigation Sidebar Nodes -->
                <div class="grid grid-cols-1 gap-6">
                    <a href="{{ route('user.tickets.index') }}" class="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 flex items-center justify-between group rounded-[2.5rem] hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-2xl bg-cream dark:bg-slate-800/60 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                <i data-lucide="ticket" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-serif text-slate-900 dark:text-white group-hover:text-primary transition-colors">Active Passes</h3>
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $activeTicketsCount }} Upcoming Experiences</p>
                            </div>
                        </div>
                        <i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 group-hover:text-primary group-hover:translate-x-1.5 transition-all"></i>
                    </a>

                    <a href="{{ route('waitlist.index') }}" class="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 flex items-center justify-between group rounded-[2.5rem] hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-2xl bg-cream dark:bg-slate-800/60 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                <i data-lucide="hourglass" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-serif text-slate-900 dark:text-white group-hover:text-primary transition-colors">My Waitlists</h3>
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $waitlistsCount }} Requests Pending</p>
                            </div>
                        </div>
                        <i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 group-hover:text-primary group-hover:translate-x-1.5 transition-all"></i>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 flex items-center justify-between group rounded-[2.5rem] hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-2xl bg-cream dark:bg-slate-800/60 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                <i data-lucide="shield-check" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-serif text-slate-900 dark:text-white group-hover:text-primary transition-colors">Profile Governance</h3>
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider mt-0.5">Identity & Security</p>
                            </div>
                        </div>
                        <i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 group-hover:text-primary group-hover:translate-x-1.5 transition-all"></i>
                    </a>
                </div>
            <!-- Experience Calendar Section -->
            @php
                $bookedTicketsArray = $confirmedTickets->map(function($t) {
                    return [
                        'id' => $t->id,
                        'booking_reference' => $t->booking_reference,
                        'quantity' => $t->quantity,
                        'status' => $t->status,
                        'event_title' => $t->event->title,
                        'event_date' => $t->event->start_date->format('Y-m-d'),
                        'event_time' => $t->event->start_date->format('g:i A'),
                        'event_category' => $t->event->category->name,
                        'event_city' => $t->event->venue ? $t->event->venue->city : 'Online',
                        'event_venue' => $t->event->venue ? $t->event->venue->name : 'Digital Realm',
                        'event_banner' => $t->event->getFirstMediaUrl('banners') ?: 'https://images.unsplash.com/photo-1540575861501-7ad05823c23d?auto=format&fit=crop&q=80&w=400',
                        'ticket_type' => $t->ticketType->name,
                        'amount' => $t->total_amount,
                        'ticket_url' => route('user.tickets.show', $t->booking_reference)
                    ];
                })->toArray();
            @endphp
            
            <script>
                window.dashboardBookings = @json($bookedTicketsArray);
            </script>
            
            <section x-data="{
                currentYear: new Date().getFullYear(),
                currentMonth: new Date().getMonth(),
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                
                daysInMonth(month, year) {
                    return new Date(year, month + 1, 0).getDate();
                },
                startDayOfWeek(month, year) {
                    return new Date(year, month, 1).getDay();
                },
                prevMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },
                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                },
                
                bookedTickets: window.dashboardBookings || [],
                
                selectedDayTickets: [],
                showTicketSlider: false,
                selectedDateStr: '',
                
                getTicketsForDay(day) {
                    const dateStr = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    return this.bookedTickets.filter(t => t.event_date === dateStr);
                },
                
                selectDay(day) {
                    const tickets = this.getTicketsForDay(day);
                    if (tickets.length > 0) {
                        this.selectedDayTickets = tickets;
                        this.selectedDateStr = new Date(this.currentYear, this.currentMonth, day).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                        this.showTicketSlider = true;
                    }
                },
                
                // Get all tickets for the active month
                get currentMonthTickets() {
                    const monthPrefix = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-`;
                    return this.bookedTickets.filter(t => t.event_date.startsWith(monthPrefix)).sort((a, b) => a.event_date.localeCompare(b.event_date));
                }
            }" class="grid gap-8 lg:grid-cols-[1.5fr_1fr] items-stretch">
                
                <!-- Calendar Card -->
                <div class="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] shadow-xl shadow-primary/[0.01] flex flex-col justify-between">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                        <div>
                            <span class="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">Pass Timeline</span>
                            <h2 class="text-2xl font-serif text-slate-900 dark:text-white font-medium">Experience Calendar</h2>
                        </div>
                        
                        <!-- Month Switcher -->
                        <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 px-4 py-2 rounded-full shadow-sm w-fit">
                            <button @click="prevMonth()" class="text-slate-400 hover:text-primary transition-colors focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            </button>
                            <span class="text-xs font-black uppercase tracking-widest text-slate-700 dark:text-slate-200 select-none min-w-[110px] text-center" x-text="`${monthNames[currentMonth]} ${currentYear}`"></span>
                            <button @click="nextMonth()" class="text-slate-400 hover:text-primary transition-colors focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Days of Week Headers -->
                    <div class="grid grid-cols-7 gap-2 text-center mb-4">
                        <template x-for="dayName in daysOfWeek">
                            <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 py-1" x-text="dayName"></span>
                        </template>
                    </div>

                    <!-- Days Grid -->
                    <div class="grid grid-cols-7 gap-2">
                        <!-- Empty cells for leading offset -->
                        <template x-for="i in Array.from({length: startDayOfWeek(currentMonth, currentYear)})">
                            <div class="aspect-square bg-transparent"></div>
                        </template>

                        <!-- Days of month -->
                        <template x-for="day in daysInMonth(currentMonth, currentYear)">
                            <button @click="selectDay(day)"
                                    :disabled="getTicketsForDay(day).length === 0"
                                    :class="getTicketsForDay(day).length > 0 
                                        ? 'bg-primary/5 text-primary border border-primary/20 hover:bg-primary hover:text-white hover:border-primary cursor-pointer hover:shadow-lg hover:shadow-primary/20 scale-[1.02] font-black' 
                                        : 'text-slate-400 dark:text-slate-500 border border-slate-50 dark:border-slate-800/60 cursor-default'"
                                    class="aspect-square flex flex-col items-center justify-center rounded-2xl transition-all duration-300 relative group font-sans text-xs">
                                
                                <span x-text="day" class="relative z-10 text-sm"></span>

                                <!-- Event indicator dot -->
                                <template x-if="getTicketsForDay(day).length > 0">
                                    <span class="absolute bottom-2.5 w-1.5 h-1.5 rounded-full bg-[#4E7D5B] group-hover:bg-white z-10 transition-colors animate-pulse"></span>
                                </template>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Right Schedule Agenda Card -->
                <div class="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] shadow-xl shadow-primary/[0.01] flex flex-col justify-between">
                    <div>
                        <div class="border-b border-slate-100 dark:border-slate-800 pb-4 mb-6">
                            <span class="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">Ecosystem Grid</span>
                            <h2 class="text-xl font-serif text-slate-900 dark:text-white font-medium">Monthly Schedule</h2>
                        </div>
                        
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1 no-scrollbar">
                            <template x-for="ticket in currentMonthTickets" :key="ticket.id">
                                <div @click="selectedDayTickets = [ticket]; selectedDateStr = new Date(ticket.event_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }); showTicketSlider = true;" 
                                     class="flex items-center gap-4 p-4 bg-cream/35 dark:bg-slate-800/10 border border-slate-50 dark:border-slate-800/60 hover:border-primary/25 rounded-2xl cursor-pointer group transition-all duration-300 hover:bg-cream/70 dark:hover:bg-slate-800/30">
                                    
                                    <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 flex flex-col items-center justify-center text-primary shrink-0 shadow-sm">
                                        <span class="text-[9px] font-black leading-none text-slate-400" x-text="new Date(ticket.event_date).toLocaleDateString('en-US', {month: 'short'}).toUpperCase()"></span>
                                        <span class="text-base font-serif font-black leading-none mt-1 text-slate-800 dark:text-slate-100" x-text="new Date(ticket.event_date).getDate()"></span>
                                    </div>
                                    
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-primary transition-colors" x-text="ticket.event_title"></h4>
                                        <div class="flex items-center gap-1.5 text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">
                                            <span x-text="ticket.event_time"></span>
                                            <span>&bull;</span>
                                            <span x-text="ticket.event_city"></span>
                                        </div>
                                    </div>
                                    
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 group-hover:text-primary group-hover:translate-x-1 transition-all" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                </div>
                            </template>

                            <template x-if="currentMonthTickets.length === 0">
                                <div class="py-16 text-center bg-cream/20 border border-slate-50 dark:border-slate-800/60 rounded-2xl flex flex-col items-center justify-center p-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">No Events Booked</span>
                                    <p class="text-[10px] text-slate-400 mt-1 max-w-[200px] leading-relaxed">No gathering nodes active in this month interval.</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <a href="{{ route('events.index') }}" class="btn-primary w-full py-3.5 text-xs tracking-widest font-black uppercase text-center mt-6">
                        Find New Gatherings
                    </a>
                </div>

                <!-- Slide-out Ticket Drawer overlay -->
                <div x-show="showTicketSlider" 
                     class="fixed inset-0 z-[100] overflow-hidden" 
                     style="display: none;"
                     aria-labelledby="slide-over-title" 
                     role="dialog" 
                     aria-modal="true">
                    
                    <div class="absolute inset-0 overflow-hidden">
                        <!-- Backdrop shadow -->
                        <div x-show="showTicketSlider"
                             x-transition:enter="ease-in-out duration-500"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in-out duration-500"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="showTicketSlider = false"
                             class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" 
                             aria-hidden="true"></div>

                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                            <!-- Drawer panel -->
                            <div x-show="showTicketSlider"
                                 x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                                 x-transition:enter-start="translate-x-full"
                                 x-transition:enter-end="translate-x-0"
                                 x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                                 x-transition:leave-start="translate-x-0"
                                 x-transition:leave-end="translate-x-full"
                                 class="pointer-events-auto w-screen max-w-md">
                                
                                <div class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-slate-900 shadow-2xl border-l border-slate-100 dark:border-slate-800/80">
                                    <div class="px-6 py-6 bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-serif font-bold text-slate-900 dark:text-white" id="slide-over-title">Active Reservations</h2>
                                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1" x-text="selectedDateStr"></p>
                                        </div>
                                        <button @click="showTicketSlider = false" class="rounded-xl border border-slate-200 dark:border-slate-800 p-2 text-slate-400 hover:text-slate-500 dark:hover:text-white focus:outline-none">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>

                                    <div class="relative flex-1 p-6 space-y-6">
                                        <!-- Stacking Ticket Slider List -->
                                        <template x-for="ticket in selectedDayTickets" :key="ticket.id">
                                            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-lg flex flex-col">
                                                
                                                <!-- Event Banner -->
                                                <div class="h-40 relative">
                                                    <img :src="ticket.event_banner" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                                                    <span class="absolute bottom-4 left-4 bg-white/15 backdrop-blur-md text-white border border-white/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest" x-text="ticket.event_category"></span>
                                                </div>

                                                <!-- Ticket Details -->
                                                <div class="p-6 space-y-6">
                                                    <div>
                                                        <h3 class="text-lg font-serif font-bold text-slate-900 dark:text-white leading-tight" x-text="ticket.event_title"></h3>
                                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mt-2" x-text="ticket.ticket_type"></span>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                                                        <div>
                                                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Date & Time</span>
                                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200" x-text="ticket.event_time"></span>
                                                        </div>
                                                        <div>
                                                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Location</span>
                                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate block max-w-[120px]" x-text="ticket.event_venue" :title="ticket.event_venue"></span>
                                                        </div>
                                                        <div>
                                                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Reference Code</span>
                                                            <span class="text-xs font-bold text-primary font-mono" x-text="ticket.booking_reference"></span>
                                                        </div>
                                                        <div>
                                                            <span class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Quantity</span>
                                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200" x-text="`${ticket.quantity} Passes`"></span>
                                                        </div>
                                                    </div>

                                                    <a :href="ticket.ticket_url" class="btn-primary w-full py-3.5 text-xs tracking-widest font-black uppercase text-center block mt-6">
                                                        Open Digital Pass
                                                    </a>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Real stats counters -->
            <section class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 relative overflow-hidden group rounded-[2.5rem] hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-125 transition-transform duration-700"></div>
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-[0.2em] block mb-4">Total Gatherings</span>
                    <div class="text-5xl font-serif text-slate-900 dark:text-white mb-3">{{ $totalGatheringsCount }}</div>
                    <div class="text-[10px] font-bold text-primary uppercase tracking-widest flex items-center gap-1.5">
                        <i data-lucide="star" class="w-3.5 h-3.5 fill-primary"></i> Active member status
                    </div>
                </div>

                <div class="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 relative overflow-hidden group rounded-[2.5rem] hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-125 transition-transform duration-700"></div>
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-[0.2em] block mb-4">Feedback Resonance</span>
                    <div class="text-5xl font-serif text-slate-900 dark:text-white mb-3">{{ $reviewsCount }}</div>
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-widest">
                        Total Reviews Provided
                    </div>
                </div>

                <div class="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 relative overflow-hidden group rounded-[2.5rem] hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-125 transition-transform duration-700"></div>
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-[0.2em] block mb-4">Distinct Domains</span>
                    <div class="text-5xl font-serif text-slate-900 dark:text-white mb-3">{{ $savedCount }}</div>
                    <div class="text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-widest">
                        Unique event domains explored
                    </div>
                </div>

                <a href="{{ route('events.index') }}" class="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 border-dashed flex flex-col items-center justify-center text-center group rounded-[2.5rem] hover:bg-white dark:hover:bg-slate-800/60 hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
                    <div class="w-14 h-14 rounded-full bg-cream dark:bg-slate-800 flex items-center justify-center text-primary mb-4 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500 shadow-sm">
                        <i data-lucide="plus" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] group-hover:text-primary transition-colors">Venture Map</span>
                    <span class="text-[9px] text-slate-400 dark:text-slate-400 font-bold uppercase tracking-widest mt-1">Discover new realms</span>
                </a>
            </section>

            <!-- Steward / Host Privilege Node -->
            @if(auth()->user()->hasRole('organizer'))
                @if(!auth()->user()->is_approved)
                    <!-- Pending Steward Application -->
                    <section class="premium-card p-12 bg-amber-500/[0.03] border border-amber-500/20 dark:border-amber-550/30 relative overflow-hidden rounded-[3rem] shadow-xl shadow-amber-500/5">
                        <div class="absolute top-0 right-0 w-80 h-80 bg-amber-500/5 rounded-full blur-[80px] translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
                        <div class="flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                            <div class="max-w-2xl space-y-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-amber-600 bg-amber-500/10 px-4 py-2 border border-amber-500/20 rounded-full">Steward Application Pending</span>
                                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></div>
                                </div>
                                <h2 class="text-3xl font-serif text-slate-900 dark:text-white leading-tight">Credentials Under Governance Review</h2>
                                <p class="text-slate-500 dark:text-slate-400 leading-relaxed font-serif italic text-sm">
                                    "Our system stewards are currently auditing your host profile parameters to ensure adherence to intellectual property regulations and community standard policies."
                                </p>
                            </div>
                            <div class="px-8 py-5 rounded-full bg-amber-500/10 border border-amber-500/20 text-xs font-black uppercase tracking-widest text-amber-700 whitespace-nowrap">
                                Undergoing Security Check
                            </div>
                        </div>
                    </section>
                @else
                    <!-- Approved Steward - Quick Switcher -->
                    <section class="premium-card p-12 bg-primary/[0.03] border border-primary/20 dark:border-primary/30 relative overflow-hidden rounded-[3rem] shadow-xl shadow-primary/5">
                        <div class="absolute top-0 right-0 w-80 h-80 bg-primary/5 rounded-full blur-[80px] translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
                        <div class="flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                            <div class="max-w-2xl space-y-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary bg-primary/10 px-4 py-2 border border-primary/20 rounded-full">Approved Host Privileges</span>
                                    <div class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></div>
                                </div>
                                <h2 class="text-3xl font-serif text-slate-900 dark:text-white leading-tight">Your Steward Governance Console is Active</h2>
                                <p class="text-slate-500 dark:text-slate-400 leading-relaxed font-serif italic text-sm">
                                    "Initialize physical event blueprints, coordinate custom check-in scanners, and track attendee waitlists instantly."
                                </p>
                            </div>
                            <a href="{{ route('organizer.dashboard') }}" class="btn-primary bg-primary text-white border-primary shadow-xl shadow-primary/20 px-10 py-5 text-xs font-black uppercase tracking-[0.2em] hover:bg-primary/90 transition-all whitespace-nowrap">
                                Access Host Control Room
                            </a>
                        </div>
                    </section>
                @endif
            @else
                <!-- Apply to become host -->
                @if(!auth()->user()->hasRole('admin'))
                    <section class="premium-card p-12 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 relative overflow-hidden group rounded-[3rem] shadow-2xl shadow-primary/5">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-[80px] translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
                        <div class="flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                            <div class="max-w-2xl space-y-4">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary bg-primary/10 px-4 py-2 border border-primary/20 rounded-full inline-block mb-2">Architect Blueprint</span>
                                <h2 class="text-3xl font-serif text-slate-900 dark:text-white leading-tight">Elevate your connections. Apply to become an <span class="italic text-primary">Organizer</span>.</h2>
                                <p class="text-slate-500 dark:text-slate-400 leading-relaxed font-serif italic text-sm">
                                    "Host premium event nodes, cultivate custom queue systems, generate reservation codes, and coordinate with real-time verification scanners."
                                </p>
                            </div>
                            <form action="{{ route('profile.apply-organizer') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-primary bg-primary text-white border-primary shadow-xl shadow-primary/20 px-10 py-5 text-xs font-black uppercase tracking-[0.2em] hover:bg-primary/95 hover:-translate-y-0.5 active:scale-95 transition-all whitespace-nowrap">
                                    Apply to Host
                                </button>
                            </form>
                        </div>
                    </section>
                @endif
            @endif

            <!-- Upcoming Experience Highlights -->
            <section class="grid gap-8 lg:grid-cols-[1.5fr_1fr]">
                <!-- Next Gathering Card -->
                <div class="premium-card p-12 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[3rem] shadow-xl shadow-primary/[0.02]">
                    <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] block mb-8">Next Gathering Node</span>
                    
                    @if($upcomingTicket)
                        <div class="flex flex-col md:flex-row gap-10 items-stretch">
                            <div class="w-full md:w-1/2 aspect-video md:aspect-auto rounded-[2.5rem] overflow-hidden shadow-2xl relative group min-h-[220px]">
                                <img src="{{ $upcomingTicket->event->getFirstMediaUrl('banners') ?: 'https://images.unsplash.com/photo-1540575861501-7ad05823c23d?auto=format&fit=crop&q=80&w=900' }}" 
                                     class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                                <div class="absolute top-6 left-6 bg-white/95 dark:bg-slate-950/95 backdrop-blur px-5 py-2.5 rounded-2xl text-center shadow-xl border border-white/50 dark:border-slate-800/50">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 leading-none mb-1">{{ $upcomingTicket->event->start_date->format('M') }}</span>
                                    <span class="block text-2xl font-serif text-slate-900 dark:text-white leading-none">{{ $upcomingTicket->event->start_date->format('d') }}</span>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 flex flex-col justify-between py-2 space-y-6">
                                <div>
                                    <span class="text-[9px] font-black text-primary uppercase tracking-widest bg-primary/10 border border-primary/20 px-3 py-1 rounded-full mb-3 inline-block">
                                        {{ $upcomingTicket->event->category->name }}
                                    </span>
                                    <h2 class="text-3xl font-serif text-slate-900 dark:text-white leading-tight mb-3 truncate max-w-sm" title="{{ $upcomingTicket->event->title }}">
                                        {{ $upcomingTicket->event->title }}
                                    </h2>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[9px] flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-primary"></i> 
                                        Starts {{ $upcomingTicket->event->start_date->format('H:i A') }} ({{ $upcomingTicket->event->timezone ?? 'IST' }})
                                    </p>
                                </div>
                                <div class="flex items-center gap-6 py-5 border-y border-slate-100 dark:border-slate-800">
                                    <div class="min-w-0">
                                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Location Node</span>
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate block max-w-[150px]">{{ $upcomingTicket->event->venue->name ?? 'Online/Virtual' }}</span>
                                    </div>
                                    <div class="min-w-0 border-l border-slate-100 dark:border-slate-800 pl-6">
                                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Reference Code</span>
                                        <span class="text-xs font-bold text-primary font-mono block truncate">{{ $upcomingTicket->booking_reference }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-4">
                                    <a href="{{ route('user.tickets.show', $upcomingTicket->booking_reference) }}" class="btn-primary bg-primary text-white border-primary shadow-lg shadow-primary/20 px-6 py-3 text-[10px] font-black uppercase tracking-widest">
                                        Open Digital Pass
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="py-16 text-center bg-cream/40 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] flex flex-col items-center justify-center p-8">
                            <div class="w-16 h-16 rounded-full bg-cream dark:bg-slate-900 flex items-center justify-center text-slate-300 mb-6 border border-slate-50 dark:border-slate-800">
                                <i data-lucide="compass" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-xl font-serif text-slate-900 dark:text-white mb-2">No Active Reservations</h3>
                            <p class="text-slate-400 max-w-sm text-sm leading-relaxed mb-6">
                                You have no upcoming confirmed passes right now. Adventure awaits inside the Experience Map!
                            </p>
                            <a href="{{ route('events.index') }}" class="btn-primary bg-primary text-white border-primary shadow-xl shadow-primary/10 px-8 py-3 text-[10px] font-black uppercase tracking-widest">
                                Explore Realm Map
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Recent Activities & History -->
                <div class="premium-card p-12 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[3rem] shadow-xl shadow-primary/[0.02] flex flex-col">
                    <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] block mb-8">Pass Vault History</span>
                    
                    @if($recentTickets->count() > 0)
                        <div class="space-y-6 flex-1 overflow-y-auto max-h-[320px] pr-2 no-scrollbar">
                            @foreach($recentTickets as $ticket)
                                <a href="{{ route('user.tickets.show', $ticket->booking_reference) }}" class="flex items-center justify-between p-5 bg-cream/30 dark:bg-slate-950/30 hover:bg-cream/70 dark:hover:bg-slate-950/60 border border-slate-50 dark:border-slate-800/40 hover:border-primary/25 rounded-2xl group transition-all duration-300">
                                    <div class="flex items-center gap-4 min-w-0">
                                        <div class="w-12 h-12 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 flex items-center justify-center text-primary group-hover:scale-110 transition-transform flex-shrink-0 shadow-sm">
                                            @if($ticket->status === 'confirmed')
                                                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                                            @else
                                                <i data-lucide="help-circle" class="w-5 h-5 text-slate-400"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-primary transition-colors max-w-[180px]" title="{{ $ticket->event->title }}">
                                                {{ $ticket->event->title }}
                                            </h4>
                                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest block mt-0.5">{{ $ticket->event->start_date->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="font-mono text-xs font-bold block text-slate-900 dark:text-white">{{ $ticket->booking_reference }}</span>
                                        <span class="text-[8px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full {{ $ticket->status === 'confirmed' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }} inline-block mt-1">
                                            {{ $ticket->status }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center bg-cream/20 dark:bg-slate-950/20 border border-slate-50 dark:border-slate-800/40 rounded-3xl flex flex-col items-center justify-center p-8 flex-1">
                            <i data-lucide="archive" class="w-8 h-8 text-slate-300 mb-4"></i>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Vault Empty</span>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
