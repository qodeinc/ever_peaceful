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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
              </svg>
            </div>
            <div class="flex-1">
              <h1 class="text-3xl font-bold text-gray-900 mb-2">GAD-2 Assessment</h1>
              <h2 class="text-lg text-emerald-600 font-semibold mb-3">Generalized Anxiety Disorder 2-item</h2>
              <p class="text-gray-600 leading-relaxed">
                The GAD-2 is a brief and easy to perform initial screening tool for generalized anxiety disorder. 
                This assessment takes about 1-2 minutes to complete.
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
              <p class="font-semibold mb-1">Over the Last 2 Weeks</p>
              <p>
                How often have you been bothered by any of the following problems?
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side: Scrollable Form -->
      <div class="space-y-6 max-h-screen overflow-y-auto pr-4 lg:pr-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
          <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">
              Over the last 2 weeks, how often have you been bothered by the following problems?
            </h3>
          </div>

          <form @submit.prevent="calculateScore" class="space-y-8">
            <!-- Question 1 -->
            <div ref="q1" class="pb-6 border-b border-gray-200">
              <label class="block text-lg font-medium text-gray-900 mb-4">
                1. Feeling nervous, anxious or on edge
              </label>
              <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <label
                  v-for="option in options"
                  :key="option.value"
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question1 === option.value
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question1"
                    :value="option.value"
                    class="sr-only"
                    required
                    @change="onAnswerChange(1)"
                  />
                  <div class="text-center">
                    <p class="font-medium text-gray-900">{{ option.label }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ option.value }} point{{ option.value !== 1 ? 's' : '' }}</p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Question 2 -->
            <div ref="q2" class="pb-6">
              <label class="block text-lg font-medium text-gray-900 mb-4">
                2. Not being able to stop or control worrying
              </label>
              <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <label
                  v-for="option in options"
                  :key="option.value"
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question2 === option.value
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question2"
                    :value="option.value"
                    class="sr-only"
                    required
                    @change="onAnswerChange(2)"
                  />
                  <div class="text-center">
                    <p class="font-medium text-gray-900">{{ option.label }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ option.value }} point{{ option.value !== 1 ? 's' : '' }}</p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-8 pt-6 border-t border-gray-200 sticky bottom-0 bg-white py-4">
              <button
                type="submit"
                class="px-8 py-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors duration-200 shadow-lg hover:shadow-xl disabled:bg-gray-400 disabled:cursor-not-allowed"
                :disabled="!allAnswered"
              >
                Calculate Score
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Results Section (Full Width Below) -->
    <div v-if="showResults" class="mt-16 space-y-8 animate-fadeIn">
      <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Your Results</h3>
        <div class="inline-block bg-emerald-100 rounded-2xl px-8 py-4 mt-4">
          <p class="text-sm text-gray-600 mb-1">Total Score</p>
          <p class="text-5xl font-bold text-emerald-600">{{ totalScore }}</p>
          <p class="text-sm text-gray-500 mt-1">out of 6</p>
        </div>
      </div>

      <!-- Interpretation -->
      <div :class="[
        'rounded-xl p-6 mb-6',
        totalScore >= 3 ? 'bg-amber-50 border-2 border-amber-200' : 'bg-green-50 border-2 border-green-200'
      ]">
        <div class="flex items-start space-x-3">
          <svg v-if="totalScore >= 3" class="w-6 h-6 text-amber-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <svg v-else class="w-6 h-6 text-green-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <h4 class="font-semibold text-lg mb-2" :class="totalScore >= 3 ? 'text-amber-900' : 'text-green-900'">
              {{ interpretationTitle }}
            </h4>
            <p class="text-gray-700 leading-relaxed">
              {{ interpretationText }}
            </p>
          </div>
        </div>
      </div>

      <!-- Performance Information -->
      <div class="bg-gray-50 rounded-xl p-6 mb-6">
        <h4 class="font-semibold text-lg text-gray-900 mb-4">About This Assessment</h4>
        <p class="text-gray-700 mb-4">
          Using a cut-off score of 3, the GAD-2 has a sensitivity of 86% and specificity of 83% for diagnosing generalized anxiety disorder.
        </p>
        <p class="text-gray-700">
          The GAD-2 also performs reasonably well as a screening tool for other common anxiety disorders including Panic Disorder, Social Anxiety Disorder, and Post-Traumatic Stress Disorder.
        </p>
      </div>

      <!-- Performance Table -->
      <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
          <h4 class="font-semibold text-gray-900">Performance as Screening Tool (Cut-off Score ≥3)</h4>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Disorder</th>
                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Sensitivity</th>
                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Specificity</th>
                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Likelihood Ratio</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="row in performanceData" :key="row.disorder" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">{{ row.disorder }}</td>
                <td class="px-6 py-4 text-sm text-center text-gray-700">{{ row.sensitivity }}</td>
                <td class="px-6 py-4 text-sm text-center text-gray-700">{{ row.specificity }}</td>
                <td class="px-6 py-4 text-sm text-center text-gray-700">{{ row.likelihoodRatio }}</td>
              </tr>
            </tbody>
          </table>
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
            This screening tool is not a diagnostic instrument. If you score 3 or higher, or if you have concerns about anxiety, 
            please consult with a qualified mental health professional for a comprehensive evaluation.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick } from 'vue'

