import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { ShieldAlert, AlertCircle, CheckCircle, XCircle } from 'lucide-react';

export default function Copyright() {
  const [reports, setReports] = useState({ filed: [], received: [] });
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState('filed'); // 'filed' or 'received'

  useEffect(() => {
    fetchReports();
  }, []);

  const fetchReports = async () => {
    try {
      setLoading(true);
      const res = await api.get('/organizer/copyright');
      setReports(res.data.data || { filed: [], received: [] });
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const getStatusBadge = (status) => {
    if (status === 'resolved') {
      return <span className="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black tracking-widest uppercase">Resolved</span>;
    }
    if (status === 'dismissed') {
      return <span className="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black tracking-widest uppercase">Dismissed</span>;
    }
    return <span className="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black tracking-widest uppercase">Pending</span>;
  };

  const renderTable = (data, isFiled) => (
    <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
      <div className="overflow-x-auto">
        <table className="w-full text-xs text-left">
          <thead className="bg-slate-50 dark:bg-slate-950/20 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
            <tr>
              <th className="px-6 py-4.5">Target Event</th>
              <th className="px-6 py-4.5">{isFiled ? 'Reported Organizer' : 'Reported By'}</th>
              <th className="px-6 py-4.5">Date</th>
              <th className="px-6 py-4.5">Description</th>
              <th className="px-6 py-4.5">Status</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
            {data.length === 0 ? (
              <tr>
                <td colSpan="4" className="px-6 py-12 text-center text-slate-400">
                  No reports found.
                </td>
              </tr>
            ) : (
              data.map((r) => (
                <tr key={r.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-colors">
                  <td className="px-6 py-5">
                    <div className="font-bold text-slate-850 dark:text-white text-sm">{r.event_name}</div>
                    <div className="text-[10px] text-slate-400 mt-1">{r.subject}</div>
                  </td>
                  <td className="px-6 py-5">
                    <div className="font-bold text-slate-850 dark:text-white text-sm">{isFiled ? r.reported_organizer : r.reporter_name}</div>
                  </td>
                  <td className="px-6 py-5">
                    <div className="font-bold text-slate-850 dark:text-white text-sm">{r.created_at}</div>
                  </td>
                  <td className="px-6 py-5 max-w-xs truncate text-slate-500" title={r.description}>
                    {r.description}
                  </td>
                  <td className="px-6 py-5">
                    {getStatusBadge(r.status)}
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );

  return (
    <SidebarLayout type="organizer">
      <div className="space-y-8 animate-slide-up text-left">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">COMPLIANCE & LEGAL</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Copyright Audits</h1>
            <p className="text-xs text-slate-400 mt-1">Track reports you have filed and reports filed against your ecosystem nodes.</p>
          </div>
        </div>

        {/* Tab Navigation */}
        <div className="flex gap-4 border-b border-slate-100 dark:border-slate-800 pb-px">
          <button
            onClick={() => setActiveTab('filed')}
            className={`pb-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all ${
              activeTab === 'filed' 
                ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' 
                : 'border-transparent text-slate-400 hover:text-slate-600'
            }`}
          >
            Filed By Me ({reports.filed.length})
          </button>
          <button
            onClick={() => setActiveTab('received')}
            className={`pb-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all ${
              activeTab === 'received' 
                ? 'border-rose-500 text-rose-600 dark:text-rose-400' 
                : 'border-transparent text-slate-400 hover:text-slate-600'
            }`}
          >
            Filed Against Me ({reports.received.length})
          </button>
        </div>

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-455">Retrieving data...</span>
          </div>
        ) : (
          <div className="animate-fade-in">
            {activeTab === 'filed' ? renderTable(reports.filed, true) : renderTable(reports.received, false)}
          </div>
        )}
      </div>
    </SidebarLayout>
  );
}
