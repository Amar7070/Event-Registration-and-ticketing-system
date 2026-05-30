import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import api, { getCsrfCookie } from '../services/api';
import { Mail, Lock, User, AlertCircle } from 'lucide-react';

export default function Register() {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    const token = localStorage.getItem('token') || localStorage.getItem('api_token');
    if (token) {
      navigate('/dashboard');
    }
  }, [navigate]);

  const handleRegister = async (e) => {
    e.preventDefault();
    setError(null);

    if (password !== passwordConfirmation) {
      setError('Passwords do not match.');
      return;
    }

    setLoading(true);

    try {
      await getCsrfCookie();

      const response = await api.post('/auth/register', {
        name: name.trim(),
        email: email.trim(),
        password,
        password_confirmation: passwordConfirmation
      });

      if (response.data.token) {
        localStorage.setItem('token', response.data.token);
      }
      if (response.data.user) {
        localStorage.setItem('user_name', response.data.user.name);
      }

      navigate('/dashboard');
    } catch (err) {
      console.error('Registration failed', err);
      if (err.response && err.response.data && err.response.data.message) {
        setError(err.response.data.message);
      } else if (err.response && err.response.data && err.response.data.errors) {
        const firstError = Object.values(err.response.data.errors)[0][0];
        setError(firstError);
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
            <img 
              src="/networking_gathering.png"
              onError={(e) => { e.target.src = 'https://images.unsplash.com/photo-1540575861501-7ad05823c23d?auto=format&fit=crop&q=80&w=1000' }}
              className="absolute inset-0 w-full h-full object-cover grayscale opacity-90" 
              alt="SmartEvent Networking" 
            />
            <div className="absolute inset-0 bg-[#4E7D5B]/30 mix-blend-multiply"></div>
          </div>
          <div className="p-20 relative z-10 bg-[#4E7D5B]">
            <h2 className="text-6xl font-serif text-white mb-8 leading-tight tracking-tighter">Host and Discover Elite Experiences</h2>
            <p className="text-xl text-white/70 font-serif italic leading-relaxed max-w-sm">
              Sign up to buy tickets, download secure cryptographic passes, and unlock premium organizer dashboards.
            </p>
          </div>
        </div>
      </div>

      {/* Right Side: Register Form */}
      <div className="flex-1 flex flex-col items-center justify-center relative px-8 md:px-20 py-12">
        {/* Progress bar */}
        <div className="absolute top-0 left-0 w-full h-1 bg-slate-50 overflow-hidden">
          <div className="h-full w-1/3 bg-[#4E7D5B]"></div>
        </div>

        <div className="w-full max-w-md">
          {/* Header */}
          <div className="mb-12 text-left">
            <div className="flex items-center gap-3 mb-8">
              <div className="w-10 h-10 bg-[#4E7D5B] rounded-xl flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
              </div>
              <h1 className="text-2xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</h1>
            </div>
            <h2 className="text-4xl font-serif text-slate-900 mb-2 leading-tight">Create account</h2>
            <p className="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Curate your experiences</p>
          </div>

          {error && (
            <div className="p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center gap-3 text-xs font-bold mb-6">
              <AlertCircle className="w-4 h-4 shrink-0" />
              <span>{error}</span>
            </div>
          )}

          {/* Form */}
          <form onSubmit={handleRegister} className="space-y-5">
            {/* Name */}
            <div className="space-y-2 text-left">
              <label htmlFor="name" className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                Full Name
              </label>
              <div className="relative group">
                <div className="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                  <User className="w-4 h-4" />
                </div>
                <input 
                  id="name" 
                  type="text" 
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  required 
                  placeholder="Jane Doe"
                  className="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm"
                />
              </div>
            </div>

            {/* Email */}
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
                  placeholder="jane@example.com"
                  className="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm"
                />
              </div>
            </div>

            {/* Password */}
            <div className="space-y-2 text-left">
              <label htmlFor="password" className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                Password
              </label>
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
                  placeholder="••••••••"
                  className="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm"
                />
              </div>
            </div>

            {/* Password Confirmation */}
            <div className="space-y-2 text-left">
              <label htmlFor="password_confirmation" className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                Confirm Password
              </label>
              <div className="relative group">
                <div className="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                  <Lock className="w-4 h-4" />
                </div>
                <input 
                  id="password_confirmation" 
                  type="password" 
                  value={passwordConfirmation}
                  onChange={(e) => setPasswordConfirmation(e.target.value)}
                  required 
                  placeholder="••••••••"
                  className="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm"
                />
              </div>
            </div>

            {/* Submit */}
            <div className="pt-6 space-y-8">
              <button 
                type="submit"
                disabled={loading}
                className="w-full bg-[#4E7D5B] text-white py-5 rounded-full text-[11px] font-black uppercase tracking-[0.3em] shadow-xl shadow-[#4E7D5B]/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 cursor-pointer"
              >
                {loading ? 'Registering...' : 'Register Account'}
              </button>

              <p className="text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                Already have an account? <Link to="/login" className="text-[#4E7D5B] hover:underline underline-offset-4 decoration-2">Sign In</Link>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
