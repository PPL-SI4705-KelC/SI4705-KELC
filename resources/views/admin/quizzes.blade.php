<x-app-layout>
    <x-slot name="title">Manage Quizzes</x-slot>
    <x-slot name="header">
        <h1 class="text-xl font-bold text-content">Quiz Management</h1>
    </x-slot>

    <div class="max-w-4xl space-y-6 animate-fade-in">
        {{-- Add New Question --}}
        <div class="card">
            <h3 class="font-semibold text-content mb-4">Add New Question</h3>
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

        {{-- Questions List --}}
        <div class="card">
            <h3 class="font-semibold text-content mb-4">All Questions ({{ $quizzes->total() }})</h3>
            <div class="space-y-3">
                @foreach($quizzes as $quiz)
                <div class="p-4 rounded-xl bg-gray-50 flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-content">{{ $quiz->question }}</p>
                        <div class="flex gap-2 mt-2">
                            <span class="badge-primary text-[10px]">{{ $quiz->category }}</span>
                            <span class="badge text-[10px] {{ $quiz->difficulty === 'hard' ? 'badge-danger' : ($quiz->difficulty === 'medium' ? 'badge-accent' : 'badge-secondary') }}">{{ $quiz->difficulty }}</span>
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
