<x-app-layout>
    <x-slot name="title">Edit Blog</x-slot>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-content">Edit Blog Post</h1>
    </x-slot>
    <form method="POST" action="{{ route('blogs.update', $blog) }}" enctype="multipart/form-data" class="max-w-2xl space-y-5 animate-fade-in">
        @csrf @method('PUT')
        <div class="card space-y-5">
            <div>
                <label for="title" class="form-label">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title', $blog->title) }}" required class="form-input">
                <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
            </div>
            <div>
                <label for="excerpt" class="form-label">Excerpt</label>
                <textarea id="excerpt" name="excerpt" rows="2" class="form-input">{{ old('excerpt', $blog->excerpt) }}</textarea>
            </div>
            <div>
                <label for="content" class="form-label">Content</label>
                <textarea id="content" name="content" rows="12" required class="form-input">{{ old('content', $blog->content) }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-1.5" />
            </div>
            <div>
                <label for="cover_image" class="form-label">Cover Image</label>
                <input id="cover_image" type="file" name="cover_image" accept="image/*" class="form-input">
            </div>
        </div>
        <button type="submit" class="btn-primary py-3 px-8">Update Blog</button>
    </form>
</x-app-layout>
