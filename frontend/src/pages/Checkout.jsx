import React, { useState, useEffect } from 'react';
import { useSearchParams, useNavigate, Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import api from '../services/api';
import { 
  Ticket, 
  Tag, 
  Calendar, 
  MapPin, 
  User, 
  Mail, 
  Lock, 
  ArrowLeft, 
  CreditCard,
  CheckCircle,
  AlertTriangle
} from 'lucide-react';

export default function Checkout() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  
  const eventSlug = searchParams.get('event');
  const ticketTypeId = parseInt(searchParams.get('ticket_type_id'));
  const quantity = parseInt(searchParams.get('quantity')) || 1;

  const [event, setEvent] = useState(null);
  const [ticketType, setTicketType] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Form states
  const [attendeeName, setAttendeeName] = useState('');
  const [attendeeEmail, setAttendeeEmail] = useState('');
  const [guestDetails, setGuestDetails] = useState([]);
  
  // Coupon states
  const [couponCode, setCouponCode] = useState('');
  const [isValidatingCoupon, setIsValidatingCoupon] = useState(false);
  const [couponError, setCouponError] = useState(null);
  const [couponSuccess, setCouponSuccess] = useState(null);
  const [appliedCouponData, setAppliedCouponData] = useState(null);

  // Booking states
  const [bookingLoading, setBookingLoading] = useState(false);
  const [bookingSuccessData, setBookingSuccessData] = useState(null);

  const token = localStorage.getItem('token') || localStorage.getItem('api_token');

  // Verify auth
  useEffect(() => {
    if (!token) {
      navigate(`/login?redirect=/checkout?event=${eventSlug}&ticket_type_id=${ticketTypeId}&quantity=${quantity}`);
    }
  }, [token, navigate, eventSlug, ticketTypeId, quantity]);

  // Initialize guest details array based on quantity
  useEffect(() => {
    if (quantity > 1) {
      const arr = [];
      for (let i = 0; i < quantity - 1; i++) {
        arr.push({ name: '', email: '' });
      }
      setGuestDetails(arr);
    } else {
      setGuestDetails([]);
    }
  }, [quantity]);

  // Load event details
  useEffect(() => {
    if (!eventSlug) {
      setError('Invalid checkout link parameters.');
      setLoading(false);
      return;
    }

    async function loadData() {
      try {
        setLoading(true);
        const res = await api.get(`/events/${eventSlug}`);
        const data = res.data.data;
        setEvent(data);
        
        const type = data.ticket_types.find(t => t.id === ticketTypeId);
        if (!type) {
          setError('Selected ticket tier does not exist on this event.');
        } else {
          setTicketType(type);
        }

        // Prefill attendee details from user profile
        try {
          const userRes = await api.get('/user');
          setAttendeeName(userRes.data.name || '');
          setAttendeeEmail(userRes.data.email || '');
        } catch (e) {
          console.error('Failed to load user profile info', e);
        }

        setError(null);
      } catch (err) {
        console.error(err);
        setError('Failed to fetch transaction context.');
      } finally {
        setLoading(false);
      }
    }
    loadData();
  }, [eventSlug, ticketTypeId]);

  // Coupon check
  const handleValidateCoupon = async (e) => {
    e.preventDefault();
    if (!couponCode.trim()) return;

    setIsValidatingCoupon(true);
    setCouponError(null);
    setCouponSuccess(null);
    setAppliedCouponData(null);

    try {
      const res = await api.post(`/events/${event.id}/validate-coupon`, {
        coupon_code: couponCode.trim(),
        ticket_type_id: ticketTypeId,
        quantity: quantity
      });
      setAppliedCouponData(res.data);
      setCouponSuccess(res.data.message);
    } catch (err) {
      console.error(err);
      setCouponError(err.response?.data?.message || 'Invalid coupon code.');
    } finally {
      setIsValidatingCoupon(false);
    }
  };

  const handleRemoveCoupon = () => {
    setCouponCode('');
    setAppliedCouponData(null);
    setCouponSuccess(null);
    setCouponError(null);
  };

  const handleGuestDetailChange = (index, field, value) => {
    const updated = [...guestDetails];
    updated[index][field] = value;
    setGuestDetails(updated);
  };

  // Perform checkout booking
  const handleCheckoutSubmit = async (e) => {
    e.preventDefault();
    if (!attendeeName || !attendeeEmail) {
      alert('Primary attendee information is required.');
      return;
    }

    // Verify all guests have details
    for (let i = 0; i < guestDetails.length; i++) {
      if (!guestDetails[i].name || !guestDetails[i].email) {
        alert(`Please fill in details for Guest #${i + 1}`);
        return;
      }
    }

    setBookingLoading(true);
    try {
      // Package attendee details
      const attendeeDetailsList = [
        { name: attendeeName, email: attendeeEmail, is_primary: true },
        ...guestDetails.map(g => ({ ...g, is_primary: false }))
      ];

      const payload = {
        ticket_type_id: ticketTypeId,
        quantity: quantity,
        attendee_details: attendeeDetailsList,
        coupon_code: appliedCouponData ? appliedCouponData.code : null
      };

      const res = await api.post(`/events/${event.id}/book`, payload);

      if (res.data.requires_payment) {
        // Redirect to payment sandbox page
        navigate(`/payment-checkout?payment_id=${res.data.payment_id}&amount=${res.data.amount}`);
      } else {
        // Free ticket secured immediately
        setBookingSuccessData(res.data);
      }
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to complete ticket booking reservation.');
    } finally {
      setBookingLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[#FAF9F5] dark:bg-slate-950 transition-colors duration-500">
        <div className="flex flex-col items-center gap-4">
          <div className="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
          <p className="text-xs font-black uppercase tracking-widest text-slate-400">Securing checkout session...</p>
        </div>
      </div>
    );
  }

  if (error || !event || !ticketType) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[#FAF9F5] dark:bg-slate-950 px-6">
        <div className="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-10 rounded-[2.5rem] shadow-xl text-center">
          <div className="w-16 h-16 bg-red-50 dark:bg-red-950/20 rounded-2xl flex items-center justify-center text-red-500 mx-auto mb-6">
            <AlertTriangle className="w-8 h-8" />
          </div>
          <h2 className="text-2xl font-serif font-medium text-slate-900 dark:text-white mb-2">Checkout Halted</h2>
          <p className="text-sm text-slate-400 dark:text-slate-400 mb-6">{error || 'Checkout context could not be resolved.'}</p>
          <Link to="/events" className="btn-primary px-8 py-3.5 text-xs font-black uppercase tracking-widest w-full text-center block">
            Return to Directory
          </Link>
        </div>
      </div>
    );
  }

  const unitPrice = ticketType.current_price !== undefined ? ticketType.current_price : ticketType.price;
  const baseSubtotal = appliedCouponData ? appliedCouponData.subtotal : (unitPrice * quantity);
  const discount = appliedCouponData ? appliedCouponData.discount : 0;
  const estimatedTotal = appliedCouponData ? appliedCouponData.total : baseSubtotal;

  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden pt-28 pb-20">
      <Navbar />

      <div className="max-w-5xl mx-auto px-6 md:px-12">
        
        {/* Back Link */}
        <div className="mb-8 text-left">
          <Link to={`/events/${event.slug}`} className="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-primary transition-colors">
            <ArrowLeft className="w-4 h-4" />
            Back to Experience details
          </Link>
        </div>

        {bookingSuccessData ? (
          /* Instant Success Panel (Free tickets) */
          <div className="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-2xl rounded-[3rem] text-center max-w-lg mx-auto space-y-8 animate-slide-up">
            <div className="relative w-24 h-24 flex items-center justify-center mx-auto">
              <div className="absolute inset-0 bg-[#4E7D5B]/20 rounded-full blur-xl animate-pulse"></div>
              <CheckCircle className="w-20 h-20 text-[#4E7D5B]" />
            </div>

            <div className="space-y-2">
              <span className="px-4 py-1.5 bg-[#4E7D5B]/10 border border-[#4E7D5B]/20 text-[#4E7D5B] rounded-full text-[9px] font-black tracking-widest uppercase">
                RESERVATION COMPLETED
              </span>
              <h3 className="text-2xl font-serif text-slate-900 dark:text-white leading-tight font-bold">Now, the ticket is yours!</h3>
              <p className="text-slate-500 dark:text-slate-400 text-xs font-serif italic">
                "Your digital presence has been verified and registered within the experience node."
              </p>
            </div>

            {/* Punched Ticket Card */}
            <div className="w-full bg-[#1E293B] text-white rounded-3xl p-6 relative overflow-hidden shadow-xl border border-white/5 text-left">
              <div className="absolute -right-16 -top-16 w-32 h-32 bg-[#4E7D5B]/20 rounded-full blur-[40px] pointer-events-none"></div>
              <div className="absolute -left-16 -bottom-16 w-32 h-32 bg-amber-500/10 rounded-full blur-[40px] pointer-events-none"></div>
              
              <div className="flex flex-col space-y-4">
                <div className="flex justify-between items-start border-b border-white/10 pb-4">
                  <div className="min-w-0">
                    <span className="text-[8px] font-black tracking-widest uppercase text-slate-400 block mb-0.5">EVENT PASS</span>
                    <h4 className="font-serif text-sm text-white truncate max-w-[200px] font-bold">{event.title}</h4>
                  </div>
                  <div className="text-right shrink-0">
                    <span className="text-[8px] font-black tracking-widest uppercase text-slate-400 block mb-0.5">REFERENCE</span>
                    <span className="font-mono text-xs font-bold text-[#4E7D5B]">#{bookingSuccessData.booking_reference}</span>
                  </div>
                </div>

                <div className="grid grid-cols-2 gap-4 text-xs">
                  <div>
                    <span className="text-[8px] font-black tracking-widest uppercase text-slate-500 block mb-0.5">TEMPORAL NODE</span>
                    <span className="font-bold text-slate-200">
                      {new Date(event.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                    </span>
                  </div>
                  <div>
                    <span className="text-[8px] font-black tracking-widest uppercase text-slate-500 block mb-0.5">SPATIAL INDEX</span>
                    <span className="font-bold text-slate-200 truncate block max-w-[120px]">{event.venue?.name || 'Digital Portal'}</span>
                  </div>
                </div>
              </div>

              {/* Punched ticket circles */}
              <div className="absolute top-1/2 -left-3 w-6 h-6 bg-white dark:bg-slate-900 rounded-full -translate-y-1/2"></div>
              <div className="absolute top-1/2 -right-3 w-6 h-6 bg-white dark:bg-slate-900 rounded-full -translate-y-1/2"></div>
            </div>

            <div className="w-full flex flex-col gap-3">
              <Link to={`/my-tickets/${bookingSuccessData.booking_reference}`} className="w-full btn-primary py-4 text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/20 text-center">
                View My Ticket
              </Link>
              <Link to="/dashboard" className="w-full text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 dark:hover:text-white transition-colors py-2 block">
                Return to Portal
              </Link>
            </div>
          </div>
        ) : (
          /* Normal Billing and Details Form */
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start text-left">
            
            {/* Left: Input Form */}
            <form onSubmit={handleCheckoutSubmit} className="lg:col-span-7 space-y-8">
              
              {/* Attendee details card */}
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-sm space-y-6">
                <div>
                  <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">RESONATOR DATA</span>
                  <h2 className="text-2xl font-serif text-slate-900 dark:text-white font-bold">Primary Attendee Details</h2>
                  <p className="text-xs text-slate-400 mt-1">This contact will receive all digital codes and ticket notifications.</p>
                </div>

                <div className="space-y-4">
                  <div className="relative flex items-center bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 px-4">
                    <User className="w-4 h-4 text-slate-400 shrink-0" />
                    <input 
                      type="text" 
                      required 
                      value={attendeeName}
                      onChange={(e) => setAttendeeName(e.target.value)}
                      placeholder="Full Name" 
                      className="flex-1 bg-transparent border-none text-xs py-3.5 px-3 placeholder:text-slate-400 text-slate-800 dark:text-slate-200 outline-none"
                    />
                  </div>

                  <div className="relative flex items-center bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 px-4">
                    <Mail className="w-4 h-4 text-slate-400 shrink-0" />
                    <input 
                      type="email" 
                      required 
                      value={attendeeEmail}
                      onChange={(e) => setAttendeeEmail(e.target.value)}
                      placeholder="Email Address" 
                      className="flex-1 bg-transparent border-none text-xs py-3.5 px-3 placeholder:text-slate-400 text-slate-800 dark:text-slate-200 outline-none"
                    />
                  </div>
                </div>
              </div>

              {/* Guest detail cards */}
              {guestDetails.map((guest, idx) => (
                <div key={idx} className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-8 rounded-3xl shadow-sm space-y-6">
                  <div>
                    <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">RESONANCE KEY #{idx + 2}</span>
                    <h2 className="text-xl font-serif text-slate-900 dark:text-white font-bold">Guest #{idx + 1} Details</h2>
                  </div>

                  <div className="space-y-4">
                    <div className="relative flex items-center bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 px-4">
                      <User className="w-4 h-4 text-slate-400 shrink-0" />
                      <input 
                        type="text" 
                        required 
                        value={guest.name}
                        onChange={(e) => handleGuestDetailChange(idx, 'name', e.target.value)}
                        placeholder="Guest Name" 
                        className="flex-1 bg-transparent border-none text-xs py-3.5 px-3 placeholder:text-slate-400 text-slate-800 dark:text-slate-200 outline-none"
                      />
                    </div>

                    <div className="relative flex items-center bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 px-4">
                      <Mail className="w-4 h-4 text-slate-400 shrink-0" />
                      <input 
                        type="email" 
                        required 
                        value={guest.email}
                        onChange={(e) => handleGuestDetailChange(idx, 'email', e.target.value)}
                        placeholder="Guest Email Address" 
                        className="flex-1 bg-transparent border-none text-xs py-3.5 px-3 placeholder:text-slate-400 text-slate-800 dark:text-slate-200 outline-none"
                      />
                    </div>
                  </div>
                </div>
              ))}

              <button 
                type="submit" 
                disabled={bookingLoading}
                className="w-full btn-primary py-4.5 px-6 rounded-full text-xs font-black uppercase tracking-[0.25em] shadow-lg shadow-[#4E7D5B]/20 cursor-pointer disabled:opacity-50"
              >
                {bookingLoading ? 'Processing Booking...' : (estimatedTotal > 0 ? 'Proceed to Payment Gateway' : 'Secure Free Reservation')}
              </button>
            </form>

            {/* Right: Order Summary and Coupon Widget */}
            <div className="lg:col-span-5 space-y-8">
              
              {/* Event card summary */}
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 flex gap-4">
                <img src={event.banner} alt={event.title} className="w-20 h-20 rounded-2xl object-cover shrink-0" />
                <div className="min-w-0">
                  <span className="text-[8px] font-black text-primary bg-[#4E7D5B]/10 px-2 py-0.5 rounded border border-primary/25 tracking-widest uppercase">
                    {event.category}
                  </span>
                  <h4 className="font-serif text-sm font-bold text-slate-900 dark:text-white truncate mt-1.5">{event.title}</h4>
                  <div className="flex items-center gap-4 text-[9px] text-slate-400 mt-2 font-black uppercase tracking-wider">
                    <span className="flex items-center gap-1"><Calendar className="w-3.5 h-3.5 text-primary" /> {new Date(event.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</span>
                    <span className="flex items-center gap-1"><MapPin className="w-3.5 h-3.5 text-primary" /> {event.venue?.city || 'Online'}</span>
                  </div>
                </div>
              </div>

              {/* Booking Breakdown */}
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-8 space-y-6">
                <div>
                  <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Summary Nodes</span>
                  <h3 className="text-xl font-serif text-slate-900 dark:text-white font-bold">Billing Architecture</h3>
                </div>

                <div className="space-y-3.5 text-xs">
                  <div className="flex justify-between items-center text-slate-500">
                    <span>{ticketType.name} (₹{unitPrice.toLocaleString('en-IN')} × {quantity})</span>
                    <span className="font-bold text-slate-800 dark:text-slate-200">
                      ₹{baseSubtotal.toLocaleString('en-IN')}
                    </span>
                  </div>

                  {discount > 0 && (
                    <div className="flex justify-between items-center text-emerald-600">
                      <span>Coupon Discount</span>
                      <span className="font-bold">
                        -₹{discount.toLocaleString('en-IN')}
                      </span>
                    </div>
                  )}

                  <div className="flex justify-between items-center text-slate-500">
                    <span>Ecosystem Commission</span>
                    <span className="font-bold text-emerald-600">Free</span>
                  </div>

                  <div className="flex justify-between items-end pt-4 border-t border-dashed border-slate-200 dark:border-slate-800">
                    <span className="font-bold uppercase tracking-wider text-slate-900 dark:text-white">Amount Due</span>
                    <span className="text-2xl font-serif font-bold text-[#4E7D5B]">
                      ₹{estimatedTotal.toLocaleString('en-IN')}
                    </span>
                  </div>
                </div>
              </div>

              {/* Available Coupons */}
              {event.available_coupons && event.available_coupons.length > 0 && (
                <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-8 space-y-4">
                  <div>
                    <span className="text-[9px] font-black text-[#4E7D5B] uppercase tracking-widest block mb-1">PROMOTIONS</span>
                    <h3 className="text-xl font-serif text-slate-900 dark:text-white font-bold">Available Coupons</h3>
                  </div>
                  <div className="space-y-3">
                    {event.available_coupons.map((c, i) => (
                      <div key={i} className="flex items-center justify-between p-3 border border-emerald-100 dark:border-emerald-900/50 bg-emerald-50/50 dark:bg-emerald-900/10 rounded-xl">
                        <div>
                          <div className="font-bold text-xs text-emerald-700">{c.code}</div>
                          <div className="text-[10px] text-emerald-600/70 uppercase tracking-widest font-bold mt-0.5">
                            {c.type === 'percentage' ? `${c.value}% OFF` : `₹${c.value} OFF`}
                          </div>
                        </div>
                        <button 
                          type="button" 
                          onClick={() => setCouponCode(c.code)}
                          className="text-[10px] bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg font-black uppercase tracking-wider hover:bg-emerald-200 transition-colors"
                        >
                          Use
                        </button>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Coupon Form */}
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-8 space-y-6">
                <div>
                  <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">PROMOTION KEYS</span>
                  <h3 className="text-xl font-serif text-slate-900 dark:text-white font-bold">Apply Coupon</h3>
                </div>

                {appliedCouponData ? (
                  <div className="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900 rounded-2xl flex justify-between items-center">
                    <div>
                      <span className="text-[9px] font-black text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded uppercase tracking-wider">
                        {appliedCouponData.code}
                      </span>
                      <p className="text-xs text-emerald-700 mt-1.5 font-medium">{couponSuccess}</p>
                    </div>
                    <button 
                      type="button" 
                      onClick={handleRemoveCoupon}
                      className="text-xs text-rose-500 font-bold hover:underline cursor-pointer"
                    >
                      Remove
                    </button>
                  </div>
                ) : (
                  <form onSubmit={handleValidateCoupon} className="flex gap-2">
                    <div className="relative flex items-center bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 px-4 flex-1">
                      <Tag className="w-4 h-4 text-slate-400 shrink-0" />
                      <input 
                        type="text" 
                        value={couponCode}
                        onChange={(e) => setCouponCode(e.target.value)}
                        placeholder="Coupon Code" 
                        className="w-full bg-transparent border-none text-xs py-3 px-3 placeholder:text-slate-400 text-slate-800 dark:text-slate-205 outline-none font-bold uppercase"
                      />
                    </div>
                    <button 
                      type="submit" 
                      disabled={isValidatingCoupon || !couponCode.trim()}
                      className="px-6 py-3 bg-[#4E7D5B] hover:bg-[#3D6449] text-white rounded-xl text-[10px] font-black uppercase tracking-wider transition-colors cursor-pointer disabled:opacity-50"
                    >
                      {isValidatingCoupon ? 'Checking...' : 'Apply'}
                    </button>
                  </form>
                )}

                {couponError && (
                  <div className="p-3 bg-red-50 text-red-700 rounded-xl text-xs font-bold uppercase tracking-wider text-center border border-red-100">
                    {couponError}
                  </div>
                )}
              </div>

              {/* Trust Badge */}
              <div className="flex items-center justify-center gap-2 text-[9px] font-black text-slate-400 uppercase tracking-widest pt-2">
                <Lock className="w-3.5 h-3.5 text-primary" />
                SECURE SSL 256-BIT ENCRYPTION
              </div>

            </div>

          </div>
        )}

      </div>
    </div>
  );
}
