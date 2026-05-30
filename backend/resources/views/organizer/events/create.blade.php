<x-organizer-layout>
    <x-slot name="header">
        Construct New Experience
    </x-slot>

    <div class="mb-12">
        <h2 class="text-3xl font-serif text-slate-900 mb-2">Experience Blueprint</h2>
        <p class="text-slate-500 font-serif italic text-sm">"Map out the architecture of your next intentional gathering."</p>
    </div>

    <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl pb-32">
        @csrf
        
        <div class="space-y-12">
            <!-- Core Identity -->
            <section class="premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <i data-lucide="info" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-serif text-slate-900">Core Identity</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="col-span-2 space-y-3">
                        <label for="title" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Experience Title</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}"
                            class="form-input"
                            placeholder="e.g. Acoustic Sunset Series: Volume I">
                        @error('title') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="category_id" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Archetype Category</label>
                        <div class="relative">
                            <select name="category_id" id="category_id" required
                                class="form-input appearance-none">
                                <option value="">Select Archetype</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                        @error('category_id') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="type" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Experience Realm</label>
                        <div class="relative">
                            <select name="type" id="type" required
                                class="form-input appearance-none">
                                <option value="physical" {{ old('type') == 'physical' ? 'selected' : '' }}>Physical Sanctuary</option>
                                <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Digital Realm</option>
                                <option value="hybrid" {{ old('type') == 'hybrid' ? 'selected' : '' }}>Hybrid Matrix</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                        @error('type') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 space-y-3">
                        <label for="short_description" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Manifesto Summary</label>
                        <textarea name="short_description" id="short_description" rows="2" required
                            class="form-input"
                            placeholder="A brief, high-level overview of the gathering purpose...">{{ old('short_description') }}</textarea>
                        @error('short_description') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 space-y-3">
                        <label for="description" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Deep Architecture (Full Description)</label>
                        <textarea name="description" id="description" rows="6" required
                            class="form-input"
                            placeholder="Detailed flow, expectations, and intended resonance for attendees...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <!-- Temporal Scheduling -->
            <section class="premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-serif text-slate-900">Temporal Scheduling</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label for="start_date" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Resonance Starts</label>
                        <input type="datetime-local" name="start_date" id="start_date" required value="{{ old('start_date') }}"
                            class="form-input">
                        @error('start_date') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="end_date" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Resonance Ends</label>
                        <input type="datetime-local" name="end_date" id="end_date" required value="{{ old('end_date') }}"
                            class="form-input">
                        @error('end_date') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label for="timezone" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">System Timezone</label>
                        <div class="relative">
                            <select name="timezone" id="timezone" required
                                class="form-input appearance-none">
                                <option value="Asia/Kolkata" {{ old('timezone', 'Asia/Kolkata') == 'Asia/Kolkata' ? 'selected' : '' }}>(GMT+05:30) IST</option>
                                <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i data-lucide="globe" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="total_capacity" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Maximum Density</label>
                        <input type="number" name="total_capacity" id="total_capacity" required value="{{ old('total_capacity') }}"
                            class="form-input"
                            placeholder="Total attendee capacity">
                        @error('total_capacity') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <!-- Spatial Logistics & Geolocation Architecture -->
            <section class="premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5"
                     x-data="{ createNewVenue: {{ old('create_new_venue') ? 'true' : 'false' }}, loadingGeo: false, lat: '{{ old('venue_latitude') }}', lng: '{{ old('venue_longitude') }}' }">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#4E7D5B]/10 flex items-center justify-center text-[#4E7D5B]">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-xl font-serif text-slate-900">Spatial Logistics</h2>
                    </div>

                    <!-- Custom Venue Toggle -->
                    <label for="create_new_venue" class="flex items-center gap-2 cursor-pointer group select-none">
                        <input type="checkbox" name="create_new_venue" id="create_new_venue" value="1" 
                               x-model="createNewVenue" class="rounded border-slate-200 text-[#4E7D5B] focus:ring-[#4E7D5B]">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">CREATE NEW VENUE</span>
                    </label>
                </div>
                
                <!-- Existing Venues Dropdown Selector -->
                <div class="space-y-3" x-show="!createNewVenue" x-transition>
                    <label for="venue_id" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Sanctuary Location</label>
                    <div class="relative">
                        <select name="venue_id" id="venue_id" :required="!createNewVenue"
                            class="form-input appearance-none">
                            <option value="">Select Managed Venue</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }} — {{ $venue->city }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <i data-lucide="navigation" class="w-4 h-4"></i>
                        </div>
                    </div>
                    @error('venue_id') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>

                <!-- Custom Venue Coordinates & Address Panel -->
                <div class="space-y-8" x-show="createNewVenue" x-transition x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="col-span-2 space-y-3">
                            <label for="venue_name" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Venue Name</label>
                            <input type="text" name="venue_name" id="venue_name" :required="createNewVenue" value="{{ old('venue_name') }}"
                                class="form-input text-slate-900" placeholder="e.g. Sage & Stone Amphitheater">
                            @error('venue_name') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-2 space-y-3">
                            <label for="venue_address" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Street Address</label>
                            <input type="text" name="venue_address" id="venue_address" :required="createNewVenue" value="{{ old('venue_address') }}"
                                class="form-input text-slate-900" placeholder="e.g. 789 Wildwood Valley Rd">
                            @error('venue_address') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label for="venue_city" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">City</label>
                            <input type="text" name="venue_city" id="venue_city" :required="createNewVenue" value="{{ old('venue_city') }}"
                                class="form-input text-slate-900" placeholder="e.g. Mumbai">
                            @error('venue_city') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label for="venue_state" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">State / Region</label>
                            <input type="text" name="venue_state" id="venue_state" :required="createNewVenue" value="{{ old('venue_state') }}"
                                class="form-input text-slate-900" placeholder="e.g. Maharashtra">
                            @error('venue_state') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label for="venue_country" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Country</label>
                            <input type="text" name="venue_country" id="venue_country" :required="createNewVenue" value="{{ old('venue_country', 'India') }}"
                                class="form-input text-slate-900" placeholder="e.g. India">
                            @error('venue_country') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label for="venue_pincode" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Pincode / Postal Code</label>
                            <input type="text" name="venue_pincode" id="venue_pincode" :required="createNewVenue" value="{{ old('venue_pincode') }}"
                                class="form-input text-slate-900" placeholder="e.g. 400001">
                            @error('venue_pincode') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Geolocation Lock Block -->
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="text-left space-y-1">
                                <span class="text-[9px] font-black text-[#4E7D5B] uppercase tracking-[0.25em] block">Spatial Precision Mapping</span>
                                <h4 class="text-sm font-bold text-slate-900">Geographical Coordinates</h4>
                                <p class="text-[11px] text-slate-500">Provide latitude/longitude to allow precise distance mapping inside the radar.</p>
                            </div>
                            
                            <!-- Detect Coordinates Button -->
                            <button type="button" 
                                    @click="
                                        loadingGeo = true;
                                        if (navigator.geolocation) {
                                            navigator.geolocation.getCurrentPosition(
                                                (position) => {
                                                    lat = position.coords.latitude;
                                                    lng = position.coords.longitude;
                                                    loadingGeo = false;
                                                },
                                                (error) => {
                                                    alert('Coordinate auto-detect failed: ' + error.message);
                                                    loadingGeo = false;
                                                }
                                            );
                                        } else {
                                            alert('Browser geolocation protocols not supported.');
                                            loadingGeo = false;
                                        }
                                    "
                                    class="px-5 py-3 bg-[#4E7D5B] text-white hover:bg-[#3D6449] rounded-full text-[10px] font-black uppercase tracking-widest transition-colors flex items-center justify-center gap-2 outline-none">
                                <template x-if="loadingGeo">
                                    <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <i data-lucide="crosshair" class="w-3.5 h-3.5" x-show="!loadingGeo"></i>
                                <span x-text="loadingGeo ? 'DETECTING...' : 'DETECT MY LOCATION'">DETECT MY LOCATION</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="venue_latitude" class="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">Latitude</label>
                                <input type="number" name="venue_latitude" id="venue_latitude" step="0.00000001" x-model="lat"
                                    class="form-input bg-white text-slate-900" placeholder="e.g. 19.0760">
                            </div>
                            <div class="space-y-2">
                                <label for="venue_longitude" class="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">Longitude</label>
                                <input type="number" name="venue_longitude" id="venue_longitude" step="0.00000001" x-model="lng"
                                    class="form-input bg-white text-slate-900" placeholder="e.g. 72.8777">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Visual Media -->
            <section class="premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <i data-lucide="image" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-serif text-slate-900">Media Assets</h2>
                </div>
                
                <div class="space-y-3">
                    <label for="banner" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Experience Banner (Cinematic Aspect Recommended)</label>
                    <div class="mt-2 flex justify-center px-10 pt-10 pb-12 border-2 border-slate-100 border-dashed rounded-[2rem] hover:border-primary/40 hover:bg-cream/50 transition-all group cursor-pointer relative"
                         onclick="document.getElementById('banner').click()">
                        <input id="banner" name="banner" type="file" class="sr-only">
                        <div class="space-y-4 text-center">
                            <div class="w-16 h-16 bg-cream rounded-2xl flex items-center justify-center mx-auto text-primary group-hover:scale-110 transition-transform duration-500">
                                <i data-lucide="upload-cloud" class="w-8 h-8"></i>
                            </div>
                            <div class="text-sm font-bold text-slate-900">
                                <span class="text-primary">Upload manifest file</span> or drag and drop
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">PNG, JPG, HEIC up to 15MB</p>
                        </div>
                    </div>
                    @error('banner') <p class="mt-2 text-[10px] font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>
            </section>

            <!-- Deployment Protocols -->
            <section class="premium-card p-10 bg-white border-slate-100 shadow-2xl shadow-primary/5">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-serif text-slate-900">Deployment Protocols</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label for="visibility" class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Ecosystem Visibility</label>
                        <div class="relative">
                            <select name="visibility" id="visibility" required
                                class="form-input appearance-none">
                                <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public Map</option>
                                <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private Invite Only</option>
                                <option value="unlisted" {{ old('visibility') == 'unlisted' ? 'selected' : '' }}>Unlisted Hub</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center pt-8 px-4">
                        <label for="requires_approval" class="flex items-center gap-4 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="requires_approval" id="requires_approval" value="1" {{ old('requires_approval') ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-12 h-6 bg-slate-100 rounded-full peer-checked:bg-primary transition-colors border border-slate-200"></div>
                                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-6 shadow-sm"></div>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">Manual Resonance Approval</span>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Verify every attendee node</span>
                            </div>
                        </label>
                    </div>
                </div>
            </section>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-12 border-t border-slate-100">
                <a href="{{ route('organizer.events.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors flex items-center gap-2">
                    <i data-lucide="x" class="w-3 h-3"></i> Abort Construction
                </a>
                <div class="flex items-center gap-6">
                    <button type="submit" name="status" value="draft" class="text-[10px] font-black text-primary uppercase tracking-[0.2em] hover:text-primary-hover transition-colors">
                        Save as Blueprint
                    </button>
                    <button type="submit" name="status" value="published" class="btn-primary px-10 py-4 text-sm font-bold tracking-tight shadow-xl shadow-primary/20">
                        Launch to Ecosystem
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        lucide.createIcons();
    </script>
</x-organizer-layout>
