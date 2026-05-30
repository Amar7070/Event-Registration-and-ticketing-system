import React, { useState, useEffect } from 'react';
import { useSearchParams, useNavigate, useLocation, Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import api from '../services/api';
import { 
  CreditCard, 
  Smartphone, 
  Lock, 
  ArrowRight, 
  CheckCircle, 
  Loader2, 
  Check,
  Calendar,
  MapPin,
  Sparkles
} from 'lucide-react';

export default function PaymentCheckout() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const location = useLocation();

  const paymentId = parseInt(searchParams.get('payment_id'));
  const amount = parseFloat(searchParams.get('amount')) || 0;

  // Retrieve event info from location state if available (fallback to generic labels if direct link)
  const stateData = location.state || {};
  const { event, ticketType, quantity } = stateData;

  const [gateway, setGateway] = useState('stripe');
  const [cardNumber, setCardNumber] = useState('4242 •••• •••• 4242');
  const [cardExpiry, setCardExpiry] = useState('12/28');
  const [cardCvc, setCardCvc] = useState('***');
  const [upiId, setUpiId] = useState('user@okaxis');

  const [processing, setProcessing] = useState(false);
  const [success, setSuccess] = useState(false);
  const [bookingReference, setBookingReference] = useState('');
  
  // Confetti particles list
  const [confetti, setConfetti] = useState([]);

  const token = localStorage.getItem('token') || localStorage.getItem('api_token');

  useEffect(() => {
    if (!token) {
      navigate('/login');
    }
  }, [token, navigate]);

  const handlePayNow = async (e) => {
    e.preventDefault();
    setProcessing(true);

    try {
      const res = await api.post('/payments/process', {
        payment_id: paymentId,
        gateway: gateway
      });

      if (res.data.success) {
        setBookingReference(res.data.booking_reference || 'REF-TKT');
        setSuccess(true);
        triggerConfetti();
      } else {
        alert('Payment failed. Please retry.');
      }
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Payment processor exception. Check sandbox state.');
    } finally {
      setProcessing(false);
    }
  };

  const triggerConfetti = () => {
    const colors = ['#4E7D5B', '#F59E0B', '#14B8A6', '#10B981', '#3B82F6', '#EC4899'];
    const particles = [];
    for (let i = 0; i < 45; i++) {
      const angle = Math.random() * Math.PI * 2;
      const distance = Math.random() * 250 + 100;
      particles.push({
        id: i,
        color: colors[Math.floor(Math.random() * colors.length)],
        size: Math.random() * 8 + 4,
        round: Math.random() > 0.5,
        tx: Math.cos(angle) * distance,
        ty: Math.sin(angle) * distance - 50,
        rot: Math.random() * 720,
        delay: Math.random() * 0.2
      });
    }
    setConfetti(particles);
  };

  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden pt-28 pb-20">
      <Navbar />

      <div className="max-w-3xl mx-auto px-6 sm:px-8">
        
        {success ? (
          /* Cinematic Success Modal View */
          <div className="relative max-w-md w-full bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-100 dark:border-slate-800 shadow-2xl mx-auto flex flex-col items-center text-center space-y-6 animate-slide-up overflow-hidden">
            
            {/* Confetti pieces */}
            <div className="absolute inset-0 pointer-events-none overflow-hidden">
              {confetti.map((c) => (
                <div 
                  key={c.id}
                  className="absolute left-1/2 top-[100px] opacity-0"
                  style={{
                    backgroundColor: c.color,
                    width: c.size + 'px',
                    height: (c.round ? c.size : c.size * 0.4) + 'px',
                    borderRadius: c.round ? '50%' : '2px',
                    animation: `confetti-explosion 1.8s cubic-bezier(0.1, 0.8, 0.3, 1) ${c.delay}s forwards`,
                    '--tx': c.tx + 'px',
                    '--ty': c.ty + 'px',
                    '--rot': c.rot + 'deg'
                  }}
                ></div>
              ))}
            </div>

            {/* Glowing checkmark container */}
            <div className="relative w-24 h-24 flex items-center justify-center">
              <div className="absolute inset-0 bg-[#4E7D5B]/20 rounded-full blur-xl animate-pulse"></div>
              <div className="w-20 h-20 rounded-full border-4 border-[#4E7D5B] flex items-center justify-center text-[#4E7D5B] bg-[#FDFBF7] dark:bg-slate-900">
                <Check className="w-10 h-10 stroke-[3]" />
              </div>
            </div>

            {/* Custom message */}
            <div className="space-y-2">
              <span className="inline-flex items-center px-4 py-1.5 bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 text-[#4E7D5B] rounded-full text-[9px] font-black tracking-widest uppercase">
                TRANSACTION COMPLETED
              </span>
              <h3 className="text-2xl font-serif text-slate-900 dark:text-white font-bold leading-tight">Now, the ticket is yours!</h3>
              <p className="text-slate-500 dark:text-slate-400 text-xs font-serif italic">
                "Your digital presence has been verified and registered within the experience node."
              </p>
            </div>

            {/* Glowing Ticket Card Component */}
            <div className="w-full bg-[#1E293B] text-white rounded-3xl p-6 relative overflow-hidden shadow-xl border border-white/5 text-left">
              <div className="absolute -right-16 -top-16 w-32 h-32 bg-[#4E7D5B]/20 rounded-full blur-[40px] pointer-events-none"></div>
              <div className="absolute -left-16 -bottom-16 w-32 h-32 bg-amber-500/10 rounded-full blur-[40px] pointer-events-none"></div>
              
              <div className="flex flex-col space-y-4">
                <div className="flex justify-between items-start border-b border-white/10 pb-4">
                  <div className="min-w-0">
                    <span className="text-[8px] font-black tracking-widest uppercase text-slate-400 block mb-0.5">EVENT PASS</span>
                    <h4 className="font-serif text-sm text-white truncate max-w-[200px] font-bold">
                      {event?.title || 'SmartEvent Experience'}
                    </h4>
                  </div>
                  <div className="text-right shrink-0">
                    <span className="text-[8px] font-black tracking-widest uppercase text-slate-400 block mb-0.5">REFERENCE</span>
                    <span className="font-mono text-xs font-bold text-[#4E7D5B]">#{bookingReference}</span>
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-4 text-xs">
                  <div>
                    <span className="text-[8px] font-black tracking-widest uppercase text-slate-500 block mb-0.5">TEMPORAL NODE</span>
                    <span className="font-bold text-slate-200">
                      {event?.start_date ? new Date(event.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Date TBD'}
                    </span>
                  </div>
                  <div>
                    <span className="text-[8px] font-black tracking-widest uppercase text-slate-500 block mb-0.5">SPATIAL INDEX</span>
                    <span className="font-bold text-slate-200 truncate block max-w-[120px]">
                      {event?.venue?.name || 'Digital Gateway'}
                    </span>
                  </div>
                </div>
              </div>

              {/* Punched ticket circles */}
              <div className="absolute top-1/2 -left-3 w-6 h-6 bg-white dark:bg-slate-900 rounded-full -translate-y-1/2"></div>
              <div className="absolute top-1/2 -right-3 w-6 h-6 bg-white dark:bg-slate-900 rounded-full -translate-y-1/2"></div>
            </div>

            {/* Action buttons */}
            <div className="w-full flex flex-col gap-3">
              <Link to={`/my-tickets/${bookingReference}`} className="w-full btn-primary py-4 text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/20 text-center flex items-center justify-center gap-2">
                <span>View My Ticket</span>
                <ArrowRight className="w-4 h-4" />
              </Link>
              <Link to="/dashboard" className="w-full text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 dark:hover:text-white transition-colors py-2 block">
                Return to Portal
              </Link>
            </div>
          </div>
        ) : (
          /* Secure Checkout Portal Form */
          <div className="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden text-left animate-slide-up">
            <div className="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
              <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                  <h2 className="text-2xl font-black text-slate-900 dark:text-white">Order Summary</h2>
                  <p className="text-slate-500 dark:text-slate-450 font-medium text-xs mt-1">Payment Reference ID: #{paymentId}</p>
                </div>
                <div className="text-left sm:text-right shrink-0">
                  <span className="text-3xl font-black text-[#4E7D5B]">₹{amount.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                  <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Total Amount</p>
                </div>
              </div>
            </div>

            <div className="p-8 space-y-8">
              {/* Event Details Card */}
              {event && (
                <div className="flex items-start gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800">
                  <div className="w-12 h-12 rounded-xl bg-[#4E7D5B] shrink-0 flex items-center justify-center text-white">
                    <Calendar className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="font-bold text-slate-800 dark:text-slate-200">{event.title}</h3>
                    <p className="text-xs text-slate-500 mt-0.5">
                      {new Date(event.start_date).toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}
                    </p>
                    <p className="text-xs text-slate-500 mt-0.5">{event.venue?.name || 'Digital Gateway'}</p>
                  </div>
                </div>
              )}

              {/* Payment Methods */}
              <div>
                <h3 className="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Select Payment Method</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <label className={`relative flex items-center p-4 border rounded-2xl cursor-pointer transition-all group ${
                    gateway === 'stripe' ? 'border-[#4E7D5B] bg-[#4E7D5B]/5' : 'border-slate-200 dark:border-slate-800 hover:border-[#4E7D5B]/20 hover:bg-[#4E7D5B]/2'
                  }`}>
                    <input 
                      type="radio" 
                      name="gateway" 
                      value="stripe" 
                      checked={gateway === 'stripe'}
                      onChange={() => setGateway('stripe')}
                      className="hidden"
                    />
                    <div className={`w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center ${
                      gateway === 'stripe' ? 'border-[#4E7D5B]' : 'border-slate-300'
                    }`}>
                      {gateway === 'stripe' && <div className="w-2.5 h-2.5 bg-[#4E7D5B] rounded-full"></div>}
                    </div>
                    <div className="flex flex-col">
                      <span className="font-bold text-slate-800 dark:text-slate-200">Stripe Sandbox</span>
                      <span className="text-xs text-slate-500">Cards, Apple Pay, Google Pay</span>
                    </div>
                    <div className="ml-auto opacity-30 group-hover:opacity-100 transition-opacity">
                      <CreditCard className="w-6 h-6 text-[#4E7D5B]" />
                    </div>
                  </label>

                  <label className={`relative flex items-center p-4 border rounded-2xl cursor-pointer transition-all group ${
                    gateway === 'razorpay' ? 'border-[#4E7D5B] bg-[#4E7D5B]/5' : 'border-slate-200 dark:border-slate-800 hover:border-[#4E7D5B]/20 hover:bg-[#4E7D5B]/2'
                  }`}>
                    <input 
                      type="radio" 
                      name="gateway" 
                      value="razorpay" 
                      checked={gateway === 'razorpay'}
                      onChange={() => setGateway('razorpay')}
                      className="hidden"
                    />
                    <div className={`w-5 h-5 border-2 rounded-full mr-3 flex items-center justify-center ${
                      gateway === 'razorpay' ? 'border-[#4E7D5B]' : 'border-slate-300'
                    }`}>
                      {gateway === 'razorpay' && <div className="w-2.5 h-2.5 bg-[#4E7D5B] rounded-full"></div>}
                    </div>
                    <div className="flex flex-col">
                      <span className="font-bold text-slate-800 dark:text-slate-200">Razorpay Sandbox</span>
                      <span className="text-xs text-slate-500">UPI, NetBanking, Wallets</span>
                    </div>
                    <div className="ml-auto opacity-30 group-hover:opacity-100 transition-opacity">
                      <Smartphone className="w-6 h-6 text-[#4E7D5B]" />
                    </div>
                  </label>
                </div>
              </div>

              {/* Secure sandbox detail forms */}
              <div className="pt-4 border-t border-slate-100 dark:border-slate-800">
                <h3 className="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Sandbox Credentials</h3>
                
                {gateway === 'stripe' ? (
                  <div className="space-y-4">
                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Card Number</label>
                      <input 
                        type="text" 
                        value={cardNumber}
                        onChange={(e) => setCardNumber(e.target.value)}
                        className="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs font-mono font-bold" 
                      />
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Expiry Date</label>
                        <input 
                          type="text" 
                          value={cardExpiry}
                          onChange={(e) => setCardExpiry(e.target.value)}
                          className="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs font-mono font-bold" 
                        />
                      </div>
                      <div>
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">CVC</label>
                        <input 
                          type="text" 
                          value={cardCvc}
                          onChange={(e) => setCardCvc(e.target.value)}
                          className="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs font-mono font-bold" 
                        />
                      </div>
                    </div>
                  </div>
                ) : (
                  <div>
                    <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">UPI ID (VPA)</label>
                    <input 
                      type="text" 
                      value={upiId}
                      onChange={(e) => setUpiId(e.target.value)}
                      className="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs font-mono font-bold" 
                    />
                  </div>
                )}
              </div>
            </div>

            <div className="p-8 bg-slate-50 dark:bg-slate-950/20 border-t border-slate-100 dark:border-slate-800">
              <button 
                onClick={handlePayNow}
                disabled={processing}
                className="w-full bg-[#4E7D5B] hover:bg-[#3D6449] text-white font-black py-4 rounded-2xl shadow-xl shadow-slate-900/10 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
              >
                {processing ? (
                  <>
                    <Loader2 className="w-5 h-5 animate-spin" />
                    <span>Securing Gateway Connection...</span>
                  </>
                ) : (
                  <>
                    <span>Pay Now</span>
                    <ArrowRight className="w-5 h-5" />
                  </>
                )}
              </button>
              <p className="text-center text-xs text-slate-400 mt-4 font-medium flex items-center justify-center gap-1">
                <Lock className="w-3 h-3 text-[#4E7D5B]" /> Secure SSL Encrypted Checkout
              </p>
            </div>
          </div>
        )}
      </div>

      <style dangerouslySetInnerHTML={{__html: `
        @keyframes confetti-explosion {
          0% {
            transform: translate(0, 0) rotate(0deg) scale(0);
            opacity: 1;
          }
          80% {
            opacity: 0.8;
          }
          100% {
            transform: translate(var(--tx), var(--ty)) rotate(var(--rot)) scale(1);
            opacity: 0;
          }
        }
      `}} />
    </div>
  );
}
