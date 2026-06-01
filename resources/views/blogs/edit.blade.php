<x-app-layout>
    <x-slot name="title">Edit Post — {{ $blog->title }}</x-slot>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div>
                <a href="{{ route('blogs.my') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2D5A4C] font-medium mb-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Back to My Blogs
                </a>
                <h1 class="text-[28px] font-black text-gray-900 tracking-tight leading-none">Edit Post</h1>
                <p class="text-sm text-gray-400 font-medium mt-2">Update your blog post content and settings.</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Status Badge --}}
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold 
                    {{ $blog->status === 'published' ? 'bg-[#e2f0ea] text-[#2D5A4C]' : 
                      ($blog->status === 'rejected' ? 'bg-red-50 text-red-600' : 'bg-[#fff8e1] text-[#E65100]') }}">
                    {{ ucfirst($blog->status) }}
                </span>
                <button type="button" onclick="document.getElementById('form-action').value='pending'; document.getElementById('blog-edit-form').requestSubmit()"
                        class="inline-flex items-center gap-2 bg-[#2D5A4C] hover:bg-[#1e4237] text-white font-bold text-sm px-6 py-3 rounded-xl transition-all shadow-sm hover:shadow-md active:scale-[0.97]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                    Update Post
                </button>
            </div>
        </div>
    </x-slot>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-6 left-1/2 -translate-x-1/2 z-50 px-6 py-3 bg-red-500 text-white text-sm font-bold rounded-full shadow-lg">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('blogs.update', $blog) }}"
          enctype="multipart/form-data"
          class="max-w-4xl mx-auto space-y-6 animate-fade-in pb-12 mt-6"
          id="blog-edit-form"
          x-data="blogEditForm()">
        @csrf
        @method('PUT')

        {{-- Hidden action field --}}
        <input type="hidden" name="action" id="form-action" value="pending">

        {{-- ══ Main Form Card ══════════════════════════════════════ --}}
        <div class="card space-y-6">

            {{-- Category --}}
            <div>
                <label for="category" class="form-label flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#2D5A4C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                    </svg>
                    Category
                </label>
                <select id="category" name="category" class="form-input max-w-xs" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category', $blog->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category')" class="mt-1.5" />
            </div>

            <hr class="border-gray-100">

            {{-- Title --}}
            <div>
                <label for="title" class="form-label flex items-center gap-2">
                    <span class="text-[#2D5A4C] font-black text-base">H</span>
                    Blog Title
                </label>
                <input id="title"
                       type="text"
                       name="title"
                       value="{{ old('title', $blog->title) }}"
                       class="form-input text-lg font-bold"
                       placeholder="Enter an engaging title for your blog post..."
                       required>
                <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
            </div>

            {{-- Short Description --}}
            <div>
                <label for="short_description" class="form-label flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#2D5A4C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                    </svg>
                    Short Description / Excerpt
                </label>
                <textarea id="short_description"
                          name="short_description"
                          rows="3"
                          class="form-input"
                          placeholder="Write a brief description or excerpt (150-200 characters recommended)...">{{ old('short_description', $blog->short_description) }}</textarea>
                <x-input-error :messages="$errors->get('short_description')" class="mt-1.5" />
            </div>

            {{-- Content --}}
            <div>
                <label for="content" class="form-label flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#2D5A4C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                    </svg>
                    Blog Content
                </label>
                <input id="content" type="hidden" name="content" value="{{ old('content', $blog->content) }}">
                <trix-editor input="content"
                             x-ref="trixEditor"
                             @trix-change="contentText = $refs.trixEditor.editor.toString()"
                             class="form-input trix-content font-sans text-sm leading-relaxed min-h-[350px] bg-white border border-gray-200 rounded-2xl p-4 focus:outline-none focus:ring-2 focus:ring-[#2D5A4C]/20 focus:border-[#2D5A4C]"
                             placeholder="Start writing your blog post here... Share your story, insights, and ideas with your readers."></trix-editor>

                <div class="flex justify-between mt-1.5">
                    <p class="text-[11px] font-medium" :class="contentText.length >= 300 && contentText.length <= 10000 ? 'text-gray-400' : 'text-red-500'">
                        Character Range: 300 - 10,000 (Current: <span x-text="contentText.length"></span> characters)
                    </p>
                    <p class="text-[11px] text-gray-400 font-medium"><span x-text="wordCount"></span> words</p>
                </div>
                <x-input-error :messages="$errors->get('content')" class="mt-1.5" />
            </div>
        </div>

        {{-- ══ Featured Image Card ═════════════════════════════════ --}}
        <div class="card space-y-4">
            <label class="form-label flex items-center gap-2">
                <svg class="w-4 h-4 text-[#2D5A4C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v13.5a1.5 1.5 0 001.5 1.5z"/>
                </svg>
                Cover Image
            </label>

            {{-- Current Image Preview --}}
            @if($blog->featured_image)
            <div class="mb-4" x-show="!imageChanged" id="current-image-preview">
                <p class="text-xs text-gray-500 font-medium mb-2">Current Image:</p>
                <div class="relative inline-block">
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Current featured image" class="max-h-48 rounded-xl shadow-sm border border-gray-100 object-cover">
                </div>
            </div>
            @endif

            {{-- Upload Zone --}}
            <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center hover:border-[#2D5A4C]/40 transition-colors cursor-pointer bg-gray-50/50"
                 @click="$refs.fileInput.click()"
                 @dragover.prevent="isDragging = true"
                 @dragleave.prevent="isDragging = false"
                 @drop.prevent="handleDrop($event)"
                 :class="isDragging ? 'border-[#2D5A4C] bg-[#e2f0ea]/30' : ''"
                 id="upload-zone">

                <template x-if="imagePreview">
                    <div class="mb-4">
                        <p class="text-xs text-[#2D5A4C] font-bold mb-2">New Image Preview:</p>
                        <img :src="imagePreview" alt="New preview" class="max-h-48 mx-auto rounded-xl shadow-sm border border-gray-100 object-cover">
                        <button type="button" @click.stop="removeImage()" class="mt-2 text-xs text-red-500 hover:text-red-700 font-bold">
                            Remove New Image
                        </button>
                    </div>
                </template>

                <template x-if="!imagePreview">
                    <div>
                        <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-[#e2f0ea] flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#2D5A4C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-[#2D5A4C]">{{ $blog->featured_image ? 'Click to replace image' : 'Click to upload or drag and drop' }}</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG or GIF (max. 5MB)</p>
                    </div>
                </template>

                <input type="file"
                       name="featured_image"
                       accept="image/jpeg,image/png,image/gif,image/webp"
                       class="hidden"
                       x-ref="fileInput"
                       @change="handleFileSelect($event)"
                       id="featured-image-input">
            </div>
            <x-input-error :messages="$errors->get('featured_image')" class="mt-1.5" />
        </div>

        {{-- ══ Tags Card ═══════════════════════════════════════════ --}}
        <div class="card space-y-4">
            <label for="tags" class="form-label flex items-center gap-2">
                <svg class="w-4 h-4 text-[#2D5A4C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                </svg>
                Tags
            </label>
            <input id="tags"
                   type="text"
                   name="tags"
                   value="{{ old('tags', $blog->tags) }}"
                   class="form-input"
                   placeholder="Add tags separated by commas (e.g., technology, coding, tutorial)">
            <p class="text-[11px] text-gray-400 font-medium">Tags help readers find your content</p>
            <x-input-error :messages="$errors->get('tags')" class="mt-1.5" />
        </div>

        {{-- ══ Bottom Actions ══════════════════════════════════════ --}}
        <div class="flex items-center justify-between pt-2">
            <div class="flex items-center gap-3">
                @if($blog->status === 'draft')
                <button type="submit"
                        onclick="document.getElementById('form-action').value='draft'"
                        class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 font-bold text-sm px-5 py-3 rounded-xl hover:bg-gray-50 transition-all shadow-sm"
                        id="btn-save-draft">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
                    </svg>
                    Save Draft
                </button>
                @endif
                <a href="{{ route('blogs.my') }}"
                   class="inline-flex items-center gap-2 text-gray-500 font-bold text-sm px-5 py-3 rounded-xl hover:bg-gray-50 transition-all border border-gray-200"
                   id="btn-cancel">
                    Cancel
                </a>
            </div>

            <div class="flex items-center gap-3">
                @if($blog->status === 'rejected')
                <span class="text-xs text-red-500 font-medium hidden sm:inline-block">Updating will resubmit for review.</span>
                @endif
                <button type="submit"
                        onclick="document.getElementById('form-action').value='pending'"
                        class="inline-flex items-center gap-2 bg-[#2D5A4C] hover:bg-[#1e4237] text-white font-bold text-sm px-6 py-3 rounded-xl transition-all shadow-sm hover:shadow-md active:scale-[0.97]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                    Update Post
                </button>
            </div>
        </div>
    </form>

    @push('scripts')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <script>
        // Define custom Web Components for alignments
        if (!window.customElements.get('align-left')) {
            window.customElements.define('align-left', class extends HTMLElement {
                connectedCallback() {
                    this.style.display = 'block';
                    this.style.textAlign = 'left';
                }
            });
        }
        if (!window.customElements.get('align-center')) {
            window.customElements.define('align-center', class extends HTMLElement {
                connectedCallback() {
                    this.style.display = 'block';
                    this.style.textAlign = 'center';
                }
            });
        }
        if (!window.customElements.get('align-right')) {
            window.customElements.define('align-right', class extends HTMLElement {
                connectedCallback() {
                    this.style.display = 'block';
                    this.style.textAlign = 'right';
                }
            });
        }
        if (!window.customElements.get('align-justify')) {
            window.customElements.define('align-justify', class extends HTMLElement {
                connectedCallback() {
                    this.style.display = 'block';
                    this.style.textAlign = 'justify';
                }
            });
        }

        // Configure custom Trix attributes before initializing
        if (window.Trix) {
            Trix.config.textAttributes.underline = {
                tagName: "u",
                inheritable: true,
                parser: function(element) {
                    return element.tagName === "U" || element.style.textDecoration === "underline";
                }
            };
            
            Trix.config.blockAttributes.alignLeft = {
                tagName: "align-left",
                parse: true,
                nestable: false,
                exclusive: true
            };
            Trix.config.blockAttributes.alignCenter = {
                tagName: "align-center",
                parse: true,
                nestable: false,
                exclusive: true
            };
            Trix.config.blockAttributes.alignRight = {
                tagName: "align-right",
                parse: true,
                nestable: false,
                exclusive: true
            };
            Trix.config.blockAttributes.alignJustify = {
                tagName: "align-justify",
                parse: true,
                nestable: false,
                exclusive: true
            };
        }

        // Listen to trix-initialize to add buttons to the toolbar
        document.addEventListener("trix-initialize", function(event) {
            const toolbar = event.target.toolbarElement;
            
            // 1. Underline Button
            const textTools = toolbar.querySelector(".trix-button-group--text-tools");
            if (textTools && !textTools.querySelector("[data-trix-attribute='underline']")) {
                const underlineBtn = `
                    <button type="button" class="trix-button trix-button--icon trix-button--icon-underline" data-trix-attribute="underline" data-trix-key="u" title="Underline" tabindex="-1">
                        <svg class="w-3.5 h-3.5 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width: 14px; height: 14px; margin: auto;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75v5.25a5.25 5.25 0 1 1-10.5 0V6.75m2.25 13.5h6" />
                        </svg>
                    </button>
                `;
                const italicBtn = textTools.querySelector("[data-trix-attribute='italic']");
                if (italicBtn) {
                    italicBtn.insertAdjacentHTML("afterend", underlineBtn);
                } else {
                    textTools.insertAdjacentHTML("beforeend", underlineBtn);
                }
            }
            
            // 2. Alignment Buttons
            const blockTools = toolbar.querySelector(".trix-button-group--block-tools");
            if (blockTools && !blockTools.querySelector("[data-trix-attribute='alignCenter']")) {
                const alignGroup = `
                    <span class="trix-button-group trix-button-group--alignment-tools">
                        <button type="button" class="trix-button trix-button--icon trix-button--icon-align-left" data-trix-attribute="alignLeft" title="Align Left" tabindex="-1">
                            <svg class="w-3.5 h-3.5 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 14px; height: 14px; margin: auto;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h12m-12 5.25h16.5" />
                            </svg>
                        </button>
                        <button type="button" class="trix-button trix-button--icon trix-button--icon-align-center" data-trix-attribute="alignCenter" title="Align Center" tabindex="-1">
                            <svg class="w-3.5 h-3.5 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 14px; height: 14px; margin: auto;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M5.25 12h13.5m-15 5.25h16.5" />
                            </svg>
                        </button>
                        <button type="button" class="trix-button trix-button--icon trix-button--icon-align-right" data-trix-attribute="alignRight" title="Align Right" tabindex="-1">
                            <svg class="w-3.5 h-3.5 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 14px; height: 14px; margin: auto;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M9 12h11.25m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                        <button type="button" class="trix-button trix-button--icon trix-button--icon-align-justify" data-trix-attribute="alignJustify" title="Justify" tabindex="-1">
                            <svg class="w-3.5 h-3.5 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 14px; height: 14px; margin: auto;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </span>
                `;
                blockTools.insertAdjacentHTML("afterend", alignGroup);
            }
        });
    </script>
    <style>
        trix-toolbar .trix-button-group--file-tools {
            display: none !important;
        }
        trix-editor {
            min-height: 350px !important;
            background-color: #fff !important;
        }
        .trix-button-group--alignment-tools {
            border-left: 1px solid #e2e8f0 !important;
            margin-left: 10px !important;
            padding-left: 5px !important;
        }
    </style>
    <script>
        function blogEditForm() {
            return {
                contentText: '',
                imagePreview: null,
                imageChanged: false,
                isDragging: false,

                init() {
                    this.$nextTick(() => {
                        if (this.$refs.trixEditor && this.$refs.trixEditor.editor) {
                            this.contentText = this.$refs.trixEditor.editor.toString();
                        }
                    });
                },

                get wordCount() {
                    if (!this.contentText) return 0;
                    return this.contentText.trim().split(/\s+/).filter(w => w.length > 0).length;
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) this.previewFile(file);
                },

                handleDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        this.$refs.fileInput.files = dt.files;
                        this.previewFile(file);
                    }
                },

                previewFile(file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Image must not exceed 5MB.');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreview = e.target.result;
                        this.imageChanged = true;
                    };
                    reader.readAsDataURL(file);
                },

                removeImage() {
                    this.imagePreview = null;
                    this.imageChanged = false;
                    this.$refs.fileInput.value = '';
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
