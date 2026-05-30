import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import SidebarLayout from '../components/SidebarLayout';
import api from '../services/api';
import { 
  Hourglass, 
  Calendar, 
  Ticket, 
  Bell, 
  ArrowRight,
  Compass
} from 'lucide-react';

export default function MyWaitlists() {
  const [waitlists, setWaitlists] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchWaitlists() {
      try {
        setLoading(true);
        const res = await api.get('/my-waitlists');
        setWaitlists(res.data.data || []);
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    }
    fetchWaitlists();
  }, []);

  return (
    <SidebarLayout type="user">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="pb-6 border-b border-slate-100 dark:border-slate-800">
          <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">REGISTRATION QUEUE</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Waitlist Queue</h1>
          <p className="text-xs text-slate-400 mt-1">Track your pending ticket requests and reservation availability triggers.</p>
        </div>

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-450">Syncing queue index...</span>
          </div>
        ) : waitlists.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {waitlists.map((entry) => {
              const isNotified = entry.status === 'notified';
              const isWaiting = entry.status === 'waiting';
              
              return (
                <div 
                  key={entry.id}
                  className={`premium-card bg-white dark:bg-slate-900 border rounded-[2rem] p-6 shadow-sm flex flex-col justify-between transition-all duration-300 relative overflow-hidden ${
                    isNotified ? 'border-amber-400 bg-amber-50/5' : 'border-slate-100 dark:border-slate-850'
                  }`}
                >
                  {isNotified && (
                    <div className="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full -translate-y-12 translate-x-12 blur-[30px] pointer-events-none"></div>
                  )}

                  <div className="space-y-4">
                    <div className="flex justify-between items-start gap-2">
                      <span className={`px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider ${
                        isNotified 
                          ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/20' 
                          : isWaiting 
                          ? 'bg-slate-105 text-slate-600 dark:bg-slate-800' 
                          : 'bg-red-50 text-red-700 dark:bg-red-950/20'
                      }`}>
                        {entry.status}
                      </span>
                      
                      {isWaiting && (
                        <div className="text-right">
                          <span className="text-[8px] font-black text-slate-400 uppercase tracking-widest block leading-none mb-0.5">POSITION</span>
                          <span className="font-serif text-lg font-bold text-primary">#{entry.position}</span>
                        </div>
                      )}
                    </div>

                    <div className="space-y-1.5">
                      <h3 className="font-serif text-base text-slate-850 dark:text-slate-100 font-bold leading-snug">
                        {entry.event.title}
                      </h3>
                      <div className="flex items-center gap-1.5 text-[9px] font-black text-slate-450 uppercase tracking-wider">
                        <span>{entry.ticket_type}</span>
                        <span>&bull;</span>
                        <span className="flex items-center gap-1">
                          <Calendar className="w-3 h-3" />
                          {new Date(entry.event.starts_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                        </span>
                      </div>
                    </div>

                    {isNotified && (
                      <div className="p-4 bg-amber-50/50 dark:bg-amber-950/10 border border-amber-100 dark:border-amber-900/50 rounded-2xl space-y-2">
                        <div className="flex items-center gap-2 text-amber-700 font-bold text-xs uppercase tracking-wider">
                          <Bell className="w-4 h-4 animate-bounce" />
                          <span>Spot Available!</span>
                        </div>
                        <p className="text-[10px] text-slate-500 leading-normal">
                          You have been offered a reservation slot! Claim this pass before it expires on:
                        </p>
                        <p className="text-xs font-mono font-bold text-amber-700">
                          {new Date(entry.expires_at).toLocaleString()}
                        </p>
                      </div>
                    )}
                  </div>

                  {isNotified && (
                    <div className="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800">
                      <Link 
                        to={`/checkout?event=${encodeURIComponent(entry.event.title.toLowerCase().replace(/ /g, '-'))}&ticket_type_id=${entry.ticket_type_id || ''}&quantity=1`}
                        className="w-full bg-amber-500 hover:bg-amber-600 text-white py-3 px-4 rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 transition-all cursor-pointer"
                      >
                        <span>Claim Reservation</span>
                        <ArrowRight className="w-4 h-4" />
                      </Link>
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        ) : (
          <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl max-w-md mx-auto">
            <Hourglass className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No waitlist entries</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              Your queue waitlist ledger is empty. Explore and join sold out experience queues.
            </p>
            <Link to="/events" className="btn-primary mt-6 px-6 py-2.5 text-[10px] font-black uppercase tracking-wider">
              Explore Blueprints
            </Link>
          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
