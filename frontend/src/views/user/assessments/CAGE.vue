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
              <h1 class="text-3xl font-bold text-gray-900 mb-2">CAGE Assessment</h1>
              <h2 class="text-lg text-emerald-600 font-semibold mb-3">CAGE Alcohol Questionnaire</h2>
              <p class="text-gray-600 leading-relaxed">
                CAGE is a 4-item questionnaire that can indicate potential problems with alcohol use. 
                This brief screening takes less than 1 minute to complete.
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
              <p class="font-semibold mb-1">Lifetime Alcohol Use</p>
              <p>
                Reflect on your experiences with alcohol throughout your life.
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
                <div class="inline-flex items-center justify-center w-10 h-10 bg-emerald-100 rounded-lg mb-2">
                  <span class="text-lg font-black text-emerald-600">C</span>
                </div>
                <label class="block text-lg font-medium text-gray-900">
                  Have you ever felt you should <span class="font-bold text-emerald-600">Cut down</span> on your drinking?
                </label>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <label
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question1 === true
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question1"
                    :value="true"
                    class="sr-only"
                    required
                    @change="onAnswerChange(1)"
                  />
                  <div class="text-center">
                    <p class="font-semibold text-gray-900">Yes</p>
                    <p class="text-sm text-gray-500 mt-1">1 point</p>
                  </div>
                </label>
                <label
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question1 === false
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question1"
                    :value="false"
                    class="sr-only"
                    required
                    @change="onAnswerChange(1)"
                  />
                  <div class="text-center">
                    <p class="font-semibold text-gray-900">No</p>
                    <p class="text-sm text-gray-500 mt-1">0 points</p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Question 2 -->
            <div ref="q2" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-flex items-center justify-center w-10 h-10 bg-emerald-100 rounded-lg mb-2">
                  <span class="text-lg font-black text-emerald-600">A</span>
                </div>
                <label class="block text-lg font-medium text-gray-900">
                  Have people <span class="font-bold text-emerald-600">Annoyed</span> you by criticizing your drinking?
                </label>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <label
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question2 === true
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question2"
                    :value="true"
                    class="sr-only"
                    required
                    @change="onAnswerChange(2)"
                  />
                  <div class="text-center">
                    <p class="font-semibold text-gray-900">Yes</p>
                    <p class="text-sm text-gray-500 mt-1">1 point</p>
                  </div>
                </label>
                <label
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question2 === false
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question2"
                    :value="false"
                    class="sr-only"
                    required
                    @change="onAnswerChange(2)"
                  />
                  <div class="text-center">
                    <p class="font-semibold text-gray-900">No</p>
                    <p class="text-sm text-gray-500 mt-1">0 points</p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Question 3 -->
            <div ref="q3" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-flex items-center justify-center w-10 h-10 bg-emerald-100 rounded-lg mb-2">
                  <span class="text-lg font-black text-emerald-600">G</span>
                </div>
                <label class="block text-lg font-medium text-gray-900">
                  Have you ever felt <span class="font-bold text-emerald-600">Guilty</span> about your drinking?
                </label>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <label
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question3 === true
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question3"
                    :value="true"
                    class="sr-only"
                    required
                    @change="onAnswerChange(3)"
                  />
                  <div class="text-center">
                    <p class="font-semibold text-gray-900">Yes</p>
                    <p class="text-sm text-gray-500 mt-1">1 point</p>
                  </div>
                </label>
                <label
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question3 === false
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question3"
                    :value="false"
                    class="sr-only"
                    required
                    @change="onAnswerChange(3)"
                  />
                  <div class="text-center">
                    <p class="font-semibold text-gray-900">No</p>
                    <p class="text-sm text-gray-500 mt-1">0 points</p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Question 4 -->
            <div ref="q4" class="pb-6 border-b border-gray-200">
              <div class="mb-4">
                <div class="inline-flex items-center justify-center w-10 h-10 bg-emerald-100 rounded-lg mb-2">
                  <span class="text-lg font-black text-emerald-600">E</span>
                </div>
                <label class="block text-lg font-medium text-gray-900">
                  Have you ever had a drink first thing in the morning (<span class="font-bold text-emerald-600">Eye opener</span>)?
                </label>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <label
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question4 === true
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question4"
                    :value="true"
                    class="sr-only"
                    required
                    @change="onAnswerChange(4)"
                  />
                  <div class="text-center">
                    <p class="font-semibold text-gray-900">Yes</p>
                    <p class="text-sm text-gray-500 mt-1">1 point</p>
                  </div>
                </label>
                <label
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers.question4 === false
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers.question4"
                    :value="false"
                    class="sr-only"
                    required
                    @change="onAnswerChange(4)"
                  />
                  <div class="text-center">
                    <p class="font-semibold text-gray-900">No</p>
                    <p class="text-sm text-gray-500 mt-1">0 points</p>
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

      <!-- Interpretation -->
      <div :class="[
        'rounded-xl p-6 mb-6',
        totalScore >= 2 ? 'bg-red-50 border-2 border-red-200' : totalScore >= 1 ? 'bg-amber-50 border-2 border-amber-200' : 'bg-green-50 border-2 border-green-200'
      ]">
        <div class="flex items-start space-x-3">
          <svg v-if="totalScore >= 1" class="w-6 h-6 flex-shrink-0 mt-1" :class="totalScore >= 2 ? 'text-red-600' : 'text-amber-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <svg v-else class="w-6 h-6 text-green-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <h4 class="font-semibold text-lg mb-2" :class="totalScore >= 2 ? 'text-red-900' : totalScore >= 1 ? 'text-amber-900' : 'text-green-900'">
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
        <h4 class="font-semibold text-lg text-gray-900 mb-4">About CAGE Scoring</h4>
        <div class="space-y-3">
          <p class="text-gray-700">
            <span class="font-semibold">Interpretation Guidelines:</span>
          </p>
          <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
            <li><span class="font-semibold">Score of 0:</span> Low likelihood of alcohol problems</li>
            <li><span class="font-semibold">Score of 1:</span> Possible alcohol problem - further assessment recommended</li>
            <li><span class="font-semibold">Score of 2-4:</span> High likelihood of alcohol dependence - comprehensive evaluation needed</li>
          </ul>
          <p class="text-gray-700 mt-4">
            The CAGE questionnaire is a screening tool. A score of 2 or more is considered clinically significant and indicates the need for further diagnostic assessment.
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
            This screening tool is not a diagnostic instrument. If you score 1 or higher, or have concerns about your alcohol use, 
            please consult with a qualified healthcare professional for a comprehensive evaluation.
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

