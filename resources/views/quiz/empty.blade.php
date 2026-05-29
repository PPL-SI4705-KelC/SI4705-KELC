<x-app-layout>
    <x-slot name="title">No Quiz Available</x-slot>
    <div class="max-w-lg mx-auto text-center py-16 animate-fade-in">
        <span class="text-6xl">📚</span>
        <h2 class="text-xl font-bold text-content mt-4">No Quiz Questions Available</h2>
        <p class="text-content-muted mt-2">Check back later when new questions are added.</p>
        <a href="{{ route('dashboard') }}" class="btn-primary mt-6 inline-flex">Back to Dashboard</a>
    </div>
</x-app-layout>
