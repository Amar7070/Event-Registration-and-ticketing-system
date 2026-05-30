import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  Scale, 
  DollarSign, 
  Percent, 
  Search, 
  Calendar,
  ChevronLeft,
  ChevronRight
} from 'lucide-react';

export default function Revenue() {
  const [payments, setPayments] = useState([]);
  const [stats, setStats] = useState({ totalCommission: 0, totalGross: 0 });
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [search, setSearch] = useState('');

  useEffect(() => {
    fetchRevenue();
  }, [page]);

  const fetchRevenue = async () => {
    try {
      setLoading(true);
      const res = await api.get(`/admin/revenue?page=${page}`);
      setPayments(res.data.payments.data || []);
      if (res.data.payments.last_page) {
        setTotalPages(res.data.payments.last_page);
      }
      if (res.data.stats) {
        setStats(res.data.stats);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const filteredPayments = payments.filter(p => 
    p.payment_reference.toLowerCase().includes(search.toLowerCase()) ||
    (p.user?.name || '').toLowerCase().includes(search.toLowerCase()) ||
    (p.event?.title || '').toLowerCase().includes(search.toLowerCase())
  );

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">REVENUE COMMISSION LEDGER</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Financial Curation</h1>
            <p className="text-xs text-slate-400 mt-1">Audit platform commission margins, gross transactional proceeds, and invoice logs.</p>
          </div>

          <div className="relative flex items-center bg-white dark:bg-slate-900 rounded-full border border-slate-100 dark:border-slate-800 px-4 py-1.5 shadow-sm max-w-xs w-full">
            <Search className="w-4 h-4 text-slate-400 shrink-0" />
            <input 
              type="text" 
              placeholder="Search reference, user..." 
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="flex-1 bg-transparent border-none text-xs py-2 px-2.5 placeholder:text-slate-400 text-slate-850 dark:text-slate-200 outline-none"
            />
          </div>
        </div>

        {/* Stats */}
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm flex items-center gap-5">
            <div className="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/20 flex items-center justify-center text-rose-550">
              <DollarSign className="w-6 h-6" />
            </div>
            <div>
              <span className="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Gross Volume Transacted</span>
              <span className="text-xl font-bold text-slate-800 dark:text-white">₹{stats.totalGross.toLocaleString('en-IN')}</span>
            </div>
          </div>

          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm flex items-center gap-5">
            <div className="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/20 flex items-center justify-center text-rose-555">
              <Percent className="w-6 h-6" />
            </div>
            <div>
              <span className="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Net Platform Revenue</span>
              <span className="text-xl font-bold text-slate-800 dark:text-white">₹{stats.totalCommission.toLocaleString('en-IN')}</span>
            </div>
          </div>
        </div>

        {/* Ledger Table */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-455">Compiling financial data...</span>
          </div>
        ) : filteredPayments.length > 0 ? (
          <div className="space-y-6">
            <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
              <div className="overflow-x-auto">
                <table className="w-full text-xs text-left">
                  <thead className="bg-slate-50 dark:bg-slate-950/20 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                    <tr>
                      <th className="px-6 py-4.5">Reference ID</th>
                      <th className="px-6 py-4.5">Account & Event</th>
                      <th className="px-6 py-4.5">Amount</th>
                      <th className="px-6 py-4.5">Commission Fee</th>
                      <th className="px-6 py-4.5">Curation Date</th>
                      <th className="px-6 py-4.5">State</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                    {filteredPayments.map((p) => {
                      const isCompleted = p.status === 'completed';
                      return (
                        <tr key={p.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-colors">
                          <td className="px-6 py-5 font-mono font-bold text-slate-400">
                            #{p.payment_reference}
                          </td>
                          <td className="px-6 py-5">
                            <div className="font-bold text-slate-850 dark:text-white text-sm line-clamp-1">{p.event?.title}</div>
                            <div className="text-[10px] text-slate-400 mt-1">Payer: {p.user?.name}</div>
                          </td>
                          <td className="px-6 py-5 font-bold text-slate-800 dark:text-white">
                            ₹{(p.amount || 0).toLocaleString('en-IN')}
                          </td>
                          <td className="px-6 py-5 font-bold text-[#4E7D5B]">
                            ₹{(p.platform_fee || 0).toLocaleString('en-IN')}
                          </td>
                          <td className="px-6 py-5 text-slate-450">
                            {p.paid_at ? new Date(p.paid_at).toLocaleString() : 'Pending'}
                          </td>
                          <td className="px-6 py-5">
                            <span className={`px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider ${
                              isCompleted 
                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20' 
                                : 'bg-rose-50 text-rose-700 dark:bg-rose-950/20'
                            }`}>
                              {p.status}
                            </span>
                          </td>
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              </div>
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
            <Scale className="w-10 h-10 text-slate-350 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No payments logged</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              No transactions have been processed on the platform yet.
            </p>
          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
