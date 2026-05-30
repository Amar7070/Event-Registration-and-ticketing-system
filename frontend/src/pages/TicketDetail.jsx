import React, { useState, useEffect } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import SidebarLayout from '../components/SidebarLayout';
import api, { API_URL } from '../services/api';
import axios from 'axios';
import { 
  ArrowLeft, 
  Calendar, 
  MapPin, 
  Download, 
  Send, 
  Lock, 
  ShieldCheck,
  CheckCircle,
  HelpCircle,
  X,
  UserCheck
} from 'lucide-react';

export default function TicketDetail() {
  const { reference } = useParams();
  const navigate = useNavigate();

  const [ticket, setTicket] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Transfer state
  const [recipientEmail, setRecipientEmail] = useState('');
  const [transferring, setTransferring] = useState(false);
  const [transferMessage, setTransferMessage] = useState(null);
  const [showTransferModal, setShowTransferModal] = useState(false);

  // PDF download loading state
  const [downloading, setDownloading] = useState(false);

  useEffect(() => {
    async function loadTicket() {
      try {
        setLoading(true);
        const res = await api.get(`/my-tickets/${reference}`);
        setTicket(res.data);
        setError(null);
      } catch (err) {
        console.error(err);
        setError('Pass nodes could not be retrieved from the ecosystem.');
      } finally {
        setLoading(false);
      }
    }
    loadTicket();
  }, [reference]);

  // Handle PDF download via Axios blob
  const handleDownloadPDF = async () => {
    if (!ticket) return;
    setDownloading(true);
    try {
      const token = localStorage.getItem('token') || localStorage.getItem('api_token');
      const response = await axios({
        url: `${API_URL}/v1/my-tickets/${ticket.id}/download`,
        method: 'GET',
        responseType: 'blob', // Important
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });
      
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `ticket-${ticket.booking_reference}.pdf`);
      document.body.appendChild(link);
      link.click();
      link.parentNode.removeChild(link);
    } catch (err) {
      console.error(err);
      alert('Could not compile download pass. Please try again.');
    } finally {
      setDownloading(false);
    }
  };

  // Handle Ticket Transfer
  const handleTransferSubmit = async (e) => {
    e.preventDefault();
    if (!recipientEmail) return;
    setTransferring(true);
    setTransferMessage(null);
    
    try {
      const res = await api.post(`/my-tickets/${reference}/transfer`, {
        email: recipientEmail
      });
      setTransferMessage({ type: 'success', text: res.data.message });
      setRecipientEmail('');
      setTimeout(() => {
        setShowTransferModal(false);
        navigate('/my-tickets');
      }, 3000);
    } catch (err) {
      console.error(err);
      setTransferMessage({ type: 'error', text: err.response?.data?.message || 'Transfer validation failed.' });
    } finally {
      setTransferring(false);
    }
  };

  if (loading) {
    return (
      <SidebarLayout type="user">
        <div className="py-24 flex flex-col items-center gap-4">
          <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
          <span className="text-xs font-black uppercase tracking-widest text-slate-450">Retrieving digital key...</span>
        </div>
      </SidebarLayout>
    );
  }

  if (error || !ticket) {
    return (
      <SidebarLayout type="user">
        <div className="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-10 rounded-[2.5rem] shadow-xl text-center mx-auto mt-12">
          <div className="w-16 h-16 bg-red-50 dark:bg-red-950/20 rounded-2xl flex items-center justify-center text-red-500 mx-auto mb-6">
            <HelpCircle className="w-8 h-8" />
          </div>
          <h2 className="text-2xl font-serif font-medium text-slate-900 dark:text-white mb-2">Sync Connection Halt</h2>
          <p className="text-sm text-slate-400 dark:text-slate-400 mb-6">{error || 'Pass details not found.'}</p>
          <Link to="/my-tickets" className="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest w-full text-center block">
            Return to Vault
          </Link>
        </div>
      </SidebarLayout>
    );
  }

  const isConfirmed = ticket.status === 'confirmed';
  const isCheckedIn = !!ticket.checked_in_at;

  return (
    <SidebarLayout type="user">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Navigation back */}
        <div>
          <Link to="/my-tickets" className="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-450 hover:text-primary transition-colors">
            <ArrowLeft className="w-4 h-4" />
            Back to Active passes
          </Link>
        </div>

        {/* Cinematic Split Layout */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
          
          {/* Left Column: QR Card Pass */}
          <div className="lg:col-span-5 flex justify-center">
            <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] shadow-xl overflow-hidden max-w-sm w-full relative">
              {/* Top accent */}
              <div className="h-3.5 w-full bg-[#4E7D5B]"></div>

              <div className="p-8 text-center space-y-6">
                <div>
                  <span className="px-3.5 py-1 bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 text-[#4E7D5B] rounded-full text-[9px] font-black uppercase tracking-widest leading-none">
                    {ticket.status}
                  </span>
                  <h3 className="font-serif text-lg text-slate-900 dark:text-white mt-4 font-bold">Verification Key Pass</h3>
                  <span className="font-mono text-xs text-slate-400 block mt-1">Ref: #{ticket.booking_reference}</span>
                </div>

                {/* QR Code container */}
                <div className="relative p-6 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-3xl w-fit mx-auto shadow-inner">
                  {ticket.qr_code_url ? (
                    <img 
                      src={ticket.qr_code_url} 
                      alt="Pass Verification QR Code" 
                      className="w-48 h-48 mix-blend-multiply dark:mix-blend-normal rounded-2xl" 
                    />
                  ) : (
                    <div className="w-48 h-48 flex items-center justify-center bg-white border border-slate-150 rounded-2xl text-slate-400 font-mono text-[10px] p-4 text-center">
                      QR Code generating or restricted...
                    </div>
                  )}

                  {isCheckedIn && (
                    <div className="absolute inset-0 bg-[#4E7D5B]/85 backdrop-blur-sm rounded-3xl flex flex-col items-center justify-center text-white p-4">
                      <ShieldCheck className="w-16 h-16 mb-2" />
                      <span className="text-[10px] font-black uppercase tracking-[0.25em]">CHECKED IN</span>
                      <span className="text-[9px] font-bold opacity-80 mt-1">Verified Node Admission</span>
                    </div>
                  )}
                </div>

                <div className="text-[10px] text-slate-400 leading-normal font-sans">
                  Present this QR gate access key to the host staff at check-in admission.
                </div>

                <div className="flex flex-col gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                  <button 
                    onClick={handleDownloadPDF}
                    disabled={downloading}
                    className="w-full btn-primary py-3.5 text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                  >
                    <Download className="w-4 h-4" />
                    {downloading ? 'Downloading...' : 'Download PDF Pass'}
                  </button>

                  {ticket.ticket_type.is_transferable && isConfirmed && !isCheckedIn && (
                    <button 
                      onClick={() => setShowTransferModal(true)}
                      className="w-full py-3.5 border border-slate-205 dark:border-slate-800 rounded-full text-xs font-black uppercase tracking-widest hover:border-primary text-slate-500 hover:text-primary transition-all cursor-pointer bg-white dark:bg-slate-900"
                    >
                      Transfer Pass Key
                    </button>
                  )}
                </div>
              </div>
            </div>
          </div>

          {/* Right Column: Pass and Event Details */}
          <div className="lg:col-span-7 space-y-6">
            
            {/* Event Info Card */}
            <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-8 space-y-6">
              <div>
                <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">BOUND EXPERIENCE</span>
                <h2 className="text-2xl font-serif text-slate-905 dark:text-white font-bold leading-tight">{ticket.event.title}</h2>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-50 dark:border-slate-850">
                <div className="flex items-start gap-3 text-slate-500">
                  <Calendar className="w-5 h-5 text-primary shrink-0" />
                  <div>
                    <span className="block text-[8px] font-black text-slate-400 uppercase tracking-widest">Date & Time</span>
                    <span className="text-xs font-bold text-slate-800 dark:text-slate-200">
                      {new Date(ticket.event.start_date).toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}
                    </span>
                  </div>
                </div>

                <div className="flex items-start gap-3 text-slate-500">
                  <MapPin className="w-5 h-5 text-primary shrink-0" />
                  <div>
                    <span className="block text-[8px] font-black text-slate-400 uppercase tracking-widest">Venue Index</span>
                    <span className="text-xs font-bold text-slate-800 dark:text-slate-200">
                      {ticket.event.venue}, {ticket.event.city}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            {/* Ticket parameters */}
            <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-8 space-y-4">
              <div>
                <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Specification parameters</span>
                <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold">Pass Specifications</h3>
              </div>

              <div className="divide-y divide-slate-50 dark:divide-slate-850 text-xs">
                <div className="flex justify-between py-3">
                  <span className="text-slate-400 font-medium">Access Tier Level</span>
                  <span className="font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">{ticket.type}</span>
                </div>
                <div className="flex justify-between py-3">
                  <span className="text-slate-400 font-medium">Registered Node Quantity</span>
                  <span className="font-bold text-slate-800 dark:text-slate-200">{ticket.quantity} admission keys</span>
                </div>
                <div className="flex justify-between py-3">
                  <span className="text-slate-400 font-medium">Total Paid Amount</span>
                  <span className="font-bold text-[#4E7D5B]">
                    {ticket.total_amount == 0 ? 'Free' : `₹${ticket.total_amount.toLocaleString('en-IN')}`}
                  </span>
                </div>
                <div className="flex justify-between py-3">
                  <span className="text-slate-400 font-medium">Secured Node Date</span>
                  <span className="font-bold text-slate-500">
                    {new Date(ticket.purchased_at).toLocaleString()}
                  </span>
                </div>
              </div>
            </div>

            {/* Refund policies */}
            <div className="bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 p-6 rounded-3xl flex items-start gap-4 text-xs leading-relaxed text-slate-500">
              <Lock className="w-5 h-5 text-primary shrink-0" />
              <div>
                <h4 className="font-bold text-slate-800 dark:text-slate-200 mb-1">Ecosystem Governance Policies</h4>
                <p>
                  This ticket has been generated in accordance with SmartEvent terms. The ticket is <strong>{ticket.ticket_type.is_transferable ? 'transferable' : 'non-transferable'}</strong> and <strong>{ticket.ticket_type.is_refundable ? 'refundable' : 'non-refundable'}</strong>.
                </p>
              </div>
            </div>

          </div>
        </div>
      </div>

      {/* Transfer modal */}
      {showTransferModal && (
        <div className="fixed inset-0 z-[120] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md transition-all">
          <div className="relative w-full max-w-md bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-3xl p-8 overflow-hidden">
            
            <div className="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
              <div className="flex items-center gap-3">
                <Send className="w-5 h-5 text-primary" />
                <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold leading-tight">Transfer Ticket Key</h3>
              </div>
              <button onClick={() => setShowTransferModal(false)} className="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                <X className="w-5 h-5" />
              </button>
            </div>

            {transferMessage && (
              <div className={`p-4 rounded-xl text-xs font-bold uppercase tracking-wider mb-5 ${
                transferMessage.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100'
              }`}>
                {transferMessage.text}
              </div>
            )}

            <form onSubmit={handleTransferSubmit} className="space-y-4 text-left">
              <p className="text-xs text-slate-400 leading-normal">
                Transferring this ticket will revoke your current access QR key and generate a fresh key in the recipient's vault. This action is irreversible.
              </p>

              <div>
                <label className="block text-[9px] font-black text-slate-450 uppercase tracking-widest mb-1.5 font-bold">Recipient Email Address</label>
                <input 
                  type="email" 
                  required
                  value={recipientEmail}
                  onChange={(e) => setRecipientEmail(e.target.value)}
                  placeholder="name@example.com" 
                  className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                />
              </div>

              <div className="pt-4 flex gap-4">
                <button 
                  type="button" 
                  onClick={() => setShowTransferModal(false)} 
                  className="flex-1 py-3 border border-slate-200 text-[9px] font-black uppercase tracking-wider text-slate-600 hover:border-slate-300 transition-all cursor-pointer bg-white"
                >
                  Cancel
                </button>
                <button 
                  type="submit" 
                  disabled={transferring}
                  className="flex-1 py-3 bg-[#4E7D5B] text-white text-[9px] font-black uppercase tracking-wider hover:bg-[#3D6449] transition-all shadow-lg shadow-[#4E7D5B]/20 cursor-pointer disabled:opacity-50 flex justify-center items-center gap-1"
                >
                  {transferring ? 'Transferring...' : (
                    <>
                      <UserCheck className="w-3.5 h-3.5" />
                      <span>Confirm Transfer</span>
                    </>
                  )}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

    </SidebarLayout>
  );
}
