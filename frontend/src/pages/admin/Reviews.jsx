import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  MessageSquare, 
  Search, 
  Trash2,
  Star
} from 'lucide-react';

export default function AdminReviews() {
  const [reviews, setReviews] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  useEffect(() => {
    fetchReviews();
  }, []);

  const fetchReviews = async () => {
    try {
      setLoading(true);
      const res = await api.get('/admin/reviews');
      setReviews(res.data?.reviews?.data || res.data?.reviews || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleDeleteReview = async (reviewId) => {
    if (!window.confirm(`Are you sure you want to permanently delete this review?`)) return;

    try {
      const res = await api.delete(`/admin/reviews/${reviewId}`);
      alert(res.data.message);
      fetchReviews();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to delete review.');
    }
  };

  const filteredReviews = reviews.filter(r => {
    const matchesSearch = 
      (r.event?.title || '').toLowerCase().includes(search.toLowerCase()) ||
      (r.user?.name || '').toLowerCase().includes(search.toLowerCase()) ||
      (r.comment || '').toLowerCase().includes(search.toLowerCase());

    return matchesSearch;
  });

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-indigo-500 uppercase tracking-[0.2em] block mb-1">MODERATION</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Experience Reviews</h1>
            <p className="text-xs text-slate-400 mt-1">Monitor and moderate attendee reviews across the platform.</p>
          </div>

          <div className="flex flex-wrap items-center gap-3">
            <div className="relative flex items-center bg-white dark:bg-slate-900 rounded-full border border-slate-100 dark:border-slate-800 px-4 py-1.5 shadow-sm max-w-xs">
              <Search className="w-4 h-4 text-slate-400 shrink-0" />
              <input 
                type="text" 
                placeholder="Search reviews..." 
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="flex-1 bg-transparent border-none text-xs py-2 px-2 placeholder:text-slate-400 text-slate-800 dark:text-slate-205 outline-none"
              />
            </div>
          </div>
        </div>

        {/* Reviews Table */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-455">Retrieving reviews...</span>
          </div>
        ) : filteredReviews.length > 0 ? (
          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
            <div className="overflow-x-auto">
              <table className="w-full text-xs text-left">
                <thead className="bg-slate-50 dark:bg-slate-950/20 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                  <tr>
                    <th className="px-6 py-4.5">Target Event</th>
                    <th className="px-6 py-4.5">Reviewer</th>
                    <th className="px-6 py-4.5">Rating & Comment</th>
                    <th className="px-6 py-4.5">Date</th>
                    <th className="px-6 py-4.5 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                  {filteredReviews.map((r) => (
                    <tr key={r.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-colors">
                      <td className="px-6 py-5">
                        <div className="font-bold text-slate-850 dark:text-white text-sm">{r.event?.title || 'Unknown Event'}</div>
                      </td>
                      <td className="px-6 py-5">
                        <div className="font-bold text-slate-850 dark:text-white text-sm">{r.user?.name || 'Unknown User'}</div>
                      </td>
                      <td className="px-6 py-5 max-w-sm">
                        <div className="flex gap-1 mb-1.5">
                          {[...Array(5)].map((_, i) => (
                            <Star 
                              key={i}
                              className={`w-3.5 h-3.5 ${i < r.rating ? 'fill-amber-400 text-amber-400' : 'fill-slate-100 text-slate-200 dark:fill-slate-800 dark:text-slate-800'}`} 
                            />
                          ))}
                        </div>
                        <p className="text-[11px] text-slate-500 line-clamp-2">{r.comment || 'No comment provided.'}</p>
                      </td>
                      <td className="px-6 py-5 text-slate-400 text-[11px]">
                        {new Date(r.created_at).toLocaleDateString()}
                      </td>
                      <td className="px-6 py-5 text-right">
                        <button 
                          onClick={() => handleDeleteReview(r.id)}
                          className="p-2 rounded-xl border border-rose-200 text-rose-600 bg-rose-50 hover:bg-rose-100 transition-colors inline-flex"
                          title="Delete Review"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        ) : (
          <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl max-w-md mx-auto">
            <MessageSquare className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No reviews found</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              There are no event reviews matching your criteria.
            </p>
          </div>
        )}
      </div>
    </SidebarLayout>
  );
}
