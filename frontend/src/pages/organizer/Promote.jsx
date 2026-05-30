import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  ArrowLeft,
  Sparkles,
  CreditCard,
  Wifi,
  ShieldCheck,
  Image,
  Calendar,
  MapPin,
  TrendingUp,
  History
} from 'lucide-react';

export default function Promote() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [event, setEvent] = useState(null);
  const [plans, setPlans] = useState([]);
  const [promotions, setPromotions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);

  // Form State
  const [selectedPlan, setSelectedPlan] = useState(null);
  const [selectedPrice, setSelectedPrice] = useState(0);
  const [selectedDuration, setSelectedDuration] = useState(0);

  const [cardName, setCardName] = useState('');
  const [cardNumber, setCardNumber] = useState('');
  const [cardExpiry, setCardExpiry] = useState('');
  const [cardCvv, setCardCvv] = useState('');

  const [successMsg, setSuccessMsg] = useState('');
  const [errorMsg, setErrorMsg] = useState('');

  useEffect(() => {
    fetchPromotionData();
  }, [id]);

  const fetchPromotionData = async () => {
    try {
      setLoading(true);
      const res = await api.get(`/organizer/events/${id}/promote`);
      setEvent(res.data.event);
      setPlans(res.data.plans || []);
      setPromotions(res.data.promotions || []);
      if (res.data.event && res.data.event.organizer_name) {
        setCardName(res.data.event.organizer_name);
      }
    } catch (err) {
      console.error(err);
      setErrorMsg('Failed to load promotion blueprints.');
    } finally {
      setLoading(false);
    }
  };

  const handleCardNumberChange = (e) => {
    let val = e.target.value.replace(/\D/g, '');
    let parts = [];
    for (let i = 0, len = val.length; i < len; i += 4) {
      parts.push(val.substring(i, i + 4));
    }
    setCardNumber(parts.length > 0 ? parts.join(' ') : val);
  };

  const selectPlan = (plan) => {
    setSelectedPlan(plan.id);
    setSelectedPrice(plan.price);
    setSelectedDuration(plan.duration_days);
    
    // Smooth scroll to checkout form
    setTimeout(() => {
      document.getElementById('checkout-simulator-form')?.scrollIntoView({ behavior: 'smooth' });
    }, 100);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!selectedPlan) {
      alert('Please select a showcase advertising plan.');
      return;
    }
    
    try {
      setSubmitting(true);
      setErrorMsg('');
      setSuccessMsg('');
      
      const res = await api.post(`/organizer/events/${id}/promote`, {
        plan_id: selectedPlan,
        card_number: cardNumber.replace(/\s/g, ''),
        card_expiry: cardExpiry,
        card_cvv: cardCvv
      });
      
      setSuccessMsg(res.data.message || 'Promotion plan purchased successfully!');
      // Reset form
      setSelectedPlan(null);
      setSelectedPrice(0);
      setSelectedDuration(0);
      setCardNumber('');
      setCardExpiry('');
      setCardCvv('');
      
      // Reload promotion list
      fetchPromotionData();
    } catch (err) {
      console.error(err);
      setErrorMsg(err.response?.data?.message || 'Simulated checkout processing encountered an error.');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <SidebarLayout type="organizer">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">Ecosystem Showcase Advertising</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Showcase Event Promotion</h1>
            <p className="text-xs text-slate-400 mt-1 italic font-serif">"Elevate your experience directly onto the premium SmartEvent Home page slideshow."</p>
          </div>

          <Link 
            to="/organizer/events"
            className="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 uppercase tracking-widest transition-colors"
          >
            <ArrowLeft className="w-4 h-4" />
            <span>Back to Blueprint Ledger</span>
          </Link>
        </div>

        {successMsg && (
          <div className="p-6 bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 rounded-[2rem] flex items-center gap-4 text-[#4E7D5B] animate-float">
            <div className="w-8 h-8 rounded-full bg-[#4E7D5B] text-white flex items-center justify-center shadow-lg shadow-[#4E7D5B]/20">
              <ShieldCheck className="w-4 h-4" />
            </div>
            <div className="text-xs font-bold uppercase tracking-wider leading-relaxed">
              {successMsg}
            </div>
          </div>
        )}

        {errorMsg && (
          <div className="p-6 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 rounded-[2rem] flex items-center gap-4 text-rose-600 dark:text-rose-400">
            <div className="text-xs font-bold uppercase tracking-wider leading-relaxed">
              {errorMsg}
            </div>
          </div>
        )}

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-450">Compiling advertising rates...</span>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 pb-32">
            
            {/* Left Side: Plan Selector and Purchase Form */}
            <div className="lg:col-span-8 space-y-12">
              
              {/* Plan Choices */}
              <section className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 md:p-10 shadow-sm">
                <div className="flex items-center gap-4 mb-8">
                  <div className="w-10 h-10 rounded-xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                    <Sparkles className="w-5 h-5" />
                  </div>
                  <div>
                    <h2 className="text-xl font-serif text-slate-900 dark:text-white">1. Select Showcase Plan</h2>
                    <p className="text-xs text-slate-450 font-bold uppercase tracking-widest mt-1">Admin curated premium landing page exposure tiers</p>
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                  {plans.map((plan) => (
                    <div 
                      key={plan.id}
                      onClick={() => selectPlan(plan)}
                      className={`border rounded-[2.5rem] p-8 flex flex-col justify-between gap-6 cursor-pointer transition-all duration-300 relative overflow-hidden group ${
                        selectedPlan === plan.id 
                          ? 'border-[#4E7D5B] bg-[#4E7D5B]/5 shadow-lg scale-[1.02]' 
                          : 'border-slate-100 dark:border-slate-800 hover:border-[#4E7D5B]/40 hover:scale-[1.01] bg-white dark:bg-slate-950'
                      }`}
                    >
                      {/* Highlight badge if active */}
                      <div className="absolute top-0 right-0 bg-[#4E7D5B] text-white text-[8px] font-black uppercase tracking-widest px-4 py-2 rounded-bl-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                        CHOOSE PACKAGE
                      </div>

                      <div className="text-left">
                        <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest block leading-none mb-3">DURATION: {plan.duration_days} DAYS</span>
                        <h3 className="text-lg font-serif text-slate-900 dark:text-white leading-snug group-hover:text-[#4E7D5B] transition-colors">{plan.name}</h3>
                        <p className="text-xs text-slate-400 mt-4 leading-relaxed line-clamp-3">{plan.description}</p>
                      </div>

                      <div className="pt-6 border-t border-slate-50 dark:border-slate-900 flex items-baseline gap-1 text-left">
                        <span className="text-3xl font-serif text-[#4E7D5B] tracking-tight">₹{plan.price.toLocaleString()}</span>
                        <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">INR</span>
                      </div>
                    </div>
                  ))}
                  {plans.length === 0 && (
                    <div className="col-span-3 text-center py-12 bg-slate-50 dark:bg-slate-950 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-slate-800 text-slate-400">
                      <p className="text-xs font-serif italic">No showcase pricing plans registered by the platform yet.</p>
                    </div>
                  )}
                </div>
              </section>

              {/* Checkout Panel (Awaiting plan selection) */}
              {selectedPlan !== null && (
                <section id="checkout-simulator-form" className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 md:p-10 shadow-sm">
                  <div className="flex items-center gap-4 mb-8">
                    <div className="w-10 h-10 rounded-xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                      <CreditCard className="w-5 h-5" />
                    </div>
                    <div>
                      <h2 className="text-xl font-serif text-slate-900 dark:text-white">2. Secure Sandbox Checkout</h2>
                      <p className="text-xs text-slate-450 font-bold uppercase tracking-widest mt-1">Simulate premium checkout mapping with zero-friction processing</p>
                    </div>
                  </div>

                  <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {/* Simulated Credit Card preview */}
                    <div className="flex flex-col justify-center">
                      <div className="w-full aspect-[1.586/1] bg-gradient-to-br from-slate-900 via-slate-800 to-[#4E7D5B] text-white rounded-[2rem] p-8 flex flex-col justify-between shadow-2xl shadow-slate-900/10 relative overflow-hidden group select-none text-left">
                        <div className="absolute inset-0 bg-[#FDFBF7]/5 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, #fff 0.8px, transparent 0.8px)', backgroundSize: '24px 24px' }}></div>
                        
                        <div className="relative z-10 flex justify-between items-start">
                          <div>
                            <span className="text-[9px] font-black text-white/40 uppercase tracking-[0.25em]">SMARTEVENT MERCHANT</span>
                            <h4 className="text-sm font-serif italic text-white/90 mt-1">Sandbox Gateway</h4>
                          </div>
                          <div className="w-10 h-8 bg-white/10 rounded-lg flex items-center justify-center backdrop-blur-md">
                            <Wifi className="w-4 h-4 text-white/60" />
                          </div>
                        </div>

                        <div className="relative z-10">
                          <span className="text-lg md:text-xl font-mono tracking-[0.18em] text-white/90">
                            {cardNumber ? cardNumber : '••••  ••••  ••••  ••••'}
                          </span>
                        </div>

                        <div className="relative z-10 flex justify-between items-end">
                          <div>
                            <span className="text-[8px] font-black text-white/40 uppercase tracking-widest block leading-none mb-1">CARDHOLDER</span>
                            <span className="text-xs font-mono uppercase tracking-wider text-white/90 truncate max-w-[150px] block">
                              {cardName ? cardName : 'CARDHOLDER NAME'}
                            </span>
                          </div>
                          <div className="flex gap-6">
                            <div>
                              <span className="text-[8px] font-black text-white/40 uppercase tracking-widest block leading-none mb-1">EXPIRY</span>
                              <span className="text-xs font-mono text-white/90">
                                {cardExpiry ? cardExpiry : 'MM/YY'}
                              </span>
                            </div>
                            <div>
                              <span className="text-[8px] font-black text-white/40 uppercase tracking-widest block leading-none mb-1">CVV</span>
                              <span className="text-xs font-mono text-white/90">
                                {cardCvv ? cardCvv : '•••'}
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    {/* Payment Inputs */}
                    <div className="space-y-6 text-left">
                      <div className="space-y-2">
                        <label className="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">Cardholder Name</label>
                        <input 
                          type="text" 
                          required 
                          value={cardName}
                          onChange={(e) => setCardName(e.target.value)}
                          className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-full text-xs text-slate-800 dark:text-slate-200 outline-none" 
                          placeholder="e.g. Host Organizer"
                        />
                      </div>

                      <div className="space-y-2">
                        <label className="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">Card Number</label>
                        <div className="relative">
                          <input 
                            type="text" 
                            required 
                            maxLength={19} 
                            value={cardNumber}
                            onChange={handleCardNumberChange}
                            className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-full text-xs text-slate-800 dark:text-slate-200 outline-none font-mono" 
                            placeholder="4111 2222 3333 4444"
                          />
                          <div className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <ShieldCheck className="w-4 h-4 text-[#4E7D5B]" />
                          </div>
                        </div>
                      </div>

                      <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                          <label className="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">Expiry Date</label>
                          <input 
                            type="text" 
                            required 
                            maxLength={5} 
                            value={cardExpiry}
                            onChange={(e) => setCardExpiry(e.target.value)}
                            className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-full text-xs text-slate-800 dark:text-slate-200 outline-none font-mono" 
                            placeholder="MM/YY"
                          />
                        </div>
                        <div className="space-y-2">
                          <label className="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">CVV Code</label>
                          <input 
                            type="password" 
                            required 
                            maxLength={4} 
                            value={cardCvv}
                            onChange={(e) => setCardCvv(e.target.value)}
                            className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-full text-xs text-slate-800 dark:text-slate-200 outline-none font-mono" 
                            placeholder="•••"
                          />
                        </div>
                      </div>
                    </div>

                    {/* Order Total Receipt */}
                    <div className="col-span-2 p-6 bg-slate-50 dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
                      <div className="text-left space-y-1">
                        <span className="text-[9px] font-black text-[#4E7D5B] uppercase tracking-[0.2em] block">Simulated billing breakdown</span>
                        <h4 className="text-sm font-bold text-slate-900 dark:text-white">Total Purchase: <span className="text-[#4E7D5B] font-serif font-black">₹{Number(selectedPrice).toLocaleString()}</span></h4>
                        <p className="text-[11px] text-slate-400">Valid for exactly <span className="font-bold text-slate-700 dark:text-slate-300">{selectedDuration}</span> days of cinematic slider showcase.</p>
                      </div>
                      <button 
                        type="submit" 
                        disabled={submitting}
                        className="inline-flex px-8 py-4 bg-[#4E7D5B] hover:bg-[#3C6347] active:scale-95 text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-[#4E7D5B]/20 transition-all outline-none cursor-pointer"
                      >
                        {submitting ? 'PROCESSING SIMULATION...' : 'PROCESS SIMULATED PAYMENT'}
                      </button>
                    </div>
                  </form>
                </section>
              )}

            </div>

            {/* Right Side: Sidebar Event Detail & Ledger Stats */}
            <div className="lg:col-span-4 space-y-8">
              <section className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm text-left">
                <div className="w-full aspect-[2/1] rounded-[1.5rem] bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-850 overflow-hidden mb-6 relative">
                  {event.banner ? (
                    <img src={event.banner} className="w-full h-full object-cover" alt={event.title} />
                  ) : (
                    <div className="w-full h-full bg-slate-100 dark:bg-slate-950 flex items-center justify-center text-slate-400">
                      <Image className="w-8 h-8" />
                    </div>
                  )}
                  <div className="absolute bottom-4 left-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md px-3 py-1.5 rounded-xl border border-slate-100 dark:border-slate-800">
                    <span className="text-[8px] font-black uppercase tracking-widest text-[#4E7D5B]">{event.category?.name}</span>
                  </div>
                </div>

                <h3 className="text-xl font-serif text-slate-900 dark:text-white leading-snug mb-3">{event.title}</h3>
                <p className="text-xs text-slate-450 leading-relaxed mb-6">{event.short_description}</p>

                <div className="space-y-4 pt-6 border-t border-slate-50 dark:border-slate-900 text-left">
                  <div className="flex justify-between items-center text-xs">
                    <span className="text-slate-400">Status:</span>
                    <span className="font-bold uppercase tracking-wider text-slate-650 dark:text-slate-350">{event.status}</span>
                  </div>
                  <div className="flex justify-between items-center text-xs">
                    <span className="text-slate-400">Launch Date:</span>
                    <span className="font-bold text-slate-800 dark:text-slate-200">
                      {new Date(event.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                    </span>
                  </div>
                </div>
              </section>

              <section className="bg-slate-900 text-white rounded-[2.5rem] p-8 relative overflow-hidden text-left">
                <div className="absolute inset-0 bg-[#FDFBF7]/5 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, #fff 0.5px, transparent 0.5px)', backgroundSize: '20px 20px' }}></div>
                
                <div className="relative z-10">
                  <span className="text-[9px] font-black text-white/40 uppercase tracking-[0.3em] block mb-2">METRIC FORECAST</span>
                  <h3 className="text-2xl font-serif text-[#98C2A7] tracking-tight mb-4">Promotional Impact</h3>
                  <p className="text-xs text-white/60 leading-relaxed mb-6">
                    Experience listings featured directly on the Home page slider enjoy up to <span class="text-white font-bold">12x higher click rates</span> and capture more waitlist sign-ups than standard search grids.
                  </p>
                  <div className="p-4 bg-white/5 rounded-2xl border border-white/10 flex items-center gap-3">
                    <div className="w-8 h-8 rounded-lg bg-[#4E7D5B] text-white flex items-center justify-center">
                      <TrendingUp className="w-4 h-4" />
                    </div>
                    <div>
                      <span className="text-[8px] font-black text-white/40 uppercase tracking-widest block leading-none">ESTIMATED VIEWS</span>
                      <span className="text-lg font-serif tracking-tight">+45,000 / week</span>
                    </div>
                  </div>
                </div>
              </section>
            </div>

            {/* Full Width Ledger of Showcase Requests */}
            <div className="lg:col-span-12">
              <section className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 md:p-10 shadow-sm text-left">
                <div className="flex items-center justify-between mb-8">
                  <div className="flex items-center gap-4">
                    <div className="w-10 h-10 rounded-xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                      <History className="w-5 h-5" />
                    </div>
                    <div>
                      <h2 className="text-xl font-serif text-slate-900 dark:text-white">Showcase Advertisement Request Ledger</h2>
                      <p className="text-xs text-slate-450 font-bold uppercase tracking-widest mt-1">Full historical audit logs of your featured event promotions</p>
                    </div>
                  </div>
                </div>

                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr className="border-b border-slate-100 dark:border-slate-800 text-slate-400 font-black uppercase tracking-widest">
                        <th className="pb-4">PLAN PACKAGE</th>
                        <th className="pb-4">COST PAID</th>
                        <th className="pb-4">PAYMENT STATUS</th>
                        <th className="pb-4">SHOWCASE STATUS</th>
                        <th className="pb-4">START DATE</th>
                        <th className="pb-4">END DATE</th>
                        <th className="pb-4">SUBMITTED ON</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 dark:divide-slate-850 font-medium">
                      {promotions.map((promo) => (
                        <tr key={promo.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-950/10">
                          <td className="py-6">
                            <span className="text-xs font-bold text-slate-900 dark:text-slate-100 block">{promo.plan?.name}</span>
                            <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Duration: {promo.plan?.duration_days} Days</span>
                          </td>
                          <td className="py-6">
                            <span className="text-xs font-serif font-black text-[#4E7D5B]">₹{Number(promo.amount_paid).toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                          </td>
                          <td className="py-6">
                            <span className="inline-flex px-3 py-1 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-900/30">
                              {promo.payment_status}
                            </span>
                          </td>
                          <td className="py-6">
                            {promo.status === 'pending' && (
                              <span className="inline-flex px-3 py-1 bg-amber-50 text-amber-700 dark:bg-amber-950/20 rounded-full text-[9px] font-black uppercase tracking-widest border border-amber-100 dark:border-amber-900/30 animate-pulse">
                                Awaiting Assignment
                              </span>
                            )}
                            {promo.status === 'approved' && (
                              <span className="inline-flex px-3 py-1 bg-[#4E7D5B]/10 text-[#4E7D5B] rounded-full text-[9px] font-black uppercase tracking-widest border border-[#4E7D5B]/20">
                                Showcase Active
                              </span>
                            )}
                            {promo.status === 'rejected' && (
                              <span className="inline-flex px-3 py-1 bg-rose-50 text-rose-700 dark:bg-rose-950/20 rounded-full text-[9px] font-black uppercase tracking-widest border border-rose-100 dark:border-rose-900/30">
                                Rejected / Refunded
                              </span>
                            )}
                            {promo.status === 'expired' && (
                              <span className="inline-flex px-3 py-1 bg-slate-100 text-slate-500 dark:bg-slate-900/20 rounded-full text-[9px] font-black uppercase tracking-widest border border-slate-200 dark:border-slate-800">
                                Expired
                              </span>
                            )}
                          </td>
                          <td className="py-6 text-xs text-slate-600 dark:text-slate-400">
                            {promo.start_date ? new Date(promo.start_date).toLocaleString() : 'Pending approval'}
                          </td>
                          <td className="py-6 text-xs text-slate-600 dark:text-slate-400">
                            {promo.end_date ? new Date(promo.end_date).toLocaleString() : 'Pending approval'}
                          </td>
                          <td className="py-6 text-xs text-slate-400">
                            {new Date(promo.created_at).toLocaleString()}
                          </td>
                        </tr>
                      ))}
                      {promotions.length === 0 && (
                        <tr>
                          <td colSpan="7" className="py-12 text-center text-xs font-serif italic text-slate-400">
                            No promotional requests registered for this event yet. Use the plans above to initiate showcase campaigns.
                          </td>
                        </tr>
                      )}
                    </tbody>
                  </table>
                </div>
              </section>
            </div>

          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
