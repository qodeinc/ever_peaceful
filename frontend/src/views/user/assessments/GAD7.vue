<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">GAD-7 Assessment</h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">A screening tool for generalized anxiety disorder. Answer each question based on how you've felt over the last two weeks.</p>
        <div class="text-sm text-gray-400">7 questions · 2-3 minutes</div>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-16">
        <div class="flex items-center gap-2">
          <div 
            v-for="i in 7" 
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredQuestions >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">Question {{ Math.min(answeredQuestions + 1, 7) }} of 7</div>
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
              {{ question }}
            </h2>
            <p class="text-gray-500">Over the last 2 weeks, how often have you been bothered by this?</p>
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
            <span class="text-3xl text-gray-400">/ 21</span>
          </div>
          
          <div class="max-w-2xl">
            <h3 class="text-2xl font-light text-gray-900 mb-4">{{ interpretationTitle }}</h3>
            <p class="text-lg text-gray-600 leading-relaxed">{{ interpretationText }}</p>
          </div>
        </div>

        <!-- Severity Scale -->
        <div class="mb-20 py-12 border-y border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">Severity Scale</h4>
          <div class="space-y-4">
            <div 
              v-for="level in severityLevels" 
              :key="level.range"
              :class="[
                'p-6 border-2 transition-all',
                isInRange(level.min, level.max) ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200'
              ]"
            >
              <div class="flex items-center justify-between">
                <div>
                  <h5 class="text-lg font-medium text-gray-900 mb-1">{{ level.title }}</h5>
                  <p class="text-sm text-gray-600">{{ level.description }}</p>
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
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">Clinical Interpretation</h4>
          <div class="max-w-2xl space-y-6 text-gray-600">
            <p>
              Using a cut-off score of 8, the GAD-7 has a sensitivity of 92% and specificity of 76% for identifying probable cases of generalized anxiety disorder.
            </p>
            <p>
              Although designed as a screening tool for generalized anxiety, the GAD-7 also performs reasonably well as a screening tool for other common anxiety disorders including Panic Disorder, Social Anxiety Disorder, and Post-Traumatic Stress Disorder.
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
                <tr v-for="row in performanceData" :key="row.test" class="hover:bg-gray-50">
                  <td class="py-4 text-sm text-gray-900">{{ row.test }}</td>
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

// Severity levels
const severityLevels = [
  { title: 'Minimal Anxiety', range: '0-4', min: 0, max: 4, description: 'Few to no anxiety symptoms' },
  { title: 'Mild Anxiety', range: '5-9', min: 5, max: 9, description: 'Some anxiety symptoms present' },
  { title: 'Moderate Anxiety', range: '10-14', min: 10, max: 14, description: 'Significant anxiety symptoms' },
  { title: 'Severe Anxiety', range: '15-21', min: 15, max: 21, description: 'Severe anxiety symptoms requiring attention' }
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
const answeredQuestions = computed(() => {
  return Object.keys(answers.value).filter(key => answers.value[key] !== null && answers.value[key] !== undefined).length
})

const allAnswered = computed(() => {
  return answeredQuestions.value === questions.length
})

const interpretationTitle = computed(() => {
  if (totalScore.value >= 15) return 'Severe anxiety'
  if (totalScore.value >= 10) return 'Moderate anxiety'
  if (totalScore.value >= 5) return 'Mild anxiety'
  return 'Minimal anxiety'
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

const isInRange = (min, max) => {
  return totalScore.value >= min && totalScore.value <= max
}

const onAnswerChange = async (index) => {
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
  totalScore.value = Object.values(answers.value).reduce((sum, val) => sum + (val || 0), 0)
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