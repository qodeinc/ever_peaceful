<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">PHQ-9</h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">Patient Health Questionnaire-9. A comprehensive tool to screen, diagnose, monitor and measure the severity of depression.</p>
        <div class="text-sm text-gray-400">9 questions · 3-5 minutes</div>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-16">
        <div class="flex items-center gap-2">
          <div 
            v-for="i in 9" 
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredQuestions >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">Question {{ Math.min(answeredQuestions + 1, 9) }} of 9</div>
      </div>

      <!-- Questions -->
      <form @submit.prevent="calculateScore" class="space-y-24">
        <div
          v-for="(question, index) in questions"
          :key="index"
          :ref="el => setQuestionRef(index, el)"
          class="scroll-mt-32"
        >
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question {{ index + 1 }}</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              {{ question.text }}
            </h2>
            <p class="text-gray-500">Over the last 2 weeks, how often have you been bothered by this?</p>
          </div>

          <!-- Crisis Warning for Question 9 -->
          <div 
            v-if="index === 8 && answers[index] !== null && answers[index] > 0"
            class="mb-6 p-6 bg-red-50 border-2 border-red-200"
          >
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <div>
                <p class="text-base font-medium text-red-900 mb-2">Immediate help is available</p>
                <p class="text-sm text-red-800 mb-3">Your response indicates thoughts of self-harm. Please reach out for help immediately.</p>
                <div class="space-y-1 text-sm text-red-900">
                  <p><strong>Crisis Line:</strong> 988 (24/7)</p>
                  <p><strong>Text:</strong> "HELLO" to 741741</p>
                  <p><strong>Emergency:</strong> Call 911</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in options"
              :key="option.value"
              :class="[
                'group block p-6 border-2 cursor-pointer transition-all duration-200',
                answers[index] === option.value
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
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
              <div class="flex items-center justify-between">
                <span class="text-lg text-gray-900">{{ option.label }}</span>
                <div :class="[
                  'w-5 h-5 border-2 rounded-full transition-all',
                  answers[index] === option.value
                    ? 'border-emerald-600 bg-emerald-600'
                    : 'border-gray-300 group-hover:border-gray-400'
                ]">
                  <div v-if="answers[index] === option.value" class="w-full h-full rounded-full bg-white scale-[0.4]" />
                </div>
              </div>
            </label>
          </div>
        </div>

        <!-- Submit Button -->
        <div v-if="allAnswered" class="pt-12 border-t border-gray-200">
          <button
            type="submit"
            class="w-full bg-emerald-600 text-white py-5 text-lg hover:bg-emerald-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            View Results
          </button>
        </div>
      </form>

      <!-- Results Section -->
      <div v-if="showResults" class="mt-32 pt-32 border-t border-gray-200">
        <!-- Score -->
        <div class="mb-20">
          <div class="text-sm text-gray-400 mb-4">Your Score</div>
          <div class="flex items-baseline gap-4 mb-8">
            <span class="text-8xl font-light text-gray-900">{{ totalScore }}</span>
            <span class="text-3xl text-gray-400">/ 27</span>
          </div>
          
          <div class="max-w-2xl">
            <h3 class="text-2xl font-light text-gray-900 mb-4">{{ severityLevel }}</h3>
            <p class="text-lg text-gray-600 leading-relaxed mb-6">{{ interpretationText }}</p>
            
            <div class="p-6 bg-gray-50 border-l-4" :class="severityBorderColor">
              <p class="text-sm font-medium text-gray-900 mb-2">Recommended Actions</p>
              <p class="text-sm text-gray-700">{{ treatmentActions }}</p>
            </div>
          </div>
        </div>

        <!-- Severity Scale -->
        <div class="mb-20 py-12 border-y border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">Depression Severity Scale</h4>
          <div class="space-y-4">
            <div 
              v-for="level in severityLevels" 
              :key="level.range"
              :class="[
                'p-6 border-2 transition-all',
                isInRange(level.min, level.max) ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200'
              ]"
            >
              <div class="flex items-start justify-between mb-3">
                <div>
                  <h5 class="text-lg font-medium text-gray-900 mb-1">{{ level.title }}</h5>
                  <p class="text-sm text-gray-600">{{ level.actions }}</p>
                </div>
                <div class="text-right">
                  <div class="text-2xl font-light text-gray-900">{{ level.range }}</div>
                  <div class="text-sm text-gray-500">points</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Clinical Information -->
        <div class="mb-20 py-12 border-b border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">About PHQ-9 Scoring</h4>
          <div class="max-w-2xl space-y-6 text-gray-600">
            <p>
              <span class="font-medium text-gray-900">Purpose:</span> The PHQ-9 is a multipurpose instrument for screening, diagnosing, monitoring and measuring the severity of depression.
            </p>
            <p>
              <span class="font-medium text-gray-900">Scoring:</span> Each of the 9 items is scored from 0 (not at all) to 3 (nearly every day), providing a total score range of 0-27.
            </p>
            <p>
              <span class="font-medium text-gray-900">Important Note:</span> This tool is designed for initial screening and monitoring. A comprehensive clinical evaluation is necessary for proper diagnosis and treatment planning.
            </p>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
          <button
            @click="resetAssessment"
            class="flex-1 py-4 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors"
          >
            Retake Assessment
          </button>
          <button
            @click="saveResults"
            class="flex-1 py-4 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors"
          >
            Save Results
          </button>
          <button
            v-if="totalScore >= 10"
            @click="bookAppointment"
            class="flex-1 py-4 bg-emerald-700 text-white hover:bg-emerald-800 transition-colors"
          >
            Book Appointment
          </button>
        </div>

        <!-- Disclaimer -->
        <div class="mt-20 pt-12 border-t border-gray-200">
          <p class="text-sm text-gray-500 leading-relaxed">
            This is a screening tool, not a diagnostic instrument. Please consult a qualified mental health professional for proper evaluation and treatment.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick } from 'vue'

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

