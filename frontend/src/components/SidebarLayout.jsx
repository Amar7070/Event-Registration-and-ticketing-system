import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import ThemeToggle from './ThemeToggle';
import api, { BACKEND_URL } from '../services/api';
import { 
  LayoutDashboard, 
  Ticket, 
  Hourglass, 
  User, 
  LogOut, 
  Plus, 
  ScanQrCode, 
  Users, 
  BarChart3, 
  MessageSquare, 
  Tag, 
  ShieldAlert, 
  FolderLock, 
  Scale, 
  ArrowLeft,
  ChevronLeft,
  ChevronRight,
  Menu,
  X,
  Sparkles
} from 'lucide-react';

export default function SidebarLayout({ children, type = 'user' }) {
  const navigate = useNavigate();
  const location = useLocation();
  const [collapsed, setCollapsed] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [user, setUser] = useState({ name: 'User', email: '', role: 'user' });

  useEffect(() => {
    async function fetchUser() {
      try {
        const response = await api.get('/user');
        setUser(response.data);
      } catch (err) {
        console.error('Failed to authenticate user context in SidebarLayout', err);
      }
    }
    fetchUser();

    const handleProfileUpdate = () => {
      // Re-fetch user on profile update
      fetchUser();
    };
    window.addEventListener('profile-updated', handleProfileUpdate);
    return () => window.removeEventListener('profile-updated', handleProfileUpdate);
  }, []);

  const handleLogout = async () => {
    try {
      await api.post('/auth/logout');
    } catch (err) {
      console.error('Logout failed', err);
    } finally {
      localStorage.removeItem('token');
      localStorage.removeItem('api_token');
      localStorage.removeItem('user_name');
      localStorage.removeItem('user_role');
      localStorage.removeItem('user_avatar');
      navigate('/login');
    }
  };

  // Define sidebar menus
  const menus = {
    user: [
      { name: 'Console Home', path: '/dashboard', icon: LayoutDashboard },
      { name: 'Active Passes', path: '/my-tickets', icon: Ticket },
      { name: 'Waitlist Queue', path: '/my-waitlists', icon: Hourglass },
      { name: 'Identity & Governance', path: '/profile', icon: User },
    ],
    organizer: [
      { name: 'Control / Dashboard', path: '/organizer/analytics', icon: BarChart3 },
      { name: 'Events Management', path: '/organizer/events', icon: Ticket },
      { name: 'Coupons Create', path: '/organizer/coupons', icon: Tag },
      { name: 'User Control', path: '/organizer/attendees', icon: Users },
      { name: 'Experience Reviews', path: '/organizer/reviews', icon: MessageSquare },
      { name: 'Copyright Audits', path: '/organizer/copyright', icon: ShieldAlert },
      { name: 'Back to Explorer', path: '/dashboard', icon: ArrowLeft, raw: true },
    ],
    admin: [
      { name: 'Platform Control', path: '/admin/dashboard', icon: LayoutDashboard },
      { name: 'User Directory', path: '/admin/users', icon: Users },
      { name: 'Domain Spheres', path: '/admin/categories', icon: Tag },
      { name: 'Promo Coupons', path: '/admin/coupons', icon: Tag },
      { name: 'Showcase Ad Ads', path: '/admin/promotions', icon: Sparkles },
      { name: 'Events Moderation', path: '/admin/events', icon: FolderLock },
      { name: 'Host Approvals', path: '/admin/organizers/pending', icon: Users },
      { name: 'Inbox & Messages', path: '/admin/contacts', icon: MessageSquare },
      { name: 'Copyright Claims', path: '/admin/claims', icon: ShieldAlert },
      { name: 'Experience Reviews', path: '/admin/reviews', icon: MessageSquare },
      { name: 'Revenue Commissions', path: '/admin/revenue', icon: Scale },
      { name: 'Back to Explorer', path: '/dashboard', icon: ArrowLeft, raw: true },
    ]
  };

  const currentMenu = menus[type] || menus.user;

  const sidebarTitle = {
    user: 'Explorer Hub',
    organizer: 'Host Control Room',
    admin: 'Ecosystem Governance'
  }[type];

  const sidebarColor = {
    user: 'text-[#4E7D5B]',
    organizer: 'text-[#4E7D5B]',
    admin: 'text-rose-500'
  }[type];

  return (
    <div className="min-h-screen bg-[#FAF9F5] dark:bg-slate-950 flex transition-colors duration-500 text-slate-800 dark:text-slate-100 font-sans">
      
      {/* Mobile Top Bar */}
      <header className="lg:hidden fixed top-0 w-full z-45 bg-[#FDFBF7]/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 px-6 py-4 flex items-center justify-between shadow-sm">
        <div className="flex items-center gap-3">
          <button 
            onClick={() => setMobileOpen(!mobileOpen)} 
            className="p-2 border border-slate-150 dark:border-slate-800 rounded-xl text-slate-400 focus:outline-none"
          >
            {mobileOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
          </button>
          <span className="text-lg font-serif font-black tracking-tight text-[#4E7D5B]">SmartEvent</span>
        </div>
        <div className="flex items-center gap-3">
          <ThemeToggle />
          <div className="w-9 h-9 rounded-lg bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 flex items-center justify-center text-[#4E7D5B] font-bold text-xs uppercase overflow-hidden">
            {user.avatar ? (
              <img src={user.avatar.startsWith('http') ? user.avatar : `${BACKEND_URL}${user.avatar}`} alt="Avatar" className="w-full h-full object-cover" />
            ) : (
              user.name.substring(0, 2)
            )}
          </div>
        </div>
      </header>

      {/* Sidebar Panel (Desktop / Collapsible) */}
      <aside 
        className={`hidden lg:flex flex-col justify-between fixed left-0 top-0 h-screen z-50 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800/80 transition-all duration-500 ${
          collapsed ? 'w-24' : 'w-72'
        }`}
      >
        <div className="p-6 flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
          {/* Logo & Collapse Switch */}
          <div className="flex items-center justify-between mb-10 pb-6 border-b border-slate-55 dark:border-slate-800/50">
            {!collapsed && (
              <Link to="/" className="flex items-center gap-2.5">
                <div className="w-9 h-9 bg-[#4E7D5B] rounded-lg flex items-center justify-center text-white shadow-md shadow-[#4E7D5B]/10">
                  <Ticket className="w-4.5 h-4.5" />
                </div>
                <span className="text-xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</span>
              </Link>
            )}
            {collapsed && (
              <div className="w-9 h-9 bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 rounded-lg flex items-center justify-center text-[#4E7D5B] mx-auto">
                <Ticket className="w-4.5 h-4.5" />
              </div>
            )}
            
            <button 
              onClick={() => setCollapsed(!collapsed)} 
              className={`p-2 border border-slate-100 dark:border-slate-800 rounded-lg text-slate-400 hover:text-[#4E7D5B] cursor-pointer ${collapsed ? 'mx-auto mt-2' : ''}`}
            >
              {collapsed ? <ChevronRight className="w-3.5 h-3.5" /> : <ChevronLeft className="w-3.5 h-3.5" />}
            </button>
          </div>

          {/* User Node Panel */}
          {!collapsed && (
            <div className="mb-8 p-5 bg-cream/35 dark:bg-slate-800/20 border border-slate-50 dark:border-slate-800/50 rounded-2xl">
              <span className={`text-[8px] font-black uppercase tracking-[0.2em] block mb-2 ${sidebarColor}`}>
                {sidebarTitle}
              </span>
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 flex items-center justify-center text-slate-400 overflow-hidden shrink-0">
                  {user.avatar ? (
                    <img src={user.avatar.startsWith('http') ? user.avatar : `${BACKEND_URL}${user.avatar}`} alt="Avatar" className="w-full h-full object-cover" />
                  ) : (
                    <User className="w-5 h-5" />
                  )}
                </div>
                <div className="min-w-0">
                  <h4 className="text-sm font-bold truncate text-slate-850 dark:text-slate-100">{user.name}</h4>
                  <p className="text-[10px] text-slate-450 dark:text-slate-400 font-bold uppercase tracking-wider mt-0.5">{user.role}</p>
                </div>
              </div>
            </div>
          )}

          {/* Sidebar Menu Items */}
          <nav className="space-y-1.5">
            {currentMenu.map((item, idx) => {
              const Icon = item.icon;
              const isActive = location.pathname === item.path;
              return (
                <Link
                  key={idx}
                  to={item.path}
                  className={`flex items-center gap-4 py-3.5 px-4 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 ${
                    isActive 
                      ? 'bg-[#4E7D5B] text-white shadow-lg shadow-[#4E7D5B]/10' 
                      : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-[#4E7D5B]'
                  }`}
                  title={collapsed ? item.name : ''}
                >
                  <Icon className="w-4.5 h-4.5 shrink-0" />
                  {!collapsed && <span>{item.name}</span>}
                </Link>
              );
            })}
          </nav>
        </div>

        {/* Bottom Actions */}
        <div className="p-6 border-t border-slate-50 dark:border-slate-800/50 space-y-4">
          <div className="flex items-center justify-between">
            {!collapsed && <span className="text-[9px] font-black text-slate-450 uppercase tracking-widest">Ecosystem Tools</span>}
            <div className={`${collapsed ? 'mx-auto' : ''}`}>
              <ThemeToggle />
            </div>
          </div>
          
          <button 
            onClick={handleLogout}
            className={`w-full flex items-center gap-4 py-3.5 px-4 rounded-xl text-xs font-bold uppercase tracking-wider text-rose-500 hover:bg-rose-50/50 dark:hover:bg-rose-950/20 cursor-pointer ${
              collapsed ? 'justify-center' : ''
            }`}
            title={collapsed ? 'Sign Out' : ''}
          >
            <LogOut className="w-4.5 h-4.5 shrink-0" />
            {!collapsed && <span>Sign Out</span>}
          </button>
        </div>
      </aside>

      {/* Mobile Drawer Overlay */}
      {mobileOpen && (
        <div className="lg:hidden fixed inset-0 z-50 overflow-hidden" role="dialog" aria-modal="true">
          <div 
            onClick={() => setMobileOpen(false)}
            className="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300"
          ></div>
          <div className="absolute inset-y-0 left-0 w-64 bg-white dark:bg-slate-900 shadow-2xl border-r border-slate-100 dark:border-slate-850 flex flex-col justify-between">
            <div className="p-6 flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
              <div className="flex items-center justify-between pb-6 border-b border-slate-100 dark:border-slate-800 mb-8">
                <span className="text-xl font-serif font-black tracking-tight text-[#4E7D5B]">SmartEvent</span>
                <button onClick={() => setMobileOpen(false)} className="p-1 border border-slate-200 dark:border-slate-850 rounded-lg text-slate-400">
                  <X className="w-4 h-4" />
                </button>
              </div>

              <div className="mb-6 p-4 bg-cream/35 dark:bg-slate-800/30 rounded-xl">
                <span className={`text-[8px] font-black uppercase tracking-[0.2em] block mb-2 ${sidebarColor}`}>
                  {sidebarTitle}
                </span>
                <div className="flex items-center gap-3">
                  <div className="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 flex items-center justify-center text-slate-400 overflow-hidden shrink-0">
                    {user.avatar ? (
                      <img src={user.avatar.startsWith('http') ? user.avatar : `${BACKEND_URL}${user.avatar}`} alt="Avatar" className="w-full h-full object-cover" />
                    ) : (
                      <User className="w-4 h-4" />
                    )}
                  </div>
                  <h4 className="text-xs font-bold truncate text-slate-850 dark:text-slate-100">{user.name}</h4>
                </div>
              </div>

              <nav className="space-y-1">
                {currentMenu.map((item, idx) => {
                  const Icon = item.icon;
                  const isActive = location.pathname === item.path;
                  return (
                    <Link
                      key={idx}
                      to={item.path}
                      onClick={() => setMobileOpen(false)}
                      className={`flex items-center gap-3.5 py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-wider ${
                        isActive 
                          ? 'bg-[#4E7D5B] text-white' 
                          : 'text-slate-650 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'
                      }`}
                    >
                      <Icon className="w-4.5 h-4.5" />
                      <span>{item.name}</span>
                    </Link>
                  );
                })}
              </nav>
            </div>

            <div className="p-6 border-t border-slate-100 dark:border-slate-800">
              <button 
                onClick={handleLogout}
                className="w-full flex items-center gap-3.5 py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-wider text-rose-500 hover:bg-rose-50"
              >
                <LogOut className="w-4.5 h-4.5" />
                <span>Sign Out</span>
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Main Content Area */}
      <main 
        className={`flex-1 transition-all duration-500 p-6 md:p-12 ${
          collapsed ? 'lg:pl-32' : 'lg:pl-84'
        } pt-28 lg:pt-12`}
      >
        <div className="max-w-[1440px] mx-auto">
          {children}
        </div>
      </main>
    </div>
  );
}
