<x-app-layout>
    <x-slot name="title">Edit Community</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Edit Community 👥</h1>
            <p class="text-sm text-content-muted">Update community details or visibility status</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto animate-fade-in">
        <div class="card">
            <form method="POST" action="{{ route('admin.communities.update', $community) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="form-label">Community Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $community->name) }}" required class="form-input" placeholder="e.g. Zero Waste Society">
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </div>

                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="5" class="form-input" placeholder="Explain the theme and goals...">{{ old('description', $community->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="is_active" class="form-label">Active Status</label>
                        <select id="is_active" name="is_active" class="form-input">
                            <option value="1" {{ old('is_active', $community->is_active) ? 'selected' : '' }}>Active (Visible to users)</option>
                            <option value="0" {{ !old('is_active', $community->is_active) ? 'selected' : '' }}>Inactive (Hidden)</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="cover_image" class="form-label">Cover Image</label>
                        <input id="cover_image" type="file" name="cover_image" accept="image/*" class="form-input">
                        <x-input-error :messages="$errors->get('cover_image')" class="mt-1.5" />
                    </div>
                </div>

                @if($community->cover_image)
                <div class="pt-2">
                    <label class="form-label">Current Cover Image</label>
                    <div class="w-full max-w-xs h-32 rounded-xl overflow-hidden shadow-sm border border-gray-100">
                        <img src="{{ asset('storage/' . $community->cover_image) }}" alt="" class="w-full h-full object-cover">
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-primary py-2.5 px-6">Save Changes</button>
                    <a href="{{ route('admin.communities') }}" class="btn-ghost py-2.5 px-5 border border-gray-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
