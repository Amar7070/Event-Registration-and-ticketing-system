<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Active Sessions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Browser Sessions') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Manage and log out your active sessions on other browsers and devices.') }}
                        </p>
                    </header>

                    <div class="mt-5 space-y-4">
                        @foreach ($sessions as $session)
                            <div class="flex items-center">
                                <div>
                                    <svg class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm text-gray-600">
                                        {{ $session->user_agent }}
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">
                                            {{ $session->ip_address }},
                                            @if ($session->is_current_device)
                                                <span class="text-green-500 font-semibold">{{ __('This device') }}</span>
                                            @else
                                                {{ __('Last active') }} {{ $session->last_active }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center mt-5">
                        <form method="POST" action="{{ route('profile.sessions.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <div class="flex items-center">
                                <x-text-input type="password" name="password" class="mr-3 block w-3/4" placeholder="{{ __('Password') }}" required />
                                <x-primary-button>
                                    {{ __('Log Out Other Devices') }}
                                </x-primary-button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </form>
                    </div>

                    @if (session('status'))
                        <p class="mt-2 text-sm text-green-600">
                            {{ session('status') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
