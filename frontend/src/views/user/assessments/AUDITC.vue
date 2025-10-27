<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">AUDIT-C Assessment</h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">A brief alcohol screening tool that identifies hazardous drinking or active alcohol use disorders. Answer each question based on your typical drinking patterns.</p>
        <div class="text-sm text-gray-400">4 items · 1-2 minutes</div>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-16">
        <div class="flex items-center gap-3">
          <div 
            v-for="i in 4" 
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredQuestions >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">Item {{ Math.min(answeredQuestions + 1, 4) }} of 4</div>
      </div>

      <!-- Assessment Form -->
      <form @submit.prevent="calculateScore" class="space-y-24">
        <!-- Gender Selection -->
        <div ref="genderSection" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Gender</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              Please select your gender
            </h2>
            <p class="text-gray-500">Different scoring thresholds apply: ≥4 for men, ≥3 for women</p>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in genderOptions"
              :key="option.value"
              :class="[
                'group block p-6 border-2 cursor-pointer transition-all duration-200',
                gender === option.value
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                v-model="gender"
                :value="option.value"
                class="sr-only"
                required
                @change="onGenderChange"
              />
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-lg text-gray-900 block mb-1">{{ option.label }}</span>
                  <span class="text-sm text-gray-500">{{ option.threshold }}</span>
                </div>
                <div :class="[
                  'w-5 h-5 border-2 rounded-full transition-all',
                  gender === option.value
                    ? 'border-emerald-600 bg-emerald-600'
                    : 'border-gray-300 group-hover:border-gray-400'
                ]">
                  <div v-if="gender === option.value" class="w-full h-full rounded-full bg-white scale-[0.4]" />
                </div>
              </div>
            </label>
          </div>
        </div>

        <!-- Question 1 -->
        <div v-if="gender" ref="q1" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question 1</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              How often do you have a drink containing alcohol?
            </h2>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in question1Options"
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
        <div v-if="gender" ref="q2" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question 2</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              How many drinks do you have on a typical day when drinking?
            </h2>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in question2Options"
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

        <!-- Question 3 -->
        <div v-if="gender" ref="q3" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question 3</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              How often do you have six or more drinks on one occasion?
            </h2>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in question3Options"
              :key="option.value"
              :class="[
                'group block p-6 border-2 cursor-pointer transition-all duration-200',
                answers.question3 === option.value
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
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
              <div class="flex items-center justify-between">
                <span class="text-lg text-gray-900">{{ option.label }}</span>
                <div :class="[
                  'w-5 h-5 border-2 rounded-full transition-all',
                  answers.question3 === option.value
                    ? 'border-emerald-600 bg-emerald-600'
                    : 'border-gray-300 group-hover:border-gray-400'
                ]">
                  <div v-if="answers.question3 === option.value" class="w-full h-full rounded-full bg-white scale-[0.4]" />
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
            <span class="text-3xl text-gray-400">/ 12</span>
          </div>
          
          <div class="max-w-2xl">
            <h3 class="text-2xl font-light text-gray-900 mb-4">{{ interpretationTitle }}</h3>
            <p class="text-lg text-gray-600 leading-relaxed mb-6">{{ interpretationText }}</p>
            
            <!-- Special note for Q1 only -->
            <div v-if="allPointsFromQuestion1" class="mt-8 p-6 bg-blue-50 border-l-4 border-blue-600">
              <p class="text-sm text-blue-900">
                <strong>Clinical Note:</strong> All points are from Question 1 only. The patient is likely drinking below recommended limits. The medical provider should review the patient's alcohol intake during the past few months.
              </p>
            </div>
          </div>
        </div>

        <!-- Interpretation Details -->
        <div class="mb-20 py-12 border-y border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">About AUDIT-C Scoring</h4>
          <div class="max-w-2xl space-y-6 text-gray-600">
            <p>
              The AUDIT-C is a brief screening tool for identifying hazardous drinking or active alcohol use disorders. Different thresholds apply based on gender.
            </p>
            <p>
              <strong>Scoring Thresholds:</strong> A score of 4 or more for men, or 3 or more for women, is considered positive for hazardous drinking or active alcohol use disorders.
            </p>
            <p>
              The AUDIT-C is derived from the full 10-item AUDIT (Alcohol Use Disorders Identification Test) and focuses on consumption patterns. Positive screens warrant further evaluation and discussion with a healthcare provider.
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

// Gender options
const genderOptions = [
  { label: 'Male', value: 'male', threshold: 'Threshold: ≥4 points' },
  { label: 'Female', value: 'female', threshold: 'Threshold: ≥3 points' }
]

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
const gender = ref(null)
const answers = ref({
  question1: null,
  question2: null,
  question3: null
})
const showResults = ref(false)
const totalScore = ref(0)

// Computed properties
const answeredQuestions = computed(() => {
  let count = 0
  if (gender.value !== null) count++
  if (answers.value.question1 !== null) count++
  if (answers.value.question2 !== null) count++
  if (answers.value.question3 !== null) count++
  return count
})

const allAnswered = computed(() => {
  return gender.value !== null &&
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
    return 'Positive screen for hazardous drinking'
  }
  return 'Negative screen'
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
    q1.value.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }
}

const onAnswerChange = async (qNum) => {
  await nextTick()
  
  // Scroll to next question or submit button
  if (qNum === 1 && q2.value) {
    q2.value.scrollIntoView({ behavior: 'smooth', block: 'center' })
  } else if (qNum === 2 && q3.value) {
    q3.value.scrollIntoView({ behavior: 'smooth', block: 'center' })
  } else if (qNum === 3 && allAnswered.value) {
    setTimeout(() => {
      const submitButton = document.querySelector('button[type="submit"]')
      if (submitButton) {
        submitButton.scrollIntoView({ behavior: 'smooth', block: 'center' })
      }
    }, 100)
  }
}

const calculateScore = () => {
  totalScore.value = (answers.value.question1 || 0) + 
                     (answers.value.question2 || 0) + 
                     (answers.value.question3 || 0)
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
  gender.value = null
  answers.value = {
    question1: null,
    question2: null,
    question3: null
  }
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