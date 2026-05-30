import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { Users, Loader2, Search, QrCode } from 'lucide-react';

export default function GlobalAttendees() {
  const [attendees, setAttendees] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  useEffect(() => {
    async function fetchData() {
      try {
        const res = await api.get('/organizer/attendees');
        setAttendees(res.data.data);
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    }
    fetchData();
  }, []);

  const filtered = attendees.filter(a => 
    a.attendee_name.toLowerCase().includes(search.toLowerCase()) || 
    a.attendee_email.toLowerCase().includes(search.toLowerCase()) ||
    a.ticket_reference.toLowerCase().includes(search.toLowerCase()) ||
    a.event_name.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <SidebarLayout type="organizer">
      <div className="space-y-8 animate-slide-up text-left">
        <div className="pb-6 border-b border-slate-100 dark:border-slate-800">
          <span className="text-[9px] font-black text-[#4E7D5B] uppercase tracking-[0.2em] block mb-1">USER CONTROL</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Global Attendees Directory</h1>
          <p className="text-xs text-slate-400 mt-1">Audit ticket holders and physical presences across all your active blueprints.</p>
        </div>

        <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-6">
          <div className="flex flex-col sm:flex-row gap-4 justify-between">
            <div className="relative flex-1 max-w-md">
              <Search className="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
              <input 
                type="text" 
                placeholder="Search by name, email, event or reference..." 
                value={search}
                onChange={e => setSearch(e.target.value)}
                className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pl-10 pr-4 py-3 text-xs outline-none focus:border-[#4E7D5B]"
              />
            </div>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs whitespace-nowrap">
              <thead>
                <tr className="border-b border-slate-100 dark:border-slate-800 text-[10px] font-black uppercase tracking-widest text-slate-400">
                  <th className="pb-3 px-4">Attendee / Identity</th>
                  <th className="pb-3 px-4">Event Blueprint</th>
                  <th className="pb-3 px-4">Pass Type</th>
                  <th className="pb-3 px-4">Reference</th>
                  <th className="pb-3 px-4 text-right">Status</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-50 dark:divide-slate-800/50">
                {loading ? (
                  <tr>
                    <td colSpan="5" className="py-12 text-center">
                      <Loader2 className="w-6 h-6 animate-spin text-[#4E7D5B] mx-auto" />
                    </td>
                  </tr>
                ) : filtered.length === 0 ? (
                  <tr>
                    <td colSpan="5" className="py-12 text-center text-slate-400">
                      No attendees found.
                    </td>
                  </tr>
                ) : (
                  filtered.map((att, idx) => (
                    <tr key={idx} className="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                      <td className="py-4 px-4">
                        <div className="font-bold text-slate-900 dark:text-white">{att.attendee_name}</div>
                        <div className="text-[10px] text-slate-500 mt-0.5">{att.attendee_email}</div>
                      </td>
                      <td className="py-4 px-4 font-serif font-bold text-slate-700 dark:text-slate-300">
                        {att.event_name}
                      </td>
                      <td className="py-4 px-4">
                        <span className="bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded text-[10px] font-bold">
                          {att.ticket_type} (x{att.quantity})
                        </span>
                      </td>
                      <td className="py-4 px-4 font-mono font-bold text-slate-500">
                        {att.ticket_reference}
                      </td>
                      <td className="py-4 px-4 text-right">
                        {att.checked_in_at ? (
                          <span className="inline-flex items-center gap-1 text-[10px] font-black uppercase text-emerald-600 bg-emerald-100 px-2 py-1 rounded">
                            <QrCode className="w-3 h-3" /> Checked In
                          </span>
                        ) : (
                          <span className="inline-flex items-center gap-1 text-[10px] font-black uppercase text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">
                            Pending Scan
                          </span>
                        )}
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </SidebarLayout>
  );
}
