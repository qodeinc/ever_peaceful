<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">
          Neff’s Self-Compassion Scale (Short-Form)
        </h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">
          This 12-item short form measures how you typically respond to feelings of failure or difficulty—with kindness, balance, or judgment.
        </p>
        <div class="text-sm text-gray-400">12 questions · 4–6 minutes</div>
      </div>

      <!-- Instructions -->
      <div class="mb-16 pb-16 border-b border-gray-200">
        <h3 class="text-sm uppercase tracking-wider text-gray-500 mb-6">
          Instructions
        </h3>
        <p class="text-base text-gray-600 leading-relaxed mb-4">
          Please respond to each statement by selecting how often you behave in the stated manner.  
          Some items are reverse-scored.
        </p>

        <div class="flex flex-wrap gap-4 text-gray-700 text-sm mt-6">
          <span><span class="text-emerald-600 font-semibold">1</span> = Never</span>
          <span><span class="text-emerald-600 font-semibold">2</span> = Rarely</span>
          <span><span class="text-emerald-600 font-semibold">3</span> = Sometimes</span>
          <span><span class="text-emerald-600 font-semibold">4</span> = Often</span>
          <span><span class="text-emerald-600 font-semibold">5</span> = Always</span>
        </div>
      </div>

      <!-- Progress Bar -->
      <div class="mb-16">
        <div class="flex items-center gap-2">
          <div
            v-for="i in 12"
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredQuestions >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">
          Question {{ Math.min(answeredQuestions + 1, 12) }} of 12
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
            <p class="text-gray-500">Select how often this applies to you.</p>
          </div>

          <!-- Options -->
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
          <div class="text-sm text-gray-400 mb-4">Your Self-Compassion Score</div>
          <div class="p-8 border-2 border-gray-200 rounded-xl text-center">
            <div class="text-6xl font-light text-gray-900 mb-4">
              {{ totalMean.toFixed(2) }}
            </div>
            <div class="text-lg text-gray-500">
              {{ interpretation }}
            </div>
          </div>
        </div>

        <!-- Subscales -->
        <div class="grid sm:grid-cols-2 gap-8 mb-20">
          <div
            v-for="(value, key) in subscales"
            :key="key"
            class="p-6 border border-gray-200 rounded-xl"
          >
            <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ key }}</h3>
            <p class="text-3xl text-emerald-600 font-light">
              {{ value.toFixed(2) }}
            </p>
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
            The Self-Compassion Scale measures how kind and balanced you are toward yourself in difficult times.  
            Higher scores indicate greater self-compassion. Subscale means reflect your tendencies across domains such as self-kindness, common humanity, and mindfulness.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

const questions = [
  { text: 'When I fail at something important to me, I become consumed by feelings of inadequacy.', reverse: true },
  { text: 'I try to be understanding and patient towards those aspects of my personality I don’t like.' },
  { text: 'When something painful happens, I try to take a balanced view of the situation.' },
  { text: 'When I’m feeling down, I tend to feel like most other people are probably happier than I am.', reverse: true },
  { text: 'I try to see my failings as part of the human condition.' },
  { text: 'When I’m going through a very hard time, I give myself the caring and tenderness I need.' },
  { text: 'When something upsets me, I try to keep my emotions in balance.' },
  { text: 'When I fail at something that’s important to me, I tend to feel alone in my failure.', reverse: true },
  { text: 'When I’m feeling down, I tend to obsess and fixate on everything that’s wrong.', reverse: true },
  { text: 'When I feel inadequate in some way, I try to remind myself that feelings of inadequacy are shared by most people.' },
  { text: 'I’m disapproving and judgmental about my own flaws and inadequacies.', reverse: true },
  { text: 'I’m intolerant and impatient towards those aspects of my personality I don’t like.', reverse: true }
]

const scaleLabels = ['Never', 'Rarely', 'Sometimes', 'Often', 'Always']

// Category groupings
const subscaleMap = {
  'Self-Kindness': [1, 5],
  'Self-Judgment': [10, 11],
  'Common Humanity': [4, 9],
  'Isolation': [3, 7],
  'Mindfulness': [2, 6],
  'Over-Identification': [0, 8]
}

const answers = ref({})
const questionRefs = ref({})
const showResults = ref(false)
const totalMean = ref(0)
const subscales = ref({})

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
  // Reverse-score negative items (R)
  const scored = questions.map((q, i) =>
    q.reverse ? 6 - Number(answers.value[i]) : Number(answers.value[i])
  )

  // Compute subscales
  const subs = {}
  for (const [name, indices] of Object.entries(subscaleMap)) {
    const mean = indices.reduce((sum, i) => sum + scored[i], 0) / indices.length
    subs[name] = mean
  }

  subscales.value = subs
  const total = scored.reduce((a, b) => a + b, 0) / questions.length
  totalMean.value = total
  showResults.value = true

  nextTick(() => {
    document.querySelector('.mt-32.pt-32')?.scrollIntoView({ behavior: 'smooth' })
  })
}

const interpretation = computed(() => {
  if (totalMean.value >= 4) return 'High self-compassion'
  if (totalMean.value >= 3) return 'Moderate self-compassion'
  return 'Low self-compassion'
})

const resetAssessment = () => {
  answers.value = {}
  showResults.value = false
  totalMean.value = 0
  subscales.value = {}
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log('Saved results:', { total: totalMean.value, subscales: subscales.value })
  alert('Results saved successfully!')
}
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>
