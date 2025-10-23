<template>
  <div class="h-screen bg-white flex flex-col">
    <!-- Header -->
    <div class="border-b border-gray-200 bg-white">
      <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-light text-gray-900 tracking-tight">Abstract Reasoning Test</h1>
            <p class="text-sm text-gray-500 mt-1">25 multiple-choice questions</p>
          </div>
          <div class="flex items-center gap-3">
            <div class="text-right">
              <p class="text-sm text-gray-500">Progress</p>
              <p class="text-2xl font-semibold text-gray-900">{{ answeredCount }}/{{ questions.length }}</p>
            </div>
            <div class="relative w-16 h-16">
              <svg class="transform -rotate-90 w-full h-full" viewBox="0 0 36 36">
                <path
                  class="text-gray-200"
                  stroke="currentColor"
                  stroke-width="3"
                  fill="none"
                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <path
                  class="text-emerald-600 transition-all duration-300"
                  stroke="currentColor"
                  stroke-width="3"
                  fill="none"
                  :stroke-dasharray="`${(answeredCount / questions.length) * 100}, 100`"
                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                />
              </svg>
              <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-xs font-semibold text-gray-900">{{ Math.round((answeredCount / questions.length) * 100) }}%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden">
      <!-- Left: PDF Viewer (Fixed) -->
      <div class="w-1/2 border-r border-gray-200 bg-gray-50 flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-white">
          <div class="flex items-center gap-2">
            <span class="text-sm font-medium text-gray-900">Question Reference</span>
          </div>
          <div class="flex items-center gap-2">
            <button
              @click="zoomOut"
              class="p-2 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
              title="Zoom Out"
            >
              <ZoomOut class="w-4 h-4" />
            </button>
            <span class="text-xs text-gray-500 min-w-[3rem] text-center">{{ zoomLevel }}%</span>
            <button
              @click="zoomIn"
              class="p-2 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
              title="Zoom In"
            >
              <ZoomIn class="w-4 h-4" />
            </button>
          </div>
        </div>
        <div class="flex-1 overflow-hidden">
          <iframe
            :src="pdfFileUrl"
            :style="{ transform: `scale(${zoomLevel / 100})`, transformOrigin: 'top left', width: `${10000 / zoomLevel}%`, height: `${10000 / zoomLevel}%` }"
            class="border-0"
            title="Abstract Reasoning Test PDF"
          ></iframe>
        </div>
      </div>

      <!-- Right: Questions (Scrollable) -->
      <div class="w-1/2 overflow-y-auto">
        <div class="px-8 py-8">
          <!-- Instructions -->
          <div class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <Info class="w-5 h-5 text-blue-600" />
              </div>
              <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Instructions</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                  <li>• Choose A, B, C, or D for each question</li>
                  <li>• Clicking an answer automatically saves and moves to the next question</li>
                  <li>• Review the PDF on the left for reference</li>
                  <li>• Submit when all questions are answered</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Questions List -->
          <form @submit.prevent="finishTest" class="space-y-8">
            <div
              v-for="(q, idx) in questions"
              :key="q.id"
              :ref="el => setQuestionRef(idx, el)"
              class="scroll-mt-8"
            >
              <!-- Question Header -->
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Question {{ idx + 1 }}</h2>
                <span
                  v-if="answers[idx]"
                  class="flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full"
                >
                  <CheckCircle class="w-3.5 h-3.5" />
                  Answered
                </span>
              </div>

              <!-- Options -->
              <div class="space-y-3">
                <label
                  v-for="opt in q.options"
                  :key="opt.key"
                  class="group block relative"
                >
                  <input
                    class="peer sr-only"
                    type="radio"
                    :name="`q-${idx}`"
                    :value="opt.key"
                    v-model="answers[idx]"
                    @change="onAnswer(idx)"
                    required
                  />
                  <div
                    :class="[
                      'flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition-all duration-200',
                      answers[idx] === opt.key 
                        ? 'border-emerald-600 bg-emerald-50 shadow-sm' 
                        : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'
                    ]"
                  >
                    <!-- Option Key -->
                    <div
                      :class="[
                        'w-10 h-10 flex items-center justify-center rounded-lg font-semibold text-sm transition-colors',
                        answers[idx] === opt.key 
                          ? 'bg-emerald-600 text-white' 
                          : 'bg-gray-100 text-gray-700 group-hover:bg-gray-200'
                      ]"
                    >
                      {{ opt.key }}
                    </div>

                    <!-- Option Text -->
                    <div class="flex-1 text-sm text-gray-900">
                      {{ opt.text }}
                    </div>

                    <!-- Radio Circle -->
                    <div
                      :class="[
                        'w-5 h-5 border-2 rounded-full flex items-center justify-center transition-all',
                        answers[idx] === opt.key 
                          ? 'border-emerald-600 bg-emerald-600' 
                          : 'border-gray-300 group-hover:border-gray-400'
                      ]"
                    >
                      <div
                        v-if="answers[idx] === opt.key"
                        class="w-2 h-2 rounded-full bg-white"
                      />
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <!-- Submit Button -->
            <div v-if="answeredCount === questions.length" class="pt-6 border-t border-gray-200">
              <button
                type="submit"
                class="w-full flex items-center justify-center gap-2 bg-emerald-600 text-white py-4 text-base font-medium hover:bg-emerald-700 transition-colors rounded-xl"
              >
                <Send class="w-5 h-5" />
                Finish & View Results
              </button>
            </div>
          </form>

          <!-- Results Section -->
          <section v-if="showResults" class="mt-12 pb-8">
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-8">
              <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center">
                  <Trophy class="w-8 h-8 text-white" />
                </div>
                <div>
                  <h3 class="text-2xl font-semibold text-gray-900">Test Complete!</h3>
                  <p class="text-gray-600 mt-1">You've answered all {{ questions.length }} questions</p>
                </div>
              </div>

              <!-- Score Display -->
              <div class="bg-white rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-sm font-medium text-gray-600">Completion</span>
                  <span class="text-2xl font-bold text-emerald-600">100%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                  <div class="bg-emerald-600 h-3 rounded-full transition-all duration-500" style="width: 100%"></div>
                </div>
              </div>

              <!-- Summary Stats -->
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                      <CheckCircle class="w-5 h-5 text-blue-600" />
                    </div>
                    <div>
                      <p class="text-xs text-gray-500">Answered</p>
                      <p class="text-lg font-semibold text-gray-900">{{ answeredCount }}/{{ questions.length }}</p>
                    </div>
                  </div>
                </div>
                <div class="bg-white rounded-xl p-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                      <Clock class="w-5 h-5 text-purple-600" />
                    </div>
                    <div>
                      <p class="text-xs text-gray-500">Time</p>
                      <p class="text-lg font-semibold text-gray-900">--:--</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-3">
                <button
                  @click="reset"
                  class="flex-1 flex items-center justify-center gap-2 py-3 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors rounded-xl font-medium"
                >
                  <RotateCcw class="w-4 h-4" />
                  Retake Test
                </button>
                <button
                  @click="save"
                  class="flex-1 flex items-center justify-center gap-2 py-3 bg-emerald-600 text-white hover:bg-emerald-700 transition-colors rounded-xl font-medium"
                >
                  <Save class="w-4 h-4" />
                  Save Results
                </button>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick, computed } from 'vue'
