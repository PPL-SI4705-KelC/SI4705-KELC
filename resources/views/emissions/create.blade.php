<x-calculator-layout>
    <x-slot name="title">Calculate Your Footprint</x-slot>

    <!-- Custom full-page wizard style overriding standard padding -->
    <div x-data="calculatorWizard()" class="min-h-[calc(100vh-7rem)] bg-white flex flex-col pb-32 -mx-8 -mt-2 relative">
        
        <!-- Banner Header -->
        <div class="relative h-80 bg-slate-900">
            <img src="https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&q=80&w=2000" class="absolute inset-0 w-full h-full object-cover opacity-80">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 pt-8 pb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight drop-shadow-md">Calculate Your Environmental Footprint</h1>
                <p class="text-white/90 text-lg md:text-xl font-light drop-shadow">Discover how your lifestyle choices impact our planet</p>
                <div class="mt-6 flex items-center gap-2 text-white/90 text-sm font-medium drop-shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Takes about 10-15 minutes
                </div>
            </div>
        </div>

        <!-- Wizard Container -->
        <div class="max-w-4xl w-full mx-auto relative z-10 px-4 md:px-8 mt-12 mb-10">
            
            <!-- Progress Header (Outside Card) -->
            <div class="flex justify-between items-center text-xs font-semibold text-gray-500 mb-2 px-1">
                <span>Progress</span>
                <span>Question <span x-text="currentStepIndex + 1"></span> of <span x-text="visibleQuestions.length"></span></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1 mb-8 overflow-hidden">
                <div class="bg-[#2D5A4C] h-full transition-all duration-500 ease-out rounded-full" :style="`width: ${((currentStepIndex + 1) / visibleQuestions.length) * 100}%`"></div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 md:p-12 mb-20">
                <form id="emission-form" method="POST" action="{{ route('calculator.store') }}">
                    @csrf
                    
                    <!-- Hidden inputs generated dynamically based on formData -->
                    <template x-for="(value, key) in formData.transport" :key="'t'+key">
                        <input type="hidden" :name="`transport[${key}]`" :value="value">
                    </template>
                    <template x-for="(value, key) in formData.consumption" :key="'c'+key">
                        <input type="hidden" :name="`consumption[${key}]`" :value="value">
                    </template>
                    <template x-for="(value, key) in formData.energy" :key="'e'+key">
                        <input type="hidden" :name="`energy[${key}]`" :value="value">
                    </template>

                    <!-- Question Display -->
                    <div class="min-h-[340px]" 
                         x-show="true" 
                         x-transition:enter="transition ease-out duration-500" 
                         x-transition:enter-start="opacity-0 translate-x-8" 
                         x-transition:enter-end="opacity-100 translate-x-0" 
                         :key="currentQuestion.id">
                        
                        <!-- Category Badge -->
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-full bg-[#2D5A4C] flex items-center justify-center text-white text-lg shadow-sm">
                                <span x-html="currentQuestion.icon"></span>
                            </div>
                            <span class="text-[#2D5A4C] font-semibold text-sm" x-text="currentQuestion.categoryLabel"></span>
                        </div>

                        <h2 class="text-3xl font-bold text-gray-900 mb-2 tracking-tight" x-text="currentQuestion.title"></h2>
                        <p class="text-gray-500 mb-10 text-sm" x-text="currentQuestion.subtitle"></p>

                        <!-- Options Grid -->
                        <div class="grid gap-4" :class="currentQuestion.options.length > 4 ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1'">
                            <template x-for="option in currentQuestion.options" :key="option.value">
                                <label class="group relative flex items-center cursor-pointer rounded-2xl p-5 transition-all duration-200 border border-transparent hover:bg-gray-50"
                                       :class="isSelected(option.value) ? 'bg-gray-50 shadow-sm ring-1 ring-gray-200' : ''">
                                    <input type="radio" :name="currentQuestion.id" :value="option.value" class="sr-only" @change="selectOption(option.value)">
                                    
                                    <div class="flex flex-1 items-start gap-4">
                                        <div class="mt-0.5 text-gray-400 group-hover:text-[#2D5A4C] transition-colors" :class="isSelected(option.value) ? 'text-[#2D5A4C]' : ''">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="block text-sm font-bold text-gray-900" x-text="option.label"></span>
                                            <span x-show="option.desc" class="mt-1 block text-xs text-gray-500" x-text="option.desc"></span>
                                        </div>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="mt-12 flex items-center justify-between pt-8">
                        <button type="button" @click="prevStep" :class="currentStepIndex === 0 ? 'invisible' : ''" class="text-gray-400 hover:text-gray-600 font-medium flex items-center gap-2 transition-colors text-sm">
                            <span>&larr;</span> Previous
                        </button>
                        
                        <button x-show="!isLastStep()" type="button" @click="nextStep" :disabled="!hasAnswer()" 
                                class="px-8 py-2.5 rounded-lg font-medium transition-all flex items-center gap-2 text-sm"
                                :class="hasAnswer() ? 'bg-[#8BA89A] hover:bg-[#2D5A4C] text-white shadow-sm' : 'bg-[#8BA89A]/60 text-white cursor-not-allowed'">
                            Next <span>&rarr;</span>
                        </button>

                        <button x-show="isLastStep()" type="submit" :disabled="!hasAnswer()" 
                                class="px-8 py-2.5 rounded-lg font-medium transition-all flex items-center gap-2 text-sm"
                                :class="hasAnswer() ? 'bg-[#FBC02D] hover:bg-yellow-400 text-gray-900 shadow-sm' : 'bg-[#FBC02D]/60 text-gray-900 cursor-not-allowed'">
                            Submit <span>&rarr;</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Floating Bottom Categories -->
        <div class="fixed bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-12 z-20">
            <div class="flex flex-col items-center gap-2 transition-all duration-300" :class="currentQuestion.group === 'energy' ? 'opacity-100' : 'opacity-40 grayscale'">
                <div class="w-16 h-12 rounded-lg flex items-center justify-center transition-colors" :class="currentQuestion.group === 'energy' ? 'bg-[#2D5A4C] text-white shadow-lg' : 'bg-transparent text-gray-900'">
                    <span class="text-2xl">⚡</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800">Energy</span>
            </div>
            <div class="flex flex-col items-center gap-2 transition-all duration-300" :class="currentQuestion.group === 'consumption' ? 'opacity-100' : 'opacity-40 grayscale'">
                <div class="w-16 h-12 rounded-lg flex items-center justify-center transition-colors" :class="currentQuestion.group === 'consumption' ? 'bg-[#2D5A4C] text-white shadow-lg' : 'bg-transparent text-gray-900'">
                    <span class="text-2xl">🍽️</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800">Food</span>
            </div>
            <div class="flex flex-col items-center gap-2 transition-all duration-300" :class="currentQuestion.group === 'transport' ? 'opacity-100' : 'opacity-40 grayscale'">
                <div class="w-16 h-12 rounded-lg flex items-center justify-center transition-colors" :class="currentQuestion.group === 'transport' ? 'bg-[#2D5A4C] text-white shadow-lg' : 'bg-transparent text-gray-900'">
                    <span class="text-2xl">🚗</span>
                </div>
                <span class="text-[11px] font-bold text-gray-800">Transport</span>
            </div>
        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('calculatorWizard', () => ({
                formData: {
                    transport: {},
                    consumption: {},
                    energy: {}
                },
                currentStepIndex: 0,
                
                get questions() {
                    return [
                        // --- TRANSPORT ---
                        { id: 'main_vehicle', group: 'transport', categoryLabel: 'Transport', icon: '🚗', title: 'Main Transportation Today', subtitle: 'How did you mostly travel today?', options: [
                            {value: 'car', label: 'Car', desc: 'Personal or ride-hailing'},
                            {value: 'motorcycle', label: 'Motorcycle', desc: 'Personal or ride-hailing'},
                            {value: 'none', label: 'No private vehicle', desc: 'Walk, bike, or fully public transit'}
                        ]},
                        { id: 'car_type', group: 'transport', categoryLabel: 'Transport', icon: '🚙', title: 'What type of car?', subtitle: 'Select your vehicle type.', condition: () => this.formData.transport.main_vehicle === 'car', options: [
                            {value: 'electric', label: 'Electric Car'},
                            {value: 'plugin_hybrid', label: 'Plug-in Hybrid'},
                            {value: 'hybrid', label: 'Hybrid'},
                            {value: 'small', label: 'Small Car (gasoline/diesel)'},
                            {value: 'medium', label: 'Medium Car'},
                            {value: 'large', label: 'Large Car / SUV'}
                        ]},
                        { id: 'motorcycle_type', group: 'transport', categoryLabel: 'Transport', icon: '🏍️', title: 'What type of motorcycle?', subtitle: 'Select your vehicle type.', condition: () => this.formData.transport.main_vehicle === 'motorcycle', options: [
                            {value: 'electric', label: 'Electric Motorcycle'},
                            {value: 'gasoline', label: 'Gasoline Motorcycle'}
                        ]},
                        { id: 'main_vehicle_duration', group: 'transport', categoryLabel: 'Transport', icon: '⏱️', title: 'Duration of Main Vehicle', subtitle: 'How much time did you spend in this vehicle today?', condition: () => ['car', 'motorcycle'].includes(this.formData.transport.main_vehicle), options: [
                            {value: 'lt_1', label: '< 1 hour'},
                            {value: '1_to_2', label: '1 - 2 hours'},
                            {value: '2_to_4', label: '2 - 4 hours'},
                            {value: 'gt_4', label: '> 4 hours'}
                        ]},
                        { id: 'train_duration', group: 'transport', categoryLabel: 'Transport', icon: '🚆', title: 'Train Usage', subtitle: 'Did you use a train or commuter line today?', options: [
                            {value: 'none', label: 'Not used'},
                            {value: 'lt_1', label: '< 1 hour'},
                            {value: '1_to_2', label: '1 - 2 hours'},
                            {value: '2_to_4', label: '2 - 4 hours'},
                            {value: 'gt_4', label: '> 4 hours'}
                        ]},
                        { id: 'bus_duration', group: 'transport', categoryLabel: 'Transport', icon: '🚌', title: 'Bus Usage', subtitle: 'Did you ride a bus today?', options: [
                            {value: 'none', label: 'Not used'},
                            {value: 'lt_1', label: '< 1 hour'},
                            {value: '1_to_2', label: '1 - 2 hours'},
                            {value: '2_to_4', label: '2 - 4 hours'},
                            {value: 'gt_4', label: '> 4 hours'}
                        ]},
                        { id: 'flight', group: 'transport', categoryLabel: 'Transport', icon: '✈️', title: 'Air Travel', subtitle: 'Did you take any flights today?', options: [
                            {value: 'no', label: 'No'},
                            {value: 'yes', label: 'Yes'}
                        ]},
                        { id: 'flight_duration', group: 'transport', categoryLabel: 'Transport', icon: '⏳', title: 'Flight Duration', subtitle: 'How long was your flight?', condition: () => this.formData.transport.flight === 'yes', options: [
                            {value: 'lt_1', label: '< 1 hour'},
                            {value: '1_to_3', label: '1 - 3 hours'},
                            {value: '3_to_6', label: '3 - 6 hours'},
                            {value: 'gt_6', label: '> 6 hours'}
                        ]},

                        // --- CONSUMPTION ---
                        { id: 'diet_type', group: 'consumption', categoryLabel: 'Food & Consumption', icon: '🍽️', title: 'Diet Type', subtitle: 'What best describes your diet today?', options: [
                            {value: 'meat_every', label: 'Meat in every meal', desc: 'Beef, lamb, pork, etc.'},
                            {value: 'meat_some', label: 'Meat in some meals'},
                            {value: 'no_beef', label: 'No beef', desc: 'Pork, chicken, or fish only'},
                            {value: 'rarely', label: 'Rarely eat meat'},
                            {value: 'vegetarian', label: 'Vegetarian', desc: 'No meat, but dairy/eggs'},
                            {value: 'vegan', label: 'Vegan', desc: 'Plant-based only'}
                        ]},
                        { id: 'spending', group: 'consumption', categoryLabel: 'Food & Consumption', icon: '💰', title: 'Daily Food Spending', subtitle: 'Roughly how much did you spend on food today?', options: [
                            {value: '0', label: 'Rp 0', desc: 'No spending'},
                            {value: 'low', label: 'Rp 20k - 200k'},
                            {value: 'medium', label: 'Rp 200k - 1.2jt'},
                            {value: 'high', label: '> Rp 1.2jt'}
                        ]},
                        { id: 'waste', group: 'consumption', categoryLabel: 'Food & Consumption', icon: '🗑️', title: 'Food Waste', subtitle: 'How much of your food was wasted today?', options: [
                            {value: 'none', label: 'None'},
                            {value: 'low', label: '1 - 10%'},
                            {value: 'medium', label: '10 - 30%'},
                            {value: 'high', label: '> 30%'}
                        ]},
                        { id: 'source', group: 'consumption', categoryLabel: 'Food & Consumption', icon: '🛒', title: 'Food Source', subtitle: 'Where was your food sourced from?', options: [
                            {value: 'mostly_local', label: 'Mostly local', desc: 'Markets, local farms'},
                            {value: 'some_local', label: 'Some local'},
                            {value: 'not_concerned', label: 'Not concerned / Imported'}
                        ]},

                        // --- ENERGY ---
                        { id: 'residence', group: 'energy', categoryLabel: 'Housing & Energy', icon: '🏠', title: 'Residence Type', subtitle: 'What kind of home do you live in?', options: [
                            {value: 'detached', label: 'House (Detached)'},
                            {value: 'row', label: 'Row house / Townhouse'},
                            {value: 'apartment', label: 'Apartment / Condo'}
                        ]},
                        { id: 'bedrooms', group: 'energy', categoryLabel: 'Housing & Energy', icon: '🛏️', title: 'Number of Bedrooms', subtitle: 'How many bedrooms are in your home?', options: [
                            {value: '1', label: '1 Bedroom'},
                            {value: '2', label: '2 Bedrooms'},
                            {value: '3', label: '3 Bedrooms'},
                            {value: '4_plus', label: '4 or more'}
                        ]},
                        { id: 'adults', group: 'energy', categoryLabel: 'Housing & Energy', icon: '👥', title: 'Number of Adults', subtitle: 'How many adults share your home?', options: [
                            {value: '1', label: '1 Adult'},
                            {value: '2', label: '2 Adults'},
                            {value: '3', label: '3 Adults'},
                            {value: '4', label: '4 Adults'},
                            {value: '5_plus', label: '5 or more'}
                        ]},
                        { id: 'electricity', group: 'energy', categoryLabel: 'Housing & Energy', icon: '⚡', title: 'Electricity Source', subtitle: 'Where does your electricity come from?', options: [
                            {value: 'pln', label: 'PLN (Grid)'},
                            {value: 'generator', label: 'Generator'}
                        ]},
                        { id: 'energy_saving', group: 'energy', categoryLabel: 'Housing & Energy', icon: '💡', title: 'Energy Saving Behavior', subtitle: 'Did you actively turn off lights and unplug appliances today?', options: [
                            {value: 'yes', label: 'Yes, actively saved energy'},
                            {value: 'no', label: 'No'}
                        ]},
                        { id: 'cooling', group: 'energy', categoryLabel: 'Housing & Energy', icon: '❄️', title: 'Cooling System', subtitle: 'What cooling did you use today?', options: [
                            {value: 'none', label: 'None'},
                            {value: 'fan', label: 'Fan'},
                            {value: 'ac_lt_8', label: 'AC (≤ 8 hours)'},
                            {value: 'ac_gt_8', label: 'AC (> 8 hours)'}
                        ]}
                    ];
                },
                
                get visibleQuestions() {
                    return this.questions.filter(q => !q.condition || q.condition());
                },

                get currentQuestion() {
                    return this.visibleQuestions[this.currentStepIndex] || this.visibleQuestions[0];
                },

                hasAnswer() {
                    const q = this.currentQuestion;
                    return this.formData[q.group][q.id] !== undefined && this.formData[q.group][q.id] !== '';
                },

                isSelected(val) {
                    const q = this.currentQuestion;
                    return this.formData[q.group][q.id] === val;
                },

                selectOption(val) {
                    const q = this.currentQuestion;
                    this.formData[q.group][q.id] = val;
                    
                    // Small delay for animation feedback
                    setTimeout(() => {
                        if (!this.isLastStep()) {
                            this.nextStep();
                        }
                    }, 350);
                },

                nextStep() {
                    if (this.currentStepIndex < this.visibleQuestions.length - 1) {
                        this.currentStepIndex++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                prevStep() {
                    if (this.currentStepIndex > 0) {
                        this.currentStepIndex--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                isLastStep() {
                    return this.currentStepIndex === this.visibleQuestions.length - 1;
                }
            }));
        });
    </script>
</x-calculator-layout>
