<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-4xl mx-auto px-6 py-16">
      <!-- Header -->
      <div class="mb-20">
        <h1 class="text-5xl font-light text-gray-900 mb-4 tracking-tight">
          Eysenck’s Personality Inventory (EPI)
        </h1>
        <p class="text-lg text-gray-500 mb-8 max-w-2xl">
          Measures Extroversion/Introversion, Neuroticism, and Social Desirability (Lie scale).  
          Please answer <span class="font-medium text-gray-900">Yes</span> or <span class="font-medium text-gray-900">No</span> to each question honestly.
        </p>
        <div class="text-sm text-gray-400">57 questions · ~5 minutes</div>
      </div>

      <!-- Progress Indicator -->
      <div class="mb-16">
        <div class="flex items-center gap-2">
          <div
            v-for="i in totalQuestions"
            :key="i"
            :class="[
              'h-1 flex-1 transition-all duration-500',
              answeredCount >= i ? 'bg-emerald-600' : 'bg-gray-200'
            ]"
          />
        </div>
        <div class="text-sm text-gray-500 mt-3">
          Question {{ Math.min(answeredCount + 1, totalQuestions) }} of {{ totalQuestions }}
        </div>
      </div>

      <!-- Questions -->
      <form @submit.prevent="calculateScores" class="space-y-16">
        <div
          v-for="(q, i) in questions"
          :key="i"
          :ref="el => setRef(i, el)"
          class="scroll-mt-32"
        >
          <div class="mb-8">
            <div class="text-sm text-gray-400 mb-3">Question {{ i + 1 }}</div>
            <h2 class="text-2xl font-light text-gray-900 mb-2 leading-snug">{{ q }}</h2>
          </div>

          <div class="space-y-3">
            <label
              :class="[
                'group block p-5 border-2 cursor-pointer transition-all duration-200',
                answers[i] === 'Yes'
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                class="sr-only"
                value="Yes"
                v-model="answers[i]"
                required
                @change="onAnswer(i)"
              />
              <div class="flex items-center justify-between">
                <span class="text-lg text-gray-900">Yes</span>
                <div
                  :class="[
                    'w-5 h-5 border-2 rounded-full transition-all',
                    answers[i] === 'Yes'
                      ? 'border-emerald-600 bg-emerald-600'
                      : 'border-gray-300 group-hover:border-gray-400'
                  ]"
                >
                  <div
                    v-if="answers[i] === 'Yes'"
                    class="w-full h-full rounded-full bg-white scale-[0.4]"
                  />
                </div>
              </div>
            </label>

            <label
              :class="[
                'group block p-5 border-2 cursor-pointer transition-all duration-200',
                answers[i] === 'No'
                  ? 'border-emerald-600 bg-emerald-50/50'
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <input
                type="radio"
                class="sr-only"
                value="No"
                v-model="answers[i]"
                required
                @change="onAnswer(i)"
              />
              <div class="flex items-center justify-between">
                <span class="text-lg text-gray-900">No</span>
                <div
                  :class="[
                    'w-5 h-5 border-2 rounded-full transition-all',
                    answers[i] === 'No'
                      ? 'border-emerald-600 bg-emerald-600'
                      : 'border-gray-300 group-hover:border-gray-400'
                  ]"
                >
                  <div
                    v-if="answers[i] === 'No'"
                    class="w-full h-full rounded-full bg-white scale-[0.4]"
                  />
                </div>
              </div>
            </label>
          </div>
        </div>

        <!-- Submit -->
        <div
          v-if="answeredCount === totalQuestions"
          class="pt-12 border-t border-gray-200"
        >
          <button
            type="submit"
            class="w-full bg-emerald-600 text-white py-5 text-lg hover:bg-emerald-700 transition-colors"
          >
            View Results
          </button>
        </div>
      </form>

      <!-- Results -->
      <div v-if="showResults" class="mt-32 pt-32 border-t border-gray-200">
        <div class="mb-20">
          <div class="text-sm text-gray-400 mb-4">Your Results</div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
            <div class="p-6 border-2 border-gray-200">
              <div class="text-6xl font-light text-gray-900">{{ eScore }}</div>
              <div class="text-gray-500 mt-2">E (Extroversion)</div>
              <div class="text-sm text-gray-400 mt-1">Out of 24</div>
            </div>
            <div class="p-6 border-2 border-gray-200">
              <div class="text-6xl font-light text-gray-900">{{ nScore }}</div>
              <div class="text-gray-500 mt-2">N (Neuroticism)</div>
              <div class="text-sm text-gray-400 mt-1">Out of 24</div>
            </div>
            <div class="p-6 border-2 border-gray-200">
              <div class="text-6xl font-light text-gray-900">{{ lScore }}</div>
              <div class="text-gray-500 mt-2">Lie Scale</div>
              <div class="text-sm text-gray-400 mt-1">Out of 9</div>
            </div>
          </div>
        </div>

        <div class="text-gray-600 max-w-2xl space-y-6">
          <p>
            <span class="font-medium text-gray-900">Interpretation:</span>
            Higher <span class="font-medium">E</span> = more extroverted,
            higher <span class="font-medium">N</span> = more emotionally reactive (neurotic),
            and <span class="font-medium">Lie score</span> ≥ 5 suggests possible social desirability bias.
          </p>
          <p>
            Use this as a reflective tool, not a diagnosis. Personality is complex and situational.
          </p>
        </div>

        <div class="flex gap-4 mt-16">
          <button
            @click="resetForm"
            class="flex-1 py-4 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors"
          >
            Retake
          </button>
          <button
            @click="saveResults"
            class="flex-1 py-4 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors"
          >
            Save Results
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'

