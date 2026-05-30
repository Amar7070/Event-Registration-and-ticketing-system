<x-organizer-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ticket Types for: ') }} {{ $event->title }}
            </h2>
            <a href="{{ route('organizer.events.tickets.create', $event) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                + Create Ticket Type
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($ticketTypes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">Name</th>
                                        <th class="px-6 py-3">Type</th>
                                        <th class="px-6 py-3">Price</th>
                                        <th class="px-6 py-3">Sold / Total</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ticketTypes as $ticket)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {{ $ticket->name }}
                                            </td>
                                            <td class="px-6 py-4 capitalize">
                                                {{ str_replace('_', ' ', $ticket->type) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($ticket->price > 0)
                                                    ₹{{ number_format($ticket->price, 2) }}
                                                @else
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Free</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $ticket->quantity_sold }} / {{ $ticket->quantity_total }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($ticket->is_active)
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right flex justify-end space-x-2">
                                                <a href="{{ route('organizer.events.tickets.edit', [$event, $ticket]) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                                <form action="{{ route('organizer.events.tickets.destroy', [$event, $ticket]) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No ticket types</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new ticket type.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-6 flex justify-between">
                <a href="{{ route('organizer.events.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Back to Events</a>
            </div>
        </div>
    </div>
</x-organizer-layout>
