<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">CAGE Assessment</h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">A brief screening tool for identifying potential problems with alcohol use. Answer each question based on your lifetime experience with alcohol.</p>
        <div class="text-sm text-gray-400">4 questions · Less than 1 minute</div>
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
        <div class="text-sm text-gray-500 mt-3">Question {{ Math.min(answeredQuestions + 1, 4) }} of 4</div>
      </div>

      <!-- Questions -->
      <form @submit.prevent="calculateScore" class="space-y-24">
        <!-- Question 1: Cut down -->
        <div ref="q1" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question 1 · C</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              Have you ever felt you should <span class="font-normal text-emerald-600">Cut down</span> on your drinking?
            </h2>
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

        <!-- Question 2: Annoyed -->
        <div ref="q2" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question 2 · A</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              Have people <span class="font-normal text-emerald-600">Annoyed</span> you by criticizing your drinking?
            </h2>
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

        <!-- Question 3: Guilty -->
        <div ref="q3" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question 3 · G</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              Have you ever felt <span class="font-normal text-emerald-600">Guilty</span> about your drinking?
            </h2>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in yesNoOptions"
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

        <!-- Question 4: Eye opener -->
        <div ref="q4" class="scroll-mt-32">
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question 4 · E</div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              Have you ever had a drink first thing in the morning to steady your nerves?
            </h2>
            <p class="text-gray-500"><span class="font-normal text-emerald-600">Eye opener</span> to get rid of a hangover</p>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in yesNoOptions"
              :key="option.value"
              :class="[
                'group block p-6 border-2 cursor-pointer transition-all duration-200',
                answers.question4 === option.value
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                v-model="answers.question4"
                :value="option.value"
                class="sr-only"
                required
                @change="onAnswerChange(4)"
              />
              <div class="flex items-center justify-between">
                <span class="text-lg text-gray-900">{{ option.label }}</span>
                <div :class="[
                  'w-5 h-5 border-2 rounded-full transition-all',
                  answers.question4 === option.value
                    ? 'border-emerald-600 bg-emerald-600'
                    : 'border-gray-300 group-hover:border-gray-400'
                ]">
                  <div v-if="answers.question4 === option.value" class="w-full h-full rounded-full bg-white scale-[0.4]" />
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
            <span class="text-3xl text-gray-400">/ 4</span>
          </div>
          
          <div class="max-w-2xl">
            <h3 class="text-2xl font-light text-gray-900 mb-4">{{ interpretationTitle }}</h3>
            <p class="text-lg text-gray-600 leading-relaxed">{{ interpretationText }}</p>
          </div>
        </div>

        <!-- Interpretation Details -->
        <div class="mb-20 py-12 border-y border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">About CAGE Scoring</h4>
          <div class="max-w-2xl space-y-6 text-gray-600">
            <p>
              The CAGE questionnaire is a widely used screening tool for identifying potential alcohol problems. Each "yes" answer scores 1 point.
            </p>
            <div class="space-y-3">
              <p><strong>Score of 0:</strong> Low likelihood of alcohol problems</p>
              <p><strong>Score of 1:</strong> Possible alcohol problem. Further assessment recommended</p>
              <p><strong>Score of 2-4:</strong> High likelihood of alcohol dependence. Comprehensive evaluation needed</p>
            </div>
            <p>
              A score of 2 or more is considered clinically significant and indicates the need for further diagnostic assessment by a healthcare professional.
            </p>
          </div>
        </div>

        <!-- CAGE Acronym Explanation -->
        <div class="mb-20">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">What CAGE Stands For</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl">
            <div class="p-6 bg-gray-50 border border-gray-200">
              <div class="text-2xl font-light text-emerald-600 mb-2">C</div>
              <div class="text-sm font-medium text-gray-900">Cut down</div>
              <div class="text-sm text-gray-600 mt-1">Felt you should reduce drinking</div>
            </div>
            <div class="p-6 bg-gray-50 border border-gray-200">
              <div class="text-2xl font-light text-emerald-600 mb-2">A</div>
              <div class="text-sm font-medium text-gray-900">Annoyed</div>
              <div class="text-sm text-gray-600 mt-1">People criticized your drinking</div>
            </div>
            <div class="p-6 bg-gray-50 border border-gray-200">
              <div class="text-2xl font-light text-emerald-600 mb-2">G</div>
              <div class="text-sm font-medium text-gray-900">Guilty</div>
              <div class="text-sm text-gray-600 mt-1">Felt bad about drinking</div>
            </div>
            <div class="p-6 bg-gray-50 border border-gray-200">
              <div class="text-2xl font-light text-emerald-600 mb-2">E</div>
              <div class="text-sm font-medium text-gray-900">Eye opener</div>
              <div class="text-sm text-gray-600 mt-1">Morning drink to start the day</div>
            </div>
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

// Answer options
const yesNoOptions = [
  { label: 'Yes', value: true },
  { label: 'No', value: false }
]

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
const answeredQuestions = computed(() => {
  let count = 0
  if (answers.value.question1 !== null) count++
  if (answers.value.question2 !== null) count++
  if (answers.value.question3 !== null) count++
  if (answers.value.question4 !== null) count++
  return count
})

const allAnswered = computed(() => {
  return answers.value.question1 !== null &&
         answers.value.question2 !== null &&
         answers.value.question3 !== null &&
         answers.value.question4 !== null
})

const interpretationTitle = computed(() => {
  if (totalScore.value >= 2) {
    return 'High likelihood of alcohol dependence'
  } else if (totalScore.value === 1) {
    return 'Possible alcohol problem'
  }
  return 'Low likelihood of alcohol problems'
})

const interpretationText = computed(() => {
  if (totalScore.value >= 2) {
    return `Your score of ${totalScore.value} is clinically significant and suggests a high likelihood of alcohol dependence. A comprehensive evaluation by a healthcare professional is strongly recommended to assess for alcohol use disorder and discuss appropriate treatment options.`
  } else if (totalScore.value === 1) {
    return 'Your score suggests a possible alcohol problem. Further assessment is recommended to determine if you may have an alcohol use issue. Please consider discussing your alcohol use with a healthcare provider.'
  }
  return 'Your score suggests a low likelihood of alcohol problems at this time. However, if you have any concerns about your alcohol use, please don\'t hesitate to discuss them with a healthcare provider.'
})

// Methods
const onAnswerChange = async (qNum) => {
  await nextTick()
  
  // Scroll to next question or submit button
  if (qNum === 1 && q2.value) {
    q2.value.scrollIntoView({ behavior: 'smooth', block: 'center' })
  } else if (qNum === 2 && q3.value) {
    q3.value.scrollIntoView({ behavior: 'smooth', block: 'center' })
  } else if (qNum === 3 && q4.value) {
    q4.value.scrollIntoView({ behavior: 'smooth', block: 'center' })
  } else if (qNum === 4 && allAnswered.value) {
    setTimeout(() => {
      const submitButton = document.querySelector('button[type="submit"]')
      if (submitButton) {
        submitButton.scrollIntoView({ behavior: 'smooth', block: 'center' })
      }
    }, 100)
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
    const resultsElement = document.querySelector('.mt-32.pt-32')
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