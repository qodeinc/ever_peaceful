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
              <h1 class="text-3xl font-bold text-gray-900 mb-2">PHQ-9 Assessment</h1>
              <p class="text-gray-600">Patient Health Questionnaire - Depression Screening</p>
            </div>
          </div>
          <p class="text-gray-700">
            This assessment helps screen, diagnose, monitor and measure the severity of depression. It takes about 3-5 minutes to complete.
          </p>
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
            <!-- Questions -->
            <div class="space-y-6">
              <div 
                v-for="(question, index) in questions" 
                :key="index"
                :ref="el => setQuestionRef(index, el)"
                class="pb-6 border-b border-gray-200 last:border-b-0 last:pb-0"
              >
                <div class="mb-4">
                  <div class="inline-block bg-emerald-100 rounded-lg px-3 py-1 mb-2">
                    <span class="text-emerald-800 font-black text-lg">{{ index + 1 }}</span>
                  </div>
                  <label class="block text-base font-medium text-gray-900">
                    {{ question.text }}
                  </label>
                </div>
                
                <!-- Warning for Question 9 -->
                <div 
                  v-if="index === 8 && answers[index] !== null && answers[index] > 0"
                  class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg flex items-start space-x-2"
                >
                  <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  <p class="text-sm text-red-800">
                    <strong>Important:</strong> Your response indicates thoughts of self-harm. Please seek immediate help from a mental health professional or call a crisis helpline.
                  </p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                  <label
                    v-for="option in options"
                    :key="option.value"
                    :class="[
                      'flex flex-col items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all duration-200',
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
                    <span class="text-sm font-medium text-gray-900 text-center">{{ option.label }}</span>
                    <span class="text-xs text-gray-500 mt-1">+{{ option.value }}</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-8 pt-6 border-t border-gray-200 sticky bottom-0 bg-white py-4">
              <button
                type="submit"
                class="px-8 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors duration-200 shadow-md hover:shadow-lg disabled:bg-gray-400 disabled:cursor-not-allowed"
                :disabled="!allAnswered"
              >
                Calculate My Score
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Results Section (Full Width Below) -->
    <div v-if="showResults" class="mt-16 space-y-6 animate-fadeIn">
      <!-- Score Display -->
      <div class="bg-emerald-500 rounded-2xl p-8 text-white shadow-lg">
        <div class="text-center">
          <p class="text-emerald-100 text-sm font-medium uppercase tracking-wide mb-2">Your Total Score</p>
          <p class="text-6xl font-bold mb-2">{{ totalScore }}</p>
          <p class="text-emerald-100 text-sm">out of 27 points</p>
          <div class="mt-4 inline-block px-4 py-2 bg-white/20 rounded-full backdrop-blur-sm">
            <p class="text-sm font-semibold">{{ severityLevel }}</p>
          </div>
        </div>
      </div>

      <!-- Interpretation & Recommendations -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div :class="['px-6 py-4 border-l-4', severityColor]">
          <div class="flex items-start space-x-3">
            <component :is="severityIcon" :class="['w-6 h-6 flex-shrink-0 mt-1', severityIconColor]" />
            <div class="flex-1">
              <h3 class="text-xl font-bold text-gray-900 mb-2">{{ interpretationTitle }}</h3>
              <p class="text-gray-700 mb-4">{{ interpretationText }}</p>
              
              <!-- Treatment Recommendation -->
              <div class="bg-gray-50 rounded-lg p-4 mt-4">
                <p class="text-sm font-semibold text-gray-900 mb-2">Recommended Actions:</p>
                <p class="text-sm text-gray-700">{{ treatmentActions }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Severity Reference Table - Compact -->
      <details class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <summary class="px-6 py-4 cursor-pointer hover:bg-gray-50 transition-colors font-semibold text-gray-900 flex items-center justify-between">
          <span>View All Severity Levels & Recommendations</span>
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </summary>
        <div class="px-6 pb-4">
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="border-b-2 border-gray-200">
                <tr>
                  <th class="text-left py-3 font-semibold text-gray-900">Score Range</th>
                  <th class="text-left py-3 font-semibold text-gray-900">Severity</th>
                  <th class="text-left py-3 font-semibold text-gray-900">Recommended Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="row in severityTable" :key="row.range" class="hover:bg-gray-50">
                  <td class="py-3 font-medium text-gray-900">{{ row.range }}</td>
                  <td class="py-3 text-gray-700">{{ row.severity }}</td>
                  <td class="py-3 text-gray-600">{{ row.actions }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </details>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3">
        <button
          @click="resetAssessment"
          class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200"
        >
          Retake Assessment
        </button>
        <button
          @click="saveResults"
          class="flex-1 px-6 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors duration-200"
        >
          Save Results
        </button>
        <button
          v-if="totalScore >= 10"
          @click="bookAppointment"
          class="flex-1 px-6 py-3 bg-emerald-700 text-white font-medium rounded-lg hover:bg-emerald-800 transition-colors duration-200"
        >
          Book Appointment
        </button>
      </div>
    </div>

    <!-- Crisis Resources Banner -->
    <div v-if="showCrisisWarning" class="mt-6 bg-red-50 rounded-xl p-5 border-2 border-red-200">
      <div class="flex items-start space-x-3">
        <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div>
          <p class="font-bold text-red-900 mb-2">Immediate Help Available</p>
          <p class="text-sm text-red-800 mb-3">
            If you're having thoughts of self-harm or suicide, please reach out for help immediately:
          </p>
          <div class="space-y-2 text-sm">
            <p class="text-red-900"><strong>National Suicide Prevention Lifeline:</strong> 988 (24/7)</p>
            <p class="text-red-900"><strong>Crisis Text Line:</strong> Text "HELLO" to 741741</p>
            <p class="text-red-900"><strong>Emergency:</strong> Call 911 or go to nearest emergency room</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Disclaimer (Full Width) -->
    <div class="mt-16 bg-blue-50 rounded-xl p-6 border border-blue-200">
      <div class="flex items-start space-x-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="text-sm text-blue-900">
          <p class="font-semibold mb-1">Note</p>
          <p>
            This is a screening tool, not a diagnostic instrument. Please consult a qualified mental health professional for proper evaluation and treatment.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick } from 'vue'
import { CheckCircle, AlertTriangle, Info, AlertCircle, ChevronDown } from 'lucide-vue-next'

// Questions
const questions = [
  { text: 'Little interest or pleasure in doing things' },
  { text: 'Feeling down, depressed or hopeless' },
  { text: 'Trouble falling asleep, staying asleep, or sleeping too much' },
  { text: 'Feeling tired or having little energy' },
  { text: 'Poor appetite or overeating' },
  { text: "Feeling bad about yourself - or that you're a failure or have let yourself or your family down" },
  { text: 'Trouble concentrating on things, such as reading the newspaper or watching television' },
  { text: 'Moving or speaking so slowly that other people could have noticed. Or, the opposite - being so fidgety or restless that you have been moving around a lot more than usual' },
  { text: 'Thoughts that you would be better off dead or of hurting yourself in some way' }
]

// Answer options
const options = [
  { label: 'Not at all', value: 0 },
  { label: 'Several days', value: 1 },
  { label: 'More than half the days', value: 2 },
  { label: 'Nearly every day', value: 3 }
]

// Severity table
const severityTable = [
  { range: '0 – 4', severity: 'None-minimal', actions: 'None' },
  { range: '5 – 9', severity: 'Mild', actions: 'Watchful waiting; repeat PHQ-9 at follow-up' },
  { range: '10 – 14', severity: 'Moderate', actions: 'Treatment plan, considering counseling, follow-up and/or pharmacotherapy' },
  { range: '15 – 19', severity: 'Moderately Severe', actions: 'Active treatment with pharmacotherapy and/or psychotherapy' },
  { range: '20 – 27', severity: 'Severe', actions: 'Immediate pharmacotherapy and expedited referral to mental health specialist' }
]

// Reactive state
const answers = ref<(number | null)[]>(Array(9).fill(null))
const questionRefs = ref({})
const showResults = ref(false)
const totalScore = ref(0)

// Computed properties
const allAnswered = computed(() => {
  return answers.value.every(answer => answer !== null)
})

const severityLevel = computed(() => {
  const score = totalScore.value
  if (score <= 4) return 'None-Minimal'
  if (score <= 9) return 'Mild Depression'
  if (score <= 14) return 'Moderate Depression'
  if (score <= 19) return 'Moderately Severe Depression'
  return 'Severe Depression'
})

const severityColor = computed(() => {
  const score = totalScore.value
  if (score <= 4) return 'border-green-500'
  if (score <= 9) return 'border-yellow-500'
  if (score <= 14) return 'border-orange-500'
  if (score <= 19) return 'border-red-500'
  return 'border-red-700'
})

const severityIcon = computed(() => {
  const score = totalScore.value
  if (score <= 4) return CheckCircle
  if (score <= 9) return Info
  if (score <= 14) return AlertCircle
  return AlertTriangle
})

const severityIconColor = computed(() => {
  const score = totalScore.value
  if (score <= 4) return 'text-green-600'
  if (score <= 9) return 'text-yellow-600'
  if (score <= 14) return 'text-orange-600'
  if (score <= 19) return 'text-red-600'
  return 'text-red-700'
})

const interpretationTitle = computed(() => {
  const score = totalScore.value
  if (score <= 4) return 'Minimal or No Depression'
  if (score <= 9) return 'Mild Depression'
  if (score <= 14) return 'Moderate Depression'
  if (score <= 19) return 'Moderately Severe Depression'
  return 'Severe Depression'
})

const interpretationText = computed(() => {
  const score = totalScore.value
  if (score <= 4) return 'Your responses indicate minimal or no depression symptoms at this time. Continue monitoring your mental health and reach out if you notice changes.'
  if (score <= 9) return 'Your responses suggest mild depression symptoms. Consider monitoring these symptoms and repeating this assessment at your next follow-up.'
  if (score <= 14) return 'Your responses indicate moderate depression. We recommend developing a treatment plan that may include counseling, follow-up appointments, and possibly medication.'
  if (score <= 19) return 'Your responses suggest moderately severe depression. Active treatment with medication and/or psychotherapy is recommended.'
  return 'Your responses indicate severe depression. Immediate treatment is recommended, including medication and potentially expedited referral to a mental health specialist.'
})

const treatmentActions = computed(() => {
  const score = totalScore.value
  if (score <= 4) return 'No specific action needed at this time.'
  if (score <= 9) return 'Watchful waiting and repeat assessment at follow-up appointments.'
  if (score <= 14) return 'Consider counseling, regular follow-up, and discuss treatment options with a healthcare provider.'
  if (score <= 19) return 'Begin active treatment with medication and/or psychotherapy as recommended by a mental health professional.'
  return 'Initiate immediate treatment and seek expedited referral to a mental health specialist for comprehensive care.'
})

const showCrisisWarning = computed(() => {
  return answers.value[8] !== null && answers.value[8]! > 0
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
  totalScore.value = answers.value.reduce((sum, answer) => sum + (answer || 0), 0)
  showResults.value = true
  
  setTimeout(() => {
    const resultsElement = document.querySelector('.animate-fadeIn')
    if (resultsElement) {
      resultsElement.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }, 100)
}

const resetAssessment = () => {
  answers.value = Array(9).fill(null)
  showResults.value = false
  totalScore.value = 0
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saving results:', {
    assessment: 'PHQ-9',
    score: totalScore.value,
    severity: severityLevel.value,
    date: new Date().toISOString(),
    answers: answers.value
  })
  alert('Results saved successfully!')
}

const bookAppointment = () => {
  console.log('Navigating to booking...')
  alert('This will navigate to the appointment booking page.')
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

details > summary {
  list-style: none;
}

details > summary::-webkit-details-marker {
  display: none;
}

details[open] svg {
  transform: rotate(180deg);
}

details svg {
  transition: transform 0.2s;
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