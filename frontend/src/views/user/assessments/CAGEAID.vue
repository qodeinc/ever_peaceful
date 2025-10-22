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
            <div class="flex-1">
              <h1 class="text-3xl font-bold text-gray-900 mb-2">CAGE-AID Assessment</h1>
              <h2 class="text-lg text-emerald-600 font-semibold mb-3">CAGE Adapted to Include Drugs</h2>
              <p class="text-gray-600 leading-relaxed">
                The CAGE-AID is an adaptation of the CAGE questionnaire for conjointly screening for alcohol and drug problems. 
                This screening focuses on lifetime use and takes about 1-2 minutes to complete.
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
              <p class="font-semibold mb-1">Before You Begin</p>
              <p>
                When thinking about drug use, include <span class="font-semibold">illegal drug use</span> and the 
                <span class="font-semibold">use of prescription drugs other than prescribed</span>.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side: Scrollable Form -->
      <div class="space-y-8 max-h-screen overflow-y-auto pr-4 lg:pr-0">
        <form @submit.prevent="calculateScore" class="space-y-8">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
            <!-- Question 1: Cut down -->
            <div ref="q1" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">C</span>
                </div>
                <label class="block text-lg font-medium text-gray-900">
                  Have you ever felt that you ought to <span class="font-bold text-emerald-700">Cut down</span> on your drinking or drug use?
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
                  <span class="text-sm font-semibold text-emerald-600">1 point</span>
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
                  <span class="text-sm font-semibold text-gray-500">0 points</span>
                </label>
              </div>
            </div>

            <!-- Question 2: Annoyed -->
            <div ref="q2" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">A</span>
                </div>
                <label class="block text-lg font-medium text-gray-900">
                  Have people <span class="font-bold text-emerald-700">Annoyed</span> you by criticizing your drinking or drug use?
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
                  <span class="text-sm font-semibold text-emerald-600">1 point</span>
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
                  <span class="text-sm font-semibold text-gray-500">0 points</span>
                </label>
              </div>
            </div>

            <!-- Question 3: Guilty -->
            <div ref="q3" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">G</span>
                </div>
                <label class="block text-lg font-medium text-gray-900">
                  Have you ever felt bad or <span class="font-bold text-emerald-700">Guilty</span> about your drinking or drug use?
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
                  <span class="text-sm font-semibold text-emerald-600">1 point</span>
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
                  <span class="text-sm font-semibold text-gray-500">0 points</span>
                </label>
              </div>
            </div>

            <!-- Question 4: Eye opener -->
            <div ref="q4" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">E</span>
                </div>
                <label class="block text-lg font-medium text-gray-900">
                  Have you ever had a drink or used drugs first thing in the morning to steady your nerves or to get rid of a hangover (<span class="font-bold text-emerald-700">Eye opener</span>)?
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
                  <span class="text-sm font-semibold text-emerald-600">1 point</span>
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
                  <span class="text-sm font-semibold text-gray-500">0 points</span>
                </label>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-8 pt-6 border-t border-gray-200 sticky bottom-0 bg-white py-4">
              <button
                type="submit"
                class="px-8 py-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors duration-200 shadow-lg hover:shadow-xl"
                :disabled="!allAnswered"
              >
                Calculate Score
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Results Section (Full Width Below) -->
    <div v-if="showResults" class="mt-16 space-y-8 animate-fadeIn">
      <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Your Results</h3>
        <div class="inline-block bg-emerald-100 rounded-2xl px-8 py-4 mt-4">
          <p class="text-sm text-gray-600 mb-1">Total Score</p>
          <p class="text-5xl font-bold text-emerald-600">{{ totalScore }}</p>
          <p class="text-sm text-gray-500 mt-1">out of 4</p>
        </div>
      </div>

      <!-- CAGE Acronym Breakdown -->
      <div class="bg-gray-50 rounded-xl p-6 mb-6">
        <h4 class="font-semibold text-lg text-gray-900 mb-4">Your Responses</h4>
        <div class="space-y-3">
          <div class="flex items-center justify-between p-3 bg-white rounded-lg">
            <div class="flex items-center space-x-3">
              <span class="font-black text-emerald-700 text-xl">C</span>
              <span class="text-gray-700">Cut down</span>
            </div>
            <span :class="answers.question1 === 1 ? 'font-semibold text-emerald-700' : 'text-gray-500'">
              {{ answers.question1 === 1 ? 'Yes' : 'No' }}
            </span>
          </div>
          <div class="flex items-center justify-between p-3 bg-white rounded-lg">
            <div class="flex items-center space-x-3">
              <span class="font-black text-emerald-700 text-xl">A</span>
              <span class="text-gray-700">Annoyed</span>
            </div>
            <span :class="answers.question2 === 1 ? 'font-semibold text-emerald-700' : 'text-gray-500'">
              {{ answers.question2 === 1 ? 'Yes' : 'No' }}
            </span>
          </div>
          <div class="flex items-center justify-between p-3 bg-white rounded-lg">
            <div class="flex items-center space-x-3">
              <span class="font-black text-emerald-700 text-xl">G</span>
              <span class="text-gray-700">Guilty</span>
            </div>
            <span :class="answers.question3 === 1 ? 'font-semibold text-emerald-700' : 'text-gray-500'">
              {{ answers.question3 === 1 ? 'Yes' : 'No' }}
            </span>
          </div>
          <div class="flex items-center justify-between p-3 bg-white rounded-lg">
            <div class="flex items-center space-x-3">
              <span class="font-black text-emerald-700 text-xl">E</span>
              <span class="text-gray-700">Eye opener</span>
            </div>
            <span :class="answers.question4 === 1 ? 'font-semibold text-emerald-700' : 'text-gray-500'">
              {{ answers.question4 === 1 ? 'Yes' : 'No' }}
            </span>
          </div>
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
        <h4 class="font-semibold text-lg text-gray-900 mb-4">About CAGE-AID Scoring</h4>
        <div class="space-y-3">
          <p class="text-gray-700">
            <span class="font-semibold">Interpretation:</span> One or more "yes" responses is regarded as a positive screening test, indicating possible substance use disorder and the need for further evaluation.
          </p>
          <p class="text-gray-700">
            <span class="font-semibold">Lifetime Focus:</span> The CAGE-AID focuses on lifetime use of alcohol and drugs, including illegal drugs and prescription drugs used other than prescribed.
          </p>
          <p class="text-gray-700">
            <span class="font-semibold">Next Steps:</span> A positive screen warrants a comprehensive clinical assessment to determine the presence and severity of a substance use disorder and appropriate treatment recommendations.
          </p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <button
          @click="resetAssessment"
          class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors duration-200"
        >
          Take Assessment Again
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
import { ref, computed, nextTick } from 'vue'

