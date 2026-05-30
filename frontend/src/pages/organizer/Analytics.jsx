import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  BarChart3, 
  DollarSign, 
  Ticket, 
  Layers, 
  TrendingUp, 
  ArrowUpRight,
  TrendingDown,
  Sparkles,
  Calendar,
  Users
} from 'lucide-react';

export default function Analytics() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchAnalytics() {
      try {
        setLoading(true);
        const res = await api.get('/organizer/analytics');
        setData(res.data);
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    }
    fetchAnalytics();
  }, []);

  const chartRevenues = data?.chartData?.revenue || [0, 0, 0, 0, 0];
  const chartLabels = data?.chartData?.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
  const maxRevenue = Math.max(...chartRevenues, 100);
  const getY = (val) => 200 - ((val / maxRevenue) * 180);
  const points = chartRevenues.map((val, index) => ({ x: 50 + (index * 127.5), y: getY(val) }));
  const pathD = points.length > 0 ? `M ${points.map(p => `${p.x} ${p.y}`).join(' L ')}` : '';
  
  const curationScore = data?.resonance?.curationScore ?? 100;
  const checkInExecution = data?.resonance?.checkInExecution ?? 0;

  return (
    <SidebarLayout type="organizer">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="pb-6 border-b border-slate-100 dark:border-slate-800">
          <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">OPERATIONS AUDIT</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Organizer Stats</h1>
          <p className="text-xs text-slate-400 mt-1">Audit ticket volume metrics, proceeds logs, and platform performance coefficients.</p>
        </div>

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-450">Compiling ledger statistics...</span>
          </div>
        ) : data ? (
          <div className="space-y-8">
            
            {/* Stat Counters */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2rem] shadow-sm space-y-4">
                <div className="flex justify-between items-center">
                  <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Earnings Volume</span>
                  <div className="w-8 h-8 rounded-lg bg-[#4E7D5B]/10 flex items-center justify-center text-primary">
                    <DollarSign className="w-4.5 h-4.5" />
                  </div>
                </div>
                <div className="space-y-1">
                  <h3 className="text-3xl font-bold text-slate-900 dark:text-white">
                    ₹{data.totalRevenue.toLocaleString('en-IN')}
                  </h3>
                  <p className="text-[10px] text-emerald-600 font-bold uppercase tracking-wider flex items-center gap-1">
                    <TrendingUp className="w-3 h-3" />
                    <span>+14.2% Growth Coefficient</span>
                  </p>
                </div>
              </div>

              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2rem] shadow-sm space-y-4">
                <div className="flex justify-between items-center">
                  <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Passes Claimed</span>
                  <div className="w-8 h-8 rounded-lg bg-[#4E7D5B]/10 flex items-center justify-center text-primary">
                    <Ticket className="w-4.5 h-4.5" />
                  </div>
                </div>
                <div className="space-y-1">
                  <h3 className="text-3xl font-bold text-slate-900 dark:text-white">
                    {data.totalTickets}
                  </h3>
                  <p className="text-[10px] text-emerald-600 font-bold uppercase tracking-wider flex items-center gap-1">
                    <TrendingUp className="w-3 h-3" />
                    <span>+8.4% Registration Velocity</span>
                  </p>
                </div>
              </div>

              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2rem] shadow-sm space-y-4">
                <div className="flex justify-between items-center">
                  <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Attendance</span>
                  <div className="w-8 h-8 rounded-lg bg-[#4E7D5B]/10 flex items-center justify-center text-primary">
                    <Users className="w-4.5 h-4.5" />
                  </div>
                </div>
                <div className="space-y-1">
                  <h3 className="text-3xl font-bold text-slate-900 dark:text-white">
                    {data.totalAttendance || 0}
                  </h3>
                  <p className="text-[10px] text-emerald-600 font-bold uppercase tracking-wider flex items-center gap-1">
                    <Sparkles className="w-3 h-3" />
                    <span>Checked-In Guests</span>
                  </p>
                </div>
              </div>

              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2rem] shadow-sm space-y-4">
                <div className="flex justify-between items-center">
                  <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Active Gatherings</span>
                  <div className="w-8 h-8 rounded-lg bg-[#4E7D5B]/10 flex items-center justify-center text-primary">
                    <Layers className="w-4.5 h-4.5" />
                  </div>
                </div>
                <div className="space-y-1">
                  <h3 className="text-3xl font-bold text-slate-900 dark:text-white">
                    {data.activeEvents}
                  </h3>
                  <p className="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                    Blueprints deployed in marketplace
                  </p>
                </div>
              </div>
            </div>

            {/* Performance charts */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
              
              {/* Earnings Timeline chart */}
              <div className="lg:col-span-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm space-y-6">
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold">Earnings Timeline</h3>
                  <p className="text-xs text-slate-400 mt-1">Procedural earnings mapping across timeline segments.</p>
                </div>

                {/* Customized SVG Chart */}
                <div className="relative pt-6 h-[260px] w-full">
                  <svg className="w-full h-full" viewBox="0 0 600 220">
                    {/* Grid lines */}
                    <line x1="50" y1="20" x2="560" y2="20" stroke="#e2e8f0" strokeWidth="1" className="dark:stroke-slate-800" strokeDasharray="4 4" />
                    <line x1="50" y1="80" x2="560" y2="80" stroke="#e2e8f0" strokeWidth="1" className="dark:stroke-slate-800" strokeDasharray="4 4" />
                    <line x1="50" y1="140" x2="560" y2="140" stroke="#e2e8f0" strokeWidth="1" className="dark:stroke-slate-800" strokeDasharray="4 4" />
                    <line x1="50" y1="200" x2="560" y2="200" stroke="#cbd5e1" strokeWidth="1.5" className="dark:stroke-slate-700" />

                    {/* Chart path line */}
                    <path 
                      d={pathD} 
                      fill="none" 
                      stroke="#4E7D5B" 
                      strokeWidth="3.5" 
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    />

                    {/* Glowing dots */}
                    {points.map((p, i) => (
                      <circle key={i} cx={p.x} cy={p.y} r="5" fill="#4E7D5B" stroke="#ffffff" strokeWidth="2" className="dark:stroke-slate-900" />
                    ))}

                    {/* X axis labels */}
                    {chartLabels.map((label, i) => (
                      <text key={i} x={50 + (i * 127.5)} y="218" textAnchor="middle" fontSize="10" fontWeight="bold" fill="#94a3b8">{label.toUpperCase()}</text>
                    ))}

                    {/* Y axis labels */}
                    <text x="40" y="25" textAnchor="end" fontSize="10" fontWeight="bold" fill="#94a3b8">₹{maxRevenue >= 1000 ? (maxRevenue/1000).toFixed(1) + 'k' : maxRevenue}</text>
                    <text x="40" y="85" textAnchor="end" fontSize="10" fontWeight="bold" fill="#94a3b8">₹{Math.round(maxRevenue * 0.66) >= 1000 ? (Math.round(maxRevenue * 0.66)/1000).toFixed(1) + 'k' : Math.round(maxRevenue * 0.66)}</text>
                    <text x="40" y="145" textAnchor="end" fontSize="10" fontWeight="bold" fill="#94a3b8">₹{Math.round(maxRevenue * 0.33) >= 1000 ? (Math.round(maxRevenue * 0.33)/1000).toFixed(1) + 'k' : Math.round(maxRevenue * 0.33)}</text>
                    <text x="40" y="204" textAnchor="end" fontSize="10" fontWeight="bold" fill="#94a3b8">₹0</text>
                  </svg>
                </div>
              </div>

              {/* Operations quality metrics */}
              <div className="lg:col-span-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm flex flex-col justify-between space-y-6">
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold">Resonance Profile</h3>
                  <p className="text-xs text-slate-400 mt-1">Platform curation coefficients.</p>
                </div>

                <div className="space-y-4 flex-1 flex flex-col justify-center">
                  <div className="space-y-2">
                    <div className="flex justify-between text-xs font-bold uppercase tracking-wider text-slate-500">
                      <span>Curation score</span>
                      <span className="text-[#4E7D5B]">{curationScore}% {curationScore >= 80 ? 'Excellent' : curationScore >= 50 ? 'Good' : 'Needs Work'}</span>
                    </div>
                    <div className="h-2 w-full bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-full overflow-hidden">
                      <div className="h-full bg-[#4E7D5B] rounded-full transition-all duration-1000" style={{ width: `${curationScore}%` }}></div>
                    </div>
                  </div>

                  <div className="space-y-2">
                    <div className="flex justify-between text-xs font-bold uppercase tracking-wider text-slate-500">
                      <span>Check-In execution</span>
                      <span className="text-[#4E7D5B]">{checkInExecution}% Complete</span>
                    </div>
                    <div className="h-2 w-full bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-full overflow-hidden">
                      <div className="h-full bg-[#4E7D5B] rounded-full transition-all duration-1000" style={{ width: `${checkInExecution}%` }}></div>
                    </div>
                  </div>
                </div>

                <div className="pt-4 border-t border-slate-50 dark:border-slate-850 flex items-center justify-between text-xs text-slate-450 leading-normal">
                  <span>Audit Coefficient</span>
                  <span className="font-bold text-slate-800 dark:text-slate-200">1.025 R_C</span>
                </div>
              </div>
            </div>

          </div>
        ) : null}

      </div>
    </SidebarLayout>
  );
}
