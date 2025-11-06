<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">TICS Assessment</h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">Two-Item Conjoint Screen for identifying potential alcohol or drug problems based on experiences in the past 12 months.</p>
        <div class="text-sm text-gray-400">2 questions · Less than 1 minute</div>
      </div>

      <!-- Important Note -->
      <div class="mb-16 p-6 bg-blue-50 border-l-4 border-blue-600">
        <p class="text-sm text-blue-900">
          <strong>Time Frame:</strong> Consider your experiences with alcohol and any drugs in the past 12 months, including prescription medications used other than as directed.
        </p>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-16">
        <div class="flex items-center gap-3">
          <div 
            v-for="i in 2" 
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredQuestions >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">Question {{ Math.min(answeredQuestions + 1, 2) }} of 2</div>
      </div>

      <!-- Questions -->
      <form @submit.prevent="calculateScore" class="space-y-24">
        <!-- Question 1 -->
        <div ref="q1" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question 1</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              Have you ever drunk or used drugs more than you meant to?
            </h2>
            <p class="text-gray-500">In the past 12 months</p>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in yesNoOptions"
              :key="option.value"
              :class="[
                'group block p-6 border-2 cursor-pointer transition-all duration-200',
                answers.question1 === option.value
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
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
              <div class="flex items-center justify-between">
                <span class="text-lg text-gray-900">{{ option.label }}</span>
                <div :class="[
                  'w-5 h-5 border-2 rounded-full transition-all',
                  answers.question1 === option.value
                    ? 'border-emerald-600 bg-emerald-600'
                    : 'border-gray-300 group-hover:border-gray-400'
                ]">
                  <div v-if="answers.question1 === option.value" class="w-full h-full rounded-full bg-white scale-[0.4]" />
                </div>
              </div>
            </label>
          </div>
        </div>

        <!-- Question 2 -->
        <div ref="q2" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question 2</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              Have you felt you wanted or needed to cut down on your drinking or drug use?
            </h2>
            <p class="text-gray-500">In the past 12 months</p>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in yesNoOptions"
              :key="option.value"
              :class="[
                'group block p-6 border-2 cursor-pointer transition-all duration-200',
                answers.question2 === option.value
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
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
              <div class="flex items-center justify-between">
                <span class="text-lg text-gray-900">{{ option.label }}</span>
                <div :class="[
                  'w-5 h-5 border-2 rounded-full transition-all',
                  answers.question2 === option.value
                    ? 'border-emerald-600 bg-emerald-600'
                    : 'border-gray-300 group-hover:border-gray-400'
                ]">
                  <div v-if="answers.question2 === option.value" class="w-full h-full rounded-full bg-white scale-[0.4]" />
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
          <div class="text-sm text-gray-400 mb-4">Your Result</div>
          <div class="flex items-baseline gap-4 mb-8">
            <span class="text-8xl font-light text-gray-900">{{ positiveCount }}</span>
            <span class="text-3xl text-gray-400">/ 2</span>
          </div>
          <div class="text-lg text-gray-500 mb-8">positive response{{ positiveCount !== 1 ? 's' : '' }}</div>
          
          <div class="max-w-2xl">
            <h3 class="text-2xl font-light text-gray-900 mb-4">{{ interpretationTitle }}</h3>
            <p class="text-lg text-gray-600 leading-relaxed">{{ interpretationText }}</p>
          </div>
        </div>

        <!-- Your Responses -->
        <div class="mb-20">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">Your Responses</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl">
            <div class="p-6 bg-gray-50 border border-gray-200">
              <div class="flex items-center justify-between mb-2">
                <div class="text-2xl font-light text-emerald-600">1</div>
                <span :class="[
                  'text-sm font-medium',
                  answers.question1 === 'yes' ? 'text-emerald-600' : 'text-gray-500'
                ]">
                  {{ answers.question1 === 'yes' ? 'Yes' : 'No' }}
                </span>
              </div>
              <div class="text-sm text-gray-600">Used more than intended</div>
            </div>
            <div class="p-6 bg-gray-50 border border-gray-200">
              <div class="flex items-center justify-between mb-2">
                <div class="text-2xl font-light text-emerald-600">2</div>
                <span :class="[
                  'text-sm font-medium',
                  answers.question2 === 'yes' ? 'text-emerald-600' : 'text-gray-500'
                ]">
                  {{ answers.question2 === 'yes' ? 'Yes' : 'No' }}
                </span>
              </div>
              <div class="text-sm text-gray-600">Wanted to cut down</div>
            </div>
          </div>
        </div>

        <!-- Interpretation Details -->
        <div class="mb-20 py-12 border-y border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">About TICS Screening</h4>
          <div class="max-w-2xl space-y-6 text-gray-600">
            <p>
              The Two-Item Conjoint Screen (TICS) is a brief screening tool designed to identify potential alcohol or drug problems based on experiences in the past 12 months.
            </p>
            <p>
              <strong>Performance:</strong> A positive response to at least one question detects a current substance use disorder with a sensitivity of 79.3% and specificity of 77.9%.
            </p>
            <p>
              <strong>Interpretation:</strong> One or more "yes" responses is considered a positive screen, suggesting the need for more detailed diagnostic assessments to evaluate the presence and severity of an alcohol or drug problem.
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
        </div>

        <!-- Disclaimer -->
        <div class="mt-20 pt-12 border-t border-gray-200">
          <p class="text-sm text-gray-500 leading-relaxed">
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
const yesNoOptions = [
  { label: 'Yes', value: 'yes' },
  { label: 'No', value: 'no' }
]

// Reactive state
const answers = ref({
  question1: null,
  question2: null
})
const showResults = ref(false)
const positiveCount = ref(0)

// Computed properties
const answeredQuestions = computed(() => {
  let count = 0
  if (answers.value.question1 !== null) count++
  if (answers.value.question2 !== null) count++
  return count
})

const allAnswered = computed(() => {
  return answers.value.question1 !== null &&
         answers.value.question2 !== null
})

const isPositiveScreen = computed(() => {
  return positiveCount.value >= 1
})

const interpretationTitle = computed(() => {
  if (isPositiveScreen.value) {
    return 'Positive screen for substance use disorder'
  }
  return 'Negative screen'
})

const interpretationText = computed(() => {
  if (isPositiveScreen.value) {
    return 'A positive response to at least one question suggests a current alcohol or drug problem. You should have more detailed diagnostic assessments for an alcohol or drug problem. A positive screen detects a current substance use disorder with a sensitivity of 79.3% and specificity of 77.9%.'
  }
  return 'Answering "no" to both questions is considered a negative screening result. This suggests you are unlikely to have a current alcohol or drug problem. However, if you have any concerns about your substance use, please discuss them with a healthcare provider.'
})

// Methods
const onAnswerChange = async (qNum) => {
  await nextTick()
  
  // Scroll to next question or submit button
  if (qNum === 1 && q2.value) {
    q2.value.scrollIntoView({ behavior: 'smooth', block: 'center' })
  } else if (qNum === 2 && allAnswered.value) {
    setTimeout(() => {
      const submitButton = document.querySelector('button[type="submit"]')
      if (submitButton) {
        submitButton.scrollIntoView({ behavior: 'smooth', block: 'center' })
      }
    }, 100)
  }
}

const calculateScore = () => {
  positiveCount.value = (answers.value.question1 === 'yes' ? 1 : 0) + 
                       (answers.value.question2 === 'yes' ? 1 : 0)
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
  alert('Results saved successfully!')
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