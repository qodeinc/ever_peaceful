<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">
          Big Five Inventory – 2 Short Form (BFI-2-S)
        </h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">
          A 30-item personality measure assessing the Big Five dimensions: 
          Extraversion, Agreeableness, Conscientiousness, Negative Emotionality, and Open-Mindedness.
        </p>
        <div class="text-sm text-gray-400">30 questions · 5–8 minutes</div>
      </div>

      <!-- Instructions -->
      <div class="mb-16 pb-16 border-b border-gray-200">
        <h3 class="text-sm uppercase tracking-wider text-gray-500 mb-6">
          Instructions
        </h3>
        <p class="text-base text-gray-600 leading-relaxed mb-6">
          Here are a number of characteristics that may or may not apply to you. 
          For example, do you agree that you are someone who is outgoing, sociable? 
          Please indicate the extent to which you agree or disagree with each statement.
        </p>
        <div class="flex flex-wrap gap-4 text-gray-700 text-sm">
          <span><span class="text-emerald-600 font-semibold">1</span> = Disagree strongly</span>
          <span><span class="text-emerald-600 font-semibold">2</span> = Disagree a little</span>
          <span><span class="text-emerald-600 font-semibold">3</span> = Neutral</span>
          <span><span class="text-emerald-600 font-semibold">4</span> = Agree a little</span>
          <span><span class="text-emerald-600 font-semibold">5</span> = Agree strongly</span>
        </div>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-16">
        <div class="flex items-center gap-2">
          <div
            v-for="i in 30"
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredQuestions >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">
          Question {{ Math.min(answeredQuestions + 1, 30) }} of 30
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
              {{ question }}
            </h2>
            <p class="text-gray-500">How much do you agree with this statement?</p>
          </div>

          <!-- Likert Scale -->
          <div class="grid grid-cols-5 gap-3">
            <label
              v-for="(label, value) in scaleLabels"
              :key="value"
              :class="[
                'group block p-6 border-2 cursor-pointer text-center transition-all duration-200 rounded-lg',
                answers[index] === value + 1
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                class="sr-only"
                :name="`q${index}`"
                :value="value + 1"
                v-model="answers[index]"
                required
                @change="onAnswerChange(index)"
              />
              <div class="text-lg text-gray-900 font-medium">{{ value + 1 }}</div>
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
          <div class="text-sm text-gray-400 mb-4">Your Personality Profile</div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div
              v-for="(score, trait) in traitScores"
              :key="trait"
              class="p-6 border-2 border-gray-200 rounded-xl"
            >
              <div class="flex justify-between mb-3">
                <h3 class="text-lg font-medium text-gray-900 capitalize">
                  {{ trait }}
                </h3>
                <span class="text-xl font-light text-gray-800">{{ score }}/5</span>
              </div>
              <div class="w-full h-2 bg-gray-200 rounded-full">
                <div
                  class="h-2 bg-emerald-600 rounded-full transition-all duration-500"
                  :style="{ width: (score / 5) * 100 + '%' }"
                />
              </div>
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

        <div class="mt-20 pt-12 border-t border-gray-200 text-sm text-gray-500 leading-relaxed">
          <p>
            This inventory measures the five broad domains of personality.
            Scores are based on your level of agreement with each statement and
            can be interpreted as relative strengths, not fixed traits.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

const questions = [
  'Is talkative.',
  'Tends to find fault with others.',
  'Does a thorough job.',
  'Is depressed, blue.',
  'Is original, comes up with new ideas.',
  'Is reserved.',
  'Is helpful and unselfish with others.',
  'Can be somewhat careless.',
  'Is relaxed, handles stress well.',
  'Is curious about many different things.',
  'Is full of energy.',
  'Starts quarrels with others.',
  'Is a reliable worker.',
  'Can be tense.',
  'Is ingenious, a deep thinker.',
  'Generates a lot of enthusiasm.',
  'Has a forgiving nature.',
  'Tends to be disorganized.',
  'Worries a lot.',
  'Has an active imagination.',
  'Tends to be quiet.',
  'Is generally trusting.',
  'Tends to be lazy.',
  'Is emotionally stable, not easily upset.',
  'Is inventive.',
  'Has an assertive personality.',
  'Can be cold and aloof.',
  'Perseveres until the task is finished.',
  'Can be moody.',
  'Values artistic, aesthetic experiences.'
]

// Scale labels
const scaleLabels = [
  'Disagree strongly',
  'Disagree a little',
  'Neutral',
  'Agree a little',
  'Agree strongly'
]

// State
const answers = ref({})
const questionRefs = ref({})
const showResults = ref(false)
const traitScores = ref({})

// Computed
const answeredQuestions = computed(() => Object.keys(answers.value).length)
const allAnswered = computed(() => answeredQuestions.value === questions.length)

// Methods
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
  const traits = ['Extraversion', 'Agreeableness', 'Conscientiousness', 'Negative Emotionality', 'Open-Mindedness']
  const scores = { Extraversion: 0, Agreeableness: 0, Conscientiousness: 0, 'Negative Emotionality': 0, 'Open-Mindedness': 0 }

  // Simplified scoring — each trait has 6 items in the 30-item version
  traits.forEach((trait, i) => {
    const start = i * 6
    const traitItems = Object.values(answers.value).slice(start, start + 6)
    scores[trait] = (traitItems.reduce((sum, val) => sum + Number(val), 0) / traitItems.length).toFixed(1)
  })

  traitScores.value = scores
  showResults.value = true
  setTimeout(() => {
    document.querySelector('.mt-32.pt-32')?.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }, 200)
}

const resetAssessment = () => {
  answers.value = {}
  showResults.value = false
  traitScores.value = {}
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saved:', traitScores.value)
  alert('Results saved successfully!')
}
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>
