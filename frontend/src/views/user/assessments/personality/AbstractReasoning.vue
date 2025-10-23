<template>
  <div class="min-h-screen bg-white">
    <div class="max-w-7xl mx-auto flex gap-8 px-6 py-12">
      <!-- Left: PDF viewer (fixed) -->
      <aside class="w-1/2 h-screen sticky top-0 bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
        <iframe
          :src="pdfFileUrl"
          class="w-full h-full border-0"
          title="Abstract Reasoning Test PDF"
        ></iframe>
      </aside>

      <!-- Right: Questions (scrollable) -->
      <main class="w-1/2 h-screen overflow-y-auto px-6 py-8">
        <div class="max-w-2xl mx-auto">
          <!-- Header -->
          <div class="mb-8">
            <h1 class="text-4xl font-light text-gray-900 mb-3 tracking-tight">Abstract Reasoning Test</h1>
            <p class="text-lg text-gray-500 mb-4 max-w-xl">
              25 multiple-choice questions. Choose A, B, C, or D. Clicking an answer moves you to the next question.
            </p>

            <!-- Progress bar -->
            <div class="mb-4">
              <div class="flex items-center gap-2">
                <div
                  v-for="i in questions.length"
                  :key="i"
                  :class="[
                    'h-1 flex-1 transition-all duration-300 rounded',
                    answeredCount >= i ? 'bg-emerald-600' : 'bg-gray-200'
                  ]"
                />
              </div>
              <div class="text-sm text-gray-500 mt-2">
                Question {{ Math.min(currentIndex + 1, questions.length) }} of {{ questions.length }}
              </div>
            </div>
          </div>

          <!-- Questions list -->
          <form @submit.prevent="finishTest" class="space-y-10">
            <div
              v-for="(q, idx) in questions"
              :key="q.id"
              :ref="el => setQuestionRef(idx, el)"
              class="scroll-mt-24"
            >
              <h2 class="text-2xl font-light text-gray-900 mb-4">Question {{ idx + 1 }}</h2>

              <!-- Options A–D -->
              <div class="grid grid-cols-1 gap-3">
                <label
                  v-for="opt in q.options"
                  :key="opt.key"
                  class="group block p-4 border-2 rounded-xl cursor-pointer transition-all duration-200"
                  :class="answers[idx] === opt.key ? 'border-emerald-600 bg-emerald-50/50' : 'border-gray-200 hover:border-gray-400'"
                >
                  <input
                    class="sr-only"
                    type="radio"
                    :name="`q-${idx}`"
                    :value="opt.key"
                    v-model="answers[idx]"
                    @change="onAnswer(idx)"
                    required
                  />
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                      <div class="w-9 h-9 flex items-center justify-center rounded-md bg-gray-100 font-semibold text-gray-800">
                        {{ opt.key }}
                      </div>
                      <div class="text-base text-gray-900">{{ opt.text }}</div>
                    </div>
                    <div :class="[
                        'w-5 h-5 border-2 rounded-full transition-all',
                        answers[idx] === opt.key ? 'border-emerald-600 bg-emerald-600' : 'border-gray-300 group-hover:border-gray-400'
                      ]"
                    >
                      <div v-if="answers[idx] === opt.key" class="w-full h-full rounded-full bg-white scale-[0.4]" />
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <!-- Submit -->
            <div v-if="answeredCount === questions.length" class="pt-8 border-t border-gray-200">
              <button
                type="submit"
                class="w-full bg-emerald-600 text-white py-4 text-lg hover:bg-emerald-700 transition-colors rounded-lg"
              >
                Finish & View Results
              </button>
            </div>
          </form>

          <!-- Results -->
          <section v-if="showResults" class="mt-12">
            <div class="mb-6">
              <h3 class="text-2xl font-medium text-gray-900">Summary</h3>
              <p class="text-gray-600 mt-2">You answered {{ answeredCount }} / {{ questions.length }} questions.</p>
            </div>

            <div class="p-4 border rounded-lg bg-gray-50">
              <p class="text-gray-700">Results processing placeholder — implement scoring or next steps here.</p>
            </div>

            <div class="flex gap-4 mt-6">
              <button @click="reset" class="flex-1 py-3 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors">
                Retake
              </button>
              <button @click="save" class="flex-1 py-3 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">
                Save Answers
              </button>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick, computed } from 'vue'
import pdfFile from '@/assets/docs/Abstract Reasoning Test 4.pdf'

const pdfFileUrl = pdfFile

const questions = Array.from({ length: 25 }, (_, i) => ({
  id: i + 1,
  options: [
    { key: 'A', text: 'Option A' },
    { key: 'B', text: 'Option B' },
    { key: 'C', text: 'Option C' },
    { key: 'D', text: 'Option D' }
  ]
}))

const answers = ref(Array(questions.length).fill(null))
const questionRefs = ref([])
const currentIndex = ref(0)
const showResults = ref(false)

const answeredCount = computed(() => answers.value.filter(a => a !== null).length)

const setQuestionRef = (idx, el) => {
  if (el) questionRefs.value[idx] = el
}

const onAnswer = async (idx) => {
  await nextTick()
  const next = idx + 1
  if (next < questions.length && questionRefs.value[next]) {
    questionRefs.value[next].scrollIntoView({ behavior: 'smooth', block: 'center' })
    currentIndex.value = next
  } else if (answeredCount.value === questions.length) {
    setTimeout(() => {
      showResults.value = true
      const main = document.querySelector('main')
      if (main) main.scrollTo({ top: main.scrollHeight, behavior: 'smooth' })
    }, 250)
  }
}

const finishTest = () => {
  showResults.value = true
}

const reset = () => {
  answers.value = Array(questions.length).fill(null)
  showResults.value = false
  currentIndex.value = 0
  questionRefs.value[0]?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

const save = () => {
  console.log('Saved answers:', answers.value)
  alert('Answers saved (placeholder).')
}
</script>

<style scoped>
html { scroll-behavior: smooth; }
button:active, label:active { transform: translateY(1px); }
input[type="radio"]:checked + div .scale-\[0\.4\] {
  animation: radioCheck 0.18s ease-out;
}
@keyframes radioCheck {
  0% { transform: scale(0); }
  50% { transform: scale(0.5); }
  100% { transform: scale(0.4); }
}
</style>