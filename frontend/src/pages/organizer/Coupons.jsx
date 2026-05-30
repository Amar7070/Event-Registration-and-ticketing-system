import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { Tag, Plus, Loader2, Calendar, Trash2, CheckCircle, ToggleLeft, ToggleRight } from 'lucide-react';

export default function Coupons() {
  const [coupons, setCoupons] = useState([]);
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);

  // Form State
  const [code, setCode] = useState('');
  const [type, setType] = useState('percentage');
  const [value, setValue] = useState('');
  const [validFrom, setValidFrom] = useState('');
  const [validUntil, setValidUntil] = useState('');
  const [eventId, setEventId] = useState('');
  const [usageLimit, setUsageLimit] = useState('');

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      setLoading(true);
      setError(null);
      const [couponsRes, eventsRes] = await Promise.all([
        api.get('/organizer/coupons'),
        api.get('/organizer/events')
      ]);
      // Coupons: returns { data: [...] }
      setCoupons(couponsRes.data.data || []);
      // Events: returns { events: { data: [...] } } (paginated)
      const eventsPayload = eventsRes.data.events;
      if (eventsPayload && eventsPayload.data) {
        setEvents(eventsPayload.data);
      } else if (Array.isArray(eventsPayload)) {
        setEvents(eventsPayload);
      } else {
        setEvents([]);
      }
    } catch (err) {
      console.error('Failed to load coupons data:', err);
      setError(err.response?.data?.message || 'Failed to fetch coupons data. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const showSuccess = (msg) => {
    setSuccess(msg);
    setTimeout(() => setSuccess(null), 3500);
  };

  const handleCreateCoupon = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    try {
      await api.post('/organizer/coupons', {
        code,
        type,
        value: parseFloat(value),
        valid_from: validFrom ? new Date(validFrom).toISOString() : null,
        valid_until: validUntil ? new Date(validUntil).toISOString() : null,
        event_id: eventId ? parseInt(eventId) : null,
        usage_limit: usageLimit ? parseInt(usageLimit) : null,
      });
      // Reset form
      setCode('');
      setValue('');
      setValidFrom('');
      setValidUntil('');
      setEventId('');
      setUsageLimit('');
      setType('percentage');
      showSuccess('Coupon created successfully!');
      fetchData();
    } catch (err) {
      console.error('Create coupon error:', err);
      if (err.response?.status === 422) {
        // Validation error — show all messages
        const msgs = err.response.data.errors;
        if (msgs) {
          const allErrors = Object.values(msgs).flat().join(' ');
          setError(allErrors);
        } else {
          setError(err.response.data.message || 'Validation failed.');
        }
      } else {
        setError(err.response?.data?.message || 'Failed to create coupon. Please try again.');
      }
    } finally {
      setSubmitting(false);
    }
  };

  const handleDelete = async (couponId) => {
    if (!window.confirm('Delete this coupon? This cannot be undone.')) return;
    try {
      await api.delete(`/organizer/coupons/${couponId}`);
      showSuccess('Coupon deleted.');
      setCoupons(prev => prev.filter(c => c.id !== couponId));
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to delete coupon.');
    }
  };

  return (
    <SidebarLayout type="organizer">
      <div className="space-y-8 animate-slide-up text-left">
        <div className="pb-6 border-b border-slate-100 dark:border-slate-800">
          <span className="text-[9px] font-black text-[#4E7D5B] uppercase tracking-[0.2em] block mb-1">PROMOTION ENGINE</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Coupons & Promos</h1>
          <p className="text-xs text-slate-400 mt-1">Manage ecosystem-wide discount codes or targeted event promotions.</p>
        </div>

        {error && (
          <div className="p-4 bg-red-50 dark:bg-red-950/20 text-red-600 border border-red-100 dark:border-red-900 rounded-xl text-xs font-bold flex items-start gap-2">
            <span className="shrink-0 mt-0.5">⚠</span>
            <span>{error}</span>
          </div>
        )}

        {success && (
          <div className="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 border border-emerald-100 dark:border-emerald-900 rounded-xl text-xs font-bold flex items-center gap-2">
            <CheckCircle className="w-4 h-4 shrink-0" />
            {success}
          </div>
        )}

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
          
          {/* Create Coupon Form */}
          <div className="lg:col-span-1 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
            <h3 className="font-serif font-bold text-lg mb-6 flex items-center gap-2">
              <Plus className="w-5 h-5 text-[#4E7D5B]" />
              Create Coupon
            </h3>
            
            <form onSubmit={handleCreateCoupon} className="space-y-4">
              <div>
                <label className="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-2">Coupon Code</label>
                <input required type="text" value={code} onChange={e => setCode(e.target.value.toUpperCase())} className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs outline-none focus:border-[#4E7D5B]" placeholder="e.g. SUMMER25" />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-2">Type</label>
                  <select value={type} onChange={e => setType(e.target.value)} className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs outline-none">
                    <option value="percentage">Percentage (%)</option>
                    <option value="flat">Flat Amount (₹)</option>
                  </select>
                </div>
                <div>
                  <label className="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-2">Value</label>
                  <input required type="number" min="0" step="0.01" value={value} onChange={e => setValue(e.target.value)} className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs outline-none" placeholder={type === 'percentage' ? '25' : '500'} />
                </div>
              </div>

              <div>
                <label className="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-2">Target Event</label>
                <select value={eventId} onChange={e => setEventId(e.target.value)} className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs outline-none">
                  <option value="">All My Events (Global)</option>
                  {events.map(ev => (
                    <option key={ev.id} value={ev.id}>{ev.title}</option>
                  ))}
                </select>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-2">Valid From</label>
                  <div className="relative">
                    <input required type="datetime-local" value={validFrom} onChange={e => setValidFrom(e.target.value)} className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pl-4 pr-10 py-3 text-[10px] outline-none relative [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:h-full [&::-webkit-calendar-picker-indicator]:opacity-0 [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:left-0 [&::-webkit-calendar-picker-indicator]:top-0" />
                    <Calendar className="w-3.5 h-3.5 absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
                  </div>
                </div>
                <div>
                  <label className="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-2">Valid Until</label>
                  <div className="relative">
                    <input required type="datetime-local" value={validUntil} onChange={e => setValidUntil(e.target.value)} className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl pl-4 pr-10 py-3 text-[10px] outline-none relative [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:h-full [&::-webkit-calendar-picker-indicator]:opacity-0 [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:left-0 [&::-webkit-calendar-picker-indicator]:top-0" />
                    <Calendar className="w-3.5 h-3.5 absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
                  </div>
                </div>
              </div>

              <div>
                <label className="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-2">Usage Limit (Optional)</label>
                <input type="number" min="1" value={usageLimit} onChange={e => setUsageLimit(e.target.value)} className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs outline-none" placeholder="e.g. 100" />
              </div>

              <button type="submit" disabled={submitting} className="w-full bg-[#4E7D5B] text-white py-3.5 rounded-xl text-xs font-black uppercase tracking-widest mt-4 hover:bg-[#3D6449] disabled:opacity-50 flex items-center justify-center gap-2">
                {submitting && <Loader2 className="w-4 h-4 animate-spin" />}
                Generate Code
              </button>
            </form>
          </div>

          {/* List of Coupons */}
          <div className="lg:col-span-2 space-y-4">
            {loading ? (
              <div className="py-12 flex justify-center"><Loader2 className="w-6 h-6 animate-spin text-[#4E7D5B]" /></div>
            ) : coupons.length === 0 ? (
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-12 text-center">
                <Tag className="w-12 h-12 text-slate-300 mx-auto mb-4" />
                <h3 className="text-lg font-serif font-bold text-slate-900 dark:text-white">No Coupons Issued</h3>
                <p className="text-xs text-slate-400 mt-2">Generate discount codes to drive more registrations.</p>
              </div>
            ) : (
              coupons.map(coupon => (
                <div key={coupon.id} className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                  <div className="flex items-start justify-between gap-4">
                    <div className="flex-1 min-w-0">
                      {/* Code + Status Badge */}
                      <div className="flex flex-wrap items-center gap-2 mb-2">
                        <span className="font-mono font-black text-sm bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg tracking-widest text-slate-900 dark:text-white">{coupon.code}</span>
                        <span className={`text-[9px] font-black uppercase px-2.5 py-1 rounded-full ${
                          coupon.is_active
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400'
                            : 'bg-red-100 text-red-700 dark:bg-red-950/30 dark:text-red-400'
                        }`}>
                          {coupon.is_active ? '● Active' : '○ Inactive'}
                        </span>
                      </div>

                      {/* Target Event */}
                      <p className="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-2">
                        🎯 {coupon.event_name || 'All Events'}
                      </p>

                      {/* Discount + Validity */}
                      <div className="flex flex-wrap items-center gap-4 text-[10px] text-slate-400 font-medium">
                        <span className="flex items-center gap-1 text-[#4E7D5B] font-black">
                          <Tag className="w-3.5 h-3.5" />
                          {coupon.type === 'percentage' ? `${coupon.value}% OFF` : `₹${coupon.value} OFF`}
                        </span>
                        <span className="flex items-center gap-1">
                          <Calendar className="w-3.5 h-3.5" />
                          Valid until {new Date(coupon.valid_until).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })}
                        </span>
                        <span className="font-bold text-slate-600 dark:text-slate-300">
                          Used: {coupon.usage_count || 0}{coupon.usage_limit ? ` / ${coupon.usage_limit}` : ''}
                        </span>
                      </div>
                    </div>

                    {/* Actions */}
                    <div className="flex items-center gap-2 shrink-0">
                      <button
                        onClick={() => handleDelete(coupon.id)}
                        className="p-2 rounded-xl text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all cursor-pointer"
                        title="Delete coupon"
                      >
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>

        </div>
      </div>
    </SidebarLayout>
  );
}
