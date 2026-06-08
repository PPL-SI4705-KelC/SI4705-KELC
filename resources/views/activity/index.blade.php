<x-app-layout>
    <x-slot name="title">Activity</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="w-full mx-auto sm:px-2 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Welcome to the Activity section!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
