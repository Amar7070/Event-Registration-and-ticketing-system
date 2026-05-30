import React, { useState } from 'react';
import Navbar from '../components/Navbar';
import api from '../services/api';
import { Mail, MapPin, Phone, Send, Sparkles, CheckCircle2 } from 'lucide-react';

export default function Contact() {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: ''
  });
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState('');

  const handleChange = (e) => {
    setFormData(prev => ({
      ...prev,
      [e.target.name]: e.target.value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    
    try {
      await api.post('/contact', formData);
      setSuccess(true);
      setFormData({ name: '', email: '', subject: '', message: '' });
    } catch (err) {
      setError(err.response?.data?.message || 'Something went wrong. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-[#FDFBF7] dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans transition-colors duration-500 overflow-x-hidden selection:bg-[#4E7D5B] selection:text-white">
      <Navbar />

      {/* Cinematic Ambient Glow Orbs */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div className="absolute top-[10%] left-[50%] w-[800px] h-[800px] bg-indigo-500/10 dark:bg-indigo-500/10 rounded-full blur-[160px] -translate-x-1/2"></div>
        <div className="absolute top-[40%] right-[0%] w-[500px] h-[500px] bg-[#4E7D5B]/10 dark:bg-[#4E7D5B]/10 rounded-full blur-[120px]"></div>
      </div>

      <section className="relative pt-40 pb-20 md:pt-48 md:pb-32 px-6 md:px-12 z-10">
        <div className="max-w-7xl mx-auto">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-start">
            
            {/* Left Column: Text & Info */}
            <div className="flex flex-col justify-center animate-slide-up">
              <div className="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-indigo-500/10 dark:bg-indigo-500/20 border border-indigo-500/20 w-fit mb-8 backdrop-blur-sm">
                <Sparkles className="w-4 h-4 text-indigo-500" />
                <span className="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-400">Get in touch</span>
              </div>
              
              <h1 className="text-5xl md:text-7xl font-serif text-slate-900 dark:text-white leading-[1.05] tracking-tight mb-8">
                Let's build <br />
                <span className="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-rose-500 italic">something extraordinary.</span>
              </h1>
              
              <p className="text-lg md:text-xl text-slate-600 dark:text-slate-400 font-medium leading-relaxed max-w-xl mb-12">
                Whether you're looking to host an exclusive summit, integrate our API, or just want to say hello, our team is ready to collaborate.
              </p>

              <div className="space-y-8">
                <div className="flex items-center gap-6 group">
                  <div className="w-14 h-14 rounded-2xl bg-white dark:bg-slate-900 flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-800 shadow-md group-hover:scale-110 group-hover:border-indigo-500/50 transition-all">
                    <Mail className="w-6 h-6 text-indigo-500" />
                  </div>
                  <div>
                    <h4 className="text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Email Us</h4>
                    <a href="mailto:hello@smartevent.com" className="text-xl font-serif font-bold text-slate-900 dark:text-white hover:text-indigo-500 transition-colors">hello@smartevent.com</a>
                  </div>
                </div>

                <div className="flex items-center gap-6 group">
                  <div className="w-14 h-14 rounded-2xl bg-white dark:bg-slate-900 flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-800 shadow-md group-hover:scale-110 group-hover:border-[#4E7D5B]/50 transition-all">
                    <MapPin className="w-6 h-6 text-[#4E7D5B]" />
                  </div>
                  <div>
                    <h4 className="text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Headquarters</h4>
                    <p className="text-xl font-serif font-bold text-slate-900 dark:text-white">San Francisco, CA</p>
                  </div>
                </div>

                <div className="flex items-center gap-6 group">
                  <div className="w-14 h-14 rounded-2xl bg-white dark:bg-slate-900 flex items-center justify-center shrink-0 border border-slate-200 dark:border-slate-800 shadow-md group-hover:scale-110 group-hover:border-rose-500/50 transition-all">
                    <Phone className="w-6 h-6 text-rose-500" />
                  </div>
                  <div>
                    <h4 className="text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Call Us</h4>
                    <p className="text-xl font-serif font-bold text-slate-900 dark:text-white">+1 (800) 123-4567</p>
                  </div>
                </div>
              </div>
            </div>

            {/* Right Column: Form */}
            <div className="relative animate-fade-in" style={{animationDelay: '200ms'}}>
              <div className="absolute -inset-4 bg-gradient-to-tr from-indigo-500/20 to-rose-500/20 rounded-[3.5rem] blur-2xl opacity-60 dark:opacity-40"></div>
              <div className="relative bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 dark:border-slate-700/50 rounded-[3rem] p-10 md:p-14 shadow-2xl">
                
                {success ? (
                  <div className="flex flex-col items-center justify-center text-center py-12">
                    <div className="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mb-6">
                      <CheckCircle2 className="w-10 h-10 text-emerald-500" />
                    </div>
                    <h3 className="text-3xl font-serif font-bold text-slate-900 dark:text-white mb-4">Message Sent!</h3>
                    <p className="text-slate-600 dark:text-slate-400">Thank you for reaching out. Our team will get back to you within 24 hours.</p>
                    <button onClick={() => setSuccess(false)} className="mt-8 text-sm font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-600">Send another message</button>
                  </div>
                ) : (
                  <form onSubmit={handleSubmit} className="space-y-6">
                    <h3 className="text-2xl font-serif font-bold text-slate-900 dark:text-white mb-8">Send a Message</h3>
                    
                    {error && (
                      <div className="p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-500 text-sm">
                        {error}
                      </div>
                    )}

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div className="space-y-2">
                        <label className="text-[10px] font-black uppercase tracking-widest text-slate-500">Your Name</label>
                        <input 
                          type="text" 
                          name="name"
                          required
                          value={formData.name}
                          onChange={handleChange}
                          className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-5 py-4 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all"
                          placeholder="Jane Doe"
                        />
                      </div>
                      <div className="space-y-2">
                        <label className="text-[10px] font-black uppercase tracking-widest text-slate-500">Email Address</label>
                        <input 
                          type="email" 
                          name="email"
                          required
                          value={formData.email}
                          onChange={handleChange}
                          className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-5 py-4 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all"
                          placeholder="jane@example.com"
                        />
                      </div>
                    </div>

                    <div className="space-y-2">
                      <label className="text-[10px] font-black uppercase tracking-widest text-slate-500">Subject</label>
                      <input 
                        type="text" 
                        name="subject"
                        value={formData.subject}
                        onChange={handleChange}
                        className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-5 py-4 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all"
                        placeholder="How can we help?"
                      />
                    </div>

                    <div className="space-y-2">
                      <label className="text-[10px] font-black uppercase tracking-widest text-slate-500">Message</label>
                      <textarea 
                        name="message"
                        required
                        value={formData.message}
                        onChange={handleChange}
                        rows="5"
                        className="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl px-5 py-4 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all resize-none"
                        placeholder="Tell us about your event needs..."
                      ></textarea>
                    </div>

                    <button 
                      type="submit" 
                      disabled={loading}
                      className="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-5 rounded-2xl text-sm font-black uppercase tracking-widest transition-all hover:-translate-y-1 shadow-[0_10px_30px_rgba(79,70,229,0.3)] flex items-center justify-center gap-3 disabled:opacity-70 disabled:hover:translate-y-0"
                    >
                      {loading ? 'Sending...' : (
                        <>
                          <span>Send Message</span>
                          <Send className="w-4 h-4" />
                        </>
                      )}
                    </button>
                  </form>
                )}
              </div>
            </div>

          </div>
        </div>
      </section>
    </div>
  );
}
