<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">
          Rosenberg Self-Esteem Scale (RSE)
        </h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">
          A 10-item self-report measure designed to assess overall self-esteem and self-worth.
        </p>
        <div class="text-sm text-gray-400">10 questions · 3–5 minutes</div>
      </div>

      <!-- Author & Description -->
      <div class="mb-16 pb-16 border-b border-gray-200">
        <h3 class="text-sm uppercase tracking-wider text-gray-500 mb-6">
          Author & Description
        </h3>
        <p class="text-base text-gray-600 leading-relaxed mb-4">
          Developed by <span class="font-semibold text-gray-800">Morris Rosenberg</span>, this scale measures
          global self-esteem. Originally created for high school students, it has since been used widely among
          adults and different cultural groups.
        </p>
        <p class="text-base text-gray-600 leading-relaxed mb-4">
          Each statement is rated based on how strongly you agree or disagree. Some items are reverse-scored.
        </p>

        <div class="flex flex-wrap gap-4 text-gray-700 text-sm mt-6">
          <span><span class="text-emerald-600 font-semibold">1</span> = Strongly agree</span>
          <span><span class="text-emerald-600 font-semibold">2</span> = Agree</span>
          <span><span class="text-emerald-600 font-semibold">3</span> = Disagree</span>
          <span><span class="text-emerald-600 font-semibold">4</span> = Strongly disagree</span>
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
            <p class="text-gray-500">Select the option that best represents your view.</p>
          </div>

          <!-- Response Options -->
          <div class="grid grid-cols-4 gap-3">
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

      <!-- Results -->
      <div v-if="showResults" class="mt-32 pt-32 border-t border-gray-200">
        <div class="mb-20">
          <div class="text-sm text-gray-400 mb-4">Your Self-Esteem Score</div>
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
            The Rosenberg Self-Esteem Scale is one of the most widely used instruments for measuring self-esteem.
            Higher scores indicate greater self-acceptance and confidence, while lower scores may suggest self-doubt
            or negative self-perception.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

const questions = [
  { text: 'On the whole, I am satisfied with myself.' },
  { text: 'At times I think I am no good at all.' },
  { text: 'I feel that I have a number of good qualities.' },
  { text: 'I am able to do things as well as most other people.' },
  { text: 'I feel I do not have much to be proud of.' },
  { text: 'I certainly feel useless at times.' },
  { text: 'I feel that I\'m a person of worth.' },
  { text: 'I wish I could have more respect for myself.' },
  { text: 'All in all, I am inclined to think that I am a failure.' },
  { text: 'I take a positive attitude toward myself.' }
]

const scaleLabels = ['Strongly agree', 'Agree', 'Disagree', 'Strongly disagree']

// Reverse-scored items (1-based index: 2,5,6,8,9)
const reverseScored = [1, 4, 5, 7, 8]

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
      score += 5 - val // reverse scoring (1<->4, 2<->3)
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
  if (totalScore.value >= 30) return 'High self-esteem'
  if (totalScore.value >= 20) return 'Moderate self-esteem'
  return 'Low self-esteem'
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
