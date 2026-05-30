import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import Navbar from '../components/Navbar';
import api from '../services/api';
import { 
  Sprout, 
  Timer, 
  MapPin, 
  Calendar, 
  Verified, 
  Star, 
  MessageSquare, 
  Lock, 
  Mail, 
  ShieldAlert, 
  Info, 
  LogIn, 
  X, 
  ChevronDown, 
  ArrowRight,
  Sparkles,
  AlertTriangle
} from 'lucide-react';

export default function EventDetail() {
  const { slug } = useParams();
  const navigate = useNavigate();
  
  const [event, setEvent] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [activeTab, setActiveTab] = useState('about');
  const [timeLeft, setTimeLeft] = useState(null);
  
  // Reviews & Claims State
  const [reviews, setReviews] = useState([]);
  const [reviewForm, setReviewForm] = useState({ rating: 5, comment: '' });
  
  // Form/Interactive State
  const [selectedType, setSelectedType] = useState(null);
  const [quantity, setQuantity] = useState(1);
  // Auth context helpers
  const token = localStorage.getItem('token') || localStorage.getItem('api_token');
  const [currentUser, setCurrentUser] = useState(null);

  useEffect(() => {
    async function fetchUser() {
      if (token) {
        try {
          const res = await api.get('/user');
          setCurrentUser(res.data);
        } catch (e) {
          console.error(e);
        }
      }
    }
    fetchUser();
  }, [token]);

  useEffect(() => {
    async function fetchEventDetail() {
      try {
        setLoading(true);
        const res = await api.get(`/events/${slug}`);
        const data = res.data.data;
        setEvent(data);
        if (data.ticket_types && data.ticket_types.length > 0) {
          setSelectedType(data.ticket_types[0].id);
        }
        setError(null);
        fetchReviews(data.id);
      } catch (err) {
        console.error("fetchEventDetail error:", err);
        setError(`Failed: ${err.message || err.toString()}`);
      } finally {
        setLoading(false);
      }
    }
    fetchEventDetail();
  }, [slug]);

  // Countdown timer logic
  useEffect(() => {
    if (!event) return;
    const target = new Date(event.start_date).getTime();
    
    const updateTime = () => {
      const now = new Date().getTime();
      const diff = target - now;
      if (diff < 0) {
        setTimeLeft('Started');
        return;
      }
      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      setTimeLeft(`${days}d ${hours}h ${minutes}m`);
    };

    updateTime();
    const timer = setInterval(updateTime, 60000);
    return () => clearInterval(timer);
  }, [event]);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[#FAF9F5] dark:bg-slate-950 transition-colors duration-500">
        <div className="flex flex-col items-center gap-4">
          <div className="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
          <p className="text-xs font-black uppercase tracking-widest text-slate-400">Loading experience blueprint...</p>
        </div>
      </div>
    );
  }

  if (error || !event) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[#FAF9F5] dark:bg-slate-950 px-6">
        <div className="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-10 rounded-[2.5rem] shadow-xl text-center">
          <div className="w-16 h-16 bg-red-50 dark:bg-red-950/20 rounded-2xl flex items-center justify-center text-red-500 mx-auto mb-6">
            <AlertTriangle className="w-8 h-8" />
          </div>
          <h2 className="text-2xl font-serif font-medium text-slate-900 dark:text-white mb-2">Blueprint Sync Failed</h2>
          <p className="text-sm text-slate-400 dark:text-slate-400 mb-6">{error || 'Event not found.'}</p>
          <Link to="/events" className="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest w-full text-center block">
            Return to Directory
          </Link>
        </div>
      </div>
    );
  }

  // Calculate ticket pricing
  const currentTicketType = event.ticket_types.find(t => t.id === selectedType);
  const ticketPrice = currentTicketType ? currentTicketType.price : 0;
  const isSoldOut = currentTicketType ? (currentTicketType.quantity_sold >= currentTicketType.quantity_total) : false;
  const totalCost = ticketPrice * quantity;

  // Handle Checkout / Booking
  const handleBookingSubmit = (e) => {
    e.preventDefault();
    if (isSoldOut) {
      // Handle join waitlist logic
      handleJoinWaitlist();
    } else {
      // Redirect to checkout wizard
      navigate(`/checkout?event=${event.slug}&ticket_type_id=${selectedType}&quantity=${quantity}`);
    }
  };

  const handleJoinWaitlist = async () => {
    if (!token) {
      navigate('/login');
      return;
    }
    try {
      const res = await api.post(`/events/${event.id}/waitlist`, {
        ticket_type_id: selectedType
      });
      alert(res.data.message || 'Successfully joined waitlist!');
      // Refresh event details to reflect registration state if needed
      const detailRes = await api.get(`/events/${slug}`);
      setEvent(detailRes.data.data);
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to join waitlist. Please check capacity.');
    }
  };

  async function fetchReviews(eventId) {
    try {
      const res = await api.get(`/events/${eventId}/reviews`);
      setReviews(res.data || []);
    } catch (err) {
      console.error('Failed to fetch reviews', err);
    }
  }

  const handleCopyrightClaim = async () => {
    const description = window.prompt("Please provide details for this copyright claim:");
    if (!description) return;

    try {
      const res = await api.post(`/events/${event.id}/claim`, { description });
      alert(res.data.message);
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to submit claim.');
    }
  };

  const handleSubmitReview = async (e) => {
    e.preventDefault();
    try {
      const res = await api.post(`/events/${event.id}/reviews`, reviewForm);
      alert(res.data.message);
      setReviewForm({ rating: 5, comment: '' });
      fetchReviews(event.id);
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to submit review.');
    }
  };


  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden">
      <Navbar />

      {/* Cinematic Hero Header */}
      <div className="relative min-h-[50vh] md:min-h-[60vh] lg:min-h-[65vh] w-full flex items-center bg-slate-950 overflow-hidden pt-24">
        {/* Background Image Blurry Backing */}
        <div 
          className="absolute inset-0 bg-cover bg-center scale-110 blur-xl opacity-30 select-none"
          style={{ backgroundImage: `url('${event.banner}')` }}
        ></div>
        
        {/* Dark Vignette Overlays */}
        <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-transparent"></div>
        <div className="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-transparent to-transparent"></div>

        <div className="max-w-[1440px] mx-auto w-full px-6 md:px-12 relative z-10 py-16">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            {/* Left: Large Cinematic Cover Frame */}
            <div className="lg:col-span-4 hidden lg:block">
              <div className="aspect-[3/4] w-full bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 group relative">
                <img 
                  src={event.banner} 
                  className="w-full h-full object-cover transition-transform duration-[2000ms] group-hover:scale-110" 
                  alt={event.title}
                />
                <div className="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
              </div>
            </div>

            {/* Right: Core Metadata & Title */}
            <div className="lg:col-span-8 space-y-6 text-left">
              <div className="flex flex-wrap items-center gap-3">
                <span className="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-[#4E7D5B] text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/20">
                  <Sprout className="w-3.5 h-3.5" />
                  {event.category}
                </span>
                
                {timeLeft && timeLeft !== 'Started' && (
                  <span className="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-[0.2em] border border-white/15">
                    <Timer className="w-3.5 h-3.5 text-[#4E7D5B]" />
                    Countdown: <span className="ml-1 text-white font-mono">{timeLeft}</span>
                  </span>
                )}
              </div>

              <h1 className="text-4xl md:text-5xl lg:text-6xl font-serif text-white leading-[1.1] tracking-tight max-w-4xl font-bold">
                {event.title}
              </h1>

              {/* Organizer and Basic Quick Specs */}
              <div className="flex flex-wrap items-center gap-8 pt-4">
                {/* Organizer Details */}
                <div className="flex items-center gap-3">
                  <div className="relative">
                    <img 
                      src={`https://ui-avatars.com/api/?name=${encodeURIComponent(event.organizer?.name || 'O')}&background=4E7D5B&color=fff`} 
                      className="w-11 h-11 rounded-xl border border-white/20 shadow-md"
                      alt="Organizer Avatar"
                    />
                    <div className="absolute -top-1 -right-1 w-4 h-4 bg-[#4E7D5B] rounded-full border border-slate-950 flex items-center justify-center">
                      <Verified className="w-2 h-2 text-white" />
                    </div>
                  </div>
                  <div>
                    <span className="block text-[9px] text-slate-400 uppercase tracking-widest leading-none mb-1">Architect</span>
                    <span className="text-sm font-bold text-white leading-none">{event.organizer?.name}</span>
                  </div>
                </div>

                <div className="h-8 w-[1px] bg-white/10 hidden sm:block"></div>

                {/* Basic Specifications */}
                <div className="flex items-center gap-3 text-slate-350">
                  <MapPin className="w-5 h-5 text-[#4E7D5B]" />
                  <span className="text-sm font-medium">{event.venue ? event.venue.city : 'Global (Online)'}</span>
                </div>

                <div className="h-8 w-[1px] bg-white/10 hidden sm:block"></div>

                <div className="flex items-center gap-3 text-slate-350">
                  <Calendar className="w-5 h-5 text-[#4E7D5B]" />
                  <span className="text-sm font-medium">{new Date(event.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                </div>

                {currentUser && (currentUser.role === 'organizer' || currentUser.role === 'admin') && currentUser.id !== event.organizer_id && (
                  <>
                    <div className="h-8 w-[1px] bg-white/10 hidden sm:block"></div>
                    <button 
                      onClick={handleCopyrightClaim}
                      className="flex items-center gap-2 text-rose-400 hover:text-rose-300 text-xs font-bold uppercase tracking-widest transition-colors cursor-pointer"
                    >
                      <ShieldAlert className="w-4 h-4" />
                      Report Copyright Issue
                    </button>
                  </>
                )}
              </div>
            </div>

          </div>
        </div>
      </div>

      {/* Main Workspace Grid */}
      <div className="max-w-[1440px] mx-auto px-6 md:px-12 py-16">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
          
          {/* Left: Interactive Content Area */}
          <div className="lg:col-span-8 space-y-12">
            
            {/* Modern Navigation Tabs */}
            <div className="border-b border-slate-200 dark:border-slate-800 flex gap-8 overflow-x-auto no-scrollbar">
              <button 
                onClick={() => setActiveTab('about')} 
                className={`pb-4 border-b-2 font-bold text-xs uppercase tracking-widest transition-all shrink-0 cursor-pointer ${
                  activeTab === 'about' ? 'border-[#4E7D5B] text-[#4E7D5B] dark:text-white' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-650 dark:hover:text-slate-300'
                }`}
              >
                Overview
              </button>
              {event.sessions && event.sessions.length > 0 && (
                <button 
                  onClick={() => setActiveTab('sessions')} 
                  className={`pb-4 border-b-2 font-bold text-xs uppercase tracking-widest transition-all shrink-0 cursor-pointer ${
                    activeTab === 'sessions' ? 'border-[#4E7D5B] text-[#4E7D5B] dark:text-white' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-650 dark:hover:text-slate-300'
                  }`}
                >
                  Timeline ({event.sessions.length})
                </button>
              )}
              {event.venue && (
                <button 
                  onClick={() => setActiveTab('venue')} 
                  className={`pb-4 border-b-2 font-bold text-xs uppercase tracking-widest transition-all shrink-0 cursor-pointer ${
                    activeTab === 'venue' ? 'border-[#4E7D5B] text-[#4E7D5B] dark:text-white' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-650 dark:hover:text-slate-300'
                  }`}
                >
                  Coordinates
                </button>
              )}
              <button 
                onClick={() => setActiveTab('reviews')} 
                className={`pb-4 border-b-2 font-bold text-xs uppercase tracking-widest transition-all shrink-0 cursor-pointer ${
                  activeTab === 'reviews' ? 'border-[#4E7D5B] text-[#4E7D5B] dark:text-white' : 'border-transparent text-slate-400 dark:text-slate-500 hover:text-slate-650 dark:hover:text-slate-300'
                }`}
              >
                Reviews ({reviews.length})
              </button>
            </div>

            {/* Tab Contents */}
            <div className="space-y-8">
              
              {/* TAB: Overview */}
              {activeTab === 'about' && (
                <div className="space-y-8 text-left animate-slide-up">
                  <div className="prose prose-slate dark:prose-invert max-w-none text-slate-650 dark:text-slate-400 leading-[1.8] text-base md:text-lg font-sans whitespace-pre-line">
                    {event.description}
                  </div>

                  {/* Speakers Grid inside Overview */}
                  {event.speakers && event.speakers.length > 0 && (
                    <div className="pt-6 border-t border-slate-100 dark:border-slate-800">
                      <h3 className="text-xl font-serif text-slate-900 dark:text-white mb-6 font-bold">Featured Guides</h3>
                      <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {event.speakers.map(speaker => (
                          <div key={speaker.id} className="flex gap-4 p-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-sm">
                            <img src={speaker.photo} alt={speaker.name} className="w-16 h-16 rounded-xl object-cover" />
                            <div className="text-left">
                              <h4 className="text-sm font-bold text-slate-900 dark:text-white">{speaker.name}</h4>
                              <p className="text-[10px] text-primary uppercase font-bold tracking-wider mt-0.5">{speaker.designation}</p>
                              <p className="text-xs text-slate-400 mt-1 line-clamp-2">{speaker.bio}</p>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}

                  {/* Sponsors inside Overview */}
                  {event.sponsors && event.sponsors.length > 0 && (
                    <div className="pt-8 border-t border-slate-100 dark:border-slate-800">
                      <h3 className="text-xl font-serif text-slate-900 dark:text-white mb-6 font-bold">Ecosystem Stewards</h3>
                      <div className="flex flex-wrap items-center gap-8">
                        {event.sponsors.map(sponsor => (
                          <div key={sponsor.id} className="grayscale hover:grayscale-0 transition-all duration-300">
                            {sponsor.logo ? (
                              <img src={sponsor.logo} alt={sponsor.name} className="h-10 object-contain max-w-[140px]" />
                            ) : (
                              <span className="text-sm font-bold uppercase tracking-wider text-slate-400">{sponsor.name}</span>
                            )}
                          </div>
                        ))}
                      </div>
                    </div>
                  )}
                </div>
              )}

              {/* TAB: Timeline / Sessions */}
              {activeTab === 'sessions' && event.sessions && (
                <div className="space-y-6 text-left animate-slide-up">
                  <div className="relative border-l-2 border-slate-100 dark:border-slate-800 ml-4 pl-8 space-y-12">
                    {event.sessions.map((session) => (
                      <div key={session.id} className="relative group">
                        {/* Timeline Node Bullet */}
                        <div className="absolute -left-[41px] top-1.5 w-5 h-5 rounded-full bg-[#FDFBF7] dark:bg-slate-950 border-4 border-[#4E7D5B] group-hover:scale-125 transition-transform duration-300 z-10"></div>
                        
                        <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                          <div className="flex flex-wrap items-center justify-between gap-4 mb-3 pb-3 border-b border-slate-50 dark:border-slate-850">
                            <div className="flex items-center gap-3">
                              <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black bg-[#4E7D5B]/10 text-[#4E7D5B] uppercase tracking-wider">
                                {new Date(session.start_time).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                              </span>
                              <span className="text-xs text-slate-400 font-medium">{session.room_or_track || 'Session Segment'}</span>
                            </div>
                          </div>
                          <h4 className="text-lg font-serif text-slate-900 dark:text-white group-hover:text-[#4E7D5B] transition-colors mb-2 font-bold">{session.title}</h4>
                          <p className="text-slate-500 dark:text-slate-450 text-xs md:text-sm leading-relaxed mb-4">{session.description}</p>
                          
                          {session.speaker && (
                            <div className="inline-flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl">
                              <img 
                                src={`https://ui-avatars.com/api/?name=${encodeURIComponent(session.speaker.name)}&background=4E7D5B&color=fff`} 
                                className="w-8 h-8 rounded-lg"
                                alt={session.speaker.name}
                              />
                              <div>
                                <span className="text-[8px] font-black text-slate-400 uppercase tracking-widest block leading-none mb-0.5">Speaker</span>
                                <span className="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-widest">{session.speaker.name}</span>
                              </div>
                            </div>
                          )}
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* TAB: Coordinates / Venue */}
              {activeTab === 'venue' && event.venue && (
                <div className="space-y-6 text-left animate-slide-up">
                  <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-sm space-y-6">
                    {/* Venue Header */}
                    <div className="flex items-start gap-4">
                      <div className="w-12 h-12 rounded-2xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B] shrink-0">
                        <MapPin className="w-6 h-6" />
                      </div>
                      <div className="flex-1">
                        <h4 className="text-lg font-serif font-bold text-slate-900 dark:text-white leading-tight">{event.venue.name}</h4>
                        <p className="text-xs text-slate-400 dark:text-slate-500 font-sans mt-0.5">
                          {[event.venue.address, event.venue.city, event.venue.state, event.venue.country].filter(Boolean).join(', ')}
                        </p>
                        {event.venue.latitude && event.venue.longitude && (
                          <p className="text-[10px] text-[#4E7D5B] font-bold mt-1.5 font-mono">
                            {parseFloat(event.venue.latitude).toFixed(6)}, {parseFloat(event.venue.longitude).toFixed(6)}
                          </p>
                        )}
                      </div>
                    </div>

                    {/* Live Map Embed - shows real OSM map if coordinates exist */}
                    <div className="rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 shadow-inner">
                      {event.venue.latitude && event.venue.longitude ? (
                        <iframe
                          title="Event Venue Map"
                          src={`https://www.openstreetmap.org/export/embed.html?bbox=${parseFloat(event.venue.longitude)-0.008},${parseFloat(event.venue.latitude)-0.008},${parseFloat(event.venue.longitude)+0.008},${parseFloat(event.venue.latitude)+0.008}&layer=mapnik&marker=${event.venue.latitude},${event.venue.longitude}`}
                          width="100%"
                          height="300"
                          style={{ border: 0, display: 'block' }}
                          loading="lazy"
                        />
                      ) : (
                        <div className="aspect-[16/6] relative group">
                          <div className="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&q=80&w=1200')] bg-cover bg-center grayscale opacity-40"></div>
                          <div className="absolute inset-0 flex flex-col items-center justify-center gap-2">
                            <MapPin className="w-8 h-8 text-[#4E7D5B]" />
                            <p className="text-xs text-slate-500 font-bold">{event.venue.address}, {event.venue.city}</p>
                          </div>
                        </div>
                      )}
                    </div>

                    {/* Navigation Action Buttons */}
                    <div className="flex flex-wrap items-center gap-3">
                      {/* Google Maps - coordinate-based if available, else address-based */}
                      <a 
                        href={
                          event.venue.latitude && event.venue.longitude
                            ? `https://www.google.com/maps?q=${event.venue.latitude},${event.venue.longitude}&z=16`
                            : `https://maps.google.com/?q=${encodeURIComponent([event.venue.name, event.venue.address, event.venue.city].filter(Boolean).join(', '))}`
                        }
                        target="_blank" 
                        rel="noreferrer"
                        className="flex items-center gap-2.5 px-6 py-3 rounded-full bg-[#4E7D5B] text-white text-[10px] font-black uppercase tracking-widest hover:bg-[#3D6449] shadow-lg shadow-[#4E7D5B]/20 transition-all duration-300 hover:scale-105"
                      >
                        <svg className="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        Open in Google Maps
                      </a>

                      {/* Apple Maps */}
                      <a 
                        href={
                          event.venue.latitude && event.venue.longitude
                            ? `https://maps.apple.com/?ll=${event.venue.latitude},${event.venue.longitude}&q=${encodeURIComponent(event.venue.name)}`
                            : `https://maps.apple.com/?q=${encodeURIComponent([event.venue.name, event.venue.address, event.venue.city].filter(Boolean).join(', '))}`
                        }
                        target="_blank" 
                        rel="noreferrer"
                        className="flex items-center gap-2.5 px-6 py-3 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-[10px] font-black uppercase tracking-widest hover:border-[#4E7D5B] hover:text-[#4E7D5B] transition-all duration-300"
                      >
                        <svg className="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        Apple Maps
                      </a>

                      {/* OSM / Waze directions */}
                      {event.venue.latitude && event.venue.longitude && (
                        <a 
                          href={`https://www.waze.com/ul?ll=${event.venue.latitude},${event.venue.longitude}&navigate=yes`}
                          target="_blank" 
                          rel="noreferrer"
                          className="flex items-center gap-2.5 px-6 py-3 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-[10px] font-black uppercase tracking-widest hover:border-[#4E7D5B] hover:text-[#4E7D5B] transition-all duration-300"
                        >
                          Navigate via Waze
                        </a>
                      )}
                    </div>
                  </div>
                </div>
              )}

              {/* TAB: Reviews */}
              {activeTab === 'reviews' && (
                <div className="space-y-8 text-left animate-slide-up">
                  {/* Reviews List */}
                  <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-sm">
                    <h3 className="text-xl font-serif text-slate-900 dark:text-white mb-6 font-bold">Attendee Resonance</h3>
                    
                    {reviews.length === 0 ? (
                      <div className="text-center py-10 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                        <MessageSquare className="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-3" />
                        <p className="text-sm font-medium text-slate-500">No reviews yet for this event.</p>
                      </div>
                    ) : (
                      <div className="space-y-6">
                        {reviews.map((review) => (
                          <div key={review.id} className="pb-6 border-b border-slate-100 dark:border-slate-800 last:border-0 last:pb-0">
                            <div className="flex items-center gap-3 mb-3">
                              <div className="w-8 h-8 rounded-full bg-[#4E7D5B]/10 text-[#4E7D5B] flex items-center justify-center font-bold text-xs">
                                {(review.user?.name || 'A').substring(0, 1)}
                              </div>
                              <div>
                                <h5 className="font-bold text-sm text-slate-900 dark:text-white">{review.user?.name || 'Attendee'}</h5>
                                <div className="flex items-center gap-1 mt-1">
                                  {[1, 2, 3, 4, 5].map((star) => (
                                    <Star 
                                      key={star} 
                                      className={`w-3 h-3 ${star <= review.rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200 dark:text-slate-700'}`} 
                                    />
                                  ))}
                                  <span className="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-2">
                                    {new Date(review.created_at).toLocaleDateString()}
                                  </span>
                                </div>
                              </div>
                            </div>
                            {review.comment && (
                              <p className="text-sm text-slate-600 dark:text-slate-400 leading-relaxed ml-11">
                                {review.comment}
                              </p>
                            )}
                          </div>
                        ))}
                      </div>
                    )}
                  </div>

                  {/* Submit Review Form (for logged in attendees) */}
                  {currentUser && (
                    <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-sm">
                      <h3 className="text-xl font-serif text-slate-900 dark:text-white mb-6 font-bold">Leave a Review</h3>
                      <form onSubmit={handleSubmitReview} className="space-y-5">
                        <div>
                          <label className="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Rating</label>
                          <div className="flex gap-2">
                            {[1, 2, 3, 4, 5].map((star) => (
                              <button
                                type="button"
                                key={star}
                                onClick={() => setReviewForm(prev => ({ ...prev, rating: star }))}
                                className="focus:outline-none"
                              >
                                <Star 
                                  className={`w-8 h-8 transition-colors ${
                                    star <= reviewForm.rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200 dark:text-slate-700 hover:text-amber-200'
                                  }`} 
                                />
                              </button>
                            ))}
                          </div>
                        </div>
                        <div>
                          <label className="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Comment (Optional)</label>
                          <textarea 
                            value={reviewForm.comment}
                            onChange={(e) => setReviewForm(prev => ({ ...prev, comment: e.target.value }))}
                            className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4 text-sm focus:border-[#4E7D5B] focus:ring-1 focus:ring-[#4E7D5B] outline-none min-h-[100px]"
                            placeholder="Share your experience..."
                          />
                        </div>
                        <button 
                          type="submit"
                          className="bg-[#4E7D5B] hover:bg-[#3D6449] text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors"
                        >
                          Submit Review
                        </button>
                        <p className="text-[10px] text-slate-400 mt-3 italic">
                          Note: You must have a ticket for this event to submit a review.
                        </p>
                      </form>
                    </div>
                  )}
                </div>
              )}

            </div>
          </div>

          {/* Right: High-Fidelity Interactive Ticket Stub */}
          <div className="lg:col-span-4 shrink-0 w-full lg:max-w-sm">
            <div className="sticky top-32 space-y-8">
              
              {/* Reservation Ticket Card */}
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] shadow-xl relative overflow-hidden">
                {/* Top Decorative Color bar */}
                <div className="h-3.5 w-full bg-[#4E7D5B] shadow-inner"></div>

                <div className="p-8 space-y-6 text-left">
                  <div>
                    <span className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Secure Entry</span>
                    <h3 className="text-2xl font-serif text-slate-900 dark:text-white leading-tight font-bold">Resonator Ticket</h3>
                  </div>

                  <form onSubmit={handleBookingSubmit} className="space-y-6">
                    
                    {/* Ticket Type Options */}
                    <div className="space-y-3.5">
                      {event.ticket_types && event.ticket_types.map((type) => {
                        const localSoldOut = type.quantity_sold >= type.quantity_total;
                        return (
                          <label 
                            key={type.id}
                            className={`block relative p-5 rounded-2xl border-2 cursor-pointer transition-all duration-300 group overflow-hidden ${
                              selectedType === type.id 
                                ? 'border-[#4E7D5B] bg-[#4E7D5B]/5 shadow-sm' 
                                : 'border-slate-100 dark:border-slate-800 hover:border-[#4E7D5B]/20 hover:bg-slate-50/50'
                            }`}
                          >
                            <input 
                              type="radio" 
                              name="ticket_type_id" 
                              value={type.id} 
                              className="sr-only" 
                              checked={selectedType === type.id}
                              onChange={() => {
                                setSelectedType(type.id);
                                setQuantity(1);
                              }}
                            />
                            
                            <div className="flex justify-between items-center relative z-10 gap-3">
                              <div className="flex-1">
                                <div className={`font-bold text-xs uppercase tracking-wider mb-0.5 ${
                                  selectedType === type.id ? 'text-[#4E7D5B]' : 'text-slate-500'
                                }`}>
                                  {type.name}
                                </div>
                                <div className="text-[11px] text-slate-400 dark:text-slate-400 leading-normal font-sans">
                                  {type.description || 'Access tier node'}
                                </div>
                                {localSoldOut && (
                                  <span className="inline-block mt-2 px-2 py-0.5 bg-amber-500/10 border border-amber-500/25 text-amber-600 rounded text-[8px] font-black uppercase tracking-wider">
                                    Sold Out (Waitlist Available)
                                  </span>
                                )}
                              </div>
                              <div className={`text-lg font-serif font-bold text-right ${
                                selectedType === type.id ? 'text-[#4E7D5B]' : 'text-slate-900 dark:text-white'
                              }`}>
                                {type.price === 0 ? 'Free' : `₹${type.price.toLocaleString('en-IN')}`}
                              </div>
                            </div>
                          </label>
                        );
                      })}
                    </div>

                    {/* Quantity Input */}
                    {!isSoldOut && (
                      <div className="flex items-center justify-between py-3.5 border-t border-b border-dashed border-slate-200 dark:border-slate-800">
                        <div>
                          <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Quantity</span>
                          <span className="text-[11px] font-serif italic text-slate-450 dark:text-slate-500">Select ticket nodes</span>
                        </div>
                        <div className="relative group">
                          <select 
                            value={quantity} 
                            onChange={(e) => setQuantity(parseInt(e.target.value))}
                            className="appearance-none bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-855 focus:border-[#4E7D5B] focus:ring-0 text-xs font-bold text-slate-800 dark:text-slate-200 py-2.5 pl-5 pr-9 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                          >
                            {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map((i) => (
                              <option key={i} value={i}>{i} {i > 1 ? 'NODES' : 'NODE'}</option>
                            ))}
                          </select>
                          <div className="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 transition-colors group-hover:text-[#4E7D5B]">
                            <ChevronDown className="w-3.5 h-3.5" />
                          </div>
                        </div>
                      </div>
                    )}

                    {/* Realtime Calculation Subtotal Display */}
                    {!isSoldOut && (
                      <div className="space-y-2 pt-2 text-xs">
                        <div className="flex justify-between items-center text-slate-500">
                          <span>Subtotal ({quantity} {quantity > 1 ? 'nodes' : 'node'})</span>
                          <span className="font-bold text-slate-850 dark:text-slate-200">
                            ₹{totalCost.toLocaleString('en-IN')}
                          </span>
                        </div>
                        <div className="flex justify-between items-center text-slate-500">
                          <span>Booking Fee</span>
                          <span className="font-semibold text-emerald-600">Free</span>
                        </div>
                        <div className="flex justify-between items-end pt-3 border-t border-slate-100 dark:border-slate-800">
                          <span className="font-bold uppercase tracking-wider text-slate-900 dark:text-white">Estimated total</span>
                          <span className="text-2xl font-serif font-bold text-[#4E7D5B]">
                            ₹{totalCost.toLocaleString('en-IN')}
                          </span>
                        </div>
                      </div>
                    )}

                    {isSoldOut ? (
                      <button 
                        type="button"
                        onClick={handleJoinWaitlist}
                        className="w-full bg-amber-500 hover:bg-amber-600 text-white py-4.5 px-6 rounded-full text-xs font-black uppercase tracking-[0.25em] transition-all duration-300 shadow-lg shadow-amber-500/20 active:scale-95 cursor-pointer"
                      >
                        JOIN QUEUE WAITLIST &rarr;
                      </button>
                    ) : (
                      <button 
                        type="submit" 
                        className="w-full bg-[#4E7D5B] hover:bg-[#3D6449] text-white py-4.5 px-6 rounded-full text-xs font-black uppercase tracking-[0.25em] transition-all duration-300 shadow-lg shadow-[#4E7D5B]/20 active:scale-95 cursor-pointer"
                      >
                        CONFIRM RESERVATION &rarr;
                      </button>
                    )}
                  </form>

                  <div className="flex items-center justify-center gap-2 text-[9px] font-black text-slate-350 dark:text-slate-650 uppercase tracking-widest pt-2">
                    <Lock className="w-3.5 h-3.5 text-[#4E7D5B]" />
                    SECURED BY GROUNDED ENTERPRISE
                  </div>
                </div>
              </div>

              {/* Organizer Info Card */}
              <div className="bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 p-6 flex items-center justify-between group cursor-pointer hover:bg-white dark:hover:bg-slate-900 hover:shadow-lg transition-all duration-500 rounded-3xl">
                <div className="flex items-center gap-4 text-left">
                  <div className="relative">
                    <img 
                      src={`https://ui-avatars.com/api/?name=${encodeURIComponent(event.organizer?.name || 'O')}&background=4E7D5B&color=fff`} 
                      className="w-12 h-12 rounded-xl shadow-sm group-hover:scale-105 transition-transform duration-500"
                      alt="Organizer Profile"
                    />
                    <div className="absolute -top-1 -right-1 w-4 h-4 bg-[#4E7D5B] rounded-full border border-white flex items-center justify-center">
                      <Verified className="w-2 h-2 text-white" />
                    </div>
                  </div>
                  <div>
                    <div className="text-[9px] text-slate-400 font-black uppercase tracking-widest leading-none mb-1.5">PRINCIPAL ARCHITECT</div>
                    <div className="text-base font-serif text-slate-900 dark:text-white group-hover:text-[#4E7D5B] transition-colors leading-none font-bold">{event.organizer?.name}</div>
                  </div>
                </div>
                <div className="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 flex items-center justify-center text-slate-400 group-hover:text-[#4E7D5B] group-hover:border-[#4E7D5B] group-hover:rotate-12 transition-all duration-500 shadow-sm">
                  <Mail className="w-4 h-4" />
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>

      {/* Recommendations Section */}
      {event.recommended_events && event.recommended_events.length > 0 && (
        <section className="py-20 px-6 md:px-12 bg-slate-50 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-800 overflow-hidden">
          <div className="max-w-[1440px] mx-auto">
            <div className="flex items-end justify-between mb-12">
              <div className="text-left">
                <span className="text-[#4E7D5B] text-[10px] font-black uppercase tracking-[0.4em] mb-3 block">RESONANCE MAP</span>
                <h2 className="text-3xl md:text-4xl font-serif text-slate-900 dark:text-white font-bold">Related Architectures</h2>
              </div>
              <Link to="/events" className="text-[10px] font-black text-slate-400 hover:text-[#4E7D5B] uppercase tracking-widest transition-colors">
                VIEW ALL NODES &rarr;
              </Link>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              {event.recommended_events.map((rec) => (
                <Link 
                  key={rec.id}
                  to={`/events/${rec.slug}`} 
                  className="premium-card bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 group overflow-hidden rounded-3xl hover:shadow-lg transition-all duration-500"
                >
                  <div className="aspect-[16/10] relative overflow-hidden m-2 rounded-2xl">
                    <img 
                      src={rec.banner} 
                      className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                      alt={rec.title}
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div className="absolute bottom-4 left-4 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-3.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest text-[#4E7D5B] border border-white/20">
                      {rec.category}
                    </div>
                  </div>
                  <div className="p-6 pt-2 text-left">
                    <h4 className="text-lg font-serif text-slate-900 dark:text-white group-hover:text-[#4E7D5B] transition-colors truncate mb-3 font-bold">{rec.title}</h4>
                    <div className="flex items-center gap-5 text-[9px] font-black uppercase tracking-widest text-slate-400">
                      <span className="flex items-center gap-1.5">
                        <Calendar className="w-3.5 h-3.5 text-[#4E7D5B]" /> 
                        {new Date(rec.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}
                      </span>
                      <span className="flex items-center gap-1.5">
                        <MapPin className="w-3.5 h-3.5 text-[#4E7D5B]" /> 
                        {rec.city || 'Global'}
                      </span>
                    </div>
                  </div>
                </Link>
              ))}
            </div>
          </div>
        </section>
      )}
    </div>
  );
}
