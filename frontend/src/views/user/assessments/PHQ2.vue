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
              <h1 class="text-3xl font-bold text-gray-900 mb-2">Patient Health Questionnaire-2 (PHQ-2)</h1>
              <h2 class="text-lg text-emerald-600 font-semibold mb-3">Screen for Depression</h2>
              <p class="text-gray-600 leading-relaxed">
                The PHQ-2 inquires about the frequency of depressed mood and anhedonia over the past two weeks. The PHQ-2 includes the first two items of the PHQ-9. The purpose of the PHQ-2 is to screen for depression in a "first-step" approach. Patients who screen positive should be further evaluated with the PHQ-9 to determine whether they meet criteria for a depressive disorder.
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
        <form @submit.prevent="calculateScore" class="space-y-6">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
            <!-- Question 1 -->
            <div ref="q1" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">1</span>
                </div>
                <label class="block text-base font-medium text-gray-900">
                  Little interest or pleasure in doing things
                </label>
              </div>
              <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                <label
                  v-for="option in options"
                  :key="option.value"
                  :class="[
                    'flex flex-col items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all duration-200',
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
                  <span class="text-sm font-medium text-gray-900 text-center">{{ option.label }}</span>
                  <span class="text-xs text-gray-500 mt-1">+{{ option.value }}</span>
                </label>
              </div>
            </div>

            <!-- Question 2 -->
            <div ref="q2" class="pb-6">
              <div class="mb-4">
                <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                  <span class="text-emerald-800 font-black text-lg">2</span>
                </div>
                <label class="block text-base font-medium text-gray-900">
                  Feeling down, depressed or hopeless
                </label>
              </div>
              <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                <label
                  v-for="option in options"
                  :key="option.value"
                  :class="[
                    'flex flex-col items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all duration-200',
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
                  <span class="text-sm font-medium text-gray-900 text-center">{{ option.label }}</span>
                  <span class="text-xs text-gray-500 mt-1">+{{ option.value }}</span>
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
                Calculate Score
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
          <p class="text-sm text-gray-600 mb-1">PHQ-2 Score</p>
          <p class="text-5xl font-bold text-emerald-600">{{ totalScore }}</p>
          <p class="text-sm text-gray-500 mt-1">out of 6</p>
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
        <h4 class="font-semibold text-lg text-gray-900 mb-4">About PHQ-2 Scoring</h4>
        <div class="space-y-3">
          <p class="text-gray-700">
            <span class="font-semibold">Interpretation:</span> A PHQ-2 score ranges from 0-6. The authors identified a score of 3 as the optimal cutpoint when using the PHQ-2 to screen for depression. If the score is 3 or greater, major depressive disorder is likely. Patients who screen positive should be further evaluated with the PHQ-9, other diagnostic instruments, or direct interview to determine whether they meet criteria for a depressive disorder.
          </p>
          <p class="text-gray-700">
            <span class="font-semibold">Next Steps:</span> A positive screen warrants a comprehensive clinical assessment to determine the presence and severity of a depressive disorder and appropriate treatment recommendations.
          </p>
        </div>
      </div>

      <!-- Operating Characteristics Table -->
      <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
          <h4 class="font-semibold text-gray-900">Operating Characteristics of PHQ-2 as a Screener for Depressive Disorders</h4>
        </div>
        <div class="overflow-x-auto">
          <div class="grid grid-cols-1 gap-4 mb-4">
            <div class="bg-blue-50 p-4 rounded-lg">
              <h5 class="font-semibold text-blue-900 mb-2">Major Depressive Disorder (7% Prevalence)</h5>
              <table class="w-full text-sm">
                <thead class="border-b border-blue-200">
                  <tr>
                    <th class="text-left py-2 font-semibold text-blue-900">PHQ-2 Score</th>
                    <th class="text-center py-2 font-semibold text-blue-900">Sensitivity</th>
                    <th class="text-center py-2 font-semibold text-blue-900">Specificity</th>
                    <th class="text-center py-2 font-semibold text-blue-900">Positive Predictive Value (PPV*)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                  <tr v-for="(row, i) in majorDepressiveData" :key="i" class="hover:bg-blue-50">
                    <td class="py-2 text-left text-blue-800">{{ row.score }}</td>
                    <td class="py-2 text-center text-blue-700">{{ row.sensitivity }}</td>
                    <td class="py-2 text-center text-blue-700">{{ row.specificity }}</td>
                    <td class="py-2 text-center text-blue-700">{{ row.ppv }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
              <h5 class="font-semibold text-green-900 mb-2">Any Depressive Disorder (18% Prevalence)</h5>
              <table class="w-full text-sm">
                <thead class="border-b border-green-200">
                  <tr>
                    <th class="text-left py-2 font-semibold text-green-900">PHQ-2 Score</th>
                    <th class="text-center py-2 font-semibold text-green-900">Sensitivity</th>
                    <th class="text-center py-2 font-semibold text-green-900">Specificity</th>
                    <th class="text-center py-2 font-semibold text-green-900">Positive Predictive Value (PPV*)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-green-100">
                  <tr v-for="(row, i) in anyDepressiveData" :key="'any-' + i" class="hover:bg-green-50">
                    <td class="py-2 text-left text-green-800">{{ row.score }}</td>
                    <td class="py-2 text-center text-green-700">{{ row.sensitivity }}</td>
                    <td class="py-2 text-center text-green-700">{{ row.specificity }}</td>
                    <td class="py-2 text-center text-green-700">{{ row.ppv }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
          <p class="text-sm text-gray-600 italic">*Because the PPV varies with the prevalence of depression, the PPV will be higher in settings with a higher prevalence of depression and lower in settings with a lower prevalence.</p>
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

// Answer options
const options = [
  { label: 'Not at all', value: 0 },
  { label: 'Several days', value: 1 },
  { label: 'More than half the days', value: 2 },
  { label: 'Nearly every day', value: 3 }
]

// Table data
const majorDepressiveData = [
  { score: 1, sensitivity: '97.6%', specificity: '59.2%', ppv: '15.4' },
  { score: 2, sensitivity: '92.7%', specificity: '73.7%', ppv: '21.1' },
  { score: 3, sensitivity: '82.9%', specificity: '90.0%', ppv: '38.4' },
  { score: 4, sensitivity: '73.2%', specificity: '93.3%', ppv: '45.4' },
  { score: 5, sensitivity: '53.7%', specificity: '96.8%', ppv: '56.4' },
  { score: 6, sensitivity: '26.8%', specificity: '99.4%', ppv: '78.6' }
]

const anyDepressiveData = [
  { score: 1, sensitivity: '90.6%', specificity: '65.4%', ppv: '36.9' },
  { score: 2, sensitivity: '82.1%', specificity: '80.4%', ppv: '48.3' },
  { score: 3, sensitivity: '62.3%', specificity: '95.4%', ppv: '75.0' },
  { score: 4, sensitivity: '50.9%', specificity: '97.9%', ppv: '81.2' },
  { score: 5, sensitivity: '31.1%', specificity: '98.7%', ppv: '84.6' },
  { score: 6, sensitivity: '12.3%', specificity: '99.8%', ppv: '92.9' }
]

// Reactive state
const answers = ref({
  question1: null,
  question2: null
})
const showResults = ref(false)
const totalScore = ref(0)

// Computed properties
const allAnswered = computed(() => {
  return answers.value.question1 !== null && answers.value.question2 !== null
})

const isPositiveScreen = computed(() => {
  return totalScore.value >= 3
})

const interpretationTitle = computed(() => {
  if (isPositiveScreen.value) {
    return 'Positive Screen - Further Evaluation Recommended'
  }
  return 'Negative Screen'
})

const interpretationText = computed(() => {
  if (isPositiveScreen.value) {
    return `Your PHQ-2 score of ${totalScore.value} is 3 or greater, indicating that major depressive disorder is likely. You should be further evaluated with the PHQ-9, other diagnostic instruments, or direct interview to determine whether you meet criteria for a depressive disorder. A comprehensive clinical assessment is recommended.`
  }
  return `Your PHQ-2 score of ${totalScore.value} is below 3, suggesting a low likelihood of depression at this time. However, if you have any concerns about your mood or mental health, please discuss them with a healthcare provider.`
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
  console.log('Saving results:', {
    assessment: 'PHQ-2',
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