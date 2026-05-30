import React, { useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';

export default function AuthCallback() {
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    const params = new URLSearchParams(location.search);
    const token = params.get('token');
    const name = params.get('name');
    const role = params.get('role');
    const isApproved = params.get('is_approved');

    if (token) {
      localStorage.setItem('token', token);
      if (name) localStorage.setItem('user_name', name);
      if (role) localStorage.setItem('user_role', role);
      if (isApproved) localStorage.setItem('is_approved', isApproved);

      if (role === 'admin') {
        navigate('/admin/dashboard');
      } else if (role === 'organizer') {
        if (isApproved === '1' || isApproved === 'true') {
          navigate('/organizer/analytics');
        } else {
          navigate('/profile');
        }
      } else {
        navigate('/dashboard');
      }
    } else {
      navigate('/login?error=Authentication+failed');
    }
  }, [navigate, location]);

  return (
    <div className="min-h-screen flex items-center justify-center bg-[#FAF9F5]">
      <div className="flex flex-col items-center gap-4">
        <div className="w-12 h-12 border-4 border-[#4E7D5B] border-t-transparent rounded-full animate-spin"></div>
        <p className="text-xs font-black uppercase tracking-widest text-slate-400">Authenticating...</p>
      </div>
    </div>
  );
}
