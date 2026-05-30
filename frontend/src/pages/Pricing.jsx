import React from 'react';
import { Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import { CheckCircle } from 'lucide-react';

export default function Pricing() {
  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden">
      <Navbar />

      {/* Hero Section */}
      <section className="pt-48 pb-32 px-6 md:px-12 bg-[#FAF9F5] dark:bg-slate-900/50 overflow-hidden relative border-b border-slate-100 dark:border-slate-800 text-center">
        <div className="absolute inset-0 opacity-10 pointer-events-none">
          <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[120px]"></div>
        </div>
        
        <div className="max-w-7xl mx-auto relative z-10">
          <div className="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-[#4E7D5B]/5 border border-[#4E7D5B]/10 mb-10">
            <span className="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
            <span className="text-[10px] font-black uppercase tracking-[0.3em] text-[#4E7D5B]">ECOSYSTEM ACCESS</span>
          </div>
          <h1 className="text-6xl md:text-7xl font-serif tracking-tight text-slate-900 dark:text-white leading-[1.05] mb-12 max-w-4xl mx-auto font-bold font-serif">
            Transparent <span className="italic text-[#4E7D5B] font-serif font-normal">yields</span> for every architect.
          </h1>
          <p className="text-xl md:text-2xl text-slate-505 dark:text-slate-400 max-w-3xl mx-auto font-serif italic leading-relaxed">
            "No hidden roots. Just simple architectures to help your community flourish."
          </p>
        </div>
      </section>

      {/* Pricing Grid */}
      <section className="py-32 px-6 md:px-12 bg-white dark:bg-slate-950 relative">
        <div className="max-w-7xl mx-auto">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-12 items-stretch text-left">
            
            {/* Free Tier */}
            <div className="premium-card p-12 bg-[#FDFBF7] dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[3rem] hover:shadow-2xl hover:shadow-[#4E7D5B]/5 transition-all duration-700 flex flex-col justify-between">
              <div className="mb-10">
                <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 block">THE SEED</span>
                <h3 className="text-3xl font-serif text-slate-900 dark:text-white mb-2 font-bold">Essential</h3>
                <div className="text-5xl font-serif text-primary font-bold mb-4">Free</div>
                <p className="text-slate-500 dark:text-slate-400 font-serif italic text-sm">Perfect for small circles and grassroots gatherings.</p>
              </div>
              
              <ul className="space-y-6 mb-12 flex-1">
                <li className="flex items-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  Up to 100 resident nodes
                </li>
                <li class="flex items-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  Basic Experience Blueprint
                </li>
                <li class="flex items-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  QR Identity Scanning
                </li>
                <li class="flex items-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  72h Financial Yielding
                </li>
              </ul>

              <Link to="/register" className="w-full text-center py-5 text-[10px] font-black uppercase tracking-[0.3em] rounded-full border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all block">
                PLANT FOR FREE
              </Link>
            </div>

            {/* Pro Tier (Highlighted) */}
            <div className="premium-card p-12 bg-slate-900 border border-primary/20 rounded-[3rem] shadow-3xl shadow-primary/10 relative overflow-hidden flex flex-col justify-between scale-105 z-10 text-white">
              <div className="absolute top-0 right-0 p-8">
                <div className="status-pill bg-primary text-white border-none tracking-widest text-[9px] animate-pulse">MOST RESONANT</div>
              </div>
              <div className="mb-10 relative z-10">
                <span className="text-[10px] font-black text-primary uppercase tracking-widest mb-4 block">THE FOREST</span>
                <h3 className="text-3xl font-serif text-white mb-2 font-bold">Professional</h3>
                <div className="flex items-baseline gap-2">
                  <span className="text-5xl font-serif text-white font-bold">₹2,999</span>
                  <span className="text-slate-400 font-serif italic text-lg">/month</span>
                </div>
                <p className="text-slate-400 font-serif italic text-sm mt-4">Deep insights and unlimited architectures for scaling communities.</p>
              </div>
              
              <ul className="space-y-6 mb-12 flex-1 relative z-10">
                <li className="flex items-center gap-4 text-sm font-medium text-white/85">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  Unlimited resident nodes
                </li>
                <li className="flex items-center gap-4 text-sm font-medium text-white/85">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  Premium Blueprint Layouts
                </li>
                <li className="flex items-center gap-4 text-sm font-medium text-white/85">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  Full Resonance Analytics
                </li>
                <li className="flex items-center gap-4 text-sm font-medium text-white/85">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  Priority Financial Yielding
                </li>
                <li className="flex items-center gap-4 text-sm font-medium text-white/85">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  White-label Digital Passports
                </li>
              </ul>

              <Link to="/register" className="btn-primary w-full py-5 text-[10px] font-black uppercase tracking-[0.4em] rounded-full shadow-2xl shadow-primary/20 block">
                START SCALE RITUAL
              </Link>
            </div>

            {/* Enterprise Tier */}
            <div className="premium-card p-12 bg-[#FDFBF7] dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[3rem] hover:shadow-2xl hover:shadow-[#4E7D5B]/5 transition-all duration-700 flex flex-col justify-between">
              <div className="mb-10">
                <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 block">THE SYSTEM</span>
                <h3 className="text-3xl font-serif text-slate-900 dark:text-white mb-2 font-bold">Ecosystem</h3>
                <div className="text-5xl font-serif text-slate-900 dark:text-white font-bold tracking-tighter">Custom</div>
                <p className="text-slate-500 dark:text-slate-400 font-serif italic text-sm mt-4">Bespoke architectures for large-scale enterprise gatherings.</p>
              </div>
              
              <ul className="space-y-6 mb-12 flex-1">
                <li className="flex items-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  Dedicated Node Management
                </li>
                <li className="flex items-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  SLA & Uptime Guarantees
                </li>
                <li className="flex items-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  Advanced Security Protocols
                </li>
                <li className="flex items-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                  <CheckCircle className="w-5 h-5 text-primary shrink-0" />
                  Custom Integration Nodes
                </li>
              </ul>

              <Link to="/contact" className="w-full text-center py-5 text-[10px] font-black uppercase tracking-[0.3em] rounded-full border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all block">
                CONTACT THE NODE
              </Link>
            </div>

          </div>
        </div>
      </section>

      {/* FAQ Section */}
      <section className="py-32 px-6 md:px-12 bg-[#FAF9F5] dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-800">
        <div className="max-w-4xl mx-auto">
          <div className="text-center mb-20">
            <span className="text-[#4E7D5B] text-[10px] font-black uppercase tracking-[0.4em] mb-4 block">TRANSPARENCY REPORT</span>
            <h2 className="text-4xl font-serif text-slate-900 dark:text-white font-bold font-serif">Common Queries</h2>
          </div>
          
          <div className="space-y-8 text-left">
            <div className="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem]">
              <h4 className="text-xl font-serif text-slate-900 dark:text-white mb-4 font-bold">How do transaction fees work?</h4>
              <p className="text-slate-500 dark:text-slate-400 text-base font-serif italic leading-relaxed">
                Every paid architecture incurs a small ecosystem fee of 2.5% + ₹10 per ticket. This yield is used to maintain the integrity of our digital soil and infrastructure.
              </p>
            </div>
            <div className="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem]">
              <h4 className="text-xl font-serif text-slate-900 dark:text-white mb-4 font-bold">Can I switch archetypes later?</h4>
              <p className="text-slate-500 dark:text-slate-400 text-base font-serif italic leading-relaxed">
                Absolutely. You can upgrade your forest's scale at any point during your gathering cycle. Downgrades take effect at the end of the current temporal period.
              </p>
            </div>
            <div className="premium-card p-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem]">
              <h4 className="text-xl font-serif text-slate-900 dark:text-white mb-4 font-bold">Is there a limit on free gatherings?</h4>
              <p className="text-slate-500 dark:text-slate-400 text-base font-serif italic leading-relaxed">
                We encourage organic growth. You can host as many free events as you need, provided each remains under the 100-node capacity limit.
              </p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
