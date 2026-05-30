import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  ArrowLeft, 
  Scan, 
  UserCheck, 
  AlertCircle, 
  Search, 
  CheckCircle,
  HelpCircle,
  Users
} from 'lucide-react';

export default function Scanner() {
  const { id } = useParams();
  const [event, setEvent] = useState(null);
  const [attendees, setAttendees] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  // Scan input states
  const [qrInput, setQrInput] = useState('');
  const [scanning, setScanning] = useState(false);
  const [scanResult, setScanResult] = useState(null);

  useEffect(() => {
    loadData();
  }, [id]);

  const loadData = async () => {
    try {
      setLoading(true);
      // Fetch event
      const evtRes = await api.get(`/organizer/events/${id}`);
      setEvent(evtRes.data.event);

      // Fetch attendees
      const attRes = await api.get(`/organizer/events/${id}/attendees`);
      setAttendees(attRes.data.data || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleScanVerify = async (code) => {
    const qrData = code || qrInput;
    if (!qrData.trim()) return;

    setScanning(true);
    setScanResult(null);

    try {
      const res = await api.post(`/organizer/events/${id}/scan`, {
        qr_data: qrData.trim()
      });
      setScanResult({
        success: true,
        message: res.data.message,
        attendee: res.data.attendee
      });
      setQrInput('');
      // Reload attendees list
      const attRes = await api.get(`/organizer/events/${id}/attendees`);
      setAttendees(attRes.data.data || []);
    } catch (err) {
      console.error(err);
      setScanResult({
        success: false,
        message: err.response?.data?.message || 'Verification failed. Invalid QR code.'
      });
    } finally {
      setScanning(false);
    }
  };

  const filteredAttendees = attendees.filter(a => 
    a.attendee_name.toLowerCase().includes(search.toLowerCase()) ||
    a.attendee_email.toLowerCase().includes(search.toLowerCase()) ||
    a.ticket_reference.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <SidebarLayout type="organizer">
      <div className="space-y-8 text-left animate-slide-up">
        
        {/* Navigation back */}
        <div>
          <Link to="/organizer/events" className="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-450 hover:text-primary transition-colors">
            <ArrowLeft className="w-4 h-4" />
            Back to Blueprints
          </Link>
        </div>

        {/* Title bar */}
        <div className="pb-6 border-b border-slate-100 dark:border-slate-800">
          <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">ADMISSION VERIFICATION</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">
            Check-In Scanner
          </h1>
          <p className="text-xs text-slate-400 mt-1">
            Event Node: <span className="font-bold text-slate-800 dark:text-slate-200">{event ? event.title : 'Loading...'}</span>
          </p>
        </div>

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-450">Syncing registry console...</span>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            {/* Left: Check-In input console */}
            <div className="lg:col-span-5 space-y-6">
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm space-y-6">
                <div>
                  <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold flex items-center gap-2">
                    <Scan className="w-5 h-5 text-primary" />
                    <span>Scan Portal</span>
                  </h3>
                  <p className="text-xs text-slate-450 mt-1">Paste encrypted QR payloads or booking references to process check-in.</p>
                </div>

                {scanResult && (
                  <div className={`p-6 rounded-2xl border text-xs flex gap-3 ${
                    scanResult.success 
                      ? 'bg-emerald-50 text-emerald-800 border-emerald-100 dark:bg-emerald-950/20 dark:border-emerald-900' 
                      : 'bg-rose-50 text-rose-800 border-rose-100 dark:bg-rose-950/20 dark:border-rose-900'
                  }`}>
                    {scanResult.success ? (
                      <CheckCircle className="w-5 h-5 text-emerald-600 shrink-0" />
                    ) : (
                      <AlertCircle className="w-5 h-5 text-rose-600 shrink-0" />
                    )}
                    <div>
                      <h4 className="font-bold uppercase tracking-wider">{scanResult.success ? 'Access Granted' : 'Access Denied'}</h4>
                      <p className="mt-1 leading-normal">{scanResult.message}</p>
                      {scanResult.attendee && (
                        <div className="mt-3 pt-3 border-t border-emerald-105/50 text-[10px] space-y-1">
                          <div><strong>Attendee:</strong> {scanResult.attendee.name}</div>
                          <div><strong>Tier:</strong> {scanResult.attendee.ticket_type}</div>
                        </div>
                      )}
                    </div>
                  </div>
                )}

                <div className="space-y-4">
                  <div className="relative flex items-center bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 px-4">
                    <input 
                      type="text" 
                      value={qrInput}
                      onChange={(e) => setQrInput(e.target.value)}
                      placeholder="Enter QR token / Ref (SE-...)" 
                      className="flex-1 bg-transparent border-none text-xs py-3.5 px-1 placeholder:text-slate-400 text-slate-850 dark:text-slate-200 outline-none"
                    />
                  </div>

                  <button 
                    onClick={() => handleScanVerify()}
                    disabled={scanning || !qrInput.trim()}
                    className="w-full btn-primary py-3.5 text-xs font-black uppercase tracking-widest cursor-pointer disabled:opacity-50"
                  >
                    {scanning ? 'Verifying Pass...' : 'Verify & Check In'}
                  </button>
                </div>
              </div>
            </div>

            {/* Right: Participant registry simulator */}
            <div className="lg:col-span-7 space-y-6">
              <div className="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm space-y-6">
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                  <div>
                    <h3 className="text-lg font-serif text-slate-900 dark:text-white font-bold flex items-center gap-2">
                      <Users className="w-5 h-5 text-primary" />
                      <span>Attendee Registry</span>
                    </h3>
                    <p className="text-xs text-slate-400 mt-1">Ecosystem logs for check-in verification simulations.</p>
                  </div>
                  <div className="relative flex items-center bg-slate-50 dark:bg-slate-950 rounded-full border border-slate-100 dark:border-slate-800 px-3 py-1.5 max-w-[200px] w-full">
                    <Search className="w-3.5 h-3.5 text-slate-400 shrink-0" />
                    <input 
                      type="text" 
                      placeholder="Search..." 
                      value={search}
                      onChange={(e) => setSearch(e.target.value)}
                      className="flex-1 bg-transparent border-none text-[10px] py-1 px-2 placeholder:text-slate-400 text-slate-850 dark:text-slate-200 outline-none"
                    />
                  </div>
                </div>

                <div className="space-y-3 max-h-[450px] overflow-y-auto pr-1">
                  {filteredAttendees.length > 0 ? (
                    filteredAttendees.map((a) => {
                      const isChecked = !!a.checked_in_at;
                      return (
                        <div 
                          key={a.ticket_reference} 
                          className={`p-4 rounded-xl border flex items-center justify-between gap-4 text-xs ${
                            isChecked 
                              ? 'bg-slate-50/50 dark:bg-slate-950/20 border-slate-100 dark:border-slate-855/50' 
                              : 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 hover:border-primary/20'
                          }`}
                        >
                          <div className="text-left min-w-0 flex-1">
                            <h4 className="font-bold text-slate-900 dark:text-white truncate">{a.attendee_name}</h4>
                            <p className="text-[10px] text-slate-400 mt-0.5">{a.attendee_email}</p>
                            <div className="flex items-center gap-1.5 text-[9px] text-slate-400 uppercase tracking-widest mt-1">
                              <span>Ref: {a.ticket_reference}</span>
                              <span>&bull;</span>
                              <span>{a.ticket_type}</span>
                            </div>
                          </div>
                          
                          {isChecked ? (
                            <span className="px-2.5 py-0.5 rounded bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 text-[8px] font-black uppercase tracking-wider shrink-0">
                              Checked In ({new Date(a.checked_in_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })})
                            </span>
                          ) : (
                            <button 
                              onClick={() => handleScanVerify(a.ticket_reference)}
                              className="px-3.5 py-2 bg-[#4E7D5B] text-white rounded-lg text-[9px] font-black uppercase tracking-wider hover:bg-[#3D6449] transition-all cursor-pointer shrink-0"
                            >
                              Check In
                            </button>
                          )}
                        </div>
                      );
                    })
                  ) : (
                    <div className="py-8 text-center text-slate-400 text-xs">
                      No attendee matches found in registry.
                    </div>
                  )}
                </div>
              </div>
            </div>

          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
