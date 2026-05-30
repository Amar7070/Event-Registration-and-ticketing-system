import React, { useState, useEffect } from 'react';
import SidebarLayout from '../components/SidebarLayout';
import api, { BACKEND_URL } from '../services/api';
import { 
  User, 
  Lock, 
  ShieldAlert, 
  Globe, 
  Smartphone, 
  UserCheck, 
  LogOut, 
  CheckCircle,
  Briefcase,
  AlertTriangle
} from 'lucide-react';

export default function Profile() {
  const [activeTab, setActiveTab] = useState('details');
  const [loading, setLoading] = useState(true);
  const [profile, setProfile] = useState(null);
  
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [timezone, setTimezone] = useState('Asia/Kolkata');
  const [preferredLanguage, setPreferredLanguage] = useState('en');
  const [avatar, setAvatar] = useState(null);
  const [detailsMessage, setDetailsMessage] = useState(null);
  const [updatingDetails, setUpdatingDetails] = useState(false);

  // Password Form State
  const [currentPassword, setCurrentPassword] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [passwordMessage, setPasswordMessage] = useState(null);
  const [updatingPassword, setUpdatingPassword] = useState(false);

  // Active Sessions State
  const [sessions, setSessions] = useState([]);
  const [sessionsLoading, setSessionsLoading] = useState(false);
  const [revokePassword, setRevokePassword] = useState('');
  const [revokeMessage, setRevokeMessage] = useState(null);
  const [revoking, setRevoking] = useState(false);

  // Organizer Apply State
  const [organizerMessage, setOrganizerMessage] = useState(null);
  const [applyingOrganizer, setApplyingOrganizer] = useState(false);

  // Identity Purge State
  const [deletePassword, setDeletePassword] = useState('');
  const [deleteMessage, setDeleteMessage] = useState(null);
  const [deletingAccount, setDeletingAccount] = useState(false);

  useEffect(() => {
    async function loadProfile() {
      try {
        setLoading(true);
        const res = await api.get('/profile');
        const u = res.data.user;
        setProfile(u);
        setName(u.name || '');
        setPhone(u.phone || '');
        setTimezone(u.timezone || 'Asia/Kolkata');
        setPreferredLanguage(u.preferred_language || 'en');
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    }
    loadProfile();
  }, []);

  useEffect(() => {
    if (activeTab === 'sessions') {
      loadSessions();
    }
  }, [activeTab]);

  const loadSessions = async () => {
    setSessionsLoading(true);
    try {
      const res = await api.get('/profile/sessions');
      setSessions(res.data.sessions || []);
    } catch (err) {
      console.error(err);
    } finally {
      setSessionsLoading(false);
    }
  };

  const handleUpdateDetails = async (e) => {
    e.preventDefault();
    setUpdatingDetails(true);
    setDetailsMessage(null);
    try {
      const formData = new FormData();
      formData.append('_method', 'PUT');
      formData.append('name', name);
      formData.append('phone', phone);
      formData.append('timezone', timezone);
      formData.append('preferred_language', preferredLanguage);
      if (avatar) {
        formData.append('avatar', avatar);
      }

      const res = await api.post('/profile', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      
      setProfile(res.data.user);
      setDetailsMessage({ type: 'success', text: res.data.message });
      // Update local storage username and avatar
      localStorage.setItem('user_name', res.data.user.name);
      if (res.data.user.avatar) {
        localStorage.setItem('user_avatar', res.data.user.avatar);
      }
      
      // Dispatch custom event to update Navbar globally
      window.dispatchEvent(new Event('profile-updated'));
    } catch (err) {
      console.error(err);
      setDetailsMessage({ type: 'error', text: err.response?.data?.message || 'Failed to update details.' });
    } finally {
      setUpdatingDetails(false);
    }
  };

  const handleUpdatePassword = async (e) => {
    e.preventDefault();
    setUpdatingPassword(true);
    setPasswordMessage(null);
    try {
      const res = await api.put('/profile/password', {
        current_password: currentPassword,
        password,
        password_confirmation: passwordConfirmation
      });
      setPasswordMessage({ type: 'success', text: res.data.message });
      setCurrentPassword('');
      setPassword('');
      setPasswordConfirmation('');
    } catch (err) {
      console.error(err);
      setPasswordMessage({ type: 'error', text: err.response?.data?.message || 'Password update failed.' });
    } finally {
      setUpdatingPassword(false);
    }
  };

  const handleApplyOrganizer = async () => {
    setApplyingOrganizer(true);
    setOrganizerMessage(null);
    try {
      const res = await api.post('/profile/apply-organizer');
      setOrganizerMessage({ type: 'success', text: res.data.message });
      // Reload profile
      const profRes = await api.get('/profile');
      setProfile(profRes.data.user);
      localStorage.setItem('user_role', profRes.data.user.role);
    } catch (err) {
      console.error(err);
      setOrganizerMessage({ type: 'error', text: err.response?.data?.message || 'Failed to submit application.' });
    } finally {
      setApplyingOrganizer(false);
    }
  };

  const handleRevokeSessions = async (e) => {
    e.preventDefault();
    if (!revokePassword) return;
    setRevoking(true);
    setRevokeMessage(null);
    try {
      const res = await api.delete('/profile/sessions', {
        data: { password: revokePassword }
      });
      setRevokeMessage({ type: 'success', text: res.data.message });
      setRevokePassword('');
      loadSessions();
    } catch (err) {
      console.error(err);
      setRevokeMessage({ type: 'error', text: err.response?.data?.message || 'Revocation request validation failed.' });
    } finally {
      setRevoking(false);
    }
  };

  const handleDeleteAccount = async (e) => {
    e.preventDefault();
    if (!deletePassword) return;
    if (!window.confirm("Are you absolutely sure you want to permanently delete your account? This action cannot be undone.")) return;
    
    setDeletingAccount(true);
    setDeleteMessage(null);
    try {
      await api.delete('/profile', {
        data: { password: deletePassword }
      });
      // Clear auth tokens and redirect to home
      localStorage.removeItem('token');
      localStorage.removeItem('api_token');
      localStorage.removeItem('user_name');
      localStorage.removeItem('user_role');
      window.location.href = '/';
    } catch (err) {
      console.error(err);
      setDeleteMessage({ type: 'error', text: err.response?.data?.message || 'Failed to delete account. Ensure your password is correct.' });
      setDeletingAccount(false);
    }
  };

  const timezones = [
    { value: 'Asia/Kolkata', label: 'Asia/Kolkata (IST)' },
    { value: 'UTC', label: 'Coordinated Universal Time (UTC)' },
    { value: 'America/New_York', label: 'America/New York (EST/EDT)' },
    { value: 'Europe/London', label: 'Europe/London (GMT/BST)' },
    { value: 'Europe/Paris', label: 'Europe/Paris (CET/CEST)' },
    { value: 'Asia/Tokyo', label: 'Asia/Tokyo (JST)' }
  ];

  return (
    <SidebarLayout type="user">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="pb-6 border-b border-slate-100 dark:border-slate-800">
          <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">GOVERNANCE CONSOLE</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Identity Settings</h1>
          <p className="text-xs text-slate-400 mt-1">Configure profile criteria, manage active login sessions, and claim hosting licenses.</p>
        </div>

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-450">Syncing identity node...</span>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            {/* Left Column: Tab Selectors */}
            <div className="lg:col-span-3 space-y-2">
              <button 
                onClick={() => setActiveTab('details')}
                className={`w-full text-left py-3.5 px-5 rounded-2xl border text-xs font-bold uppercase tracking-wider flex items-center gap-3 cursor-pointer transition-all duration-300 ${
                  activeTab === 'details' 
                    ? 'bg-primary text-white border-primary shadow-lg shadow-primary/10' 
                    : 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 hover:border-primary/20 text-slate-500 hover:text-primary'
                }`}
              >
                <User className="w-4.5 h-4.5" />
                <span>Profile Criteria</span>
              </button>

              <button 
                onClick={() => setActiveTab('security')}
                className={`w-full text-left py-3.5 px-5 rounded-2xl border text-xs font-bold uppercase tracking-wider flex items-center gap-3 cursor-pointer transition-all duration-300 ${
                  activeTab === 'security' 
                    ? 'bg-primary text-white border-primary shadow-lg shadow-primary/10' 
                    : 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 hover:border-primary/20 text-slate-500 hover:text-primary'
                }`}
              >
                <Lock className="w-4.5 h-4.5" />
                <span>Pass & Access</span>
              </button>

              <button 
                onClick={() => setActiveTab('sessions')}
                className={`w-full text-left py-3.5 px-5 rounded-2xl border text-xs font-bold uppercase tracking-wider flex items-center gap-3 cursor-pointer transition-all duration-300 ${
                  activeTab === 'sessions' 
                    ? 'bg-primary text-white border-primary shadow-lg shadow-primary/10' 
                    : 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 hover:border-primary/20 text-slate-500 hover:text-primary'
                }`}
              >
                <Smartphone className="w-4.5 h-4.5" />
                <span>Active Nodes</span>
              </button>
            </div>

            {/* Right Column: Tab View */}
            <div className="lg:col-span-9 space-y-6">
              
              {/* TAB: Details Settings */}
              {activeTab === 'details' && (
                <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm space-y-6 animate-slide-up">
                  <div>
                    <h3 className="text-xl font-serif text-slate-900 dark:text-white font-bold">Profile Details</h3>
                    <p className="text-xs text-slate-400 mt-1">Configure your identity characteristics for registrations and licenses.</p>
                  </div>

                  {detailsMessage && (
                    <div className={`p-4 rounded-xl text-xs font-bold uppercase tracking-wider ${
                      detailsMessage.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100'
                    }`}>
                      {detailsMessage.text}
                    </div>
                  )}

                  <form onSubmit={handleUpdateDetails} className="space-y-6">
                    {/* Avatar Upload */}
                    <div className="flex items-center gap-6 pb-6 border-b border-slate-100 dark:border-slate-800">
                      <div className="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden shrink-0">
                        {avatar ? (
                          <img src={URL.createObjectURL(avatar)} alt="Avatar preview" className="w-full h-full object-cover" />
                        ) : profile?.avatar ? (
                          <img src={profile.avatar.startsWith('http') ? profile.avatar : `${BACKEND_URL}${profile.avatar}`} alt="Avatar" className="w-full h-full object-cover" />
                        ) : (
                          <User className="w-8 h-8 text-slate-400" />
                        )}
                      </div>
                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Profile Photo</label>
                        <input 
                          type="file" 
                          accept="image/*"
                          onChange={(e) => setAvatar(e.target.files[0])}
                          className="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer"
                        />
                        <p className="text-[10px] text-slate-400 mt-2">Recommended: Square image, max 2MB.</p>
                      </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Display Name</label>
                        <input 
                          type="text" 
                          required
                          value={name}
                          onChange={(e) => setName(e.target.value)}
                          placeholder="Your Display Name" 
                          className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-primary"
                        />
                      </div>

                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Contact Number</label>
                        <input 
                          type="text" 
                          value={phone}
                          onChange={(e) => setPhone(e.target.value)}
                          placeholder="Phone Number" 
                          className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-primary"
                        />
                      </div>

                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Temporal Zone</label>
                        <select 
                          value={timezone}
                          onChange={(e) => setTimezone(e.target.value)}
                          required
                          className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer focus:border-primary"
                        >
                          {timezones.map((tz) => (
                            <option key={tz.value} value={tz.value}>{tz.label}</option>
                          ))}
                        </select>
                      </div>

                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Language Dialect</label>
                        <select 
                          value={preferredLanguage}
                          onChange={(e) => setPreferredLanguage(e.target.value)}
                          required
                          className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer focus:border-primary"
                        >
                          <option value="en">English (US/UK)</option>
                          <option value="hi">Hindi (हिन्दी)</option>
                        </select>
                      </div>
                    </div>

                    <button 
                      type="submit" 
                      disabled={updatingDetails}
                      className="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest cursor-pointer disabled:opacity-50"
                    >
                      {updatingDetails ? 'Updating Details...' : 'Update Details'}
                    </button>
                  </form>
                </div>
              )}

              {/* TAB: Pass & Access (Security) */}
              {activeTab === 'security' && (
                <div className="space-y-6 animate-slide-up">
                  
                  {/* Change Password Panel */}
                  <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm space-y-6">
                    <div>
                      <h3 className="text-xl font-serif text-slate-900 dark:text-white font-bold">Access Passwords</h3>
                      <p className="text-xs text-slate-400 mt-1">Configure passwords for authentication pass validations.</p>
                    </div>

                    {passwordMessage && (
                      <div className={`p-4 rounded-xl text-xs font-bold uppercase tracking-wider ${
                        passwordMessage.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100'
                      }`}>
                        {passwordMessage.text}
                      </div>
                    )}

                    <form onSubmit={handleUpdatePassword} className="space-y-6">
                      <div className="space-y-4">
                        <div>
                          <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Current Password</label>
                          <input 
                            type="password" 
                            required
                            value={currentPassword}
                            onChange={(e) => setCurrentPassword(e.target.value)}
                            placeholder="••••••••" 
                            className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none focus:border-primary"
                          />
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                          <div>
                            <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">New Password</label>
                            <input 
                              type="password" 
                              required
                              value={password}
                              onChange={(e) => setPassword(e.target.value)}
                              placeholder="••••••••" 
                              className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none focus:border-primary"
                            />
                          </div>
                          <div>
                            <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Confirm Password</label>
                            <input 
                              type="password" 
                              required
                              value={passwordConfirmation}
                              onChange={(e) => setPasswordConfirmation(e.target.value)}
                              placeholder="••••••••" 
                              className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none focus:border-primary"
                            />
                          </div>
                        </div>
                      </div>

                      <button 
                        type="submit" 
                        disabled={updatingPassword}
                        className="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest cursor-pointer disabled:opacity-50"
                      >
                        {updatingPassword ? 'Updating...' : 'Update Password'}
                      </button>
                    </form>
                  </div>

                  {/* Organizer apply panel */}
                  {profile && (profile.role === 'user' || profile.role === 'attendee') && (
                    <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm space-y-6">
                      <div className="flex items-start gap-4">
                        <div className="w-12 h-12 rounded-2xl bg-[#4E7D5B]/10 flex items-center justify-center text-primary shrink-0">
                          <Briefcase className="w-6 h-6" />
                        </div>
                        <div>
                          <h3 className="text-xl font-serif text-slate-900 dark:text-white font-bold">Host & Organizer Application</h3>
                          <p className="text-xs text-slate-400 mt-1 leading-relaxed">
                            Want to curate community experiences, manage tickets, scan QR admission passes, and access ledgers? Apply to become a verified Host.
                          </p>
                        </div>
                      </div>

                      {organizerMessage && (
                        <div className={`p-4 rounded-xl text-xs font-bold uppercase tracking-wider ${
                          organizerMessage.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100'
                        }`}>
                          {organizerMessage.text}
                        </div>
                      )}

                      <div className="pt-4 border-t border-slate-50 dark:border-slate-850 flex justify-end">
                        <button 
                          onClick={handleApplyOrganizer}
                          disabled={applyingOrganizer}
                          className="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest cursor-pointer disabled:opacity-50"
                        >
                          {applyingOrganizer ? 'Submitting Application...' : 'Apply for Host License'}
                        </button>
                      </div>
                    </div>
                  )}

                  {profile && profile.role === 'organizer' && !profile.is_approved && (
                    <div className="bg-amber-500/5 border border-amber-500/10 p-6 rounded-3xl flex items-start gap-4">
                      <AlertTriangle className="w-6 h-6 text-amber-600 shrink-0" />
                      <div>
                        <h4 className="text-sm font-bold text-slate-850 dark:text-slate-100">Application Pending Governance Review</h4>
                        <p className="text-xs text-slate-500 dark:text-slate-400 mt-1">
                          Your organizer credentials are under review by the Governance Council. Once approved, your host parameters will unlock.
                        </p>
                      </div>
                    </div>
                  )}

                  {/* Identity Purge Panel */}
                  <div className="bg-rose-50/50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/50 p-8 rounded-[2.5rem] shadow-sm space-y-6">
                    <div className="flex items-start gap-4">
                      <div className="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500 shrink-0">
                        <AlertTriangle className="w-6 h-6" />
                      </div>
                      <div>
                        <h3 className="text-xl font-serif text-slate-900 dark:text-white font-bold">Identity Purge</h3>
                        <p className="text-xs text-rose-500/70 mt-1 leading-relaxed">
                          Once your identity is purged, all of its resources and data will be permanently deleted. Before proceeding, please download any data or information that you wish to retain.
                        </p>
                      </div>
                    </div>

                    {deleteMessage && (
                      <div className={`p-4 rounded-xl text-xs font-bold uppercase tracking-wider ${
                        deleteMessage.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100'
                      }`}>
                        {deleteMessage.text}
                      </div>
                    )}

                    <form onSubmit={handleDeleteAccount} className="space-y-4 pt-4 border-t border-rose-100 dark:border-rose-900/50">
                      <div>
                        <label className="block text-[9px] font-black text-rose-500 uppercase tracking-widest mb-1.5 font-bold">Confirm with Password</label>
                        <input 
                          type="password" 
                          required
                          value={deletePassword}
                          onChange={(e) => setDeletePassword(e.target.value)}
                          placeholder="••••••••" 
                          className="w-full max-w-sm px-5 py-3.5 bg-white dark:bg-slate-900 border border-rose-100 dark:border-rose-900/50 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none focus:border-rose-300"
                        />
                      </div>
                      <div className="pt-2">
                        <button 
                          type="submit" 
                          disabled={deletingAccount}
                          className="bg-rose-500 text-white px-8 py-3.5 rounded-full text-[11px] font-black uppercase tracking-widest shadow-lg shadow-rose-500/20 hover:bg-rose-600 transition-all cursor-pointer disabled:opacity-50"
                        >
                          {deletingAccount ? 'Purging Identity...' : 'Permanently Delete Account'}
                        </button>
                      </div>
                    </form>
                  </div>

                </div>
              )}

              {/* TAB: Active Sessions */}
              {activeTab === 'sessions' && (
                <div className="space-y-6 animate-slide-up">
                  
                  {/* Sessions list */}
                  <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm space-y-6">
                    <div>
                      <h3 className="text-xl font-serif text-slate-900 dark:text-white font-bold">Active Device Nodes</h3>
                      <p className="text-xs text-slate-400 mt-1">Audit active sessions logged into your account across browsers and APIs.</p>
                    </div>

                    {sessionsLoading ? (
                      <div className="py-12 flex flex-col items-center gap-4">
                        <div className="w-8 h-8 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                        <span className="text-xs font-black uppercase tracking-widest text-slate-450">Scanning device nodes...</span>
                      </div>
                    ) : sessions.length > 0 ? (
                      <div className="space-y-4">
                        {sessions.map((sess) => (
                          <div 
                            key={sess.id}
                            className={`p-5 rounded-2xl border flex items-center justify-between gap-4 ${
                              sess.is_current_device 
                                ? 'bg-[#4E7D5B]/5 border-primary/20' 
                                : 'bg-slate-50/50 dark:bg-slate-950/20 border-slate-105 dark:border-slate-850'
                            }`}
                          >
                            <div className="flex items-center gap-4">
                              <div className={`w-10 h-10 rounded-xl flex items-center justify-center shrink-0 ${
                                sess.is_current_device ? 'bg-[#4E7D5B] text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400'
                              }`}>
                                <Smartphone className="w-5 h-5" />
                              </div>
                              <div className="min-w-0 text-left">
                                <h4 className="text-xs font-bold text-slate-800 dark:text-slate-100 truncate max-w-sm">
                                  {sess.user_agent || 'Unknown Client Device'}
                                </h4>
                                <div className="flex items-center gap-2 text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">
                                  <span>{sess.ip_address}</span>
                                  <span>&bull;</span>
                                  <span>Active: {sess.last_active}</span>
                                </div>
                              </div>
                            </div>
                            
                            {sess.is_current_device && (
                              <span className="px-2.5 py-0.5 rounded-full bg-[#4E7D5B]/15 text-[#4E7D5B] border border-primary/20 text-[8px] font-black uppercase tracking-wider shrink-0">
                                Current
                              </span>
                            )}
                          </div>
                        ))}
                      </div>
                    ) : (
                      <div className="py-8 text-center text-slate-400 text-xs">
                        No active login sessions synchronized.
                      </div>
                    )}
                  </div>

                  {/* Revoke other sessions */}
                  {sessions.length > 1 && (
                    <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm space-y-6">
                      <div>
                        <h3 className="text-xl font-serif text-rose-500 font-bold">Revoke Other Nodes</h3>
                        <p className="text-xs text-slate-400 mt-1">Terminate all active device sessions and API tokens except for this current window.</p>
                      </div>

                      {revokeMessage && (
                        <div className={`p-4 rounded-xl text-xs font-bold uppercase tracking-wider ${
                          revokeMessage.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100'
                        }`}>
                          {revokeMessage.text}
                        </div>
                      )}

                      <form onSubmit={handleRevokeSessions} className="space-y-4">
                        <div>
                          <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Validate Password</label>
                          <input 
                            type="password" 
                            required
                            value={revokePassword}
                            onChange={(e) => setRevokePassword(e.target.value)}
                            placeholder="Enter current password to verify" 
                            className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none focus:border-rose-400"
                          />
                        </div>

                        <div className="flex justify-end pt-2">
                          <button 
                            type="submit" 
                            disabled={revoking || !revokePassword}
                            className="px-8 py-3.5 bg-rose-500 hover:bg-rose-600 text-white rounded-full text-xs font-black uppercase tracking-widest cursor-pointer disabled:opacity-50 flex items-center gap-2 shadow-lg shadow-rose-500/10"
                          >
                            <LogOut className="w-4 h-4" />
                            <span>{revoking ? 'Revoking Sessions...' : 'Revoke Other Sessions'}</span>
                          </button>
                        </div>
                      </form>
                    </div>
                  )}

                </div>
              )}

            </div>
          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
