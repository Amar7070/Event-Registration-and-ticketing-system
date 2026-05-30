import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  Plus, 
  Settings, 
  BarChart3, 
  Trash2, 
  Copy, 
  XCircle, 
  Send,
  Calendar, 
  Users, 
  DollarSign, 
  Tag, 
  Ticket,
  ChevronDown,
  X,
  Sparkles
} from 'lucide-react';

export default function Events() {
  const navigate = useNavigate();
  const [events, setEvents] = useState([]);
  const [filter, setFilter] = useState('all');
  const [stats, setStats] = useState({ activeCount: 0, totalResonance: 0, totalRevenue: 0 });
  const [loading, setLoading] = useState(true);

  // Modals state
  const [selectedEventForTickets, setSelectedEventForTickets] = useState(null);
  const [ticketsList, setTicketsList] = useState([]);
  const [ticketModalOpen, setTicketModalOpen] = useState(false);
  const [ticketForm, setTicketForm] = useState({ name: '', description: '', type: 'regular', price: 0, original_price: '', quantity_total: 100, max_per_order: 10, min_per_order: 1 });

  const [selectedEventForCoupons, setSelectedEventForCoupons] = useState(null);
  const [couponsList, setCouponsList] = useState([]);
  const [couponModalOpen, setCouponModalOpen] = useState(false);
  const [couponForm, setCouponForm] = useState({ code: '', type: 'percentage', value: 10, max_discount: '', min_order_amount: 0, usage_limit: '', usage_per_user: 1, valid_from: '', valid_until: '' });

  useEffect(() => {
    fetchOrganizerEvents();
  }, []);

  const fetchOrganizerEvents = async () => {
    try {
      setLoading(true);
      const res = await api.get('/organizer/events');
      setEvents(res.data.events.data || []);
      if (res.data.stats) {
        setStats(res.data.stats);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  // Clone action
  const handleCloneEvent = async (event) => {
    if (!window.confirm(`Clone "${event.title}" as a draft?`)) return;
    try {
      const res = await api.post(`/organizer/events/${event.id}/clone`);
      alert(res.data.message);
      fetchOrganizerEvents();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to clone event.');
    }
  };

  // Publish action
  const handlePublishEvent = async (event) => {
    if (!window.confirm(`Publish "${event.title}" to the public marketplace?`)) return;
    try {
      const res = await api.post(`/organizer/events/${event.id}/publish`);
      alert(res.data.message);
      fetchOrganizerEvents();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to publish event.');
    }
  };

  // Cancel action
  const handleCancelEvent = async (event) => {
    if (!window.confirm(`Cancel "${event.title}"? Confirmed passes will be auto-refunded.`)) return;
    try {
      const res = await api.post(`/organizer/events/${event.id}/cancel`);
      alert(res.data.message);
      fetchOrganizerEvents();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to cancel event.');
    }
  };

  // Delete action
  const handleDeleteEvent = async (event) => {
    if (!window.confirm(`Delete "${event.title}"? This is permanent.`)) return;
    try {
      const res = await api.delete(`/organizer/events/${event.id}`);
      alert(res.data.message);
      fetchOrganizerEvents();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to delete event.');
    }
  };

  // Ticket modal manager
  const openTicketModal = async (evt) => {
    setSelectedEventForTickets(evt);
    setTicketModalOpen(true);
    try {
      const res = await api.get(`/organizer/events/${evt.id}/tickets`);
      setTicketsList(res.data.tickets || []);
    } catch (err) {
      console.error(err);
    }
  };

  const handleAddTicket = async (e) => {
    e.preventDefault();
    try {
      const res = await api.post(`/organizer/events/${selectedEventForTickets.id}/tickets`, ticketForm);
      setTicketsList([...ticketsList, res.data.ticket_type]);
      setTicketForm({ name: '', description: '', type: 'regular', price: 0, original_price: '', quantity_total: 100, max_per_order: 10, min_per_order: 1 });
      alert(res.data.message);
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to add ticket type.');
    }
  };

  const handleDeleteTicket = async (ticketTypeId) => {
    if (!window.confirm('Delete this ticket type?')) return;
    try {
      const res = await api.delete(`/organizer/events/${selectedEventForTickets.id}/tickets/${ticketTypeId}`);
      setTicketsList(ticketsList.filter(t => t.id !== ticketTypeId));
      alert(res.data.message);
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to delete ticket.');
    }
  };

  // Coupon modal manager
  const openCouponModal = async (evt) => {
    setSelectedEventForCoupons(evt);
    setCouponModalOpen(true);
    try {
      const res = await api.get(`/organizer/events/${evt.id}/coupons`);
      setCouponsList(res.data.coupons || []);
    } catch (err) {
      console.error(err);
    }
  };

  const handleAddCoupon = async (e) => {
    e.preventDefault();
    try {
      const res = await api.post(`/organizer/events/${selectedEventForCoupons.id}/coupons`, couponForm);
      setCouponsList([...couponsList, res.data.coupon]);
      setCouponForm({ code: '', type: 'percentage', value: 10, max_discount: '', min_order_amount: 0, usage_limit: '', usage_per_user: 1, valid_from: '', valid_until: '' });
      alert(res.data.message);
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to add coupon code.');
    }
  };

  const handleDeleteCoupon = async (couponId) => {
    if (!window.confirm('Delete this coupon code?')) return;
    try {
      const res = await api.delete(`/organizer/events/${selectedEventForCoupons.id}/coupons/${couponId}`);
      setCouponsList(couponsList.filter(c => c.id !== couponId));
      alert(res.data.message);
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to delete coupon.');
    }
  };

  return (
    <SidebarLayout type="organizer">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">HOST CONSOLE</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Event Blueprints</h1>
            <p className="text-xs text-slate-400 mt-1">Deploy new gatherings, manage reservation tiers, and audit active operations.</p>
          </div>

          <Link 
            to="/organizer/events/create"
            className="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest flex items-center gap-2"
          >
            <Plus className="w-4 h-4 stroke-[3]" />
            <span>Create Blueprint</span>
          </Link>
        </div>

        {/* Global stats */}
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm flex items-center gap-5">
            <div className="w-12 h-12 rounded-2xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
              <Calendar className="w-6 h-6" />
            </div>
            <div>
              <span className="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Total Templates</span>
              <span className="text-xl font-bold text-slate-800 dark:text-white">{stats.activeCount} Blueprints</span>
            </div>
          </div>

          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm flex items-center gap-5">
            <div className="w-12 h-12 rounded-2xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
              <Users className="w-6 h-6" />
            </div>
            <div>
              <span className="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Reservations Sold</span>
              <span className="text-xl font-bold text-slate-800 dark:text-white">{stats.totalResonance} Passes</span>
            </div>
          </div>

          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm flex items-center gap-5">
            <div className="w-12 h-12 rounded-2xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
              <DollarSign className="w-6 h-6" />
            </div>
            <div>
              <span className="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Gross Proceeds</span>
              <span className="text-xl font-bold text-slate-800 dark:text-white">₹{stats.totalRevenue.toLocaleString('en-IN')}</span>
            </div>
          </div>
        </div>

        {/* Filter Tabs */}
        <div className="flex items-center gap-3 overflow-x-auto pb-2 custom-scrollbar">
          {['all', 'upcoming', 'live', 'past'].map(f => (
            <button
              key={f}
              onClick={() => setFilter(f)}
              className={`px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all shrink-0 ${
                filter === f 
                  ? 'bg-primary text-white shadow-md shadow-primary/20' 
                  : 'bg-white text-slate-500 border border-slate-200 hover:border-primary/30 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-400 dark:hover:border-primary/50'
              }`}
            >
              {f} Events
            </button>
          ))}
        </div>

        {/* Blueprint table listing */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-450">Syncing database index...</span>
          </div>
        ) : events.length > 0 ? (
          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
            <div className="overflow-x-auto">
              <table className="w-full text-xs text-left">
                <thead className="bg-slate-50 dark:bg-slate-950/20 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                  <tr>
                    <th className="px-6 py-4.5">Blueprint Details</th>
                    <th className="px-6 py-4.5">Type & Visibility</th>
                    <th className="px-6 py-4.5">Status</th>
                    <th className="px-6 py-4.5">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                  {events.filter(evt => {
                    if (filter === 'all') return true;
                    const now = new Date();
                    const start = new Date(evt.start_date);
                    const end = new Date(evt.end_date);
                    
                    if (filter === 'upcoming') return start > now;
                    if (filter === 'live') return start <= now && end >= now;
                    if (filter === 'past') return end < now;
                    return true;
                  }).map((evt) => {
                    const isDraft = evt.status === 'draft';
                    const isPublished = evt.status === 'published';
                    const isCancelled = evt.status === 'cancelled';
                    return (
                      <tr key={evt.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-colors">
                        <td className="px-6 py-5 min-w-[200px]">
                          <div className="font-bold text-slate-850 dark:text-white text-sm line-clamp-1">{evt.title}</div>
                          <div className="flex items-center gap-1.5 text-[9px] text-slate-400 uppercase tracking-widest mt-1.5">
                            <span>{evt.category?.name}</span>
                            <span>&bull;</span>
                            <span>{new Date(evt.start_date).toLocaleDateString()}</span>
                            <span>&bull;</span>
                            <span>{new Date(evt.end_date).toLocaleDateString()}</span>
                          </div>
                        </td>
                        <td className="px-6 py-5 uppercase tracking-wider text-[10px]">
                          <div>{evt.type}</div>
                          <div className="text-slate-400 mt-1">{evt.visibility}</div>
                        </td>
                        <td className="px-6 py-5">
                          <span className={`px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider ${
                            isPublished 
                              ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20' 
                              : isCancelled 
                              ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/20' 
                              : 'bg-amber-50 text-amber-700 dark:bg-amber-950/20'
                          }`}>
                            {evt.status}
                          </span>
                        </td>
                        <td className="px-6 py-5">
                          <div className="flex flex-wrap items-center gap-2">
                            <button 
                              onClick={() => navigate(`/organizer/events/${evt.id}/edit`)}
                              className="p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-lg hover:text-primary transition-colors cursor-pointer"
                              title="Edit Blueprint"
                            >
                              <Settings className="w-4 h-4" />
                            </button>
                            <button 
                              onClick={() => openTicketModal(evt)}
                              className="p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-lg hover:text-primary transition-colors cursor-pointer"
                              title="Manage Ticket Tiers"
                            >
                              <Ticket className="w-4 h-4" />
                            </button>
                            <button 
                              onClick={() => openCouponModal(evt)}
                              className="p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-lg hover:text-primary transition-colors cursor-pointer"
                              title="Manage Coupons"
                            >
                              <Tag className="w-4 h-4" />
                            </button>
                            <button 
                              onClick={() => navigate(`/organizer/events/${evt.id}/attendees`)}
                              className="p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-lg hover:text-primary transition-colors cursor-pointer"
                              title="Attendee Registry"
                            >
                              <Users className="w-4 h-4" />
                            </button>
                            <button 
                              onClick={() => navigate(`/organizer/events/${evt.id}/scan`)}
                              className="p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-lg hover:text-primary transition-colors cursor-pointer font-bold text-[10px]"
                              title="QR Scan Checkin"
                            >
                              Scan
                            </button>
                            <button 
                              onClick={() => navigate(`/organizer/events/${evt.id}/promote`)}
                              className="p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-lg hover:text-primary transition-colors cursor-pointer"
                              title="Promote / Advertise Showcase"
                            >
                              <Sparkles className="w-4 h-4" />
                            </button>
                            <button 
                              onClick={() => handleCloneEvent(evt)}
                              className="p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-lg hover:text-primary transition-colors cursor-pointer"
                              title="Clone Blueprint"
                            >
                              <Copy className="w-4 h-4" />
                            </button>

                            {isDraft && (
                              <button 
                                onClick={() => handlePublishEvent(evt)}
                                className="p-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg hover:bg-emerald-100 transition-colors cursor-pointer"
                                title="Publish to Market"
                              >
                                <Send className="w-4 h-4" />
                              </button>
                            )}

                            {isPublished && (
                              <button 
                                onClick={() => handleCancelEvent(evt)}
                                className="p-2 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg hover:bg-rose-100 transition-colors cursor-pointer"
                                title="Cancel Event"
                              >
                                <XCircle className="w-4 h-4" />
                              </button>
                            )}

                            <button 
                              onClick={() => handleDeleteEvent(evt)}
                              className="p-2 bg-rose-50 text-rose-600 border border-rose-150 rounded-lg hover:bg-rose-100 transition-colors cursor-pointer"
                              title="Delete Blueprint"
                            >
                              <Trash2 className="w-4 h-4" />
                            </button>
                          </div>
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
            <Ticket className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No blueprints deployed</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              You haven't designed any experience nodes yet. Spawn your first gathering blueprint!
            </p>
            <Link to="/organizer/events/create" className="btn-primary mt-6 px-6 py-2.5 text-[10px] font-black uppercase tracking-wider">
              Create Blueprint
            </Link>
          </div>
        )}

        {/* Ticket Modal */}
        {ticketModalOpen && selectedEventForTickets && (
          <div className="fixed inset-0 z-[120] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all">
            <div className="relative w-full max-w-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-3xl p-8 overflow-hidden max-h-[90vh] flex flex-col justify-between">
              
              <div className="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold leading-tight">Manage Ticket Tiers</h3>
                  <span className="font-mono text-[9px] text-slate-400 block mt-1">{selectedEventForTickets.title}</span>
                </div>
                <button onClick={() => setTicketModalOpen(false)} className="text-slate-400 hover:text-slate-650 transition-colors cursor-pointer">
                  <X className="w-5 h-5" />
                </button>
              </div>

              <div className="flex-1 overflow-y-auto space-y-6 pr-2">
                
                {/* Tickets list */}
                <div className="space-y-3">
                  <h4 className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Tiers</h4>
                  {ticketsList.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      {ticketsList.map((t) => (
                        <div key={t.id} className="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl flex justify-between items-start text-xs">
                          <div>
                            <div className="font-bold text-slate-850 dark:text-slate-100 uppercase tracking-wider">{t.name}</div>
                            <div className="text-[10px] text-slate-450 mt-1">{t.type} &bull; Capacity: {t.quantity_total}</div>
                            <div className="font-bold text-[#4E7D5B] mt-2">₹{t.price}</div>
                          </div>
                          <button 
                            onClick={() => handleDeleteTicket(t.id)}
                            className="text-rose-500 hover:text-rose-700 transition-colors cursor-pointer"
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="p-8 text-center bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-105 dark:border-slate-850 text-slate-400">
                      No ticket tiers defined. Reservation is restricted until a tier is added.
                    </div>
                  )}
                </div>

                {/* Form to add */}
                <form onSubmit={handleAddTicket} className="space-y-4 border-t border-slate-100 dark:border-slate-800 pt-6">
                  <h4 className="text-[10px] font-black text-slate-450 uppercase tracking-widest">Add New Tier Node</h4>
                  
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Tier Name</label>
                      <input 
                        type="text" 
                        required
                        value={ticketForm.name}
                        onChange={(e) => setTicketForm({ ...ticketForm, name: e.target.value })}
                        placeholder="e.g. VIP Key Access"
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Classification Type</label>
                      <select 
                        value={ticketForm.type}
                        onChange={(e) => setTicketForm({ ...ticketForm, type: e.target.value })}
                        required
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-850 dark:text-slate-200 outline-none cursor-pointer"
                      >
                        <option value="regular">Regular Pass</option>
                        <option value="vip">VIP Key</option>
                        <option value="early_bird">Early Bird</option>
                        <option value="student">Student Discount</option>
                        <option value="premium">Premium Pack</option>
                      </select>
                    </div>

                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Price (INR)</label>
                      <input 
                        type="number" 
                        required
                        min="0"
                        value={ticketForm.price}
                        onChange={(e) => setTicketForm({ ...ticketForm, price: parseFloat(e.target.value) })}
                        placeholder="0 for Free"
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none font-bold"
                      />
                    </div>

                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Tier Capacity</label>
                      <input 
                        type="number" 
                        required
                        min="1"
                        value={ticketForm.quantity_total}
                        onChange={(e) => setTicketForm({ ...ticketForm, quantity_total: parseInt(e.target.value) })}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                      />
                    </div>
                  </div>

                  <div className="flex justify-end">
                    <button 
                      type="submit"
                      className="px-6 py-3 bg-[#4E7D5B] text-white rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-[#3D6449] transition-colors cursor-pointer"
                    >
                      Save Ticket Tier
                    </button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        )}

        {/* Coupon Modal */}
        {couponModalOpen && selectedEventForCoupons && (
          <div className="fixed inset-0 z-[120] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all">
            <div className="relative w-full max-w-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-3xl p-8 overflow-hidden max-h-[90vh] flex flex-col justify-between">
              
              <div className="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold leading-tight">Manage Coupon Keys</h3>
                  <span className="font-mono text-[9px] text-slate-400 block mt-1">{selectedEventForCoupons.title}</span>
                </div>
                <button onClick={() => setCouponModalOpen(false)} className="text-slate-400 hover:text-slate-650 transition-colors cursor-pointer">
                  <X className="w-5 h-5" />
                </button>
              </div>

              <div className="flex-1 overflow-y-auto space-y-6 pr-2">
                
                {/* Coupons list */}
                <div className="space-y-3">
                  <h4 className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Coupon Codes</h4>
                  {couponsList.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      {couponsList.map((c) => (
                        <div key={c.id} className="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl flex justify-between items-start text-xs">
                          <div>
                            <div className="font-bold text-primary uppercase tracking-wider">{c.code}</div>
                            <div className="text-[10px] text-slate-450 mt-1">{c.type === 'percentage' ? `${c.value}%` : `Flat ₹${c.value}`} Discount</div>
                            <div className="text-[9px] text-slate-400 mt-1">Usage: {c.used_count ?? 0} / {c.usage_limit || 'Unlimited'}</div>
                          </div>
                          <button 
                            onClick={() => handleDeleteCoupon(c.id)}
                            className="text-rose-500 hover:text-rose-700 transition-colors cursor-pointer"
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="p-8 text-center bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-105 dark:border-slate-850 text-slate-400">
                      No coupon code promotions created.
                    </div>
                  )}
                </div>

                {/* Form to add */}
                <form onSubmit={handleAddCoupon} className="space-y-4 border-t border-slate-100 dark:border-slate-800 pt-6">
                  <h4 className="text-[10px] font-black text-slate-450 uppercase tracking-widest">Generate Coupon Key</h4>
                  
                  <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Code</label>
                      <input 
                        type="text" 
                        required
                        value={couponForm.code}
                        onChange={(e) => setCouponForm({ ...couponForm, code: e.target.value.toUpperCase() })}
                        placeholder="e.g. FIFTYOFF"
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none uppercase font-bold"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Discount Type</label>
                      <select 
                        value={couponForm.type}
                        onChange={(e) => setCouponForm({ ...couponForm, type: e.target.value })}
                        required
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer"
                      >
                        <option value="percentage">Percentage (%)</option>
                        <option value="flat">Flat Amount (INR)</option>
                      </select>
                    </div>
                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Discount Value</label>
                      <input 
                        type="number" 
                        required
                        min="1"
                        value={couponForm.value}
                        onChange={(e) => setCouponForm({ ...couponForm, value: parseFloat(e.target.value) })}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                      />
                    </div>

                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Min Order Total (INR)</label>
                      <input 
                        type="number" 
                        required
                        min="0"
                        value={couponForm.min_order_amount}
                        onChange={(e) => setCouponForm({ ...couponForm, min_order_amount: parseFloat(e.target.value) })}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                      />
                    </div>

                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Valid From</label>
                      <input 
                        type="datetime-local" 
                        required
                        value={couponForm.valid_from}
                        onChange={(e) => setCouponForm({ ...couponForm, valid_from: e.target.value })}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer"
                      />
                    </div>

                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Valid Until</label>
                      <input 
                        type="datetime-local" 
                        required
                        value={couponForm.valid_until}
                        onChange={(e) => setCouponForm({ ...couponForm, valid_until: e.target.value })}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer"
                      />
                    </div>
                  </div>

                  <div className="flex justify-end">
                    <button 
                      type="submit"
                      className="px-6 py-3 bg-[#4E7D5B] text-white rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-[#3D6449] transition-colors cursor-pointer"
                    >
                      Deploy Coupon Code
                    </button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
