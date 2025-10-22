<template>
  <div class="max-w-6xl mx-auto px-6 py-12">
    <!-- Main Layout: Left Header, Right Form -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
      <!-- Left Side: Header and Info -->
      <div class="space-y-8">
        <!-- Header -->
        <div class="space-y-4">
          <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="flex-1">
              <h1 class="text-3xl font-bold text-gray-900 mb-2">Primary Care PTSD Screen for DSM-5 (PC-PTSD-5)</h1>
              <h2 class="text-lg text-emerald-600 font-semibold mb-3">Screening Tool for Probable PTSD</h2>
              <p class="text-gray-600 leading-relaxed">
                The Primary Care PTSD Screen for DSM-5 (PC-PTSD-5) is a screening tool designed to identify persons with probable PTSD. Available data suggest the PC-PTSD-5 screen should be considered "positive" if the respondent answers "yes" to any 3 items in the questions listed below. Those screening positive should have further assessment with a structured interview for PTSD, preferably performed by a mental health professional who has experience in diagnosing PTSD.
              </p>
            </div>
          </div>
        </div>

        <!-- Important Note -->
        <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-200">
          <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-emerald-900">
              <p class="font-semibold mb-1">Traumatic Events</p>
              <p class="mb-2">
                Sometimes things happen to people that are unusually or especially frightening, horrible, or traumatic. For example:
              </p>
              <ul class="list-disc list-inside space-y-1 text-sm">
                <li>a serious accident or fire</li>
                <li>a physical or sexual assault or abuse</li>
                <li>an earthquake or flood</li>
                <li>a war</li>
                <li>seeing someone be killed or seriously injured</li>
                <li>having a loved one die through homicide or suicide</li>
              </ul>
              <p class="mt-2">If you have ever experienced this type of event, please answer the following:</p>
              <p class="font-semibold">In the past month, have you:</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side: Scrollable Form -->
      <div class="space-y-6 max-h-screen overflow-y-auto pr-4 lg:pr-0">
        <form @submit.prevent="calculateScore" class="space-y-6">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
            <!-- Question 1 -->
            <div ref="q1" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">1</span>
                </div>
                <label class="block text-base font-medium text-gray-900">
                  Had nightmares about the event(s) or thought about the event(s) when you did not want to?
                </label>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question1 === 1
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question1"
                    :value="1"
                    class="sr-only"
                    required
                    @change="onAnswerChange(1)"
                  />
                  <span class="font-medium text-gray-900">Yes</span>
                  <span class="text-sm font-semibold text-emerald-600">+1</span>
                </label>
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question1 === 0
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question1"
                    :value="0"
                    class="sr-only"
                    required
                    @change="onAnswerChange(1)"
                  />
                  <span class="font-medium text-gray-900">No</span>
                  <span class="text-sm font-semibold text-gray-500">0</span>
                </label>
              </div>
            </div>

            <!-- Question 2 -->
            <div ref="q2" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">2</span>
                </div>
                <label class="block text-base font-medium text-gray-900">
                  Tried hard not to think about the event(s) or went out of your way to avoid situations that reminded you of the event(s)?
                </label>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question2 === 1
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question2"
                    :value="1"
                    class="sr-only"
                    required
                    @change="onAnswerChange(2)"
                  />
                  <span class="font-medium text-gray-900">Yes</span>
                  <span class="text-sm font-semibold text-emerald-600">+1</span>
                </label>
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question2 === 0
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question2"
                    :value="0"
                    class="sr-only"
                    required
                    @change="onAnswerChange(2)"
                  />
                  <span class="font-medium text-gray-900">No</span>
                  <span class="text-sm font-semibold text-gray-500">0</span>
                </label>
              </div>
            </div>

            <!-- Question 3 -->
            <div ref="q3" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">3</span>
                </div>
                <label class="block text-base font-medium text-gray-900">
                  Been constantly on guard, watchful, or easily startled?
                </label>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question3 === 1
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question3"
                    :value="1"
                    class="sr-only"
                    required
                    @change="onAnswerChange(3)"
                  />
                  <span class="font-medium text-gray-900">Yes</span>
                  <span class="text-sm font-semibold text-emerald-600">+1</span>
                </label>
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question3 === 0
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question3"
                    :value="0"
                    class="sr-only"
                    required
                    @change="onAnswerChange(3)"
                  />
                  <span class="font-medium text-gray-900">No</span>
                  <span class="text-sm font-semibold text-gray-500">0</span>
                </label>
              </div>
            </div>

            <!-- Question 4 -->
            <div ref="q4" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">4</span>
                </div>
                <label class="block text-base font-medium text-gray-900">
                  Felt numb or detached from people, activities, or your surroundings?
                </label>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question4 === 1
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question4"
                    :value="1"
                    class="sr-only"
                    required
                    @change="onAnswerChange(4)"
                  />
                  <span class="font-medium text-gray-900">Yes</span>
                  <span class="text-sm font-semibold text-emerald-600">+1</span>
                </label>
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question4 === 0
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question4"
                    :value="0"
                    class="sr-only"
                    required
                    @change="onAnswerChange(4)"
                  />
                  <span class="font-medium text-gray-900">No</span>
                  <span class="text-sm font-semibold text-gray-500">0</span>
                </label>
              </div>
            </div>

            <!-- Question 5 -->
            <div ref="q5" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">5</span>
                </div>
                <label class="block text-base font-medium text-gray-900">
                  Felt guilty or unable to stop blaming yourself or others for the event(s) or any problems the event(s) may have caused?
                </label>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question5 === 1
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question5"
                    :value="1"
                    class="sr-only"
                    required
                    @change="onAnswerChange(5)"
                  />
                  <span class="font-medium text-gray-900">Yes</span>
                  <span class="text-sm font-semibold text-emerald-600">+1</span>
                </label>
                <label
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question5 === 0
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question5"
                    :value="0"
                    class="sr-only"
                    required
                    @change="onAnswerChange(5)"
                  />
                  <span class="font-medium text-gray-900">No</span>
                  <span class="text-sm font-semibold text-gray-500">0</span>
                </label>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-8 pt-6 border-t border-gray-200 sticky bottom-0 bg-white py-4">
              <button
                type="submit"
                class="px-8 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors duration-200 shadow-md hover:shadow-lg disabled:bg-gray-400 disabled:cursor-not-allowed"
                :disabled="!allAnswered"
              >
                Calculate Result
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Results Section (Full Width Below) -->
    <div v-if="showResults" class="mt-16 space-y-6 animate-fadeIn">
      <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Your Results</h3>
        <div class="inline-block bg-emerald-100 rounded-2xl px-8 py-4 mt-4">
          <p class="text-sm text-gray-600 mb-1">{{ positiveCount }} yes answer{{ positiveCount !== 1 ? 's' : '' }}</p>
          <p class="text-5xl font-bold text-emerald-600">out of 5</p>
        </div>
      </div>

      <!-- Interpretation -->
      <div :class="[
        'rounded-xl p-6 mb-6',
        isPositiveScreen ? 'bg-amber-50 border-2 border-amber-200' : 'bg-green-50 border-2 border-green-200'
      ]">
        <div class="flex items-start space-x-3">
          <svg v-if="isPositiveScreen" class="w-6 h-6 text-amber-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <svg v-else class="w-6 h-6 text-green-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <h4 class="font-semibold text-lg mb-2" :class="isPositiveScreen ? 'text-amber-900' : 'text-green-900'">
              {{ interpretationTitle }}
            </h4>
            <p class="text-gray-700 leading-relaxed">
              {{ interpretationText }}
            </p>
          </div>
        </div>
      </div>

      <!-- Clinical Information -->
      <div class="bg-gray-50 rounded-xl p-6 mb-6">
        <h4 class="font-semibold text-lg text-gray-900 mb-4">About PC-PTSD-5 Screening</h4>
        <div class="space-y-3">
          <p class="text-gray-700">
            <span class="font-semibold">Scoring:</span> Current research suggests that the results of the PC-PTSD-5 should be considered "positive" if a patient answers "yes" to any three items.
          </p>
          <p class="text-gray-700">
            <span class="font-semibold">Next Steps:</span> A positive screen warrants a comprehensive clinical assessment with a structured interview for PTSD, preferably performed by a mental health professional who has experience in diagnosing PTSD.
          </p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button
          @click="resetAssessment"
          class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors duration-200"
        >
          Take Screening Again
        </button>
        <button
          @click="saveResults"
          class="px-6 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors duration-200"
        >
          Save Results
        </button>
      </div>
    </div>

    <!-- Disclaimer (Full Width) -->
    <div class="mt-16 bg-blue-50 rounded-xl p-6 border border-blue-200">
      <div class="flex items-start space-x-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="text-sm text-blue-900">
          <p class="font-semibold mb-1">Important Note</p>
          <p>
            This screening tool is not a diagnostic instrument. If you screen positive or have concerns about your trauma experiences or symptoms, 
            please consult with a qualified mental health professional for a comprehensive evaluation and appropriate treatment options.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

