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
              <h1 class="text-3xl font-bold text-gray-900 mb-2">AUDIT-C Assessment</h1>
              <h2 class="text-lg text-emerald-600 font-semibold mb-3">Alcohol Use Disorders Identification Test</h2>
              <p class="text-gray-600 leading-relaxed">
                The AUDIT-C is a brief alcohol screening tool that identifies hazardous drinking or active alcohol use disorders. 
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
              <p class="font-semibold mb-1">Scoring Note</p>
              <p>
                Different thresholds apply: ≥4 for men, ≥3 for women. Please select your gender for accurate scoring.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side: Scrollable Form -->
      <div class="space-y-6 max-h-screen overflow-y-auto pr-4 lg:pr-0">
        <!-- Gender Selection -->
        <div ref="genderSection" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Please select your gender</h3>
          <p class="text-sm text-gray-600 mb-4">Different scoring thresholds apply based on gender</p>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label
              :class="[
                'flex items-center justify-center p-6 border-2 rounded-xl cursor-pointer transition-all duration-200',
                gender === 'male'
                  ? 'border-emerald-500 bg-emerald-50 shadow-md'
                  : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
              ]"
            >
              <input
                type="radio"
                v-model="gender"
                value="male"
                class="sr-only"
                @change="onGenderChange"
              />
              <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <p class="font-semibold text-gray-900">Male</p>
                <p class="text-sm text-gray-500 mt-1">Threshold: ≥4 points</p>
              </div>
            </label>
            <label
              :class="[
                'flex items-center justify-center p-6 border-2 rounded-xl cursor-pointer transition-all duration-200',
                gender === 'female'
                  ? 'border-emerald-500 bg-emerald-50 shadow-md'
                  : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
              ]"
            >
              <input
                type="radio"
                v-model="gender"
                value="female"
                class="sr-only"
                @change="onGenderChange"
              />
              <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <p class="font-semibold text-gray-900">Female</p>
                <p class="text-sm text-gray-500 mt-1">Threshold: ≥3 points</p>
              </div>
            </label>
          </div>
        </div>

        <!-- Assessment Form -->
        <form v-if="gender" @submit.prevent="calculateScore" class="space-y-8">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
            <!-- Question 1 -->
            <div ref="q1" class="pb-6 border-b border-gray-200">
              <label class="block text-lg font-medium text-gray-900 mb-4">
                1. How often do you have a drink containing alcohol?
              </label>
              <div class="space-y-3">
                <label
                  v-for="option in question1Options"
                  :key="option.value"
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
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
                  <span class="font-medium text-gray-900">{{ option.label }}</span>
                  <span class="text-sm font-semibold text-emerald-600">{{ option.value }} point{{ option.value !== 1 ? 's' : '' }}</span>
                </label>
              </div>
            </div>

            <!-- Question 2 -->
            <div ref="q2" class="pb-6 border-b border-gray-200">
              <label class="block text-lg font-medium text-gray-900 mb-4">
                2. How many drinks containing alcohol do you have on a typical day when you are drinking?
              </label>
              <div class="space-y-3">
                <label
                  v-for="option in question2Options"
                  :key="option.value"
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
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
                  <span class="font-medium text-gray-900">{{ option.label }}</span>
                  <span class="text-sm font-semibold text-emerald-600">{{ option.value }} point{{ option.value !== 1 ? 's' : '' }}</span>
                </label>
              </div>
            </div>

            <!-- Question 3 -->
            <div ref="q3" class="pb-6 border-b border-gray-200">
              <label class="block text-lg font-medium text-gray-900 mb-4">
                3. How often do you have six or more drinks on one occasion?
              </label>
              <div class="space-y-3">
                <label
                  v-for="option in question3Options"
                  :key="option.value"
                  :class="[
                    'flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question3 === option.value
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question3"
                    :value="option.value"
                    class="sr-only"
                    required
                    @change="onAnswerChange(3)"
                  />
                  <span class="font-medium text-gray-900">{{ option.label }}</span>
                  <span class="text-sm font-semibold text-emerald-600">{{ option.value }} point{{ option.value !== 1 ? 's' : '' }}</span>
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
          <p class="text-sm text-gray-500 mt-1">out of 12</p>
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

      <!-- Special Note for Question 1 Only -->
      <div v-if="allPointsFromQuestion1" class="bg-blue-50 rounded-xl p-6 mb-6 border border-blue-200">
        <div class="flex items-start space-x-3">
          <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="text-sm text-blue-900">
            <p class="font-semibold mb-1">Clinical Note</p>
            <p>
              All points are from Question 1 only. The patient is likely drinking below recommended limits. 
              The medical provider should review the patient's alcohol intake during the past few months.
            </p>
          </div>
        </div>
      </div>

      <!-- Clinical Information -->
      <div class="bg-gray-50 rounded-xl p-6 mb-6">
        <h4 class="font-semibold text-lg text-gray-900 mb-4">About AUDIT-C Scoring</h4>
        <div class="space-y-3">
          <p class="text-gray-700">
            <span class="font-semibold">Scoring Thresholds:</span>
          </p>
          <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
            <li><span class="font-semibold">Men:</span> A score of 4 or more is considered positive for hazardous drinking or active alcohol use disorders</li>
            <li><span class="font-semibold">Women:</span> A score of 3 or more is considered positive for hazardous drinking or active alcohol use disorders</li>
          </ul>
          <p class="text-gray-700 mt-4">
            <span class="font-semibold">Note:</span> The AUDIT-C is a screening tool. Positive screens warrant further evaluation and discussion with a healthcare provider about alcohol use and potential interventions.
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
            This screening tool is not a diagnostic instrument. If you screen positive or have concerns about your alcohol use, 
            please consult with a qualified healthcare professional for a comprehensive evaluation and appropriate interventions.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