// Refs for question elements
const q1 = ref(null)
const q2 = ref(null)

// Answer options
const options = [
  { label: 'Not at all', value: 0 },
  { label: 'Several days', value: 1 },
  { label: 'More than half the days', value: 2 },
  { label: 'Nearly every day', value: 3 }
]

// Performance data
const performanceData = [
  { disorder: 'Generalized Anxiety Disorder', sensitivity: '86%', specificity: '83%', likelihoodRatio: '5.0' },
  { disorder: 'Panic Disorder', sensitivity: '76%', specificity: '81%', likelihoodRatio: '4.1' },
  { disorder: 'Social Anxiety Disorder', sensitivity: '70%', specificity: '81%', likelihoodRatio: '3.6' },
  { disorder: 'Post-Traumatic Stress Disorder', sensitivity: '59%', specificity: '81%', likelihoodRatio: '3.1' },
  { disorder: 'Any Anxiety Disorder', sensitivity: '65%', specificity: '88%', likelihoodRatio: '5.2' }
]

// Reactive state
const answers = ref({
  question1: null as number | null,
  question2: null as number | null
})

const showResults = ref(false)
const totalScore = ref(0)

// Computed properties
const allAnswered = computed(() => {
  return answers.value.question1 !== null && answers.value.question2 !== null
})

const interpretationTitle = computed(() => {
  if (totalScore.value >= 3) {
    return 'Further Evaluation Recommended'
  }
  return 'Low Anxiety Symptoms'
})

const interpretationText = computed(() => {
  if (totalScore.value >= 3) {
    return 'Your score suggests possible anxiety concerns. A score of 3 or higher indicates that further diagnostic evaluation for generalized anxiety disorder is warranted. We recommend speaking with a qualified mental health professional for a comprehensive assessment.'
  }
  return 'Your score suggests minimal anxiety symptoms at this time. However, if you have any concerns about your mental health, please don\'t hesitate to reach out to a mental health professional.'
})

// Methods
const onAnswerChange = async (qNum: number) => {
  await nextTick()
  const nextQNum = qNum + 1
  const nextRef = getRefByNum(nextQNum)
  if (nextRef && nextRef.value) {
    nextRef.value.$el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const getRefByNum = (num: number) => {
  switch (num) {
    case 1: return q1
    case 2: return q2
    default: return null
  }
}

const calculateScore = () => {
  totalScore.value = (answers.value.question1 || 0) + (answers.value.question2 || 0)
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
    question2: null
  }
  showResults.value = false
  totalScore.value = 0
  
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  // TODO: Implement save functionality
  console.log('Saving results:', {
    assessment: 'GAD-2',
    score: totalScore.value,
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
input[type="radio"]:checked + div {
  transform: scale(1.02);
}

/* Table styling */
table {
  border-collapse: collapse;
}

/* Smooth transitions */
* {
  transition-property: background-color, border-color, transform;
  transition-duration: 200ms;
  transition-timing-function: ease-in-out;
}
</style>