import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import { Search, Ticket, Layout, ShieldCheck, ChevronRight, ChevronDown } from 'lucide-react';

export default function Help() {
  const [search, setSearch] = useState('');
  const [openFaq, setOpenFaq] = useState(null);

  const faqs = [
    {
      category: 'Resident',
      question: 'How do I book tickets for an event?',
      answer: 'Browse events on our listings page, select your preferred experience, choose your ticket tier and quantity, and click "Reserve Tickets". Complete the checkout form and simulated payment process to secure your tickets.'
    },
    {
      category: 'Resident',
      question: 'What is the ticket transfer protocol?',
      answer: 'Registered attendees can transfer verified tickets to another ecosystem user. Under "My Tickets", click the ticket details sheet, choose "Transfer Ticket", and enter the recipient user email.'
    },
    {
      category: 'Architect',
      question: 'How do organizers create event blueprints?',
      answer: 'Register or apply for an organizer account. Once approved, navigate to the Organizer Console, choose "Deploy Blueprint", configure the multi-step event metadata, select your venue type, upload visual banner assets, and submit the draft.'
    },
    {
      category: 'Architect',
      question: 'How are advertising showcase promotions approved?',
      answer: 'Organizers can purchase Spotlight slideshow packages to feature their upcoming experiences on the platform homepage hero carousel. Admins review and approve these campaign requests under the Showcase Requests Queue.'
    },
    {
      category: 'Security',
      question: 'What is the ecosystem governance policy?',
      answer: 'Our platform maintains strict governance protocols. Admins actively moderate all user reviews, and suspend violating event nodes or organizer licenses to protect ecosystem integrity.'
    }
  ];

  const filteredFaqs = faqs.filter(faq => 
    faq.question.toLowerCase().includes(search.toLowerCase()) ||
    faq.answer.toLowerCase().includes(search.toLowerCase()) ||
    faq.category.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden">
      <Navbar />

      {/* Hero Header */}
      <section className="pt-48 pb-32 px-6 md:px-12 bg-[#FAF9F5] dark:bg-slate-900/50 overflow-hidden relative border-b border-slate-100 dark:border-slate-800 text-center">
        <div className="absolute inset-0 opacity-10 pointer-events-none">
          <div className="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        </div>
        
        <div className="max-w-7xl mx-auto relative z-10">
          <div className="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-[#4E7D5B]/5 border border-[#4E7D5B]/10 mb-10">
            <span className="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
            <span className="text-[10px] font-black uppercase tracking-[0.3em] text-[#4E7D5B]">OPERATIONAL GUIDANCE</span>
          </div>
          <h1 className="text-6xl md:text-7xl font-serif tracking-tight text-slate-900 dark:text-white leading-[1.05] mb-12 max-w-4xl mx-auto font-bold">
            How can we <span className="italic text-[#4E7D5B] font-serif font-normal">support</span> your journey?
          </h1>
          <p className="text-xl md:text-2xl text-slate-505 dark:text-slate-400 max-w-3xl mx-auto font-serif italic leading-relaxed mb-16">
            "Find clarity within the ecosystem. We've architected these guides to ensure your experience remains frictionless."
          </p>
          
          {/* Search Bar */}
          <div className="max-w-2xl mx-auto relative group text-left">
            <div className="absolute inset-y-0 left-6 flex items-center text-slate-400 group-focus-within:text-primary transition-colors">
              <Search className="w-6 h-6" />
            </div>
            <input 
              type="text" 
              placeholder="Search guidance nodes, protocols, or support topics..." 
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full pl-16 pr-8 py-6 rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xl focus:ring-4 focus:ring-[#4E7D5B]/10 focus:border-[#4E7D5B]/20 transition-all outline-none text-lg font-serif italic"
            />
          </div>
        </div>
      </section>

      {/* Support Grid & FAQ List */}
      <section className="py-24 px-6 md:px-12 bg-white dark:bg-slate-950">
        <div className="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
          
          {/* Left: Support Pillars Cards */}
          <div className="lg:col-span-4 space-y-8 text-left">
            <div className="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[3rem] shadow-sm flex flex-col group">
              <div className="w-14 h-14 bg-[#4E7D5B]/5 dark:bg-slate-950 rounded-2xl flex items-center justify-center text-[#4E7D5B] mb-8 group-hover:scale-110 group-hover:bg-[#4E7D5B] group-hover:text-white transition-all duration-500">
                <Ticket className="w-7 h-7" />
              </div>
              <h3 className="text-xl font-serif text-slate-900 dark:text-white mb-6 font-bold">Resident <span className="italic text-[#4E7D5B] font-serif font-normal">Assistance</span></h3>
              <ul className="space-y-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                <li className="flex justify-between items-center hover:text-primary cursor-pointer transition-colors">Booking Protocols <ChevronRight className="w-4 h-4" /></li>
                <li className="flex justify-between items-center hover:text-primary cursor-pointer transition-colors">Payment Sandbox <ChevronRight className="w-4 h-4" /></li>
                <li className="flex justify-between items-center hover:text-primary cursor-pointer transition-colors">Waitlist Queues <ChevronRight className="w-4 h-4" /></li>
              </ul>
            </div>

            <div className="premium-card p-10 bg-slate-900 dark:bg-slate-900 border border-white/5 text-white rounded-[3rem] shadow-md flex flex-col group relative overflow-hidden">
              <div className="absolute top-0 right-0 w-32 h-32 bg-[#4E7D5B]/10 rounded-full blur-3xl"></div>
              <div className="relative z-10">
                <div className="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center text-[#4E7D5B] mb-8 group-hover:scale-110 group-hover:bg-[#4E7D5B] group-hover:text-white transition-all duration-500 border border-white/5">
                  <Layout className="w-7 h-7" />
                </div>
                <h3 className="text-xl font-serif text-white mb-6 font-bold">Architect <span className="italic text-[#4E7D5B] font-serif font-normal">Guidance</span></h3>
                <ul className="space-y-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                  <li className="flex justify-between items-center hover:text-white cursor-pointer transition-colors">Blueprint Deployments <ChevronRight className="w-4 h-4" /></li>
                  <li className="flex justify-between items-center hover:text-white cursor-pointer transition-colors">Host Approval Audits <ChevronRight className="w-4 h-4" /></li>
                  <li className="flex justify-between items-center hover:text-white cursor-pointer transition-colors">Financial Yields <ChevronRight className="w-4 h-4" /></li>
                </ul>
              </div>
            </div>

            <div className="premium-card p-10 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[3rem] shadow-sm flex flex-col group">
              <div className="w-14 h-14 bg-[#4E7D5B]/5 dark:bg-slate-950 rounded-2xl flex items-center justify-center text-[#4E7D5B] mb-8 group-hover:scale-110 group-hover:bg-[#4E7D5B] group-hover:text-white transition-all duration-500">
                <ShieldCheck className="w-7 h-7" />
              </div>
              <h3 className="text-xl font-serif text-slate-900 dark:text-white mb-6 font-bold">Security <span className="italic text-[#4E7D5B] font-serif font-normal">Protocols</span></h3>
              <ul className="space-y-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                <li className="flex justify-between items-center hover:text-primary cursor-pointer transition-colors">Active Sessions <ChevronRight className="w-4 h-4" /></li>
                <li className="flex justify-between items-center hover:text-primary cursor-pointer transition-colors">Two-Factor OTP <ChevronRight className="w-4 h-4" /></li>
              </ul>
            </div>
          </div>

          {/* Right: Expandable FAQ registry */}
          <div className="lg:col-span-8 space-y-6 text-left">
            <h3 className="text-2xl font-serif text-slate-900 dark:text-white font-bold mb-8">Guidance Registry Nodes</h3>
            <div className="space-y-4">
              {filteredFaqs.length > 0 ? (
                filteredFaqs.map((faq, idx) => {
                  const isOpen = openFaq === idx;
                  return (
                    <div key={idx} className="bg-[#FAF9F5] dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden transition-all duration-300">
                      <button 
                        onClick={() => setOpenFaq(isOpen ? null : idx)}
                        className="w-full px-8 py-6 flex items-center justify-between text-left focus:outline-none cursor-pointer"
                      >
                        <div>
                          <span className="text-[8px] font-black text-primary bg-[#4E7D5B]/10 px-2.5 py-0.5 rounded border border-primary/20 tracking-wider uppercase mb-2 inline-block">
                            {faq.category}
                          </span>
                          <h4 className="text-sm font-serif font-bold text-slate-900 dark:text-white">{faq.question}</h4>
                        </div>
                        <ChevronDown className={`w-5 h-5 text-slate-400 transition-transform duration-300 ${isOpen ? 'rotate-180' : ''}`} />
                      </button>
                      
                      {isOpen && (
                        <div className="px-8 pb-6 text-xs text-slate-500 dark:text-slate-450 leading-relaxed border-t border-slate-105/50 dark:border-slate-800/50 pt-4 animate-slide-up">
                          {faq.answer}
                        </div>
                      )}
                    </div>
                  );
                })
              ) : (
                <div className="py-12 text-center text-slate-400 font-serif italic border border-dashed rounded-3xl">
                  No guidance nodes match your search query. Try searching for "transfer" or "blueprint".
                </div>
              )}
            </div>
          </div>

        </div>
      </section>

      {/* Still need help CTA */}
      <section className="py-32 px-6 md:px-12 bg-[#FAF9F5] dark:bg-slate-900/20 text-center border-t border-slate-100 dark:border-slate-800">
        <div className="max-w-4xl mx-auto">
          <h2 className="text-4xl md:text-5xl font-serif text-slate-900 dark:text-white mb-10 leading-tight font-bold">Can't find the <span className="italic text-[#4E7D5B] font-serif font-normal">resonance</span> you need?</h2>
          <p className="text-xl text-slate-500 dark:text-slate-400 mb-14 font-serif italic">Our support nodes are standing by to assist with any operational queries.</p>
          <Link to="/contact" className="btn-primary px-16 py-6 text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/20">
            INITIATE DIRECT SYNC
          </Link>
        </div>
      </section>
    </div>
  );
}
