<x-app-layout>
    <x-slot name="title">Create Community</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Create New Community 👥</h1>
            <p class="text-sm text-content-muted">Establish a new community channel for environmental advocacy</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto animate-fade-in">
        <div class="card">
            <form method="POST" action="{{ route('admin.communities.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="form-label">Community Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="e.g. Zero Waste Society">
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </div>

                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="5" class="form-input" placeholder="Write a description explaining the theme and objectives of this community...">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                </div>

                <div>
                    <label for="cover_image" class="form-label">Cover Image <span class="text-content-muted font-normal">(recommended 1200x600px)</span></label>
                    <input id="cover_image" type="file" name="cover_image" accept="image/*" class="form-input">
                    <x-input-error :messages="$errors->get('cover_image')" class="mt-1.5" />
                </div>

                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-primary py-2.5 px-6">Create Community</button>
                    <a href="{{ route('admin.communities') }}" class="btn-ghost py-2.5 px-5 border border-gray-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
