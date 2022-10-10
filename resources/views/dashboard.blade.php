<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @foreach($blackholeds as $user)

        <div class="border-2 border-sky-500 bg-white">
            <img src="{{ $user->image_url }}" alt="{{ $user->login }}">
            {{ $user->name }}
            {{ $user->blackholed_at }}
        </div>

        @endforeach

    </div>

    </div>
</x-app-layout>
