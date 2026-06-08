<x-app-layout>
    <x-slot name="title">Write Blog</x-slot>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-content">Write a Blog Post ✍️</h1>
    </x-slot>

    <form method="POST" action="{{ route('blogs.store') }}" enctype="multipart/form-data" class="max-w-2xl space-y-5 animate-fade-in">
        @csrf
        <div class="card space-y-5">
            <div>
                <label for="title" class="form-label">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required class="form-input" placeholder="An engaging title about climate action">
                <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
            </div>
            <div>
                <label for="excerpt" class="form-label">Excerpt <span class="text-content-muted font-normal">(optional)</span></label>
                <textarea id="excerpt" name="excerpt" rows="2" class="form-input" placeholder="A brief summary...">{{ old('excerpt') }}</textarea>
            </div>
            <div>
                <label for="content" class="form-label">Content</label>
                <textarea id="content" name="content" rows="12" required class="form-input" placeholder="Share your climate story (min 50 characters)...">{{ old('content') }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-1.5" />
            </div>
            <div>
                <label for="cover_image" class="form-label">Cover Image <span class="text-content-muted font-normal">(optional)</span></label>
                <input id="cover_image" type="file" name="cover_image" accept="image/*" class="form-input">
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary py-3 px-8">Submit for Review</button>
            <span class="text-xs text-content-muted">Your blog will be reviewed by an admin before publishing.</span>
        </div>
    </form>
</x-app-layout>
