<x-admin-layout>
    <x-slot name="header">
        Archetype Library
    </x-slot>

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 mb-12">
        <div>
            <h2 class="text-3xl font-serif text-slate-900 mb-2">Archetype Constructs</h2>
            <p class="text-slate-500 font-serif italic text-sm">"Defining the foundational categories for all ecosystem experiences."</p>
        </div>
        <button onclick="openCreateModal()" class="btn-primary px-8 py-3 text-xs shadow-xl shadow-primary/20">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Create Archetype
        </button>
    </div>

    <div class="premium-card bg-white border-slate-100 overflow-hidden shadow-2xl shadow-primary/5">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-cream/30">
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Archetype Details</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Visual Identity</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Experience Count</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Node Status</th>
                        <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($categories as $category)
                        <tr class="group hover:bg-cream/50 transition-colors duration-300">
                            <td class="px-10 py-8">
                                <p class="font-bold text-slate-900 group-hover:text-primary transition-colors text-lg leading-tight">{{ $category->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 truncate max-w-xs">{{ $category->description ?? 'Universal experience archetype.' }}</p>
                            </td>
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-500" style="background-color: {{ $category->color ?? '#4D7C0F' }}">
                                        @if($category->icon)
                                            <i data-lucide="{{ $category->icon }}" class="w-5 h-5"></i>
                                        @else
                                            <i data-lucide="tag" class="w-5 h-5"></i>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-mono tracking-tighter">{{ strtoupper($category->color ?? '#4D7C0F') }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <span class="status-pill bg-cream text-slate-600 border-slate-100 tracking-widest">
                                    {{ $category->events_count ?? 0 }} ARCHITECTURES
                                </span>
                            </td>
                            <td class="px-10 py-8">
                                @if($category->is_active)
                                    <span class="status-pill bg-primary/10 text-primary border-primary/20 tracking-widest">ENABLED</span>
                                @else
                                    <span class="status-pill bg-slate-50 text-slate-400 border-slate-100 tracking-widest">INACTIVE</span>
                                @endif
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex items-center justify-end gap-6">
                                    <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}', '{{ $category->icon ?? '' }}', '{{ $category->color ?? '#4D7C0F' }}', {{ $category->is_active ? 'true' : 'false' }})" 
                                            class="text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:text-primary-hover transition-colors">
                                        Modify
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this archetype?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-500 hover:text-rose-600 transition-colors">
                                            Purge
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center text-slate-400 font-serif italic">
                                <div class="w-20 h-20 bg-cream rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                    <i data-lucide="tags" class="w-10 h-10"></i>
                                </div>
                                <h3 class="text-xl font-serif text-slate-900 mb-2">Archetype library is empty.</h3>
                                <p class="text-slate-500 font-serif italic">Construct your first experience category to begin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($categories->hasPages())
            <div class="p-10 border-t border-slate-50 bg-cream/10">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCategoryModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="bg-white p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                                <i data-lucide="layers" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-2xl font-serif text-slate-900" id="modal-title">Construct Archetype</h3>
                        </div>
                        
                        <div class="space-y-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Archetype Label</label>
                                <input type="text" name="name" id="modal-name" required class="form-input" placeholder="e.g. Cinematic Experiences">
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Resonance Manifest (Description)</label>
                                <textarea name="description" id="modal-description" rows="3" class="form-input" placeholder="Define the core intent of this archetype..."></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Icon Glyph</label>
                                    <div class="relative">
                                        <input type="text" name="icon" id="modal-icon" placeholder="e.g. music, camera" class="form-input pl-12">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                            <i data-lucide="pen-tool" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Frequency Color</label>
                                    <div class="flex items-center gap-4 p-2 bg-cream rounded-2xl border border-slate-100">
                                        <input type="color" name="color" id="modal-color" value="#4D7C0F" class="h-10 w-12 border-none rounded-xl cursor-pointer bg-transparent">
                                        <span class="text-[10px] text-slate-500 font-mono font-black" id="color-hex">#4D7C0F</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pt-4">
                                <label class="flex items-center gap-4 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" id="modal-active" value="1" checked class="sr-only peer">
                                        <div class="w-12 h-6 bg-slate-100 rounded-full peer-checked:bg-primary transition-colors border border-slate-200"></div>
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6 shadow-sm"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">Enabled in Ecosystem</span>
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Available for experience architecture</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-cream p-10 flex items-center justify-end gap-6">
                        <button type="button" onclick="closeCategoryModal()" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="modal-submit-btn" class="btn-primary px-10 py-4 text-xs shadow-xl shadow-primary/20">
                            Construct Archetype
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('modal-color').addEventListener('input', function(e) {
            document.getElementById('color-hex').innerText = e.target.value.toUpperCase();
        });

        function openCreateModal() {
            document.getElementById('modal-title').innerText = 'Construct Archetype';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('categoryForm').action = '{{ route('admin.categories.store') }}';
            document.getElementById('modal-submit-btn').innerText = 'Construct Archetype';
            
            document.getElementById('modal-name').value = '';
            document.getElementById('modal-description').value = '';
            document.getElementById('modal-icon').value = '';
            document.getElementById('modal-color').value = '#4D7C0F';
            document.getElementById('color-hex').innerText = '#4D7C0F';
            document.getElementById('modal-active').checked = true;
            
            document.getElementById('categoryModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function openEditModal(id, name, description, icon, color, isActive) {
            document.getElementById('modal-title').innerText = 'Modify Archetype';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('categoryForm').action = `/admin/categories/${id}`;
            document.getElementById('modal-submit-btn').innerText = 'Apply Modifications';
            
            document.getElementById('modal-name').value = name;
            document.getElementById('modal-description').value = description;
            document.getElementById('modal-icon').value = icon;
            document.getElementById('modal-color').value = color;
            document.getElementById('color-hex').innerText = color.toUpperCase();
            document.getElementById('modal-active').checked = isActive;
            
            document.getElementById('categoryModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }
        
        lucide.createIcons();
    </script>
</x-admin-layout>
