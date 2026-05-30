import React, { useState, useEffect, useRef, useCallback } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import SidebarLayout from '../../components/SidebarLayout';
import api from '../../services/api';
import { 
  ArrowLeft, 
  Calendar, 
  MapPin, 
  Upload, 
  Check, 
  Layers,
  Search,
  Loader2,
  Navigation,
  X
} from 'lucide-react';

export default function CreateEvent() {
  const { id } = useParams();
  const navigate = useNavigate();
  const isEdit = !!id;

  const [step, setStep] = useState(1);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);

  // Options lists
  const [categories, setCategories] = useState([]);
  const [venues, setVenues] = useState([]);

  // Form Fields
  const [title, setTitle] = useState('');
  const [categoryId, setCategoryId] = useState('');
  const [venueId, setVenueId] = useState('');
  const [shortDescription, setShortDescription] = useState('');
  const [description, setDescription] = useState('');
  const [type, setType] = useState('physical');
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');
  const [totalCapacity, setTotalCapacity] = useState(100);
  const [visibility, setVisibility] = useState('public');
  const [timezone, setTimezone] = useState('Asia/Kolkata');
  const [requiresApproval, setRequiresApproval] = useState(false);
  
  // Custom Venue creation
  const [createNewVenue, setCreateNewVenue] = useState(false);
  const [venueName, setVenueName] = useState('');
  const [venueAddress, setVenueAddress] = useState('');
  const [venueCity, setVenueCity] = useState('');
  const [venueState, setVenueState] = useState('');
  const [venueCountry, setVenueCountry] = useState('India');
  const [venuePincode, setVenuePincode] = useState('');
  const [venueLat, setVenueLat] = useState('');
  const [venueLng, setVenueLng] = useState('');

  // Location Search
  const [locationQuery, setLocationQuery] = useState('');
  const [locationResults, setLocationResults] = useState([]);
  const [locationSearching, setLocationSearching] = useState(false);
  const [showLocationDropdown, setShowLocationDropdown] = useState(false);
  const locationSearchRef = useRef(null);
  const locationDebounceRef = useRef(null);

  // Image banner uploader
  const [bannerFile, setBannerFile] = useState(null);
  const [bannerPreview, setBannerPreview] = useState(null);

  // Load initial options (categories/venues)
  useEffect(() => {
    async function loadOptions() {
      try {
        setLoading(true);
        const catRes = await api.get('/categories');
        const cats = catRes.data.categories || catRes.data || [];
        setCategories(cats);
        
        const venRes = await api.get('/organizer/venues');
        setVenues(venRes.data.venues || []);

        if (cats && cats.length > 0) {
          setCategoryId(cats[0].id);
        }

        if (isEdit) {
          const evtRes = await api.get(`/organizer/events/${id}`);
          const evt = evtRes.data.event;
          
          setTitle(evt.title || '');
          setCategoryId(evt.category_id || '');
          setVenueId(evt.venue_id || '');
          setShortDescription(evt.short_description || '');
          setDescription(evt.description || '');
          setType(evt.type || 'physical');
          setTotalCapacity(evt.total_capacity || 100);
          setVisibility(evt.visibility || 'public');
          setTimezone(evt.timezone || 'Asia/Kolkata');
          setRequiresApproval(!!evt.requires_approval);

          if (evt.start_date) setStartDate(evt.start_date.substring(0, 16));
          if (evt.end_date) setEndDate(evt.end_date.substring(0, 16));
          if (evt.banner) setBannerPreview(evt.banner);
        }
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    }
    loadOptions();
  }, [id, isEdit]);

  // Close location dropdown on outside click
  useEffect(() => {
    function handleClickOutside(e) {
      if (locationSearchRef.current && !locationSearchRef.current.contains(e.target)) {
        setShowLocationDropdown(false);
      }
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  // Search via Nominatim OpenStreetMap (free, no API key)
  const searchLocation = useCallback(async (query) => {
    if (!query || query.trim().length < 3) {
      setLocationResults([]);
      setShowLocationDropdown(false);
      return;
    }
    setLocationSearching(true);
    try {
      const res = await fetch(
        `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=6`,
        { headers: { 'Accept-Language': 'en' } }
      );
      const data = await res.json();
      setLocationResults(data);
      setShowLocationDropdown(data.length > 0);
    } catch (err) {
      console.error('Location search failed', err);
    } finally {
      setLocationSearching(false);
    }
  }, []);

  const handleLocationQueryChange = (e) => {
    const val = e.target.value;
    setLocationQuery(val);
    clearTimeout(locationDebounceRef.current);
    locationDebounceRef.current = setTimeout(() => searchLocation(val), 500);
  };

  const handleLocationSelect = (place) => {
    const addr = place.address;
    // Populate venue fields from Nominatim address object
    setVenueName(place.display_name.split(',')[0].trim());
    setVenueAddress(
      [addr.road, addr.neighbourhood, addr.suburb]
        .filter(Boolean)
        .join(', ') || place.display_name.split(',').slice(0, 2).join(',').trim()
    );
    setVenueCity(addr.city || addr.town || addr.village || addr.county || '');
    setVenueState(addr.state || '');
    setVenueCountry(addr.country || 'India');
    setVenuePincode(addr.postcode || '');
    setVenueLat(place.lat);
    setVenueLng(place.lon);
    setLocationQuery(place.display_name);
    setShowLocationDropdown(false);
  };

  const clearLocation = () => {
    setLocationQuery('');
    setVenueName('');
    setVenueAddress('');
    setVenueCity('');
    setVenueState('');
    setVenueCountry('India');
    setVenuePincode('');
    setVenueLat('');
    setVenueLng('');
  };

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setBannerFile(file);
      setBannerPreview(URL.createObjectURL(file));
    }
  };

  const handleNext = () => {
    if (step === 1) {
      if (!title) { alert('Please enter an event title.'); return; }
      setStep(2);
    } else if (step === 2) {
      if (!startDate || !endDate) { alert('Please enter start and end date times.'); return; }
      if (new Date(startDate) >= new Date(endDate)) { alert('End date must be after start date.'); return; }
      if (type === 'physical' && !venueId && !createNewVenue) { alert('Please select or create a venue.'); return; }
      setStep(3);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const formData = new FormData();
      formData.append('title', title);
      formData.append('category_id', categoryId);
      formData.append('short_description', shortDescription);
      formData.append('description', description);
      formData.append('type', type);
      formData.append('start_date', startDate);
      formData.append('end_date', endDate);
      formData.append('total_capacity', totalCapacity);
      formData.append('visibility', visibility);
      formData.append('timezone', timezone);
      formData.append('requires_approval', requiresApproval ? 1 : 0);

      if (type === 'physical' || type === 'hybrid') {
        if (createNewVenue) {
          formData.append('create_new_venue', 1);
          formData.append('venue_name', venueName);
          formData.append('venue_address', venueAddress);
          formData.append('venue_city', venueCity);
          formData.append('venue_state', venueState);
          formData.append('venue_country', venueCountry);
          formData.append('venue_pincode', venuePincode);
          if (venueLat) formData.append('venue_latitude', venueLat);
          if (venueLng) formData.append('venue_longitude', venueLng);
        } else {
          formData.append('venue_id', venueId);
        }
      }

      if (bannerFile) formData.append('banner', bannerFile);

      let res;
      if (isEdit) {
        formData.append('_method', 'PUT');
        res = await api.post(`/organizer/events/${id}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      } else {
        res = await api.post('/organizer/events', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
      }

      alert(res.data.message);
      navigate('/organizer/events');
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.message || 'Failed to save event blueprint.');
    } finally {
      setSaving(false);
    }
  };

  // Build OSM embed URL for preview
  const getMapEmbedUrl = () => {
    if (venueLat && venueLng) {
      return `https://www.openstreetmap.org/export/embed.html?bbox=${parseFloat(venueLng)-0.005},${parseFloat(venueLat)-0.005},${parseFloat(venueLng)+0.005},${parseFloat(venueLat)+0.005}&layer=mapnik&marker=${venueLat},${venueLng}`;
    }
    return null;
  };

  const mapEmbedUrl = getMapEmbedUrl();

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
          <span className="text-[9px] font-black text-primary uppercase tracking-[0.2em] block mb-1">ARCHITECT BLUEPRINT</span>
          <h1 className="text-3xl font-serif text-slate-900 dark:text-white font-bold leading-tight">
            {isEdit ? 'Modify Event Blueprint' : 'Deploy Event Blueprint'}
          </h1>
          <p className="text-xs text-slate-400 mt-1">Configure layout, capacity, visual assets, and timelines for public curation.</p>
        </div>

        {loading ? (
          <div className="py-24 flex flex-col items-center gap-4">
            <div className="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            <span className="text-xs font-black uppercase tracking-widest text-slate-450">Loading blueprint options...</span>
          </div>
        ) : (
          <div className="max-w-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm">
            
            {/* Step Wizard Header */}
            <div className="flex items-center justify-between pb-6 border-b border-slate-100 dark:border-slate-800 mb-8">
              <button onClick={() => setStep(1)} className="flex items-center gap-2 text-left outline-none cursor-pointer">
                <div className={`w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-colors ${
                  step >= 1 ? 'bg-primary text-white' : 'border border-slate-205 text-slate-400'
                }`}>1</div>
                <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Specifications</span>
              </button>
              <div className="flex-1 h-px bg-slate-100 dark:bg-slate-800 mx-4"></div>
              <button onClick={() => step > 1 && setStep(2)} className="flex items-center gap-2 text-left outline-none cursor-pointer">
                <div className={`w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-colors ${
                  step >= 2 ? 'bg-primary text-white' : 'border border-slate-205 text-slate-400'
                }`}>2</div>
                <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Time & Space</span>
              </button>
              <div className="flex-1 h-px bg-slate-100 dark:bg-slate-800 mx-4"></div>
              <button onClick={() => step > 2 && setStep(3)} className="flex items-center gap-2 text-left outline-none cursor-pointer">
                <div className={`w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-colors ${
                  step >= 3 ? 'bg-primary text-white' : 'border border-slate-205 text-slate-400'
                }`}>3</div>
                <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Assets</span>
              </button>
            </div>

            {/* Step 1: Basic Specifications */}
            {step === 1 && (
              <div className="space-y-6 animate-slide-up">
                <div className="space-y-4">
                  <div>
                    <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Event Title</label>
                    <input 
                      type="text" 
                      required
                      value={title}
                      onChange={(e) => setTitle(e.target.value)}
                      placeholder="e.g. Global Curation Summit"
                      className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                    />
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Category Domain</label>
                      <select 
                        value={categoryId}
                        onChange={(e) => setCategoryId(e.target.value)}
                        required
                        className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer"
                      >
                        {categories.map((c) => (
                          <option key={c.id} value={c.id}>{c.name}</option>
                        ))}
                      </select>
                    </div>

                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Experience Format</label>
                      <select 
                        value={type}
                        onChange={(e) => setType(e.target.value)}
                        required
                        className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-850 dark:text-slate-200 outline-none cursor-pointer"
                      >
                        <option value="physical">Physical Location Gathering</option>
                        <option value="online">Online / Digital Portal</option>
                        <option value="hybrid">Hybrid Curation</option>
                      </select>
                    </div>

                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Total Capacity</label>
                      <input 
                        type="number" 
                        required
                        min="1"
                        value={totalCapacity}
                        onChange={(e) => setTotalCapacity(parseInt(e.target.value))}
                        className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                      />
                    </div>

                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Visibility Index</label>
                      <select 
                        value={visibility}
                        onChange={(e) => setVisibility(e.target.value)}
                        required
                        className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-805 dark:text-slate-200 outline-none cursor-pointer"
                      >
                        <option value="public">Public Marketplace Listing</option>
                        <option value="private">Private (Invite Link Only)</option>
                        <option value="unlisted">Unlisted (Dashboard Exclusive)</option>
                      </select>
                    </div>

                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Timezone</label>
                      <select 
                        value={timezone}
                        onChange={(e) => setTimezone(e.target.value)}
                        required
                        className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-850 dark:text-slate-200 outline-none cursor-pointer"
                      >
                        <option value="Asia/Kolkata">Asia/Kolkata (IST)</option>
                        <option value="UTC">Coordinated Universal Time (UTC)</option>
                        <option value="America/New_York">America/New York (EST/EDT)</option>
                        <option value="Europe/London">Europe/London (GMT/BST)</option>
                      </select>
                    </div>

                    <div className="flex items-center pt-6">
                      <label className="flex items-center gap-3 cursor-pointer">
                        <input 
                          type="checkbox" 
                          checked={requiresApproval}
                          onChange={(e) => setRequiresApproval(e.target.checked)}
                          className="w-4 h-4 rounded border-slate-200 text-primary focus:ring-0 cursor-pointer" 
                        />
                        <span className="text-xs text-slate-550 dark:text-slate-350 font-bold uppercase tracking-wider select-none">
                          Requires Booking Approval
                        </span>
                      </label>
                    </div>
                  </div>
                </div>

                <div className="flex justify-end pt-4 border-t border-slate-100 dark:border-slate-800">
                  <button 
                    onClick={handleNext}
                    className="btn-primary px-10 py-3.5 text-xs font-black uppercase tracking-widest cursor-pointer"
                  >
                    Next Step &rarr;
                  </button>
                </div>
              </div>
            )}

            {/* Step 2: Time & Location */}
            {step === 2 && (
              <div className="space-y-6 animate-slide-up">
                <div className="space-y-6">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Start Date & Time</label>
                      <div className="relative">
                        <input 
                          type="datetime-local" 
                          required
                          value={startDate}
                          onChange={(e) => setStartDate(e.target.value)}
                          className="w-full pl-5 pr-11 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer relative [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:h-full [&::-webkit-calendar-picker-indicator]:opacity-0 [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:left-0 [&::-webkit-calendar-picker-indicator]:top-0"
                        />
                        <Calendar className="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 text-primary pointer-events-none" />
                      </div>
                    </div>
                    <div>
                      <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">End Date & Time</label>
                      <div className="relative">
                        <input 
                          type="datetime-local" 
                          required
                          value={endDate}
                          onChange={(e) => setEndDate(e.target.value)}
                          className="w-full pl-5 pr-11 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none cursor-pointer relative [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:h-full [&::-webkit-calendar-picker-indicator]:opacity-0 [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:left-0 [&::-webkit-calendar-picker-indicator]:top-0"
                        />
                        <Calendar className="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 text-primary pointer-events-none" />
                      </div>
                    </div>
                  </div>

                  {(type === 'physical' || type === 'hybrid') && (
                    <div className="space-y-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                      <div className="flex justify-between items-center">
                        <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest font-bold">Physical Location Curation</label>
                        <label className="flex items-center gap-2 cursor-pointer">
                          <input 
                            type="checkbox" 
                            checked={createNewVenue}
                            onChange={(e) => {
                              setCreateNewVenue(e.target.checked);
                              if (!e.target.checked) clearLocation();
                            }}
                            className="w-4 h-4 rounded border-slate-200 text-primary focus:ring-0 cursor-pointer" 
                          />
                          <span className="text-[9px] font-black text-slate-450 uppercase tracking-widest select-none">Create New Venue</span>
                        </label>
                      </div>

                      {createNewVenue ? (
                        <div className="space-y-5 animate-slide-up">

                          {/* === LOCATION SEARCH === */}
                          <div>
                            <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold flex items-center gap-1.5">
                              <Search className="w-3 h-3" />
                              Search Location (like Google Maps)
                            </label>
                            <div className="relative" ref={locationSearchRef}>
                              <div className="relative flex items-center">
                                <Search className="absolute left-4 w-4 h-4 text-slate-400 pointer-events-none" />
                                <input
                                  type="text"
                                  value={locationQuery}
                                  onChange={handleLocationQueryChange}
                                  onFocus={() => locationResults.length > 0 && setShowLocationDropdown(true)}
                                  placeholder="Search for a place, address, city..."
                                  className="w-full pl-11 pr-10 py-3.5 bg-slate-50 dark:bg-slate-950 border-2 border-primary/40 focus:border-primary rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none transition-colors"
                                />
                                {locationSearching && (
                                  <Loader2 className="absolute right-4 w-4 h-4 text-primary animate-spin" />
                                )}
                                {!locationSearching && locationQuery && (
                                  <button
                                    type="button"
                                    onClick={clearLocation}
                                    className="absolute right-4 w-4 h-4 text-slate-400 hover:text-slate-600 cursor-pointer"
                                  >
                                    <X className="w-4 h-4" />
                                  </button>
                                )}
                              </div>

                              {/* Search Dropdown Results */}
                              {showLocationDropdown && locationResults.length > 0 && (
                                <div className="absolute z-50 mt-2 w-full bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                                  {locationResults.map((place, idx) => (
                                    <button
                                      key={idx}
                                      type="button"
                                      onClick={() => handleLocationSelect(place)}
                                      className="w-full flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800 text-left transition-colors border-b border-slate-50 dark:border-slate-800/50 last:border-b-0 cursor-pointer"
                                    >
                                      <MapPin className="w-4 h-4 text-primary mt-0.5 shrink-0" />
                                      <div>
                                        <p className="text-xs font-bold text-slate-800 dark:text-slate-200 line-clamp-1">
                                          {place.display_name.split(',')[0]}
                                        </p>
                                        <p className="text-[10px] text-slate-400 line-clamp-1 mt-0.5">
                                          {place.display_name.split(',').slice(1).join(',').trim()}
                                        </p>
                                      </div>
                                    </button>
                                  ))}
                                </div>
                              )}
                            </div>
                            <p className="text-[9px] text-slate-400 mt-1.5">Powered by OpenStreetMap · Search fills all fields automatically</p>
                          </div>

                          {/* === LIVE MAP PREVIEW === */}
                          {mapEmbedUrl && (
                            <div className="rounded-2xl overflow-hidden border-2 border-primary/20 shadow-lg animate-slide-up">
                              <div className="bg-primary/5 px-4 py-2 flex items-center justify-between border-b border-primary/10">
                                <div className="flex items-center gap-2">
                                  <Navigation className="w-3.5 h-3.5 text-primary" />
                                  <span className="text-[9px] font-black text-primary uppercase tracking-widest">Location Preview</span>
                                </div>
                                <a
                                  href={`https://www.google.com/maps?q=${venueLat},${venueLng}`}
                                  target="_blank"
                                  rel="noreferrer"
                                  className="text-[9px] font-black text-primary uppercase tracking-wider hover:underline flex items-center gap-1"
                                >
                                  Open in Google Maps →
                                </a>
                              </div>
                              <iframe
                                title="Venue Map Preview"
                                src={mapEmbedUrl}
                                width="100%"
                                height="240"
                                style={{ border: 0 }}
                                loading="lazy"
                                className="block"
                              />
                            </div>
                          )}

                          {/* === VENUE FIELDS (auto-filled or manual) === */}
                          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div className="sm:col-span-2">
                              <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Venue Name</label>
                              <input 
                                type="text" 
                                required
                                value={venueName}
                                onChange={(e) => setVenueName(e.target.value)}
                                placeholder="e.g. Curation Labs Loft"
                                className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200" 
                              />
                            </div>
                            <div className="sm:col-span-2">
                              <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Street Address</label>
                              <input 
                                type="text" 
                                required
                                value={venueAddress}
                                onChange={(e) => setVenueAddress(e.target.value)}
                                placeholder="Street Address, Area"
                                className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200" 
                              />
                            </div>
                            <div>
                              <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">City</label>
                              <input 
                                type="text" 
                                required
                                value={venueCity}
                                onChange={(e) => setVenueCity(e.target.value)}
                                className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200" 
                              />
                            </div>
                            <div>
                              <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">State</label>
                              <input 
                                type="text" 
                                required
                                value={venueState}
                                onChange={(e) => setVenueState(e.target.value)}
                                className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200" 
                              />
                            </div>
                            <div>
                              <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Pincode</label>
                              <input 
                                type="text" 
                                required
                                value={venuePincode}
                                onChange={(e) => setVenuePincode(e.target.value)}
                                className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200" 
                              />
                            </div>
                            <div>
                              <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Country</label>
                              <input 
                                type="text" 
                                required
                                value={venueCountry}
                                onChange={(e) => setVenueCountry(e.target.value)}
                                className="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200" 
                              />
                            </div>

                            {/* Coordinates (auto-filled, read-only display) */}
                            {venueLat && venueLng && (
                              <div className="sm:col-span-2 flex items-center gap-3 px-4 py-3 bg-primary/5 border border-primary/15 rounded-xl">
                                <Navigation className="w-4 h-4 text-primary shrink-0" />
                                <div className="text-[10px] text-primary font-bold">
                                  <span className="font-black">GPS Locked:</span> {parseFloat(venueLat).toFixed(6)}, {parseFloat(venueLng).toFixed(6)}
                                  <span className="ml-2 text-primary/60 font-normal">· Navigation-ready coordinates saved</span>
                                </div>
                              </div>
                            )}
                          </div>
                        </div>
                      ) : (
                        <div>
                          <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Select Active Venue</label>
                          <select 
                            value={venueId}
                            onChange={(e) => setVenueId(e.target.value)}
                            required
                            className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-205 outline-none cursor-pointer"
                          >
                            <option value="">Select Venue...</option>
                            {venues.map((v) => (
                              <option key={v.id} value={v.id}>{v.name} ({v.city})</option>
                            ))}
                          </select>
                        </div>
                      )}
                    </div>
                  )}
                </div>

                <div className="flex justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                  <button 
                    onClick={() => setStep(1)}
                    className="px-8 py-3.5 border border-slate-200 rounded-full text-xs font-black uppercase tracking-widest text-slate-500 hover:border-slate-300 transition-all cursor-pointer bg-white"
                  >
                    &larr; Prev Step
                  </button>
                  <button 
                    onClick={handleNext}
                    className="btn-primary px-10 py-3.5 text-xs font-black uppercase tracking-widest cursor-pointer"
                  >
                    Next Step &rarr;
                  </button>
                </div>
              </div>
            )}

            {/* Step 3: Descriptions & Visual Assets */}
            {step === 3 && (
              <div className="space-y-6 animate-slide-up">
                <div className="space-y-4">
                  <div>
                    <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Short Summary Curation (Max 500 chars)</label>
                    <input 
                      type="text"
                      required
                      value={shortDescription}
                      onChange={(e) => setShortDescription(e.target.value)}
                      placeholder="Brief excerpt display for browser cards."
                      className="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 outline-none"
                    />
                  </div>

                  <div>
                    <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 font-bold">Detailed Narrative Description</label>
                    <textarea 
                      required
                      rows="6"
                      value={description}
                      onChange={(e) => setDescription(e.target.value)}
                      placeholder="Markdown and deep narrative content displaying on detail sheets."
                      className="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl text-xs text-slate-800 dark:text-slate-200 outline-none focus:border-primary"
                    ></textarea>
                  </div>

                  <div>
                    <label className="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 font-bold">Event Curation Banner</label>
                    <div className="border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-950/20">
                      {bannerPreview ? (
                        <div className="space-y-4 text-center w-full">
                          <img src={bannerPreview} alt="Upload preview" className="max-h-48 object-cover rounded-xl mx-auto shadow-sm" />
                          <button 
                            type="button" 
                            onClick={() => { setBannerFile(null); setBannerPreview(null); }}
                            className="text-xs text-rose-500 font-bold hover:underline cursor-pointer"
                          >
                            Remove visual asset
                          </button>
                        </div>
                      ) : (
                        <label className="cursor-pointer flex flex-col items-center gap-2.5">
                          <Upload className="w-10 h-10 text-slate-350 animate-pulse" />
                          <span className="text-xs font-bold text-primary uppercase tracking-wider">Select banner image</span>
                          <span className="text-[9px] text-slate-400">PNG, JPG up to 5MB size limit</span>
                          <input type="file" accept="image/*" onChange={handleFileChange} className="hidden" />
                        </label>
                      )}
                    </div>
                  </div>
                </div>

                <div className="flex justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                  <button 
                    onClick={() => setStep(2)}
                    className="px-8 py-3.5 border border-slate-200 rounded-full text-xs font-black uppercase tracking-widest text-slate-500 hover:border-slate-300 transition-all cursor-pointer bg-white"
                  >
                    &larr; Prev Step
                  </button>
                  <button 
                    onClick={handleSubmit}
                    disabled={saving}
                    className="px-10 py-3.5 bg-[#4E7D5B] text-white rounded-full text-xs font-black uppercase tracking-widest hover:bg-[#3D6449] transition-all shadow-lg shadow-[#4E7D5B]/20 cursor-pointer disabled:opacity-50"
                  >
                    {saving ? 'Saving Blueprint...' : (isEdit ? 'Update Event Blueprint' : 'Deploy Event Blueprint')}
                  </button>
                </div>
              </div>
            )}

          </div>
        )}

      </div>
    </SidebarLayout>
  );
}
