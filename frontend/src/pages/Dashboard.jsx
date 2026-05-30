import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import api, { BACKEND_URL } from '../services/api';
import { 
  Ticket, 
  Hourglass, 
  ShieldCheck, 
  Star, 
  Plus, 
  Clock, 
  MapPin, 
  Compass, 
  Archive, 
  CheckCircle, 
  HelpCircle,
  ChevronLeft,
  ChevronRight,
  X,
  User,
  LogOut,
  Moon,
  Sun,
  LayoutDashboard,
  Briefcase,
  ShieldAlert,
  BarChart3,
  MessageSquare
} from 'lucide-react';

export default function Dashboard() {
  const navigate = useNavigate();
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [darkMode, setDarkMode] = useState(
    localStorage.getItem('darkMode') === 'true' || 
    (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
  );

  // Calendar State
  const [currentYear, setCurrentYear] = useState(new Date().getFullYear());
  const [currentMonth, setCurrentMonth] = useState(new Date().getMonth());
  const [selectedDayTickets, setSelectedDayTickets] = useState([]);
  const [showTicketSlider, setShowTicketSlider] = useState(false);
  const [selectedDateStr, setSelectedDateStr] = useState('');
  const [dropdownOpen, setDropdownOpen] = useState(false);

  const monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June', 
    'July', 'August', 'September', 'October', 'November', 'December'
  ];
  const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

  useEffect(() => {
    // Apply dark mode class to document
    if (darkMode) {
      document.documentElement.classList.add('dark');
      localStorage.setItem('darkMode', 'true');
    } else {
      document.documentElement.classList.remove('dark');
      localStorage.setItem('darkMode', 'false');
    }
  }, [darkMode]);

  useEffect(() => {
    async function fetchDashboard() {
      try {
        setLoading(true);
        const response = await api.get('/dashboard');
        setData(response.data);
        setError(null);
      } catch (err) {
        console.error('Error fetching dashboard', err);
        if (err.response && err.response.status === 401) {
          localStorage.removeItem('token');
          localStorage.removeItem('api_token');
          localStorage.removeItem('user_name');
          navigate('/login');
        } else {
          setError('Failed to sync explorer console. Please sign in or check connectivity.');
        }
      } finally {
        setLoading(false);
      }
    }
    fetchDashboard();
  }, [navigate]);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[#FAF9F5] dark:bg-slate-950 transition-colors duration-500">
        <div className="flex flex-col items-center gap-4">
          <div className="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
          <p className="text-xs font-black uppercase tracking-widest text-slate-450 dark:text-slate-400">Synchronizing Explorer Console...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[#FAF9F5] dark:bg-slate-950 px-6">
        <div className="max-w-md w-full bg-white dark:bg-slate-900 border border-red-100 dark:border-red-950 p-10 rounded-[2.5rem] shadow-xl text-center">
          <div className="w-16 h-16 bg-red-50 dark:bg-red-950/20 rounded-2xl flex items-center justify-center text-red-500 mx-auto mb-6">
            <HelpCircle className="w-8 h-8" />
          </div>
          <h2 className="text-2xl font-serif font-medium text-slate-900 dark:text-white mb-2">Sync Connection Halt</h2>
          <p className="text-sm text-slate-400 dark:text-slate-400 mb-6">{error}</p>
          <button 
            onClick={() => {
              localStorage.removeItem('token');
              localStorage.removeItem('api_token');
              localStorage.removeItem('user_name');
              navigate('/login');
            }}
            className="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest w-full text-center block cursor-pointer"
          >
            Return to Authenticator
          </button>
        </div>
      </div>
    );
  }

  const { user, stats, upcomingTicket, recentTickets, confirmedTickets } = data;

  // Calendar Helpers
  const daysInMonth = (month, year) => new Date(year, month + 1, 0).getDate();
  const startDayOfWeek = (month, year) => new Date(year, month, 1).getDay();

  const prevMonth = () => {
    if (currentMonth === 0) {
      setCurrentMonth(11);
      setCurrentYear(prev => prev - 1);
    } else {
      setCurrentMonth(prev => prev - 1);
    }
  };

  const nextMonth = () => {
    if (currentMonth === 11) {
      setCurrentMonth(0);
      setCurrentYear(prev => prev + 1);
    } else {
      setCurrentMonth(prev => prev + 1);
    }
  };

  const getTicketsForDay = (day) => {
    const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    return confirmedTickets.filter(t => t.event_date === dateStr);
  };

  const selectDay = (day) => {
    const tickets = getTicketsForDay(day);
    if (tickets.length > 0) {
      setSelectedDayTickets(tickets);
      setSelectedDateStr(new Date(currentYear, currentMonth, day).toLocaleDateString('en-US', { 
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
      }));
      setShowTicketSlider(true);
    }
  };

  const handleLogout = async () => {
    try {
      await api.post('/auth/logout');
    } catch (err) {
      console.error('Logout failed', err);
    } finally {
      localStorage.removeItem('token');
      localStorage.removeItem('api_token');
      localStorage.removeItem('user_name');
      navigate('/login');
    }
  };

  // Get active month tickets
  const monthPrefix = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-`;
  const currentMonthTickets = confirmedTickets
    .filter(t => t.event_date.startsWith(monthPrefix))
    .sort((a, b) => a.event_date.localeCompare(b.event_date));

  return (
    <div className="min-h-screen bg-[#FAF9F5] dark:bg-slate-950 px-6 md:px-12 pt-12 pb-24 transition-colors duration-500 font-sans">
      <div className="max-w-[1440px] mx-auto space-y-12 animate-slide-up">
        
        {/* Header toolbar */}
        <div className="flex items-center justify-between pb-6 border-b border-slate-100 dark:border-slate-800 relative z-[100]">
          <Link to="/" className="flex items-center gap-3 group cursor-pointer">
            <div className="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white group-hover:scale-105 transition-all shadow-md shadow-primary/10">
              <Ticket className="w-5 h-5" />
            </div>
            <span className="text-2xl font-serif font-black tracking-tighter text-primary group-hover:text-primary/90 transition-all">SmartEvent</span>
          </Link>
          
          <div className="flex items-center gap-4">
            <button 
              onClick={() => setDarkMode(!darkMode)}
              className="p-3 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl text-slate-400 hover:text-primary transition-all duration-300 cursor-pointer"
              title={darkMode ? "Switch to Light Mode" : "Switch to Dark Mode"}
            >
              {darkMode ? <Sun className="w-4 h-4" /> : <Moon className="w-4 h-4" />}
            </button>
            
            <div className="relative">
              <button 
                onClick={() => setDropdownOpen(!dropdownOpen)} 
                className="flex items-center gap-2 focus:outline-none cursor-pointer"
              >
                <div className="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-xs uppercase shadow-sm">
                  {user.name.substring(0, 2)}
                </div>
              </button>

              {dropdownOpen && (
                <>
                  <div 
                    className="fixed inset-0 z-40" 
                    onClick={() => setDropdownOpen(false)}
                  ></div>
                  <div className="absolute right-0 mt-3 w-64 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-xl py-2 z-50 text-left animate-slide-up">
                    <div className="px-5 py-4 border-b border-slate-50 dark:border-slate-800">
                      <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Signed In As</p>
                      <p className="text-xs font-bold text-slate-800 dark:text-white truncate mt-1.5">{user.name}</p>
                    </div>

                    <Link 
                      to="/dashboard" 
                      onClick={() => setDropdownOpen(false)}
                      className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                    >
                      <LayoutDashboard className="w-4 h-4 text-slate-400" />
                      Dashboard
                    </Link>

                    <Link 
                      to="/profile" 
                      onClick={() => setDropdownOpen(false)}
                      className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                    >
                      <User className="w-4 h-4 text-slate-400" />
                      Profile Settings
                    </Link>

                    {(user.role === 'organizer' || user.role === 'admin') && (
                      <>
                        <Link 
                          to="/organizer/analytics" 
                          onClick={() => setDropdownOpen(false)}
                          className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                        >
                          <BarChart3 className="w-4 h-4 text-slate-400" />
                          Organizer Stats
                        </Link>
                        <Link 
                          to="/organizer/events" 
                          onClick={() => setDropdownOpen(false)}
                          className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                        >
                          <Briefcase className="w-4 h-4 text-slate-400" />
                          Event Blueprints
                        </Link>
                        <Link 
                          to="/organizer/reviews" 
                          onClick={() => setDropdownOpen(false)}
                          className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                        >
                          <MessageSquare className="w-4 h-4 text-slate-400" />
                          Review Moderation
                        </Link>
                      </>
                    )}

                    {user.role === 'admin' && (
                      <Link 
                        to="/admin/dashboard" 
                        onClick={() => setDropdownOpen(false)}
                        className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                      >
                        <ShieldAlert className="w-4 h-4 text-slate-400" />
                        Admin Panel
                      </Link>
                    )}

                    <button 
                      onClick={handleLogout}
                      className="w-full flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-rose-500 hover:bg-rose-50/50 dark:hover:bg-rose-950/20 uppercase tracking-wider transition-colors text-left font-sans cursor-pointer border-t border-slate-50 dark:border-slate-800 mt-1"
                    >
                      <LogOut className="w-4 h-4 text-rose-450" />
                      Sign Out
                    </button>
                  </div>
                </>
              )}
            </div>
          </div>
        </div>

        {/* Welcome Hero Grid */}
        <section className="grid gap-8 lg:grid-cols-[1.6fr_0.9fr] items-stretch">
          {/* Welcome Banner */}
          <div className="premium-card p-12 bg-[#1E293B] text-white relative overflow-hidden flex flex-col justify-center rounded-[3rem] border border-slate-800 shadow-2xl">
            <div className="absolute top-0 right-0 w-80 h-80 bg-primary/10 rounded-full blur-[100px] translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
            <div className="absolute bottom-0 left-0 w-80 h-80 bg-blue-500/5 rounded-full blur-[100px] -translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
            
            <div className="relative z-10 space-y-6">
              <div className="flex items-center gap-3">
                <span className="text-[10px] font-black uppercase tracking-[0.3em] text-primary bg-primary/10 px-4 py-2 border border-primary/20 rounded-full">Ecosystem Dashboard</span>
                <div className="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
              </div>
              <h1 className="text-4xl md:text-5xl lg:text-6xl font-serif text-white tracking-tight leading-tight">
                Welcome back,<br />
                <span className="text-primary italic font-normal">{user.name}</span>
              </h1>
              <p className="text-lg text-slate-400 max-w-xl leading-relaxed">
                Your personalized explorer console is synchronized. Track your reservations, audit legal notifications, or manage your organizer parameters seamlessly.
              </p>
              <div className="flex flex-wrap gap-4 pt-4">
                <Link to="/events" className="btn-primary px-10 py-4 text-xs font-black uppercase tracking-widest inline-block text-center">
                  Discover Experiences
                </Link>
                <Link to="/my-tickets" className="px-10 py-4 rounded-full bg-white/5 border border-white/10 text-xs font-black uppercase tracking-widest text-slate-300 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all inline-block text-center">
                  Ticket Vault
                </Link>
              </div>
            </div>
          </div>

          {/* Navigation Sidebar Nodes */}
          <div className="grid grid-cols-1 gap-6">
            <div onClick={() => navigate('/my-tickets')} className="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 flex items-center justify-between group rounded-[2.5rem] hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 cursor-pointer">
              <div className="flex items-center gap-6">
                <div className="w-16 h-16 rounded-2xl bg-cream dark:bg-slate-800/60 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                  <Ticket className="w-6 h-6" />
                </div>
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white group-hover:text-primary transition-colors">Active Passes</h3>
                  <p className="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider mt-0.5">{stats.activeTicketsCount} Upcoming Experiences</p>
                </div>
              </div>
            </div>

            <div onClick={() => navigate('/my-waitlists')} className="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 flex items-center justify-between group rounded-[2.5rem] hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 cursor-pointer">
              <div className="flex items-center gap-6">
                <div className="w-16 h-16 rounded-2xl bg-cream dark:bg-slate-800/60 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                  <Hourglass className="w-6 h-6" />
                </div>
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white group-hover:text-primary transition-colors">My Waitlists</h3>
                  <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider mt-0.5">{stats.waitlistsCount} Requests Pending</p>
                </div>
              </div>
            </div>

            <div onClick={() => navigate('/profile')} className="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 flex items-center justify-between group rounded-[2.5rem] hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 cursor-pointer">
              <div className="flex items-center gap-6">
                <div className="w-16 h-16 rounded-2xl bg-cream dark:bg-slate-800/60 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                  <ShieldCheck className="w-6 h-6" />
                </div>
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white group-hover:text-primary transition-colors">Profile Governance</h3>
                  <p className="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider mt-0.5">Identity & Security</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Experience Calendar Section */}
        <section className="grid gap-8 lg:grid-cols-[1.5fr_1fr] items-stretch">
          
          {/* Calendar Card */}
          <div className="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] shadow-xl shadow-primary/[0.01] flex flex-col justify-between">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
              <div>
                <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">Pass Timeline</span>
                <h2 className="text-2xl font-serif text-slate-900 dark:text-white font-medium">Experience Calendar</h2>
              </div>
              
              {/* Month Switcher */}
              <div className="flex items-center gap-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800/80 px-4 py-2 rounded-full shadow-sm w-fit">
                <button onClick={prevMonth} className="text-slate-400 hover:text-primary transition-colors focus:outline-none cursor-pointer">
                  <ChevronLeft className="w-4 h-4" />
                </button>
                <span className="text-xs font-black uppercase tracking-widest text-slate-700 dark:text-slate-200 select-none min-w-[110px] text-center">
                  {`${monthNames[currentMonth]} ${currentYear}`}
                </span>
                <button onClick={nextMonth} className="text-slate-400 hover:text-primary transition-colors focus:outline-none cursor-pointer">
                  <ChevronRight className="w-4 h-4" />
                </button>
              </div>
            </div>

            {/* Days of Week Headers */}
            <div className="grid grid-cols-7 gap-2 text-center mb-4">
              {daysOfWeek.map(dayName => (
                <span key={dayName} className="text-[9px] font-black uppercase tracking-wider text-slate-400 py-1">{dayName}</span>
              ))}
            </div>

            {/* Days Grid */}
            <div className="grid grid-cols-7 gap-2">
              {/* Empty cells for leading offset */}
              {Array.from({ length: startDayOfWeek(currentMonth, currentYear) }).map((_, i) => (
                <div key={`offset-${i}`} className="aspect-square bg-transparent"></div>
              ))}

              {/* Days of month */}
              {Array.from({ length: daysInMonth(currentMonth, currentYear) }).map((_, i) => {
                const day = i + 1;
                const dayTickets = getTicketsForDay(day);
                const hasTickets = dayTickets.length > 0;
                return (
                  <button 
                    key={`day-${day}`}
                    onClick={() => selectDay(day)}
                    disabled={!hasTickets}
                    className={`aspect-square flex flex-col items-center justify-center rounded-2xl transition-all duration-300 relative group font-sans text-xs overflow-hidden ${
                      hasTickets 
                        ? 'bg-primary/5 text-primary border border-primary/20 hover:border-primary cursor-pointer hover:shadow-lg hover:shadow-primary/20 hover:-translate-y-1 font-black' 
                        : 'text-slate-400 dark:text-slate-500 border border-slate-50 dark:border-slate-800/60 cursor-default'
                    }`}
                  >
                    {/* Animated Sliding Banner with Image */}
                    {hasTickets && (
                      <div className="absolute inset-0 bg-[#4E7D5B] translate-y-[101%] group-hover:translate-y-0 transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] flex items-center justify-center rounded-2xl z-0 overflow-hidden">
                        {dayTickets[0]?.event_banner && (
                          <img 
                            src={dayTickets[0].event_banner.startsWith('http') ? dayTickets[0].event_banner : `${BACKEND_URL}${dayTickets[0].event_banner}`}
                            alt="Event Banner" 
                            className="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay" 
                          />
                        )}
                        <span className="relative z-10 text-[9px] font-black uppercase tracking-widest text-white opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 drop-shadow-md">View</span>
                      </div>
                    )}
                    
                    <span className={`relative z-10 text-sm transition-colors duration-300 ${hasTickets ? 'group-hover:text-white' : ''}`}>
                      {day}
                    </span>
                    
                    {hasTickets && (
                      <span className="absolute bottom-2.5 w-1.5 h-1.5 rounded-full bg-[#4E7D5B] group-hover:bg-white z-10 transition-colors duration-300 group-hover:scale-0"></span>
                    )}
                  </button>
                );
              })}
            </div>
          </div>

          {/* Right Schedule Agenda Card */}
          <div className="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] shadow-xl shadow-primary/[0.01] flex flex-col justify-between">
            <div>
              <div className="border-b border-slate-100 dark:border-slate-800 pb-4 mb-6">
                <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">Ecosystem Grid</span>
                <h2 className="text-xl font-serif text-slate-900 dark:text-white font-medium">Monthly Schedule</h2>
              </div>
              
              <div className="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                {currentMonthTickets.map(ticket => (
                  <div 
                    key={ticket.id}
                    onClick={() => {
                      setSelectedDayTickets([ticket]);
                      setSelectedDateStr(new Date(ticket.event_date).toLocaleDateString('en-US', { 
                        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
                      }));
                      setShowTicketSlider(true);
                    }} 
                    className="flex items-center gap-4 p-4 bg-cream/35 dark:bg-slate-800/10 border border-slate-50 dark:border-slate-800/60 hover:border-primary/25 rounded-2xl cursor-pointer group transition-all duration-300 hover:bg-cream/70 dark:hover:bg-slate-800/30"
                  >
                    <div className="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 flex flex-col items-center justify-center text-primary shrink-0 shadow-sm">
                      <span className="text-[9px] font-black leading-none text-slate-400">
                        {new Date(ticket.event_date).toLocaleDateString('en-US', { month: 'short' }).toUpperCase()}
                      </span>
                      <span className="text-base font-serif font-black leading-none mt-1 text-slate-800 dark:text-slate-100">
                        {new Date(ticket.event_date).getDate()}
                      </span>
                    </div>
                    
                    <div className="min-w-0 flex-1">
                      <h4 className="text-xs font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-primary transition-colors">
                        {ticket.event_title}
                      </h4>
                      <div className="flex items-center gap-1.5 text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">
                        <span>{ticket.event_time}</span>
                        <span>&bull;</span>
                        <span>{ticket.event_city}</span>
                      </div>
                    </div>
                    
                    <ChevronRight className="w-4 h-4 text-slate-300 group-hover:text-primary group-hover:translate-x-1 transition-all" />
                  </div>
                ))}

                {currentMonthTickets.length === 0 && (
                  <div className="py-16 text-center bg-cream/20 border border-slate-50 dark:border-slate-800/60 rounded-2xl flex flex-col items-center justify-center p-6">
                    <Compass className="w-8 h-8 text-slate-300 mb-3" />
                    <span className="text-xs font-bold text-slate-400 uppercase tracking-widest">No Events Booked</span>
                    <p className="text-[10px] text-slate-450 mt-1 max-w-[200px] leading-relaxed">No gathering nodes active in this month interval.</p>
                  </div>
                )}
              </div>
            </div>

            <button onClick={() => navigate('/events')} className="btn-primary w-full py-3.5 text-xs tracking-widest font-black uppercase text-center mt-6 cursor-pointer">
              Find New Gatherings
            </button>
          </div>
        </section>

        {/* Real stats counters */}
        <section className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div className="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 relative overflow-hidden group rounded-[2.5rem] hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
            <div className="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-125 transition-transform duration-700"></div>
            <span className="text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-[0.2em] block mb-4">Total Gatherings</span>
            <div className="text-5xl font-serif text-slate-900 dark:text-white mb-3">{stats.totalGatheringsCount}</div>
            <div className="text-[10px] font-bold text-primary uppercase tracking-widest flex items-center gap-1.5">
              <Star className="w-3.5 h-3.5 fill-primary" /> Active member status
            </div>
          </div>



          <div className="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 relative overflow-hidden group rounded-[2.5rem] hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
            <div className="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -translate-y-12 translate-x-12 group-hover:scale-125 transition-transform duration-700"></div>
            <span className="text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-[0.2em] block mb-4">Distinct Domains</span>
            <div className="text-5xl font-serif text-slate-900 dark:text-white mb-3">{stats.savedCount}</div>
            <div className="text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-widest">
              Unique event domains explored
            </div>
          </div>

          <div onClick={() => navigate('/events')} className="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 border-dashed flex flex-col items-center justify-center text-center group rounded-[2.5rem] hover:bg-white dark:hover:bg-slate-800/60 hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 cursor-pointer">
            <div className="w-14 h-14 rounded-full bg-cream dark:bg-slate-800 flex items-center justify-center text-primary mb-4 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-500 shadow-sm">
              <Plus className="w-6 h-6" />
            </div>
            <span className="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] group-hover:text-primary transition-colors">Venture Map</span>
            <span className="text-[9px] text-slate-400 dark:text-slate-400 font-bold uppercase tracking-widest mt-1">Discover new realms</span>
          </div>
        </section>

        {/* Steward / Host Privilege Node */}
        {(user.role === 'organizer' || user.role === 'admin') && (
          <section className="premium-card p-12 bg-primary/[0.03] border border-primary/20 dark:border-primary/30 relative overflow-hidden rounded-[3rem] shadow-xl shadow-primary/5">
            <div className="absolute top-0 right-0 w-80 h-80 bg-primary/5 rounded-full blur-[80px] translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
            <div className="flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
              <div className="max-w-2xl space-y-4">
                <div className="flex items-center gap-3">
                  <span className="text-[10px] font-black uppercase tracking-[0.3em] text-primary bg-primary/10 px-4 py-2 border border-primary/20 rounded-full">Approved Host Privileges</span>
                  <div className="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></div>
                </div>
                <h2 className="text-3xl font-serif text-slate-900 dark:text-white leading-tight">Your Steward Governance Console is Active</h2>
                <p className="text-slate-500 dark:text-slate-400 leading-relaxed font-serif italic text-sm">
                  "Initialize physical event blueprints, coordinate custom check-in scanners, and track attendee waitlists instantly."
                </p>
              </div>
              <Link to="/organizer/events" className="btn-primary bg-primary text-white border-primary shadow-xl shadow-primary/20 px-10 py-5 text-xs font-black uppercase tracking-[0.2em] hover:bg-primary/90 transition-all whitespace-nowrap inline-block text-center">
                Access Host Control Room
              </Link>
            </div>
          </section>
        )}

        {/* Admin Control Node */}
        {user.role === 'admin' && (
          <section className="premium-card p-12 bg-rose-500/[0.03] border border-rose-500/20 dark:border-rose-500/30 relative overflow-hidden rounded-[3rem] shadow-xl shadow-rose-500/5 mt-8">
            <div className="absolute top-0 right-0 w-80 h-80 bg-rose-500/5 rounded-full blur-[80px] translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
            <div className="flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
              <div className="max-w-2xl space-y-4">
                <div className="flex items-center gap-3">
                  <span className="text-[10px] font-black uppercase tracking-[0.3em] text-rose-500 bg-rose-500/10 px-4 py-2 border border-rose-500/20 rounded-full">Ecosystem Governance</span>
                  <div className="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></div>
                </div>
                <h2 className="text-3xl font-serif text-slate-900 dark:text-white leading-tight">Platform Control Room is Active</h2>
                <p className="text-slate-500 dark:text-slate-400 leading-relaxed font-serif italic text-sm">
                  "Audit platform-wide transaction registries, active licenses, and content restrictions instantly."
                </p>
              </div>
              <Link to="/admin/dashboard" className="btn-primary bg-rose-500 text-white border-rose-500 shadow-xl shadow-rose-500/20 px-10 py-5 text-xs font-black uppercase tracking-[0.2em] hover:bg-rose-600 hover:text-white transition-all whitespace-nowrap inline-block text-center">
                Enter Platform Control
              </Link>
            </div>
          </section>
        )}

        {/* Upcoming Experience Highlights */}
        <section className="grid gap-8 lg:grid-cols-[1.5fr_1fr]">
          
          {/* Next Gathering Card */}
          <div className="premium-card p-12 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[3rem] shadow-xl shadow-primary/[0.02]">
            <span className="text-[10px] font-black text-primary uppercase tracking-[0.2em] block mb-8">Next Gathering Node</span>
            
            {upcomingTicket ? (
              <div className="flex flex-col md:flex-row gap-10 items-stretch">
                <div className="w-full md:w-1/2 aspect-video md:aspect-auto rounded-[2.5rem] overflow-hidden shadow-2xl relative group min-h-[220px]">
                  <img src={upcomingTicket.event_banner} className="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" alt="Event Banner" />
                  <div className="absolute top-6 left-6 bg-white/95 dark:bg-slate-950/95 backdrop-blur px-5 py-2.5 rounded-2xl text-center shadow-xl border border-white/50 dark:border-slate-800/50">
                    <span className="block text-[10px] font-black uppercase tracking-widest text-slate-400 leading-none mb-1">
                      {new Date(upcomingTicket.event_date).toLocaleDateString('en-US', { month: 'short' }).toUpperCase()}
                    </span>
                    <span className="block text-2xl font-serif text-slate-900 dark:text-white leading-none">
                      {new Date(upcomingTicket.event_date).getDate()}
                    </span>
                  </div>
                </div>
                <div className="w-full md:w-1/2 flex flex-col justify-between py-2 space-y-6">
                  <div>
                    <span className="text-[9px] font-black text-primary uppercase tracking-widest bg-primary/10 border border-primary/20 px-3 py-1 rounded-full mb-3 inline-block">
                      {upcomingTicket.event_category}
                    </span>
                    <h2 className="text-3xl font-serif text-slate-900 dark:text-white leading-tight mb-3 truncate max-w-sm" title={upcomingTicket.event_title}>
                      {upcomingTicket.event_title}
                    </h2>
                    <p className="text-slate-400 font-bold uppercase tracking-widest text-[9px] flex items-center gap-1.5">
                      <Clock className="w-3.5 h-3.5 text-primary" /> 
                      Starts {upcomingTicket.event_time}
                    </p>
                  </div>
                  <div className="flex items-center gap-6 py-5 border-y border-slate-100 dark:border-slate-800">
                    <div className="min-w-0">
                      <span className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Location Node</span>
                      <span className="text-xs font-bold text-slate-800 dark:text-slate-200 truncate block max-w-[150px]">{upcomingTicket.event_venue}</span>
                    </div>
                    <div className="min-w-0 border-l border-slate-100 dark:border-slate-800 pl-6">
                      <span className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Reference Code</span>
                      <span className="text-xs font-bold text-primary font-mono block truncate">{upcomingTicket.booking_reference}</span>
                    </div>
                  </div>
                  <div className="flex flex-wrap items-center gap-4">
                    <button onClick={() => navigate('/my-tickets/' + upcomingTicket.booking_reference)} className="btn-primary px-6 py-3 text-[10px] font-black uppercase tracking-widest cursor-pointer">
                      Open Digital Pass
                    </button>
                  </div>
                </div>
              </div>
            ) : (
              <div className="py-16 text-center bg-cream/40 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 rounded-[2.5rem] flex flex-col items-center justify-center p-8">
                <div className="w-16 h-16 rounded-full bg-cream dark:bg-slate-900 flex items-center justify-center text-slate-300 mb-6 border border-slate-50 dark:border-slate-800">
                  <Compass className="w-8 h-8" />
                </div>
                <h3 className="text-xl font-serif text-slate-900 dark:text-white mb-2">No Active Reservations</h3>
                <p className="text-slate-400 max-w-sm text-sm leading-relaxed mb-6">
                  You have no upcoming confirmed passes right now. Adventure awaits inside the Experience Map!
                </p>
                <button onClick={() => navigate('/events')} className="btn-primary px-8 py-3 text-[10px] font-black uppercase tracking-widest cursor-pointer">
                  Explore Realm Map
                </button>
              </div>
            )}
          </div>

          {/* Recent Activities & History */}
          <div className="premium-card p-12 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-[3rem] shadow-xl shadow-primary/[0.02] flex flex-col">
            <span className="text-[10px] font-black text-primary uppercase tracking-[0.2em] block mb-8">Pass Vault History</span>
            
            {recentTickets.length > 0 ? (
              <div className="space-y-6 flex-1 overflow-y-auto max-h-[320px] pr-2">
                {recentTickets.map(ticket => (
                  <div 
                    key={ticket.id} 
                    onClick={() => navigate('/my-tickets/' + ticket.booking_reference)}
                    className="flex items-center justify-between p-5 bg-cream/30 dark:bg-slate-950/30 hover:bg-cream/70 dark:hover:bg-slate-950/60 border border-slate-50 dark:border-slate-800/40 hover:border-primary/25 rounded-2xl group transition-all duration-300 cursor-pointer"
                  >
                    <div className="flex items-center gap-4 min-w-0">
                      <div className="w-12 h-12 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 flex items-center justify-center text-primary group-hover:scale-110 transition-transform flex-shrink-0 shadow-sm">
                        {ticket.status === 'confirmed' ? (
                          <CheckCircle className="w-5 h-5 text-emerald-500" />
                        ) : (
                          <HelpCircle className="w-5 h-5 text-slate-400" />
                        )}
                      </div>
                      <div className="min-w-0">
                        <h4 className="text-sm font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-primary transition-colors max-w-[180px]" title={ticket.event_title}>
                          {ticket.event_title}
                        </h4>
                        <span className="text-[9px] text-slate-400 font-bold uppercase tracking-widest block mt-0.5">{ticket.event_date}</span>
                      </div>
                    </div>
                    <div className="text-right flex-shrink-0">
                      <span className="font-mono text-xs font-bold block text-slate-900 dark:text-white">{ticket.booking_reference}</span>
                      <span className={`text-[8px] font-black uppercase tracking-widest px-2.5 py-0.5 rounded-full inline-block mt-1 ${
                        ticket.status === 'confirmed' 
                          ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' 
                          : 'bg-slate-100 dark:bg-slate-800 text-slate-400'
                      }`}>
                        {ticket.status}
                      </span>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="py-12 text-center bg-cream/20 dark:bg-slate-950/20 border border-slate-50 dark:border-slate-800/40 rounded-3xl flex flex-col items-center justify-center p-8 flex-1">
                <Archive className="w-8 h-8 text-slate-300 mb-4" />
                <span className="text-xs font-bold text-slate-400 uppercase tracking-widest">Vault Empty</span>
              </div>
            )}
          </div>
        </section>
      </div>

      {/* Slide-out Ticket Drawer overlay */}
      {showTicketSlider && (
        <div className="fixed inset-0 z-[100] overflow-hidden" role="dialog" aria-modal="true">
          <div className="absolute inset-0 overflow-hidden">
            {/* Backdrop shadow */}
            <div 
              onClick={() => setShowTicketSlider(false)}
              className="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity duration-500" 
              aria-hidden="true"
            ></div>

            <div className="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
              {/* Drawer panel */}
              <div className="pointer-events-auto w-screen max-w-md transform transition ease-in-out duration-500">
                <div className="flex h-full flex-col overflow-y-scroll bg-white dark:bg-slate-900 shadow-2xl border-l border-slate-100 dark:border-slate-800/80">
                  
                  {/* Drawer Header */}
                  <div className="px-6 py-6 bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                      <h2 className="text-lg font-serif font-bold text-slate-900 dark:text-white">Active Reservations</h2>
                      <p className="text-[10px] text-slate-450 dark:text-slate-400 font-black uppercase tracking-widest mt-1">{selectedDateStr}</p>
                    </div>
                    <button 
                      onClick={() => setShowTicketSlider(false)} 
                      className="rounded-xl border border-slate-200 dark:border-slate-800 p-2 text-slate-400 hover:text-slate-500 dark:hover:text-white focus:outline-none cursor-pointer"
                    >
                      <X className="h-5 w-5" />
                    </button>
                  </div>

                  {/* Drawer Content */}
                  <div className="relative flex-1 p-6 space-y-6">
                    {selectedDayTickets.map(ticket => (
                      <div key={ticket.id} className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-lg flex flex-col">
                        
                        {/* Event Banner */}
                        <div className="h-40 relative">
                          <img src={ticket.event_banner} className="w-full h-full object-cover" alt="Banner" />
                          <div className="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                          <span className="absolute bottom-4 left-4 bg-white/15 backdrop-blur-md text-white border border-white/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">
                            {ticket.event_category}
                          </span>
                        </div>

                        {/* Ticket Details */}
                        <div className="p-6 space-y-6">
                          <div>
                            <h3 className="text-lg font-serif font-bold text-slate-900 dark:text-white leading-tight">{ticket.event_title}</h3>
                            <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest block mt-2">{ticket.ticket_type}</span>
                          </div>

                          <div className="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                            <div>
                              <span className="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Date & Time</span>
                              <span className="text-xs font-bold text-slate-800 dark:text-slate-200">{ticket.event_time}</span>
                            </div>
                            <div>
                              <span className="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Location</span>
                              <span className="text-xs font-bold text-slate-800 dark:text-slate-200 truncate block max-w-[120px]" title={ticket.event_venue}>
                                {ticket.event_venue}
                              </span>
                            </div>
                            <div>
                              <span className="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Reference Code</span>
                              <span className="text-xs font-bold text-primary font-mono">{ticket.booking_reference}</span>
                            </div>
                            <div>
                              <span className="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Quantity</span>
                              <span className="text-xs font-bold text-slate-800 dark:text-slate-200">{ticket.quantity} Passes</span>
                            </div>
                          </div>

                          <button onClick={() => navigate('/my-tickets/' + ticket.booking_reference)} className="btn-primary w-full py-3.5 text-xs tracking-widest font-black uppercase text-center block mt-6 cursor-pointer">
                            Open Digital Pass
                          </button>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
