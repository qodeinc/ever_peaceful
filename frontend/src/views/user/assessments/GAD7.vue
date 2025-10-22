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
              <h1 class="text-3xl font-bold text-gray-900 mb-2">GAD-7 Assessment</h1>
              <h2 class="text-lg text-emerald-600 font-semibold mb-3">Generalized Anxiety Disorder 7-item</h2>
              <p class="text-gray-600 leading-relaxed">
                The GAD-7 is an easy to perform initial screening tool for generalized anxiety disorder. 
                This assessment takes about 2-3 minutes to complete.
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
            <!-- Questions -->
            <div
              v-for="(question, index) in questions"
              :key="index"
              :ref="el => setQuestionRef(index, el)"
              class="pb-6 border-b border-gray-200 last:border-b-0"
            >
              <label class="block text-lg font-medium text-gray-900 mb-4">
                {{ index + 1 }}. {{ question }}
              </label>
              <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <label
                  v-for="option in options"
                  :key="option.value"
                  :class="[
                    'flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                    answers[index] === option.value
                      ? 'border-emerald-500 bg-emerald-50 shadow-md'
                      : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50'
                  ]"
                >
                  <input
                    type="radio"
                    v-model="answers[index]"
                    :value="option.value"
                    class="sr-only"
                    required
                    @change="onAnswerChange(index)"
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
          <p class="text-sm text-gray-500 mt-1">out of 21</p>
        </div>
      </div>

      <!-- Interpretation -->
      <div :class="[
        'rounded-xl p-6 mb-6',
        severityClass
      ]">
        <div class="flex items-start space-x-3">
          <svg v-if="totalScore >= 10" class="w-6 h-6 flex-shrink-0 mt-1" :class="severityIconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <svg v-else class="w-6 h-6 flex-shrink-0 mt-1" :class="severityIconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <h4 class="font-semibold text-lg mb-2" :class="severityTextColor">
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
        <h4 class="font-semibold text-lg text-gray-900 mb-4">Clinical Interpretation</h4>
        <div class="space-y-3">
          <p class="text-gray-700">
            <span class="font-semibold">Diagnostic Cut-off:</span> A score of 8 or greater represents a reasonable cut-point for identifying probable cases of generalized anxiety disorder. Using this cut-off, the GAD-7 has a sensitivity of 92% and specificity of 76%.
          </p>
          <p class="text-gray-700">
            <span class="font-semibold">Severity Levels:</span>
          </p>
          <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
            <li>0-4: Minimal Anxiety</li>
            <li>5-9: Mild Anxiety</li>
            <li>10-14: Moderate Anxiety</li>
            <li>15+: Severe Anxiety</li>
          </ul>
        </div>
      </div>

      <!-- Performance Information -->
      <div class="bg-gray-50 rounded-xl p-6 mb-6">
        <h4 class="font-semibold text-lg text-gray-900 mb-4">About This Assessment</h4>
        <p class="text-gray-700 mb-4">
          Although designed as a screening tool for generalized anxiety, the GAD-7 also performs reasonably well as a screening tool for three other common anxiety disorders—Panic Disorder, Social Anxiety Disorder, and Posttraumatic Stress Disorder.
        </p>
        <p class="text-gray-700">
          Based on recent meta-analysis, some experts recommend considering a cut-off of 8 to optimize sensitivity without compromising specificity.
        </p>
      </div>

      <!-- Performance Table -->
      <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
          <h4 class="font-semibold text-gray-900">Performance as Screening Tool (Cut-off Score ≥10)</h4>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Test</th>
                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Sensitivity</th>
                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Specificity</th>
                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Positive Likelihood Ratio</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="row in performanceData" :key="row.test" class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">{{ row.test }}</td>
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
            This screening tool is not a diagnostic instrument. If you score 8 or higher, or if you have concerns about anxiety, 
            please consult with a qualified mental health professional for a comprehensive evaluation.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

// Questions
const questions = [
  "Feeling nervous, anxious or on edge",
  "Not being able to stop or control worrying",
  "Worrying too much about different things",
  "Trouble relaxing",
  "Being so restless that it is hard to sit still",
  "Becoming easily annoyed or irritable",
  "Feeling afraid as if something awful might happen"
]

