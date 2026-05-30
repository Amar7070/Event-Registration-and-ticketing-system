<x-admin-layout>
    <x-slot name="header">
        Host Validation Hub
    </x-slot>

    <div class="mb-12">
        <h2 class="text-3xl font-serif text-slate-900 mb-2">Host Identity Validation</h2>
        <p class="text-slate-500 font-serif italic text-sm">"Reviewing applications to ensure principal architects align with ecosystem values."</p>
    </div>

    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="p-10 border-b border-slate-50 bg-white">
            <h3 class="text-xl font-serif text-slate-900">Pending Applications</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Awaiting Integrity Verification: {{ $organizers->count() }}</p>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Applicant Identity</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Application Temporal Point</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Documentation</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Approval Decision</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($organizers as $organizer)
                        <tr class="group hover:bg-cream/50 transition-colors duration-300">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black text-sm group-hover:scale-110 transition-transform duration-500">
                                        {{ substr($organizer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 group-hover:text-primary transition-colors text-lg leading-tight">{{ $organizer->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $organizer->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-sm font-bold text-slate-700">{{ $organizer->created_at->format('M d, Y') }}</p>
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mt-1">{{ $organizer->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-10 py-8">
                                <button class="text-[9px] font-black uppercase tracking-widest text-primary bg-primary/5 px-4 py-2 rounded-full hover:bg-primary hover:text-white transition-all border border-primary/10">
                                    Verify Documentation
                                </button>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <form action="{{ route('admin.organizers.approve', $organizer) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold text-[10px] uppercase tracking-widest rounded-full hover:bg-primary-hover transition-all shadow-lg shadow-primary/5">
                                            Grant Access
                                        </button>
                                    </form>
                                    <button onclick="openRejectModal({{ $organizer->id }})" 
                                            class="px-6 py-2.5 bg-rose-50 text-rose-500 font-bold text-[10px] uppercase tracking-widest rounded-full hover:bg-rose-500 hover:text-white transition-all border border-rose-100">
                                        Restrict
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-10 py-32 text-center">
                                <div class="w-24 h-24 bg-cream rounded-full flex items-center justify-center mx-auto mb-8 text-primary/20">
                                    <i data-lucide="shield-check" class="w-12 h-12"></i>
                                </div>
                                <h3 class="text-2xl font-serif text-slate-900 mb-2">Ecosystem Integrity Maintained.</h3>
                                <p class="text-slate-500 font-serif italic">All pending host applications have been successfully reviewed and verified.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($organizers->hasPages())
            <div class="p-10 border-t border-slate-50 bg-cream/10">
                {{ $organizers->links() }}
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <div class="bg-white p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500">
                                <i data-lucide="alert-octagon" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-2xl font-serif text-slate-900" id="modal-title">Restrict Access</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Reason for Restriction</label>
                            <textarea name="reason" rows="4" required placeholder="Explain the rationale for access restriction..." 
                                      class="form-input"></textarea>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">This notification will be transmitted to the applicant node.</p>
                        </div>
                    </div>
                    <div class="bg-cream p-10 flex items-center justify-end gap-6">
                        <button type="button" onclick="closeRejectModal()" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-10 py-4 bg-rose-500 text-white text-xs font-bold uppercase tracking-widest rounded-full hover:bg-rose-600 shadow-xl shadow-rose-500/10 transition-all">
                            Confirm Restriction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(organizerId) {
            document.getElementById('rejectForm').action = `/admin/organizers/${organizerId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
        
        lucide.createIcons();
    </script>
</x-admin-layout>
