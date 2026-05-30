import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  Tag, 
  Plus, 
  Settings, 
  Trash2, 
  Check, 
  X,
  AlertTriangle
} from 'lucide-react';

export default function Categories() {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);

  // Form states
  const [modalOpen, setModalOpen] = useState(false);
  const [editingCategory, setEditingCategory] = useState(null);
  const [form, setForm] = useState({ name: '', icon: 'sprout', color: '#4E7D5B', description: '', is_active: true });

  useEffect(() => {
    fetchCategories();
  }, []);

  const fetchCategories = async () => {
    try {
      setLoading(true);
      const res = await api.get('/admin/categories');
      setCategories(res.data.categories || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleOpenCreate = () => {
    setEditingCategory(null);
    setForm({ name: '', icon: 'sprout', color: '#4E7D5B', description: '', is_active: true });
    setModalOpen(true);
  };

  const handleOpenEdit = (cat) => {
    setEditingCategory(cat);
    setForm({ 
      name: cat.name, 
      icon: cat.icon || 'sprout', 
      color: cat.color || '#4E7D5B', 
      description: cat.description || '', 
      is_active: !!cat.is_active 
    });
    setModalOpen(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      let res;
      if (editingCategory) {
        res = await api.put(`/admin/categories/${editingCategory.id}`, form);
      } else {
        res = await api.post('/admin/categories', form);
      }
      alert(res.data.message);
      setModalOpen(false);
      fetchCategories();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to save category.');
    }
  };

  const handleDelete = async (cat) => {
    if (!window.confirm(`Delete category "${cat.name}" permanently?`)) return;
    try {
      const res = await api.delete(`/admin/categories/${cat.id}`);
      alert(res.data.message);
      fetchCategories();
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to delete category.');
    }
  };

  return (
    <SidebarLayout type="admin">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">METADATA SCHEMAS</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Domain Spheres</h1>
            <p className="text-xs text-slate-400 mt-1">Manage public categories and domain schemas for marketplace filtering.</p>
          </div>

          <button 
            onClick={handleOpenCreate}
            className="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest flex items-center gap-2"
          >
            <Plus className="w-4 h-4 stroke-[3]" />
            <span>Create Domain</span>
          </button>
        </div>

        {/* Categories grid */}
        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-455">Syncing domain categories...</span>
          </div>
        ) : categories.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {categories.map((cat) => {
              const isActive = !!cat.is_active;
              return (
                <div 
                  key={cat.id}
                  className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-850 rounded-[2rem] p-6 shadow-sm flex flex-col justify-between group transition-all duration-300 hover:shadow-md"
                >
                  <div className="space-y-4">
                    <div className="flex justify-between items-center">
                      <div className="flex items-center gap-3">
                        <div 
                          className="w-8 h-8 rounded-lg flex items-center justify-center text-white font-serif font-black"
                          style={{ backgroundColor: cat.color || '#4E7D5B' }}
                        >
                          {cat.name.substring(0, 1).toUpperCase()}
                        </div>
                        <h3 className="font-serif text-base text-slate-850 dark:text-white font-bold leading-none">{cat.name}</h3>
                      </div>
                      <span className={`px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider ${
                        isActive 
                          ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20' 
                          : 'bg-rose-50 text-rose-700 dark:bg-rose-950/20'
                      }`}>
                        {isActive ? 'Active' : 'Disabled'}
                      </span>
                    </div>

                    <p className="text-slate-450 dark:text-slate-400 text-xs leading-relaxed line-clamp-2">
                      {cat.description || 'No description provided.'}
                    </p>
                  </div>

                  <div className="pt-6 mt-6 border-t border-slate-50 dark:border-slate-850 flex justify-end gap-2">
                    <button 
                      onClick={() => handleOpenEdit(cat)}
                      className="p-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-lg hover:text-primary transition-colors cursor-pointer"
                      title="Edit Domain"
                    >
                      <Settings className="w-4 h-4" />
                    </button>
                    <button 
                      onClick={() => handleDelete(cat)}
                      className="p-2 bg-rose-50 border border-rose-100 rounded-lg text-rose-600 hover:bg-rose-100 transition-colors cursor-pointer"
                      title="Delete Domain"
                    >
                      <Trash2 className="w-4 h-4" />
                    </button>
                  </div>
                </div>
              );
            })}
          </div>
        ) : (
          <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-3xl max-w-md mx-auto">
            <Tag className="w-10 h-10 text-slate-300 mx-auto mb-4" />
            <span className="text-xs font-bold text-slate-400 uppercase tracking-widest block">No categories created</span>
            <p className="text-[11px] text-slate-450 mt-1.5 max-w-[240px] mx-auto leading-relaxed">
              Domain metadata is empty. Defer public blueprint deployments by spawning a category.
            </p>
          </div>
        )}

        {/* Create/Edit Modal */}
        {modalOpen && (
          <div className="fixed inset-0 z-[120] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all">
            <div className="relative w-full max-w-md bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-3xl p-8 overflow-hidden">
              
              <div className="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold leading-tight">
                  {editingCategory ? 'Edit Domain Sphere' : 'Create Domain Sphere'}
                </h3>
                <button onClick={() => setModalOpen(false)} className="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                  <X className="w-5 h-5" />
                </button>
              </div>

              <form onSubmit={handleSubmit} className="space-y-4 text-left">
                <div>
                  <label className="block text-[9px] font-black text-slate-450 uppercase tracking-widest mb-1.5 font-bold">Domain Name</label>
                  <input 
                    type="text" 
                    required
                    value={form.name}
                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                    placeholder="e.g. Technology & Code" 
                    className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-primary"
                  />
                </div>

                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Accent Color</label>
                    <input 
                      type="color" 
                      required
                      value={form.color}
                      onChange={(e) => setForm({ ...form, color: e.target.value })}
                      className="w-full h-11 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl cursor-pointer"
                    />
                  </div>
                  <div>
                    <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Icon Identifier</label>
                    <input 
                      type="text" 
                      value={form.icon}
                      onChange={(e) => setForm({ ...form, icon: e.target.value })}
                      placeholder="e.g. sprout" 
                      className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-[9px] font-black text-slate-455 uppercase tracking-widest mb-1.5 font-bold">Brief Narrative</label>
                  <textarea 
                    rows="3"
                    value={form.description}
                    onChange={(e) => setForm({ ...form, description: e.target.value })}
                    placeholder="Domain description..."
                    className="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                  ></textarea>
                </div>

                <div className="flex items-center pt-2">
                  <label className="flex items-center gap-3 cursor-pointer">
                    <input 
                      type="checkbox" 
                      checked={form.is_active}
                      onChange={(e) => setForm({ ...form, is_active: e.target.checked })}
                      className="w-4 h-4 rounded border-slate-200 text-primary focus:ring-0 cursor-pointer" 
                    />
                    <span className="text-xs text-slate-600 dark:text-slate-350 font-bold uppercase tracking-wider select-none">
                      Active and Deployed
                    </span>
                  </label>
                </div>

                <div className="pt-4 flex gap-4">
                  <button 
                    type="button" 
                    onClick={() => setModalOpen(false)} 
                    className="flex-1 py-3 border border-slate-205 text-[9px] font-black uppercase tracking-wider text-slate-650 hover:border-slate-300 transition-all cursor-pointer bg-white"
                  >
                    Cancel
                  </button>
                  <button 
                    type="submit" 
                    className="flex-1 py-3 bg-rose-500 text-white text-[9px] font-black uppercase tracking-wider hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/20 cursor-pointer"
                  >
                    Save Domain
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
