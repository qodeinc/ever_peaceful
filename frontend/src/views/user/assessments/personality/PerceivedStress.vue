<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">
          Perceived Stress Scale (PSS-10)
        </h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">
          A 10-item questionnaire designed to measure the perception of stress over the last month.
        </p>
        <div class="text-sm text-gray-400">10 questions · 3–5 minutes</div>
      </div>

      <!-- Instructions -->
      <div class="mb-16 pb-16 border-b border-gray-200">
        <h3 class="text-sm uppercase tracking-wider text-gray-500 mb-6">
          Instructions
        </h3>
        <p class="text-base text-gray-600 leading-relaxed mb-6">
          The questions in this scale ask you about your feelings and thoughts during the last month. 
          Indicate how often you felt or thought a certain way.
        </p>
        <div class="flex flex-wrap gap-4 text-gray-700 text-sm">
          <span><span class="text-emerald-600 font-semibold">0</span> = Never</span>
          <span><span class="text-emerald-600 font-semibold">1</span> = Almost Never</span>
          <span><span class="text-emerald-600 font-semibold">2</span> = Sometimes</span>
          <span><span class="text-emerald-600 font-semibold">3</span> = Fairly Often</span>
          <span><span class="text-emerald-600 font-semibold">4</span> = Very Often</span>
        </div>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-16">
        <div class="flex items-center gap-2">
          <div
            v-for="i in 10"
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredQuestions >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">
          Question {{ Math.min(answeredQuestions + 1, 10) }} of 10
        </div>
      </div>

      <!-- Questions -->
      <form @submit.prevent="calculateResults" class="space-y-24">
        <div
          v-for="(question, index) in questions"
          :key="index"
          :ref="el => setQuestionRef(index, el)"
          class="scroll-mt-32"
        >
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">
              Question {{ index + 1 }}
            </div>
            <h2 class="text-3xl font-light text-gray-900 mb-2">
              {{ question.text }}
            </h2>
            <p class="text-gray-500">How often have you felt this way in the last month?</p>
          </div>

          <!-- Likert Scale -->
          <div class="grid grid-cols-5 gap-3">
            <label
              v-for="(label, value) in scaleLabels"
              :key="value"
              :class="[
                'group block p-6 border-2 cursor-pointer text-center transition-all duration-200 rounded-lg',
                answers[index] === value
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                class="sr-only"
                :name="`q${index}`"
                :value="value"
                v-model="answers[index]"
                required
                @change="onAnswerChange(index)"
              />
              <div class="text-lg text-gray-900 font-medium">{{ value }}</div>
              <div class="text-sm text-gray-500 mt-1">{{ label }}</div>
            </label>
          </div>
        </div>

        <!-- Submit -->
        <div v-if="allAnswered" class="pt-12 border-t border-gray-200">
          <button
            type="submit"
            class="w-full bg-emerald-600 text-white py-5 text-lg hover:bg-emerald-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            View Results
          </button>
        </div>
      </form>

      <!-- Results -->
      <div v-if="showResults" class="mt-32 pt-32 border-t border-gray-200">
        <div class="mb-20">
          <div class="text-sm text-gray-400 mb-4">Your Stress Score</div>
          <div class="p-8 border-2 border-gray-200 rounded-xl text-center">
            <div class="text-6xl font-light text-gray-900 mb-4">
              {{ totalScore }}
            </div>
            <div class="text-lg text-gray-500">
              {{ interpretation }}
            </div>
          </div>
        </div>

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

        <div class="mt-20 pt-12 border-t border-gray-200 text-sm text-gray-500 leading-relaxed">
          <p>
            The PSS-10 assesses perceived stress. Higher scores indicate greater perceived stress during
            the past month. It helps identify how unpredictable, uncontrollable, and overloaded your life feels.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

const questions = [
  { text: 'Been upset because of something that happened unexpectedly?' },
  { text: 'Felt that you were unable to control the important things in your life?' },
  { text: 'Felt nervous and "stressed"?' },
  { text: 'Felt confident about your ability to handle your personal problems?' },
  { text: 'Felt that things were going your way?' },
  { text: 'Found that you could not cope with all the things you had to do?' },
  { text: 'Been able to control irritations in your life?' },
  { text: 'Felt that you were on top of things?' },
  { text: 'Been angered because of things that were outside of your control?' },
  { text: 'Felt difficulties were piling up so high that you could not overcome them?' }
]

const scaleLabels = ['Never', 'Almost Never', 'Sometimes', 'Fairly Often', 'Very Often']

const reverseScored = [3, 4, 6, 7] // reversed scoring questions (4,5,7,8 originally 1-based)

const answers = ref({})
const questionRefs = ref({})
const showResults = ref(false)
const totalScore = ref(0)

const answeredQuestions = computed(() => Object.keys(answers.value).length)
const allAnswered = computed(() => answeredQuestions.value === questions.length)

const setQuestionRef = (index, el) => {
  if (el) questionRefs.value[index] = el
}

const onAnswerChange = async (index) => {
  await nextTick()
  const nextIndex = index + 1
  if (questionRefs.value[nextIndex]) {
    questionRefs.value[nextIndex].scrollIntoView({ behavior: 'smooth', block: 'center' })
  }
}

const calculateResults = () => {
  let score = 0
  for (let i = 0; i < questions.length; i++) {
    const val = Number(answers.value[i])
    if (reverseScored.includes(i)) {
      score += 4 - val // reverse scoring
    } else {
      score += val
    }
  }
  totalScore.value = score
  showResults.value = true
  nextTick(() => {
    document.querySelector('.mt-32.pt-32')?.scrollIntoView({ behavior: 'smooth' })
  })
}

const interpretation = computed(() => {
  if (totalScore.value <= 13) return 'Low stress level'
  if (totalScore.value <= 26) return 'Moderate stress level'
  return 'High perceived stress level'
})

const resetAssessment = () => {
  answers.value = {}
  showResults.value = false
  totalScore.value = 0
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saved:', totalScore.value)
  alert('Results saved successfully!')
}
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>
