import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  ShieldAlert, 
  Users, 
  Layers, 
  DollarSign, 
  Percent, 
  Ticket,
  ChevronRight
} from 'lucide-react';

export default function Dashboard() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchDashboard() {
      try {
        setLoading(true);
        const res = await api.get('/admin/dashboard');
        setData(res.data);
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    }
    fetchDashboard();
  }, []);

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="pb-6 border-b border-slate-100 dark:border-slate-800">
          <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">PLATFORM CONTROL ROOM</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Ecosystem Governance</h1>
          <p className="text-xs text-slate-400 mt-1">Audit platform-wide transaction registries, active licenses, and content restrictions.</p>
        </div>

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-455">Retrieving system ledger...</span>
          </div>
        ) : data ? (
          <div className="space-y-8">
            
            {/* Platform metrics */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              <div className="bg-white dark:bg-slate-900 border border-slate-105 dark:border-slate-800/80 p-6 rounded-3xl shadow-sm space-y-3">
                <div className="flex justify-between items-center text-slate-400">
                  <span className="text-[8px] font-black uppercase tracking-widest">Total Accounts</span>
                  <Users className="w-4 h-4 text-rose-550" />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-slate-900 dark:text-white">{data.total_users}</h3>
                  <p className="text-[9px] text-slate-400 mt-1 uppercase font-bold">Registered Nodes</p>
                </div>
              </div>

              <div className="bg-white dark:bg-slate-900 border border-slate-105 dark:border-slate-800/80 p-6 rounded-3xl shadow-sm space-y-3">
                <div className="flex justify-between items-center text-slate-400">
                  <span className="text-[8px] font-black uppercase tracking-widest">Marketplace Events</span>
                  <Layers className="w-4 h-4 text-rose-550" />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-slate-900 dark:text-white">{data.total_events}</h3>
                  <p className="text-[9px] text-slate-400 mt-1 uppercase font-bold">Blueprints Deployed</p>
                </div>
              </div>

              <div className="bg-white dark:bg-slate-900 border border-slate-105 dark:border-slate-800/80 p-6 rounded-3xl shadow-sm space-y-3">
                <div className="flex justify-between items-center text-slate-400">
                  <span className="text-[8px] font-black uppercase tracking-widest">Gross proceeds</span>
                  <DollarSign className="w-4 h-4 text-rose-550" />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-slate-900 dark:text-white">
                    ₹{(data.total_revenue || 0).toLocaleString('en-IN')}
                  </h3>
                  <p className="text-[9px] text-slate-400 mt-1 uppercase font-bold">Total volume transacted</p>
                </div>
              </div>

              <div className="bg-white dark:bg-slate-900 border border-slate-105 dark:border-slate-800/80 p-6 rounded-3xl shadow-sm space-y-3">
                <div className="flex justify-between items-center text-slate-400">
                  <span className="text-[8px] font-black uppercase tracking-widest">Platform Fees</span>
                  <Percent className="w-4 h-4 text-rose-550" />
                </div>
                <div>
                  <h3 className="text-2xl font-bold text-slate-900 dark:text-white">
                    ₹{(data.total_platform_fees || 0).toLocaleString('en-IN')}
                  </h3>
                  <p className="text-[9px] text-slate-400 mt-1 uppercase font-bold">Ecosystem commissions</p>
                </div>
              </div>
            </div>

            {/* Split statistics */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
              
              {/* Left: Top organizers */}
              <div className="lg:col-span-7 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2.5rem] shadow-sm space-y-6">
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold">Top Curation Architects</h3>
                  <p className="text-xs text-slate-400 mt-1">Host entities with the highest gross transaction volumes.</p>
                </div>

                <div className="space-y-4">
                  {data.top_organizers && data.top_organizers.length > 0 ? (
                    data.top_organizers.map((org, index) => (
                      <div key={org.id} className="flex justify-between items-center p-3.5 bg-slate-50 dark:bg-slate-950/20 border border-slate-105 dark:border-slate-800 rounded-2xl text-xs">
                        <div className="flex items-center gap-3">
                          <span className="font-serif font-black text-rose-500 w-5">#{index + 1}</span>
                          <div>
                            <div className="font-bold text-slate-850 dark:text-slate-105">{org.name}</div>
                            <div className="text-[10px] text-slate-400 mt-0.5">{org.email}</div>
                          </div>
                        </div>
                        <span className="font-bold text-slate-800 dark:text-white">
                          ₹{(org.revenue || 0).toLocaleString('en-IN')}
                        </span>
                      </div>
                    ))
                  ) : (
                    <div className="py-8 text-center text-slate-400 text-xs">
                      No transaction summaries logged.
                    </div>
                  )}
                </div>
              </div>

              {/* Right: Categories distribution */}
              <div className="lg:col-span-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2.5rem] shadow-sm space-y-6 flex flex-col justify-between">
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold">Domain Distribution</h3>
                  <p className="text-xs text-slate-400 mt-1">Classification breakdown of deployed event blueprints.</p>
                </div>

                <div className="space-y-4 flex-1 flex flex-col justify-center">
                  {data.category_distribution && data.category_distribution.length > 0 ? (
                    data.category_distribution.map((cat) => (
                      <div key={cat.id} className="space-y-2">
                        <div className="flex justify-between text-xs font-bold text-slate-500">
                          <span>{cat.name}</span>
                          <span>{cat.events_count} Deployed</span>
                        </div>
                        <div className="h-2 w-full bg-slate-50 dark:bg-slate-950 rounded-full overflow-hidden border border-slate-100 dark:border-slate-800">
                          <div 
                            className="h-full bg-rose-500 rounded-full" 
                            style={{ 
                              width: `${data.total_events > 0 ? (cat.events_count / data.total_events) * 100 : 0}%` 
                            }}
                          ></div>
                        </div>
                      </div>
                    ))
                  ) : (
                    <div className="text-center text-slate-400 text-xs">
                      No categories created.
                    </div>
                  )}
                </div>
              </div>

            </div>

          </div>
        ) : null}

      </div>
    </SidebarLayout>
  );
}
