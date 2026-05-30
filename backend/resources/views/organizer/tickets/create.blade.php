<x-organizer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Ticket Type for: ') }} {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('organizer.events.tickets.store', $event) }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info -->
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium border-b pb-2 mb-4">Basic Information</h3>
                            </div>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Ticket Name</label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                <select name="type" id="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="regular">Regular</option>
                                    <option value="vip">VIP</option>
                                    <option value="early_bird">Early Bird</option>
                                    <option value="student">Student</option>
                                    <option value="group">Group</option>
                                    <option value="premium">Premium</option>
                                </select>
                                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Pricing & Capacity -->
                            <div class="col-span-2 mt-4">
                                <h3 class="text-lg font-medium border-b pb-2 mb-4">Pricing & Capacity</h3>
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price (₹)</label>
                                <input type="number" step="0.01" name="price" id="price" required value="{{ old('price', '0.00') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="text-xs text-gray-500 mt-1">Set to 0 for free tickets</p>
                                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="original_price" class="block text-sm font-medium text-gray-700">Original Price (optional)</label>
                                <input type="number" step="0.01" name="original_price" id="original_price" value="{{ old('original_price') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="text-xs text-gray-500 mt-1">Shows as strikethrough next to actual price</p>
                            </div>

                            <div>
                                <label for="quantity_total" class="block text-sm font-medium text-gray-700">Total Quantity Available</label>
                                <input type="number" name="quantity_total" id="quantity_total" required value="{{ old('quantity_total') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('quantity_total') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="min_per_order" class="block text-sm font-medium text-gray-700">Min per order</label>
                                    <input type="number" name="min_per_order" id="min_per_order" required value="{{ old('min_per_order', '1') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="max_per_order" class="block text-sm font-medium text-gray-700">Max per order</label>
                                    <input type="number" name="max_per_order" id="max_per_order" required value="{{ old('max_per_order', '10') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <!-- Schedule -->
                            <div class="col-span-2 mt-4">
                                <h3 class="text-lg font-medium border-b pb-2 mb-4">Sales Window</h3>
                            </div>

                            <div>
                                <label for="sale_starts_at" class="block text-sm font-medium text-gray-700">Sales Start Date (Optional)</label>
                                <input type="datetime-local" name="sale_starts_at" id="sale_starts_at" value="{{ old('sale_starts_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="sale_ends_at" class="block text-sm font-medium text-gray-700">Sales End Date (Optional)</label>
                                <input type="datetime-local" name="sale_ends_at" id="sale_ends_at" value="{{ old('sale_ends_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Settings -->
                            <div class="col-span-2 mt-4">
                                <h3 class="text-lg font-medium border-b pb-2 mb-4">Settings</h3>
                            </div>

                            <div class="col-span-2 flex items-center space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">Active</span>
                                </label>
                                
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_transferable" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">Transferable</span>
                                </label>
                                
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_refundable" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2">Refundable</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-4 border-t pt-6">
                            <a href="{{ route('organizer.events.tickets.index', $event) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700">
                                Create Ticket Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-organizer-layout>
