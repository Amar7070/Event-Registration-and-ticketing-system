import React from 'react';
import { Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import { 
  Heart, Layers, Zap, Shield, BarChart3, 
  Globe, Sparkles, Target, ArrowRight 
} from 'lucide-react';

export default function About() {
  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden selection:bg-[#4E7D5B] selection:text-white">
      <Navbar />

      {/* Cinematic Ambient Glow Orbs */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div className="absolute top-[5%] left-[10%] w-[600px] h-[600px] bg-[#4E7D5B]/10 dark:bg-[#4E7D5B]/15 rounded-full blur-[140px]"></div>
        <div className="absolute top-[30%] right-[5%] w-[500px] h-[500px] bg-indigo-500/10 dark:bg-indigo-500/10 rounded-full blur-[120px]"></div>
      </div>

      {/* Hero Section */}
      <section className="relative pt-40 pb-20 md:pt-52 md:pb-32 px-6 md:px-12 z-10 overflow-hidden text-center">
        <div className="max-w-5xl mx-auto">
           <div className="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-[#4E7D5B]/10 dark:bg-[#4E7D5B]/20 border border-[#4E7D5B]/20 w-fit mb-8 backdrop-blur-sm animate-fade-in">
              <span className="w-2 h-2 rounded-full bg-[#4E7D5B] animate-pulse shadow-[0_0_10px_rgba(78,125,91,0.8)]"></span>
              <span className="text-[10px] font-black uppercase tracking-[0.3em] text-[#4E7D5B] dark:text-[#8ac99d]">Our Story</span>
           </div>
           <h1 className="text-6xl md:text-8xl font-serif text-slate-900 dark:text-white leading-[1.05] tracking-tight mb-8 animate-slide-up">
              Redefining how the world <span className="text-transparent bg-clip-text bg-gradient-to-r from-[#4E7D5B] to-emerald-500 italic">gathers</span>.
           </h1>
           <p className="text-xl md:text-2xl text-slate-600 dark:text-slate-400 font-medium leading-relaxed max-w-3xl mx-auto animate-fade-in" style={{animationDelay: '200ms'}}>
             SmartEvent isn't just a ticketing platform. It's an ecosystem designed to elevate experiences, protect creators, and connect global communities without friction.
           </p>
        </div>
      </section>

      {/* Visual Break / Image Grid */}
      <section className="px-6 md:px-12 pb-32 max-w-[1440px] mx-auto z-10 relative">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 h-[400px] md:h-[600px]">
          <div className="md:col-span-2 relative rounded-[3rem] overflow-hidden group shadow-2xl">
            <div className="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent z-10"></div>
            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80" className="w-full h-full object-cover transition-transform duration-[4000ms] group-hover:scale-105" alt="Event crowd" />
            <div className="absolute bottom-10 left-10 z-20 transition-transform duration-500 group-hover:translate-y-[-10px]">
              <h3 className="text-white text-3xl md:text-4xl font-serif font-bold">Unforgettable Energy</h3>
            </div>
          </div>
          <div className="hidden md:flex flex-col gap-6">
            <div className="relative flex-1 rounded-[3rem] overflow-hidden group shadow-xl">
               <div className="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent z-10"></div>
               <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80" className="w-full h-full object-cover transition-transform duration-[4000ms] group-hover:scale-105" alt="Concert" />
            </div>
            <div className="relative flex-1 rounded-[3rem] overflow-hidden group shadow-xl">
               <div className="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent z-10"></div>
               <img src="https://images.unsplash.com/photo-1475721028313-0a6311667b93?auto=format&fit=crop&w=800&q=80" className="w-full h-full object-cover transition-transform duration-[4000ms] group-hover:scale-105" alt="Meeting" />
            </div>
          </div>
        </div>
      </section>

      {/* Core Values Bento Grid */}
      <section className="py-32 bg-slate-50 dark:bg-slate-900 border-y border-slate-200 dark:border-slate-800 relative z-10">
        <div className="max-w-[1440px] mx-auto px-6">
           <div className="text-center mb-20">
            <span className="text-indigo-500 text-[10px] font-black uppercase tracking-[0.3em] block mb-4">The DNA</span>
            <h2 className="text-4xl md:text-6xl font-serif font-bold text-slate-900 dark:text-white max-w-3xl mx-auto leading-[1.1]">Our Core Philosophy</h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 auto-rows-[350px]">
            {/* Value 1 */}
            <div className="bg-white dark:bg-slate-950 rounded-[3rem] p-10 md:p-12 border border-slate-100 dark:border-slate-800 shadow-xl flex flex-col justify-between group overflow-hidden relative">
              <div className="absolute right-0 top-0 opacity-5 dark:opacity-10 group-hover:scale-110 transition-transform duration-[1000ms] origin-top-right">
                <Target className="w-64 h-64 text-[#4E7D5B] -translate-y-1/4 translate-x-1/4" />
              </div>
              <div className="w-16 h-16 bg-[#4E7D5B]/10 rounded-2xl flex items-center justify-center mb-6 relative z-10 border border-[#4E7D5B]/20 backdrop-blur-sm">
                <Heart className="w-8 h-8 text-[#4E7D5B]" />
              </div>
              <div className="relative z-10">
                <h3 className="text-3xl font-serif font-bold text-slate-900 dark:text-white mb-4">Intentionality Over Scale</h3>
                <p className="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">We prioritize the depth and quality of the gathering over the sheer breadth of the audience. Every feature is designed to foster genuine human connection.</p>
              </div>
            </div>

            {/* Value 2 */}
            <div className="bg-gradient-to-br from-slate-900 to-slate-950 dark:from-indigo-950/40 dark:to-slate-950 rounded-[3rem] p-10 md:p-12 border border-slate-800 shadow-xl flex flex-col justify-between group overflow-hidden relative text-white">
              <div className="absolute right-0 bottom-0 opacity-10 group-hover:rotate-12 transition-transform duration-[2000ms]">
                <Shield className="w-64 h-64 text-indigo-500 translate-x-1/4 translate-y-1/4" />
              </div>
              <div className="w-16 h-16 bg-indigo-500/20 rounded-2xl flex items-center justify-center mb-6 relative z-10 border border-indigo-500/30 backdrop-blur-sm">
                <Shield className="w-8 h-8 text-indigo-400" />
              </div>
              <div className="relative z-10">
                <h3 className="text-3xl font-serif font-bold mb-4">Absolute Integrity</h3>
                <p className="text-slate-300 text-lg leading-relaxed">Our infrastructure protects creators. From cryptographic QR tickets to strict copyright enforcement, we ensure your brand and attendees are completely safe.</p>
              </div>
            </div>

            {/* Value 3 */}
            <div className="md:col-span-2 bg-gradient-to-br from-amber-50 to-rose-50 dark:from-slate-950 dark:to-slate-900 rounded-[3rem] p-10 md:p-12 border border-amber-100 dark:border-slate-800 shadow-xl flex flex-col justify-between group overflow-hidden relative">
              <div className="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80')] bg-cover bg-center opacity-10 mix-blend-overlay group-hover:scale-105 transition-transform duration-[3000ms]"></div>
              
              <div className="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-8 h-full">
                <div className="max-w-xl">
                  <div className="w-16 h-16 bg-amber-500/10 dark:bg-amber-500/20 rounded-2xl flex items-center justify-center mb-6 border border-amber-500/20 backdrop-blur-sm group-hover:scale-110 transition-transform">
                    <Sparkles className="w-8 h-8 text-amber-500" />
                  </div>
                  <h3 className="text-3xl md:text-4xl font-serif font-bold text-slate-900 dark:text-white mb-4">Frictionless Elegance</h3>
                  <p className="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">A clean, high-contrast visual system that gets out of the way. We believe that technology should breathe, allowing the physical connection to take center stage.</p>
                </div>
                <div className="shrink-0 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md p-6 rounded-3xl shadow-lg border border-white dark:border-slate-700">
                   <div className="flex -space-x-4">
                      {[1,2,3,4].map(i => (
                        <img key={i} src={`https://i.pravatar.cc/100?img=${i+10}`} className="w-12 h-12 rounded-full border-2 border-white dark:border-slate-800" alt="team" />
                      ))}
                      <div className="w-12 h-12 rounded-full bg-slate-900 text-white text-xs font-bold flex items-center justify-center border-2 border-white dark:border-slate-800">
                        +50
                      </div>
                   </div>
                   <p className="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest text-center mt-4">Built by a Global Team</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Dual CTA */}
      <section className="py-32 relative z-10 px-6">
        <div className="max-w-[1440px] mx-auto">
          <div className="bg-slate-950 rounded-[4rem] overflow-hidden shadow-2xl relative border border-slate-800 group">
            <div className="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&q=80&w=2000')] bg-cover bg-center opacity-30 mix-blend-luminosity group-hover:scale-105 transition-transform duration-[3000ms]"></div>
            <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-[#4E7D5B]/30"></div>
            
            <div className="relative p-16 md:p-24 flex flex-col md:flex-row items-center justify-between gap-16 text-center md:text-left">
              <div className="max-w-2xl">
                <span className="text-emerald-400 text-[10px] font-black uppercase tracking-[0.4em] block mb-6 shadow-sm">Join the Revolution</span>
                <h2 className="text-5xl md:text-7xl font-serif font-bold text-white mb-8 leading-[1.1]">Become a Gathering Architect.</h2>
                <p className="text-slate-300 text-xl font-medium leading-relaxed">Experience the highest standard of event management, waitlists, and secure ticketing.</p>
              </div>
              <div className="flex flex-col sm:flex-row gap-6 shrink-0 w-full md:w-auto">
                <Link to="/register?role=organizer" className="bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-10 py-5 rounded-full text-sm font-black uppercase tracking-widest transition-all hover:-translate-y-1 text-center shadow-[0_10px_30px_rgba(16,185,129,0.4)] flex items-center justify-center gap-3">
                  Start Building <ArrowRight className="w-4 h-4 stroke-[3]" />
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
  );
}
