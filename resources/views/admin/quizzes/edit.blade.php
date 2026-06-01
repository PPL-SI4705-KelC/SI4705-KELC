<x-app-layout>
    <x-slot name="title">Edit Quiz Question</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">Edit Quiz Question ⚙️</h1>
            <p class="text-sm text-content-muted">Modify quiz details, difficulty, or category</p>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto animate-fade-in">
        <div class="card">
            <form method="POST" action="{{ route('admin.quizzes.update', $quiz) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label">Question Text</label>
                    <textarea name="question" rows="3" required class="form-input" placeholder="Enter the question...">{{ old('question', $quiz->question) }}</textarea>
                    <x-input-error :messages="$errors->get('question')" class="mt-1" />
                </div>

                <div class="space-y-3">
                    <label class="form-label">Answer Options</label>
                    @foreach($quiz->options as $index => $option)
                    <div>
                        <label class="text-[11px] font-bold text-gray-400 uppercase block mb-1">Option {{ $index + 1 }}</label>
                        <input type="text" name="options[]" value="{{ old('options.'.$index, $option) }}" required class="form-input" placeholder="Option {{ $index + 1 }}">
                    </div>
                    @endforeach
                    <x-input-error :messages="$errors->get('options')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Correct Answer</label>
                        <select name="correct_answer" class="form-input">
                            <option value="0" {{ old('correct_answer', $quiz->correct_answer) === 0 ? 'selected' : '' }}>Option 1</option>
                            <option value="1" {{ old('correct_answer', $quiz->correct_answer) === 1 ? 'selected' : '' }}>Option 2</option>
                            <option value="2" {{ old('correct_answer', $quiz->correct_answer) === 2 ? 'selected' : '' }}>Option 3</option>
                            <option value="3" {{ old('correct_answer', $quiz->correct_answer) === 3 ? 'selected' : '' }}>Option 4</option>
                        </select>
                        <x-input-error :messages="$errors->get('correct_answer')" class="mt-1" />
                    </div>

                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input">
                            <option value="transport" {{ old('category', $quiz->category) === 'transport' ? 'selected' : '' }}>transport</option>
                            <option value="energy" {{ old('category', $quiz->category) === 'energy' ? 'selected' : '' }}>energy</option>
                            <option value="consumption" {{ old('category', $quiz->category) === 'consumption' ? 'selected' : '' }}>consumption</option>
                            <option value="climate" {{ old('category', $quiz->category) === 'climate' ? 'selected' : '' }}>climate</option>
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Difficulty</label>
                        <select name="difficulty" class="form-input">
                            <option value="easy" {{ old('difficulty', $quiz->difficulty) === 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="medium" {{ old('difficulty', $quiz->difficulty) === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="hard" {{ old('difficulty', $quiz->difficulty) === 'hard' ? 'selected' : '' }}>Hard</option>
                        </select>
                        <x-input-error :messages="$errors->get('difficulty')" class="mt-1" />
                    </div>

                    <div>
                        <label class="form-label">Is Active</label>
                        <select name="is_active" class="form-input">
                            <option value="1" {{ old('is_active', $quiz->is_active) ? 'selected' : '' }}>Active (Enabled)</option>
                            <option value="0" {{ !old('is_active', $quiz->is_active) ? 'selected' : '' }}>Inactive (Disabled)</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-1" />
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-primary py-2.5 px-6">Save Changes</button>
                    <a href="{{ route('admin.quizzes') }}" class="btn-ghost py-2.5 px-5 border border-gray-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
