<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">PC-PTSD-5</h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">Primary Care PTSD Screen for DSM-5. A brief screening tool to identify persons with probable PTSD.</p>
        <div class="text-sm text-gray-400">5 questions · 1-2 minutes</div>
      </div>

      <!-- Context Information -->
      <div class="mb-16 pb-16 border-b border-gray-200">
        <h3 class="text-sm uppercase tracking-wider text-gray-500 mb-6">About Traumatic Events</h3>
        <p class="text-base text-gray-600 mb-6 leading-relaxed">
          Sometimes things happen to people that are unusually or especially frightening, horrible, or traumatic. Examples include:
        </p>
        <ul class="space-y-2 text-gray-600 mb-8">
          <li class="flex items-start">
            <span class="text-emerald-600 mr-3">•</span>
            <span>A serious accident or fire</span>
          </li>
          <li class="flex items-start">
            <span class="text-emerald-600 mr-3">•</span>
            <span>A physical or sexual assault or abuse</span>
          </li>
          <li class="flex items-start">
            <span class="text-emerald-600 mr-3">•</span>
            <span>An earthquake or flood</span>
          </li>
          <li class="flex items-start">
            <span class="text-emerald-600 mr-3">•</span>
            <span>A war</span>
          </li>
          <li class="flex items-start">
            <span class="text-emerald-600 mr-3">•</span>
            <span>Seeing someone be killed or seriously injured</span>
          </li>
          <li class="flex items-start">
            <span class="text-emerald-600 mr-3">•</span>
            <span>Having a loved one die through homicide or suicide</span>
          </li>
        </ul>
        <p class="text-base text-gray-900 font-medium">If you have experienced such events, please answer the following questions about the past month:</p>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-16">
        <div class="flex items-center gap-2">
          <div 
            v-for="i in 5" 
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredQuestions >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">Question {{ Math.min(answeredQuestions + 1, 5) }} of 5</div>
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
            <p class="text-gray-500">In the past month, have you:</p>
          </div>
          
          <div class="space-y-3">
            <label
              :class="[
                'group block p-6 border-2 cursor-pointer transition-all duration-200',
                answers[index] === 1
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                v-model="answers[index]"
                :value="1"
                class="sr-only"
                required
                @change="onAnswerChange(index)"
              />
              <div class="flex items-center justify-between">
                <span class="text-lg text-gray-900">Yes</span>
                <div :class="[
                  'w-5 h-5 border-2 rounded-full transition-all',
                  answers[index] === 1
                    ? 'border-emerald-600 bg-emerald-600'
                    : 'border-gray-300 group-hover:border-gray-400'
                ]">
                  <div v-if="answers[index] === 1" class="w-full h-full rounded-full bg-white scale-[0.4]" />
                </div>
              </div>
            </label>

            <label
              :class="[
                'group block p-6 border-2 cursor-pointer transition-all duration-200',
                answers[index] === 0
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                v-model="answers[index]"
                :value="0"
                class="sr-only"
                required
                @change="onAnswerChange(index)"
              />
              <div class="flex items-center justify-between">
                <span class="text-lg text-gray-900">No</span>
                <div :class="[
                  'w-5 h-5 border-2 rounded-full transition-all',
                  answers[index] === 0
                    ? 'border-emerald-600 bg-emerald-600'
                    : 'border-gray-300 group-hover:border-gray-400'
                ]">
                  <div v-if="answers[index] === 0" class="w-full h-full rounded-full bg-white scale-[0.4]" />
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
            <span class="text-3xl text-gray-400">/ 5</span>
          </div>
          <div class="text-lg text-gray-500 mb-8">{{ positiveCount === 1 ? '1 yes answer' : `${positiveCount} yes answers` }}</div>
          
          <div class="max-w-2xl">
            <h3 class="text-2xl font-light text-gray-900 mb-4">{{ interpretationTitle }}</h3>
            <p class="text-lg text-gray-600 leading-relaxed">{{ interpretationText }}</p>
          </div>
        </div>

        <!-- Screening Criteria -->
        <div class="mb-20 py-12 border-y border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">Screening Interpretation</h4>
          <div class="space-y-6">
            <div 
              :class="[
                'p-6 border-2 transition-all',
                positiveCount >= 3 ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200'
              ]"
            >
              <div class="flex items-start justify-between mb-3">
                <div>
                  <h5 class="text-lg font-medium text-gray-900 mb-1">Positive Screen</h5>
                  <p class="text-sm text-gray-600">Probable PTSD — Further evaluation recommended</p>
                </div>
                <div class="text-right">
                  <div class="text-2xl font-light text-gray-900">3-5</div>
                  <div class="text-sm text-gray-500">yes answers</div>
                </div>
              </div>
            </div>

            <div 
              :class="[
                'p-6 border-2 transition-all',
                positiveCount < 3 ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200'
              ]"
            >
              <div class="flex items-start justify-between mb-3">
                <div>
                  <h5 class="text-lg font-medium text-gray-900 mb-1">Negative Screen</h5>
                  <p class="text-sm text-gray-600">Low likelihood of PTSD at this time</p>
                </div>
                <div class="text-right">
                  <div class="text-2xl font-light text-gray-900">0-2</div>
                  <div class="text-sm text-gray-500">yes answers</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Clinical Information -->
        <div class="mb-20 py-12 border-b border-gray-200">
          <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-8">About This Screening</h4>
          <div class="max-w-2xl space-y-6 text-gray-600">
            <p>
              <span class="font-medium text-gray-900">Scoring:</span> Current research suggests that the results of the PC-PTSD-5 should be considered "positive" if a patient answers "yes" to any three items.
            </p>
            <p>
              <span class="font-medium text-gray-900">Next Steps:</span> A positive screen warrants a comprehensive clinical assessment with a structured interview for PTSD, preferably performed by a mental health professional who has experience in diagnosing PTSD.
            </p>
            <p>
              <span class="font-medium text-gray-900">Important:</span> This is a screening tool, not a diagnostic instrument. Professional evaluation is necessary for accurate diagnosis and treatment planning.
            </p>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
          <button
            @click="resetAssessment"
            class="flex-1 py-4 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors"
          >
            Retake Screening
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
            This screening tool is not a diagnostic instrument. If you screen positive or have concerns about trauma experiences or symptoms, 
            please consult with a qualified mental health professional for a comprehensive evaluation and appropriate treatment options.
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
  "Had nightmares about the event(s) or thought about the event(s) when you did not want to?",
  "Tried hard not to think about the event(s) or went out of your way to avoid situations that reminded you of the event(s)?",
  "Been constantly on guard, watchful, or easily startled?",
  "Felt numb or detached from people, activities, or your surroundings?",
  "Felt guilty or unable to stop blaming yourself or others for the event(s) or any problems the event(s) may have caused?"
]

