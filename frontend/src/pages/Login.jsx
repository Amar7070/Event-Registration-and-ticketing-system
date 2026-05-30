import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import api, { getCsrfCookie, API_URL } from '../services/api';
import { Mail, Lock, AlertCircle } from 'lucide-react';

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [rememberMe, setRememberMe] = useState(false);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  // If already logged in, redirect to respective dashboard
  useEffect(() => {
    const token = localStorage.getItem('token') || localStorage.getItem('api_token');
    if (token) {
      const role = localStorage.getItem('user_role');
      if (role === 'admin') navigate('/admin/dashboard');
      else if (role === 'organizer') navigate('/organizer/analytics');
      else navigate('/dashboard');
    }
  }, [navigate]);

  const handleLogin = async (e) => {
    e.preventDefault();
    setError(null);
    setLoading(true);

    try {
      // 1. Get CSRF cookie first (necessary for Sanctum security)
      await getCsrfCookie();

      // 2. Submit login request to Laravel API
      const response = await api.post('/auth/login', { 
        email: email.trim(), 
        password,
        remember: rememberMe 
      });
      
      // 3. Store Sanctum Token in localStorage
      if (response.data.token) {
        localStorage.setItem('token', response.data.token);
      }
      
      // Store user details and route based on role
      if (response.data.user) {
        localStorage.setItem('user_name', response.data.user.name);
        localStorage.setItem('user_role', response.data.user.role);
        localStorage.setItem('is_approved', response.data.user.is_approved);

        // 4. Redirect based on role
        if (response.data.user.role === 'admin') {
          navigate('/admin/dashboard');
        } else if (response.data.user.role === 'organizer') {
          if (response.data.user.is_approved) {
            navigate('/organizer/analytics');
          } else {
            navigate('/profile');
          }
        } else {
          navigate('/dashboard');
        }
      } else {
        navigate('/dashboard');
      }
    } catch (err) {
      console.error('Login failure', err);
      if (err.response && err.response.data && err.response.data.message) {
        setError(err.response.data.message);
      } else {
        setError('Connection to security console failed. Check server status.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-[#FDFBF7] flex items-stretch">

      {/* Left Side: Monochromatic Image Card */}
      <div className="hidden lg:flex flex-1 p-6">
        <div className="relative w-full h-full bg-[#4E7D5B] rounded-[3rem] overflow-hidden shadow-2xl flex flex-col">
          <div className="flex-1 relative">
            <img src="/tech_summit_lobby.png"
                className="absolute inset-0 w-full h-full object-cover grayscale opacity-90" alt="SmartEvent Lobby" />
            <div className="absolute inset-0 bg-[#4E7D5B]/30 mix-blend-multiply"></div>
          </div>
          <div className="p-20 relative z-10 bg-[#4E7D5B]">
            <h2 className="text-6xl font-serif text-white mb-8 leading-tight tracking-tighter">Access your SmartEvent Hub</h2>
            <p className="text-xl text-white/70 font-serif italic leading-relaxed max-w-sm">
              Log in to scan ticketing pipelines, coordinate automated waitlists, and monitor your organizer dashboard seamlessly.
            </p>
          </div>
        </div>
      </div>

      {/* Right Side: Login Form */}
      <div className="flex-1 flex flex-col items-center justify-center relative px-8 md:px-20 py-12">
        
        {/* Thin Progress Bar at Top (Visual only for login) */}
        <div className="absolute top-0 left-0 w-full h-1 bg-slate-50 overflow-hidden">
          <div className="h-full w-full bg-[#4E7D5B] transition-all duration-1000"></div>
        </div>

        <div className="w-full max-w-md">
          {/* Header */}
          <div className="mb-16 text-left">
            <div className="flex items-center gap-3 mb-8">
              <div className="w-10 h-10 bg-[#4E7D5B] rounded-xl flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
              </div>
              <h1 className="text-2xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</h1>
            </div>
            <h2 className="text-4xl font-serif text-slate-900 mb-2 leading-tight">Access your portal</h2>
            <p className="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Secure entry to the platform</p>
          </div>

          {error && (
            <div className="p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center gap-3 text-xs font-bold mb-6">
              <AlertCircle className="w-4 h-4 shrink-0" />
              <span>{error}</span>
            </div>
          )}

          {/* Login Form */}
          <form onSubmit={handleLogin} className="space-y-6">
            
            {/* Email Address */}
            <div className="space-y-2 text-left">
              <label htmlFor="email" className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                Email Address
              </label>
              <div className="relative group">
                <div className="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                  <Mail className="w-4 h-4" />
                </div>
                <input 
                  id="email" 
                  type="email" 
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required 
                  autoFocus
                  placeholder="jane@example.com"
                  className="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium text-slate-900 focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm"
                />
              </div>
            </div>

            {/* Password */}
            <div className="space-y-2 text-left">
              <div className="flex justify-between items-center px-1">
                <label htmlFor="password" className="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                  Password
                </label>
                <Link to="/forgot-password" className="text-[10px] font-black text-[#4E7D5B] hover:underline uppercase tracking-widest">
                  Forgot Password?
                </Link>
              </div>
              <div className="relative group">
                <div className="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                  <Lock className="w-4 h-4" />
                </div>
                <input 
                  id="password" 
                  type="password" 
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required 
                  autoComplete="current-password"
                  placeholder="••••••••"
                  className="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium text-slate-900 focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm"
                />
              </div>
            </div>

            {/* Remember Me */}
            <div className="flex items-center justify-between px-1">
              <label htmlFor="remember_me" className="inline-flex items-center cursor-pointer group">
                <input 
                  id="remember_me" 
                  type="checkbox"
                  checked={rememberMe}
                  onChange={(e) => setRememberMe(e.target.checked)}
                  className="rounded-md border-slate-200 text-[#4E7D5B] focus:ring-[#4E7D5B]/20 shadow-sm transition-all"
                />
                <span className="ml-3 text-[10px] font-black text-slate-400 group-hover:text-slate-900 transition-colors uppercase tracking-widest">
                  Remember Me
                </span>
              </label>
            </div>

            {/* Actions */}
            <div className="pt-10 space-y-12">
              <button 
                type="submit"
                disabled={loading}
                className="w-full bg-[#4E7D5B] text-white py-5 rounded-full text-[11px] font-black uppercase tracking-[0.3em] shadow-xl shadow-[#4E7D5B]/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50"
              >
                {loading ? 'Authenticating...' : 'Sign In'}
              </button>

              <div className="relative">
                <div className="absolute inset-0 flex items-center">
                  <div className="w-full border-t border-slate-100"></div>
                </div>
                <div className="relative flex justify-center">
                  <span className="px-6 bg-[#FDFBF7] text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">
                    Or continue with
                  </span>
                </div>
              </div>

              <div className="flex gap-4">
                <a href={`${API_URL}/v1/auth/google/redirect`} className="w-full bg-white border border-slate-100 py-4 rounded-full flex items-center justify-center gap-3 hover:bg-slate-50 transition-all shadow-sm">
                  <img src="https://www.google.com/favicon.ico" className="w-4 h-4 grayscale opacity-40" alt="Google" />
                  <span className="text-[10px] font-black text-slate-900 uppercase tracking-widest">Sign in with Google</span>
                </a>
              </div>

              <p className="text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                New to SmartEvent? <Link to="/register" className="text-[#4E7D5B] hover:underline underline-offset-4 decoration-2">Create Account</Link>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