// Severity levels
const severityLevels = [
  { title: 'None-Minimal', range: '0-4', min: 0, max: 4, actions: 'No specific action needed' },
  { title: 'Mild Depression', range: '5-9', min: 5, max: 9, actions: 'Watchful waiting; repeat PHQ-9 at follow-up' },
  { title: 'Moderate Depression', range: '10-14', min: 10, max: 14, actions: 'Treatment plan, considering counseling and/or medication' },
  { title: 'Moderately Severe', range: '15-19', min: 15, max: 19, actions: 'Active treatment with medication and/or psychotherapy' },
  { title: 'Severe Depression', range: '20-27', min: 20, max: 27, actions: 'Immediate treatment and expedited referral to specialist' }
]

// Reactive state
const answers = ref<(number | null)[]>(Array(9).fill(null))
const questionRefs = ref({})
const showResults = ref(false)
const totalScore = ref(0)

// Computed properties
const answeredQuestions = computed(() => {
  return answers.value.filter(answer => answer !== null).length
})

const allAnswered = computed(() => {
  return answers.value.every(answer => answer !== null)
})

const severityLevel = computed(() => {
  const score = totalScore.value
  if (score <= 4) return 'None-minimal depression'
  if (score <= 9) return 'Mild depression'
  if (score <= 14) return 'Moderate depression'
  if (score <= 19) return 'Moderately severe depression'
  return 'Severe depression'
})

const severityBorderColor = computed(() => {
  const score = totalScore.value
  if (score <= 4) return 'border-green-500'
  if (score <= 9) return 'border-yellow-500'
  if (score <= 14) return 'border-orange-500'
  if (score <= 19) return 'border-red-500'
  return 'border-red-700'
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

const isInRange = (min: number, max: number) => {
  return totalScore.value >= min && totalScore.value <= max
}

// Methods
const setQuestionRef = (index: number, el: any) => {
  if (el) {
    questionRefs.value[index] = el
  }
}

const onAnswerChange = async (index: number) => {
  await nextTick()
  
  // Scroll to next question or submit button
  const nextIndex = index + 1
  if (nextIndex < questions.length && questionRefs.value[nextIndex]) {
    questionRefs.value[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'center' })
  } else if (allAnswered.value) {
    // Scroll to submit button
    setTimeout(() => {
      const submitButton = document.querySelector('button[type="submit"]')
      if (submitButton) {
        submitButton.scrollIntoView({ behavior: 'smooth', block: 'center' })
      }
    }, 100)
  }
}

const calculateScore = () => {
  totalScore.value = answers.value.reduce((sum, answer) => sum + (answer || 0), 0)
  showResults.value = true
  
  // Scroll to results
  setTimeout(() => {
    const resultsElement = document.querySelector('.mt-32.pt-32')
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
/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Custom radio button animation */
input[type="radio"]:checked + div .scale-\[0\.4\] {
  animation: radioCheck 0.2s ease-out;
}

@keyframes radioCheck {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(0.5);
  }
  100% {
    transform: scale(0.4);
  }
}
</style>