<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">GAD-2 Assessment</h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">A brief screening tool for generalized anxiety disorder. Answer each question based on how you've felt over the last two weeks.</p>
        <div class="text-sm text-gray-400">2 questions · 1-2 minutes</div>
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
              Feeling nervous, anxious or on edge
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
              Not being able to stop or control worrying
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

        <!-- Interpretation Details -->
        <div class="mb-20 py-12 border-y border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">About This Assessment</h4>
          <div class="max-w-2xl space-y-6 text-gray-600">
            <p>
              Using a cut-off score of 3, the GAD-2 has a sensitivity of 86% and specificity of 83% for diagnosing generalized anxiety disorder.
            </p>
            <p>
              The GAD-2 also performs reasonably well as a screening tool for other common anxiety disorders including Panic Disorder, Social Anxiety Disorder, and Post-Traumatic Stress Disorder.
            </p>
          </div>
        </div>

        <!-- Performance Table -->
        <div class="mb-20">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">Performance as Screening Tool</h4>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="text-left py-4 text-sm font-medium text-gray-900">Disorder</th>
                  <th class="text-right py-4 text-sm font-medium text-gray-900">Sensitivity</th>
                  <th class="text-right py-4 text-sm font-medium text-gray-900">Specificity</th>
                  <th class="text-right py-4 text-sm font-medium text-gray-900">LR+</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="row in performanceData" :key="row.disorder" class="hover:bg-gray-50">
                  <td class="py-4 text-sm text-gray-900">{{ row.disorder }}</td>
                  <td class="py-4 text-sm text-gray-600 text-right">{{ row.sensitivity }}</td>
                  <td class="py-4 text-sm text-gray-600 text-right">{{ row.specificity }}</td>
                  <td class="py-4 text-sm text-gray-600 text-right">{{ row.likelihoodRatio }}</td>
                </tr>
              </tbody>
            </table>
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
            This screening tool is not a diagnostic instrument. If you score 3 or higher, or if you have concerns about anxiety, 
            please consult with a qualified mental health professional for a comprehensive evaluation.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
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

// Performance data
const performanceData = [
  { disorder: 'Generalized Anxiety Disorder', sensitivity: '86%', specificity: '83%', likelihoodRatio: '5.0' },
  { disorder: 'Panic Disorder', sensitivity: '76%', specificity: '81%', likelihoodRatio: '4.1' },
  { disorder: 'Social Anxiety Disorder', sensitivity: '70%', specificity: '81%', likelihoodRatio: '3.6' },
  { disorder: 'Post-Traumatic Stress Disorder', sensitivity: '59%', specificity: '81%', likelihoodRatio: '3.1' },
  { disorder: 'Any Anxiety Disorder', sensitivity: '65%', specificity: '88%', likelihoodRatio: '5.2' }
]

// Reactive state
const answers = ref({
  question1: null as number | null,
  question2: null as number | null
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

const interpretationTitle = computed(() => {
  if (totalScore.value >= 3) {
    return 'Further evaluation recommended'
  }
  return 'Low anxiety symptoms'
})

const interpretationText = computed(() => {
  if (totalScore.value >= 3) {
    return 'Your score suggests possible anxiety concerns. A score of 3 or higher indicates that further diagnostic evaluation for generalized anxiety disorder is warranted. We recommend speaking with a qualified mental health professional for a comprehensive assessment.'
  }
  return 'Your score suggests minimal anxiety symptoms at this time. However, if you have any concerns about your mental health, please don\'t hesitate to reach out to a mental health professional.'
})

// Methods
const onAnswerChange = async (qNum: number) => {
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
    assessment: 'GAD-2',
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

/* Table hover effect */
table tbody tr {
  transition: background-color 0.15s ease;
}
</style>