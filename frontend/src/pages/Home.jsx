import React, { useState, useEffect, useRef } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../services/api';
import Navbar from '../components/Navbar';
import { 
  Ticket, MapPin, Calendar, ArrowRight, Search, Star, 
  ShieldCheck, Zap, Activity, Users, Globe, Layout, ChevronRight, ChevronLeft
} from 'lucide-react';

export default function Home() {
  const navigate = useNavigate();
  
  // New backend data
  const [data, setData] = useState({
    featured_events: [],
    categories: [],
    top_organizers: [],
    testimonials: [],
    stats: { tickets_sold: 0, active_organizers: 0, total_events: 0, total_cities: 0 }
  });
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');
  const [activeSlide, setActiveSlide] = useState(0);

  // Auto-slide for featured events
  useEffect(() => {
    if (data.featured_events.length === 0) return;
    const interval = setInterval(() => {
      setActiveSlide((prev) => (prev + 1) % data.featured_events.length);
    }, 6000);
    return () => clearInterval(interval);
  }, [data.featured_events]);

  const fallbackImages = [
    'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1475721028313-0a6311667b93?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&w=500&q=80'
  ];

  // Simulator State (From previous version)
  const [ticketStep, setTicketStep] = useState(1);
  const [selectedTier, setSelectedTier] = useState('regular');
  const [guestName, setGuestName] = useState('');
  const [guestEmail, setGuestEmail] = useState('');
  const [bookingSuccess, setBookingSuccess] = useState(false);
  
  useEffect(() => {
    async function fetchHomeData() {
      try {
        const response = await api.get('/home');
        setData(response.data);
      } catch (err) {
        console.error('Failed to load home data', err);
      } finally {
        setLoading(false);
      }
    }
    fetchHomeData();
  }, []);

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      navigate(`/events?search=${encodeURIComponent(searchQuery.trim())}`);
    }
  };

  const handleSimulatedBooking = () => {
    if (ticketStep === 1) {
      setTicketStep(2);
    } else if (ticketStep === 2) {
      if (guestName && guestEmail) {
        setTicketStep(3);
        setBookingSuccess(true);
      }
    } else if (ticketStep === 3) {
      setTicketStep(1);
      setBookingSuccess(false);
      setGuestName('');
      setGuestEmail('');
    }
  };

  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden selection:bg-[#4E7D5B] selection:text-white">
      <Navbar />

      {/* Cinematic Ambient Glow Orbs */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div className="absolute top-[10%] right-[5%] w-[600px] h-[600px] bg-[#4E7D5B]/10 dark:bg-[#4E7D5B]/15 rounded-full blur-[140px]"></div>
        <div className="absolute top-[40%] left-[2%] w-[500px] h-[500px] bg-amber-500/10 dark:bg-[#4E7D5B]/10 rounded-full blur-[120px]"></div>
      </div>

      {/* Hero Section (Original Interactive + Premium touches) */}
      <section className="relative min-h-[85vh] pt-24 md:pt-32 pb-20 flex items-center overflow-hidden z-10">
        <div className="max-w-[1440px] mx-auto px-6 md:px-12 w-full">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
            
            {/* Left Panel: Simulated Interactive Pass Booking */}
            <div className="relative w-full order-2 lg:order-1 animate-slide-up">
              <div className="absolute -inset-4 bg-gradient-to-tr from-[#4E7D5B]/30 to-amber-500/20 rounded-[3.5rem] blur-2xl opacity-60 dark:opacity-40"></div>
              <div className="relative w-full max-w-lg mx-auto bg-slate-900 dark:bg-slate-900 border border-slate-700/50 rounded-[3rem] p-6 shadow-2xl overflow-hidden flex flex-col min-h-[580px]">
                
                {/* Monochromatic background image */}
                <div className="relative h-[280px] w-full rounded-[2.2rem] overflow-hidden group shadow-lg">
                  <img 
                    src="/tech_summit_lobby.png" 
                    onError={(e) => { e.target.src = 'https://images.unsplash.com/photo-1540575861501-7ad05823c23d?auto=format&fit=crop&q=80&w=1000' }}
                    className="absolute inset-0 w-full h-full object-cover transition-transform duration-[4000ms] group-hover:scale-105 opacity-90 mix-blend-overlay" 
                    alt="Global Tech Summit Lobby" 
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
                  
                  {/* Status overlay */}
                  <div className="absolute top-6 left-6 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-black/60 backdrop-blur-md shadow-md border border-white/10">
                    <span className="flex h-2 w-2 relative">
                      <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span className="text-[9px] font-black uppercase tracking-widest text-emerald-400">LIVE SUMMIT SUMMONS</span>
                  </div>
                </div>

                {/* Multi-step progress details */}
                <div className="flex-1 flex flex-col justify-between pt-6 px-4">
                  
                  <div className="w-full flex items-center justify-between pb-6 border-b border-white/10">
                    <button onClick={() => !bookingSuccess && setTicketStep(1)} className="flex items-center gap-2 outline-none text-left cursor-pointer group">
                      <div className={`w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all ${
                        ticketStep >= 1 ? 'bg-emerald-500 text-slate-900 shadow-[0_0_15px_rgba(16,185,129,0.5)]' : 'border border-slate-600 text-slate-500'
                      }`}>1</div>
                      <span className={`text-[9px] font-black uppercase tracking-widest transition-colors ${ticketStep >= 1 ? 'text-white' : 'text-slate-500'}`}>Tickets</span>
                    </button>
                    <div className="flex-1 h-px bg-slate-700/50 mx-3"></div>
                    <button onClick={() => !bookingSuccess && guestName !== '' && setTicketStep(2)} className={`flex items-center gap-2 outline-none text-left ${guestName === '' ? 'cursor-not-allowed opacity-40' : 'cursor-pointer'}`}>
                      <div className={`w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all ${
                        ticketStep >= 2 ? 'bg-emerald-500 text-slate-900 shadow-[0_0_15px_rgba(16,185,129,0.5)]' : 'border border-slate-600 text-slate-500'
                      }`}>2</div>
                      <span className={`text-[9px] font-black uppercase tracking-widest transition-colors ${ticketStep >= 2 ? 'text-white' : 'text-slate-500'}`}>Details</span>
                    </button>
                    <div className="flex-1 h-px bg-slate-700/50 mx-3"></div>
                    <div className={`flex items-center gap-2 ${ticketStep < 3 ? 'opacity-40' : ''}`}>
                      <div className={`w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all ${
                        ticketStep >= 3 ? 'bg-emerald-500 text-slate-900 shadow-[0_0_15px_rgba(16,185,129,0.5)]' : 'border border-slate-600 text-slate-500'
                      }`}>3</div>
                      <span className={`text-[9px] font-black uppercase tracking-widest transition-colors ${ticketStep >= 3 ? 'text-white' : 'text-slate-500'}`}>Pass</span>
                    </div>
                  </div>

                  {/* Wizard content */}
                  <div className="pt-6 flex-1 flex flex-col justify-center text-left">
                    {ticketStep === 1 && (
                      <div className="space-y-4 animate-fade-in">
                        <h3 className="text-2xl font-serif text-white mb-2 leading-tight tracking-tight">Select Experience Tier</h3>
                        <p className="text-xs text-slate-400 mb-4">Book your seat at the Global Tech Summit 2026.</p>
                        
                        <div className="space-y-3">
                          <label className={`flex items-center justify-between p-4 rounded-2xl border cursor-pointer hover:bg-slate-800 transition-all ${
                            selectedTier === 'regular' ? 'border-emerald-500 bg-emerald-500/10' : 'border-slate-700 bg-slate-800/50'
                          }`}>
                            <div className="flex items-center gap-4">
                              <span className={`w-4 h-4 rounded-full border flex items-center justify-center transition-colors ${selectedTier === 'regular' ? 'border-emerald-500' : 'border-slate-500'}`}>
                                {selectedTier === 'regular' && <span className="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)]"></span>}
                              </span>
                              <div>
                                <span className="block text-xs font-bold text-white uppercase tracking-wider">Regular Pass</span>
                                <span className="text-[10px] text-slate-400 mt-1 block">Full access to tracks & lobbies</span>
                              </div>
                            </div>
                            <span className="text-sm font-black text-white">₹1,499</span>
                          </label>

                          <label className={`flex items-center justify-between p-4 rounded-2xl border cursor-pointer hover:bg-slate-800 transition-all ${
                            selectedTier === 'vip' ? 'border-amber-500 bg-amber-500/10' : 'border-slate-700 bg-slate-800/50'
                          }`}>
                            <div className="flex items-center gap-4">
                              <span className={`w-4 h-4 rounded-full border flex items-center justify-center transition-colors ${selectedTier === 'vip' ? 'border-amber-500' : 'border-slate-500'}`}>
                                {selectedTier === 'vip' && <span className="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.8)]"></span>}
                              </span>
                              <div>
                                <span className="block text-xs font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-200 to-amber-500 uppercase tracking-wider">VIP Key Access</span>
                                <span className="text-[9px] text-emerald-400 font-black uppercase tracking-widest mt-1 block">24 Tickets Left</span>
                              </div>
                            </div>
                            <span className="text-sm font-black text-amber-400">₹4,999</span>
                          </label>
                        </div>
                      </div>
                    )}

                    {ticketStep === 2 && (
                      <div className="space-y-4 animate-fade-in">
                        <h3 className="text-2xl font-serif text-white mb-2 leading-tight tracking-tight">Attendee Information</h3>
                        <p className="text-xs text-slate-400 mb-4">Provide details for your secure QR access key.</p>
                        
                        <div className="space-y-4">
                          <input 
                            type="text" 
                            placeholder="Attendee Name" 
                            value={guestName}
                            onChange={(e) => setGuestName(e.target.value)}
                            className="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 rounded-xl text-sm text-white placeholder:text-slate-500 focus:border-emerald-500 focus:bg-slate-800 outline-none transition-colors" 
                          />
                          <input 
                            type="email" 
                            placeholder="Your Email Address" 
                            value={guestEmail}
                            onChange={(e) => setGuestEmail(e.target.value)}
                            className="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 rounded-xl text-sm text-white placeholder:text-slate-500 focus:border-emerald-500 focus:bg-slate-800 outline-none transition-colors" 
                          />
                        </div>
                      </div>
                    )}

                    {ticketStep === 3 && (
                      <div className="space-y-4 text-center animate-fade-in">
                        <div className="w-24 h-24 bg-white rounded-2xl mx-auto flex items-center justify-center p-4 border-4 border-emerald-400 shadow-[0_0_30px_rgba(16,185,129,0.3)] relative overflow-hidden">
                          <div className="absolute inset-0 bg-gradient-to-tr from-emerald-500/20 to-transparent"></div>
                          <svg xmlns="http://www.w3.org/2000/svg" className="w-full h-full text-slate-900 relative z-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="3" height="3" rx="0.5" />
                            <rect x="17" y="17" width="4" height="4" rx="0.5" />
                          </svg>
                        </div>
                        <h3 className="text-3xl font-serif text-white leading-tight tracking-tight mt-4">Booking Secured!</h3>
                        <div className="inline-block px-3 py-1 bg-emerald-500/10 rounded-full border border-emerald-500/20 text-[10px] uppercase font-black tracking-widest text-emerald-400 my-2">REF: SE-2026-T402</div>
                        <p className="text-xs text-slate-400 max-w-[250px] mx-auto leading-relaxed">
                          Welcome, <span className="font-bold text-white">{guestName}</span>! Your QR code has been generated and sent to <span className="font-bold text-white">{guestEmail}</span>.
                        </p>
                      </div>
                    )}
                  </div>

                  {/* Actions */}
                  <div className="w-full pb-2 pt-8">
                    <button 
                      onClick={handleSimulatedBooking}
                      disabled={ticketStep === 2 && (!guestName || !guestEmail)}
                      className={`w-full bg-gradient-to-r from-emerald-500 to-[#4E7D5B] text-white py-4.5 rounded-full text-xs font-black uppercase tracking-[0.2em] shadow-[0_10px_30px_rgba(78,125,91,0.3)] flex items-center justify-center gap-3 hover:scale-[1.02] active:scale-95 transition-all outline-none cursor-pointer ${
                        ticketStep === 2 && (!guestName || !guestEmail) ? 'opacity-50 cursor-not-allowed saturate-0' : ''
                      }`}
                    >
                      <span>{ticketStep === 1 ? 'Continue to Details' : (ticketStep === 2 ? 'Generate Secure Pass' : 'Book Another Seat')}</span>
                      <ArrowRight className="w-4 h-4 stroke-[3]" />
                    </button>
                  </div>

                </div>
              </div>
            </div>

            {/* Right Panel: Content */}
            <div className="flex flex-col justify-center order-1 lg:order-2 text-left z-10 relative">
              <div className="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-[#4E7D5B]/10 dark:bg-[#4E7D5B]/20 border border-[#4E7D5B]/20 w-fit mb-8 backdrop-blur-sm">
                <span className="w-2 h-2 rounded-full bg-[#4E7D5B] animate-pulse shadow-[0_0_10px_rgba(78,125,91,0.8)]"></span>
                <span className="text-[10px] font-black uppercase tracking-[0.3em] text-[#4E7D5B] dark:text-[#8ac99d]">AI-Powered Ticketing & Registrations</span>
              </div>

              <h1 className="text-6xl md:text-7xl lg:text-8xl font-serif text-slate-900 dark:text-white leading-[1.05] tracking-tight mb-8">
                Host <br />
                <span className="text-transparent bg-clip-text bg-gradient-to-r from-[#4E7D5B] to-emerald-500 italic relative inline-block">Beautiful, Seamless</span> <br />
                Experiences.
              </h1>

              <p className="text-lg md:text-xl text-slate-600 dark:text-slate-400 font-medium leading-relaxed max-w-xl mb-12">
                SmartEvent coordinates tickets, waitlists, secure QR access check-ins, and payments globally. Empowering premium organizers with automated registration workflows.
              </p>

              {/* Direct Search Bar */}
              <form onSubmit={handleSearchSubmit} className="relative max-w-md w-full group">
                <div className="absolute -inset-1.5 bg-gradient-to-r from-[#4E7D5B]/20 via-emerald-500/20 to-[#4E7D5B]/20 rounded-full blur-md opacity-50 group-focus-within:opacity-100 group-focus-within:blur-xl transition-all duration-500"></div>
                <div className="relative flex items-center bg-white dark:bg-slate-900/90 backdrop-blur-xl rounded-full border border-slate-200 dark:border-slate-700 shadow-xl p-2 overflow-hidden">
                  <div className="pl-5 text-slate-400">
                    <Search className="w-5 h-5" />
                  </div>
                  <input 
                    type="text" 
                    placeholder="Search experiences, cities..." 
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    required
                    className="flex-1 bg-transparent border-none text-sm md:text-base py-4 px-4 placeholder:text-slate-400 text-slate-800 dark:text-slate-100 font-medium outline-none focus:ring-0" 
                  />
                  <button type="submit" className="bg-slate-900 dark:bg-[#4E7D5B] hover:bg-slate-800 dark:hover:bg-[#3d6348] text-white px-8 py-4 rounded-full text-[10px] md:text-xs font-black uppercase tracking-[0.2em] shadow-md hover:scale-[1.02] transition-all cursor-pointer shrink-0">
                    Scan Ecosystem
                  </button>
                </div>
              </form>
            </div>

          </div>
        </div>
      </section>

      {/* Platform Stats Marquee */}
      <section className="py-12 bg-white dark:bg-slate-900 border-y border-slate-100 dark:border-slate-800 relative z-10 overflow-hidden shadow-sm">
        <div className="max-w-[1440px] mx-auto px-6">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-100 dark:divide-slate-800 text-center">
            <div className="p-4 transform hover:-translate-y-1 transition-transform">
              <div className="text-4xl md:text-5xl font-black mb-2 text-slate-800 dark:text-white font-serif">{data.stats.tickets_sold.toLocaleString()}</div>
              <div className="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Tickets Sold</div>
            </div>
            <div className="p-4 transform hover:-translate-y-1 transition-transform">
              <div className="text-4xl md:text-5xl font-black mb-2 text-[#4E7D5B] font-serif">{data.stats.active_organizers.toLocaleString()}</div>
              <div className="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Active Organizers</div>
            </div>
            <div className="p-4 transform hover:-translate-y-1 transition-transform">
              <div className="text-4xl md:text-5xl font-black mb-2 text-slate-800 dark:text-white font-serif">{data.stats.total_events.toLocaleString()}</div>
              <div className="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Global Events</div>
            </div>
            <div className="p-4 transform hover:-translate-y-1 transition-transform">
              <div className="text-4xl md:text-5xl font-black mb-2 text-amber-500 font-serif">{data.stats.total_cities.toLocaleString()}</div>
              <div className="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Cities Reached</div>
            </div>
          </div>
        </div>
      </section>

      {/* Categories Grid */}
      {data.categories && data.categories.length > 0 && (
        <section className="py-32 max-w-[1440px] mx-auto px-6 relative z-10">
          <div className="text-center mb-20">
            <span className="text-[#4E7D5B] text-[10px] font-black uppercase tracking-[0.3em] block mb-4">Explore By Interest</span>
            <h2 className="text-4xl md:text-6xl font-serif font-bold text-slate-900 dark:text-white">Curated Categories</h2>
          </div>
          
          <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            {data.categories.map((cat, idx) => (
              <Link to={`/events?category=${cat.slug}`} key={cat.id} className="group relative h-48 rounded-[2rem] overflow-hidden bg-white dark:bg-slate-900 shadow-lg border border-slate-100 dark:border-slate-800 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                <div className="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent z-10 transition-opacity group-hover:opacity-80"></div>
                <img src={cat.image_url || fallbackImages[idx % fallbackImages.length]} alt={cat.name} className="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale group-hover:grayscale-0" />
                <div className="absolute bottom-0 left-0 w-full p-6 z-20 text-center">
                  <span className="text-white font-bold block text-sm md:text-base tracking-wide">{cat.name}</span>
                </div>
              </Link>
            ))}
          </div>
        </section>
      )}

      {/* Featured Events Horizontal Track */}
      {data.featured_events && data.featured_events.length > 0 && (
        <section className="py-32 bg-slate-100/50 dark:bg-slate-900 relative z-10 border-y border-slate-200 dark:border-slate-800">
          <div className="max-w-[1440px] mx-auto px-6">
            <div className="flex flex-col md:flex-row md:justify-between md:items-end mb-16 gap-6">
              <div>
                <span className="text-rose-500 text-[10px] font-black uppercase tracking-[0.3em] block mb-4">Don't Miss Out</span>
                <h2 className="text-4xl md:text-6xl font-serif font-bold text-slate-900 dark:text-white">Trending Events</h2>
              </div>
              <Link to="/events" className="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-black uppercase tracking-widest text-slate-800 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                View All <ArrowRight className="w-4 h-4" />
              </Link>
            </div>

            {/* Slides Container */}
            <div className="relative w-full rounded-[2rem] overflow-hidden aspect-[21/10] md:aspect-[21/8] lg:aspect-[21/7] shadow-2xl border border-slate-100 dark:border-slate-800 bg-slate-900 group">
              {data.featured_events.map((evt, index) => (
                <div 
                  key={evt.id}
                  className={`absolute inset-0 w-full h-full bg-cover bg-center transition-all duration-1000 ${
                    index === activeSlide ? 'opacity-100 scale-100' : 'opacity-0 scale-95 pointer-events-none'
                  }`}
                  style={{ backgroundImage: `url('${evt.banner || 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'}')` }}
                >
                  {/* Dark Gradient Overlay */}
                  <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-transparent"></div>
                  <div className="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/20 to-transparent"></div>

                  {/* Slide Contents */}
                  <div className="absolute bottom-0 left-0 w-full p-8 md:p-12 lg:p-16 flex flex-col justify-end h-full text-left space-y-4 max-w-3xl z-10">
                    <div className="flex flex-wrap items-center gap-3">
                      <span className="px-3.5 py-1 rounded-full bg-[#4E7D5B] text-white text-[9px] font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/30">
                        Trending
                      </span>
                      <span className="px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-[0.2em] border border-white/10">
                        {evt.category?.name || 'Experience'}
                      </span>
                    </div>

                    <h2 className="text-3xl md:text-5xl lg:text-6xl font-serif text-white font-bold leading-tight tracking-tight line-clamp-2">
                      {evt.title}
                    </h2>

                    <div className="flex flex-wrap items-center gap-6 text-slate-300 text-xs md:text-sm">
                      <span className="flex items-center gap-1.5">
                        <Calendar className="w-4 h-4 text-[#4E7D5B]" />
                        {new Date(evt.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                      </span>
                      <span className="flex items-center gap-1.5">
                        <MapPin className="w-4 h-4 text-[#4E7D5B]" />
                        {evt.venue?.city || 'Online / Global'}
                      </span>
                    </div>

                    <div className="pt-3">
                      <Link to={`/events/${evt.slug}`} className="inline-flex items-center gap-2 bg-[#4E7D5B] hover:bg-[#3D6449] text-white px-8 py-4 rounded-full text-xs font-black uppercase tracking-widest transition shadow-lg shadow-[#4E7D5B]/20 cursor-pointer">
                        View Experience &rarr;
                      </Link>
                    </div>
                  </div>
                </div>
              ))}

              {/* Left Arrow */}
              <button 
                onClick={() => setActiveSlide((prev) => (prev - 1 + data.featured_events.length) % data.featured_events.length)}
                className="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-slate-950/40 hover:bg-[#4E7D5B] text-white backdrop-blur-md flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 z-20 border border-white/10 cursor-pointer"
              >
                <ChevronLeft className="w-5 h-5" />
              </button>

              {/* Right Arrow */}
              <button 
                onClick={() => setActiveSlide((prev) => (prev + 1) % data.featured_events.length)}
                className="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-slate-950/40 hover:bg-[#4E7D5B] text-white backdrop-blur-md flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 z-20 border border-white/10 cursor-pointer"
              >
                <ChevronRight className="w-5 h-5" />
              </button>

              {/* Slide Indicators */}
              <div className="absolute bottom-8 right-8 flex items-center gap-2 px-4 py-2 rounded-full bg-black/40 backdrop-blur-sm z-20 border border-white/5">
                {data.featured_events.map((_, index) => (
                  <button 
                    key={index}
                    onClick={() => setActiveSlide(index)}
                    className={`transition-all duration-500 rounded-full cursor-pointer ${
                      activeSlide === index ? 'w-6 h-1.5 bg-[#4E7D5B]' : 'w-1.5 h-1.5 bg-white/40 hover:bg-white/85'
                    }`}
                  ></button>
                ))}
              </div>
            </div>
          </div>
        </section>
      )}

      {/* Bento Box Features */}
      <section className="py-32 max-w-[1440px] mx-auto px-6 relative z-10">
        <div className="text-center mb-20">
          <span className="text-indigo-500 text-[10px] font-black uppercase tracking-[0.3em] block mb-4">Why Choose SmartEvent</span>
          <h2 className="text-4xl md:text-6xl font-serif font-bold text-slate-900 dark:text-white max-w-3xl mx-auto leading-[1.1]">Everything you need to host, manage, and attend flawlessly.</h2>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 auto-rows-[300px]">
          {/* Feature 1 */}
          <div className="md:col-span-2 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-900/50 rounded-[3rem] p-10 md:p-12 border border-slate-200 dark:border-slate-800 shadow-xl flex flex-col justify-between overflow-hidden relative group">
            <div className="absolute right-0 bottom-0 opacity-5 dark:opacity-10 group-hover:scale-110 transition-transform duration-[1000ms] origin-bottom-right">
              <Ticket className="w-96 h-96 text-[#4E7D5B]" />
            </div>
            <div className="w-16 h-16 bg-[#4E7D5B]/10 rounded-2xl flex items-center justify-center mb-6 border border-[#4E7D5B]/20 backdrop-blur-sm relative z-10">
              <Zap className="w-8 h-8 text-[#4E7D5B]" />
            </div>
            <div className="relative z-10">
              <h3 className="text-3xl font-serif font-bold text-slate-900 dark:text-white mb-4">Instant Secure Ticketing</h3>
              <p className="text-slate-600 dark:text-slate-400 text-base md:text-lg max-w-md leading-relaxed">Generate cryptographic QR codes instantly upon booking. Fully secure check-ins with zero friction for both attendees and organizers.</p>
            </div>
          </div>
          
          {/* Feature 2 */}
          <div className="bg-gradient-to-bl from-indigo-50 to-white dark:from-indigo-950/20 dark:to-slate-900 rounded-[3rem] p-10 border border-indigo-100 dark:border-indigo-900/30 shadow-xl flex flex-col justify-between group overflow-hidden relative">
            <div className="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center mb-6 relative z-10 border border-indigo-500/20 backdrop-blur-sm">
              <Activity className="w-8 h-8 text-indigo-500" />
            </div>
            <div className="relative z-10">
              <h3 className="text-2xl font-serif font-bold text-slate-900 dark:text-white mb-4">Smart Waitlists</h3>
              <p className="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">Automated queue management. When a ticket drops, the next in line gets it instantly without manual intervention.</p>
            </div>
          </div>

          {/* Feature 3 */}
          <div className="bg-gradient-to-tr from-amber-50 to-white dark:from-amber-950/20 dark:to-slate-900 rounded-[3rem] p-10 border border-amber-100 dark:border-amber-900/30 shadow-xl flex flex-col justify-between group">
            <div className="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mb-6 border border-amber-500/20 backdrop-blur-sm transition-transform group-hover:scale-110">
              <ShieldCheck className="w-8 h-8 text-amber-500" />
            </div>
            <div>
              <h3 className="text-2xl font-serif font-bold text-slate-900 dark:text-white mb-4">Copyright Protection</h3>
              <p className="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">Premium organizers can file claims to protect their brand and event IP from copycats globally.</p>
            </div>
          </div>

          {/* Feature 4 */}
          <div className="md:col-span-2 bg-gradient-to-br from-rose-50 to-white dark:from-rose-950/20 dark:to-slate-900 rounded-[3rem] p-10 md:p-12 border border-rose-100 dark:border-rose-900/30 shadow-xl flex flex-col justify-between overflow-hidden relative group">
            <div className="w-16 h-16 bg-rose-500/10 rounded-2xl flex items-center justify-center mb-6 relative z-10 border border-rose-500/20 backdrop-blur-sm">
              <Globe className="w-8 h-8 text-rose-500" />
            </div>
            <div className="absolute right-0 bottom-0 opacity-5 dark:opacity-10 group-hover:rotate-12 transition-transform duration-[2000ms]">
              <Globe className="w-80 h-80 text-rose-500 translate-x-1/4 translate-y-1/4" />
            </div>
            <div className="relative z-10">
              <h3 className="text-3xl font-serif font-bold text-slate-900 dark:text-white mb-4">Global Audience Reach</h3>
              <p className="text-slate-600 dark:text-slate-400 text-base md:text-lg max-w-md leading-relaxed">Promote your events with featured placements and customized tracking links to reach thousands worldwide.</p>
            </div>
          </div>
        </div>
      </section>

      {/* Top Organizers */}
      {data.top_organizers && data.top_organizers.length > 0 && (
        <section className="py-32 bg-slate-50 dark:bg-slate-950 relative z-10 border-t border-slate-200 dark:border-slate-800">
          <div className="max-w-[1440px] mx-auto px-6 text-center">
            <span className="text-[#4E7D5B] text-[10px] font-black uppercase tracking-[0.3em] block mb-4">Community Leaders</span>
            <h2 className="text-4xl md:text-5xl font-serif font-bold text-slate-900 dark:text-white mb-20">Top Event Organizers</h2>
            
            <div className="grid grid-cols-2 md:grid-cols-4 gap-12">
              {data.top_organizers.map(org => (
                <div key={org.id} className="flex flex-col items-center group cursor-pointer">
                  <div className="relative w-40 h-40 rounded-full mb-6">
                    <div className="absolute -inset-2 bg-gradient-to-tr from-[#4E7D5B] to-emerald-400 rounded-full blur opacity-20 group-hover:opacity-60 transition-opacity duration-500"></div>
                    <div className="relative w-full h-full rounded-full overflow-hidden border-4 border-white dark:border-slate-900 shadow-2xl group-hover:scale-105 transition-transform duration-500 z-10 bg-slate-100 dark:bg-slate-800">
                      <img src={org.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(org.name)}&background=random`} alt={org.name} className="w-full h-full object-cover" />
                    </div>
                  </div>
                  <h4 className="text-xl font-bold text-slate-900 dark:text-white group-hover:text-[#4E7D5B] transition-colors">{org.name}</h4>
                  <p className="text-sm font-medium text-slate-500 dark:text-slate-400 mt-2 bg-slate-200 dark:bg-slate-800 px-4 py-1.5 rounded-full">{org.organized_events_count} Events Hosted</p>
                </div>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Testimonials */}
      {data.testimonials && data.testimonials.length > 0 && (
        <section className="py-32 max-w-[1440px] mx-auto px-6 relative z-10">
          <div className="text-center mb-20">
            <span className="text-amber-500 text-[10px] font-black uppercase tracking-[0.3em] block mb-4">Verified Feedback</span>
            <h2 className="text-4xl md:text-6xl font-serif font-bold text-slate-900 dark:text-white mb-6">Don't just take our word for it.</h2>
            <p className="text-slate-500 text-lg">Read authentic reviews from attendees worldwide.</p>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {data.testimonials.map(review => (
              <div key={review.id} className="bg-white dark:bg-slate-900 p-10 rounded-[3rem] shadow-xl border border-slate-100 dark:border-slate-800 flex flex-col justify-between hover:-translate-y-2 transition-transform duration-500 group">
                <div>
                  <div className="flex gap-1.5 mb-8">
                    {[...Array(review.rating)].map((_, i) => (
                      <Star key={i} className="w-5 h-5 fill-amber-400 text-amber-400 filter drop-shadow-sm group-hover:scale-110 transition-transform" style={{ transitionDelay: `${i * 50}ms` }} />
                    ))}
                  </div>
                  <p className="text-slate-700 dark:text-slate-300 font-medium text-lg mb-10 leading-relaxed italic">"{review.comment}"</p>
                </div>
                <div className="flex items-center gap-5 pt-6 border-t border-slate-100 dark:border-slate-800">
                  <img src={review.user?.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(review.user?.name || 'User')}&background=random`} className="w-14 h-14 rounded-full border-2 border-slate-200 dark:border-slate-700 shadow-md" alt="" />
                  <div>
                    <h5 className="text-base font-bold text-slate-900 dark:text-white">{review.user?.name}</h5>
                    <span className="text-[10px] text-slate-500 font-black uppercase tracking-widest line-clamp-1 mt-1 block">Attended: {review.event?.title}</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </section>
      )}

      {/* Dual CTA */}
      <section className="py-24 relative z-10 px-6">
        <div className="max-w-[1440px] mx-auto">
          <div className="bg-slate-950 rounded-[4rem] overflow-hidden shadow-2xl relative border border-slate-800 group">
            <div className="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&q=80&w=2000')] bg-cover bg-center opacity-40 mix-blend-luminosity group-hover:scale-105 transition-transform duration-[3000ms]"></div>
            <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-[#4E7D5B]/20"></div>
            
            <div className="relative p-16 md:p-24 flex flex-col md:flex-row items-center justify-between gap-16 text-center md:text-left">
              <div className="max-w-2xl">
                <span className="text-emerald-400 text-[10px] font-black uppercase tracking-[0.4em] block mb-6 shadow-sm">Your Stage Awaits</span>
                <h2 className="text-5xl md:text-7xl font-serif font-bold text-white mb-8 leading-[1.1]">Ready to create magic?</h2>
                <p className="text-slate-300 text-xl font-medium leading-relaxed">Join thousands of top-tier organizers hosting premium experiences on SmartEvent today.</p>
              </div>
              <div className="flex flex-col sm:flex-row gap-6 shrink-0 w-full md:w-auto">
                <Link to="/register?role=organizer" className="bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-10 py-5 rounded-full text-sm font-black uppercase tracking-widest transition-all hover:-translate-y-1 text-center shadow-[0_10px_30px_rgba(16,185,129,0.4)] flex-1 md:flex-none">
                  Host an Event
                </Link>
                <Link to="/events" className="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 px-10 py-5 rounded-full text-sm font-black uppercase tracking-widest transition-all hover:-translate-y-1 text-center flex-1 md:flex-none">
                  Explore Events
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
  );
}
