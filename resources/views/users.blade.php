<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
    
        <div class="sm:px-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white  lg:flex">

                <table class="w-full">
                @foreach($users as $user)
                <tr>
                    <td class="w-16"><img class="" src="{{ $user->image_url }}" style="width:50px;" alt=""></td>
                    <td class="">{{ $user->user_id           }}</td>
                    <td class="">{{ $user->name              }}</td>
                    <td class="">{{ $user->login             }}</td>
                    <td class="">{{ $user->level             }}</td>
                    <td class="">{{ $user->begin_at          }}</td>
{{--                <td class="">{{ $user->end_at            }}</td> --}}
                    <td class="">{{ $user->blackholed_at     }}</td>
                    <td class="">{{ $user->has_coalition     }}</td>
                    <td class="">{{ $user->created_at        }}</td>
                    <td class="">{{ $user->updated_at        }}</td>
                    <td class="">{{ $user->email             }}</td>
                    <td class="">{{ $user->email_verified_at }}</td>
                    <td class="">{{ $user->password          }}</td>
                    <td class="">{{ $user->remember_token    }}</td>
                    <td class="">{{ $user->user42_id         }}</td>
                    <td class="text-right">{{ $user->pool_month        }} {{ $user->pool_year         }}</td>
                    <td class="">{{ $user->correction_point  }}</td>
                    <td class="">{{ $user->wallet            }}</td>
                    <td class="">{{ $user->alumni            }}</td>
                </tr>
                @endforeach
                </table>
                    
                </div>
            </div>
        </div>

    

    </div>
</x-app-layout>