// Questions
const questions = [
  "Do you often long for excitement?",
  "Do you often need understanding friends to cheer you up?",
  "Are you usually carefree?",
  "Do you find it very hard to take no for an answer?",
  "Do you stop and think things over before doing anything?",
  "If you say you will do something do you always keep your promise?",
  "Do your moods go up and down?",
  "Do you generally do and say things quickly without stopping to think?",
  "Do you ever feel ‘just miserable’ for no good reason?",
  "Would you do almost anything for a dare?",
  "Do you suddenly feel shy when you want to talk to an attractive stranger?",
  "Once in a while do you lose your temper and get angry?",
  "Do you often do things on the spur of the moment?",
  "Do you often worry about things you should have done or said?",
  "Generally do you prefer reading to meeting people?",
  "Are your feelings rather easily hurt?",
  "Do you like going out a lot?",
  "Do you occasionally have thoughts and ideas that you would not like other people to know about?",
  "Are you sometimes bubbling over with energy and sometimes very sluggish?",
  "Do you prefer to have few but special friends?",
  "Do you daydream a lot?",
  "When people shout at you do you shout back?",
  "Are you often troubled about feelings of guilt?",
  "Are all your habits good and desirable ones?",
  "Can you usually let yourself go and enjoy yourself a lot at a lively party?",
  "Would you call yourself tense or ‘highly strung’?",
  "Do other people think of you as being very lively?",
  "After you have done something important, do you come away feeling you could have done better?",
  "Are you mostly quiet when you are with other people?",
  "Do you sometimes gossip?",
  "Do ideas run through your head so that you cannot sleep?",
  "If there is something you want to know about, would you rather look it up in a book than talk to someone about it?",
  "Do you get palpitations or thumping in your heart?",
  "Do you like the kind of work that you need to pay close attention to?",
  "Do you get attacks of shaking or trembling?",
  "Would you always declare everything at customs, even if you knew you could never be found out?",
  "Do you hate being with a crowd who play jokes on one another?",
  "Are you an irritable person?",
  "Do you like doing things in which you have to act quickly?",
  "Do you worry about awful things that might happen?",
  "Are you slow and unhurried in the way you move?",
  "Have you ever been late for an appointment or work?",
  "Do you have many nightmares?",
  "Do you like talking to people so much that you never miss a chance of talking to a stranger?",
  "Are you troubled by aches and pains?",
  "Would you be very unhappy if you could not see lots of people most of the time?",
  "Would you call yourself a nervous person?",
  "Of all the people you know, are there some whom you definitely do not like?",
  "Would you say that you were fairly self-confident?",
  "Are you easily hurt when people find fault with you or your work?",
  "Do you find it hard to really enjoy yourself at a lively party?",
  "Are you troubled by feelings of inferiority?",
  "Can you easily get some life into a dull party?",
  "Do you sometimes talk about things you know nothing about?",
  "Do you worry about your health?",
  "Do you like playing pranks on others?",
  "Do you suffer from sleeplessness?"
]

// Scoring Tables
const eYes = [1, 3, 8, 10, 13, 17, 22, 25, 27, 39, 44, 46, 49, 53, 56]
const eNo = [5, 15, 20, 29, 32, 34, 37, 41, 51]
const nYes = [2, 4, 7, 9, 11, 14, 16, 19, 21, 23, 26, 28, 31, 33, 35, 38, 40, 43, 45, 47, 50, 52, 55, 57]
const lYes = [6, 24, 36]
const lNo = [12, 18, 30, 42, 48, 54]

const totalQuestions = questions.length
const answers = ref(Array(totalQuestions).fill('')) // ✅ FIXED
const questionRefs = ref([])
const showResults = ref(false)
const eScore = ref(0)
const nScore = ref(0)
const lScore = ref(0)

const answeredCount = computed(() => answers.value.filter(a => a).length)

const setRef = (i, el) => {
  if (el) questionRefs.value[i] = el
}

const onAnswer = async (i) => {
  await nextTick()
  const next = i + 1
  if (next < totalQuestions) {
    questionRefs.value[next]?.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }
}

const calculateScores = () => {
  eScore.value =
    eYes.filter(i => answers.value[i - 1] === 'Yes').length +
    eNo.filter(i => answers.value[i - 1] === 'No').length

  nScore.value = nYes.filter(i => answers.value[i - 1] === 'Yes').length
  lScore.value =
    lYes.filter(i => answers.value[i - 1] === 'Yes').length +
    lNo.filter(i => answers.value[i - 1] === 'No').length

  showResults.value = true
  setTimeout(() => window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' }), 200)
}

const resetForm = () => {
  answers.value = Array(totalQuestions).fill('')
  showResults.value = false
  eScore.value = 0
  nScore.value = 0
  lScore.value = 0
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const saveResults = () => {
  console.log({ eScore: eScore.value, nScore: nScore.value, lScore: lScore.value })
  alert('Results saved successfully!')
}
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
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
