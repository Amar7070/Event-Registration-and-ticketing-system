<x-app-layout>
    <div class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(79,70,229,0.45),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.3),_transparent_30%)]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="max-w-3xl">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300/80 mb-4">Insight & stories</p>
                <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">Latest news from the world of events.</h1>
                <p class="text-lg text-slate-300 leading-relaxed">Discover product updates, event planning tips and organizer success stories designed to help your next event reach more people.</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid gap-8 lg:grid-cols-3">
            <article class="group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-2 hover:shadow-xl">
                <img src="https://images.unsplash.com/photo-1485217988980-11786ced9454?auto=format&fit=crop&q=80&w=1200" alt="Event planning" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="p-8">
                    <p class="text-xs uppercase tracking-[0.35em] text-indigo-600 mb-4">Planning</p>
                    <h2 class="text-2xl font-extrabold text-slate-900 mb-4">How to plan a memorable event in 2026</h2>
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">A complete guide for organizers on selecting venues, ticketing strategies, and creating unforgettable experiences.</p>
                    <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800">Read article <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
                </div>
            </article>

            <article class="group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-2 hover:shadow-xl">
                <img src="https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&q=80&w=1200" alt="Networking event" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="p-8">
                    <p class="text-xs uppercase tracking-[0.35em] text-indigo-600 mb-4">Tips</p>
                    <h2 class="text-2xl font-extrabold text-slate-900 mb-4">Boost attendance with better invite flow</h2>
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">Small improvements in your event listing and checkout experience can drive far better ticket conversions.</p>
                    <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800">Read article <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
                </div>
            </article>

            <article class="group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-2 hover:shadow-xl">
                <img src="https://images.unsplash.com/photo-1515169067868-5387ec356754?auto=format&fit=crop&q=80&w=1200" alt="Event technology" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="p-8">
                    <p class="text-xs uppercase tracking-[0.35em] text-indigo-600 mb-4">Product</p>
                    <h2 class="text-2xl font-extrabold text-slate-900 mb-4">New tools for organizers to sell faster</h2>
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">From dynamic pricing to waitlist automation, learn how Evenzo is helping organizers run events with confidence.</p>
                    <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800">Read article <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
                </div>
            </article>
        </div>

        <div class="mt-20 rounded-[2rem] bg-indigo-600 px-8 py-14 text-white shadow-2xl shadow-indigo-500/20">
            <div class="md:flex md:items-center md:justify-between gap-8">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-100 mb-3">Stay current</p>
                    <h2 class="text-3xl font-extrabold mb-4">Subscribe for event insights every week.</h2>
                    <p class="text-slate-100 max-w-2xl">Get the latest event marketing tips, product announcements, and success stories delivered directly to your inbox.</p>
                </div>
                <form class="mt-8 md:mt-0 flex flex-col sm:flex-row gap-3 w-full max-w-xl">
                    <input type="email" placeholder="Your email address" class="flex-1 rounded-full border border-white/20 bg-white/10 px-5 py-4 text-sm text-white placeholder:text-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-300/60">
                    <button type="submit" class="rounded-full bg-white px-7 py-4 text-sm font-bold uppercase tracking-[0.2em] text-indigo-600 shadow-lg shadow-white/10 hover:bg-slate-100 transition">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
