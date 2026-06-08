<x-app-layout>
    <x-slot name="title">Manage Quizzes</x-slot>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-content">Quiz Management</h1>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
        {{-- Add New Question Form (Takes 1 column on large screens) --}}
        <div class="card h-fit">
            <h3 class="font-bold text-lg text-content mb-4">Add New Question</h3>
            <form method="POST" action="{{ route('admin.quizzes.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Question</label>
                    <textarea name="question" rows="2" required class="form-input" placeholder="Enter the quiz question..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @for($i = 0; $i < 4; $i++)
                    <div>
                        <label class="form-label">Option {{ $i + 1 }}</label>
                        <input type="text" name="options[]" required class="form-input" placeholder="Answer option {{ $i + 1 }}">
                    </div>
                    @endfor
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="form-label">Correct Answer</label>
                        <select name="correct_answer" class="form-input">
                            <option value="0">Option 1</option>
                            <option value="1">Option 2</option>
                            <option value="2">Option 3</option>
                            <option value="3">Option 4</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="climate" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Difficulty</label>
                        <select name="difficulty" class="form-input">
                            <option value="easy">Easy</option>
                            <option value="medium" selected>Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Add Question</button>
            </form>
        </div>

        {{-- Questions List (Takes 2 columns on large screens) --}}
        <div class="lg:col-span-2 space-y-4">
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

                <div class="space-y-3">
                    @forelse($quizzes as $quiz)
                    <div class="p-4 rounded-2xl bg-gray-50/50 border border-gray-100 flex items-start justify-between gap-4">
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
                            <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" onsubmit="return confirm('Delete this question permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600 text-sm">Delete</button>
                    </form>
                </div>
                @endforeach
            </div>
            {{ $quizzes->links() }}
        </div>
    </div>
</x-app-layout>
