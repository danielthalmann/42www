<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
    
        <div class="lg:grid lg:grid-cols-3 gap-4">

            <div class="sm:px-6 col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        You're logged in!
                    </div>
                </div>
            </div>

            <div class="sm:px-6">

                <h2 class="font-semibold text-xl text-gray-100 leading-tight mb-2">Next Blackhole</h2>
            
                @foreach($blackholeds as $user)
                <figure class="md:flex rounded-xl p-8 mb-2 md:p-0 bg-slate-800">
                    <img class="max-h-24 md:h-auto md:rounded-none rounded-full" src="{{ $user->image_url }}" alt="">
                    <div class="pt-6 md:p-5 text-center md:text-left space-y-4">
                        <figcaption class="font-medium">
                            <div class="text-sky-500 dark:text-sky-400">
                                {{ $user->name }}
                            </div>
                            <div class="text-slate-500">
                            {{ $user->blackholed_at->format('d.m.Y') }}
                            </div>
                        </figcaption>
                    </div>
                </figure>
                @endforeach

            </div>

        </div>

    </div>
</x-app-layout>
