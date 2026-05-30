import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  FolderLock, 
  Search, 
  AlertTriangle, 
  CheckCircle2, 
  Calendar,
  X,
  ShieldCheck
} from 'lucide-react';

export default function Events() {
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [search, setSearch] = useState('');

  // Restrict modal states
  const [selectedEvent, setSelectedEvent] = useState(null);
  const [restrictReason, setRestrictReason] = useState('Violation of Content Policies');
  const [restrictModalOpen, setRestrictModalOpen] = useState(false);
  const [restricting, setRestricting] = useState(false);

  useEffect(() => {
    fetchEvents();
  }, [page]);

  const fetchEvents = async () => {
    try {
      setLoading(true);
      const res = await api.get(`/admin/events?page=${page}`);
      setEvents(res.data.events.data || []);
      if (res.data.events.last_page) {
        setTotalPages(res.data.events.last_page);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleOpenRestrictModal = (evt) => {
    setSelectedEvent(evt);
    setRestrictReason('Violation of Content Policies');
    setRestrictModalOpen(true);
  };

  const handleToggleRestriction = async (e) => {
    e.preventDefault();
    if (!selectedEvent) return;

    setRestricting(true);
    try {
      const res = await api.post(`/admin/events/${selectedEvent.id}/restrict`, {
        restriction_reason: restrictReason
      });
      alert(res.data.message);
      setRestrictModalOpen(false);
      fetchEvents();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to update event restriction.');
    } finally {
      setRestricting(false);
    }
  };

  const handleLiftRestriction = async (evt) => {
    if (!window.confirm(`Lift all platform restrictions on "${evt.title}"?`)) return;
    try {
      const res = await api.post(`/admin/events/${evt.id}/restrict`);
      alert(res.data.message);
      fetchEvents();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to lift restriction.');
    }
  };

  const filteredEvents = events.filter(e => 
    e.title.toLowerCase().includes(search.toLowerCase()) ||
    (e.organizer?.name || '').toLowerCase().includes(search.toLowerCase())
  );

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">CONTENT GOVERNANCE</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Events Moderation</h1>
            <p className="text-xs text-slate-400 mt-1">Audit active event blueprints, enforce restrictions, or lift dispute blockades.</p>
          </div>

          <div className="relative flex items-center bg-white dark:bg-slate-900 rounded-full border border-slate-100 dark:border-slate-800 px-4 py-1.5 shadow-sm max-w-xs w-full">
            <Search className="w-4 h-4 text-slate-400 shrink-0" />
            <input 
              type="text" 
              placeholder="Search title, organizer..." 
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="flex-1 bg-transparent border-none text-xs py-2 px-2.5 placeholder:text-slate-400 text-slate-850 dark:text-slate-205 outline-none"
            />
          </div>
        </div>

        {/* Events listing */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-455">Syncing event templates...</span>
          </div>
        ) : filteredEvents.length > 0 ? (
          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
            <div className="overflow-x-auto">
              <table className="w-full text-xs text-left">
                <thead className="bg-slate-50 dark:bg-slate-950/20 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                  <tr>
                    <th className="px-6 py-4.5">Event details</th>
                    <th className="px-6 py-4.5">Organizer (Architect)</th>
                    <th className="px-6 py-4.5">Restriction Status</th>
                    <th className="px-6 py-4.5">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                  {filteredEvents.map((evt) => {
                    const isRestricted = !!evt.is_restricted;
                    return (
                      <tr key={evt.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-colors">
                        <td className="px-6 py-5">
                          <div className="font-bold text-slate-850 dark:text-white text-sm line-clamp-1">{evt.title}</div>
                          <div className="flex items-center gap-1.5 text-[9px] text-slate-450 uppercase tracking-widest mt-1.5">
                            <span>{evt.category?.name}</span>
                            <span>&bull;</span>
                            <span>{new Date(evt.start_date).toLocaleDateString()}</span>
                          </div>
                        </td>
                        <td className="px-6 py-5">
                          <div className="font-bold text-slate-800 dark:text-slate-200">{evt.organizer?.name}</div>
                          <div className="text-[10px] text-slate-400 mt-0.5">{evt.organizer?.email}</div>
                        </td>
                        <td className="px-6 py-5">
                          <span className={`px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider ${
                            isRestricted 
                              ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/20' 
                              : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20'
                          }`}>
                            {isRestricted ? 'Restricted' : 'Active'}
                          </span>
                          {isRestricted && evt.restriction_reason && (
                            <p className="text-[9px] text-rose-500 mt-1.5 italic max-w-xs">Reason: {evt.restriction_reason}</p>
                          )}
                        </td>
                        <td className="px-6 py-5">
                          {isRestricted ? (
                            <button 
                              onClick={() => handleLiftRestriction(evt)}
                              className="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer border border-emerald-100"
                            >
                              <ShieldCheck className="w-3.5 h-3.5" />
                              <span>Lift Blockade</span>
                            </button>
                          ) : (
                            <button 
                              onClick={() => handleOpenRestrictModal(evt)}
                              className="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer border border-rose-100"
                            >
                              <FolderLock className="w-3.5 h-3.5" />
                              <span>Restrict Event</span>
                            </button>
                          )}
                        </td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>
          </div>
        ) : (
          <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl max-w-md mx-auto">
            <FolderLock className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No events found</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              No marketplace blueprints match the query filters.
            </p>
          </div>
        )}

        {/* Restrict Modal */}
        {restrictModalOpen && selectedEvent && (
          <div className="fixed inset-0 z-[120] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all">
            <div className="relative w-full max-w-md bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-3xl p-8 overflow-hidden">
              
              <div className="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div className="flex items-center gap-3 text-rose-500">
                  <FolderLock className="w-5 h-5" />
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold leading-tight">Restrict Event Node</h3>
                </div>
                <button onClick={() => setRestrictModalOpen(false)} className="text-slate-400 hover:text-slate-650 transition-colors cursor-pointer">
                  <X className="w-5 h-5" />
                </button>
              </div>

              <form onSubmit={handleToggleRestriction} className="space-y-4 text-left">
                <p className="text-xs text-slate-400 leading-normal">
                  Restricting this event will immediately hide its details page from search directories and suspend all active checkouts.
                </p>

                <div>
                  <label className="block text-[9px] font-black text-slate-450 uppercase tracking-widest mb-1.5 font-bold">Restriction Audit Reason</label>
                  <textarea 
                    rows="4" 
                    required
                    value={restrictReason}
                    onChange={(e) => setRestrictReason(e.target.value)}
                    placeholder="Provide details on content violation, fraudulent activity, or safety concerns..."
                    className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-105 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                  ></textarea>
                </div>

                <div className="pt-4 flex gap-4">
                  <button 
                    type="button" 
                    onClick={() => setRestrictModalOpen(false)} 
                    className="flex-1 py-3.5 border border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-600 hover:border-slate-300 transition-all cursor-pointer bg-white"
                  >
                    Cancel
                  </button>
                  <button 
                    type="submit" 
                    disabled={restricting}
                    className="flex-1 py-3.5 bg-rose-500 text-white text-[10px] font-black uppercase tracking-wider hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/20 cursor-pointer disabled:opacity-50"
                  >
                    {restricting ? 'Restricting...' : 'Enforce Restriction'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
