import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  ArrowLeft, 
  Users, 
  Search, 
  CheckCircle, 
  Clock, 
  SlidersHorizontal,
  Mail,
  UserCheck
} from 'lucide-react';

export default function Attendees() {
  const { id } = useParams();
  const [event, setEvent] = useState(null);
  const [attendees, setAttendees] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [tierFilter, setTierFilter] = useState('');

  useEffect(() => {
    async function loadData() {
      try {
        setLoading(true);
        // Load event
        const evtRes = await api.get(`/organizer/events/${id}`);
        setEvent(evtRes.data.event);

        // Load attendees
        const attRes = await api.get(`/organizer/events/${id}/attendees`);
        setAttendees(attRes.data.data || []);
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    }
    loadData();
  }, [id]);

  const checkedInCount = attendees.filter(a => !!a.checked_in_at).length;
  const checkedInPercent = attendees.length > 0 
    ? Math.round((checkedInCount / attendees.length) * 100) 
    : 0;

  const uniqueTiers = [...new Set(attendees.map(a => a.ticket_type))];

  const filteredAttendees = attendees.filter(a => {
    const matchesSearch = 
      a.attendee_name.toLowerCase().includes(search.toLowerCase()) ||
      a.attendee_email.toLowerCase().includes(search.toLowerCase()) ||
      a.ticket_reference.toLowerCase().includes(search.toLowerCase());
    
    const matchesTier = tierFilter === '' || a.ticket_type === tierFilter;
    
    return matchesSearch && matchesTier;
  });

  return (
    <SidebarLayout type="organizer">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Navigation back */}
        <div>
          <Link to="/organizer/events" className="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-450 hover:text-primary transition-colors">
            <ArrowLeft className="w-4 h-4" />
            Back to Blueprints
          </Link>
        </div>

        {/* Title bar */}
        <div className="pb-6 border-b border-slate-100 dark:border-slate-800">
          <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">PARTICIPANT TRACKING</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">
            Attendee Registry
          </h1>
          <p className="text-xs text-slate-400 mt-1">
            Event Node: <span className="font-bold text-slate-800 dark:text-slate-200">{event ? event.title : 'Loading...'}</span>
          </p>
        </div>

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-455">Syncing attendee records...</span>
          </div>
        ) : (
          <div className="space-y-8">
            
            {/* Admission Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm flex items-center gap-5">
                <div className="w-12 h-12 rounded-2xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                  <Users className="w-6 h-6" />
                </div>
                <div>
                  <span className="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Total Registered</span>
                  <span className="text-xl font-bold text-slate-805 dark:text-white">{attendees.length} Attendees</span>
                </div>
              </div>

              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm flex items-center gap-5">
                <div className="w-12 h-12 rounded-2xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                  <CheckCircle className="w-6 h-6" />
                </div>
                <div>
                  <span className="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Total Checked In</span>
                  <span className="text-xl font-bold text-slate-800 dark:text-white">{checkedInCount} checked in</span>
                </div>
              </div>

              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm flex items-center gap-5">
                <div className="w-12 h-12 rounded-2xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                  <UserCheck className="w-6 h-6" />
                </div>
                <div>
                  <span className="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Check-In Velocity</span>
                  <span className="text-xl font-bold text-slate-800 dark:text-white">{checkedInPercent}% Admission</span>
                </div>
              </div>
            </div>

            {/* Filter controls */}
            <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div className="relative flex items-center bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 px-4 max-w-md w-full">
                <Search className="w-4 h-4 text-slate-400 shrink-0" />
                <input 
                  type="text" 
                  placeholder="Search name, email, reference..." 
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  className="flex-1 bg-transparent border-none text-xs py-3 px-3 placeholder:text-slate-400 text-slate-800 dark:text-slate-200 outline-none"
                />
              </div>

              <div className="flex items-center gap-3">
                <SlidersHorizontal className="w-4 h-4 text-slate-400 shrink-0" />
                <select 
                  value={tierFilter}
                  onChange={(e) => setTierFilter(e.target.value)}
                  className="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-650 dark:text-slate-205 py-2.5 px-4 cursor-pointer outline-none"
                >
                  <option value="">All Ticket Tiers</option>
                  {uniqueTiers.map((tier) => (
                    <option key={tier} value={tier}>{tier}</option>
                  ))}
                </select>
              </div>
            </div>

            {/* Attendee grid */}
            {filteredAttendees.length > 0 ? (
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {filteredAttendees.map((a) => {
                  const isChecked = !!a.checked_in_at;
                  return (
                    <div 
                      key={a.ticket_reference}
                      className="bg-white dark:bg-slate-900 border border-slate-105 dark:border-slate-800/80 p-6 rounded-3xl relative shadow-sm hover:shadow-md transition-all flex flex-col justify-between"
                    >
                      <div className="space-y-3 text-left">
                        <div className="flex justify-between items-start gap-2">
                          <span className="font-mono text-[9px] font-black text-slate-400">
                            #{a.ticket_reference}
                          </span>
                          <span className={`px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider ${
                            isChecked 
                              ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20' 
                              : 'bg-slate-100 text-slate-500 dark:bg-slate-800'
                          }`}>
                            {isChecked ? 'Checked In' : 'Registered'}
                          </span>
                        </div>

                        <div>
                          <h4 className="font-bold text-slate-900 dark:text-white truncate">{a.attendee_name}</h4>
                          <p className="text-[10px] text-slate-450 dark:text-slate-400 truncate flex items-center gap-1 mt-0.5">
                            <Mail className="w-3 h-3 text-slate-400" />
                            {a.attendee_email}
                          </p>
                        </div>
                      </div>

                      <div className="pt-4 mt-4 border-t border-slate-50 dark:border-slate-850 flex justify-between items-center text-xs">
                        <span className="text-slate-400 font-medium">{a.ticket_type} (x{a.quantity})</span>
                        {isChecked && (
                          <span className="text-[10px] text-slate-400 font-black uppercase tracking-widest flex items-center gap-1">
                            <Clock className="w-3.5 h-3.5 text-primary" />
                            {new Date(a.checked_in_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                          </span>
                        )}
                      </div>
                    </div>
                  );
                })}
              </div>
            ) : (
              <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl max-w-md mx-auto">
                <Users className="w-10 h-10 text-slate-300 mx-auto mb-4" />
                <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No attendees found</span>
                <p className="text-[11px] text-slate-450 mt-1.5 max-w-[245px] mx-auto leading-relaxed">
                  No registered attendees match the search criteria or filter classification.
                </p>
              </div>
            )}

          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
