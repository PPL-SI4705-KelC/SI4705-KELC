<x-app-layout>
    <x-slot name="title">Create Climate Article</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Create Climate Article ✍️</h1>
            <p class="text-sm text-content-muted">Write and publish an official educational article directly</p>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" class="max-w-3xl mx-auto space-y-6 animate-fade-in">
        @csrf
        <div class="card space-y-5">
            <div>
                <label for="title" class="form-label">Article Title</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required class="form-input" placeholder="e.g. 10 Simple Ways to Reduce Household Electricity">
                <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
            </div>

            <div>
                <label for="excerpt" class="form-label">Excerpt <span class="text-content-muted font-normal">(optional summary)</span></label>
                <textarea id="excerpt" name="excerpt" rows="2" class="form-input" placeholder="A brief introductory summary of this article...">{{ old('excerpt') }}</textarea>
                <x-input-error :messages="$errors->get('excerpt')" class="mt-1.5" />
            </div>

            <div>
                <label for="content" class="form-label">Article Body Content</label>
                <textarea id="content" name="content" rows="14" required class="form-input font-sans text-sm" placeholder="Write the main body of the climate education article here (min 50 characters)...">{{ old('content') }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-1.5" />
            </div>

            <div>
                <label for="cover_image" class="form-label">Cover Image <span class="text-content-muted font-normal">(recommended 800x400px)</span></label>
                <input id="cover_image" type="file" name="cover_image" accept="image/*" class="form-input">
                <x-input-error :messages="$errors->get('cover_image')" class="mt-1.5" />
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary py-3 px-8">Publish Directly</button>
            <a href="{{ route('admin.blogs', ['status' => 'approved']) }}" class="btn-ghost py-3 px-6 border border-gray-200">Cancel</a>
        </div>
    </form>
</x-app-layout>
