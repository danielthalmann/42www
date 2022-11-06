<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 lg:flex  max-w-7xl mx-auto">

        <div class="lg:w-2/3 2xl:w-3/4">

            <div class="sm:px-3">
                <h2 class="font-semibold text-xl text-gray-100 leading-tight mb-2">Your score</h2>
            </div>
            
            <div class="grid sm:grid-cols-3 lg:grid-cols-4">

                <div class="sm:px-3 mb-5">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white">
                            <span class="text-xl text-sky-400">{{ $cuser->wallet }}</span>
                            <br> money
                        </div>
                    </div>
                </div>

                <div class="sm:px-3 mb-5">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white">
                            <span class="text-xl text-sky-400">{{ $cuser->correction_point }}</span>
                            <br> correction point 
                        </div>
                    </div>
                </div>

                <div class="sm:px-3 mb-5">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white">
                            <span class="text-xl text-sky-400">{{ $cursus->cursus_name }}</span>
                            <br> current cursus
                        </div>
                    </div>
                </div>


                <div class="sm:px-3 mb-5">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white">
                            <span class="text-xl text-sky-400">{{ $projectCount }} / {{ $projectCursusCount }} </span>
                            <br> finished project
                        </div>
                    </div>
                </div>


                <div class="sm:px-3 mb-5">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white">
                            <span class="text-xl text-sky-400">{{ $projectInprogressCount }}</span>
                            <br> project in progress
                        </div>
                    </div>
                </div>


                <div class="sm:px-3 mb-5">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white">
                            <span class="text-xl text-sky-400">{{ $projectAvg }} / {{ $projectAvgCursus }}</span>
                            <br> average score
                        </div>
                    </div>
                </div>


                <div class="sm:px-3 mb-5">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white">
                            <span class="text-xl text-sky-400">{{ $projectLast->name }}</span>
                            <br> last project
                        </div>
                    </div>
                </div>

            </div>
           
            <div class="flex">

                <div class="sm:px-3 w-1/3">
                    <h2 class="font-semibold text-xl text-gray-100 leading-tight mb-2">Best Level</h2>

                    <figure class="overflow-hidden md:flex rounded-xl p-8 mb-2 md:p-0 bg-slate-800">
                        <img class="max-h-24 md:h-auto mx-auto md:mx-0" src="{{ $bestLevel->image_url_small }}" alt="">
                        <div class="pt-6 md:p-5 text-center md:text-left space-y-4">
                            <figcaption class="font-medium">
                                <div class="text-sky-500 dark:text-sky-400">
                                    {{ $bestLevel->name }}
                                </div>
                                <div class="text-slate-500">
                                {{ $bestLevel->level}} level
                                </div>
                            </figcaption>
                        </div>
                    </figure>
                </div>

                <div class="sm:px-3 w-1/3">
                    <h2 class="font-semibold text-xl text-gray-100 leading-tight mb-2">Best point</h2>

                    <figure class="overflow-hidden md:flex rounded-xl p-8 mb-2 md:p-0 bg-slate-800">
                        <img class="max-h-24 md:h-auto mx-auto md:mx-0" src="{{ $bestPoint->image_url_small }}" alt="">
                        <div class="pt-6 md:p-5 text-center md:text-left space-y-4">
                            <figcaption class="font-medium">
                                <div class="text-sky-500 dark:text-sky-400">
                                    {{ $bestPoint->name }}
                                </div>
                                <div class="text-slate-500">
                                {{ $bestPoint->correction_point }} points
                                </div>
                            </figcaption>
                        </div>
                    </figure>
                </div>

                <div class="sm:px-3 w-1/3">
                    <h2 class="font-semibold text-xl text-gray-100 leading-tight mb-2">Rich</h2>

                    <figure class="overflow-hidden md:flex rounded-xl p-8 mb-2 md:p-0 bg-slate-800">
                        <img class="max-h-24 md:h-auto mx-auto md:mx-0" src="{{ $richMen->image_url_small }}" alt="">
                        <div class="pt-6 md:p-5 text-center md:text-left space-y-4">
                            <figcaption class="font-medium">
                                <div class="text-sky-500 dark:text-sky-400">
                                    {{ $richMen->name }}
                                </div>
                                <div class="text-slate-500">
                                {{ $richMen->wallet }} wallet
                                </div>
                            </figcaption>
                        </div>
                    </figure>
                </div>

            </div>
        </div>

        <div class="lg:w-1/3  2xl:w-1/4">

            <div class="sm:px-3">
                <h2 class="font-semibold text-xl text-gray-100 leading-tight mb-2">Next Blackhole</h2>
           
                @foreach($blackholeds as $user)
                <figure class="overflow-hidden md:flex rounded-xl p-8 mb-2 md:p-0 bg-slate-800">
                    <img class="max-h-24 md:h-auto mx-auto md:mx-0" src="{{ $user->image_url_small }}" alt="">
                    <div class="pt-6 md:p-5 text-center md:text-left space-y-4">
                        <figcaption class="font-medium">
                            <div class="text-sky-500 dark:text-sky-400">
                                {{ $user->name }} (<small class="text-white">{{ $user->level }}</small>)
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

    </div>
</x-app-layout>
