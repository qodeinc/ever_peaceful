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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
            </div>
            <div>
              <h1 class="text-3xl font-bold text-gray-900">Two-Item Conjoint Screen (TICS) for Alcohol and Other Drug Problems</h1>
              <h2 class="text-lg text-emerald-600 font-semibold">Quick Screening for Recent Substance Use</h2>
            </div>
          </div>
          <p class="text-gray-600 leading-relaxed">
            This brief screening tool helps identify potential alcohol or drug problems based on experiences in the past 12 months. It takes less than a minute to complete.
          </p>
        </div>

        <!-- Important Note -->
        <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-200">
          <div class="flex items-start space-x-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-emerald-900">
              <p class="font-semibold mb-1">In the Past 12 Months</p>
              <p>
                Consider your experiences with alcohol and any drugs, including prescription medications used other than as directed.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side: Form -->
      <div class="space-y-8">
        <form @submit.prevent="calculateScore" class="space-y-8">
          <!-- Question 1 -->
          <div class="pb-6 border-b border-gray-200">
            <div class="mb-4">
              <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                <span class="text-emerald-800 font-black text-lg">1</span>
              </div>
              <label class="block text-lg font-medium text-gray-900">
                Have you ever drunk or used drugs more than you meant to?
              </label>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <label
                :class="[
                  'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                  answers.question1 === 'yes'
                    ? 'border-emerald-500 bg-emerald-50 shadow-md'
                    : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                ]"
              >
                <input
                  type="radio"
                  v-model="answers.question1"
                  value="yes"
                  class="sr-only"
                  required
                />
                <span class="font-medium text-gray-900">Yes</span>
                <span class="text-sm font-semibold text-emerald-600">+1</span>
              </label>
              <label
                :class="[
                  'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                  answers.question1 === 'no'
                    ? 'border-emerald-500 bg-emerald-50 shadow-md'
                    : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                ]"
              >
                <input
                  type="radio"
                  v-model="answers.question1"
                  value="no"
                  class="sr-only"
                  required
                />
                <span class="font-medium text-gray-900">No</span>
                <span class="text-sm font-semibold text-gray-500">0</span>
              </label>
            </div>
          </div>

          <!-- Question 2 -->
          <div class="pb-6 border-b border-gray-200">
            <div class="mb-4">
              <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                <span class="text-emerald-800 font-black text-lg">2</span>
              </div>
              <label class="block text-lg font-medium text-gray-900">
                Have you felt you wanted or needed to cut down on your drinking or drug use?
              </label>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <label
                :class="[
                  'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                  answers.question2 === 'yes'
                    ? 'border-emerald-500 bg-emerald-50 shadow-md'
                    : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                ]"
              >
                <input
                  type="radio"
                  v-model="answers.question2"
                  value="yes"
                  class="sr-only"
                  required
                />
                <span class="font-medium text-gray-900">Yes</span>
                <span class="text-sm font-semibold text-emerald-600">+1</span>
              </label>
              <label
                :class="[
                  'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                  answers.question2 === 'no'
                    ? 'border-emerald-500 bg-emerald-50 shadow-md'
                    : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                ]"
              >
                <input
                  type="radio"
                  v-model="answers.question2"
                  value="no"
                  class="sr-only"
                  required
                />
                <span class="font-medium text-gray-900">No</span>
                <span class="text-sm font-semibold text-gray-500">0</span>
              </label>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-center pt-4">
            <button
              type="submit"
              class="px-8 py-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors duration-200 shadow-lg hover:shadow-xl"
            >
              Calculate Result
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Results Section (Full Width Below) -->
    <div v-if="showResults" class="mt-16 space-y-8">
      <!-- Score Summary -->
      <div class="text-center">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Your Results</h3>
        <div class="inline-block bg-emerald-100 rounded-2xl px-8 py-4 mt-4">
          <p class="text-sm text-gray-600 mb-1">{{ positiveCount }} positive response{{ positiveCount !== 1 ? 's' : '' }}</p>
          <p class="text-5xl font-bold text-emerald-600">out of 2</p>
        </div>
      </div>

      <!-- Responses Breakdown -->
      <div class="bg-gray-50 rounded-xl p-6">
        <h4 class="font-semibold text-lg text-gray-900 mb-4">Your Responses</h4>
        <div class="space-y-3">
          <div class="flex items-center justify-between p-3 bg-white rounded-lg">
            <div class="flex items-center space-x-3">
              <span class="font-black text-emerald-700 text-xl">1</span>
              <span class="text-gray-700">More than meant to</span>
            </div>
            <span :class="answers.question1 === 'yes' ? 'font-semibold text-emerald-700' : 'text-gray-500'">
              {{ answers.question1 === 'yes' ? 'Yes' : 'No' }}
            </span>
          </div>
          <div class="flex items-center justify-between p-3 bg-white rounded-lg">
            <div class="flex items-center space-x-3">
              <span class="font-black text-emerald-700 text-xl">2</span>
              <span class="text-gray-700">Wanted to cut down</span>
            </div>
            <span :class="answers.question2 === 'yes' ? 'font-semibold text-emerald-700' : 'text-gray-500'">
              {{ answers.question2 === 'yes' ? 'Yes' : 'No' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Interpretation -->
      <div :class="[
        'rounded-xl p-6',
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
      <div class="bg-gray-50 rounded-xl p-6">
        <h4 class="font-semibold text-lg text-gray-900 mb-4">About TICS Screening</h4>
        <div class="space-y-3">
          <p class="text-gray-700">
            <span class="font-semibold">Performance:</span> A positive response to at least one question detects a current substance use disorder with a sensitivity of 79.3% and specificity of 77.9%.
          </p>
          <p class="text-gray-700">
            <span class="font-semibold">Time Frame:</span> This tool focuses on the past 12 months to identify recent patterns of problematic substance use.
          </p>
          <p class="text-gray-700">
            <span class="font-semibold">Next Steps:</span> A positive screen suggests the need for more detailed diagnostic assessments to evaluate the presence and severity of an alcohol or drug problem.
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
            This screening tool is not a diagnostic instrument. If you screen positive or have concerns about your substance use, 
            please consult with a qualified healthcare professional for a comprehensive evaluation and appropriate treatment options.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Reactive state
const answers = ref({
  question1: null,
  question2: null
})
const showResults = ref(false)
const positiveCount = ref(0)

// Computed properties
const isPositiveScreen = computed(() => {
  return positiveCount.value >= 1
})

const interpretationTitle = computed(() => {
  if (isPositiveScreen.value) {
    return 'Positive Screen'
  }
  return 'Negative Screen'
})

const interpretationText = computed(() => {
  if (isPositiveScreen.value) {
    return 'If the patient answers yes to one or both questions, it is considered a positive screening result and may suggest a current alcohol or drug problem; the patient should have more detailed diagnostic assessments for an alcohol or drug problem. A positive response to at least one question detects a current substance use disorder with a sensitivity of 79.3% and specificity of 77.9%.'
  }
  return 'If the patient answers no to both questions, it is considered a negative screening result; the patient is unlikely to have a current alcohol or drug problem.'
})

// Methods
const calculateScore = () => {
  positiveCount.value = (answers.value.question1 === 'yes' ? 1 : 0) + 
                       (answers.value.question2 === 'yes' ? 1 : 0)
  showResults.value = true
  
  // Scroll to results
  setTimeout(() => {
    const resultsElement = document.querySelector('.mt-16')
    if (resultsElement) {
      resultsElement.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }, 100)
}

const resetAssessment = () => {
  answers.value = {
    question1: null,
    question2: null
  }
  showResults.value = false
  positiveCount.value = 0
  
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saving results:', {
    assessment: 'TICS',
    positiveCount: positiveCount.value,
    positiveScreen: isPositiveScreen.value,
    date: new Date().toISOString(),
    answers: answers.value
  })
  alert('Results saved successfully! (This will be implemented with backend integration)')
}
</script>

<style scoped>
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