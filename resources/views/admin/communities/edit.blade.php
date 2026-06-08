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
                        <label class="form-label">New Cover Image</label>
                        
                        <!-- Custom Interactive Upload Zone -->
                        <div id="upload-zone" class="relative group cursor-pointer border-2 border-dashed border-gray-200 hover:border-[#2D5A4C] rounded-2xl p-6 bg-gray-50/50 hover:bg-emerald-50/10 transition-all duration-300 flex flex-col items-center justify-center text-center overflow-hidden min-h-[160px] max-w-xs">
                            <!-- Hidden real file input -->
                            <input id="cover_image" type="file" name="cover_image" accept="image/*" class="sr-only">
                            
                            <!-- Upload Prompt -->
                            <div id="upload-prompt" class="flex flex-col items-center gap-2 transition-all duration-300">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gray-150 flex items-center justify-center text-gray-400 group-hover:text-[#2D5A4C] group-hover:scale-105 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
                                    </svg>
                                </div>
                                <p class="text-[11px] font-bold text-gray-700 mt-1">Choose a new image</p>
                                <p class="text-[9px] text-gray-400 font-medium">PNG, JPG, JPEG, WEBP</p>
                            </div>

                            <!-- Image Preview Overlay -->
                            <div id="image-preview-container" class="absolute inset-0 hidden z-10 w-full h-full bg-gray-50">
                                <img id="image-preview" src="#" alt="Cover Image Preview" class="w-full h-full object-cover">
                                <!-- Overlay on hover -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col items-center justify-center text-white gap-1 select-none">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                    </svg>
                                    <span class="text-[10px] font-bold">Change Image</span>
                                </div>
                                <!-- Remove button -->
                                <button type="button" id="remove-preview-btn" class="absolute top-2 right-2 bg-black/50 hover:bg-red-500 text-white rounded-full p-1.5 transition active:scale-95 shadow-md flex items-center justify-center z-20" title="Remove image">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadZone = document.getElementById('upload-zone');
            const fileInput = document.getElementById('cover_image');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImg = document.getElementById('image-preview');
            const removeBtn = document.getElementById('remove-preview-btn');

            uploadZone.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    clearPreview();
                }
            });

            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // Stop click from bubbling to uploadZone
                clearPreview();
            });

            function clearPreview() {
                fileInput.value = '';
                previewImg.src = '#';
                previewContainer.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>
