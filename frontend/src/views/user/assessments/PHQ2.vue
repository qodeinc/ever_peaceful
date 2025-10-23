<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">PHQ-2</h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">Patient Health Questionnaire-2. A brief screening tool for depression using a first-step approach.</p>
        <div class="text-sm text-gray-400">2 questions · 1 minute</div>
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
              Little interest or pleasure in doing things
            </h2>
            <p class="text-gray-500">Over the last 2 weeks, how often have you been bothered by this?</p>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in options"
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
              Feeling down, depressed, or hopeless
            </h2>
            <p class="text-gray-500">Over the last 2 weeks, how often have you been bothered by this?</p>
          </div>
          
          <div class="space-y-3">
            <label
              v-for="option in options"
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
          <div class="text-sm text-gray-400 mb-4">Your Score</div>
          <div class="flex items-baseline gap-4 mb-8">
            <span class="text-8xl font-light text-gray-900">{{ totalScore }}</span>
            <span class="text-3xl text-gray-400">/ 6</span>
          </div>
          
          <div class="max-w-2xl">
            <h3 class="text-2xl font-light text-gray-900 mb-4">{{ interpretationTitle }}</h3>
            <p class="text-lg text-gray-600 leading-relaxed">{{ interpretationText }}</p>
          </div>
        </div>

        <!-- Screening Threshold -->
        <div class="mb-20 py-12 border-y border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">Screening Interpretation</h4>
          <div class="space-y-4">
            <div 
              :class="[
                'p-6 border-2 transition-all',
                totalScore >= 3 ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200'
              ]"
            >
              <div class="flex items-start justify-between mb-3">
                <div>
                  <h5 class="text-lg font-medium text-gray-900 mb-1">Positive Screen</h5>
                  <p class="text-sm text-gray-600">Further evaluation recommended with PHQ-9</p>
                </div>
                <div class="text-right">
                  <div class="text-2xl font-light text-gray-900">3-6</div>
                  <div class="text-sm text-gray-500">points</div>
                </div>
              </div>
            </div>

            <div 
              :class="[
                'p-6 border-2 transition-all',
                totalScore < 3 ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200'
              ]"
            >
              <div class="flex items-start justify-between mb-3">
                <div>
                  <h5 class="text-lg font-medium text-gray-900 mb-1">Negative Screen</h5>
                  <p class="text-sm text-gray-600">Low likelihood of depression at this time</p>
                </div>
                <div class="text-right">
                  <div class="text-2xl font-light text-gray-900">0-2</div>
                  <div class="text-sm text-gray-500">points</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Clinical Information -->
        <div class="mb-20 py-12 border-b border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">About PHQ-2 Scoring</h4>
          <div class="max-w-2xl space-y-6 text-gray-600">
            <p>
              <span class="font-medium text-gray-900">Cut-off Score:</span> The optimal cut-point is a score of 3. If the score is 3 or greater, major depressive disorder is likely.
            </p>
            <p>
              <span class="font-medium text-gray-900">Next Steps:</span> Patients who screen positive should be further evaluated with the PHQ-9, other diagnostic instruments, or direct interview to determine whether they meet criteria for a depressive disorder.
            </p>
            <p>
              <span class="font-medium text-gray-900">Purpose:</span> The PHQ-2 serves as a "first-step" screening approach. A positive screen warrants comprehensive clinical assessment.
            </p>
          </div>
        </div>

        <!-- Operating Characteristics -->
        <div class="mb-20">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">Operating Characteristics</h4>
          
          <!-- Major Depressive Disorder -->
          <div class="mb-8">
            <h5 class="text-base font-medium text-gray-900 mb-4">Major Depressive Disorder (7% prevalence)</h5>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="border-b border-gray-200">
                    <th class="text-left py-3 text-sm font-medium text-gray-900">Score</th>
                    <th class="text-right py-3 text-sm font-medium text-gray-900">Sensitivity</th>
                    <th class="text-right py-3 text-sm font-medium text-gray-900">Specificity</th>
                    <th class="text-right py-3 text-sm font-medium text-gray-900">PPV</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <tr v-for="row in majorDepressiveData" :key="row.score" class="hover:bg-gray-50">
                    <td class="py-3 text-sm text-gray-900">{{ row.score }}</td>
                    <td class="py-3 text-sm text-gray-600 text-right">{{ row.sensitivity }}</td>
                    <td class="py-3 text-sm text-gray-600 text-right">{{ row.specificity }}</td>
                    <td class="py-3 text-sm text-gray-600 text-right">{{ row.ppv }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Any Depressive Disorder -->
          <div>
            <h5 class="text-base font-medium text-gray-900 mb-4">Any Depressive Disorder (18% prevalence)</h5>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="border-b border-gray-200">
                    <th class="text-left py-3 text-sm font-medium text-gray-900">Score</th>
                    <th class="text-right py-3 text-sm font-medium text-gray-900">Sensitivity</th>
                    <th class="text-right py-3 text-sm font-medium text-gray-900">Specificity</th>
                    <th class="text-right py-3 text-sm font-medium text-gray-900">PPV</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <tr v-for="row in anyDepressiveData" :key="'any-' + row.score" class="hover:bg-gray-50">
                    <td class="py-3 text-sm text-gray-900">{{ row.score }}</td>
                    <td class="py-3 text-sm text-gray-600 text-right">{{ row.sensitivity }}</td>
                    <td class="py-3 text-sm text-gray-600 text-right">{{ row.specificity }}</td>
                    <td class="py-3 text-sm text-gray-600 text-right">{{ row.ppv }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <p class="text-sm text-gray-500 mt-6 italic">
            Note: PPV varies with the prevalence of depression and will be higher in settings with higher prevalence.
          </p>
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
            This screening tool is not a diagnostic instrument. If you screen positive or have concerns about depression, 
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
const answeredQuestions = computed(() => {
  let count = 0
  if (answers.value.question1 !== null) count++
  if (answers.value.question2 !== null) count++
  return count
})

const allAnswered = computed(() => {
  return answers.value.question1 !== null && answers.value.question2 !== null
})

const isPositiveScreen = computed(() => {
  return totalScore.value >= 3
})

const interpretationTitle = computed(() => {
  return isPositiveScreen.value ? 'Positive screen' : 'Negative screen'
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
  
  // Scroll to next question or submit button
  if (qNum === 1 && q2.value) {
    q2.value.scrollIntoView({ behavior: 'smooth', block: 'center' })
  } else if (qNum === 2 && allAnswered.value) {
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
  totalScore.value = (answers.value.question1 || 0) + (answers.value.question2 || 0)
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

/* Table hover effect */
table tbody tr {
  transition: background-color 0.15s ease;
}
</style>