<x-organizer-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Event: ') }} {{ $event->title }}
            </h2>
            <a href="{{ route('organizer.events.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back to Events</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('organizer.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-8">
                    <!-- Basic Information -->
                    <section class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <i data-lucide="info" class="w-5 h-5 mr-2 text-indigo-500"></i> Basic Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Event Title</label>
                                <input type="text" name="title" id="title" required value="{{ old('title', $event->title) }}"
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                                <select name="category_id" id="category_id" required
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-semibold text-gray-700 mb-1">Event Type</label>
                                <select name="type" id="type" required
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                    <option value="physical" {{ old('type', $event->type) == 'physical' ? 'selected' : '' }}>Physical</option>
                                    <option value="online" {{ old('type', $event->type) == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="hybrid" {{ old('type', $event->type) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                                @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="short_description" class="block text-sm font-semibold text-gray-700 mb-1">Short Description</label>
                                <textarea name="short_description" id="short_description" rows="2" required
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">{{ old('short_description', $event->short_description) }}</textarea>
                                @error('short_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Full Description</label>
                                <textarea name="description" id="description" rows="6" required
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $event->description) }}</textarea>
                                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <!-- Schedule & Capacity -->
                    <section class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <i data-lucide="calendar" class="w-5 h-5 mr-2 text-indigo-500"></i> Schedule & Capacity
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1">Start Date & Time</label>
                                <input type="datetime-local" name="start_date" id="start_date" required value="{{ old('start_date', $event->start_date ? date('Y-m-d\TH:i', strtotime($event->start_date)) : '') }}"
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                @error('start_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1">End Date & Time</label>
                                <input type="datetime-local" name="end_date" id="end_date" required value="{{ old('end_date', $event->end_date ? date('Y-m-d\TH:i', strtotime($event->end_date)) : '') }}"
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                @error('end_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="timezone" class="block text-sm font-semibold text-gray-700 mb-1">Timezone</label>
                                <select name="timezone" id="timezone" required
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                    <option value="Asia/Kolkata" {{ old('timezone', $event->timezone) == 'Asia/Kolkata' ? 'selected' : '' }}>(GMT+05:30) India Standard Time</option>
                                    <option value="UTC" {{ old('timezone', $event->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                </select>
                                @error('timezone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="total_capacity" class="block text-sm font-semibold text-gray-700 mb-1">Total Capacity</label>
                                <input type="number" name="total_capacity" id="total_capacity" required value="{{ old('total_capacity', $event->total_capacity) }}"
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                @error('total_capacity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <!-- Spatial Logistics & Geolocation Architecture -->
                    <section class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm"
                             x-data="{ createNewVenue: {{ old('create_new_venue') ? 'true' : 'false' }}, loadingGeo: false, lat: '{{ old('venue_latitude') }}', lng: '{{ old('venue_longitude') }}' }">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                                <i data-lucide="map-pin" class="w-5 h-5 mr-2 text-[#4E7D5B]"></i> Spatial Logistics
                            </h2>

                            <!-- Custom Venue Toggle -->
                            <label for="create_new_venue" class="flex items-center gap-2 cursor-pointer group select-none">
                                <input type="checkbox" name="create_new_venue" id="create_new_venue" value="1" 
                                       x-model="createNewVenue" class="rounded border-slate-200 text-[#4E7D5B] focus:ring-[#4E7D5B]">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">CREATE NEW VENUE</span>
                            </label>
                        </div>
                        
                        <!-- Existing Venues Dropdown Selector -->
                        <div class="space-y-3" x-show="!createNewVenue" x-transition>
                            <label for="venue_id" class="block text-sm font-semibold text-gray-700 mb-1">Venue (for physical/hybrid)</label>
                            <div class="relative">
                                <select name="venue_id" id="venue_id" :required="!createNewVenue"
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                    <option value="">Select Venue</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ old('venue_id', $event->venue_id) == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->name }} - {{ $venue->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('venue_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Custom Venue Coordinates & Address Panel -->
                        <div class="space-y-6 mt-6" x-show="createNewVenue" x-transition x-cloak>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2 space-y-2">
                                    <label for="venue_name" class="block text-sm font-semibold text-gray-700 mb-1">Venue Name</label>
                                    <input type="text" name="venue_name" id="venue_name" :required="createNewVenue" value="{{ old('venue_name') }}"
                                        class="w-full border-gray-200 rounded-xl py-3 text-slate-900" placeholder="e.g. Sage & Stone Amphitheater">
                                    @error('venue_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="col-span-2 space-y-2">
                                    <label for="venue_address" class="block text-sm font-semibold text-gray-700 mb-1">Street Address</label>
                                    <input type="text" name="venue_address" id="venue_address" :required="createNewVenue" value="{{ old('venue_address') }}"
                                        class="w-full border-gray-200 rounded-xl py-3 text-slate-900" placeholder="e.g. 789 Wildwood Valley Rd">
                                    @error('venue_address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="venue_city" class="block text-sm font-semibold text-gray-700 mb-1">City</label>
                                    <input type="text" name="venue_city" id="venue_city" :required="createNewVenue" value="{{ old('venue_city') }}"
                                        class="w-full border-gray-200 rounded-xl py-3 text-slate-900" placeholder="e.g. Mumbai">
                                    @error('venue_city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="venue_state" class="block text-sm font-semibold text-gray-700 mb-1">State / Region</label>
                                    <input type="text" name="venue_state" id="venue_state" :required="createNewVenue" value="{{ old('venue_state') }}"
                                        class="w-full border-gray-200 rounded-xl py-3 text-slate-900" placeholder="e.g. Maharashtra">
                                    @error('venue_state') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="venue_country" class="block text-sm font-semibold text-gray-700 mb-1">Country</label>
                                    <input type="text" name="venue_country" id="venue_country" :required="createNewVenue" value="{{ old('venue_country', 'India') }}"
                                        class="w-full border-gray-200 rounded-xl py-3 text-slate-900" placeholder="e.g. India">
                                    @error('venue_country') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="venue_pincode" class="block text-sm font-semibold text-gray-700 mb-1">Pincode / Postal Code</label>
                                    <input type="text" name="venue_pincode" id="venue_pincode" :required="createNewVenue" value="{{ old('venue_pincode') }}"
                                        class="w-full border-gray-200 rounded-xl py-3 text-slate-900" placeholder="e.g. 400001">
                                    @error('venue_pincode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
                                            class="w-full border-gray-200 rounded-xl py-3 text-slate-900 bg-white" placeholder="e.g. 19.0760">
                                    </div>
                                    <div class="space-y-2">
                                        <label for="venue_longitude" class="text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">Longitude</label>
                                        <input type="number" name="venue_longitude" id="venue_longitude" step="0.00000001" x-model="lng"
                                            class="w-full border-gray-200 rounded-xl py-3 text-slate-900 bg-white" placeholder="e.g. 72.8777">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Banner Image -->
                    <section class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <i data-lucide="image" class="w-5 h-5 mr-2 text-indigo-500"></i> Media
                        </h2>
                        <div>
                            @if($event->banner)
                                <div class="mb-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Current Banner</p>
                                    <img src="{{ asset('storage/' . $event->banner) }}" alt="Banner" class="w-full max-h-64 object-cover rounded-xl border border-gray-200">
                                </div>
                            @endif
                            <label for="banner" class="block text-sm font-semibold text-gray-700 mb-1">Replace Banner Image</label>
                            <input id="banner" name="banner" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 10MB. 1920x600 recommended.</p>
                            @error('banner') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </section>

                    <!-- Visibility & Other -->
                    <section class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <i data-lucide="eye" class="w-5 h-5 mr-2 text-indigo-500"></i> Settings
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="visibility" class="block text-sm font-semibold text-gray-700 mb-1">Visibility</label>
                                <select name="visibility" id="visibility" required
                                    class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                    <option value="public" {{ old('visibility', $event->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ old('visibility', $event->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                                    <option value="unlisted" {{ old('visibility', $event->visibility) == 'unlisted' ? 'selected' : '' }}>Unlisted</option>
                                </select>
                                @error('visibility') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center mt-8">
                                <input type="checkbox" name="requires_approval" id="requires_approval" value="1" {{ old('requires_approval', $event->requires_approval) ? 'checked' : '' }}
                                    class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="requires_approval" class="ml-3 block text-sm font-semibold text-gray-900">
                                    Attendees require manual approval
                                </label>
                            </div>
                        </div>
                    </section>

                    <div class="flex items-center justify-end gap-4 pb-12">
                        <a href="{{ route('organizer.events.index') }}" class="px-6 py-3 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 transition-all flex items-center">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
</x-organizer-layout>
