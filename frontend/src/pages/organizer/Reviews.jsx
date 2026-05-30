import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  MessageSquare, 
  Search, 
  Trash2,
  Star,
  Mail,
  X,
  ThumbsUp,
  ThumbsDown,
  Send
} from 'lucide-react';

export default function OrganizerReviews() {
  const [reviews, setReviews] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  // Reply Modal States
  const [replyReview, setReplyReview] = useState(null);
  const [replyTemplate, setReplyTemplate] = useState('positive');
  const [customNote, setCustomNote] = useState('');
  const [sendingReply, setSendingReply] = useState(false);

  const POSITIVE_TEMPLATE = "Thank you so much for your wonderful feedback! We are thrilled you had a great time at our event and hope to see you again soon.";
  const NEGATIVE_TEMPLATE = "We sincerely apologize that your experience didn't meet expectations. We value your feedback and are actively working on improvements for our future events.";

  useEffect(() => {
    fetchReviews();
  }, []);

  const fetchReviews = async () => {
    try {
      setLoading(true);
      const res = await api.get('/organizer/reviews');
      setReviews(res.data?.reviews?.data || res.data?.reviews || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleDeleteReview = async (reviewId) => {
    if (!window.confirm(`Are you sure you want to permanently delete this review from your event?`)) return;

    try {
      const res = await api.delete(`/organizer/reviews/${reviewId}`);
      alert(res.data.message);
      fetchReviews();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to delete review.');
    }
  };

  const openReplyModal = (review) => {
    setReplyReview(review);
    setReplyTemplate(review.rating >= 4 ? 'positive' : 'negative');
    setCustomNote('');
  };

  const closeReplyModal = () => {
    setReplyReview(null);
    setCustomNote('');
  };

  const handleSendReply = async () => {
    if (!replyReview) return;
    
    setSendingReply(true);
    try {
      const replyMessage = replyTemplate === 'positive' ? POSITIVE_TEMPLATE : NEGATIVE_TEMPLATE;
      const res = await api.post(`/organizer/reviews/${replyReview.id}/reply`, {
        replyMessage,
        customNote
      });
      alert(res.data.message || 'Reply sent successfully!');
      closeReplyModal();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to send reply.');
    } finally {
      setSendingReply(false);
    }
  };

  const filteredReviews = reviews.filter(r => {
    const matchesSearch = 
      (r.event?.title || '').toLowerCase().includes(search.toLowerCase()) ||
      (r.user?.name || '').toLowerCase().includes(search.toLowerCase()) ||
      (r.comment || '').toLowerCase().includes(search.toLowerCase());

    return matchesSearch;
  });

  // Calculate metrics
  const totalReviews = reviews.length;
  const avgRating = totalReviews > 0 
    ? (reviews.reduce((acc, curr) => acc + curr.rating, 0) / totalReviews).toFixed(1) 
    : 0;

  return (
    <SidebarLayout type="organizer">
      <div className="space-y-8 text-left animate-slide-up relative">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">FEEDBACK</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Experience Reviews</h1>
            <p className="text-xs text-slate-400 mt-1">See what attendees are saying about your events.</p>
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

        {/* Quick Metrics */}
        {!loading && totalReviews > 0 && (
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Reviews</p>
              <div className="text-3xl font-serif font-bold text-slate-900 dark:text-white">{totalReviews}</div>
            </div>
            <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Avg Rating</p>
              <div className="flex items-center gap-2">
                <div className="text-3xl font-serif font-bold text-slate-900 dark:text-white">{avgRating}</div>
                <Star className="w-5 h-5 text-amber-400 fill-amber-400" />
              </div>
            </div>
          </div>
        )}

        {/* Reviews Table */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
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
                        <div className="flex items-center justify-end gap-2">
                          <button 
                            onClick={() => openReplyModal(r)}
                            className="p-2 rounded-xl border border-sky-200 text-sky-600 bg-sky-50 hover:bg-sky-100 dark:border-sky-800 dark:text-sky-400 dark:bg-sky-900/30 dark:hover:bg-sky-900/50 active:scale-95 cursor-pointer transition-all inline-flex"
                            title="Reply to Attendee"
                          >
                            <Mail className="w-4 h-4" />
                          </button>
                          <button 
                            onClick={() => handleDeleteReview(r.id)}
                            className="p-2 rounded-xl border border-rose-200 text-rose-600 bg-rose-50 hover:bg-rose-100 dark:border-rose-800 dark:text-rose-400 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 active:scale-95 cursor-pointer transition-all inline-flex"
                            title="Delete/Hide Review"
                          >
                            <Trash2 className="w-4 h-4" />
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
            <MessageSquare className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No reviews found</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              There are no event reviews matching your criteria.
            </p>
          </div>
        )}

        {/* Reply Modal */}
        {replyReview && (
          <div className="fixed inset-0 z-50 flex justify-center items-start pt-[12vh] p-4 sm:p-6 bg-slate-900/70 backdrop-blur-sm animate-fade-in">
            <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-3xl w-full max-w-lg shadow-2xl flex flex-col overflow-hidden max-h-[75vh]">
              {/* Modal Header */}
              <div className="shrink-0 flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900">
                <div>
                  <h3 className="font-serif font-bold text-lg text-slate-900 dark:text-white">Reply to {replyReview.user?.name}</h3>
                  <p className="text-[10px] text-slate-500 uppercase tracking-widest mt-1">Review for {replyReview.event?.title}</p>
                </div>
                <button onClick={closeReplyModal} className="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-white dark:bg-slate-800 rounded-full border border-slate-100 dark:border-slate-700 transition-colors">
                  <X className="w-4 h-4" />
                </button>
              </div>

              {/* Modal Body */}
              <div className="p-6 space-y-6 overflow-y-auto flex-1 custom-scrollbar min-h-0">
                
                {/* Original Review Preview */}
                <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-inner">
                  <div className="flex gap-1 mb-3">
                    {[...Array(5)].map((_, i) => (
                      <Star key={i} className={`w-4 h-4 ${i < replyReview.rating ? 'fill-amber-400 text-amber-400' : 'fill-slate-200 text-slate-200 dark:fill-slate-600 dark:text-slate-600'}`} />
                    ))}
                  </div>
                  <p className="text-sm font-medium text-slate-800 dark:text-slate-100 italic">"{replyReview.comment}"</p>
                </div>

                {/* Template Selection */}
                <div>
                  <label className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 block">1. Select Email Template</label>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button 
                      onClick={() => setReplyTemplate('positive')}
                      className={`p-4 rounded-2xl border text-left transition-all ${
                        replyTemplate === 'positive' 
                        ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 ring-1 ring-emerald-500 shadow-sm' 
                        : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 hover:border-emerald-300 dark:hover:border-emerald-700'
                      }`}
                    >
                      <div className="flex items-center gap-2 mb-2">
                        <div className={`p-1.5 rounded-full ${replyTemplate === 'positive' ? 'bg-emerald-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-300'}`}>
                          <ThumbsUp className="w-3.5 h-3.5" />
                        </div>
                        <span className={`text-sm font-bold ${replyTemplate === 'positive' ? 'text-emerald-900 dark:text-emerald-300' : 'text-slate-700 dark:text-slate-200'}`}>Positive Reply</span>
                      </div>
                      <p className={`text-xs line-clamp-3 leading-relaxed ${replyTemplate === 'positive' ? 'text-emerald-700 dark:text-emerald-200' : 'text-slate-500 dark:text-slate-400'}`}>{POSITIVE_TEMPLATE}</p>
                    </button>

                    <button 
                      onClick={() => setReplyTemplate('negative')}
                      className={`p-4 rounded-2xl border text-left transition-all ${
                        replyTemplate === 'negative' 
                        ? 'border-rose-500 bg-rose-50 dark:bg-rose-500/10 ring-1 ring-rose-500 shadow-sm' 
                        : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 hover:border-rose-300 dark:hover:border-rose-700'
                      }`}
                    >
                      <div className="flex items-center gap-2 mb-2">
                        <div className={`p-1.5 rounded-full ${replyTemplate === 'negative' ? 'bg-rose-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-300'}`}>
                          <ThumbsDown className="w-3.5 h-3.5" />
                        </div>
                        <span className={`text-sm font-bold ${replyTemplate === 'negative' ? 'text-rose-900 dark:text-rose-300' : 'text-slate-700 dark:text-slate-200'}`}>Negative Reply</span>
                      </div>
                      <p className={`text-xs line-clamp-3 leading-relaxed ${replyTemplate === 'negative' ? 'text-rose-700 dark:text-rose-200' : 'text-slate-500 dark:text-slate-400'}`}>{NEGATIVE_TEMPLATE}</p>
                    </button>
                  </div>
                </div>

                {/* Custom Note */}
                <div>
                  <label className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 block">2. Add a Custom Note (Optional)</label>
                  <textarea
                    rows="3"
                    className="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 text-xs text-slate-800 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all resize-none"
                    placeholder="E.g. We would love to offer you a free ticket to our next event..."
                    value={customNote}
                    onChange={(e) => setCustomNote(e.target.value)}
                  ></textarea>
                </div>
              </div>

              {/* Modal Footer */}
              <div className="shrink-0 p-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3 bg-slate-50/50 dark:bg-slate-900">
                <button 
                  onClick={closeReplyModal}
                  className="px-5 py-2.5 rounded-full text-xs font-bold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors"
                >
                  Cancel
                </button>
                <button 
                  onClick={handleSendReply}
                  disabled={sendingReply}
                  className="px-6 py-2.5 rounded-full text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm shadow-indigo-200 flex items-center gap-2"
                >
                  {sendingReply ? (
                    <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                  ) : (
                    <Send className="w-3.5 h-3.5" />
                  )}
                  {sendingReply ? 'Sending Email...' : 'Send Reply'}
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
    </SidebarLayout>
  );
}