// Reactive state
const answers = ref({})
const questionRefs = ref({})
const showResults = ref(false)
const positiveCount = ref(0)

// Computed properties
const answeredQuestions = computed(() => {
  return Object.keys(answers.value).filter(key => answers.value[key] !== null && answers.value[key] !== undefined).length
})

const allAnswered = computed(() => {
  return answeredQuestions.value === questions.length
})

const isPositiveScreen = computed(() => {
  return positiveCount.value >= 3
})

const interpretationTitle = computed(() => {
  return isPositiveScreen.value ? 'Positive screen' : 'Negative screen'
})

const interpretationText = computed(() => {
  if (isPositiveScreen.value) {
    return `You answered "yes" to ${positiveCount.value} ${positiveCount.value === 1 ? 'item' : 'items'}. Current research suggests that the results of the PC-PTSD-5 should be considered "positive" if a patient answers "yes" to any three items. This indicates probable PTSD, and further assessment with a structured interview for PTSD is recommended, preferably performed by a mental health professional who has experience in diagnosing PTSD.`
  }
  return `You answered "yes" to ${positiveCount.value} ${positiveCount.value === 1 ? 'item' : 'items'}. This is a negative screen, suggesting low likelihood of PTSD at this time. However, if you have any concerns about your trauma experiences or symptoms, please discuss them with a healthcare provider.`
})

// Methods
const setQuestionRef = (index, el) => {
  if (el) {
    questionRefs.value[index] = el
  }
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
  positiveCount.value = Object.values(answers.value).reduce((sum, val) => sum + (val || 0), 0)
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
  positiveCount.value = 0
  
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saving results:', {
    assessment: 'PC-PTSD-5',
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