const interpretationTitle = computed(() => {
  if (totalScore.value >= 2) return 'High Likelihood of Alcohol Dependence'
  if (totalScore.value === 1) return 'Possible Alcohol Problem'
  return 'Low Likelihood of Alcohol Problems'
})

const interpretationText = computed(() => {
  if (totalScore.value >= 2) {
    return `Your score of ${totalScore.value} is clinically significant and suggests a high likelihood of alcohol dependence. A comprehensive evaluation by a healthcare professional is strongly recommended to assess for alcohol use disorder and discuss appropriate treatment options.`
  }
  if (totalScore.value === 1) {
    return 'Your score suggests a possible alcohol problem. Further assessment is recommended to determine if you may have an alcohol use issue. Please consider discussing your alcohol use with a healthcare provider.'
  }
  return 'Your score suggests a low likelihood of alcohol problems at this time. However, if you have any concerns about your alcohol use, please don\'t hesitate to discuss them with a healthcare provider.'
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
  totalScore.value = 
    (answers.value.question1 ? 1 : 0) +
    (answers.value.question2 ? 1 : 0) +
    (answers.value.question3 ? 1 : 0) +
    (answers.value.question4 ? 1 : 0)
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
    assessment: 'CAGE',
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

/* Smooth transitions */
* {
  transition-property: background-color, border-color, transform;
  transition-duration: 200ms;
  transition-timing-function: ease-in-out;
}
</style>