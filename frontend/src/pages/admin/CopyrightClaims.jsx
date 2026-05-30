import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  ShieldAlert, 
  Search, 
  CheckCircle,
  XCircle,
  Clock
} from 'lucide-react';

export default function CopyrightClaims() {
  const [claims, setClaims] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  useEffect(() => {
    fetchClaims();
  }, []);

  const fetchClaims = async () => {
    try {
      setLoading(true);
      const res = await api.get('/admin/claims');
      setClaims(res.data || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleUpdateStatus = async (reportId, status) => {
    if (!window.confirm(`Update claim status to ${status}?`)) return;

    try {
      const res = await api.put(`/admin/claims/${reportId}`, { status });
      alert(res.data.message);
      fetchClaims();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to update claim status.');
    }
  };

  const handleRestrictEvent = async (eventId) => {
    const reason = window.prompt("Enter restriction reason (e.g., Copyright Violation):", "Violation of Copyright / Illegal Content Terms");
    if (!reason) return;

    try {
      const res = await api.post(`/admin/events/${eventId}/restrict`, { restriction_reason: reason });
      alert(res.data.message);
      fetchClaims();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to restrict event.');
    }
  };

  const filteredClaims = claims.filter(c => {
    const matchesSearch = 
      (c.event?.title || '').toLowerCase().includes(search.toLowerCase()) ||
      (c.subject || '').toLowerCase().includes(search.toLowerCase());

    return matchesSearch;
  });

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">LEGAL & COMPLIANCE</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Copyright Claims</h1>
            <p className="text-xs text-slate-400 mt-1">Review and manage duplicate event flags reported by organizers.</p>
          </div>

          <div className="flex flex-wrap items-center gap-3">
            <div className="relative flex items-center bg-white dark:bg-slate-900 rounded-full border border-slate-100 dark:border-slate-800 px-4 py-1.5 shadow-sm max-w-xs">
              <Search className="w-4 h-4 text-slate-400 shrink-0" />
              <input 
                type="text" 
                placeholder="Search events..." 
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="flex-1 bg-transparent border-none text-xs py-2 px-2 placeholder:text-slate-400 text-slate-800 dark:text-slate-205 outline-none"
              />
            </div>
          </div>
        </div>

        {/* Claims Table */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-455">Retrieving claims...</span>
          </div>
        ) : filteredClaims.length > 0 ? (
          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
            <div className="overflow-x-auto">
              <table className="w-full text-xs text-left">
                <thead className="bg-slate-50 dark:bg-slate-950/20 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                  <tr>
                    <th className="px-6 py-4.5">Target Event</th>
                    <th className="px-6 py-4.5">Claimed By</th>
                    <th className="px-6 py-4.5">Details</th>
                    <th className="px-6 py-4.5">Status</th>
                    <th className="px-6 py-4.5">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                  {filteredClaims.map((c) => (
                    <tr key={c.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-colors">
                      <td className="px-6 py-5">
                        <div className="font-bold text-slate-850 dark:text-white text-sm">{c.event?.title || 'Unknown Event'}</div>
                        <div className="text-[10px] text-slate-400 mt-1">Event ID: {c.event_id}</div>
                      </td>
                      <td className="px-6 py-5">
                        <div className="font-bold text-slate-850 dark:text-white text-sm">{c.user?.name || 'Unknown User'}</div>
                        <div className="text-[10px] text-slate-400 mt-1">{new Date(c.created_at).toLocaleDateString()}</div>
                      </td>
                      <td className="px-6 py-5 max-w-xs truncate" title={c.description}>
                        {c.description}
                      </td>
                      <td className="px-6 py-5 uppercase tracking-wider text-[10px]">
                        <span className={`px-2.5 py-1 rounded-full font-black tracking-wider ${
                          c.status === 'resolved' 
                            ? 'bg-emerald-50 text-emerald-700' 
                            : c.status === 'dismissed' 
                            ? 'bg-slate-100 text-slate-600' 
                            : 'bg-amber-50 text-amber-700'
                        }`}>
                          {c.status}
                        </span>
                      </td>
                      <td className="px-6 py-5">
                        <div className="flex gap-2">
                          <button 
                            onClick={() => handleUpdateStatus(c.id, 'resolved')}
                            className="p-2 rounded-xl border border-emerald-100 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition-colors"
                            title="Resolve & Action Taken"
                          >
                            <CheckCircle className="w-4 h-4" />
                          </button>
                          <button 
                            onClick={() => handleUpdateStatus(c.id, 'dismissed')}
                            className="p-2 rounded-xl border border-slate-200 text-slate-600 bg-slate-50 hover:bg-slate-100 transition-colors"
                            title="Dismiss Claim"
                          >
                            <XCircle className="w-4 h-4" />
                          </button>
                          <button 
                            onClick={() => handleRestrictEvent(c.event_id)}
                            className="p-2 rounded-xl border border-rose-200 text-rose-600 bg-rose-50 hover:bg-rose-100 transition-colors"
                            title="Restrict Event"
                          >
                            <ShieldAlert className="w-4 h-4" />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        ) : (
          <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl max-w-md mx-auto">
            <ShieldAlert className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No claims found</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              There are no copyright claims matching your criteria.
            </p>
          </div>
        )}
      </div>
    </SidebarLayout>
  );
}
