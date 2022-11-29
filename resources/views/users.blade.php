<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto">
    
        <div class="sm:px-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white flex  overflow-x-auto">

                <table class="table-auto border-collapse border border-slate-500">
                <thead>
                <tr>
                    <th class="    border border-slate-600 w-12">img</th>
                    {{--    <th class="p-4 border border-slate-600">user_id          </th> --}}
                    <th class="p-4 border border-slate-600">name             </th>
                    <th class="p-4 border border-slate-600">login            </th>
                    <th class="p-4 border border-slate-600">level            </th>
                    <th class="p-4 border border-slate-600">begin_at        </th>
{{--                <th class="p-4 border border-slate-600">end_at          </th> --}}
                    <th class="p-4 border border-slate-600">blackholed_at   </th>
                    <th class="p-4 border border-slate-600 text-right">pool_month_year</th>
                    <th class="p-4 border border-slate-600">corr_point  </th>
                    <th class="p-4 border border-slate-600">wallet            </th>
                    {{--    <th class="p-4 border border-slate-600">created_at</th> --}}
                    <th class="p-4 border border-slate-600">updated_at</th>
                </tr>
                </thead>
                <tbody>
                @php $counter = 0; @endphp
                @foreach($users as $user)
                <tr class="@if(\Carbon\Carbon::parse($user->blackholed_at)->lt(\Carbon\Carbon::now())) text-red-500 @endif">
                    <td class="    border border-slate-600 w-12" dtat-id="{{$user->user_id}}"><img src="{{ $user->image_url_small }}" alt=""></td>
        {{--        <td class="p-4 border border-slate-600">{{ $user->user_id           }}</td> --}}
                    <td class="p-4 border border-slate-600">#{{ ++$counter }} {{ $user->name              }}</td>
                    <td class="p-4 border border-slate-600"><a target="_blank" href="https://profile.intra.42.fr/users/{{ $user->login }}">{{ $user->login }}</a></td>
                    <td class="p-4 border border-slate-600">{{ $user->level             }}</td>
                    <td class="p-4 border border-slate-600">{{ $user->begin_at ? \Carbon\Carbon::parse($user->begin_at)->format('d.m.Y') : null         }}</td>
{{--                <td class="p-4 border border-slate-600">{{ $user->end_at ? $user->end_at->format('d.m.Y') : null           }}</td> --}}
                    <td class="p-4 border border-slate-600">{{ $user->blackholed_at ? \Carbon\Carbon::parse($user->blackholed_at)->format('d.m.Y') : null      }}</td>
                    <td class="p-4 border border-slate-600 text-right">{{ $user->pool_month        }} {{ $user->pool_year         }}</td>
                    <td class="p-4 border border-slate-600">{{ $user->correction_point  }}</td>
                    <td class="p-4 border border-slate-600">{{ $user->wallet            }}</td>
{{--                <td class="p-4 border border-slate-600">{{ $user->created_at->format('d.m.Y')        }}</td> --}}
                    <td class="p-4 border border-slate-600">{{ $user->updated_at->format('d.m.Y')        }}</td>
                </tr>
                @endforeach
                </tbody>
                </table>
                    
                </div>
            </div>
        </div>

    

    </div>
</x-app-layout>
