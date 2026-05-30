<x-admin-layout>
    <x-slot name="header">
        User Matrix
    </x-slot>

    <div class="mb-12">
        <h2 class="text-3xl font-serif text-slate-900 mb-2">Resident Identity Matrix</h2>
        <p class="text-slate-500 font-serif italic text-sm">"Managing the individual nodes that form our collective resonance."</p>
    </div>

    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="p-10 border-b border-slate-50 flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white">
            <div>
                <h3 class="text-xl font-serif text-slate-900">Identity Grid</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Total Resident Nodes: {{ $users->total() }}</p>
            </div>
            
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="relative group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-primary transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Locate identity..." 
                           class="form-input pl-12 pr-6 py-3 w-full sm:w-72">
                </div>
                <div class="relative">
                    <select name="role" class="form-input pl-6 pr-12 py-3 appearance-none bg-white min-w-[160px]">
                        <option value="">All Archetypes</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
                <button type="submit" class="btn-primary px-8 py-3 text-xs">
                    Filter Matrix
                </button>
            </form>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Identity Details</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Archetype</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Node Status</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Creation Date</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                        <tr class="group hover:bg-cream/50 transition-colors duration-300">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-5">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4D7C0F&color=fff" class="w-12 h-12 rounded-2xl shadow-sm border-2 border-white group-hover:scale-110 transition-transform duration-500">
                                    <div>
                                        <p class="font-bold text-slate-900 group-hover:text-primary transition-colors text-lg leading-tight">{{ $user->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <span class="status-pill bg-slate-100 text-slate-600 border-slate-200 tracking-widest uppercase">
                                    {{ $user->roles->first()->name ?? 'Guest' }}
                                </span>
                            </td>
                            <td class="px-10 py-8">
                                @if($user->is_active)
                                    <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">ACTIVE NODE</span>
                                @else
                                    <span class="status-pill bg-rose-50 text-rose-500 border-rose-100 tracking-widest">SUSPENDED</span>
                                @endif
                            </td>
                            <td class="px-10 py-8 text-sm font-bold text-slate-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-10 py-8 text-right">
                                <button onclick="openEditModal({{ $user->id }}, '{{ $user->roles->first()->name ?? '' }}', {{ $user->is_active ? 'true' : 'false' }})" 
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-primary group-hover:translate-x-1 transition-transform inline-flex items-center gap-2">
                                    Adjust Access <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center">
                                <div class="w-20 h-20 bg-cream rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                    <i data-lucide="user-x" class="w-10 h-10"></i>
                                </div>
                                <h3 class="text-xl font-serif text-slate-900 mb-2">No identities located.</h3>
                                <p class="text-slate-500 font-serif italic">Adjust your filters to scan broader matrix nodes.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="p-10 border-t border-slate-50 bg-cream/10">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="bg-white p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                                <i data-lucide="shield-check" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-2xl font-serif text-slate-900" id="modal-title">Adjust Access Credentials</h3>
                        </div>
                        
                        <div class="space-y-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Archetype Role</label>
                                <div class="relative">
                                    <select name="role" id="modal-role" class="form-input appearance-none bg-white">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }} Node</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Ecosystem Status</label>
                                <div class="relative">
                                    <select name="is_active" id="modal-status" class="form-input appearance-none bg-white">
                                        <option value="1">Active Node</option>
                                        <option value="0">Suspended / Quarantined</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-cream p-10 flex items-center justify-end gap-6">
                        <button type="button" onclick="closeEditModal()" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary px-10 py-4 text-xs">
                            Apply Adjustments
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(userId, currentRole, isActive) {
            document.getElementById('editForm').action = `/admin/users/${userId}`;
            document.getElementById('modal-role').value = currentRole;
            document.getElementById('modal-status').value = isActive ? '1' : '0';
            document.getElementById('editModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        
        lucide.createIcons();
    </script>
</x-admin-layout>