// Refs for question elements
const q1 = ref(null)
const q2 = ref(null)
const q3 = ref(null)
const q4 = ref(null)
const q5 = ref(null)

// Reactive state
const answers = ref({
  question1: null,
  question2: null,
  question3: null,
  question4: null,
  question5: null
})
const showResults = ref(false)
const positiveCount = ref(0)

// Computed properties
const allAnswered = computed(() => {
  return Object.values(answers.value).every(val => val !== null)
})

const isPositiveScreen = computed(() => {
  return positiveCount.value >= 3
})

const interpretationTitle = computed(() => {
  if (isPositiveScreen.value) {
    return 'Positive Screen'
  }
  return 'Negative Screen'
})

const interpretationText = computed(() => {
  if (isPositiveScreen.value) {
    return `You answered "yes" to ${positiveCount.value} item${positiveCount.value > 1 ? 's' : ''}. Current research suggests that the results of the PC-PTSD-5 should be considered "positive" if a patient answers "yes" to any three items. This indicates probable PTSD, and further assessment with a structured interview for PTSD is recommended, preferably performed by a mental health professional who has experience in diagnosing PTSD.`
  }
  return `You answered "yes" to ${positiveCount.value} item${positiveCount.value > 1 ? 's' : ''}. This is a negative screen, suggesting low likelihood of PTSD at this time. However, if you have any concerns about your trauma experiences or symptoms, please discuss them with a healthcare provider.`
})

