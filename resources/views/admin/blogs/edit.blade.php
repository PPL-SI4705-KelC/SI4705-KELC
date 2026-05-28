<x-app-layout>
    <x-slot name="title">Edit Article</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Edit Climate Article ⚙️</h1>
            <p class="text-sm text-content-muted">Update article content, metadata, or status</p>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data" class="max-w-3xl mx-auto space-y-6 animate-fade-in" x-data="{ status: '{{ old('status', $blog->status) }}' }">
        @csrf
        @method('PUT')
        
        <div class="card space-y-5">
            <div>
                <label for="title" class="form-label">Article Title</label>
                <input id="title" type="text" name="title" value="{{ old('title', $blog->title) }}" required class="form-input" placeholder="An engaging title about climate action">
                <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
            </div>

            <div>
                <label for="excerpt" class="form-label">Excerpt <span class="text-content-muted font-normal">(optional summary)</span></label>
                <textarea id="excerpt" name="excerpt" rows="2" class="form-input" placeholder="A brief summary...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                <x-input-error :messages="$errors->get('excerpt')" class="mt-1.5" />
            </div>

            <div>
                <label for="content" class="form-label">Article Content</label>
                <textarea id="content" name="content" rows="12" required class="form-input" placeholder="Share your climate story...">{{ old('content', $blog->content) }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-1.5" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" x-model="status" class="form-input">
                        <option value="pending">Pending Review</option>
                        <option value="approved">Approved & Published</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1.5" />
                </div>

                <div>
                    <label for="cover_image" class="form-label">Cover Image</label>
                    <input id="cover_image" type="file" name="cover_image" accept="image/*" class="form-input">
                    <x-input-error :messages="$errors->get('cover_image')" class="mt-1.5" />
                </div>
            </div>

            <div x-show="status === 'rejected'" style="display: none;">
                <label for="rejection_reason" class="form-label text-red-600">Rejection Reason</label>
                <textarea id="rejection_reason" name="rejection_reason" rows="2" class="form-input border-red-200 focus:border-red-500 focus:ring-red-100" placeholder="Provide a reason for rejecting this article...">{{ old('rejection_reason', $blog->rejection_reason) }}</textarea>
                <x-input-error :messages="$errors->get('rejection_reason')" class="mt-1.5" />
            </div>

            @if($blog->cover_image)
            <div class="pt-2">
                <label class="form-label">Current Cover Image</label>
                <div class="w-full max-w-xs h-32 rounded-xl overflow-hidden shadow-sm border border-gray-100">
                    <img src="{{ asset('storage/' . $blog->cover_image) }}" alt="" class="w-full h-full object-cover">
                </div>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary py-3 px-8">Save Changes</button>
            <a href="{{ route('admin.blogs', ['status' => $blog->status]) }}" class="btn-ghost py-3 px-6 border border-gray-200">Cancel</a>
        </div>
    </form>
</x-app-layout>