// Refs for question elements
const q1 = ref(null)
const q2 = ref(null)
const q3 = ref(null)
const q4 = ref(null)

// Reactive state
const answers = ref({
  question1: null,
  question2: null,
  question3: null,
  question4: null
})
const showResults = ref(false)
const totalScore = ref(0)

// Computed properties
const allAnswered = computed(() => {
  return answers.value.question1 !== null &&
         answers.value.question2 !== null &&
         answers.value.question3 !== null &&
         answers.value.question4 !== null
})

const isPositiveScreen = computed(() => {
  return totalScore.value >= 1
})

const interpretationTitle = computed(() => {
  if (isPositiveScreen.value) {
    return 'Positive Screen'
  }
  return 'Negative Screen'
})

const interpretationText = computed(() => {
  if (isPositiveScreen.value) {
    return `You answered "yes" to ${totalScore.value} question${totalScore.value > 1 ? 's' : ''}. One or more "yes" responses indicates possible substance use disorder and warrants further evaluation by a healthcare professional. A comprehensive clinical assessment can help determine the presence and severity of substance use issues and guide appropriate treatment recommendations.`
  }
  return 'You answered "no" to all questions. This suggests no current indicators of problematic substance use. However, if you have any concerns about your alcohol or drug use, please don\'t hesitate to discuss them with a healthcare provider.'
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
    default: return null
  }
}

const calculateScore = () => {
  totalScore.value = (answers.value.question1 || 0) + 
                     (answers.value.question2 || 0) + 
                     (answers.value.question3 || 0) + 
                     (answers.value.question4 || 0)
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
    question4: null
  }
  showResults.value = false
  totalScore.value = 0
  
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saving results:', {
    assessment: 'CAGE-AID',
    score: totalScore.value,
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