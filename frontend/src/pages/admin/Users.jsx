import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  Users, 
  Search, 
  ShieldCheck, 
  Ban, 
  CheckCircle,
  HelpCircle,
  Clock,
  UserCheck
} from 'lucide-react';

export default function UsersList() {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [roleFilter, setRoleFilter] = useState('');

  useEffect(() => {
    fetchUsers();
  }, []);

  const fetchUsers = async () => {
    try {
      setLoading(true);
      const res = await api.get('/admin/users');
      setUsers(res.data.data || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleToggleState = async (user) => {
    const nextState = !user.is_active;
    const confirmMsg = nextState 
      ? `Re-activate user account "${user.name}"?`
      : `Restrict / Suspend user account "${user.name}"?`;
    
    if (!window.confirm(confirmMsg)) return;

    try {
      const res = await api.put(`/admin/users/${user.id}`, {
        is_active: nextState
      });
      alert(res.data.message);
      // Refresh list
      fetchUsers();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to update user status.');
    }
  };

  const handleRoleChange = async (user, newRole) => {
    if (!window.confirm(`Are you sure you want to change the role of "${user.name}" to "${newRole}"?`)) {
      // Force refresh to reset dropdown to previous state
      fetchUsers();
      return;
    }

    try {
      const res = await api.put(`/admin/users/${user.id}`, {
        role: newRole
      });
      alert(res.data.message);
      fetchUsers();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to update user role.');
      fetchUsers();
    }
  };

  const filteredUsers = users.filter(u => {
    const matchesSearch = 
      u.name.toLowerCase().includes(search.toLowerCase()) ||
      u.email.toLowerCase().includes(search.toLowerCase());
    
    const matchesRole = roleFilter === '' || u.role === roleFilter;

    return matchesSearch && matchesRole;
  });

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">IDENTITY REGISTRY</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">User Directory</h1>
            <p className="text-xs text-slate-400 mt-1">Audit active registration accounts, roles, and administrative active states.</p>
          </div>

          <div className="flex flex-wrap items-center gap-3">
            <div className="relative flex items-center bg-white dark:bg-slate-900 rounded-full border border-slate-100 dark:border-slate-800 px-4 py-1.5 shadow-sm max-w-xs">
              <Search className="w-4 h-4 text-slate-400 shrink-0" />
              <input 
                type="text" 
                placeholder="Search name, email..." 
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="flex-1 bg-transparent border-none text-xs py-2 px-2 placeholder:text-slate-400 text-slate-800 dark:text-slate-205 outline-none"
              />
            </div>

            <select 
              value={roleFilter}
              onChange={(e) => setRoleFilter(e.target.value)}
              className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-full text-xs font-bold text-slate-500 py-2.5 px-4 cursor-pointer outline-none shadow-sm"
            >
              <option value="">All Roles</option>
              <option value="user">Attendees (User)</option>
              <option value="organizer">Organizers (Host)</option>
              <option value="admin">Administrators</option>
            </select>
          </div>
        </div>

        {/* User registry Table */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-455">Retrieving user nodes...</span>
          </div>
        ) : filteredUsers.length > 0 ? (
          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
            <div className="overflow-x-auto">
              <table className="w-full text-xs text-left">
                <thead className="bg-slate-50 dark:bg-slate-950/20 text-slate-400 font-black uppercase tracking-widest border-b border-slate-100 dark:border-slate-800">
                  <tr>
                    <th className="px-6 py-4.5">Account Details</th>
                    <th className="px-6 py-4.5">Role Classification</th>
                    <th className="px-6 py-4.5">Registration Date</th>
                    <th className="px-6 py-4.5">Account State</th>
                    <th className="px-6 py-4.5">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                  {filteredUsers.map((u) => {
                    const isActive = !!u.is_active;
                    return (
                      <tr key={u.id} className="hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-colors">
                        <td className="px-6 py-5">
                          <div className="font-bold text-slate-850 dark:text-white text-sm">{u.name}</div>
                          <div className="text-[10px] text-slate-400 mt-1">{u.email}</div>
                        </td>
                        <td className="px-6 py-5 uppercase tracking-wider text-[10px]">
                          <select
                            value={u.role}
                            onChange={(e) => handleRoleChange(u, e.target.value)}
                            className={`px-2.5 py-1 rounded-full font-black text-[10px] uppercase tracking-wider border cursor-pointer outline-none transition-all duration-200 ${
                              u.role === 'admin' 
                                ? 'bg-rose-50 border-rose-100 text-rose-700 dark:bg-rose-950/20 dark:border-rose-900/50' 
                                : u.role === 'organizer' 
                                ? 'bg-emerald-50 border-emerald-100 text-emerald-700 dark:bg-emerald-950/20 dark:border-emerald-900/50' 
                                : 'bg-slate-100 border-slate-205 text-slate-600 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-350'
                            }`}
                          >
                            <option value="attendee" className="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Attendee</option>
                            <option value="organizer" className="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Organizer</option>
                            <option value="admin" className="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">Admin</option>
                          </select>
                        </td>
                        <td className="px-6 py-5 text-slate-500">
                          {new Date(u.created_at).toLocaleDateString()}
                        </td>
                        <td className="px-6 py-5">
                          <span className={`px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider ${
                            isActive 
                              ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20' 
                              : 'bg-rose-50 text-rose-700 dark:bg-rose-950/20'
                          }`}>
                            {isActive ? 'Active' : 'Suspended'}
                          </span>
                        </td>
                        <td className="px-6 py-5">
                          {u.role !== 'admin' && (
                            <button 
                              onClick={() => handleToggleState(u)}
                              className={`px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer border ${
                                isActive 
                                  ? 'bg-rose-50 hover:bg-rose-100 text-rose-700 border-rose-100' 
                                  : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-100'
                              }`}
                            >
                              {isActive ? (
                                <>
                                  <Ban className="w-3.5 h-3.5" />
                                  <span>Suspend</span>
                                </>
                              ) : (
                                <>
                                  <CheckCircle className="w-3.5 h-3.5" />
                                  <span>Activate</span>
                                </>
                              )}
                            </button>
                          )}
                        </td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>
          </div>
        ) : (
          <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl max-w-md mx-auto">
            <Users className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No users found</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              No registered user nodes match the active search criteria or filters.
            </p>
          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
