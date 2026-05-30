import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import ThemeToggle from './ThemeToggle';
import api, { BACKEND_URL } from '../services/api';
import { Ticket, Menu, X, User, LogOut, ShieldAlert, Briefcase, LayoutDashboard, BarChart3, MessageSquare } from 'lucide-react';

export default function Navbar() {
  const navigate = useNavigate();
  const [user, setUser] = useState(() => {
    const token = localStorage.getItem('token') || localStorage.getItem('api_token');
    const name = localStorage.getItem('user_name');
    const role = localStorage.getItem('user_role');
    const avatar = localStorage.getItem('user_avatar');
    return token && name ? { name, role, avatar } : null;
  });
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  const token = localStorage.getItem('token') || localStorage.getItem('api_token');

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 50);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    async function fetchUser() {
      if (token) {
        try {
          const response = await api.get('/user');
          setUser(response.data);
          localStorage.setItem('user_name', response.data.name);
          localStorage.setItem('user_role', response.data.role);
          localStorage.setItem('is_approved', response.data.is_approved);
          if (response.data.avatar) {
            localStorage.setItem('user_avatar', response.data.avatar);
          } else {
            localStorage.removeItem('user_avatar');
          }
        } catch (err) {
          console.error('Failed to load user', err);
          // Token expired or invalid
          localStorage.removeItem('token');
          localStorage.removeItem('api_token');
          localStorage.removeItem('user_name');
          localStorage.removeItem('user_role');
          localStorage.removeItem('user_avatar');
          setUser(null);
        }
      } else {
        setUser(null);
      }
    }
    fetchUser();

    // Listen for global profile updates
    const handleProfileUpdate = () => {
      setUser(prev => {
        if (!prev) return prev;
        return {
          ...prev,
          name: localStorage.getItem('user_name') || prev.name,
          avatar: localStorage.getItem('user_avatar')
        };
      });
    };
    window.addEventListener('profile-updated', handleProfileUpdate);
    
    return () => window.removeEventListener('profile-updated', handleProfileUpdate);
  }, [token]);

  const handleLogout = async () => {
    try {
      await api.post('/auth/logout');
    } catch (err) {
      console.error('Logout request failed', err);
    } finally {
      localStorage.removeItem('token');
      localStorage.removeItem('api_token');
      localStorage.removeItem('user_name');
      localStorage.removeItem('user_role');
      localStorage.removeItem('user_avatar');
      setUser(null);
      setDropdownOpen(false);
      navigate('/login');
    }
  };

  return (
    <nav className={`fixed top-0 w-full z-[100] transition-all duration-500 ${
      scrolled 
        ? 'bg-[#FDFBF7]/90 dark:bg-slate-950/90 backdrop-blur-xl border-b border-slate-100 dark:border-slate-800 py-4 shadow-sm' 
        : 'py-8 bg-transparent'
    }`}>
      <div className="max-w-[1440px] mx-auto px-6 md:px-12 flex items-center justify-between">
        
        {/* Left Side: Branding */}
        <Link to="/" className="flex items-center gap-3 group">
          <div className="w-10 h-10 bg-[#4E7D5B] rounded-xl flex items-center justify-center text-white transition-all group-hover:scale-105 shadow-md shadow-[#4E7D5B]/10">
            <Ticket className="w-5 h-5 text-white" />
          </div>
          <span className="text-2xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</span>
        </Link>

        {/* Center Links */}
        <div className="hidden md:flex items-center gap-10">
          <Link to="/events" className="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] hover:text-[#4E7D5B] transition-colors">
            Browse Events
          </Link>
          <a href="/#features" className="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] hover:text-[#4E7D5B] transition-colors">
            Features
          </a>
          <a href="/#metrics" className="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] hover:text-[#4E7D5B] transition-colors">
            Impact
          </a>
          <Link to="/about" className="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] hover:text-[#4E7D5B] transition-colors">
            About Us
          </Link>
          <Link to="/contact" className="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] hover:text-[#4E7D5B] transition-colors">
            Contact
          </Link>
        </div>

        {/* Right Side Actions */}
        <div className="flex items-center gap-4">
          <ThemeToggle />

          {user ? (
            <div className="relative">
              <button 
                onClick={() => setDropdownOpen(!dropdownOpen)} 
                className="flex items-center gap-2 focus:outline-none cursor-pointer"
              >
                <div className="w-10 h-10 rounded-xl bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 flex items-center justify-center text-[#4E7D5B] font-bold text-xs uppercase shadow-sm overflow-hidden">
                  {user.avatar ? (
                    <img src={user.avatar.startsWith('http') ? user.avatar : `${BACKEND_URL}${user.avatar}`} alt="Avatar" className="w-full h-full object-cover" />
                  ) : (
                    user.name.substring(0, 2)
                  )}
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
                      className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-[#4E7D5B] hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                    >
                      <LayoutDashboard className="w-4 h-4 text-slate-400" />
                      Dashboard
                    </Link>

                    <Link 
                      to="/profile" 
                      onClick={() => setDropdownOpen(false)}
                      className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-[#4E7D5B] hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                    >
                      <User className="w-4 h-4 text-slate-400" />
                      Profile Settings
                    </Link>

                    {user.role === 'organizer' && (
                      <>
                        <Link 
                          to="/organizer/analytics" 
                          onClick={() => setDropdownOpen(false)}
                          className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-[#4E7D5B] hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                        >
                          <BarChart3 className="w-4 h-4 text-slate-400" />
                          Organizer Stats
                        </Link>
                        <Link 
                          to="/organizer/events" 
                          onClick={() => setDropdownOpen(false)}
                          className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-[#4E7D5B] hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
                        >
                          <Briefcase className="w-4 h-4 text-slate-400" />
                          Event Blueprints
                        </Link>

                      </>
                    )}

                    {user.role === 'admin' && (
                      <Link 
                        to="/admin/dashboard" 
                        onClick={() => setDropdownOpen(false)}
                        className="flex items-center gap-3 px-5 py-3.5 text-[11px] font-bold text-slate-600 dark:text-slate-300 hover:text-[#4E7D5B] hover:bg-slate-50 dark:hover:bg-slate-800/50 uppercase tracking-wider transition-colors"
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
          ) : (
            <div className="hidden sm:flex items-center gap-4">
              <Link to="/login" className="px-6 py-3 border border-slate-200 dark:border-slate-800 text-[#4E7D5B] dark:text-slate-300 bg-white dark:bg-slate-900 rounded-full text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                Sign In
              </Link>
              <Link to="/register" className="px-8 py-3 bg-[#4E7D5B] text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/20 hover:scale-[1.03] active:scale-95 transition-all">
                Get Started
              </Link>
            </div>
          )}

          {/* Mobile Menu Toggle */}
          <button 
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)} 
            className="md:hidden p-2 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white"
          >
            {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
          </button>
        </div>
      </div>

      {/* Mobile Drawer */}
      {mobileMenuOpen && (
        <div className="md:hidden bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 px-6 py-6 space-y-4">
          <Link 
            to="/events" 
            onClick={() => setMobileMenuOpen(false)}
            className="block text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-300 py-2 hover:text-[#4E7D5B]"
          >
            Browse Events
          </Link>
          <a 
            href="/#features" 
            onClick={() => setMobileMenuOpen(false)}
            className="block text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-300 py-2 hover:text-[#4E7D5B]"
          >
            Features
          </a>
          <a 
            href="/#metrics" 
            onClick={() => setMobileMenuOpen(false)}
            className="block text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-300 py-2 hover:text-[#4E7D5B]"
          >
            Impact
          </a>
          <Link 
            to="/about" 
            onClick={() => setMobileMenuOpen(false)}
            className="block text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-300 py-2 hover:text-[#4E7D5B]"
          >
            About Us
          </Link>
          
          {!user && (
            <div className="pt-4 flex flex-col gap-3">
              <Link 
                to="/login" 
                onClick={() => setMobileMenuOpen(false)}
                className="w-full text-center py-3 border border-slate-200 dark:border-slate-800 text-[#4E7D5B] dark:text-slate-300 bg-white dark:bg-slate-900 rounded-full text-xs font-bold uppercase tracking-widest"
              >
                Sign In
              </Link>
              <Link 
                to="/register" 
                onClick={() => setMobileMenuOpen(false)}
                className="w-full text-center py-3 bg-[#4E7D5B] text-white rounded-full text-xs font-bold uppercase tracking-widest"
              >
                Get Started
              </Link>
            </div>
          )}
        </div>
      )}
    </nav>
  );
}
