<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identity Verification - {{ $event->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0F172A; }
        .font-serif { font-family: 'Fraunces', serif; }
        #reader { border: none !important; }
        #reader__dashboard_section_csr button {
            background-color: #4D7C0F !important;
            color: white !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 9999px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            font-size: 0.7rem !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s !important;
        }
        #reader__dashboard_section_csr button:hover {
            background-color: #3f620c !important;
            transform: translateY(-2px) !important;
        }
    </style>
</head>
<body class="text-white min-h-screen flex flex-col selection:bg-primary selection:text-white">
    <!-- Navigation Header -->
    <header class="p-6 md:p-10 border-b border-white/5 bg-[#1E293B]/80 backdrop-blur-xl sticky top-0 z-[50]">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-6">
            <div class="flex items-center gap-5">
                <a href="{{ route('organizer.events.index') }}" class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                    <i data-lucide="arrow-left" class="w-6 h-6"></i>
                </a>
                <div class="min-w-0">
                    <h1 class="text-xl md:text-2xl font-serif text-white truncate max-w-[200px] md:max-w-md">{{ $event->title }}</h1>
                    <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] mt-1">Identity Verification Hub</p>
                </div>
            </div>
            <div class="text-right shrink-0">
                <div class="flex items-center justify-end gap-3">
                    <div class="text-3xl md:text-4xl font-serif text-white leading-none" id="attendance-count">{{ $event->attendance_count ?? 0 }}</div>
                    <div class="w-1.5 h-10 bg-primary/20 rounded-full overflow-hidden">
                        <div class="bg-primary w-full transition-all duration-500" style="height: {{ ($event->attendance_count / max($event->total_capacity, 1)) * 100 }}%"></div>
                    </div>
                </div>
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mt-2">Resident Nodes Verified</p>
            </div>
        </div>
    </header>

    <!-- Scanning Node -->
    <main class="flex-1 flex flex-col items-center justify-center p-6 md:p-12 relative overflow-hidden">
        <!-- Background Accents -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary/5 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="relative w-full max-w-xl group">
            <!-- Scanner Frame -->
            <div class="absolute -inset-1 bg-gradient-to-r from-primary/20 via-primary/40 to-primary/20 rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
            
            <div class="relative bg-[#1E293B] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
                <div id="reader" class="w-full aspect-square"></div>
                
                <div class="absolute bottom-0 inset-x-0 p-8 bg-gradient-to-t from-black/80 to-transparent pointer-events-none">
                    <div class="flex items-center justify-center gap-4">
                        <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-white/60">Node Pulse Scanning Active</span>
                    </div>
                </div>
            </div>

            <!-- Guidance Lines -->
            <div class="absolute inset-0 pointer-events-none border-2 border-primary/20 rounded-[2.5rem] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>

        <div class="mt-12 max-w-md text-center">
            <h3 class="text-lg font-serif text-slate-400 mb-2 italic">"Scan the Digital Passport of the arriving node."</h3>
            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Position the QR code within the focus architecture.</p>
        </div>
        
        <!-- Status Portal (Overlay) -->
        <div id="status-overlay" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-6">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-xl" onclick="resetScanner()"></div>
            
            <div id="status-card" class="relative bg-white text-slate-900 w-full max-w-lg rounded-[3rem] p-12 text-center shadow-2xl transform scale-95 transition-all duration-500 opacity-0 border border-slate-100">
                <div id="status-icon-container" class="mx-auto w-24 h-24 rounded-[2rem] flex items-center justify-center mb-10 shadow-lg transition-transform duration-700">
                    <i id="status-icon" data-lucide="check" class="w-10 h-10"></i>
                </div>
                
                <h2 id="status-title" class="text-4xl font-serif mb-4 leading-tight text-slate-900">Valid Identity</h2>
                <p id="status-message" class="text-slate-500 font-serif italic text-lg mb-10">Resident node successfully verified in the ecosystem.</p>
                
                <div id="attendee-details" class="bg-cream rounded-[2rem] p-8 text-left hidden mb-10 border border-slate-100">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-primary font-black text-xl shadow-sm border border-slate-100" id="attendee-initial">
                            J
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">RESIDENT IDENTITY</span>
                            <p id="attendee-name" class="font-bold text-xl text-slate-900 mt-1"></p>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-slate-200/50 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ARCHETYPE</span>
                            <p id="attendee-ticket" class="font-bold text-primary mt-1"></p>
                        </div>
                        <div class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">VERIFIED</div>
                    </div>
                </div>

                <button onclick="resetScanner()" class="w-full py-5 px-8 bg-slate-900 text-white rounded-full font-black text-[10px] uppercase tracking-[0.3em] hover:bg-slate-800 transition-all hover:scale-[1.02] shadow-xl shadow-slate-900/10">
                    Scan Next Passport
                </button>
            </div>
        </div>
    </main>

    <footer class="p-10 text-center relative z-10">
        <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.4em]">SmartEvent Integrity Engine v2.0</p>
    </footer>

    <script>
        const eventId = {{ $event->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let html5QrcodeScanner;
        let isScanning = true;

        function onScanSuccess(decodedText, decodedResult) {
            if (!isScanning) return;
            isScanning = false;
            
            html5QrcodeScanner.pause();
            
            fetch(`/organizer/events/${eventId}/scan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    qr_data: decodedText,
                    event_id: eventId
                })
            })
            .then(response => response.json())
            .then(data => {
                showResult(data.valid, data);
                if(data.valid) {
                    let countEl = document.getElementById('attendance-count');
                    countEl.innerText = parseInt(countEl.innerText) + 1;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showResult(false, { message: 'Identity structure unrecognized or network signal lost.' });
            });
        }

        function showResult(isValid, data) {
            const overlay = document.getElementById('status-overlay');
            const card = document.getElementById('status-card');
            const iconContainer = document.getElementById('status-icon-container');
            const icon = document.getElementById('status-icon');
            const title = document.getElementById('status-title');
            const message = document.getElementById('status-message');
            const details = document.getElementById('attendee-details');
            
            overlay.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 50);

            if (isValid) {
                iconContainer.className = 'mx-auto w-24 h-24 rounded-[2rem] flex items-center justify-center mb-10 bg-primary/10 text-primary shadow-lg shadow-primary/5';
                icon.setAttribute('data-lucide', 'check-circle');
                title.innerText = 'Identity Verified';
                title.className = 'text-4xl font-serif mb-4 text-slate-900';
                message.innerText = 'Digital Passport validated. Resonance point cleared.';
                
                details.classList.remove('hidden');
                document.getElementById('attendee-name').innerText = data.ticket.user.name;
                document.getElementById('attendee-ticket').innerText = data.ticket.ticket_type.name;
                document.getElementById('attendee-initial').innerText = data.ticket.user.name.charAt(0);
            } else {
                iconContainer.className = 'mx-auto w-24 h-24 rounded-[2rem] flex items-center justify-center mb-10 bg-rose-50 text-rose-500 shadow-lg shadow-rose-500/5';
                icon.setAttribute('data-lucide', 'alert-octagon');
                title.innerText = 'Verification Failure';
                title.className = 'text-4xl font-serif mb-4 text-rose-500';
                message.innerText = data.message || 'Identity node rejected from current architecture.';
                details.classList.add('hidden');
            }
            lucide.createIcons();
        }

        function resetScanner() {
            const overlay = document.getElementById('status-overlay');
            const card = document.getElementById('status-card');
            
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
                isScanning = true;
                html5QrcodeScanner.resume();
            }, 300);
        }

        document.addEventListener("DOMContentLoaded", function() {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { 
                    fps: 15, 
                    qrbox: { width: 300, height: 300 },
                    aspectRatio: 1.0,
                    showTorchButtonIfSupported: true
                },
                /* verbose= */ false);
            html5QrcodeScanner.render(onScanSuccess);
            lucide.createIcons();
        });
    </script>
</body>
</html>
