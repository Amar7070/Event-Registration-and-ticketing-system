import React, { useState } from 'react';
import { useNavigate, useSearchParams, Link } from 'react-router-dom';
import api, { getCsrfCookie } from '../services/api';
import { Lock, Mail, AlertCircle, CheckCircle } from 'lucide-react';

export default function ResetPassword() {
  const [searchParams] = useSearchParams();
  const token = searchParams.get('token') || '';
  const emailParam = searchParams.get('email') || '';

  const [email, setEmail] = useState(emailParam);
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [status, setStatus] = useState(null);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleReset = async (e) => {
    e.preventDefault();
    setError(null);
    setStatus(null);

    if (password !== passwordConfirmation) {
      setError('Passwords do not match.');
      return;
    }

    setLoading(true);

    try {
      await getCsrfCookie();
      const response = await api.post('/auth/reset-password', {
        token,
        email: email.trim(),
        password,
        password_confirmation: passwordConfirmation,
      });

      setStatus(response.data.status || 'Your password has been reset!');
      setTimeout(() => navigate('/login'), 2000);
    } catch (err) {
      console.error(err);
      if (err.response && err.response.data && err.response.data.message) {
        setError(err.response.data.message);
      } else {
        setError('Failed to reset password. Check connection or token expiry.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-[#FDFBF7] flex items-center justify-center px-6">
      <div className="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-10 rounded-[2.5rem] shadow-xl text-left">
        <div className="flex items-center gap-3 mb-8">
          <div className="w-10 h-10 bg-[#4E7D5B] rounded-xl flex items-center justify-center text-white">
            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
              <path strokeLinecap="round" strokeLinejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
          </div>
          <h1 className="text-xl font-serif font-black tracking-tighter text-[#4E7D5B]">SmartEvent</h1>
        </div>

        <h2 className="text-3xl font-serif text-slate-900 dark:text-white mb-2 leading-tight">Reset Password</h2>
        <p className="text-xs text-slate-450 dark:text-slate-400 mb-8">Choose your new secure passcode.</p>

        {status && (
          <div className="p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 text-xs font-bold mb-6">
            <CheckCircle className="w-4 h-4 shrink-0" />
            <span>{status}</span>
          </div>
        )}

        {error && (
          <div className="p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center gap-3 text-xs font-bold mb-6">
            <AlertCircle className="w-4 h-4 shrink-0" />
            <span>{error}</span>
          </div>
        )}

        <form onSubmit={handleReset} className="space-y-6">
          <div className="space-y-2">
            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
            <div className="relative group">
              <div className="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                <Mail className="w-4 h-4" />
              </div>
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm"
              />
            </div>
          </div>

          <div className="space-y-2">
            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
            <div className="relative group">
              <div className="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                <Lock className="w-4 h-4" />
              </div>
              <input
                type="password"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm"
              />
            </div>
          </div>

          <div className="space-y-2">
            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm New Password</label>
            <div className="relative group">
              <div className="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4E7D5B] transition-colors">
                <Lock className="w-4 h-4" />
              </div>
              <input
                type="password"
                required
                value={passwordConfirmation}
                onChange={(e) => setPasswordConfirmation(e.target.value)}
                placeholder="••••••••"
                className="w-full pl-14 pr-6 py-4 bg-white border border-slate-100 rounded-full text-sm font-medium focus:border-[#4E7D5B]/50 focus:ring-4 focus:ring-[#4E7D5B]/5 outline-none transition-all shadow-sm"
              />
            </div>
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-[#4E7D5B] text-white py-5 rounded-full text-[11px] font-black uppercase tracking-[0.3em] shadow-xl shadow-[#4E7D5B]/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 cursor-pointer"
          >
            {loading ? 'Resetting Password...' : 'Reset Password'}
          </button>
        </form>
      </div>
    </div>
  );
}
