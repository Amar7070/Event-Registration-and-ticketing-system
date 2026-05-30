import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  Ticket, 
  Search, 
  Power, 
  Trash2, 
  Plus, 
  X, 
  Calendar,
  AlertTriangle,
  Info,
  Layers,
  ChevronLeft,
  ChevronRight,
  TrendingDown,
  Activity
} from 'lucide-react';

export default function Coupons() {
  const [coupons, setCoupons] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  // Editing modal states
  const [editingCoupon, setEditingCoupon] = useState(null);
  const [editModalOpen, setEditModalOpen] = useState(false);
  const [type, setType] = useState('percentage');
  const [value, setValue] = useState(0);
  const [minOrder, setMinOrder] = useState('');
  const [maxDiscount, setMaxDiscount] = useState('');
  const [usageLimit, setUsageLimit] = useState('');
  const [usagePerUser, setUsagePerUser] = useState(1);
  const [validFrom, setValidFrom] = useState('');
  const [validUntil, setValidUntil] = useState('');
  const [isActive, setIsActive] = useState(true);
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    fetchCoupons();
  }, []);

  const fetchCoupons = async () => {
    try {
      setLoading(true);
      const res = await api.get('/admin/coupons');
      setCoupons(res.data.coupons || []);
    } catch (err) {
      console.error('Failed to load coupons:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleToggleActive = async (coupon) => {
    try {
      const res = await api.put(`/admin/coupons/${coupon.id}`, { toggle_active: true });
      alert(res.data.message);
      fetchCoupons();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to toggle active state.');
    }
  };

  const handlePurge = async (coupon) => {
    if (!window.confirm(`Purge coupon code "${coupon.code}" from ecosystem? This action is irreversible.`)) return;
    try {
      const res = await api.delete(`/admin/coupons/${coupon.id}`);
      alert(res.data.message);
      fetchCoupons();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to purge coupon code.');
    }
  };

  const handleOpenEditModal = (coupon) => {
    setEditingCoupon(coupon);
    setType(coupon.type || 'percentage');
    setValue(coupon.value || 0);
    setMinOrder(coupon.min_order_amount || '');
    setMaxDiscount(coupon.max_discount || '');
    setUsageLimit(coupon.usage_limit || '');
    setUsagePerUser(coupon.usage_per_user || 1);
    
    // Format dates to YYYY-MM-DDTHH:MM
    if (coupon.valid_from) {
      setValidFrom(coupon.valid_from.substring(0, 16));
    }
    if (coupon.valid_until) {
      setValidUntil(coupon.valid_until.substring(0, 16));
    }
    
    setIsActive(!!coupon.is_active);
    setEditModalOpen(true);
  };

  const handleEditSubmit = async (e) => {
    e.preventDefault();
    if (!editingCoupon) return;

    setSubmitting(true);
    try {
      const payload = {
        type,
        value: parseFloat(value),
        min_order_amount: minOrder ? parseFloat(minOrder) : null,
        max_discount: maxDiscount ? parseFloat(maxDiscount) : null,
        usage_limit: usageLimit ? parseInt(usageLimit) : null,
        usage_per_user: parseInt(usagePerUser),
        valid_from: validFrom,
        valid_until: validUntil,
        is_active: isActive
      };

      const res = await api.put(`/admin/coupons/${editingCoupon.id}`, payload);
      alert(res.data.message);
      setEditModalOpen(false);
      fetchCoupons();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to update coupon properties.');
    } finally {
      setSubmitting(false);
    }
  };

  const filteredCoupons = coupons.filter(c => 
    c.code.toLowerCase().includes(search.toLowerCase()) ||
    (c.organizer?.name || '').toLowerCase().includes(search.toLowerCase()) ||
    (c.event?.title || '').toLowerCase().includes(search.toLowerCase())
  );

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">PROMOTIONAL VECTORS</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Ecosystem Coupons</h1>
            <p className="text-xs text-slate-400 mt-1">Govern discount vectors, active scopes, and promotional campaigns across all event nodes.</p>
          </div>

          <div className="relative flex items-center bg-white dark:bg-slate-900 rounded-full border border-slate-100 dark:border-slate-800 px-4 py-1.5 shadow-sm max-w-xs w-full">
            <Search className="w-4 h-4 text-slate-400 shrink-0" />
            <input 
              type="text" 
              placeholder="Search coupon codes, events, hosts..." 
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="flex-1 bg-transparent border-none text-xs py-2 px-2.5 placeholder:text-slate-400 text-slate-850 dark:text-slate-205 outline-none"
            />
          </div>
        </div>

        {/* Coupons Table Card */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-450">Retrieving promotion registries...</span>
          </div>
        ) : filteredCoupons.length > 0 ? (
          <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800">
                    <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Coupon Code</th>
                    <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Campaign Scope</th>
                    <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Discount Logic</th>
                    <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Saturation Limit</th>
                    <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Ecosystem Status</th>
                    <th className="px-8 py-5 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                  {filteredCoupons.map((coupon) => {
                    const isExpired = new Date(coupon.valid_until) < new Date();
                    const percentUsed = coupon.usage_limit ? Math.min(100, (coupon.used_count / coupon.usage_limit) * 100) : 0;
                    
                    return (
                      <tr key={coupon.id} className="group hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-all duration-300">
                        <td className="px-8 py-6">
                          <div className="flex items-center gap-3">
                            <div className="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                              <Ticket className="w-4.5 h-4.5" />
                            </div>
                            <div>
                              <span className="font-mono font-black text-slate-900 dark:text-white text-sm tracking-wider uppercase select-all">{coupon.code}</span>
                              <span className="block text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Host: {coupon.organizer?.name || 'Deleted Host'}</span>
                            </div>
                          </div>
                        </td>

                        <td className="px-8 py-6">
                          {coupon.event ? (
                            <div>
                              <span className="status-pill bg-amber-50 text-amber-600 border border-amber-100/50 tracking-wider">EVENT SPECIFIC</span>
                              <span className="block text-[10px] text-slate-500 font-serif italic truncate max-w-[180px] mt-1">{coupon.event.title}</span>
                            </div>
                          ) : (
                            <div>
                              <span className="status-pill bg-emerald-50 text-emerald-600 border border-emerald-100/50 tracking-wider">GLOBAL CAMPAIGN</span>
                              <span className="block text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-1">ALL HOST EXPERIENCES</span>
                            </div>
                          )}
                        </td>

                        <td className="px-8 py-6">
                          <p className="font-serif text-slate-900 dark:text-white font-bold text-base leading-tight">
                            {coupon.type === 'percentage' ? `${coupon.value}% Off` : `₹${coupon.value} Off`}
                          </p>
                          <p className="text-[8px] text-slate-400 font-black uppercase tracking-widest mt-0.5">
                            {coupon.min_order_amount > 0 && `Min ₹${coupon.min_order_amount}`}
                            {coupon.max_discount > 0 && ` | Max ₹${coupon.max_discount}`}
                          </p>
                        </td>

                        <td className="px-8 py-6">
                          <div className="flex flex-col gap-1 max-w-[120px]">
                            <div className="flex justify-between text-[10px] font-mono text-slate-500">
                              <span>{coupon.used_count} Uses</span>
                              <span className="text-slate-400">/ {coupon.usage_limit || '∞'} limit</span>
                            </div>
                            <div className="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                              <div className="bg-primary h-full rounded-full" style={{ width: `${percentUsed}%` }}></div>
                            </div>
                          </div>
                        </td>

                        <td className="px-8 py-6">
                          <div className="flex items-center gap-3">
                            {coupon.is_active && !isExpired ? (
                              <span className="status-pill bg-primary/10 text-primary border border-primary/20 tracking-wider">ACTIVE</span>
                            ) : isExpired ? (
                              <span className="status-pill bg-slate-100 text-slate-400 border border-slate-205 tracking-wider">EXPIRED</span>
                            ) : (
                              <span className="status-pill bg-rose-50 text-rose-500 border border-rose-100 tracking-wider">SUSPENDED</span>
                            )}

                            <button 
                              onClick={() => handleToggleActive(coupon)}
                              className="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-primary hover:text-primary transition-all flex items-center justify-center text-slate-400 cursor-pointer"
                              title="Toggle active status"
                            >
                              <Power className="w-3.5 h-3.5" />
                            </button>
                          </div>
                        </td>

                        <td className="px-8 py-6 text-right">
                          <div className="flex items-center justify-end gap-5">
                            <button 
                              onClick={() => handleOpenEditModal(coupon)} 
                              className="text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:text-primary-hover transition-colors cursor-pointer"
                            >
                              Modify
                            </button>
                            <button 
                              onClick={() => handlePurge(coupon)}
                              className="text-[10px] font-black uppercase tracking-[0.2em] text-rose-500 hover:text-rose-600 transition-colors cursor-pointer"
                            >
                              Purge
                            </button>
                          </div>
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
            <Ticket className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No promo registers</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              No promotional incentive codes matched your search parameter.
            </p>
          </div>
        )}

        {/* Edit Coupon Modal */}
        {editModalOpen && editingCoupon && (
          <div className="fixed inset-0 z-[120] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all animate-fade-in text-left">
            <div className="relative w-full max-w-lg bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-[2.5rem] p-10 overflow-hidden">
              <form onSubmit={handleEditSubmit} className="space-y-6">
                
                <div className="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                  <div className="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                    <Ticket className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="text-2xl font-serif text-slate-900 dark:text-white font-bold">Modify Coupon Node</h3>
                    <p className="text-[9px] text-slate-400 font-mono tracking-widest uppercase mt-0.5">CODE: {editingCoupon.code}</p>
                  </div>
                </div>

                <div className="space-y-4">
                  {/* Type and Value */}
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[9px] font-black text-slate-450 uppercase tracking-widest mb-1.5 font-bold">Discount Type</label>
                      <select 
                        value={type} 
                        onChange={(e) => setType(e.target.value)}
                        required 
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer"
                      >
                        <option value="percentage">Percentage (%)</option>
                        <option value="flat">Flat Price (₹)</option>
                      </select>
                    </div>
                    <div>
                      <label className="block text-[9px] font-black text-slate-450 uppercase tracking-widest mb-1.5 font-bold">Discount Value</label>
                      <input 
                        type="number" 
                        required 
                        value={value}
                        onChange={(e) => setValue(parseFloat(e.target.value))}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none" 
                      />
                    </div>
                  </div>

                  {/* Min Order & Max Discount */}
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Min Order (₹)</label>
                      <input 
                        type="number" 
                        value={minOrder}
                        onChange={(e) => setMinOrder(e.target.value)}
                        placeholder="0"
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none" 
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Max Discount (₹)</label>
                      <input 
                        type="number" 
                        value={maxDiscount}
                        onChange={(e) => setMaxDiscount(e.target.value)}
                        placeholder="No Limit"
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none" 
                      />
                    </div>
                  </div>

                  {/* Limits */}
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Total Usage Limit</label>
                      <input 
                        type="number" 
                        value={usageLimit}
                        onChange={(e) => setUsageLimit(e.target.value)}
                        placeholder="No Limit (∞)"
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none" 
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Usage Per User</label>
                      <input 
                        type="number" 
                        required 
                        value={usagePerUser}
                        onChange={(e) => setUsagePerUser(parseInt(e.target.value))}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none" 
                      />
                    </div>
                  </div>

                  {/* Dates */}
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Valid From</label>
                      <input 
                        type="datetime-local" 
                        required 
                        value={validFrom}
                        onChange={(e) => setValidFrom(e.target.value)}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer" 
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Valid Until</label>
                      <input 
                        type="datetime-local" 
                        required 
                        value={validUntil}
                        onChange={(e) => setValidUntil(e.target.value)}
                        className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer" 
                      />
                    </div>
                  </div>

                  {/* Active Switch */}
                  <div className="pt-4 border-t border-slate-50 dark:border-slate-800">
                    <label className="flex items-center gap-4 cursor-pointer group">
                      <div className="relative">
                        <input 
                          type="checkbox" 
                          checked={isActive}
                          onChange={(e) => setIsActive(e.target.checked)}
                          className="sr-only peer" 
                        />
                        <div className="w-12 h-6 bg-slate-100 dark:bg-slate-950 rounded-full peer-checked:bg-primary transition-colors border border-slate-200 dark:border-slate-800"></div>
                        <div className="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6 shadow-sm"></div>
                      </div>
                      <div>
                        <span className="block text-sm font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors leading-none">Enabled in Ecosystem</span>
                        <span className="block text-[9px] text-slate-400 uppercase tracking-widest mt-1">Allow curating attendees to use code at checkout</span>
                      </div>
                    </label>
                  </div>
                </div>

                <div className="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-4 shrink-0">
                  <button 
                    type="button" 
                    onClick={() => setEditModalOpen(false)} 
                    className="px-6 py-3 border border-slate-205 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:border-slate-300 transition-all cursor-pointer bg-white dark:bg-slate-900 rounded-xl"
                  >
                    Cancel
                  </button>
                  <button 
                    type="submit" 
                    disabled={submitting}
                    className="btn-primary px-8 py-3 text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-primary/10 disabled:opacity-50 cursor-pointer"
                  >
                    {submitting ? 'Applying...' : 'Apply Modifications'}
                  </button>
                </div>

              </form>
            </div>
          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
