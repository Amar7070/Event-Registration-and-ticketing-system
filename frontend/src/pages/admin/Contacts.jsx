import React, { useState, useEffect } from 'react';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { Mail, RefreshCw, Check, CheckCircle2, Circle, Send, User, Calendar, CornerDownRight } from 'lucide-react';

export default function AdminContacts() {
  const [contacts, setContacts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedContact, setSelectedContact] = useState(null);
  
  // Reply State
  const [replyMessage, setReplyMessage] = useState('');
  const [replying, setReplying] = useState(false);
  const [replySuccess, setReplySuccess] = useState('');

  const fetchContacts = async () => {
    try {
      setLoading(true);
      const response = await api.get('/admin/contacts');
      setContacts(response.data.contacts.data);
      if (response.data.contacts.data.length > 0 && !selectedContact) {
        setSelectedContact(response.data.contacts.data[0]);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchContacts();
  }, []);

  const updateStatus = async (id, newStatus) => {
    try {
      await api.put(`/admin/contacts/${id}/status`, { status: newStatus });
      const updatedContacts = contacts.map(c => c.id === id ? { ...c, status: newStatus } : c);
      setContacts(updatedContacts);
      if (selectedContact && selectedContact.id === id) {
        setSelectedContact({ ...selectedContact, status: newStatus });
      }
    } catch (err) {
      console.error(err);
    }
  };

  const handleReply = async (e) => {
    e.preventDefault();
    if (!replyMessage.trim() || !selectedContact) return;

    setReplying(true);
    setReplySuccess('');

    try {
      await api.post(`/admin/contacts/${selectedContact.id}/reply`, {
        reply_message: replyMessage
      });
      
      setReplySuccess('Reply sent successfully!');
      setReplyMessage('');
      
      // Auto update status in UI to replied
      const updatedContacts = contacts.map(c => 
        c.id === selectedContact.id ? { ...c, status: 'replied' } : c
      );
      setContacts(updatedContacts);
      setSelectedContact({ ...selectedContact, status: 'replied' });
      
      setTimeout(() => setReplySuccess(''), 4000);
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to send reply.');
    } finally {
      setReplying(false);
    }
  };

  const getStatusBadge = (status) => {
    if (status === 'new') return <span className="bg-rose-50 text-rose-700 dark:bg-rose-950/20 px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider flex items-center gap-1 w-fit"><Circle className="w-3 h-3 fill-rose-500 text-rose-500" /> New</span>;
    if (status === 'read') return <span className="bg-amber-50 text-amber-700 dark:bg-amber-950/20 px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider flex items-center gap-1 w-fit"><Check className="w-3 h-3" /> Read</span>;
    if (status === 'replied') return <span className="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider flex items-center gap-1 w-fit"><CheckCircle2 className="w-3 h-3" /> Replied</span>;
  };

  return (
    <SidebarLayout type="admin">
      <div className="flex flex-col h-[calc(100vh-6rem)] animate-slide-up">
        
        {/* Title bar */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800 shrink-0">
          <div>
            <span className="text-[9px] font-black text-rose-500 uppercase tracking-[0.2em] block mb-1">ECOSYSTEM SUPPORT</span>
            <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Inbox & Messages</h1>
          </div>
          
          <div className="flex items-center gap-3">
            <button 
              onClick={fetchContacts}
              className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 px-4 py-2 rounded-full text-xs font-bold shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center gap-2 transition-all cursor-pointer outline-none"
            >
              <RefreshCw className={`w-3.5 h-3.5 ${loading ? 'animate-spin' : ''}`} /> Refresh
            </button>
          </div>
        </div>

        {/* Split Pane Mailbox */}
        <div className="flex flex-1 mt-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
          
          {/* Left Pane: Message List */}
          <div className="w-full md:w-1/3 lg:w-1/4 border-r border-slate-100 dark:border-slate-800 flex flex-col bg-slate-50/50 dark:bg-slate-950/10">
            <div className="p-4 border-b border-slate-100 dark:border-slate-800 font-black text-[10px] uppercase tracking-widest text-slate-400 shrink-0">
              All Messages ({contacts.length})
            </div>
            
            <div className="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
              {loading ? (
                <div className="p-8 flex justify-center">
                  <div className="w-6 h-6 border-2 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
              ) : contacts.length === 0 ? (
                <div className="p-8 text-center text-slate-400">
                  <Mail className="w-8 h-8 mx-auto mb-2 opacity-50" />
                  <p className="text-xs font-bold">Inbox Empty</p>
                </div>
              ) : (
                <div className="divide-y divide-slate-100 dark:divide-slate-800/50">
                  {contacts.map((contact) => (
                    <div 
                      key={contact.id} 
                      onClick={() => setSelectedContact(contact)}
                      className={`p-4 cursor-pointer transition-colors ${
                        selectedContact?.id === contact.id 
                          ? 'bg-rose-50 dark:bg-rose-950/20 border-l-2 border-rose-500' 
                          : 'hover:bg-slate-100 dark:hover:bg-slate-800/30 border-l-2 border-transparent'
                      }`}
                    >
                      <div className="flex items-start justify-between gap-2 mb-1">
                        <span className="font-bold text-slate-900 dark:text-white text-sm truncate">{contact.name}</span>
                        <span className="text-[9px] text-slate-400 shrink-0">{new Date(contact.created_at).toLocaleDateString()}</span>
                      </div>
                      <div className="font-bold text-slate-700 dark:text-slate-300 text-xs truncate mb-1">
                        {contact.subject || 'No Subject'}
                      </div>
                      <div className="text-xs text-slate-500 line-clamp-2 mb-3">
                        {contact.message}
                      </div>
                      {getStatusBadge(contact.status)}
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>

          {/* Right Pane: Message Viewer & Reply */}
          <div className="hidden md:flex flex-1 flex-col bg-white dark:bg-slate-900 overflow-hidden relative">
            {selectedContact ? (
              <>
                {/* Header Actions */}
                <div className="px-8 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-950/20 shrink-0">
                  <div className="flex items-center gap-3">
                    <span className="text-[10px] font-black uppercase tracking-widest text-slate-400">Mark as:</span>
                    <select 
                      value={selectedContact.status}
                      onChange={(e) => updateStatus(selectedContact.id, e.target.value)}
                      className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-lg px-3 py-1.5 outline-none cursor-pointer hover:border-rose-500 transition-colors shadow-sm"
                    >
                      <option value="new">New</option>
                      <option value="read">Read</option>
                      <option value="replied">Replied</option>
                    </select>
                  </div>
                  {getStatusBadge(selectedContact.status)}
                </div>

                {/* Message Body */}
                <div className="flex-1 overflow-y-auto p-8 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
                  <h2 className="text-2xl font-serif font-bold text-slate-900 dark:text-white mb-6">
                    {selectedContact.subject || 'No Subject Provided'}
                  </h2>
                  
                  <div className="flex items-center gap-4 mb-8">
                    <div className="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 border border-slate-200 dark:border-slate-700">
                      <User className="w-5 h-5" />
                    </div>
                    <div>
                      <div className="font-bold text-slate-900 dark:text-white">{selectedContact.name}</div>
                      <div className="flex items-center gap-3 text-xs text-slate-500 mt-0.5">
                        <a href={`mailto:${selectedContact.email}`} className="hover:text-rose-500 font-medium">{selectedContact.email}</a>
                        <span className="flex items-center gap-1"><Calendar className="w-3 h-3" /> {new Date(selectedContact.created_at).toLocaleString()}</span>
                      </div>
                    </div>
                  </div>

                  <div className="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">
                    {selectedContact.message}
                  </div>
                </div>

                {/* Reply Box */}
                <div className="p-6 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/20 shrink-0">
                  {replySuccess && (
                    <div className="mb-4 p-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-400 text-xs font-bold flex items-center gap-2 animate-fade-in">
                      <CheckCircle2 className="w-4 h-4" /> {replySuccess}
                    </div>
                  )}
                  
                  <form onSubmit={handleReply} className="relative">
                    <div className="flex items-center gap-2 mb-2 text-[10px] font-black uppercase tracking-widest text-slate-400">
                      <CornerDownRight className="w-3 h-3" /> Reply to {selectedContact.name}
                    </div>
                    <textarea 
                      value={replyMessage}
                      onChange={(e) => setReplyMessage(e.target.value)}
                      placeholder="Type your response here... (An email will be sent)"
                      className="w-full h-32 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 text-sm text-slate-800 dark:text-slate-200 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 outline-none resize-none transition-all shadow-sm"
                      required
                    ></textarea>
                    
                    <div className="flex justify-end mt-3">
                      <button 
                        type="submit"
                        disabled={replying || !replyMessage.trim()}
                        className="bg-rose-500 hover:bg-rose-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-rose-500/20 flex items-center gap-2 transition-all hover:-translate-y-0.5"
                      >
                        {replying ? (
                          <RefreshCw className="w-4 h-4 animate-spin" />
                        ) : (
                          <>
                            Send Email <Send className="w-3.5 h-3.5" />
                          </>
                        )}
                      </button>
                    </div>
                  </form>
                </div>
              </>
            ) : (
              <div className="flex-1 flex flex-col items-center justify-center text-slate-400 bg-slate-50/30 dark:bg-slate-950/10">
                <Mail className="w-12 h-12 text-slate-200 dark:text-slate-800 mb-4" />
                <h3 className="text-lg font-serif font-bold text-slate-900 dark:text-white mb-1">No Message Selected</h3>
                <p className="text-xs">Select a conversation from the sidebar to read and reply.</p>
              </div>
            )}
          </div>
          
        </div>
      </div>
    </SidebarLayout>
  );
}