// Answer options
const options = [
  { label: 'Not at all', value: 0 },
  { label: 'Several days', value: 1 },
  { label: 'More than half the days', value: 2 },
  { label: 'Nearly every day', value: 3 }
]

// Performance data
const performanceData = [
  { test: 'Generalized Anxiety Disorder', sensitivity: '89%', specificity: '82%', likelihoodRatio: '5.1' },
  { test: 'Panic Disorder', sensitivity: '74%', specificity: '81%', likelihoodRatio: '3.9' },
  { test: 'Social Anxiety Disorder', sensitivity: '72%', specificity: '80%', likelihoodRatio: '3.6' },
  { test: 'Post-Traumatic Stress Disorder', sensitivity: '66%', specificity: '81%', likelihoodRatio: '3.5' },
  { test: 'Any Anxiety Disorder', sensitivity: '68%', specificity: '88%', likelihoodRatio: '5.5' }
]

// Reactive state
const answers = ref({})
const questionRefs = ref({})
const showResults = ref(false)
const totalScore = ref(0)

// Computed properties
const allAnswered = computed(() => {
  return Object.keys(answers.value).length === questions.length && 
         Object.values(answers.value).every(val => val !== null && val !== undefined)
})

const severityClass = computed(() => {
  if (totalScore.value >= 15) return 'bg-red-50 border-2 border-red-200'
  if (totalScore.value >= 10) return 'bg-orange-50 border-2 border-orange-200'
  if (totalScore.value >= 5) return 'bg-amber-50 border-2 border-amber-200'
  return 'bg-green-50 border-2 border-green-200'
})

const severityIconColor = computed(() => {
  if (totalScore.value >= 15) return 'text-red-600'
  if (totalScore.value >= 10) return 'text-orange-600'
  if (totalScore.value >= 5) return 'text-amber-600'
  return 'text-green-600'
})

const severityTextColor = computed(() => {
  if (totalScore.value >= 15) return 'text-red-900'
  if (totalScore.value >= 10) return 'text-orange-900'
  if (totalScore.value >= 5) return 'text-amber-900'
  return 'text-green-900'
})

const interpretationTitle = computed(() => {
  if (totalScore.value >= 15) return 'Severe Anxiety'
  if (totalScore.value >= 10) return 'Moderate Anxiety'
  if (totalScore.value >= 5) return 'Mild Anxiety'
  return 'Minimal Anxiety'
})

const interpretationText = computed(() => {
  if (totalScore.value >= 15) {
    return 'Your score indicates severe anxiety symptoms. We strongly recommend seeking evaluation and support from a qualified mental health professional as soon as possible. Effective treatments are available.'
  }
  if (totalScore.value >= 10) {
    return 'Your score indicates moderate anxiety symptoms. Further diagnostic evaluation is warranted. Please consider scheduling an appointment with a mental health professional for comprehensive assessment and appropriate treatment options.'
  }
  if (totalScore.value >= 8) {
    return 'Your score suggests mild to moderate anxiety symptoms. A score of 8 or higher indicates that further evaluation for generalized anxiety disorder is recommended. Please consult with a qualified mental health professional.'
  }
  if (totalScore.value >= 5) {
    return 'Your score suggests mild anxiety symptoms. While this may not require immediate clinical intervention, monitoring your symptoms and practicing self-care strategies is recommended. Consider speaking with a healthcare provider if symptoms persist or worsen.'
  }
  return 'Your score suggests minimal anxiety symptoms at this time. However, if you have any concerns about your mental health or if symptoms develop, please don\'t hesitate to reach out to a mental health professional.'
})

// Methods
const setQuestionRef = (index, el) => {
  if (el) {
    questionRefs.value[index] = el
  }
}

const onAnswerChange = async (index) => {
  await nextTick()
  const nextIndex = index + 1
  if (nextIndex < questions.length && questionRefs.value[nextIndex]) {
    questionRefs.value[nextIndex].$el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const calculateScore = () => {
  totalScore.value = Object.values(answers.value).reduce((sum, val) => sum + (val || 0), 0)
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
  answers.value = {}
  showResults.value = false
  totalScore.value = 0
  
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saving results:', {
    assessment: 'GAD-7',
    score: totalScore.value,
    severity: interpretationTitle.value,
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