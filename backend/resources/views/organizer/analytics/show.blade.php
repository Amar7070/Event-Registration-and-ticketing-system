<x-organizer-layout>
    <x-slot name="header">
        Experience Analytics
    </x-slot>

    <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
        <div>
            <h2 class="text-3xl font-serif text-slate-900 mb-2">{{ $event->title }}</h2>
            <p class="text-slate-500 font-serif italic text-sm">"Monitoring the intentional resonance and collective density of this node."</p>
        </div>
        <div class="px-5 py-2.5 bg-white border border-slate-100 rounded-full shadow-sm flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Real-time Node Pulse</span>
        </div>
    </div>

    <!-- Resonance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
        <div class="premium-card p-10 bg-white border-slate-100 group">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="zap" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">ENERGY YIELD</span>
            </div>
            <div class="text-4xl font-serif text-slate-900 leading-none">₹{{ number_format($metrics['total_revenue']) }}</div>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-4">GROSS RESONANCE VALUE</p>
        </div>

        <div class="premium-card p-10 bg-white border-slate-100 group relative overflow-hidden">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">NODES OCCUPIED</span>
            </div>
            <div class="text-4xl font-serif text-slate-900 leading-none">{{ $metrics['tickets_sold'] }} <span class="text-lg text-slate-300">/ {{ $event->total_capacity }}</span></div>
            <div class="mt-6 w-full bg-slate-50 rounded-full h-1.5 overflow-hidden">
                <div class="bg-primary h-full transition-all duration-1000 ease-out" style="width: {{ $metrics['capacity_usage'] }}%"></div>
            </div>
        </div>

        <div class="premium-card p-10 bg-white border-slate-100 group">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">RESONANCE RATE</span>
            </div>
            <div class="text-4xl font-serif text-slate-900 leading-none">{{ number_format($metrics['attendance_rate'], 1) }}%</div>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-4">IDENTITY VERIFICATION FLOW</p>
        </div>

        <div class="premium-card p-10 bg-[#1E293B] text-white group">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-rose-400 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="rotate-ccw" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">ENERGY REVERSALS</span>
            </div>
            <div class="text-4xl font-serif text-white leading-none">₹{{ number_format($metrics['refunds_total']) }}</div>
            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-4">PLATFORM DEDUCTIONS</p>
        </div>
    </div>

    <!-- Data Visualization Matrix -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Sales Velocity -->
        <div class="lg:col-span-2 premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-2xl font-serif text-slate-900">Temporal Sales Velocity</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Daily Resonance Acquisition (30-Day Cycle)</p>
                </div>
            </div>
            <div class="relative h-96">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Density Distribution -->
        <div class="premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5 flex flex-col">
            <div class="mb-10">
                <h3 class="text-2xl font-serif text-slate-900">Energy Distribution</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Yield analysis by ticket archetype</p>
            </div>
            <div class="relative h-64 flex justify-center items-center">
                <canvas id="revenueTypeChart"></canvas>
            </div>
            <div class="mt-10 space-y-4 flex-1" id="revenue-legend">
                <!-- Legend populated by JS -->
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($metrics);
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            
            const dates = chartData.daily_sales.map(item => {
                const d = new Date(item.date);
                return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            });
            const counts = chartData.daily_sales.map(item => item.count);
            
            if(dates.length === 0) {
                dates.push('Awaiting Data');
                counts.push(0);
            }

            const gradient = salesCtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(77, 124, 15, 0.2)'); // Sage-700
            gradient.addColorStop(1, 'rgba(77, 124, 15, 0.0)');

            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Nodes Acquired',
                        data: counts,
                        borderColor: '#4D7C0F',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4D7C0F',
                        pointBorderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1E293B',
                            titleFont: { family: "'Inter', sans-serif", size: 10, weight: 'bold' },
                            bodyFont: { family: "'DM Serif Display', serif", size: 16 },
                            padding: 15,
                            displayColors: false,
                            cornerRadius: 15,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Resident Nodes';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f8fafc', drawBorder: false },
                            ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8', padding: 10 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8', maxTicksLimit: 7, padding: 10 }
                        }
                    }
                }
            });

            // Distribution Chart
            const revCtx = document.getElementById('revenueTypeChart').getContext('2d');
            const types = chartData.revenue_by_type.map(item => item.name);
            const revenues = chartData.revenue_by_type.map(item => item.tickets_sum_total_amount || 0);
            
            const brandColors = ['#4D7C0F', '#65A30D', '#84CC16', '#BEF264', '#1E293B', '#334155'];
            
            if(types.length === 0) {
                types.push('Archetype Pending');
                revenues.push(1);
            }

            new Chart(revCtx, {
                type: 'doughnut',
                data: {
                    labels: types,
                    datasets: [{
                        data: revenues,
                        backgroundColor: brandColors,
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
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1E293B',
                            padding: 15,
                            cornerRadius: 15,
                            titleFont: { family: "'Inter', sans-serif", size: 10 },
                            bodyFont: { family: "'DM Serif Display', serif", size: 16 },
                            callbacks: {
                                label: function(context) {
                                    let val = chartData.revenue_by_type.length ? context.parsed : 0;
                                    return ' ₹' + Number(val).toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Custom Legend
            const legendContainer = document.getElementById('revenue-legend');
            let legendHTML = '';
            chartData.revenue_by_type.forEach((item, index) => {
                const color = brandColors[index % brandColors.length];
                const value = item.tickets_sum_total_amount || 0;
                legendHTML += `
                    <div class="flex justify-between items-center p-4 bg-cream/50 rounded-2xl border border-slate-50 group hover:bg-white hover:shadow-lg hover:shadow-primary/5 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full shadow-sm" style="background-color: ${color}"></div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">${item.name}</span>
                        </div>
                        <span class="text-sm font-bold text-slate-900">₹${Number(value).toLocaleString()}</span>
                    </div>
                `;
            });
            legendContainer.innerHTML = legendHTML;
        });
    </script>
</x-organizer-layout>
