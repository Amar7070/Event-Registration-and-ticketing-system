import React, { useState, useEffect } from 'react';
import { useSearchParams, Link } from 'react-router-dom';
import api from '../services/api';
import Navbar from '../components/Navbar';
import { MapPin, Calendar, Compass, Search, ChevronLeft, ChevronRight, Crosshair, Trash2, CalendarRange, Filter } from 'lucide-react';

export default function EventListing() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [events, setEvents] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [totalCount, setTotalCount] = useState(0);
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [firstItem, setFirstItem] = useState(0);
  const [lastItem, setLastItem] = useState(0);
  const [promotedEvents, setPromotedEvents] = useState([]);
  const [activeSlide, setActiveSlide] = useState(0);
  
  // Mobile filter toggle
  const [showFilters, setShowFilters] = useState(false);

  // Auto-slide for promoted events
  useEffect(() => {
    if (promotedEvents.length === 0) return;
    const interval = setInterval(() => {
      setActiveSlide((prev) => (prev + 1) % promotedEvents.length);
    }, 6000);
    return () => clearInterval(interval);
  }, [promotedEvents]);

  // Search/Filters inputs
  const [searchQuery, setSearchQuery] = useState(searchParams.get('search') || '');
  const [startDate, setStartDate] = useState(searchParams.get('start_date') || '');
  const [endDate, setEndDate] = useState(searchParams.get('end_date') || '');
  
  // Geolocation locking
  const [locationLocked, setLocationLocked] = useState(
    searchParams.has('latitude') && searchParams.has('longitude')
  );
  const [lat, setLat] = useState(searchParams.get('latitude') || '');
  const [lng, setLng] = useState(searchParams.get('longitude') || '');
  const [radius, setRadius] = useState(searchParams.get('radius') || '50');
  const [geoLoading, setGeoLoading] = useState(false);

  useEffect(() => {
    async function fetchCategories() {
      try {
        const response = await api.get('/categories');
        setCategories(response.data.categories || []);
      } catch (err) {
        console.error('Failed to load categories list', err);
      }
    }
    fetchCategories();
  }, []);

  useEffect(() => {
    async function fetchEvents() {
      setLoading(true);
      try {
        const params = {};
        if (searchParams.get('search')) params.search = searchParams.get('search');
        if (searchParams.get('category')) params.category = searchParams.get('category');
        if (searchParams.get('type')) params.type = searchParams.get('type');
        if (searchParams.get('latitude')) params.latitude = searchParams.get('latitude');
        if (searchParams.get('longitude')) params.longitude = searchParams.get('longitude');
        if (searchParams.get('radius')) params.radius = searchParams.get('radius');
        if (searchParams.get('start_date')) params.start_date = searchParams.get('start_date');
        if (searchParams.get('end_date')) params.end_date = searchParams.get('end_date');
        params.page = currentPage;

        const response = await api.get('/events', { params });
        const pagination = response.data.meta || {};
        
        setEvents(response.data.data || []);
        setTotalCount(pagination.total || response.data.data.length || 0);
        setCurrentPage(pagination.current_page || 1);
        setLastPage(pagination.last_page || 1);
        setFirstItem(pagination.from || 1);
        setLastItem(pagination.to || response.data.data.length || 0);
        setPromotedEvents(response.data.promoted || []);
      } catch (err) {
        console.error('Failed to load events list', err);
      } finally {
        setLoading(false);
      }
    }
    fetchEvents();
  }, [searchParams, currentPage]);

  const updateSearchParam = (key, value) => {
    const newParams = new URLSearchParams(searchParams);
    if (value) {
      newParams.set(key, value);
    } else {
      newParams.delete(key);
    }
    newParams.set('page', '1');
    setCurrentPage(1);
    setSearchParams(newParams);
  };

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    updateSearchParam('search', searchQuery.trim());
  };

  const handleDateFilterSubmit = (e) => {
    e.preventDefault();
    const newParams = new URLSearchParams(searchParams);
    if (startDate) newParams.set('start_date', startDate);
    else newParams.delete('start_date');
    
    if (endDate) newParams.set('end_date', endDate);
    else newParams.delete('end_date');
    
    newParams.set('page', '1');
    setCurrentPage(1);
    setSearchParams(newParams);
  };

  const clearDateFilter = () => {
    setStartDate('');
    setEndDate('');
    const newParams = new URLSearchParams(searchParams);
    newParams.delete('start_date');
    newParams.delete('end_date');
    newParams.set('page', '1');
    setCurrentPage(1);
    setSearchParams(newParams);
  };

  const handleFormatChange = (format) => updateSearchParam('type', format);
  const handleCategoryChange = (catSlug) => updateSearchParam('category', catSlug);

  const requestLocation = () => {
    if (locationLocked) return;
    setGeoLoading(true);

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const latitude = position.coords.latitude;
          const longitude = position.coords.longitude;
          setLat(latitude);
          setLng(longitude);
          setLocationLocked(true);
          setGeoLoading(false);

          const newParams = new URLSearchParams(searchParams);
          newParams.set('latitude', latitude.toString());
          newParams.set('longitude', longitude.toString());
          newParams.set('radius', radius);
          newParams.set('page', '1');
          setCurrentPage(1);
          setSearchParams(newParams);
        },
        (error) => {
          alert('GPS Lock failed: ' + error.message);
          setGeoLoading(false);
        }
      );
    } else {
      alert('Location lock not supported by browser.');
      setGeoLoading(false);
    }
  };

  const handleRadiusChange = (newRadius) => {
    setRadius(newRadius);
    if (locationLocked) updateSearchParam('radius', newRadius);
  };

  const resetRadar = () => {
    setLat('');
    setLng('');
    setLocationLocked(false);
    
    const newParams = new URLSearchParams(searchParams);
    newParams.delete('latitude');
    newParams.delete('longitude');
    newParams.delete('radius');
    newParams.delete('search');
    newParams.delete('start_date');
    newParams.delete('end_date');
    newParams.delete('category');
    newParams.delete('type');
    newParams.set('page', '1');
    
    setSearchQuery('');
    setStartDate('');
    setEndDate('');
    
    setCurrentPage(1);
    setSearchParams(newParams);
  };

  const selectedCategory = searchParams.get('category');
  const selectedFormat = searchParams.get('type');

  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-[#0B0F19] text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden selection:bg-[#4E7D5B] selection:text-white">
      <Navbar />

      {/* Cinematic Glowing Backdrop */}
      <div className="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div className="absolute top-[-10%] right-[-5%] w-[800px] h-[800px] bg-[#4E7D5B]/5 dark:bg-[#4E7D5B]/[0.02] rounded-full blur-[150px]"></div>
        <div className="absolute top-[30%] left-[-10%] w-[600px] h-[600px] bg-amber-500/5 dark:bg-amber-500/[0.02] rounded-full blur-[150px]"></div>
      </div>

      {/* Top Promoted Carousel (BookMyShow Style) */}
      {promotedEvents.length > 0 && (
        <section className="pt-28 pb-10 px-6 md:px-12 max-w-7xl mx-auto relative z-10">
          <div className="relative rounded-[2rem] overflow-hidden aspect-[21/10] md:aspect-[21/8] lg:aspect-[21/7] shadow-2xl border border-slate-100 dark:border-slate-800 bg-slate-900 group">
            
            {/* Slides Container */}
            <div className="w-full h-full relative">
              {promotedEvents.map((pe, index) => (
                <div 
                  key={pe.id}
                  className={`absolute inset-0 w-full h-full bg-cover bg-center transition-all duration-1000 ${
                    index === activeSlide ? 'opacity-100 scale-100' : 'opacity-0 scale-95 pointer-events-none'
                  }`}
                  style={{ backgroundImage: `url('${pe.banner}')` }}
                >
                  {/* Dark Gradient Overlay */}
                  <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-transparent"></div>
                  <div className="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-950/20 to-transparent"></div>

                  {/* Slide Contents */}
                  <div className="absolute bottom-0 left-0 w-full p-8 md:p-12 lg:p-16 flex flex-col justify-end h-full text-left space-y-4 max-w-3xl">
                    <div className="flex flex-wrap items-center gap-3">
                      <span className="px-3.5 py-1 rounded-full bg-[#4E7D5B] text-white text-[9px] font-black uppercase tracking-[0.2em] shadow-lg shadow-[#4E7D5B]/30">
                        Featured Gathering
                      </span>
                      <span className="px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-[0.2em] border border-white/10">
                        {pe.category}
                      </span>
                    </div>

                    <h2 className="text-2xl md:text-4xl lg:text-5xl font-serif text-white font-bold leading-tight tracking-tight line-clamp-2">
                      {pe.title}
                    </h2>

                    <div className="flex flex-wrap items-center gap-6 text-slate-300 text-xs md:text-sm">
                      <span className="flex items-center gap-1.5">
                        <Calendar className="w-4 h-4 text-[#4E7D5B]" />
                        {new Date(pe.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                      </span>
                      <span className="flex items-center gap-1.5">
                        <MapPin className="w-4 h-4 text-[#4E7D5B]" />
                        {pe.venue ? pe.venue.name : 'Global Portal'}
                      </span>
                    </div>

                    <div className="pt-3">
                      <Link to={`/events/${pe.slug}`} className="inline-flex items-center gap-2 bg-[#4E7D5B] hover:bg-[#3D6449] text-white px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest transition shadow-lg shadow-[#4E7D5B]/20">
                        Book Pass &rarr;
                      </Link>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {/* Left Arrow */}
            <button 
              onClick={() => setActiveSlide((prev) => (prev - 1 + promotedEvents.length) % promotedEvents.length)}
              className="absolute left-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-slate-950/40 hover:bg-[#4E7D5B] text-white backdrop-blur-md flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 z-20 border border-white/10 cursor-pointer"
            >
              <ChevronLeft className="w-5 h-5" />
            </button>

            {/* Right Arrow */}
            <button 
              onClick={() => setActiveSlide((prev) => (prev + 1) % promotedEvents.length)}
              className="absolute right-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-slate-950/40 hover:bg-[#4E7D5B] text-white backdrop-blur-md flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 z-20 border border-white/10 cursor-pointer"
            >
              <ChevronRight className="w-5 h-5" />
            </button>

            {/* Slide Indicators */}
            <div className="absolute bottom-8 right-8 flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/40 backdrop-blur-sm z-20 border border-white/5">
              {promotedEvents.map((_, index) => (
                <button 
                  key={index}
                  onClick={() => setActiveSlide(index)}
                  className={`transition-all duration-500 rounded-full cursor-pointer ${
                    activeSlide === index ? 'w-5 h-1.5 bg-[#4E7D5B]' : 'w-1.5 h-1.5 bg-white/40 hover:bg-white/85'
                  }`}
                ></button>
              ))}
            </div>
          </div>
        </section>
      )}

      <div className={`relative z-10 pb-20 max-w-[1400px] mx-auto px-6 md:px-10 lg:px-12 flex flex-col lg:flex-row gap-10 ${promotedEvents.length > 0 ? 'pt-4' : 'pt-28'}`}>
        
        {/* Mobile Filter Toggle */}
        <div className="lg:hidden flex items-center justify-between mb-4">
          <h1 className="text-3xl font-serif font-black tracking-tight text-slate-900 dark:text-white">Discover</h1>
          <button 
            onClick={() => setShowFilters(!showFilters)}
            className="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-900 rounded-full border border-slate-200 dark:border-slate-800 shadow-sm text-sm font-bold"
          >
            <Filter className="w-4 h-4" /> Filters
          </button>
        </div>

        {/* --- LEFT SIDEBAR (Filters) --- */}
        <aside className={`${showFilters ? 'block' : 'hidden'} lg:block w-full lg:w-[320px] shrink-0 space-y-6 lg:sticky lg:top-28 h-max animate-fade-in`}>
          
          <div className="hidden lg:block mb-8">
            <h1 className="text-4xl lg:text-5xl font-serif font-black tracking-tight text-slate-900 dark:text-white mb-2 leading-none">Discover<br/>Experiences</h1>
            <div className="h-1 w-12 bg-gradient-to-r from-[#4E7D5B] to-emerald-400 rounded-full mt-4"></div>
          </div>

          {/* Search Box */}
          <div className="bg-white/70 dark:bg-slate-900/40 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none space-y-4">
            <h3 className="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">Search Events</h3>
            <form onSubmit={handleSearchSubmit} className="relative">
              <input 
                type="text" 
                placeholder="Keywords, hosts, venues..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full bg-slate-100/50 dark:bg-slate-950/50 border border-slate-200/50 dark:border-slate-800 focus:border-[#4E7D5B] focus:ring-1 focus:ring-[#4E7D5B] text-sm font-medium text-slate-800 dark:text-slate-200 py-3.5 pl-4 pr-12 rounded-2xl outline-none transition-all placeholder:text-slate-400" 
              />
              <button type="submit" className="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-slate-200/50 dark:bg-slate-800 hover:bg-[#4E7D5B] hover:text-white text-slate-500 rounded-xl transition-colors cursor-pointer">
                <Search className="w-4 h-4" />
              </button>
            </form>
          </div>

          {/* Date Range Filter (NEW) */}
          <div className="bg-white/70 dark:bg-slate-900/40 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none space-y-4">
            <div className="flex items-center justify-between">
              <h3 className="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">Date Range</h3>
              <CalendarRange className="w-3.5 h-3.5 text-slate-400" />
            </div>
            
            <form onSubmit={handleDateFilterSubmit} className="space-y-3">
              <div className="grid grid-cols-2 gap-3">
                <div className="space-y-1">
                  <label className="text-[9px] font-bold text-slate-500 uppercase ml-1">From</label>
                  <input 
                    type="date"
                    value={startDate}
                    onChange={(e) => setStartDate(e.target.value)}
                    className="w-full bg-slate-100/50 dark:bg-slate-950/50 border border-slate-200/50 dark:border-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 p-2.5 rounded-xl outline-none focus:border-[#4E7D5B]"
                  />
                </div>
                <div className="space-y-1">
                  <label className="text-[9px] font-bold text-slate-500 uppercase ml-1">To</label>
                  <input 
                    type="date"
                    value={endDate}
                    onChange={(e) => setEndDate(e.target.value)}
                    className="w-full bg-slate-100/50 dark:bg-slate-950/50 border border-slate-200/50 dark:border-slate-800 text-xs font-medium text-slate-800 dark:text-slate-200 p-2.5 rounded-xl outline-none focus:border-[#4E7D5B]"
                  />
                </div>
              </div>
              
              <div className="flex gap-2 pt-1">
                <button type="submit" className="flex-1 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 dark:hover:bg-white transition-colors shadow-md">
                  Apply Dates
                </button>
                {(startDate || endDate) && (
                  <button type="button" onClick={clearDateFilter} className="px-3 bg-rose-50 dark:bg-rose-950/30 text-rose-600 rounded-xl hover:bg-rose-100 transition-colors">
                    <Trash2 className="w-4 h-4" />
                  </button>
                )}
              </div>
            </form>
          </div>

          {/* Categories */}
          <div className="bg-white/70 dark:bg-slate-900/40 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none space-y-4">
            <h3 className="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">Domains</h3>
            <div className="flex flex-col gap-1.5">
              <button 
                onClick={() => handleCategoryChange(null)}
                className={`flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all cursor-pointer text-left border ${
                  !selectedCategory 
                    ? 'bg-[#4E7D5B]/10 border-[#4E7D5B]/20 text-[#4E7D5B]' 
                    : 'border-transparent text-slate-500 hover:bg-slate-100/50 dark:hover:bg-slate-800/50'
                }`}
              >
                <span className="flex items-center gap-2">
                  <Compass className="w-4 h-4" />
                  All Domains
                </span>
              </button>

              {categories.map(cat => (
                <button 
                  key={cat.id}
                  onClick={() => handleCategoryChange(cat.slug)}
                  className={`flex items-center justify-between px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all cursor-pointer text-left border ${
                    selectedCategory === cat.slug 
                      ? 'bg-[#4E7D5B]/10 border-[#4E7D5B]/20 text-[#4E7D5B]' 
                      : 'border-transparent text-slate-500 hover:bg-slate-100/50 dark:hover:bg-slate-800/50'
                  }`}
                >
                  <span>{cat.name}</span>
                </button>
              ))}
            </div>
          </div>

          {/* Format */}
          <div className="bg-white/70 dark:bg-slate-900/40 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none space-y-4">
            <h3 className="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">Format</h3>
            <div className="flex bg-slate-100/50 dark:bg-slate-950 p-1 rounded-xl">
              <button 
                onClick={() => handleFormatChange(null)}
                className={`flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all ${
                  !selectedFormat ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'
                }`}
              >
                All
              </button>
              <button 
                onClick={() => handleFormatChange('physical')}
                className={`flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all ${
                  selectedFormat === 'physical' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'
                }`}
              >
                Physical
              </button>
              <button 
                onClick={() => handleFormatChange('online')}
                className={`flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all ${
                  selectedFormat === 'online' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'
                }`}
              >
                Online
              </button>
            </div>
          </div>

          {/* GPS Radar */}
          <div className="bg-white/70 dark:bg-slate-900/40 backdrop-blur-xl border border-white/20 dark:border-slate-800 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none space-y-4 relative overflow-hidden group">
            {locationLocked && (
              <div className="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl animate-pulse"></div>
            )}
            
            <div className="flex items-center justify-between relative z-10">
              <h3 className="text-[10px] font-black uppercase tracking-[0.15em] text-slate-400">Nearby Radar</h3>
              {locationLocked && <span className="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)] animate-pulse"></span>}
            </div>

            {locationLocked && (
              <div className="relative z-10">
                <div className="flex items-center justify-between mb-2">
                  <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Radius</span>
                  <span className="text-xs font-black text-[#4E7D5B]">{radius} KM</span>
                </div>
                <input 
                  type="range" 
                  min="5" 
                  max="1000" 
                  step="5" 
                  value={radius} 
                  onChange={(e) => handleRadiusChange(e.target.value)}
                  className="w-full h-1.5 bg-slate-200 dark:bg-slate-800 rounded-full appearance-none cursor-pointer accent-[#4E7D5B]" 
                />
              </div>
            )}

            <div className="flex gap-2 relative z-10">
              <button 
                onClick={requestLocation}
                className="flex-1 px-4 py-3 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all hover:scale-[1.02] flex items-center justify-center gap-2 cursor-pointer shadow-md"
              >
                {geoLoading ? (
                  <span className="flex items-center gap-2"><div className="w-3 h-3 border-2 border-white/30 border-t-white rounded-full animate-spin"></div> LOCKING...</span>
                ) : (
                  <>
                    <Crosshair className="w-3.5 h-3.5" />
                    <span>{locationLocked ? 'LOCKED' : 'GPS LOCK'}</span>
                  </>
                )}
              </button>

              {locationLocked && (
                <button 
                  onClick={resetRadar}
                  className="w-10 h-10 bg-rose-50 dark:bg-rose-950/30 text-rose-600 border border-rose-100 dark:border-rose-900/50 rounded-xl flex items-center justify-center cursor-pointer hover:bg-rose-100 transition-colors shrink-0"
                >
                  <Trash2 className="w-4 h-4" />
                </button>
              )}
            </div>
          </div>

          <button 
            onClick={resetRadar}
            className="w-full py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors"
          >
            Clear All Filters
          </button>

        </aside>

        {/* --- RIGHT CONTENT (Events) --- */}
        <main className="flex-1 space-y-8 animate-slide-up" style={{ animationDelay: '0.1s' }}>
          
          <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
              <h2 className="text-xl lg:text-2xl font-serif font-bold text-slate-900 dark:text-white leading-tight">
                {selectedCategory ? (
                  <span><span className="text-[#4E7D5B] capitalize">{selectedCategory}</span> Experiences</span>
                ) : searchQuery ? (
                  <span>Search: <span className="text-[#4E7D5B] italic font-serif">"{searchQuery}"</span></span>
                ) : (
                  <span>All Experiences</span>
                )}
              </h2>
              <p className="text-xs text-slate-500 mt-1 font-medium">
                Displaying {firstItem}-{lastItem} of {totalCount} results
              </p>
            </div>
            
            <div className="flex items-center gap-2 text-xs font-bold text-slate-400">
              {startDate && endDate && (
                <span className="px-3 py-1.5 bg-[#4E7D5B]/10 text-[#4E7D5B] rounded-full text-[10px] uppercase tracking-widest border border-[#4E7D5B]/20">
                  {new Date(startDate).toLocaleDateString(undefined, {month:'short', day:'numeric'})} - {new Date(endDate).toLocaleDateString(undefined, {month:'short', day:'numeric'})}
                </span>
              )}
            </div>
          </div>

          {loading ? (
            <div className="py-32 flex flex-col items-center justify-center">
              <div className="w-12 h-12 border-4 border-[#4E7D5B] border-t-transparent rounded-full animate-spin mb-6 shadow-lg shadow-[#4E7D5B]/20"></div>
              <p className="text-xs font-black uppercase tracking-[0.2em] text-slate-400 animate-pulse">Scanning Ecosystem...</p>
            </div>
          ) : events.length > 0 ? (
            <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
              {events.map((evt, i) => {
                const spotsLeft = evt.stats.available;
                let statusText = 'EXCLUSIVE PASS';
                let statusColor = 'bg-slate-900/90 text-white backdrop-blur-md border border-white/10';
                if (spotsLeft <= 0) {
                  statusText = 'FULLY RESERVED';
                  statusColor = 'bg-rose-500/90 text-white backdrop-blur-md border border-rose-400/20';
                } else if (spotsLeft < 15) {
                  statusText = 'LIMITED NODES';
                  statusColor = 'bg-amber-500/90 text-white backdrop-blur-md border border-amber-400/20';
                }

                return (
                  <article 
                    key={evt.id} 
                    className="group relative bg-white dark:bg-slate-900 rounded-[2rem] overflow-hidden border border-slate-100 dark:border-slate-800 shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-none hover:shadow-2xl hover:shadow-[#4E7D5B]/10 transition-all duration-500 hover:-translate-y-1 flex flex-col"
                    style={{ animationDelay: `${i * 0.05}s` }}
                  >
                    <Link to={`/events/${evt.slug}`} className="flex flex-col h-full">
                      {/* Image Banner */}
                      <div className="relative aspect-[4/3] overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img 
                          src={evt.banner} 
                          className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" 
                          alt={evt.title} 
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-500"></div>

                        {/* Date Badge */}
                        <div className="absolute top-4 left-4 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl rounded-2xl p-2.5 text-center min-w-[54px] shadow-xl border border-white/20">
                          <span className="block text-[9px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-0.5 leading-none">
                            {new Date(evt.start_date).toLocaleDateString('en-US', { month: 'short' }).toUpperCase()}
                          </span>
                          <span className="block text-xl font-serif text-slate-900 dark:text-white leading-none font-bold">
                            {new Date(evt.start_date).getDate()}
                          </span>
                        </div>

                        {/* Status Badge */}
                        <div className="absolute top-4 right-4">
                          <span className={`px-3 py-1.5 rounded-xl text-[8px] font-black tracking-[0.15em] ${statusColor} shadow-lg flex items-center gap-1`}>
                            {spotsLeft <= 0 && <span className="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>}
                            {statusText}
                          </span>
                        </div>
                      </div>

                      {/* Card Content */}
                      <div className="p-6 flex flex-col flex-1 bg-white dark:bg-slate-900">
                        <div className="flex items-center justify-between mb-3">
                          <span className="text-[9px] font-black uppercase tracking-[0.15em] text-[#4E7D5B]">
                            {evt.category}
                          </span>
                          <span className="text-xs font-bold text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-lg">
                            {evt.price_range.min === 0 ? 'FREE' : `₹${evt.price_range.min.toLocaleString()}`}
                          </span>
                        </div>
                        
                        <h4 className="text-lg font-serif text-slate-900 dark:text-white font-bold leading-snug group-hover:text-[#4E7D5B] transition-colors line-clamp-2 mb-3">
                          {evt.title}
                        </h4>
                        
                        <p className="text-slate-500 dark:text-slate-400 text-xs leading-relaxed line-clamp-2 mb-6">
                          {evt.short_description}
                        </p>

                        <div className="mt-auto pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                          <div className="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            <MapPin className="w-3.5 h-3.5 text-[#4E7D5B]" />
                            {evt.venue ? evt.venue.city : 'Virtual'}
                          </div>
                          
                          <div className="w-8 h-8 rounded-full bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B] group-hover:bg-[#4E7D5B] group-hover:text-white transition-colors">
                            <ChevronRight className="w-4 h-4 group-hover:translate-x-0.5 transition-transform" />
                          </div>
                        </div>
                      </div>
                    </Link>
                  </article>
                );
              })}
            </div>
          ) : (
            <div className="py-24 text-center bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 border-dashed rounded-[3rem] shadow-sm max-w-2xl mx-auto">
              <div className="w-16 h-16 bg-slate-50 dark:bg-slate-950 rounded-2xl flex items-center justify-center mx-auto mb-6 text-slate-300">
                <Compass className="w-8 h-8" />
              </div>
              <h3 className="text-2xl font-serif text-slate-900 dark:text-white mb-2 font-bold tracking-tight">Ecosystem Empty</h3>
              <p className="text-sm text-slate-500 max-w-sm mx-auto mb-8 leading-relaxed">
                We couldn't locate any gatherings matching your specific domain, dates, or radar coordinates.
              </p>
              <button 
                onClick={resetRadar}
                className="px-8 py-3.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-xs font-black uppercase tracking-[0.15em] hover:bg-slate-800 dark:hover:bg-slate-200 transition-all shadow-xl shadow-slate-900/10 cursor-pointer"
              >
                Reset All Filters
              </button>
            </div>
          )}

          {/* Pagination */}
          {lastPage > 1 && (
            <div className="pt-12 pb-8 flex justify-center items-center gap-3">
              <button 
                onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                disabled={currentPage === 1}
                className="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-500 disabled:opacity-30 hover:border-[#4E7D5B] hover:text-[#4E7D5B] transition-colors cursor-pointer"
              >
                <ChevronLeft className="w-5 h-5" />
              </button>
              
              <div className="px-6 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl">
                <span className="text-xs font-black text-slate-500 uppercase tracking-[0.15em]">
                  Page <span className="text-slate-900 dark:text-white">{currentPage}</span> of {lastPage}
                </span>
              </div>
              
              <button 
                onClick={() => setCurrentPage(prev => Math.min(prev + 1, lastPage))}
                disabled={currentPage === lastPage}
                className="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-500 disabled:opacity-30 hover:border-[#4E7D5B] hover:text-[#4E7D5B] transition-colors cursor-pointer"
              >
                <ChevronRight className="w-5 h-5" />
              </button>
            </div>
          )}

        </main>
      </div>
    </div>
  );
}
