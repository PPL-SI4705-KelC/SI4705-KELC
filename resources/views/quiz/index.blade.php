<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daily Quiz - {{ config('app.name', 'Act4Climate') }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-gray-900 bg-[#fafbfc]">

    <div x-data="quizComponent()" class="min-h-screen flex flex-col">
        
        {{-- Top Navigation & Progress --}}
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
            <div class="w-full px-6 lg:px-10 h-16 flex items-center justify-between">
                {{-- Left: Back + Logo (flush left) --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('emissions.index') }}" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors -ml-2">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                        <span class="font-extrabold text-[#2A5C4D] text-lg tracking-tight">Act4Climate</span>
                    </div>
                </div>
                
                {{-- Center: Progress Bar --}}
                <div class="flex-1 max-w-md mx-8 hidden md:flex flex-col items-center gap-1">
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-[#2A5C4D] h-full rounded-full transition-all duration-500 ease-out" :style="`width: ${((currentIndex + 1) / questions.length) * 100}%`"></div>
                    </div>
                    <span class="text-[11px] font-bold text-gray-400 tracking-wide" x-text="`${currentIndex + 1} / ${questions.length}`"></span>
                </div>

                {{-- Right: Question counter badge --}}
                <div class="hidden md:flex items-center">
                    <span class="inline-flex items-center gap-1.5 bg-[#f0faf5] text-[#2A5C4D] px-3 py-1.5 rounded-full text-xs font-bold border border-[#2A5C4D]/10">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Daily Quiz
                    </span>
                </div>
            </div>
            
            {{-- Mobile Progress Bar (full width, more visible) --}}
            <div class="w-full bg-gray-100 h-2 md:hidden">
                <div class="bg-[#2A5C4D] h-full transition-all duration-500 ease-out" :style="`width: ${((currentIndex + 1) / questions.length) * 100}%`"></div>
            </div>
        </header>

        <main class="flex-1 w-full max-w-4xl mx-auto px-6 lg:px-10 py-10 flex flex-col items-center">
            
            {{-- Question --}}
            <div class="w-full mb-10 text-center">
                <h2 class="text-2xl md:text-3xl font-bold text-[#2A5C4D]" x-text="currentQuestion.question"></h2>
            </div>

            {{-- Options Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full max-w-3xl mb-12">
                <template x-for="(option, index) in currentQuestion.options" :key="index">
                    <button 
                        @click="answers[currentIndex] = index"
                        :class="answers[currentIndex] === index ? 'border-[#2A5C4D] ring-1 ring-[#2A5C4D] shadow-sm' : 'border-gray-200 hover:border-[#2A5C4D]/50 hover:bg-gray-50/50'"
                        class="flex items-center gap-4 p-5 rounded-[16px] border bg-white text-left transition-all duration-200">
                        
                        {{-- Icon wrapper --}}
                        <div 
                            :class="answers[currentIndex] === index ? 'bg-[#2A5C4D] text-white' : 'bg-[#e8f5e9] text-[#2A5C4D]'"
                            class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 transition-colors">
                            {{-- Generic Icon --}}
                            <svg x-show="currentQuestion.category === 'transport'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <svg x-show="currentQuestion.category === 'energy'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            <svg x-show="currentQuestion.category === 'consumption'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <svg x-show="!['transport', 'energy', 'consumption'].includes(currentQuestion.category)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>

                        <span class="font-semibold text-gray-900" x-text="option"></span>
                    </button>
                </template>
            </div>

            {{-- Fact Image Card --}}
            <div class="w-full max-w-3xl mt-auto relative rounded-2xl overflow-hidden h-40 shadow-sm">
                {{-- Dynamic Background Images --}}
                <img x-show="currentQuestion.category === 'transport'" src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&q=80&w=1000" class="absolute inset-0 w-full h-full object-cover">
                <img x-show="currentQuestion.category === 'energy'" src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&q=80&w=1000" class="absolute inset-0 w-full h-full object-cover">
                <img x-show="currentQuestion.category === 'consumption'" src="https://images.unsplash.com/photo-1498837167922-41c53bbf0558?auto=format&fit=crop&q=80&w=1000" class="absolute inset-0 w-full h-full object-cover">
                <img x-show="!['transport', 'energy', 'consumption'].includes(currentQuestion.category)" src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=1000" class="absolute inset-0 w-full h-full object-cover">
                
                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                
                {{-- Fact Text --}}
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <p class="text-white text-sm font-medium tracking-wide">
                        <span x-show="currentQuestion.category === 'transport'">"Transportation is responsible for nearly 30% of global greenhouse gas emissions."</span>
                        <span x-show="currentQuestion.category === 'energy'">"Switching to renewable energy can reduce your carbon footprint significantly."</span>
                        <span x-show="currentQuestion.category === 'consumption'">"Food accounts for about 25% of global greenhouse gas emissions."</span>
                        <span x-show="!['transport', 'energy', 'consumption'].includes(currentQuestion.category)">"Every small climate action adds up to a massive global impact."</span>
                    </p>
                </div>
            </div>

            {{-- Bottom Navigation --}}
            <div class="w-full max-w-3xl flex items-center justify-between mt-8">
                <button 
                    @click="prevQuestion()"
                    :class="currentIndex === 0 ? 'opacity-0 pointer-events-none' : 'opacity-100'"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-full border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    BACK
                </button>

                <button 
                    @click="nextQuestion()"
                    :disabled="answers[currentIndex] === undefined"
                    :class="answers[currentIndex] === undefined ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#1e4438] hover:shadow-md'"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#2A5C4D] text-white font-bold transition-all">
                    <span x-text="currentIndex === questions.length - 1 ? 'SUBMIT QUIZ' : 'NEXT QUESTION'"></span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>

            {{-- Hidden Form for Submission --}}
            <form id="quiz-form" method="POST" action="{{ route('quiz.submit') }}" class="hidden">
                @csrf
                <template x-for="(q, index) in questions" :key="index">
                    <div>
                        <input type="hidden" name="question_ids[]" :value="q.id">
                        <input type="hidden" :name="'answers[' + index + ']'" :value="answers[index]">
                    </div>
                </template>
            </form>

        </main>
    </div>

    <script>
        function quizComponent() {
            return {
                questions: @json($questions),
                currentIndex: 0,
                answers: {},
                
                get currentQuestion() {
                    return this.questions[this.currentIndex];
                },
                
                nextQuestion() {
                    if (this.answers[this.currentIndex] === undefined) return;
                    
                    if (this.currentIndex < this.questions.length - 1) {
                        this.currentIndex++;
                    } else {
                        // Submit form
                        document.getElementById('quiz-form').submit();
                    }
                },
                
                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    }
                }
            }
        }
    </script>
</body>
</html>