// Refs for sections
const genderSection = ref(null)
const q1 = ref(null)
const q2 = ref(null)
const q3 = ref(null)

// Gender selection
const gender = ref(null)

// Question options
const question1Options = [
  { label: 'Never', value: 0 },
  { label: 'Monthly or less', value: 1 },
  { label: '2-4 times a month', value: 2 },
  { label: '2-3 times a week', value: 3 },
  { label: '4 or more times a week', value: 4 }
]

const question2Options = [
  { label: '1 or 2', value: 0 },
  { label: '3 or 4', value: 1 },
  { label: '5 or 6', value: 2 },
  { label: '7 to 9', value: 3 },
  { label: '10 or more', value: 4 }
]

const question3Options = [
  { label: 'Never', value: 0 },
  { label: 'Less than monthly', value: 1 },
  { label: 'Monthly', value: 2 },
  { label: 'Weekly', value: 3 },
  { label: 'Daily or almost daily', value: 4 }
]

// Reactive state
const answers = ref({
  question1: null,
  question2: null,
  question3: null
})
const showResults = ref(false)
const totalScore = ref(0)

// Computed properties
const allAnswered = computed(() => {
  return gender.value && 
         answers.value.question1 !== null &&
         answers.value.question2 !== null &&
         answers.value.question3 !== null
})

const threshold = computed(() => {
  return gender.value === 'male' ? 4 : 3
})

const isPositiveScreen = computed(() => {
  return totalScore.value >= threshold.value
})

const allPointsFromQuestion1 = computed(() => {
  return totalScore.value > 0 && 
         answers.value.question2 === 0 && 
         answers.value.question3 === 0
})

const interpretationTitle = computed(() => {
  if (isPositiveScreen.value) {
    return 'Positive Screen for Hazardous Drinking'
  }
  return 'Negative Screen'
})

const interpretationText = computed(() => {
  const genderText = gender.value === 'male' ? 'men' : 'women'
  const thresholdText = threshold.value
  
  if (isPositiveScreen.value) {
    return `Your score of ${totalScore.value} is at or above the threshold of ${thresholdText} for ${genderText}, indicating possible hazardous drinking or an active alcohol use disorder. Further evaluation by a healthcare professional is recommended to discuss your alcohol use and explore appropriate interventions or treatment options.`
  }
  return `Your score of ${totalScore.value} is below the threshold of ${thresholdText} for ${genderText}. This suggests you are likely drinking within recommended limits. However, if you have any concerns about your alcohol use, please discuss them with a healthcare provider.`
})

// Methods
const onGenderChange = async () => {
  await nextTick()
  if (q1.value) {
    q1.value.$el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

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
    default: return null
  }
}

const calculateScore = () => {
  totalScore.value = (answers.value.question1 || 0) + 
                     (answers.value.question2 || 0) + 
                     (answers.value.question3 || 0)
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
    question3: null
  }
  gender.value = null
  showResults.value = false
  totalScore.value = 0
  
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saving results:', {
    assessment: 'AUDIT-C',
    score: totalScore.value,
    gender: gender.value,
    threshold: threshold.value,
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
input[type="radio"]:checked + div,
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