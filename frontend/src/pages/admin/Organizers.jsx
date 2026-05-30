import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  Users, 
  Search, 
  Check, 
  X, 
  ShieldAlert, 
  Info,
  ChevronLeft,
  ChevronRight,
  UserCheck,
  UserX,
  Award,
  Calendar,
  DollarSign,
  Briefcase
} from 'lucide-react';

export default function Organizers() {
  const [activeTab, setActiveTab] = useState('pending'); // 'pending' or 'all'
  const [organizers, setOrganizers] = useState([]);
  const [loading, setLoading] = useState(true);
  
  // Pagination
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  
  // Search & Filter
  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState(''); // '', 'active', 'suspended'

  // Rejection modal
  const [selectedOrg, setSelectedOrg] = useState(null);
  const [rejectReason, setRejectReason] = useState('Insufficient Host Curation Criteria');
  const [rejectModalOpen, setRejectModalOpen] = useState(false);
  const [rejecting, setRejecting] = useState(false);

  // Detail Modal
  const [detailOrg, setDetailOrg] = useState(null);
  const [detailModalOpen, setDetailModalOpen] = useState(false);
  const [detailEvents, setDetailEvents] = useState([]);
  const [loadingDetail, setLoadingDetail] = useState(false);

  useEffect(() => {
    setPage(1);
    fetchData(1);
  }, [activeTab, statusFilter]);

  useEffect(() => {
    fetchData(page);
  }, [page]);

  const fetchData = async (currentPage) => {
    try {
      setLoading(true);
      if (activeTab === 'pending') {
        const res = await api.get(`/admin/organizers/pending?page=${currentPage}`);
        setOrganizers(res.data.organizers.data || []);
        setTotalPages(res.data.organizers.last_page || 1);
      } else {
        const res = await api.get(`/admin/organizers?page=${currentPage}&search=${search}&status=${statusFilter}`);
        setOrganizers(res.data.organizers.data || []);
        setTotalPages(res.data.organizers.last_page || 1);
      }
    } catch (err) {
      console.error('Failed to fetch organizers:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    setPage(1);
    fetchData(1);
  };

  const handleApprove = async (org) => {
    if (!window.confirm(`Approve Host License for "${org.name}"?`)) return;
    try {
      const res = await api.post(`/admin/organizers/${org.id}/approve`);
      alert(res.data.message);
      fetchData(page);
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to approve organizer.');
    }
  };

  const handleOpenRejectModal = (org) => {
    setSelectedOrg(org);
    setRejectReason('Insufficient Host Curation Criteria');
    setRejectModalOpen(true);
  };

  const handleReject = async (e) => {
    e.preventDefault();
    if (!selectedOrg) return;

    setRejecting(true);
    try {
      const res = await api.post(`/admin/organizers/${selectedOrg.id}/reject`, {
        reason: rejectReason
      });
      alert(res.data.message);
      setRejectModalOpen(false);
      fetchData(page);
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to reject application.');
    } finally {
      setRejecting(false);
    }
  };

  const handleToggleStatus = async (org) => {
    const actionText = org.is_active ? 'suspend' : 'activate';
    if (!window.confirm(`Are you sure you want to ${actionText} host "${org.name}"?`)) return;
    
    try {
      const res = await api.post(`/admin/organizers/${org.id}/toggle-status`);
      alert(res.data.message);
      fetchData(page);
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to update host status.');
    }
  };

  const handleOpenDetail = async (org) => {
    setDetailOrg(org);
    setDetailModalOpen(true);
    setLoadingDetail(true);
    try {
      const res = await api.get(`/admin/organizers/${org.id}`);
      setDetailEvents(res.data.events || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoadingDetail(false);
    }
  };

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">LICENSE DISPENSATION MATRIX</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Organizer Governance</h1>
            <p className="text-xs text-slate-400 mt-1">Auditpending applications, regulate active hosts, and inspect ecosystem yields.</p>
          </div>

          <div className="flex items-center gap-3">
            <button 
              onClick={() => setActiveTab(activeTab === 'pending' ? 'all' : 'pending')}
              className="px-5 py-3 rounded-full border border-primary/20 text-xs font-black uppercase tracking-widest text-primary bg-primary/5 hover:bg-primary hover:text-white transition-all flex items-center gap-2 cursor-pointer"
            >
              {activeTab === 'pending' ? 'View All Host Directory' : 'View Pending Applications'}
            </button>
          </div>
        </div>

        {/* Filters Panel for All Hosts directory */}
        {activeTab === 'all' && (
          <form onSubmit={handleSearchSubmit} className="grid grid-cols-1 md:grid-cols-12 gap-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm">
            <div className="md:col-span-6 relative flex items-center bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 px-4">
              <Search className="w-4 h-4 text-slate-400 shrink-0" />
              <input 
                type="text" 
                placeholder="Search hosts by name or email..." 
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="flex-1 bg-transparent border-none text-xs py-3.5 px-3 placeholder:text-slate-400 text-slate-800 dark:text-slate-200 outline-none"
              />
            </div>
            
            <div className="md:col-span-4">
              <select 
                value={statusFilter}
                onChange={(e) => setStatusFilter(e.target.value)}
                className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer"
              >
                <option value="">All Statuses</option>
                <option value="active">Active Nodes</option>
                <option value="suspended">Suspended Nodes</option>
              </select>
            </div>

            <div className="md:col-span-2">
              <button 
                type="submit"
                className="w-full btn-primary py-3.5 text-xs font-black uppercase tracking-wider cursor-pointer"
              >
                Filter Nodes
              </button>
            </div>
          </form>
        )}

        {/* Toggle indicators */}
        <div className="flex border-b border-slate-100 dark:border-slate-800">
          <button 
            onClick={() => setActiveTab('pending')}
            className={`px-6 py-3 text-xs font-black uppercase tracking-widest border-b-2 transition-all cursor-pointer ${
              activeTab === 'pending' 
                ? 'border-primary text-primary font-bold' 
                : 'border-transparent text-slate-400 hover:text-slate-600'
            }`}
          >
            Pending Applications
          </button>
          <button 
            onClick={() => setActiveTab('all')}
            className={`px-6 py-3 text-xs font-black uppercase tracking-widest border-b-2 transition-all cursor-pointer ${
              activeTab === 'all' 
                ? 'border-primary text-primary font-bold' 
                : 'border-transparent text-slate-400 hover:text-slate-600'
            }`}
          >
            All Host Directory
          </button>
        </div>

        {/* Listings */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-400">Syncing database registers...</span>
          </div>
        ) : organizers.length > 0 ? (
          <div className="space-y-6 animate-slide-up">
            {activeTab === 'pending' ? (
              /* Pending Applications layout */
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {organizers.map((org) => (
                  <div 
                    key={org.id}
                    className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md"
                  >
                    <div className="space-y-4">
                      <div className="flex items-center gap-3">
                        <div className="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black text-sm uppercase">
                          {org.name.substring(0, 2)}
                        </div>
                        <div>
                          <h4 className="font-bold text-slate-900 dark:text-white text-sm">{org.name}</h4>
                          <span className="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">{org.email}</span>
                        </div>
                      </div>

                      <div className="bg-slate-50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-800 p-4 rounded-2xl flex items-start gap-2.5 text-xs text-slate-500 leading-relaxed">
                        <Info className="w-4 h-4 text-rose-500 shrink-0 mt-0.5" />
                        <p>
                          This applicant has requested host parameters to curate marketplace gatherings. Verify that their identity is clean and validated before approval.
                        </p>
                      </div>
                    </div>

                    <div className="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                      <button 
                        onClick={() => handleApprove(org)}
                        className="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer border border-emerald-100"
                      >
                        <Check className="w-3.5 h-3.5 stroke-[3]" />
                        <span>Approve License</span>
                      </button>
                      <button 
                        onClick={() => handleOpenRejectModal(org)}
                        className="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer border border-rose-100"
                      >
                        <X className="w-3.5 h-3.5" />
                        <span>Reject Application</span>
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              /* All Directory layout (Table matches Blade exactly) */
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse">
                    <thead>
                      <tr className="bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800">
                        <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Host Node Details</th>
                        <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Governance Status</th>
                        <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Experiences</th>
                        <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Ecosystem Yield</th>
                        <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Commission Date</th>
                        <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Actions</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 dark:divide-slate-800/50">
                      {organizers.map((org) => (
                        <tr key={org.id} className="group hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-all duration-300">
                          <td className="px-8 py-6">
                            <div className="flex items-center gap-4">
                              <div className="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black text-sm group-hover:scale-105 transition-transform duration-500">
                                {org.name.substring(0, 2).toUpperCase()}
                              </div>
                              <div>
                                <p className="font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors text-sm leading-tight">{org.name}</p>
                                <p className="text-[10px] text-slate-450 font-bold uppercase tracking-wider mt-0.5">{org.email}</p>
                              </div>
                            </div>
                          </td>
                          <td className="px-8 py-6">
                            {org.is_active ? (
                              <span className="bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Active Node</span>
                            ) : (
                              <span className="bg-rose-500/10 text-rose-600 border border-rose-500/20 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Suspended</span>
                            )}
                          </td>
                          <td className="px-8 py-6 text-xs text-slate-700 dark:text-slate-300 font-bold">
                            {org.organized_events_count} Events
                          </td>
                          <td className="px-8 py-6 text-xs text-slate-900 dark:text-white font-black">
                            ₹{org.total_revenue.toLocaleString('en-IN')}
                          </td>
                          <td className="px-8 py-6">
                            <p className="text-xs font-bold text-slate-700 dark:text-slate-300">
                              {new Date(org.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                            </p>
                          </td>
                          <td className="px-8 py-6 text-right">
                            <div className="flex items-center justify-end gap-3">
                              <button 
                                onClick={() => handleOpenDetail(org)}
                                className="inline-flex items-center gap-1 px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-850 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all cursor-pointer"
                              >
                                Audit Hub
                              </button>
                              <button 
                                onClick={() => handleToggleStatus(org)}
                                className={`inline-flex items-center gap-1.5 px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all cursor-pointer ${
                                  org.is_active 
                                    ? 'bg-rose-50 text-rose-600 hover:bg-rose-100' 
                                    : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100'
                                }`}
                              >
                                {org.is_active ? 'Suspend' : 'Activate'}
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            )}

            {/* Pagination Controls */}
            {totalPages > 1 && (
              <div className="flex items-center justify-center gap-4 pt-6">
                <button 
                  onClick={() => setPage(p => Math.max(1, p - 1))}
                  disabled={page === 1}
                  className="p-2 border border-slate-100 dark:border-slate-800 rounded-xl hover:text-primary transition-colors disabled:opacity-30 cursor-pointer bg-white dark:bg-slate-900"
                >
                  <ChevronLeft className="w-4 h-4" />
                </button>
                <span className="text-xs font-black uppercase tracking-widest text-slate-500 select-none">
                  Page {page} of {totalPages}
                </span>
                <button 
                  onClick={() => setPage(p => Math.min(totalPages, p + 1))}
                  disabled={page === totalPages}
                  className="p-2 border border-slate-100 dark:border-slate-800 rounded-xl hover:text-primary transition-colors disabled:opacity-30 cursor-pointer bg-white dark:bg-slate-900"
                >
                  <ChevronRight className="w-4 h-4" />
                </button>
              </div>
            )}
          </div>
        ) : (
          <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl max-w-md mx-auto">
            <Users className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No results found</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[245px] mx-auto leading-relaxed">
              No organizer accounts matched your selection or filters.
            </p>
          </div>
        )}

        {/* Rejection Modal */}
        {rejectModalOpen && selectedOrg && (
          <div className="fixed inset-0 z-[120] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all animate-fade-in">
            <div className="relative w-full max-w-md bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-3xl p-8 overflow-hidden text-left">
              
              <div className="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div className="flex items-center gap-3 text-rose-500">
                  <ShieldAlert className="w-5 h-5" />
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold leading-tight">Reject Application</h3>
                </div>
                <button onClick={() => setRejectModalOpen(false)} className="text-slate-400 hover:text-slate-650 transition-colors cursor-pointer">
                  <X className="w-5 h-5" />
                </button>
              </div>

              <form onSubmit={handleReject} className="space-y-4">
                <p className="text-xs text-slate-400 leading-normal">
                  Rejecting this organizer license request will revert their status to attendee.
                </p>

                <div>
                  <label className="block text-[9px] font-black text-slate-450 uppercase tracking-widest mb-1.5 font-bold">Audit Rejection Reason</label>
                  <textarea 
                    rows="4" 
                    required
                    value={rejectReason}
                    onChange={(e) => setRejectReason(e.target.value)}
                    placeholder="Describe specific licensing rules, insufficient references, or validation failures..."
                    className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none"
                  ></textarea>
                </div>

                <div className="pt-4 flex gap-4">
                  <button 
                    type="button" 
                    onClick={() => setRejectModalOpen(false)} 
                    className="flex-1 py-3.5 border border-slate-205 text-[10px] font-black uppercase tracking-wider text-slate-600 hover:border-slate-350 transition-all cursor-pointer bg-white"
                  >
                    Cancel
                  </button>
                  <button 
                    type="submit" 
                    disabled={rejecting}
                    className="flex-1 py-3.5 bg-rose-500 text-white text-[10px] font-black uppercase tracking-wider hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/20 cursor-pointer disabled:opacity-50"
                  >
                    {rejecting ? 'Rejecting...' : 'Reject License'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        )}

        {/* Audit Detail Modal */}
        {detailModalOpen && detailOrg && (
          <div className="fixed inset-0 z-[120] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all animate-fade-in">
            <div className="relative w-full max-w-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-3xl p-8 overflow-hidden text-left flex flex-col max-h-[85vh]">
              
              <div className="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
                <div className="flex items-center gap-3 text-primary">
                  <Award className="w-5 h-5" />
                  <h3 className="text-xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Host Audit Hub</h3>
                </div>
                <button onClick={() => setDetailModalOpen(false)} className="text-slate-400 hover:text-slate-650 transition-colors cursor-pointer">
                  <X className="w-5 h-5" />
                </button>
              </div>

              <div className="overflow-y-auto pr-2 space-y-6 flex-1">
                {/* Meta details */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-6 bg-slate-50 dark:bg-slate-950 p-6 rounded-2xl border border-slate-100 dark:border-slate-800">
                  <div>
                    <span className="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Host Entity</span>
                    <h4 className="text-sm font-bold text-slate-900 dark:text-white">{detailOrg.name}</h4>
                    <p className="text-[10px] text-slate-500 mt-0.5">{detailOrg.email}</p>
                  </div>
                  <div>
                    <span className="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Experiences Deployed</span>
                    <h4 className="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5"><Briefcase className="w-3.5 h-3.5 text-primary" /> {detailOrg.organized_events_count} Event blueprints</h4>
                  </div>
                  <div>
                    <span className="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Ecosystem Yield</span>
                    <h4 className="text-sm font-bold text-emerald-600 flex items-center gap-1"><DollarSign className="w-3.5 h-3.5" /> ₹{detailOrg.total_revenue.toLocaleString('en-IN')} Gross</h4>
                  </div>
                </div>

                <div>
                  <h4 className="text-xs font-black uppercase tracking-wider text-slate-450 mb-3.5">Deployment Log Blueprints</h4>
                  {loadingDetail ? (
                    <div className="py-12 text-center text-xs font-bold text-slate-400 animate-pulse uppercase tracking-wider">Syncing log index...</div>
                  ) : detailEvents.length > 0 ? (
                    <div className="space-y-3">
                      {detailEvents.map((evt) => (
                        <div key={evt.id} className="p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center justify-between text-xs hover:border-primary/20 transition-all">
                          <div>
                            <h5 className="font-bold text-slate-900 dark:text-white">{evt.title}</h5>
                            <div className="flex items-center gap-2 text-[9px] text-slate-400 uppercase tracking-widest mt-1">
                              <span>Format: {evt.type}</span>
                              <span>&bull;</span>
                              <span>Capacity: {evt.total_capacity}</span>
                            </div>
                          </div>
                          <span className={`px-2.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider ${
                            evt.status === 'published' ? 'bg-emerald-50 text-emerald-600' :
                            evt.status === 'draft' ? 'bg-slate-100 text-slate-400' :
                            'bg-amber-50 text-amber-600'
                          }`}>
                            {evt.status}
                          </span>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="py-8 text-center text-xs text-slate-400 font-serif italic border border-dashed rounded-xl">
                      No experiences deployed by this host node yet.
                    </div>
                  )}
                </div>
              </div>

              <div className="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3 shrink-0">
                <button 
                  onClick={() => setDetailModalOpen(false)}
                  className="px-6 py-3 bg-slate-105 hover:bg-slate-150 border border-slate-200 dark:border-slate-800 text-[10px] font-black uppercase tracking-wider rounded-xl cursor-pointer dark:bg-slate-900 text-slate-500"
                >
                  Close Audit Sheet
                </button>
              </div>

            </div>
          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
