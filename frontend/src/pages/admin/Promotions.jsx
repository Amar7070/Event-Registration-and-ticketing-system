import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  Sparkles, 
  Search, 
  Check, 
  X, 
  Plus, 
  TrendingUp, 
  Activity, 
  Trash2, 
  Image, 
  History, 
  Calendar, 
  HelpCircle 
} from 'lucide-react';

export default function Promotions() {
  const [promotions, setPromotions] = useState([]);
  const [plans, setPlans] = useState([]);
  const [upcomingEvents, setUpcomingEvents] = useState([]);
  const [stats, setStats] = useState({
    totalEarned: 0,
    pendingCount: 0,
    activeCount: 0
  });
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState('queue');

  // Modal states for pricing package CRUD
  const [planModalOpen, setPlanModalOpen] = useState(false);
  const [editMode, setEditMode] = useState(false);
  const [editingPlan, setEditingPlan] = useState(null);
  
  const [planName, setPlanName] = useState('');
  const [planDescription, setPlanDescription] = useState('');
  const [planPrice, setPlanPrice] = useState(0);
  const [planDuration, setPlanDuration] = useState(7);
  const [planActive, setPlanActive] = useState(true);
  const [planSubmitting, setPlanSubmitting] = useState(false);

  useEffect(() => {
    fetchPromotionsData();
  }, []);

  const fetchPromotionsData = async () => {
    try {
      setLoading(true);
      const res = await api.get('/admin/promotions');
      setPromotions(res.data.promotions || []);
      setPlans(res.data.plans || []);
      setUpcomingEvents(res.data.upcomingEvents || []);
      if (res.data.stats) {
        setStats(res.data.stats);
      }
    } catch (err) {
      console.error('Failed to load promotions directory:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleApprove = async (promo) => {
    if (!window.confirm(`Approve campaign for "${promo.event?.title || 'this event'}" and activate in slideshow?`)) return;
    try {
      const res = await api.post(`/admin/promotions/${promo.id}/approve`);
      alert(res.data.message);
      fetchPromotionsData();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to approve promotion.');
    }
  };

  const handleReject = async (promo) => {
    if (!window.confirm(`Reject campaign request for "${promo.event?.title || 'this event'}"?`)) return;
    try {
      const res = await api.post(`/admin/promotions/${promo.id}/reject`);
      alert(res.data.message);
      fetchPromotionsData();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to reject promotion.');
    }
  };

  const handleAddToSlideshow = async (event) => {
    try {
      const res = await api.post(`/admin/promotions/events/${event.id}/add`);
      alert(res.data.message);
      fetchPromotionsData();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to manually add event.');
    }
  };

  const handleRemoveFromSlideshow = async (event) => {
    try {
      const res = await api.post(`/admin/promotions/events/${event.id}/remove`);
      alert(res.data.message);
      fetchPromotionsData();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to manually remove event.');
    }
  };

  const handleOpenPlanModal = (plan = null) => {
    if (plan) {
      setEditMode(true);
      setEditingPlan(plan);
      setPlanName(plan.name || '');
      setPlanDescription(plan.description || '');
      setPlanPrice(plan.price || 0);
      setPlanDuration(plan.duration_days || 7);
      setPlanActive(!!plan.is_active);
    } else {
      setEditMode(false);
      setEditingPlan(null);
      setPlanName('');
      setPlanDescription('');
      setPlanPrice(0);
      setPlanDuration(7);
      setPlanActive(true);
    }
    setPlanModalOpen(true);
  };

  const handlePlanSubmit = async (e) => {
    e.preventDefault();
    setPlanSubmitting(true);
    try {
      const payload = {
        name: planName,
        description: planDescription,
        price: parseFloat(planPrice),
        duration_days: parseInt(planDuration),
        is_active: planActive
      };

      let res;
      if (editMode && editingPlan) {
        res = await api.put(`/admin/promotion-plans/${editingPlan.id}`, payload);
      } else {
        res = await api.post('/admin/promotion-plans', payload);
      }
      alert(res.data.message);
      setPlanModalOpen(false);
      fetchPromotionsData();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to save showcase package plan.');
    } finally {
      setPlanSubmitting(false);
    }
  };

  const handleDeletePlan = async (plan) => {
    if (!window.confirm(`Purge showcase plan "${plan.name}" from ecosystem?`)) return;
    try {
      const res = await api.delete(`/admin/promotion-plans/${plan.id}`);
      alert(res.data.message);
      fetchPromotionsData();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to delete package plan.');
    }
  };

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="pb-6 border-b border-slate-100 dark:border-slate-800">
          <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">SHOWCASE ADVERTISING SYSTEM</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Ecosystem Showcase Advertising</h1>
          <p className="text-xs text-slate-400 mt-1">Govern active slideshow showcases, manage paid host placements, and adjust advertising prices.</p>
        </div>

        {/* Metrics Ledger Grid */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2rem] shadow-sm relative overflow-hidden group">
            <span className="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Total Ad Revenue</span>
            <div className="text-3xl font-serif font-black text-slate-900 dark:text-white mb-2">₹{stats.totalEarned.toLocaleString('en-IN')}</div>
            <div className="text-[9px] font-black text-[#4E7D5B] uppercase tracking-widest flex items-center gap-1">
              <TrendingUp className="w-3.5 h-3.5" /> Ads Exchange Nominal
            </div>
          </div>

          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2rem] shadow-sm relative overflow-hidden group">
            <span className="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Pending Requests</span>
            <div className="text-3xl font-serif font-black text-slate-900 dark:text-white mb-2">{stats.pendingCount}</div>
            <div className="text-[9px] font-black text-amber-500 uppercase tracking-widest flex items-center gap-1">
              <Activity className="w-3.5 h-3.5" /> Awaiting Review
            </div>
          </div>

          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2rem] shadow-sm relative overflow-hidden group">
            <span className="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Active Slide Blueprints</span>
            <div className="text-3xl font-serif font-black text-primary mb-2">{stats.activeCount}</div>
            <div className="text-[9px] font-black text-primary uppercase tracking-widest flex items-center gap-1">
              <Sparkles className="w-3.5 h-3.5 animate-pulse" /> Showcase Live Nodes
            </div>
          </div>
        </div>

        {/* Main Content Layout with Tabs */}
        <div className="flex items-center gap-6 border-b border-slate-100 dark:border-slate-800 pb-px mb-8">
          <button
            onClick={() => setActiveTab('queue')}
            className={`pb-4 text-[10px] font-black uppercase tracking-widest border-b-2 transition-colors shrink-0 ${
              activeTab === 'queue' ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'
            }`}
          >
            Showcase Queue
          </button>
          <button
            onClick={() => setActiveTab('manual')}
            className={`pb-4 text-[10px] font-black uppercase tracking-widest border-b-2 transition-colors shrink-0 ${
              activeTab === 'manual' ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'
            }`}
          >
            Manual Controls
          </button>
          <button
            onClick={() => setActiveTab('plans')}
            className={`pb-4 text-[10px] font-black uppercase tracking-widest border-b-2 transition-colors shrink-0 ${
              activeTab === 'plans' ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'
            }`}
          >
            Pricing Packages
          </button>
        </div>

        <div className="w-full">
          
          {/* Tab 1: Request Queue */}
          {activeTab === 'queue' && (
            <section className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm animate-fade-in">
              <div className="p-8 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold">Showcase Requests Queue</h3>
                <p className="text-xs text-slate-400 mt-1">Review host paid campaigns and assign them slots in the homepage slideshow carousel.</p>
              </div>

              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse">
                  <thead>
                    <tr className="bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800">
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Event</th>
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Plan</th>
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Yield</th>
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Showcase End</th>
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 dark:divide-slate-800/50">
                    {promotions.length > 0 ? (
                      promotions.map((promo) => (
                        <tr key={promo.id} className="group hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-all duration-300">
                          <td className="px-8 py-6">
                            <div className="flex items-center gap-3">
                              <img src={promo.event?.banner || 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800'} alt="" className="w-10 h-10 rounded-xl object-cover" />
                              <div>
                                <span className="font-bold text-slate-900 dark:text-white text-xs block leading-tight">{promo.event?.title || 'Deleted Event'}</span>
                                <span className="block text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Host: {promo.event?.organizer?.name || 'Unknown'}</span>
                              </div>
                            </div>
                          </td>

                          <td className="px-8 py-6 text-xs text-slate-700 dark:text-slate-300">
                            <span className="font-bold block">{promo.plan?.name || 'Showcase Ad'}</span>
                            <span className="text-[9px] text-primary font-bold uppercase tracking-wider">Duration: {promo.plan?.duration_days} Days</span>
                          </td>

                          <td className="px-8 py-6">
                            <span className="font-serif text-[#4E7D5B] font-black text-xs">₹{promo.amount_paid.toLocaleString('en-IN')}</span>
                          </td>

                          <td className="px-8 py-6 text-xs text-slate-500 font-mono">
                            {promo.status === 'approved' && promo.end_date ? (
                              <span className="font-bold text-slate-700 dark:text-slate-350">{new Date(promo.end_date).toLocaleDateString('en-US')}</span>
                            ) : (
                              <span className="italic text-slate-400">Not Active</span>
                            )}
                          </td>

                          <td className="px-8 py-6">
                            {promo.status === 'pending' ? (
                              <span className="status-pill bg-amber-50 text-amber-600 border border-amber-100 animate-pulse tracking-wider">AWAITING</span>
                            ) : promo.status === 'approved' ? (
                              new Date(promo.end_date) > new Date() ? (
                                <span className="status-pill bg-emerald-50 text-emerald-600 border border-emerald-100 tracking-wider">SHOWCASE LIVE</span>
                              ) : (
                                <span className="status-pill bg-slate-100 text-slate-400 border border-slate-205 tracking-wider">EXPIRED</span>
                              )
                            ) : (
                              <span className="status-pill bg-rose-50 text-rose-500 border border-rose-100 tracking-wider">REJECTED</span>
                            )}
                          </td>

                          <td className="px-8 py-6 text-right">
                            {promo.status === 'pending' ? (
                              <div className="flex gap-2 justify-end">
                                <button 
                                  onClick={() => handleApprove(promo)} 
                                  className="px-3.5 py-1.5 bg-[#4E7D5B] hover:bg-[#3D6449] text-white rounded-lg text-[9px] font-black uppercase tracking-wider transition-colors cursor-pointer"
                                >
                                  Approve
                                </button>
                                <button 
                                  onClick={() => handleReject(promo)} 
                                  className="px-3.5 py-1.5 border border-rose-250 text-rose-500 hover:bg-rose-50 rounded-lg text-[9px] font-black uppercase tracking-wider transition-colors cursor-pointer"
                                >
                                  Reject
                                </button>
                              </div>
                            ) : (
                              <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">ARCHIVED</span>
                            )}
                          </td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan="6" className="px-8 py-20 text-center font-serif text-slate-400 text-xs italic">
                          <History className="w-10 h-10 mx-auto text-slate-300 mb-4" />
                          No showcase requests in queue.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </section>
          )}

          {/* Tab 2: Manual Slide Controls */}
          {activeTab === 'manual' && (
            <section className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm animate-fade-in">
              <div className="p-8 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold">Slideshow Display Control (Manual Override)</h3>
                <p className="text-xs text-slate-400 mt-1">Directly feature or unfeature upcoming event banners on the home hero slider.</p>
              </div>

              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse">
                  <thead>
                    <tr className="bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800">
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Event</th>
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Category</th>
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Start Date</th>
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Slideshow Status</th>
                      <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 dark:divide-slate-800/50">
                    {upcomingEvents.length > 0 ? (
                      upcomingEvents.map((event) => {
                        const isInSlideshow = !!event.is_featured;
                        return (
                          <tr key={event.id} className="group hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-all duration-300">
                            <td className="px-8 py-6">
                              <div className="flex items-center gap-3">
                                <img src={event.banner || 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800'} alt="" className="w-10 h-10 rounded-xl object-cover" />
                                <div>
                                  <span className="font-bold text-slate-900 dark:text-white text-xs block leading-tight">{event.title}</span>
                                  <span className="block text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Host: {event.organizer?.name || 'Unknown'}</span>
                                </div>
                              </div>
                            </td>

                            <td className="px-8 py-6 text-xs text-slate-700 dark:text-slate-350">
                              {event.category?.name || 'Experience'}
                            </td>

                            <td className="px-8 py-6 text-xs text-slate-500 font-mono">
                              {new Date(event.start_date).toLocaleDateString('en-US')}
                            </td>

                            <td className="px-8 py-6">
                              {isInSlideshow ? (
                                <span className="status-pill bg-[#4E7D5B]/10 text-[#4E7D5B] border border-[#4E7D5B]/20 tracking-wider">MANUALLY FEATURED</span>
                              ) : (
                                <span className="status-pill bg-slate-100 text-slate-400 border border-slate-200 tracking-wider">STANDARD LISTING</span>
                              )}
                            </td>

                            <td className="px-8 py-6 text-right">
                              {isInSlideshow ? (
                                <button 
                                  onClick={() => handleRemoveFromSlideshow(event)}
                                  className="px-3.5 py-1.5 border border-rose-250 text-rose-500 hover:bg-rose-50 rounded-lg text-[9px] font-black uppercase tracking-wider transition-colors cursor-pointer"
                                >
                                  Unfeature
                                </button>
                              ) : (
                                <button 
                                  onClick={() => handleAddToSlideshow(event)}
                                  className="px-3.5 py-1.5 bg-[#4E7D5B] hover:bg-[#3D6449] text-white rounded-lg text-[9px] font-black uppercase tracking-wider transition-colors cursor-pointer"
                                >
                                  Feature on Carousel
                                </button>
                              )}
                            </td>
                          </tr>
                        );
                      })
                    ) : (
                      <tr>
                        <td colSpan="5" className="px-8 py-20 text-center font-serif text-slate-400 text-xs italic">
                          No upcoming events found.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </section>
          )}

          {/* Tab 3: Packages pricing CRUD */}
          {activeTab === 'plans' && (
            <section className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-[2.5rem] shadow-sm animate-fade-in">
              <div className="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold leading-tight">Showcase Plans</h3>
                  <p className="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Configure paid ad placements</p>
                </div>
                <button 
                  onClick={() => handleOpenPlanModal()}
                  className="w-8 h-8 rounded-full bg-[#4E7D5B]/10 hover:bg-[#4E7D5B]/20 text-[#4E7D5B] transition-all flex items-center justify-center cursor-pointer"
                >
                  <Plus className="w-4 h-4" />
                </button>
              </div>

              <div className="space-y-6">
                {plans.length > 0 ? (
                  plans.map((plan) => (
                    <div key={plan.id} className="p-6 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-slate-100 dark:border-slate-800 text-xs space-y-3 relative overflow-hidden group">
                      <div className="flex items-start justify-between">
                        <div>
                          <h4 className="font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors text-sm">{plan.name}</h4>
                          <span className="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5 block">DURATION: {plan.duration_days} DAYS</span>
                        </div>
                        <span className="font-serif text-[#4E7D5B] font-black text-base">₹{plan.price}</span>
                      </div>

                      <p className="text-slate-450 dark:text-slate-400 leading-normal">{plan.description || 'No description provided.'}</p>

                      <div className="flex items-center justify-between pt-3 border-t border-slate-200/50 dark:border-slate-800">
                        <span className={`inline-flex px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border ${
                          plan.is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100'
                        }`}>
                          {plan.is_active ? 'ACTIVE' : 'SUSPENDED'}
                        </span>
                        
                        <div className="flex gap-4">
                          <button 
                            onClick={() => handleOpenPlanModal(plan)}
                            className="text-[9px] font-black text-primary hover:text-primary-hover uppercase tracking-widest cursor-pointer"
                          >
                            Modify
                          </button>
                          <button 
                            onClick={() => handleDeletePlan(plan)}
                            className="text-[9px] font-black text-rose-500 hover:text-rose-600 uppercase tracking-widest cursor-pointer"
                          >
                            Delete
                          </button>
                        </div>
                      </div>
                    </div>
                  ))
                ) : (
                  <p className="text-xs font-serif italic text-slate-400 text-center py-8">No pricing plans defined yet.</p>
                )}
              </div>
            </section>
          )}

        </div>

        {/* Pricing Plan Modal */}
        {planModalOpen && (
          <div className="fixed inset-0 z-[120] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all animate-fade-in text-left">
            <div className="relative w-full max-w-lg bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-[2.5rem] p-10 overflow-hidden">
              <form onSubmit={handlePlanSubmit} className="space-y-6">
                
                <div className="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                  <div className="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                    <Sparkles className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="text-2xl font-serif text-slate-900 dark:text-white font-bold">{editMode ? 'Modify Showcase Plan' : 'Construct Showcase Plan'}</h3>
                    <p className="text-[9px] text-slate-400 font-mono tracking-widest uppercase mt-0.5">Ecosystem Billing Strategy</p>
                  </div>
                </div>

                <div className="space-y-4">
                  <div>
                    <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Plan Name</label>
                    <input 
                      type="text" 
                      required 
                      value={planName}
                      onChange={(e) => setPlanName(e.target.value)}
                      placeholder="e.g. Spotlight Banner"
                      className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none" 
                    />
                  </div>

                  <div>
                    <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Description</label>
                    <textarea 
                      rows="3" 
                      value={planDescription}
                      onChange={(e) => setPlanDescription(e.target.value)}
                      placeholder="Describe placement and display details..."
                      className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none" 
                    ></textarea>
                  </div>

                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Price (₹)</label>
                      <input 
                        type="number" 
                        required 
                        min="0"
                        value={planPrice}
                        onChange={(e) => setPlanPrice(parseFloat(e.target.value))}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none" 
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Duration (Days)</label>
                      <input 
                        type="number" 
                        required 
                        min="1"
                        value={planDuration}
                        onChange={(e) => setPlanDuration(parseInt(e.target.value))}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none" 
                      />
                    </div>
                  </div>

                  {editMode && (
                    <div className="pt-4 border-t border-slate-50 dark:border-slate-800">
                      <label className="flex items-center gap-4 cursor-pointer group">
                        <div className="relative">
                          <input 
                            type="checkbox" 
                            checked={planActive}
                            onChange={(e) => setPlanActive(e.target.checked)}
                            className="sr-only peer" 
                          />
                          <div className="w-12 h-6 bg-slate-100 dark:bg-slate-950 rounded-full peer-checked:bg-primary transition-colors border border-slate-200 dark:border-slate-800"></div>
                          <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6 shadow-sm"></div>
                        </div>
                        <div>
                          <span className="block text-sm font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors leading-none">Enabled in Ecosystem</span>
                          <span className="block text-[9px] text-slate-400 uppercase tracking-widest mt-1">Allow host organizers to purchase this package plan</span>
                        </div>
                      </label>
                    </div>
                  )}

                </div>

                <div className="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-4 shrink-0">
                  <button 
                    type="button" 
                    onClick={() => setPlanModalOpen(false)} 
                    className="px-6 py-3 border border-slate-205 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:border-slate-350 transition-all cursor-pointer bg-white dark:bg-slate-900 rounded-xl"
                  >
                    Cancel
                  </button>
                  <button 
                    type="submit" 
                    disabled={planSubmitting}
                    className="btn-primary px-8 py-3 text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-primary/10 disabled:opacity-50 cursor-pointer"
                  >
                    {planSubmitting ? 'Saving...' : 'Apply Modifications'}
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