// Methods
const onAnswerChange = async (qNum) => {
  await nextTick()
  const nextQNum = qNum + 1
  const nextRef = getRefByNum(nextQNum)
  if (nextRef && nextRef.value) {
    nextRef.value.$el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const getRefByNum = (num) => {
  switch (num) {
    case 1: return q1
    case 2: return q2
    case 3: return q3
    case 4: return q4
    case 5: return q5
    default: return null
  }
}

const calculateScore = () => {
  positiveCount.value = (answers.value.question1 || 0) + 
                       (answers.value.question2 || 0) + 
                       (answers.value.question3 || 0) + 
                       (answers.value.question4 || 0) + 
                       (answers.value.question5 || 0)
  showResults.value = true
  
  // Scroll to results
  setTimeout(() => {
    const resultsElement = document.querySelector('.animate-fadeIn')
    if (resultsElement) {
      resultsElement.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }, 100)
}

const resetAssessment = () => {
  answers.value = {
    question1: null,
    question2: null,
    question3: null,
    question4: null,
    question5: null
  }
  showResults.value = false
  positiveCount.value = 0
  
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saving results:', {
    assessment: 'PC-PTSD-5',
    positiveCount: positiveCount.value,
    positiveScreen: isPositiveScreen.value,
    date: new Date().toISOString(),
    answers: answers.value
  })
  alert('Results saved successfully! (This will be implemented with backend integration)')
}
</script>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.5s ease-out;
}

/* Custom radio button styling */
input[type="radio"]:checked + span,
input[type="radio"]:checked ~ span {
  transform: scale(1.01);
}

/* Smooth transitions */
* {
  transition-property: background-color, border-color, transform;
  transition-duration: 200ms;
  transition-timing-function: ease-in-out;
}
</style>