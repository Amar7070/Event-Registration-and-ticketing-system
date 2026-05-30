import React from 'react';
import Navbar from '../components/Navbar';
import { ArrowRight, BookOpen, Clock } from 'lucide-react';

export default function Blog() {
  const articles = [
    {
      category: 'Planning',
      title: 'How to plan a memorable event in 2026',
      description: 'A complete guide for organizers on selecting venues, ticketing strategies, and creating unforgettable experiences.',
      image: 'https://images.unsplash.com/photo-1485217988980-11786ced9454?auto=format&fit=crop&q=80&w=1200',
      readTime: '5 min read'
    },
    {
      category: 'Tips',
      title: 'Boost attendance with better invite flow',
      description: 'Small improvements in your event listing and checkout experience can drive far better ticket conversions.',
      image: 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&q=80&w=1200',
      readTime: '3 min read'
    },
    {
      category: 'Product',
      title: 'New tools for organizers to sell faster',
      description: 'From dynamic pricing to waitlist automation, learn how Evenzo is helping organizers run events with confidence.',
      image: 'https://images.unsplash.com/photo-1515169067868-5387ec356754?auto=format&fit=crop&q=80&w=1200',
      readTime: '4 min read'
    }
  ];

  const handleSubscribe = (e) => {
    e.preventDefault();
    alert('Thank you for subscribing! You will receive our insights shortly.');
  };

  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden">
      <Navbar />

      {/* Hero Header */}
      <section className="pt-48 pb-24 px-6 md:px-12 bg-slate-950 text-white relative overflow-hidden">
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(78,125,91,0.25),_transparent_40%),radial-gradient(circle_at_bottom_right,_rgba(78,125,91,0.15),_transparent_35%)]"></div>
        <div className="relative max-w-7xl mx-auto text-left">
          <p className="text-[10px] font-black uppercase tracking-[0.35em] text-[#4E7D5B] mb-4">INSIGHT & STORIES</p>
          <h1 className="text-5xl lg:text-7xl font-serif tracking-tight mb-6 max-w-3xl leading-[1.1] font-bold">Latest news from the world of events.</h1>
          <p className="text-lg text-slate-400 leading-relaxed max-w-2xl font-serif italic">Discover product updates, event planning tips and organizer success stories designed to help your next event reach more people.</p>
        </div>
      </section>

      {/* Articles Grid */}
      <section className="max-w-7xl mx-auto px-6 md:px-12 py-24">
        <div className="grid gap-12 md:grid-cols-2 lg:grid-cols-3 text-left">
          {articles.map((article, idx) => (
            <article key={idx} className="group overflow-hidden rounded-[2.5rem] border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col justify-between">
              <div>
                <div className="h-60 overflow-hidden relative">
                  <img src={article.image} alt={article.title} className="h-full w-full object-cover transition duration-1000 group-hover:scale-105" />
                  <div className="absolute inset-0 bg-primary/5 mix-blend-multiply"></div>
                </div>
                <div className="p-8">
                  <div className="flex items-center justify-between mb-4">
                    <span className="text-[10px] font-black uppercase tracking-[0.25em] text-[#4E7D5B]">{article.category}</span>
                    <span className="text-[9px] text-slate-400 font-mono flex items-center gap-1"><Clock className="w-3 h-3" /> {article.readTime}</span>
                  </div>
                  <h2 className="text-xl font-serif text-slate-900 dark:text-white mb-4 leading-tight font-bold group-hover:text-primary transition-colors">{article.title}</h2>
                  <p className="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-6">{article.description}</p>
                </div>
              </div>
              <div className="px-8 pb-8">
                <a href="#" className="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-[#4E7D5B] hover:text-[#3D6449] transition-colors">
                  <span>Read Article</span>
                  <ArrowRight className="w-4 h-4 group-hover:translate-x-1.5 transition-transform" />
                </a>
              </div>
            </article>
          ))}
        </div>

        {/* Subscribe Banner */}
        <div className="mt-24 rounded-[3.5rem] bg-[#4E7D5B] px-8 py-16 text-white shadow-2xl shadow-[#4E7D5B]/20 relative overflow-hidden text-left">
          <div className="absolute inset-0 opacity-10 pointer-events-none">
            <div className="absolute top-0 left-0 w-full h-full" style={{ backgroundImage: 'radial-gradient(circle, #fff 1px, transparent 1px)', backgroundSize: '40px 40px' }}></div>
          </div>
          <div className="relative z-10 lg:flex lg:items-center lg:justify-between gap-12">
            <div className="max-w-2xl">
              <span className="text-[9px] font-black uppercase tracking-[0.35em] text-[#DDE7CB] mb-3 block">STAY CURRENT</span>
              <h2 className="text-3xl lg:text-4xl font-serif mb-4 leading-tight font-bold">Subscribe for event insights every week.</h2>
              <p className="text-slate-100 font-serif italic text-sm">Get the latest event marketing tips, product announcements, and success stories delivered directly to your inbox.</p>
            </div>
            <form onSubmit={handleSubscribe} className="mt-8 lg:mt-0 flex flex-col sm:flex-row gap-3 w-full max-w-xl">
              <input 
                type="email" 
                required 
                placeholder="Your email address" 
                className="flex-1 rounded-full border border-white/20 bg-white/10 px-6 py-4 text-xs text-white placeholder:text-slate-205 focus:outline-none focus:ring-2 focus:ring-white/40" 
              />
              <button 
                type="submit" 
                className="rounded-full bg-white px-8 py-4 text-[10px] font-black uppercase tracking-widest text-[#4E7D5B] hover:bg-slate-50 active:scale-95 transition-all cursor-pointer shadow-lg"
              >
                Subscribe
              </button>
            </form>
          </div>
        </div>
      </section>
    </div>
  );
}
