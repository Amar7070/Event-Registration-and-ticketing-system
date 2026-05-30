import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import SidebarLayout from '../components/SidebarLayout';
import api from '../services/api';
import { 
  Ticket, 
  Calendar, 
  MapPin, 
  Search, 
  ChevronLeft, 
  ChevronRight,
  Compass
} from 'lucide-react';

export default function MyTickets() {
  const navigate = useNavigate();
  const [tickets, setTickets] = useState([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [search, setSearch] = useState('');

  useEffect(() => {
    async function fetchTickets() {
      try {
        setLoading(true);
        const res = await api.get(`/my-tickets?page=${page}`);
        setTickets(res.data.data || []);
        // Setup pagination if returned in meta/links
        if (res.data.meta) {
          setTotalPages(res.data.meta.last_page || 1);
        } else {
          setTotalPages(1);
        }
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    }
    fetchTickets();
  }, [page]);

  const filteredTickets = tickets.filter(t => 
    t.event.title.toLowerCase().includes(search.toLowerCase()) ||
    t.booking_reference.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <SidebarLayout type="user">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">RESERVATION VAULT</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Active Passes</h1>
          </div>

          <div className="relative flex items-center bg-white dark:bg-slate-900 rounded-full border border-slate-100 dark:border-slate-800 px-4 py-1 max-w-xs w-full shadow-sm">
            <Search className="w-4 h-4 text-slate-400 shrink-0" />
            <input 
              type="text" 
              placeholder="Search reference, events..." 
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="flex-1 bg-transparent border-none text-xs py-2.5 px-2.5 placeholder:text-slate-400 text-slate-800 dark:text-slate-205 outline-none"
            />
          </div>
        </div>

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-450">Synchronizing Vault...</span>
          </div>
        ) : filteredTickets.length > 0 ? (
          <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {filteredTickets.map((ticket) => {
                const isConfirmed = ticket.status === 'confirmed';
                const isCancelled = ticket.status === 'cancelled';
                return (
                  <Link 
                    key={ticket.id}
                    to={`/my-tickets/${ticket.booking_reference}`}
                    className="premium-card bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850 rounded-[2rem] overflow-hidden hover:shadow-lg flex flex-col justify-between transition-all duration-300 group"
                  >
                    {/* Top ticket strip bar */}
                    <div className={`h-2.5 w-full shadow-inner ${
                      isConfirmed ? 'bg-primary' : isCancelled ? 'bg-rose-500' : 'bg-amber-500'
                    }`}></div>

                    <div className="p-6 space-y-4 flex-1 flex flex-col justify-between">
                      <div className="space-y-2">
                        <div className="flex justify-between items-start gap-2">
                          <span className="font-mono text-[9px] font-black text-slate-400 group-hover:text-primary transition-colors">
                            #{ticket.booking_reference}
                          </span>
                          <span className={`px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider ${
                            isConfirmed 
                              ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20' 
                              : isCancelled 
                              ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/20' 
                              : 'bg-amber-50 text-amber-700 dark:bg-amber-950/20'
                          }`}>
                            {ticket.status}
                          </span>
                        </div>

                        <h3 className="font-serif text-base text-slate-850 dark:text-slate-100 font-bold group-hover:text-primary transition-colors line-clamp-2">
                          {ticket.event.title}
                        </h3>
                      </div>

                      <div className="pt-4 border-t border-dashed border-slate-100 dark:border-slate-800 space-y-2">
                        <div className="flex items-center gap-2 text-xs text-slate-500">
                          <Calendar className="w-4 h-4 text-primary shrink-0" />
                          <span>
                            {new Date(ticket.event.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                          </span>
                        </div>
                        <div className="flex justify-between items-center text-xs">
                          <span className="text-slate-400 font-medium">{ticket.type} (x{ticket.quantity})</span>
                          <span className="font-bold text-slate-855 dark:text-slate-200">
                            {ticket.total_price == 0 ? 'Free' : `₹${ticket.total_price}`}
                          </span>
                        </div>
                      </div>
                    </div>
                  </Link>
                );
              })}
            </div>

            {/* Pagination Controls */}
            {totalPages > 1 && (
              <div className="flex items-center justify-center gap-4 pt-6">
                <button 
                  onClick={() => setPage(p => Math.max(1, p - 1))}
                  disabled={page === 1}
                  className="p-2 border border-slate-100 dark:border-slate-800 rounded-xl hover:text-primary transition-colors disabled:opacity-30 cursor-pointer"
                >
                  <ChevronLeft className="w-4 h-4" />
                </button>
                <span className="text-xs font-black uppercase tracking-widest text-slate-500 select-none">
                  Page {page} of {totalPages}
                </span>
                <button 
                  onClick={() => setPage(p => Math.min(totalPages, p + 1))}
                  disabled={page === totalPages}
                  className="p-2 border border-slate-100 dark:border-slate-800 rounded-xl hover:text-primary transition-colors disabled:opacity-30 cursor-pointer"
                >
                  <ChevronRight className="w-4 h-4" />
                </button>
              </div>
            )}
          </div>
        ) : (
          <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl max-w-md mx-auto">
            <Compass className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No active tickets</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              You haven't registered for any ecosystem gatherings yet. Explore directory events!
            </p>
            <Link to="/events" className="btn-primary mt-6 px-6 py-2.5 text-[10px] font-black uppercase tracking-wider">
              Browse Experiences
            </Link>
          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
