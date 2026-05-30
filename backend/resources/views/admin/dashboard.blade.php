<x-admin-layout>
    <x-slot name="header">
        Governance Hub
    </x-slot>

    <div class="mb-12">
        <h2 class="text-4xl font-serif text-slate-900 mb-2">Platform Overview</h2>
        <p class="text-slate-500 font-serif italic text-lg">"Overseeing the architecture of intentional connection."</p>
    </div>

    <div class="grid gap-10 xl:grid-cols-[1.4fr_0.9fr] mb-16">
        <!-- Core Metrics -->
        <div class="grid gap-8 sm:grid-cols-2">
            <a href="{{ route('admin.users.index') }}" class="premium-card p-10 bg-white border-slate-100 group transition-all hover:shadow-2xl hover:shadow-primary/5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Total Resident Nodes</span>
                        <h2 class="mt-4 text-5xl font-serif text-slate-900">{{ number_format($metrics['total_users']) }}</h2>
                    </div>
                    <div class="w-14 h-14 bg-cream rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                        <i data-lucide="users" class="w-7 h-7"></i>
                    </div>
                </div>
                <p class="mt-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Manage Identity Grid</p>
            </a>

            <a href="{{ route('admin.events.index') }}" class="premium-card p-10 bg-white border-slate-100 group transition-all hover:shadow-2xl hover:shadow-primary/5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Architectures</span>
                        <h2 class="mt-4 text-5xl font-serif text-slate-900">{{ number_format($metrics['total_events']) }}</h2>
                    </div>
                    <div class="w-14 h-14 bg-cream rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                        <i data-lucide="calendar" class="w-7 h-7"></i>
                    </div>
                </div>
                <p class="mt-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Ecosystem Surveillance</p>
            </a>

            <a href="{{ route('admin.revenue.index') }}" class="premium-card p-10 bg-white border-slate-100 group transition-all hover:shadow-2xl hover:shadow-primary/5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Platform Energy Yield</span>
                        <h2 class="mt-4 text-5xl font-serif text-slate-900">₹{{ number_format($metrics['total_platform_fees'] / 1000, 1) }}k</h2>
                    </div>
                    <div class="w-14 h-14 bg-cream rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                        <i data-lucide="indian-rupee" class="w-7 h-7"></i>
                    </div>
                </div>
                <p class="mt-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Financial Integrity</p>
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="premium-card p-10 bg-white border-slate-100 group transition-all hover:shadow-2xl hover:shadow-primary/5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Resonance Count</span>
                        <h2 class="mt-4 text-5xl font-serif text-slate-900">{{ number_format($metrics['total_tickets']) }}</h2>
                    </div>
                    <div class="w-14 h-14 bg-cream rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                        <i data-lucide="ticket" class="w-7 h-7"></i>
                    </div>
                </div>
                <p class="mt-8 text-[10px] font-black uppercase tracking-widest text-slate-400">Connection Velocity</p>
            </a>
        </div>

        <!-- Pending Actions Center -->
        <div class="premium-card bg-[#1E293B] text-white p-12 relative overflow-hidden flex flex-col">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
            <div class="relative z-10 flex-1">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-primary">
                        <i data-lucide="shield-alert" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">Integrity Protocols</span>
                </div>
                <h2 class="text-4xl font-serif text-white mb-4">Urgent Actions</h2>
                <p class="text-slate-400 font-serif italic text-sm mb-12">"Immediate oversight required for ecosystem equilibrium."</p>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-6 bg-white/5 rounded-3xl border border-white/5">
                        <div>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Pending Host Nodes</span>
                            <span class="block text-2xl font-serif text-white mt-1">{{ $metrics['pending_organizers'] ?? 0 }} identities</span>
                        </div>
                        <a href="{{ route('admin.organizers.pending') }}" class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </a>
                    </div>
                    <div class="flex items-center justify-between p-6 bg-white/5 rounded-3xl border border-white/5">
                        <div>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Showcase Advertising</span>
                            @php $pendingPromos = \App\Models\EventPromotion::where('status', 'pending')->count(); @endphp
                            <span class="block text-2xl font-serif text-white mt-1">{{ $pendingPromos }} request{{ $pendingPromos == 1 ? '' : 's' }}</span>
                        </div>
                        <a href="{{ route('admin.promotions.index') }}" class="w-12 h-12 rounded-2xl bg-[#4E7D5B] flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </a>
                    </div>
                    <div class="flex items-center justify-between p-6 bg-white/5 rounded-3xl border border-white/5">
                        <div>
                            <span class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Feedback Moderation</span>
                            <span class="block text-2xl font-serif text-white mt-1">Review flagged resonance</span>
                        </div>
                        <a href="{{ route('admin.reviews.index') }}" class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="relative z-10 mt-12 pt-8 border-t border-white/5 flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-slate-500">
                <i data-lucide="activity" class="w-3 h-3 text-primary animate-pulse"></i>
                Governance Node Active
            </div>
        </div>
    </div>

    <div class="grid gap-10 xl:grid-cols-[1.55fr_0.95fr] mb-16">
        <!-- High Resonance Organizers -->
        <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
            <div class="p-10 border-b border-slate-50 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-serif text-slate-900">Elite Host Hierarchy</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Organizers with highest resonance impact</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-[10px] font-black text-primary uppercase tracking-[0.2em] flex items-center gap-2">
                    Global Map <i data-lucide="chevron-right" class="w-3 h-3"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-cream/30">
                            <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Host Identity</th>
                            <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center">Nodes</th>
                            <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Energy Yield</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($metrics['top_organizers'] as $organizer)
                            <tr class="group hover:bg-cream/50 transition-colors duration-300">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black text-sm group-hover:scale-110 transition-transform">
                                            {{ substr($organizer->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 leading-tight">{{ $organizer->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $organizer->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <span class="text-sm font-bold text-slate-600">{{ $organizer->events_count ?? 0 }} Experiences</span>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <span class="text-sm font-bold text-slate-900">₹{{ number_format($organizer->revenue ?? 0) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-10 py-20 text-center text-slate-400 font-serif italic">Identity grid empty.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ecosystem Distribution -->
        <div class="premium-card bg-white border-slate-100 p-10 flex flex-col shadow-2xl shadow-primary/5">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-2xl font-serif text-slate-900">Archetype Map</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Ecosystem distribution by category</p>
                </div>
                <div class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest animate-pulse">LIVE FEED</div>
            </div>
            <div class="flex-1 min-h-[300px] flex items-center justify-center relative">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-8 pt-8 border-t border-slate-50">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-cream rounded-3xl text-center">
                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Primary Archetype</span>
                        <span class="block text-sm font-bold text-slate-900 mt-1">Conferences</span>
                    </div>
                    <div class="p-4 bg-cream rounded-3xl text-center">
                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Resonance Peak</span>
                        <span class="block text-sm font-bold text-slate-900 mt-1">Workshops</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('categoryChart').getContext('2d');
            const categories = @json($metrics['category_distribution']);
            const labels = categories.map(c => c.name);
            const data = categories.map(c => c.events_count);
            // Grounded Color Palette
            const colors = ['#4D7C0F', '#65A30D', '#84CC16', '#BEF264', '#1E293B', '#334155', '#475569'];

            if (labels.length === 0) {
                labels.push('Awaiting Data');
                data.push(1);
            }

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 15,
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 25,
                                color: '#64748b',
                                font: { family: "'Inter', sans-serif", size: 10, weight: 'bold' }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: "'DM Serif Display', serif", size: 14 },
                            bodyFont: { family: "'Inter', sans-serif", size: 12 },
                            padding: 15,
                            displayColors: false,
                            cornerRadius: 15
                        }
                    }
                }
            });
        });
    </script>
</x-admin-layout>