import { 
  ZoomIn, ZoomOut, Info, CheckCircle, 
  Send, Trophy, Clock, RotateCcw, Save 
} from 'lucide-vue-next'

// Replace with actual PDF path
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
const zoomLevel = ref(100)

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
      const container = questionRefs.value[0]?.closest('.overflow-y-auto')
      if (container) container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' })
    }, 250)
  }
}

const finishTest = () => {
  showResults.value = true
  const container = questionRefs.value[0]?.closest('.overflow-y-auto')
  if (container) {
    setTimeout(() => {
      container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' })
    }, 100)
  }
}

const reset = () => {
  answers.value = Array(questions.length).fill(null)
  showResults.value = false
  currentIndex.value = 0
  const container = questionRefs.value[0]?.closest('.overflow-y-auto')
  if (container) container.scrollTo({ top: 0, behavior: 'smooth' })
}

const save = () => {
  console.log('Saved answers:', answers.value)
  alert('Answers saved successfully!')
}

const zoomIn = () => {
  if (zoomLevel.value < 150) zoomLevel.value += 10
}

const zoomOut = () => {
  if (zoomLevel.value > 50) zoomLevel.value -= 10
}
</script>

<style scoped>
/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Smooth transitions */
button, label {
  transition: all 0.2s ease;
}

button:active, label:active {
  transform: translateY(1px);
}

/* Radio check animation */
@keyframes radioCheck {
  0% { transform: scale(0); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}

.peer:checked ~ div .w-2 {
  animation: radioCheck 0.2s ease-out;
}
</style>