<x-app-layout>
    <x-slot name="title">Manage Quizzes</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Quiz Management</h1>
            <p class="text-sm text-content-muted">Manage the daily climate questions pool</p>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in" x-data="{
        selectedIds: [],
        selectAll: false,
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedIds = Array.from(document.querySelectorAll('.quiz-checkbox')).map(cb => parseInt(cb.value));
            } else {
                this.selectedIds = [];
            }
        },
        updateSelectAll() {
            const total = document.querySelectorAll('.quiz-checkbox').length;
            this.selectAll = this.selectedIds.length > 0 && this.selectedIds.length === total;
        }
    }">
        {{-- Add New Question Form (Takes 1 column on large screens) --}}
        <div class="card h-fit">
            <h3 class="font-bold text-lg text-content mb-4">Add New Question</h3>
            <form method="POST" action="{{ route('admin.quizzes.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Question</label>
                    <textarea name="question" rows="3" required class="form-input text-xs" placeholder="e.g. Which of the following has the highest carbon footprint?"></textarea>
                    <x-input-error :messages="$errors->get('question')" class="mt-1" />
                </div>
                
                <div class="space-y-2">
                    <label class="form-label">Options</label>
                    @for($i = 0; $i < 4; $i++)
                    <div>
                        <input type="text" name="options[]" required class="form-input text-xs py-2" placeholder="Option {{ $i + 1 }}">
                    </div>
                    @endfor
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Correct Answer</label>
                        <select name="correct_answer" class="form-input text-xs py-2">
                            <option value="0">Option 1</option>
                            <option value="1">Option 2</option>
                            <option value="2">Option 3</option>
                            <option value="3">Option 4</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input text-xs py-2">
                            <option value="transport">transport</option>
                            <option value="energy">energy</option>
                            <option value="consumption">consumption</option>
                            <option value="climate">climate</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Difficulty</label>
                    <select name="difficulty" class="form-input text-xs py-2">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary w-full py-2.5 mt-2">Add Question</button>
            </form>
        </div>

        {{-- Questions List (Takes 2 columns on large screens) --}}
        <div class="lg:col-span-2 space-y-4">
            <!-- Bulk Action Bar -->
            <div x-show="selectedIds.length > 0" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                 class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center justify-between shadow-sm select-none"
                 style="display: none;">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                    <p class="text-sm font-bold text-red-700">
                        <span x-text="selectedIds.length"></span> questions selected
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.quizzes.bulk-destroy') }}" data-confirm="Are you sure you want to delete the selected questions? This action is permanent and cannot be undone." id="bulk-delete-form">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-sm hover:shadow active:scale-[0.98] transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Selected
                    </button>
                </form>
            </div>

            <div class="card p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-4">
                    <h3 class="font-bold text-lg text-content">All Questions ({{ $quizzes->total() }})</h3>
                    
                    <form method="GET" action="{{ route('admin.quizzes') }}" class="flex gap-2 w-full sm:w-72">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search questions..." class="form-input py-2 text-xs">
                        <button type="submit" class="btn-primary text-xs px-3">Search</button>
                        @if(request()->filled('search'))
                            <a href="{{ route('admin.quizzes') }}" class="btn-ghost text-xs px-2 flex items-center justify-center border border-gray-200">Reset</a>
                        @endif
                    </form>
                </div>

                @if($quizzes->count() > 0)
                <div class="flex items-center gap-3 px-4 py-2 bg-gray-50/50 rounded-xl border border-gray-100 text-xs font-bold text-gray-500 select-none mb-4">
                    <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="rounded border-gray-300 text-[#2D5A4C] focus:ring-[#2D5A4C]/20 w-4 h-4">
                    <span>Select All Questions on Page</span>
                </div>
                @endif

                <div class="space-y-3">
                    @forelse($quizzes as $quiz)
                    <div class="p-4 rounded-2xl bg-gray-50/50 border border-gray-100 flex items-start justify-between gap-4">
                        <input type="checkbox" value="{{ $quiz->id }}" x-model="selectedIds" @change="updateSelectAll()" class="quiz-checkbox mt-1 rounded border-gray-300 text-[#2D5A4C] focus:ring-[#2D5A4C]/20 w-4 h-4 shrink-0">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-content">{{ $quiz->question }}</p>
                            
                            {{-- Options Preview --}}
                            <div class="grid grid-cols-2 gap-2 mt-3 pl-3 border-l-2 border-primary/20">
                                @foreach($quiz->options as $index => $option)
                                    <p class="text-xs {{ $index === $quiz->correct_answer ? 'text-emerald-600 font-bold' : 'text-gray-500' }}">
                                        {{ $index + 1 }}. {{ $option }}
                                        @if($index === $quiz->correct_answer) ✓ @endif
                                    </p>
                                @endforeach
                            </div>

                            <div class="flex items-center gap-2 mt-3.5">
                                <span class="bg-primary/5 text-primary text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $quiz->category }}</span>
                                <span class="badge text-[10px] {{ $quiz->difficulty === 'hard' ? 'badge-danger' : ($quiz->difficulty === 'medium' ? 'badge-accent' : 'badge-secondary') }}">{{ $quiz->difficulty }}</span>
                                @if(!$quiz->is_active)
                                    <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="text-xs font-semibold text-[#2D5A4C] hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" data-confirm="Delete this question permanently?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12"><p class="text-content-muted">No quiz questions found.</p></div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $quizzes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
